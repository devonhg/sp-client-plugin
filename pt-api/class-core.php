<?php
if ( ! defined( 'WPINC' ) ) { die; }

foreach (glob( plugin_dir_path( __FILE__ ) . "*." . "php" ) as $filename){
	include_once( $filename );
}
