<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
 * Plugin Name:       Devons Tools - Post-Type API
 * Plugin URI:        
 * Description:       This is the Core for Devons Tools Framework
 * Version:           1.3
 * Author:            Devon Godfrey
 * Author URI:        http://playfreygames.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

	This plugin is based on Devons Tools Beta 1.3

	*IMPORTANT*
	do a "find/replace" accross the directory for "MYPLUGIN" and replace
	with your plugin name. 

	Any files put into the inc folder automatically get included. 
*/

include_once('pt-api/class-core.php');



/*
define("MYPLUGIN_HOME_DIR", plugin_dir_path( __FILE__ ));
define("MYPLUGIN_HOME_URL", plugin_dir_url( __FILE__ ));

include_once( MYPLUGIN_HOME_DIR . "framework/class-devons_tools_core.php" );
*/

/*
===========================================
	Edit everything below this comment!
===========================================
*/


$pt_books = new MYPLUGIN_post_type( "Books", "Book" ); 

$pt_books->reg_tax("Genres", "Genre" );
$pt_books->reg_tax("Authors", "Author" );

$pt_books->reg_meta('Price', 'The Cost of Item');
$pt_books->reg_meta('Weight', 'The Weight of Item');
$pt_books->reg_meta('Cover', 'The Cover Type', "radio", array("Hardcover", "Softcover"));
$pt_books->reg_meta('Color', 'The Color', "color");