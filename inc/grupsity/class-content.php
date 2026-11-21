<?php
/**
 * Grupsity: limpieza del contenido de las páginas.
 *
 * Algunas páginas (p. ej. el carrito) se armaron con WPBakery, que ya no está activo, y en el
 * sitio se veían sus shortcodes como texto: «[vc_row][vc_column][vc_column_text]». Si el
 * shortcode no existe, se quitan del contenido junto con los párrafos que quedan vacíos.
 * Con WPBakery activo no se toca nada.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Content {

	public static function init() {
		add_filter( 'the_content', array( __CLASS__, 'strip_orphan_vc_shortcodes' ), 20 );
	}

	public static function strip_orphan_vc_shortcodes( $content ) {
		if ( ! is_string( $content ) || false === strpos( $content, '[vc_' ) && false === strpos( $content, '[/vc_' ) ) {
			return $content;
		}
		if ( shortcode_exists( 'vc_row' ) ) {
			return $content;
		}

		$content = preg_replace( '#\[/?vc_[a-z0-9_]+(?:\s[^\]]*)?\]#i', '', $content );

		return preg_replace( '#<p>(?:\s|&nbsp;|<br\s*/?>)*</p>#i', '', $content );
	}
}
