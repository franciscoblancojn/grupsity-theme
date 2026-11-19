<?php
/**
 * Grupsity: selector de diseño del sitio.
 *
 * - martfury:  el tema tal como está (Martfury + la configuración del Customizer).
 * - jomashop:  estilo inspirado en https://www.jomashop.com/ aplicado sobre el mismo
 *              HTML de Martfury, sin tocar opciones ni contenido.
 *
 * El diseño activo se elige en Apariencia → Grupsity → General y se guarda en
 * grupsity_options['design']. Para añadir otro: registrarlo en DESIGNS, crear su hoja
 * en css/design-<id>.css y, si necesita lógica propia, una clase como la de JomaShop.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Designs {

	const DESIGNS = array(
		'martfury'  => 'Martfury (diseño actual)',
		'jomashop'  => 'JomaShop (forma de jomashop.com, colores de Grupsity)',
		'v1'        => 'V1 (estructura de TeDotamos, colores y fuentes de Grupsity)',
	);

	/**
	 * Fuentes de Google que necesita cada diseño (el sitio ya carga otras de Google Fonts).
	 * Sin esto el CSS pedía Inter pero el navegador caía en Arial/Helvetica.
	 */
	const FONTS = array(
		'jomashop' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
	);

	public static function init() {
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 100 );
		// Los estilos en línea del tema se guardan en caché y LiteSpeed sirve página estática:
		// al cambiar de diseño hay que regenerar ambas cosas.
		add_action( 'update_option_' . Grupsity_Admin::OPTION, array( __CLASS__, 'maybe_clear_caches' ), 10, 2 );
	}

	public static function current() {
		$design = Grupsity_Admin::get_option( 'design' );

		return isset( self::DESIGNS[ $design ] ) ? $design : 'martfury';
	}

	public static function body_class( $classes ) {
		$classes[] = 'grupsity-design-' . self::current();

		return $classes;
	}

	public static function enqueue() {
		$design = self::current();
		if ( 'martfury' === $design ) {
			return;
		}

		$file = 'css/design-' . $design . '.css';
		if ( ! file_exists( get_template_directory() . '/' . $file ) ) {
			return;
		}

		$deps = array( 'grupsity' );
		if ( isset( self::FONTS[ $design ] ) ) {
			wp_enqueue_style( 'grupsity-design-' . $design . '-fonts', self::FONTS[ $design ], array(), null );
			$deps[] = 'grupsity-design-' . $design . '-fonts';
		}

		wp_enqueue_style(
			'grupsity-design-' . $design,
			get_template_directory_uri() . '/' . $file,
			$deps,
			wp_get_theme()->get( 'Version' )
		);
	}

	public static function maybe_clear_caches( $old, $new ) {
		$old_design = isset( $old['design'] ) ? $old['design'] : 'martfury';
		$new_design = isset( $new['design'] ) ? $new['design'] : 'martfury';
		if ( $old_design === $new_design ) {
			return;
		}

		self::clear_caches();
	}

	public static function clear_caches() {
		if ( function_exists( 'martfury_clear_inline_styles_cache' ) ) {
			martfury_clear_inline_styles_cache();
		}
		do_action( 'litespeed_purge_all' );
	}
}
