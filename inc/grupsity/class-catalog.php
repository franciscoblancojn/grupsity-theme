<?php
/**
 * Grupsity: ajustes del catálogo (tienda, categorías, marcas y búsqueda), con cualquier diseño.
 *
 * - Navegación: grupsity_options['catalog_nav'] = 'infinite' | 'numbers' | '' (lo que diga el
 *   Customizer de Martfury). Se aplica en tiempo de ejecución con el filtro martfury_get_option
 *   (no cambia theme_mods). El scroll infinito lo hace el JS de Martfury
 *   (martfury.productsLoadInfinite); woocommerce/loop/pagination.php imprime el enlace.
 * - Filtro de precios: los campos "mín./máx." se pueden escribir y mueven el slider.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Catalog {

	const NAV_CHOICES = array(
		'infinite' => 'Scroll infinito (carga más productos al llegar al final)',
		'numbers'  => 'Números de página',
		''         => 'La del Customizer de Martfury',
	);

	public static function init() {
		add_filter( 'martfury_get_option', array( __CLASS__, 'override_options' ), 20, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'price_filter_script' ), 30 );
	}

	public static function nav() {
		$nav = (string) Grupsity_Admin::get_option( 'catalog_nav' );

		return isset( self::NAV_CHOICES[ $nav ] ) ? $nav : '';
	}

	public static function override_options( $value, $name ) {
		if ( 'catalog_nav_type' === $name ) {
			$nav = self::nav();

			return '' !== $nav ? $nav : $value;
		}

		return $value;
	}

	/**
	 * WooCommerce oculta los campos del filtro de precios y solo deja el slider. Con el diseño
	 * se muestran: al escribir un valor se mueve el slider (y el texto "Precio: …").
	 */
	public static function price_filter_script() {
		if ( ! wp_script_is( 'wc-price-slider', 'registered' ) ) {
			return;
		}
		$js = <<<'JS'
jQuery( function ( $ ) {
	$( document.body ).on( 'change', '.price_slider_amount #min_price, .price_slider_amount #max_price', function () {
		var $wrap   = $( this ).closest( '.price_slider_wrapper' ),
			$slider = $wrap.find( '.price_slider' ),
			$min    = $wrap.find( '#min_price' ),
			$max    = $wrap.find( '#max_price' ),
			lo      = parseFloat( $min.data( 'min' ) ),
			hi      = parseFloat( $max.data( 'max' ) ),
			a       = parseFloat( String( $min.val() ).replace( /[^\d.]/g, '' ) ),
			b       = parseFloat( String( $max.val() ).replace( /[^\d.]/g, '' ) );
		if ( ! $slider.hasClass( 'ui-slider' ) ) {
			return;
		}
		a = isNaN( a ) ? lo : Math.max( lo, Math.min( a, hi ) );
		b = isNaN( b ) ? hi : Math.max( lo, Math.min( b, hi ) );
		if ( a > b ) {
			var t = a; a = b; b = t;
		}
		$min.val( a );
		$max.val( b );
		$slider.slider( 'values', [ a, b ] );
		$( document.body ).trigger( 'price_slider_slide', [ a, b ] );
	} );
} );
JS;
		wp_add_inline_script( 'wc-price-slider', $js );
	}
}
