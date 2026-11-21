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

		if ( is_front_page() ) {
			wp_add_inline_style( 'grupsity', self::home_gap_css() );
		}
	}

	/**
	 * Portada: mismo espacio entre todas las secciones de Elementor (Apariencia → Grupsity →
	 * General). Cada sección traía sus propios márgenes/rellenos y el hueco era desigual y grande.
	 * Las secciones con fondo conservan su relleno interior.
	 */
	public static function home_gap_css() {
		$desktop = (int) Grupsity_Admin::get_option( 'home_gap' );
		$mobile  = (int) Grupsity_Admin::get_option( 'home_gap_mobile' );

		$top     = 'body.home .site-content .elementor > :is(.e-con.e-parent, .elementor-section.elementor-top-section)';
		$no_bg   = $top . ':not([data-settings*="background_background"])';
		$columns = 'body.home .site-content .elementor > .elementor-top-section:not([data-settings*="background_background"]) > .elementor-container > .elementor-column > .elementor-widget-wrap';

		return $top . '{--margin-top:0px;--margin-bottom:var(--g-home-gap);margin-top:0!important;margin-bottom:var(--g-home-gap)!important}'
			. $top . ':last-child{--margin-bottom:0px;margin-bottom:0!important}'
			. $no_bg . '{--padding-top:0px;--padding-bottom:0px;padding-top:0!important;padding-bottom:0!important}'
			. $columns . '{padding-top:0!important;padding-bottom:0!important}'
			. 'body.home{--g-home-gap:' . $desktop . 'px}'
			. '@media (max-width:767px){body.home{--g-home-gap:' . $mobile . 'px}}';
	}
}
