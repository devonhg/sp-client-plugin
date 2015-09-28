<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
 * Plugin Name:       Devons Tools - Client Projects
 * Plugin URI:        http://dhgodfrey.net
 * Description:       This is a plugin for private client pages, very useful in interacting with clients for project development. 
 * Version:           v1.0.0
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

//Create Post-Type Object
    $pt_client = new DHG_CLI_post_type( "Client Projects", "Client Project", "Add private pages for clients here." ); 

//Set posts to private by default
/*add_action( 'transition_post_status', 'DHG_CLI_post_status_new', 10, 3 );
    function DHG_CLI_post_status_new( $new_status, $old_status, $post ) { 
        global $pt_client;
        if ( $post->post_type == $pt_client->pt_slug && $old_status == 'new' ) {
            //$post->post_status = 'private';
            $post_up = array(
                "ID" => $post->ID,
                "post_status" => 'private',
            );
            wp_update_post( $post_up );
        }
    } */

    
    add_action( 'post_submitbox_misc_actions' , 'DHG_change_visibility_metabox_value' );
    function DHG_change_visibility_metabox_value(){
        global $post, $pt_client;
        if ($post->post_type != $pt_client->pt_slug)
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
        //print_r( $post );
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
