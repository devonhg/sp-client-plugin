<?php
if ( ! defined( 'WPINC' ) ) { die; }
/*
 * Plugin Name:       Devons Tools - Post-Type API
 * Plugin URI:        
 * Description:       This is the Core for Devons Tools Framework
 * Version:           v0.9.5
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
include_once('pt-api/class-core.php');

$pt_books = new MYPLUGIN_post_type( "Honor Students", "Honor Student" ); 