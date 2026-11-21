<?php
/**
 * Grupsity: ficha de producto.
 *
 * Con cualquier diseño (arreglos):
 * - Sin stock no salía nada en el bloque de compra: la plantilla de Martfury
 *   (woocommerce/single-product/add-to-cart/simple.php) no imprime el estado de stock porque
 *   Martfury lo pinta en su propio layout, y la ficha de Grupsity es una plantilla de Elementor.
 *   simple.php imprime ahora «Agotado» y lanza grupsity_add_to_cart_unavailable.
 * - Pestañas en inglés («Description», «Specification») → «Descripción», «Especificaciones».
 * - «Brand:» (etiqueta de la taxonomía de marcas de WooCommerce sin traducir) → «Marca:».
 * - Bloque «See It Styled On Instagram» de Martfury activo sin token: imprimía «No access token»
 *   en todas las fichas. Se oculta mientras no haya token (Customizer → Instagram).
 * - Título «Related products» por defecto → «Productos relacionados».
 *
 * Solo con el diseño V1 (como la ficha de TeDotamos):
 * - Botón «Comprar por WhatsApp» (o «Preguntar disponibilidad» si está agotado).
 * - Porcentaje de descuento junto al precio del producto principal.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Product {

	public static function init() {
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'tab_titles' ), 99 );
		add_filter( 'woocommerce_product_brands_output', array( __CLASS__, 'brand_label' ) );
		add_filter( 'martfury_get_option', array( __CLASS__, 'override_options' ), 20, 2 );

		if ( 'v1' === Grupsity_Designs::current() ) {
			add_action( 'woocommerce_after_add_to_cart_form', array( __CLASS__, 'whatsapp_button' ) );
			add_action( 'grupsity_add_to_cart_unavailable', array( __CLASS__, 'whatsapp_button' ) );
			add_filter( 'woocommerce_get_price_html', array( __CLASS__, 'discount_badge' ), PHP_INT_MAX, 2 );
		}
	}

	public static function override_options( $value, $name ) {
		switch ( $name ) {
			case 'product_instagram':
				return $value && '' === trim( (string) martfury_get_option( 'api_instagram_token' ) ) ? 0 : $value;
			case 'product_related_title':
				return ! $value || 'Related products' === $value ? 'Productos relacionados' : $value;
			case 'product_thumbnail_numbers':
				// V1 muestra las miniaturas en cuadrícula debajo de la foto (css/design-v1.css).
				// Con más fotos que este número, Martfury (js/scripts.js, productThumbnail) las
				// convertía en un slider vertical y quedaban apiladas en una columna: solo en los
				// productos con más fotos de la cuenta. Con un tope alto nunca arma el slider.
				return 'v1' === Grupsity_Designs::current() ? 100 : $value;
		}

		return $value;
	}

	public static function tab_titles( $tabs ) {
		$map = array(
			'Description'            => 'Descripción',
			'Specification'          => 'Especificaciones',
			'Additional information' => 'Especificaciones',
		);
		foreach ( $tabs as $key => $tab ) {
			if ( isset( $tab['title'], $map[ $tab['title'] ] ) ) {
				$tabs[ $key ]['title'] = $map[ $tab['title'] ];
			}
		}

		return $tabs;
	}

	public static function brand_label( $output ) {
		return preg_replace(
			'/<span class="posted_in">\s*Brands?:\s*/',
			'<span class="posted_in"><span class="detail-label">Marca:</span> ',
			(string) $output,
			1
		);
	}

	/**
	 * Número de WhatsApp (solo dígitos, con indicativo) para la ficha de producto.
	 */
	public static function whatsapp_number() {
		$number = preg_replace( '/\D/', '', (string) Grupsity_Design_V1::get( 'v1_whatsapp' ) );

		return $number ? $number : '';
	}

	public static function whatsapp_button() {
		global $product;
		if ( ! $product instanceof WC_Product || ! is_product() ) {
			return;
		}
		$number = self::whatsapp_number();
		if ( ! $number ) {
			return;
		}
		$available = $product->is_in_stock();
		$text      = $available
			? sprintf( 'Hola, quiero comprar: %1$s %2$s', $product->get_name(), get_permalink( $product->get_id() ) )
			: sprintf( 'Hola, ¿tienen disponible %1$s? %2$s', $product->get_name(), get_permalink( $product->get_id() ) );

		printf(
			'<a class="gv1-whatsapp%1$s" href="%2$s" target="_blank" rel="noopener">%3$s<span>%4$s</span></a>',
			$available ? '' : ' gv1-whatsapp--ask',
			esc_url( 'https://wa.me/' . $number . '?text=' . rawurlencode( $text ) ),
			'<svg class="gv1-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.1-1.8-.9-2-1s-.5-.1-.7.1-.8 1-.9 1.2-.3.2-.6.1a8.2 8.2 0 0 1-2.4-1.5 9 9 0 0 1-1.7-2.1c-.2-.3 0-.5.1-.6l.4-.5.3-.5v-.5l-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6a1.1 1.1 0 0 0-.8.4 3.4 3.4 0 0 0-1 2.5 5.9 5.9 0 0 0 1.2 3.1 13.4 13.4 0 0 0 5.2 4.6c.7.3 1.3.5 1.7.6a4.2 4.2 0 0 0 1.9.1 3.1 3.1 0 0 0 2-1.4 2.5 2.5 0 0 0 .2-1.4c-.1-.1-.3-.2-.6-.3zM12 21.8a9.8 9.8 0 0 1-5-1.4l-.4-.2-3.7 1 1-3.6-.2-.4A9.8 9.8 0 1 1 12 21.8zm8.4-18.2A11.8 11.8 0 0 0 1.8 17.8L.1 24l6.3-1.7a11.8 11.8 0 0 0 5.6 1.4 11.8 11.8 0 0 0 8.4-20.1z"/></svg>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $available ? 'Comprar por WhatsApp' : 'Preguntar disponibilidad por WhatsApp' )
		);
	}

	/**
	 * «-25 %» junto al precio del producto principal de la ficha (no en listados ni relacionados).
	 */
	/**
	 * Un solo «-23%» después del precio del producto principal.
	 *
	 * Martfury añade en la ficha su propio «(-23%)» (span.sale), y otro filtro de precio vuelve a
	 * llamar a get_price_html() y mete ese resultado como precio anterior dentro del <del>: las
	 * etiquetas salían dobles y tachadas. Este filtro corre el último (PHP_INT_MAX): quita las
	 * etiquetas que haya en cualquier parte del HTML y deja una sola al final, fuera del <del>.
	 */
	public static function discount_badge( $html, $product ) {
		if ( ! is_product() || ! $product instanceof WC_Product || $product->get_id() !== get_queried_object_id() ) {
			return $html;
		}
		if ( ! $product->is_on_sale() || $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ) {
			return $html;
		}
		$regular = (float) $product->get_regular_price();
		$sale    = (float) $product->get_sale_price();
		if ( $regular <= 0 || $sale <= 0 || $sale >= $regular ) {
			return $html;
		}
		$pct = (int) round( ( $regular - $sale ) / $regular * 100 );
		if ( $pct <= 0 ) {
			return $html;
		}

		$html = preg_replace( '#\s*<span class="(?:gv1-discount|sale)">[^<]*</span>#', '', (string) $html );

		return $html . ' <span class="gv1-discount">-' . $pct . '%</span>';
	}
}
