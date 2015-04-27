<?php
if ( ! defined( 'WPINC' ) ) { die; }

add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );

class MYPLUGIN_pt_sc{
    
	var $pt;

    //This is the method that actually applies the shortcodes. 
    public function __construct($pt){
    	$this->pt = $pt;

        add_shortcode( $pt . '_archive', array( $this, 'display_archive_f'));
        add_shortcode( $pt . '_single', array( $this, 'display_single_f'));
    }
    
    //This shortcode runs and shows the archive of a 
    //given post type. 
    public function display_archive_f($atts){
        /*extract( shortcode_atts( array(
            'wpargs' => '',
            'title' => true,
            'fi' => true, 
            'meta' => true,
            'content' => true,
            'cats' => true,  
        ), $atts ) );       

        $args = array(
            'isTitle' => ($title == 'true' ),
            'isFI' => ($fi == 'true'),
            'isMeta' => ($meta == 'true'),
            'isContent' => ($content == 'true'),
            'isCats' => ($cats == 'true'), 
        );

    	return MYPLUGIN_func::archive( $args , $wpargs , $this->pt); */

        extract( shortcode_atts( array(
            'wpargs' => '', 
            'pt' => '',
        ), $atts ) );  

        //global $post; 

        if ( $wpargs == '' ){ $wpargs = 'post_type=' . $this->pt; } 

        $out = "";

        $quer = new WP_Query ( $wpargs );

        while ( $quer->have_posts() ) : $quer->the_post();
            ob_start();
            do_action('pt_shortcode' , $quer );
            $out .= ob_get_clean();
        endwhile; 
        wp_reset_postdata();

        //return $out; 

        return $out;

    }

    public function display_single_f($atts){

        extract( shortcode_atts( array(
            'entry' => '',
            'title' => true,
            'fi' => true, 
            'meta' => true,
            'content' => true,
            'cats' => true,  
        ), $atts ) );

        $args = array(
            'isTitle' => ($title == 'true' ),
            'isFI' => ($fi == 'true'),
            'isMeta' => ($meta == 'true'),
            'isContent' => ($content == 'true'),
            'isCats' => ($cats == 'true'), 
        );

        return MYPLUGIN_func::single( false, $args , $this->pt, $entry );

    }

}

