<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
	This is a static class that contains functions important to the plugin. 
*/


class MYPLUGIN_func{

	public static function print_meta( $ID ){
		$out = "";
		$pst_meta = get_post_custom( $ID ); 
		$out .= "<ul>";
			foreach($pst_meta as $key=>$val){
				if (strpos($key,'_vkey') !== false && ( $key[0] !== "h" && $key[1] !== "_" ) ){
					$out .= "<li>";
						$out .= "<label>" . ucfirst(str_replace(array("meta_", "_vkey") , array("","") , $key)) . "</label>";
						$out .= ' : ' . $val[0] . '<br/>';
					$out .= "</li>";
				}
			} 
		$out .= "</ul>";

		return $out; 
	}


	// Function by David Paulsson, get ID from slug. Modified to also consider post type by Devon Godfrey.  
	// MYPLUGIN_get_id_by_slug('any-page-slug');
	public static function get_id_by_slug($page_slug, $pt) {
		$page = get_page_by_path($page_slug, OBJECT, $pt);
		if ($page) {
			return $page->ID;
		} else {
			return null;
		}
	} 

	//This is a helper function, used to gather the categories for
	//a given post. 
	public static function get_cats( $tax , $post_slug = null, $pt = null ){
	    $output = "";
	    $tax_terms = get_terms( $tax );
	    $tax_info = get_taxonomy( $tax ); 

	    $pID = MYPLUGIN_func::get_id_by_slug( $post_slug, $pt );

	    if ( ! empty( $tax_terms ) ){
	            foreach ($tax_terms as $tax_term){
	            	if (has_term($tax_term, $tax, $pID)){
				        $output .= "<h3>" . $tax_info->labels->name . "</h3>";
				        $output .= "<ul>";
				        break; 
	            	}
	            }

	            foreach ($tax_terms as $tax_term){
	            	if (has_term($tax_term, $tax, $pID)){
	                	$output .= '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $tax)) . '" title="' . sprintf( __( "View all items in %s" ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
	            	}
	            }
	        $output .= "</ul>"; 
	    }

	    return $output;	
	}
}