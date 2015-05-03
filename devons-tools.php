<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
 * Plugin Name:       Devons Tools - Post-Type API
 * Plugin URI:        
 * Description:       This is the Core for Devons Tools Framework
 * Version:           v0.9.0
 * Author:            Devon Godfrey
 * Author URI:        http://playfreygames.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

	*IMPORTANT*
	do a "find/replace" accross the directory for "MYPLUGIN" and replace
	with your plugin name. 

	Plugin slug: MYPLUGIN

*/

//Include the core class of the post type api

function debug( $entry , $value ){
    $f = WP_CONTENT_DIR . "\debug.txt"; 
    if ( file_exists( $f ) ){
        $fc = file_get_contents( $f );

        //Convert string to associative array
        $mstr = explode("|",$fc);
        $a = array();
        foreach($mstr as $nstr )
        {   
            if (strpos($nstr, '=') !== FALSE ){
                $narr = explode("=",$nstr);
                $narr[0] = trim( str_replace("\x98","",$narr[0]) );
                $ytr[1] = trim( $narr[1] );
                $a[$narr[0]] =$ytr[1];
            }
        }

        $a[$entry] = $value;

        //Convert back to string
        $fo = http_build_query($a, '', '|'."\n");
        
        file_put_contents( $f , $fo );
    }else{
        file_put_contents( $f , $entry . '=' . $value . " | " );
    }
}

include_once('pt-api/class-core.php');

$pt_books = new MYPLUGIN_post_type( "Books", "Book" ); 

$pt_books->reg_tax("Genres", "Genre" );
$pt_books->reg_tax("Authors", "Author" );

 $pt_books->add_hook_single( array("MYPLUGIN_pt_pcs",'pt_media') );


$pt_books->reg_meta('Price', 'The Cost of the Book', true);
$pt_books->reg_meta('Weight', 'The Weight of Item');
$pt_books->reg_meta('Cover', 'The Cover Type', false ,  "radio", array("Hardcover", "Softcover"));
$pt_books->reg_meta('Color', 'The Color', true , "color");

$pt_books->reg_meta('Link', 'Add a Link!', false , "link");

$pt_books->reg_meta('Another Link', 'Add a Link!', true , "link");

$pt_books->reg_meta('Name', 'Add a name!');

$pt_books->reg_meta('Media', 'Add some media!!', false, "media" );