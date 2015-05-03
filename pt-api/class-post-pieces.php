<?php
if ( ! defined( 'WPINC' ) ) { die; }

/*
	These are the basic hook functions for setting up the layout. 
*/

class MYPLUGIN_pt_pcs{

	//General

		//Title
		public static function pt_title( $quer = null ){
			if (!$quer == null){ $post = $quer; }
			else{ global $post; }

			$out = "";
	    	$out .= "<h1>" . $post->post_title . "</h1>";

	    	echo $out; 
		}

		//Content
		public static function pt_content( $quer = null ){
			if (!$quer == null){ $post = $quer; }
			else{ global $post; }

			$out = "";
	   		$out .= "<div class='" . "MYPLUGIN-content" . "'>";
		    			$out .= get_the_content( $post->ID ); 
		    $out .= "</div>";
		
			echo $out;
		}

		//Featured Image
		public static function pt_fi( $quer = null ){
			if (!$quer == null){ $post = $quer; }
			else{ global $post; }

			$out = "";
	    	if ( has_post_thumbnail( $post->ID ) ){
		    	$out .= "<div class='" . "MYPLUGIN-image" . "'>";
		    		$out .= "<a title='" . $post->post_title . "' href='" . get_permalink( $post->ID ) .  "' alt='" . $post->post_title . "'>" . get_the_post_thumbnail( $post->ID ) . "</a>"; 
		    	$out .= "</div>";
			}		

			echo $out; 	
		}

		//Featured image, medium sized
		public static function pt_fimed( $quer = null ){
			if (!$quer == null){ $post = $quer; }
			else{ global $post; }

			$out = "";
	    	if ( has_post_thumbnail( $post->ID ) ){
		    	$out .= "<div class='" . "MYPLUGIN-image" . "'>";
		    		$out .= "<a title='" . get_the_title( $post->ID ) . "' href='" . get_permalink( $post->ID ) .  "' alt='" .  get_the_title( $post->ID ) . "'>" . get_the_post_thumbnail( $post->ID , "medium" ) . "</a>"; 
		    	$out .= "</div>";
			}		

			echo $out; 	
		}

		//Output unhidden meta
		public static function pt_meta( $quer = null ){
			//if (!$quer == null){ $post = $quer; }
			//else{ global $post; }
			$id = MYPLUGIN_func::get_pieces_id( $quer );


			$out = "";
	   		$out .= "<div class='" . "MYPLUGIN-meta" . "'>";
			$out .= MYPLUGIN_func::print_meta( $id );
		    $out .= "</div>";

		    echo $out; 	
		}

		//Output unhidden media
		public static function pt_media( $quer = null ){
			//if (!$quer == null){ $post = $quer; }
			//else{ global $post; }
			$id = MYPLUGIN_func::get_pieces_id( $quer );


			$out = "";
	   		$out .= "<div class='" . "MYPLUGIN-media" . "'>";
			$out .= MYPLUGIN_func::print_media( $id );
		    $out .= "</div>";

		    echo $out; 	
		}

		//Output Categories
		public static function pt_cats( $quer = null ){
			if (!$quer == null){ $post = $quer; }
			else{ global $post; }

			$out = "";
			$pt_values = get_object_taxonomies( get_post_type( $post->ID ) );
	    	$out .= "<div class='" . "MYPLUGIN-categories" . "'>";
		    	foreach($pt_values as $tax){
		    		$out .= MYPLUGIN_func::get_cats( $tax , $post->ID , $post->post_type );
		    	}
	    	$out .= "</div>";	

	    	echo $out; 	
		}

	//Archive Versions
		//Title hyperlinked
		public static function pt_title_a( $quer = null ){
			if (!$quer == null){ $post = $quer; }
			else{ global $post; }

			$out = "";
			$out .= "<a title='" . get_the_title( $post->ID ) . "' href='" . get_permalink( $post->ID ) .  "'>";
	    		$out .= "<h1>" . get_the_title( $post->ID ) . "</h1>";
	    	$out .= "</a>";

	    	echo $out; 
		}
}