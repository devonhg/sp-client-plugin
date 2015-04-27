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
	public static function MYPLUGIN_get_id_by_slug($page_slug, $pt) {
		$page = get_page_by_path($page_slug, OBJECT, $pt);
		if ($page) {
			return $page->ID;
		} else {
			return null;
		}
	} 

	/*
		Use this function get a single post, used in the archive function
		as the basic layout. Will take either the post ID or post slug. 

		$archive : Boolean, determines how the post is display, ex: whether
		or not title links to single page. 

		$display : Array/null, an associative array that determines what exactly is outputted
		onto the page. 

		$pt : String, Post type, if within the loop it fetches this automatically. 

		$post_slug : String/Int, Slug or ID of the specified post you want to display, if 
		within the loop it's obtained automatically. 

	*/
	public static function single( $archive = false , $display = null , $pt = null, $post_slug = null ){

		if ( is_array( $display ) ){
			extract( $display, EXTR_PREFIX_SAME, "dsp");
		}else{
			$display == null;
		}

		if ( $display == null ){
			$isTitle = true; 
			$isFI = true;
			$isMeta = true; 
			$isContent = true;
			$isCats = true; 
		}

		//Output variable
		global $post;

		$out = "";

		//Checks if post is set, if so, automatically sets the $pt variable
		if ( isset( $post ) ){
			if ( $pt == null ) $pt = $post->post_type; 
			if ( $post_slug == null ) $post_slug = $post->ID; 
		}

		//Collect the Taxonomies
	    $pt_values = get_object_taxonomies( $pt );

	    //Check if we were given a slug or an id. 
	    if ( is_string($post_slug) ){
	    	$pID = MYPLUGIN_func::MYPLUGIN_get_id_by_slug( $post_slug, $pt );
		}else{
			$pID = $post_slug;
		}
	    
	    //Get Post Object
	    $the_pst = get_post( $pID );

	    $pst_name = (string)$the_pst->post_name;

	    //Check if we are within post.php, and if so don't run this block. 
	    if ( basename($_SERVER['PHP_SELF']) != "post.php" ){

	    	//Get the post classes, implode them into string. 
	    	$classes = implode( " " , get_post_class( "", $pID ));

	    	//Check if archive, and if so change the behavior of the post. 
	    	if ($archive){
	    		$out .= "<article class='clearfix " . $classes . " pt-archive'>";	
	    	}else{
	    		$out .= "<article class='clearfix pt-single " . $classes . "'>";
	    	}

	    		//Title of post
	    		/*
	    		if ($isTitle){
		    		if ($archive == true) $out .= "<a title='" . $the_pst->post_title . "' href='" . get_permalink( $pID ) .  "'>";
			    		$out .= "<h1>" . $the_pst->post_title . "</h1>";
			    	if ($archive == true) $out .= "</a>";
		    	}
		    	*/

		    	//If there is a featured image, display it. 
		    	/*
		    	if ($isFI){
			    	if ( has_post_thumbnail( $pID ) ){
				    	$out .= "<div class='" . "MYPLUGIN-image" . "'>";
				    		$out .= "<a title='" . $the_pst->post_title . "' href='" . get_permalink( $pID ) .  "' alt='" . $the_pst->post_title . "'>" . get_the_post_thumbnail( $pID ) . "</a>"; 
				    	$out .= "</div>";
		   			}
		   		}
		   		*/

		   		//This lists the meta of the post.

		   		/* 

		   		if ($isMeta){
			   		$out .= "<div class='" . "MYPLUGIN-meta" . "'>";
				    		$pst_meta = get_post_custom( $pID ); 
				    		$out .= "<ul>";
					    		foreach($pst_meta as $key=>$val){
					    			if ($key[0] != "_"){
					    				$out .= "<li>";
						    				$out .= "<label>" . ucfirst(str_replace(array("meta_", "_vkey") , array("","") , $key)) . "</label>";
						    				$out .= ' : ' . $val[0] . '<br/>';
						    			$out .= "</li>";
					    			}
					    		} 
				    		$out .= "</ul>";
				    $out .= "</div>";
				}

				*/

				/*
			    //The content, if archive show only excerpt, otherwise show everything. 
			    if ($isContent){
			   		$out .= "<div class='" . "MYPLUGIN-content" . "'>";
			   				//if ( $archive){
			   				//	$out .= $the_pst->post_excerpt;
			   				//}else{
				    			$out .= $the_pst->post_content; 
				    		//}
				    $out .= "</div>";
				}

				*/

				/*

			    //List categories. 
			    if ($isCats){
			    	$out .= "<div class='" . "MYPLUGIN-categories" . "'>";
				    	foreach($pt_values as $tax){
				    		$out .= MYPLUGIN_func::MYPLUGIN_get_cats( $tax , $post_slug, $pt);
				    	}
			    	$out .= "</div>";
		    	}

		    	*/

		    $out .= "</article>";

		    return $out; 
		}
	}	

	/*
		This is a helper function, used to gather the categories for
		a given post. 
	*/
	public static function MYPLUGIN_get_cats( $tax , $post_slug = null, $pt = null ){
	    $output = "";
	    $tax_terms = get_terms( $tax );
	    $tax_info = get_taxonomy( $tax ); 

	    $pID = MYPLUGIN_func::MYPLUGIN_get_id_by_slug( $post_slug, $pt );

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


	//This function is not meant to be used beyond within the plugin
	public static function archive( $args , $wpargs = "" , $pt = null ){

		global $post;

		//Checks if post is set, if so, automatically sets the $pt variable
		if ( isset( $post ) ){
			if ( $pt == null ) $pt = $post->post_type; 
		}

		if ($wpargs == ""){
			$wpargs = "post_type=" . $pt;
		}

	    $out = "";

	    $pt_values = get_object_taxonomies( $pt );

	    if ( basename($_SERVER['PHP_SELF']) != "post.php" ){

	        $the_query = new WP_Query( $wpargs );

	        $out .= "<div class='clearfix'>";

	        if ( $the_query->have_posts() ) {
	        while($the_query->have_posts()) : $the_query->the_post();

				$out .= MYPLUGIN_func::single( true , $args , $pt , get_the_ID()  );

	        endwhile; 
	        }else{
	            $out .= "<h3>Nothing Found</h3>";
	        }

	        wp_reset_postdata();

	        $out .= "</div>";

	        return $out; 
	    }
	}

}









