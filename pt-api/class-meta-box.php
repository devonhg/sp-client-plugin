<?php
if ( ! defined( 'WPINC' ) ) { die; }


class MYPLUGIN_pt_meta {

	static $instances = array(); 

	//Public Values
		public $title;
		public $pt;
		public $type;
		public $hidden;
		public $options; 
		public $desc;
	//Private Values
		private $val_key;
		private $met_nonce;
		private $cust_box;
		private $new_field;		
		private $id;

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct($title, $desc, $pt, $hide = false , $type = "text", $options = null) {

		MYPLUGIN_pt_meta::$instances[] = $this; 

		//if (is_admin() && basename(__FILE__) == 'post.php' ){

			$valName = str_replace(" " , "-" , trim(strtolower($title)));

			$this->id = "meta_" . substr(strtolower($pt), 3) . "_" . $valName; 

			$this->title = $title; //Title String
			$this->pt = $pt; //Post Type String
			$this->desc = $desc; //Descrption String
			$this->val_key = $this->id . "_vkey"; //Value Key for Instance Array
			$this->met_nonce = $this->id . "_nonce";
			$this->cust_box = $this->id . "_custom_box";
			$this->new_field = $this->id . "_new_field";
			$this->type = $type; 
			$this->options = $options; 

			$this->hidden = $hide; 

			//$this->id_array = explode( "_" , $this->id );//This can be used for the checks on the meta ID. 

			add_action( 'load-post.php', array( $this, 'call_mb' ) );
    		add_action( 'load-post-new.php', array( $this, 'call_mb' ) );

    		if ( $type == "color" ){
    			add_action( "admin_enqueue_scripts", array( $this, "color_style_f" ) );
    		}

    		if ( $type == "link" ){
    			add_action( "admin_enqueue_scripts", array( $this, "color_style_links" ) );
    		}

    		if ( $type == "media" ){
    			add_action( "admin_enqueue_scripts", array( $this, "media_js" ) );
    		}

		}
	//}

	public function get_val( $ID = null ){
		if (!$ID == null){ 
			$meta = get_post_custom( $ID );
		}
		else{ 
			global $post; 
			$meta = get_post_custom( $post->id );
		} 

		foreach( $meta as $key=>$val ){
			if ( $key == $this->val_key ){
				return implode( $val );
			}
		}
	}

	public function color_style_f(){
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_script( 'cp-script', plugin_dir_url( __FILE__ ) . "colorpicker.js" , array( 'wp-color-picker' ), false, true );	
	}

	public function media_js(){
		wp_enqueue_script( 'pt-uploader-script', plugin_dir_url( __FILE__ ) . "uploader-script.js" );
	}

	public function color_style_links(){
		wp_enqueue_script( 'wp-link' );
		wp_enqueue_script( 'link-script', plugin_dir_url( __FILE__ ) . "cmb.js" );
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
		if ( $this->type == "media" || $this->type == "link" ){
			$mydata = esc_url( $_POST[$this->new_field] );			
		}else{
			$mydata = sanitize_text_field( $_POST[$this->new_field] );
		}

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
		//$this->value = $value; 

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

        if ( $this->type == "link" ){

        	if ( $value == null ) { $value = ""; }; 

			echo '<input class="cmb_text_link" type="text" size="25" id="', $this->new_field , '" name="', $this->new_field, '" value="', esc_attr( $value ), '" />';
  			echo '<input class="cmb_link_button button" type="button" value="Get Link" />';
        }

        if ( $this->type == "media" ){
        	if ( $value == null ) { $value = ""; }; 
        	if ( $value !== ""){
        		echo "<div>";
					echo MYPLUGIN_func::media_check( $value );
				echo "</div>";
        	}

			echo "<input type='text' size='25' name=" . $this->new_field . " id=" . $this->new_field  . " value=" . $value . ">";
			echo "<input class='upload_image_button button' type='button' value='Upload Media' >";
        }
	}
}