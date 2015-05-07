<?php
if ( ! defined( 'WPINC' ) ) { die; }

class MYPLUGIN_pt_sc{
    
    static $instances = array(); 
	public $pt;
    public $par; 
    public $name;
    public $desc;
    public $query;

    public function __construct($pt, $par, $name, $desc, $query = "" ){
    
        MYPLUGIN_pt_sc::$instances[] = $this; 
    	$this->pt = $pt;
        $this->par = $par; 
        $this->name = $name; 
        $this->desc = $desc;
        $this->query = $query; 

        add_shortcode( str_replace( " ", "_", $name ), array( $this, 'display_archive_f'));
    }

    public static function action(){
        do_action( $this->par->$name_s . "pt_shortcode" );
    }
    
    public function display_archive_f($atts){

        if ( $this->query == '' ){ $argOut = 'post_type=' . $this->pt; } 
        else { 
            if ( gettype( $this->query ) == 'string' ){
                $argOut = 'post_type=' . $this->pt . "&" . $this->query ; 
            }else if ( gettype( $this->query ) == 'array' ){
                $pt_set = array( 'post_type' => $this->pt );
                $argOut = array_merge( $pt_set, $this->query );
            }
            
        }

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