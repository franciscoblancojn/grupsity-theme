<?php
/**
 * Grupsity: título de la portada.
 *
 * La página de inicio se llama «Home», y Rank Math (plantilla «%title% %sep% %sitename%»)
 * la mostraba como «Home - Grupsity.Com» en la pestaña del navegador, en Google y al
 * compartir. En la portada, si el título empieza por el nombre genérico de la página
 * («Home», «Inicio», «Portada»), se deja solo el nombre del sitio, más el eslogan si el
 * sitio tiene uno propio. Un título SEO personalizado (que no empiece así) no se toca.
 *
 * Funciona con Rank Math y sin él (título de WordPress).
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Seo {

	/** Nombres de página que no aportan nada en el título. */
	const GENERIC_NAMES = array( 'home', 'inicio', 'portada', 'página de inicio', 'pagina de inicio' );

	/** Eslóganes por defecto de WordPress: no se añaden al título. */
	const DEFAULT_TAGLINES = array( 'just another wordpress site', 'otro sitio realizado con wordpress' );

	public static function init() {
		add_filter( 'rank_math/frontend/title', array( __CLASS__, 'front_title' ), 20 );
		add_filter( 'rank_math/opengraph/facebook/og_title', array( __CLASS__, 'front_title' ), 20 );
		add_filter( 'rank_math/opengraph/twitter/title', array( __CLASS__, 'front_title' ), 20 );
		add_filter( 'document_title_parts', array( __CLASS__, 'title_parts' ), 20 );
	}

	/** Título completo (Rank Math): «Home - Grupsity.Com» → «Grupsity.Com». */
	public static function front_title( $title ) {
		$page = self::generic_front_page_title();
		if ( null === $page || ! is_string( $title ) ) {
			return $title;
		}

		if ( 0 !== stripos( ltrim( $title ), $page ) ) {
			return $title;
		}

		return self::site_title();
	}

	/** Título de WordPress sin Rank Math: quita la parte «Home». */
	public static function title_parts( $parts ) {
		$page = self::generic_front_page_title();
		if ( null === $page || empty( $parts['title'] ) || 0 !== strcasecmp( trim( $parts['title'] ), $page ) ) {
			return $parts;
		}

		$parts['title'] = get_bloginfo( 'name' );
		unset( $parts['site'] );

		$tagline = self::tagline();
		if ( '' !== $tagline ) {
			$parts['tagline'] = $tagline;
		}

		return $parts;
	}

	/** Nombre de la página de portada si es genérico; null en otro caso. */
	private static function generic_front_page_title() {
		if ( ! is_front_page() ) {
			return null;
		}

		$front_id = (int) get_option( 'page_on_front' );
		if ( ! $front_id || 'page' !== get_option( 'show_on_front' ) ) {
			return null;
		}

		$page = trim( wp_strip_all_tags( get_the_title( $front_id ) ) );
		$key  = function_exists( 'mb_strtolower' ) ? mb_strtolower( $page ) : strtolower( $page );

		return in_array( $key, self::GENERIC_NAMES, true ) ? $page : null;
	}

	private static function site_title() {
		$name    = wp_strip_all_tags( get_bloginfo( 'name' ) );
		$tagline = self::tagline();

		return '' === $tagline ? $name : $name . ' - ' . $tagline;
	}

	private static function tagline() {
		$tagline = trim( wp_strip_all_tags( get_bloginfo( 'description' ) ) );
		$key     = function_exists( 'mb_strtolower' ) ? mb_strtolower( $tagline ) : strtolower( $tagline );

		return in_array( $key, self::DEFAULT_TAGLINES, true ) ? '' : $tagline;
	}
}
