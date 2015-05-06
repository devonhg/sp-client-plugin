<?php
if ( ! defined( 'WPINC' ) ) { die; }

add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );

class MYPLUGIN_pt_sc{
    
	public $pt;
    public $par; 

    public function __construct($pt, $par ){
    	$this->pt = $pt;
        $this->par = $par; 

        add_shortcode( $pt . '_sc', array( $this, 'display_archive_f'));
    }

    public static function action(){
        do_action( $this->par->$name_s . "pt_shortcode" );
    }
    
    public function display_archive_f($atts){

        extract( shortcode_atts( array(
            'wpargs' => '', 
        ), $atts ) );  

        if ( $wpargs == '' ){ $argOut = 'post_type=' . $this->pt; } 
        else { $argOut = 'post_type=' . $this->pt . "&" . $wpargs ; }

        $out = "";

        $this->par->reg_hooks_sc();

        $quer = new WP_Query ( $argOut );

        while ( $quer->have_posts() ) : $quer->the_post();
            ob_start();
            do_action( $this->par->name_s . 'pt_shortcode' , $quer );
            $out .= ob_get_clean();
        endwhile; 

        wp_reset_postdata();

        return $out;

    }
}