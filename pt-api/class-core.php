<?php
if ( ! defined( 'WPINC' ) ) { die; }

//Include all files in directory
	foreach (glob( plugin_dir_path( __FILE__ ) . "*." . "php" ) as $filename){
		include_once( $filename );
	}

//Front end hooks
	function pt_archive(){
		do_action('pt_archive');
	}

	function pt_single(){
		do_action('pt_single');
	}

	function pt_shortcode(){
		do_action('pt_shortcode');		
	}

//Post Pieces