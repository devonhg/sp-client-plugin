<?php
if ( ! defined( 'WPINC' ) ) { die; }

function dhg_debug( $entry , $value ){
    $f = get_home_path() . "debug.txt"; 
    if ( file_exists( $f ) ){
        $fc = file_get_contents( $f );

        //Convert string to associative array
        $mstr = explode("|",$fc);
        $a = array();
        foreach($mstr as $nstr )
        {   
            if (strpos($nstr, '=') !== FALSE ){
                $narr = explode("=",$nstr);
                $narr[0] = trim( str_replace("\x98","",$narr[0]) );
                $ytr[1] = trim( $narr[1] );
                $a[$narr[0]] =$ytr[1];
            }
        }

        $a[$entry] = $value;

        //Convert back to string
        $fo = http_build_query($a, '', '|'."\n");
        
        file_put_contents( $f , $fo );
    }else{
        file_put_contents( $f , $entry . '=' . $value . " | " );
    }
}



class MYPLUGIN_pt_meta {

	var $id;
	var $title;
	var $pt;
	var $desc;
	var $val_key;
	var $met_nonce;
	var $cust_box;
	var $new_field;
	var $type;
	var $options; 

	//The color picker styles to enqeue if neccessary
	/*public function wptuts_add_color_picker_s( $hook ) {
        wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_script( 'my-script-handle',  plugin_dir_url( __FILE__ ) . "colorpicker.js" , array( 'wp-color-picker' ), false, true );
	}*/


	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct($title, $desc, $pt, $type = "text", $options = null) {
		if (is_admin()){
			$this->id = "meta_" . trim(strtolower($title));
			$this->title = $title;
			$this->pt = $pt;
			$this->desc = $desc; 
			$this->val_key = $this->id . "_vkey";
			$this->met_nonce = $this->id . "_nonce";
			$this->cust_box = $this->id . "_custom_box";
			$this->new_field = $this->id . "_new_field";
			$this->type = $type; 
			$this->options = $options; 

			add_action( 'load-post.php', array( $this, 'call_mb' ) );
    		add_action( 'load-post-new.php', array( $this, 'call_mb' ) );

    		if ( $type == "color" ){
    			add_action( "admin_enqueue_scripts", array( $this, "color_style_f" ) );
    		}

		}
	}

	public function color_style_f(){
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_script( 'my-script-handle', plugin_dir_url( __FILE__ ) . "colorpicker.js" , array( 'wp-color-picker' ), false, true );	
	}

	//If the conditions clear for the construct function, 
	public function call_mb() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );		
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = $this->pt; //limit meta box to certain post types
	            if (is_array($this->pt)){
		            if ( in_array( $post_type, $post_types )) {
						add_meta_box(
							$this->id
							,__( $this->title, 'myplugin_textdomain' )
							,array( $this, 'render_meta_box_content' )
							,$post_type
							,'advanced'
							,'high'
						);
		            }
	        }else{
				add_meta_box(
					$this->id
					,__( $this->title, 'myplugin_textdomain' )
					,array( $this, 'render_meta_box_content' )
					,$this->pt
					,'advanced'
					,'high'
				);	        	
	        }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST[$this->met_nonce] ) )
			return $post_id;

		$nonce = $_POST[$this->met_nonce];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, $this->cust_box ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST[$this->new_field] );

		// Update the meta field.
		update_post_meta( $post_id, $this->val_key, $mydata );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( $this->cust_box, $this->met_nonce );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, $this->val_key , true );

		// Display the form, using the current value.

		echo '<label for="' . $this->new_field . '">';
		_e( "<sub>" . $this->desc . "</sub><br>" , 'myplugin_textdomain' );
		echo '</label> ';

		if ( $this->type == "text" ){
			echo '<input type="text" id="' . $this->new_field . '" name="' . $this->new_field . '"';
	                echo ' value="' . esc_attr( $value ) . '" size="25" />';
        }
        if ( $this->type == "radio"){
        	$i = 0; 
        	foreach( $this->options as $option ){
        		if ( (esc_attr( $value ) == esc_attr( $option )) || ( ( $value == null) && ($i == 0) ) ){
					$chk = "checked";
        		}
        		else $chk = "";

				echo '<input type="radio" id="' . $this->new_field . '" name="' . $this->new_field . '"';
		        echo ' value="' . esc_attr( $option ) . '" size="25" ' . $chk . ' />';       
		        echo esc_attr( $option ) . "<br>";
		        $i++;
        	}
        }
        if ( $this->type == "textarea"){
				echo "<textarea name='" . $this->new_field . "'>" . esc_attr( $value ) . "</textarea>";
        }
        if ( $this->type == "color" ){

        	if ( $value == "" ) { $value = "#aaa"; }; 

			echo '<input type="text" class="my-color-field" id="' . $this->new_field . '" name="' . $this->new_field . '"';
	        echo ' value="' . esc_attr( $value ) . '" size="25" />';
        }

	}
}