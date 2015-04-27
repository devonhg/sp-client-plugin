<?php
if ( ! defined( 'WPINC' ) ) { die; }

if ( ! get_theme_support( 'post-thumbnails' )) add_theme_support('post-thumbnails');

class MYPLUGIN_post_type{

    //Properties
        var $name;
        var $name_s;
        var $pt_slug;
        var $s_display = array(
                'isTitle' => true,
                'isFI' => true,
                'isMeta' => true,
                'isContent' => true,
                'isCats' => true,
            );  
        var $a_display = array(
                'isTitle' => true,
                'isFI' => true,
                'isMeta' => true,
                'isContent' => true,
                'isCats' => true,
        );
    //Magic Methods
        public function __construct( $name , $name_s , $default = true ){
            $this->name = $name;
            $this->name_s = $name_s;
            $this->pt_slug = "pt_" . trim(strtolower($name_s));
            new MYPLUGIN_pt_sc($this->pt_slug);//Creates Shortcodes

            add_action( 'init', array($this, 'initiate_cpt'), 0 );

            if ( $default ){
            //Single Actions
                add_action("pt_single" , array("MYPLUGIN_pt_pcs",'pt_title') );
                add_action("pt_single" , array("MYPLUGIN_pt_pcs", 'pt_content' ) );
                add_action("pt_single" , array("MYPLUGIN_pt_pcs", 'pt_fi' ) );
                add_action("pt_single" , array("MYPLUGIN_pt_pcs", 'pt_meta' ) );
                add_action("pt_single" , array("MYPLUGIN_pt_pcs", 'pt_cats' ) );

            //Archive Actions
                add_action("pt_archive" , array("MYPLUGIN_pt_pcs",'pt_title_a'));
                //add_action("pt_archive" , array("MYPLUGIN_pt_pcs",'pt_test') );

                
                //add_action("pt_archive" , array("MYPLUGIN_pt_pcs", 'pt_content' ) );
                //add_action("pt_archive" , array("MYPLUGIN_pt_pcs", 'pt_fi' ) );
                //add_action("pt_archive" , array("MYPLUGIN_pt_pcs", 'pt_meta' ) );
                //add_action("pt_archive" , array("MYPLUGIN_pt_pcs", 'pt_cats' ) );

            //Shortcode Actions
                add_action("pt_shortcode" , array("MYPLUGIN_pt_pcs",'pt_title_a'),10,1);
                add_action("pt_shortcode" , array("MYPLUGIN_pt_pcs", 'pt_content' ),10,1);
                add_action("pt_shortcode" , array("MYPLUGIN_pt_pcs", 'pt_fi' ),10,1);
                add_action("pt_shortcode" , array("MYPLUGIN_pt_pcs", 'pt_meta' ),10,1);
                add_action("pt_shortcode" , array("MYPLUGIN_pt_pcs", 'pt_cats' ),10,1);
            }
        }

    //Register Methods
        public function reg_tax($name, $name_s ){
            $a = new MYPLUGIN_pt_tax($name, $name_s, $this->pt_slug );
            return $a;
        }
        public function reg_meta($title, $desc, $typ = "text", $options = null){
            $a = new MYPLUGIN_pt_meta($title, $desc, $this->pt_slug, $typ, $options );
            return $a;
        }

    //Output Medthods 
        public function single(){

            global $post; 

            if ( $post->post_type == $this->pt_slug ){
                echo MYPLUGIN_func::single( false, $this->s_display , $this->pt_slug , $post->ID );
            }      
        }

        public function archive(){

            global $post; 

            if ( $post->post_type == $this->pt_slug ){
                echo MYPLUGIN_func::single( false, $this->s_display , $this->pt_slug , $post->ID );
            }  
        }


    //Core Methods
        public function initiate_cpt(){

            $name = $this->name;
            $name_s = $this->name_s;
            $pt_slug = $this->pt_slug; 

            $labels = array(
                'name'                => _x($name, 'Post Type General Name'), 
                'singular_name'       => _x($name_s, 'Post Type Singular Name', 'twentythirteen'),
                'menu_name'           => __($name, 'twentythirteen'),
                'parent_item_colon'   => __('Parent ' . $name_s, 'twentythirteen'),
                'all_items'           => __( 'All ' .  $name, 'twentythirteen' ),
                'view_item'           => __( 'View ' . $name_s, 'twentythirteen' ),
                'add_new_item'        => __( 'Add New ' . $name_s, 'twentythirteen' ),
                'add_new'             => __( 'Add New', 'twentythirteen' ),
                'edit_item'           => __( 'Edit ' . $name_s, 'twentythirteen' ),
                'update_item'         => __( 'Update ' . $name_s, 'twentythirteen' ),
                'search_items'        => __( 'Search ' . $name_s, 'twentythirteen' ),
                'not_found'           => __( 'There are currently no '. $name, 'twentythirteen' ),
                'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
            );

            $args = array(
                'label'               => __( $name, 'twentythirteen' ),
                'description'         => __( 'Contains the post data for ' . $name_s . ".", 'twentythirteen' ),
                'labels'              => $labels,
                'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', ),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 5,
                'can_export'          => true,
                'menu_icon'           => "dashicons-media-document",
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
            );
            register_post_type( $pt_slug , $args );
        }
}