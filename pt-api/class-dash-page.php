<?php
if ( ! defined( 'WPINC' ) ) { die; }

class MYPLUGIN_dash_page {

	static $instances = array(); 

	public $menu_pos; 
	public $name; 
	public $name_s; 

	public function __construct( $name, $name_s ){
		MYPLUGIN_dash_page::$instances[] = $this; 
		$this->name = $name;
		$this->name_s = $name; 
		$this->menu_pos = 82.1 + ( count( MYPLUGIN_dash_page::$instances )/10 );


		add_action('admin_menu', array( $this, 'page_enqueue' ));
	}

	public function page_enqueue(){
		add_menu_page( $this->name . " Info Page" , $this->name . " Info", 'read', $this->name_s . "_menu", array($this, 'page_function') , "", $this->menu_pos );
	}

	public function page_function(){
		$out = "";



		$out .= "<div class='wrap'>";
			$out .= "<h1>Shortcodes</h1>";

			$out .= "<p>These are the shortcodes generated for the custom post types.</p>";

			$out .= "<hr>";

			foreach( MYPLUGIN_post_type::$instances as $pt  ){

				$out .= "<div style='padding: 25px' class='postbox'>";
					$out .= "<h2>" . $pt->name . "</h2>";

					$out .= "<hr>";

					foreach ( MYPLUGIN_pt_sc::$instances as $sc ){
						if ( $sc->par->name == $pt->name ){
							$out .= "<div>";
								$out .= "<h3>[" . $sc->name . "]</h3>";
								$out .= "<p>" . $sc->desc . "</p>";
							$out .= "</div>";
						}
					}
				$out .= "</div>";
			}
		$out .= "</div>";

		$out .= "<hr>";

		$out .= "<sub>";
			$out .= "These features were created using an API developed by <a target='_blank' href='http://www.dhgodfrey.net'>Devon Godfrey</a>, please show your appreciation by <a target='_blank' href='http://www.dhgodfrey.net'>visiting my website</a>.";
		$out .= "</sub>";

		echo $out; 
	}
}