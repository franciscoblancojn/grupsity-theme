<?php
/**
 * Grupsity: hoja de estilos propia del tema.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Assets {

	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 20 );
	}

	public static function enqueue() {
		wp_enqueue_style(
			'grupsity',
			get_template_directory_uri() . '/css/grupsity.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
}
