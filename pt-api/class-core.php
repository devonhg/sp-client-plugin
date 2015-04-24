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

//Post Pieces
	function pt_title(){
		do_action('pt_title');
	}

	function pt_content(){
		do_action('pt_content');
	}
	
	function pt_fi(){
		do_action('pt_fi');
	}

	function pt_meta(){
		do_action('pt_meta');
	}

	function pt_cats(){
		do_action('pt_cats');
	}