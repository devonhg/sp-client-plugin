<?php
if ( ! defined( 'WPINC' ) ) { die; }

/*
	These are the basic hook functions for setting up the layout. 
*/

class MYPLUGIN_pt_pcs{

	//General

		//Title
		public static function pt_title( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
	    	$out .= "<h1>" . get_the_title( $id ) . "</h1>";

	    	echo $out; 
		}

		//Content
		public static function pt_content( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
	   		$out .= "<div class='" . "MYPLUGIN-content" . "'>";
		    			$out .= get_the_content( $id ); 
		    $out .= "</div>";
		
			echo $out;
		}

		//Featured Image
		public static function pt_fi( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
	    	if ( has_post_thumbnail( $id ) ){
		    	$out .= "<div class='" . "MYPLUGIN-image" . "'>";
		    		$out .= "<a title='" . get_the_title( $id ) . "' href='" . get_permalink( $id ) .  "' alt='" . get_the_title( $id ) . "'>" . get_the_post_thumbnail( $id ) . "</a>"; 
		    	$out .= "</div>";
			}		

			echo $out; 	
		}

		//Featured image, medium sized
		public static function pt_fimed( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
	    	if ( has_post_thumbnail( $id ) ){
		    	$out .= "<div class='" . "MYPLUGIN-image" . "'>";
		    		$out .= "<a title='" . get_the_title($id ) . "' href='" . get_permalink( $id ) .  "' alt='" .  get_the_title( $id ) . "'>" . get_the_post_thumbnail( $id , "medium" ) . "</a>"; 
		    	$out .= "</div>";
			}		

			echo $out; 	
		}

		//Output unhidden meta
		public static function pt_meta( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
	   		$out .= "<div class='" . "MYPLUGIN-meta" . "'>";
			$out .= MYPLUGIN_func::print_meta( $id );
		    $out .= "</div>";

		    echo $out; 	
		}

		//Output unhidden media
		public static function pt_media( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
	   		$out .= "<div class='" . "MYPLUGIN-media" . "'>";
			$out .= MYPLUGIN_func::print_media( $id );
		    $out .= "</div>";

		    echo $out; 	
		}

		//Output Categories
		public static function pt_cats( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
			$pt_values = get_object_taxonomies( get_post_type( $id ) );
	    	$out .= "<div class='" . "MYPLUGIN-categories" . "'>";
		    	foreach($pt_values as $tax){
		    		$out .= MYPLUGIN_func::get_cats( $tax , $id , get_post_type( $id ) );
		    	}
	    	$out .= "</div>";	

	    	echo $out; 	
		}

	//Archive Versions
		//Title hyperlinked
		public static function pt_title_a( $quer = null ){
			$id = MYPLUGIN_func::get_pieces_id( $quer );

			$out = "";
			$out .= "<a title='" . get_the_title( $id ) . "' href='" . get_permalink( $id ) .  "'>";
	    		$out .= "<h1>" . get_the_title( $id ) . "</h1>";
	    	$out .= "</a>";

	    	echo $out; 
		}
}