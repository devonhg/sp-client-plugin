<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
 * Plugin Name:       Devons Tools - Client Projects
 * Plugin URI:        http://dhgodfrey.net
 * Description:       This is a plugin for private client pages, very useful in interacting with clients for project development. 
 * Version:           v1.1.3
 * Author:            Devon Godfrey
 * Author URI:        http://playfreygames.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/devonhg/sp-client-plugin

	*IMPORTANT*
	do a "find/replace" accross the directory for "DHG_CLI" and replace
	with your plugin name. 

	Plugin slug: DHG_CLI

*/

//Include the core class of the post type api
    include_once('pt-api/class-core.php');
    register_activation_hook( __FILE__, 'DHG_CLI_ptapi_activate' );

/********************
* Client Post Type
********************/
    //Create Post-Type Object - Client Pages
        $pt_client = new DHG_CLI_post_type( "Client Projects", "Client Project", "Add private pages for clients here.", "", true, null, false ); 

    //List the songs associated with client. 
        function CLIENT_list_songs( $quer = null ){
            $post = DHG_CLI_func::get_post( $quer );
            global $pt_music_client_tax;

            $out = "";

            $term_ex = term_exists( $post->post_title, $pt_music_client_tax->tax_slug );
            $slug = strtolower( str_replace(" ", "-", $post->post_title ) );

            if( $term_ex ){
                $out .= "<div class='CLIENT_songs'>";
                    $out .= "<h2>Project Songs</h2>";
                    $out .= do_shortcode( "[client_songs_sc cats=" . $slug . " ]" );
                $out .= "</div>";
            }

            echo $out; 
        }
        $pt_client->add_hook_single( "CLIENT_list_songs" );



/********************
* Music Post Type
********************/
    //Create Post-Type Object - Music
        $pt_music = new DHG_CLI_post_type( "Songs", "Song", "A post-type for tracking songs." );

        $pt_music->remove_hook_single( array("DHG_CLI_pt_pcs",'pc_cats') );

    //Add client taxonomy for music
        $pt_music_client_tax = $pt_music->reg_tax( "Clients", "Client" );
        $pt_music_cat_tax = $pt_music->reg_tax( "Categories", "Category" );

    //Add music meta
        $pt_music_meta_song = $pt_music->reg_meta("Song File", "Add your song file here.", true, "media");

    //The song bar
        function MUSIC_song_piece( $quer = null ){
            $post = DHG_CLI_func::get_post( $quer );
            global $pt_music_meta_song;

            $link = $pt_music_meta_song->get_val();
            $out = "";

            //Make sure it doesn't contain nothing.
            if ( $link != "" || $fb_link != null ){
                //Make sure it's a sound file
                if( strpos($link,'.mp3') !== false || strpos($link,'.m4a') !== false || strpos($link,'.wav') !== false ){
                    //Get file type
                    if( strpos($link,'.mp3') !== false ){ $ftype = "mp3"; }
                    if( strpos($link,'.m4a') !== false ){ $ftype = "m4a"; }
                    if( strpos($link,'.wav') !== false ){ $ftype = "wav"; }
                    $out .= "<div class='music_song'>";   
                        $out .= do_shortcode( "[audio " . $ftype . "=" . $link . "]" );
                    $out .= "</div>";
                }
            }

            echo $out; 
        }

    //The leave feedback link
        function MUSIC_leave_feedback( $quer = null ){
            $post = DHG_CLI_func::get_post( $quer );

            $link = get_permalink( $post->ID );
            $out = "";

            $out .= "<a target='_blank' class='music_feedback' href='" . $link . "' title='Leave feedback for " . $post->post_title . "' >";
                $out .= "<h3>";
                    $out .= "Leave Feedback"; 
                $out .= "</h3>";
            $out .= "</a>";

            echo $out; 
        }

    //Hook in the functions
        $pt_music->remove_hook_sc( array("DHG_CLI_pt_pcs",'pc_excerpt') );
        $pt_music->add_hook_sc( array("DHG_CLI_pt_pcs",'pc_content') );
        $pt_music->add_hook_single( "MUSIC_song_piece" );
        $pt_music->add_hook_sc( "MUSIC_song_piece" );
        $pt_music->add_hook_sc( "MUSIC_leave_feedback" );

/********************
* General Post Type
********************/
    //Set the default state of post invisibility for the client post type. 
        add_action( 'post_submitbox_misc_actions' , 'DHG_change_visibility_metabox_value' );
        function DHG_change_visibility_metabox_value(){
            global $post, $pt_client, $pt_music;
            if ($post->post_type != $pt_client->pt_slug && $post->post_type != $pt_music->pt_slug)
                return;
            //$post->post_password = '';
            if ( $post->post_status == 'auto-draft' ){
                $visibility = 'private';
                $visibility_trans = __('Private');
                $post->comment_status = 'open';
                ?>
                <script type="text/javascript">
                    (function($){
                        try {
                            $('#post-visibility-display').text('<?php echo $visibility_trans; ?>');
                            $('#hidden-post-visibility').val('<?php echo $visibility; ?>');
                            $('#visibility-radio-<?php echo $visibility; ?>').attr('checked', true);
                        } catch(err){}
                    }) (jQuery);
                </script>
                <?php
            }
        }
    

    //Edit page title to remove "protected"
        add_filter( 'the_title', 'DHG_CLI_title', 10, 2);
        function DHG_CLI_title($title) {
            global $post;

            if( $post->post_types == $pt_client->pt_slug ){
                $title = str_replace( "Protected: ", "", $title );
            }
            return $title;
        }

    //Enqueue css
        function DHG_css(){
            global $pt_client;
            if ( is_singular( $pt_client->pt_slug ) ){
                wp_enqueue_style( 'DHG_CLI_STYLE', plugins_url("build/styles.min.css", __FILE__) );
            }
        }
        add_action( 'wp_enqueue_scripts', 'DHG_css' );

    //On saving a client page, if a client category does not exists, it makes one. 
        function CLIENT_add_to_cat(){
            global $pt_music_client_tax, $post, $pt_client; 
            if ($post->post_type != $pt_client->pt_slug) return;

            //Check if term exists
            $term_ex = term_exists( $post->post_title, $pt_music_client_tax->tax_slug );  

            if ( !$term_ex ){
                wp_insert_term( $post->post_title, $pt_music_client_tax->tax_slug );
            } 
        }
        add_action('save_post', 'CLIENT_add_to_cat');


    //On saving a client project, it sets any songs passwords to its own. 
        function CLIENT_set_songs_pw(){
            global $pt_music, $post, $pt_client, $pt_music_client_tax; 
            if ($post->post_type != $pt_client->pt_slug) return;

            $tax = $pt_music_client_tax->tax_slug;
            $d_term = get_term_by( "slug", $post->post_title, $tax );
            $m_posts = DHG_get_post_type_items( $pt_music->pt_slug );

            if ( is_array( $m_posts ) ){
                foreach( $m_posts as $m_post ){
                    //Check if has term
                    $has_term = has_term( $d_term->term_id ,$tax, $m_post->ID );
                    if ( $has_term ){

                        if ( ! wp_is_post_revision( $post->ID) ){
                            remove_action('save_post', 'CLIENT_set_songs_pw');
                            $post_update = array(
                                "ID" => $m_post->ID,
                                "post_status" => $post->post_status,
                                "post_password" => $post->post_password,
                            );
                            wp_update_post( $post_update );
                            add_action('save_post', 'CLIENT_set_songs_pw');
                            break; 
                        }
                    } 
                }
            }
        }
        add_action('save_post', 'CLIENT_set_songs_pw');

    //On saving a song, it checks the client project, and updates itself. 
        function MUSIC_set_songs_pw(){
            global $pt_music, $post, $pt_client, $pt_music_client_tax; 
            if ($post->post_type != $pt_music->pt_slug) return;  

            $tax = $pt_music_client_tax->tax_slug;
            $d_term = wp_get_post_terms( $post->ID, $tax );
            $m_posts = DHG_get_post_type_items( $pt_client->pt_slug );            

            if ( is_array() ){
                foreach( $m_posts as $m_post ){
                    $pt = preg_replace("/[^A-Za-z0-9]/", "", $m_post->post_title); 
                    $tt = preg_replace("/[^A-Za-z0-9]/", "", $d_term[0]->name); 

                    if( $pt == $tt ){
                        remove_action('save_post', 'MUSIC_set_songs_pw');
                        $post_update = array(
                            "ID" => $post->ID,
                            "post_status" => $m_post->post_status,
                            "post_password" => $m_post->post_password,
                        );
                        wp_update_post( $post_update );
                        add_action('save_post', 'MUSIC_set_songs_pw');
                        break; 
                    }
                }
            }
        }

    add_action( 'save_post' , 'MUSIC_set_songs_pw' );



/**********************
* External Functions
**********************/
//Function by sean barton : http://www.sean-barton.co.uk/2013/07/array-custom-post-type-item-objects-wordpress/#.ViO3LStcigk
//Gets all the items of a post-type and sorts them into an array. 
function DHG_get_post_type_items($post_type, $args_extended=array()) {
        global $post;
        $old_post = $post;
        $return = false;

        $args = array(
            'post_type'=>$post_type
            , 'showposts'=>-1
            , 'order'=>'ASC'
            , 'orderby'=>'title'
        );

        if ($args && count($args_extended)) {
            $args = array_merge($args, $args_extended);
        }

        query_posts($args);

        if (have_posts()) {
            global $post;
            $return = array();

            while (have_posts()) {
                the_post();
                $return[get_the_ID()] = $post;
            }
        }

        wp_reset_query();
        $post = $old_post;

        return $return;     
    }