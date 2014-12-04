<?php

class WP_Foundation_Shortcodes {

	static function init(){
		// register shortcodes here
	}
}

add_action( 'init', array( 'WP_Foundation_Shortcodes', 'init' ) );