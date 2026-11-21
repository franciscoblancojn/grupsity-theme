<?php
/**
 * Grupsity: traducción de los textos que salen en inglés en el sitio (como en TeDotamos).
 *
 * Martfury no trae traducción es_CO, y a WooCommerce y WCBoost les faltan algunas cadenas.
 * El filtro gettext solo actúa cuando la cadena NO tiene traducción (la traducción devuelta es
 * igual al original), así que nunca pisa lo que ya esté traducido en los .mo o en Loco Translate.
 * Para añadir una: copiar el texto original exacto (con sus %s) como clave.
 *
 * Además, textos que no son cadenas traducibles sino valores guardados en inglés:
 * - Nombre del paquete de envío «Shipment» / «Shipping» (título del envío en el carrito y el
 *   checkout) → «Envío».
 * - Casilla de términos del checkout guardada como «I have read and agree to the website
 *   [terms]» (WooCommerce → Ajustes → Avanzado / Personalizar → WooCommerce → Finalizar compra).
 * - Descripción por defecto de la pasarela Wompi «Pay via Wompi gateway.».
 * Si se cambian en sus ajustes a otro texto, se respeta.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Translations {

	/** Dominios a los que se aplica. */
	const DOMAINS = array( 'martfury', 'woocommerce', 'wcboost-wishlist', 'wcboost-products-compare' );

	const STRINGS = array(
		// Checkout y carrito.
		'Your order'                      => 'Tu pedido',
		'Have a coupon?'                  => '¿Tienes un cupón?',
		'Click here to enter your code'   => 'Haz clic aquí para ingresar tu código',
		'Coupon code'                     => 'Código del cupón',
		'Apply coupon'                    => 'Aplicar cupón',
		'If you have a coupon code, please apply it below.' => 'Si tienes un código de cupón, aplícalo abajo.',
		'Update cart'                     => 'Actualizar carrito',
		'Cart totals'                     => 'Total del carrito',
		'Proceed to checkout'             => 'Finalizar compra',
		'Continue Shopping'               => 'Seguir comprando',
		'Returning customer?'             => '¿Ya eres cliente?',
		// Página del carrito (plantillas de Martfury).
		'Product'                         => 'Producto',
		'Price'                           => 'Precio',
		'Quantity'                        => 'Cantidad',
		'%s quantity'                     => 'Cantidad de %s',
		'Back To Shop'                    => 'Seguir comprando',
		'Coupon Discount'                 => '¿Tienes un cupón?',
		'Remove'                          => 'Quitar',
		'Remove this item'                => 'Quitar este producto',
		'Available on backorder'          => 'Disponible bajo pedido',
		'Shipping to %s.'                 => 'Envío a %s.',
		'Change address'                  => 'Cambiar dirección',
		'Enter a different address'       => 'Ingresar otra dirección',
		'Enter your address to view shipping options.' => 'Ingresa tu dirección para ver las opciones de envío.',
		'Shipping costs are calculated during checkout.' => 'El envío se calcula al finalizar la compra.',
		'Shipping options will be updated during checkout.' => 'Las opciones de envío se actualizan al finalizar la compra.',
		'No shipping options were found for %s.' => 'No encontramos opciones de envío para %s.',
		'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.' => 'No hay opciones de envío disponibles. Revisa que tu dirección esté bien escrita o escríbenos si necesitas ayuda.',
		'(estimated for %s)'              => '(estimado para %s)',
		'Click here to login'             => 'Haz clic aquí para iniciar sesión',
		// Catálogo y fichas.
		'Filter'                          => 'Filtrar',
		'Quick View'                      => 'Vista rápida',
		'Qty'                             => 'Cant.',
		'Clear'                           => 'Limpiar',
		'Reviews'                         => 'Reseñas',
		'There are no reviews yet.'       => 'Todavía no hay reseñas.',
		'Email'                           => 'Correo electrónico',
		'Price range: %1$s through %2$s'  => 'Rango de precios: de %1$s a %2$s',
		'Next Page'                       => 'Página siguiente',
		'Previous Page'                   => 'Página anterior',
		'Loading'                         => 'Cargando',
		'Loading more products'           => 'Cargando más productos…',
		'Product Pagination'              => 'Paginación de productos',
		// Visor de fotos de la ficha (PhotoSwipe).
		'Close (Esc)'                     => 'Cerrar (Esc)',
		'Share'                           => 'Compartir',
		'Toggle fullscreen'               => 'Pantalla completa',
		'Zoom in/out'                     => 'Acercar/alejar',
		'Previous (arrow left)'           => 'Anterior (flecha izquierda)',
		'Next (arrow right)'              => 'Siguiente (flecha derecha)',
		'Close'                           => 'Cerrar',
		// Favoritos y comparar (WCBoost).
		'Wishlist'                        => 'Favoritos',
		'Add to wishlist'                 => 'Añadir a favoritos',
		'View wishlist'                   => 'Ver favoritos',
		'Add %s to the wishlist'          => 'Añadir %s a favoritos',
		'The wishlist is empty.'          => 'Tu lista de favoritos está vacía.',
		'Compare'                         => 'Comparar',
		'Compare %s'                      => 'Comparar %s',
		'Compare products'                => 'Comparar productos',
		'Return to shop'                  => 'Volver a la tienda',
		// Mi cuenta.
		'Username or email address'       => 'Usuario o correo electrónico',
		'Password'                        => 'Contraseña',
		'Remember me'                     => 'Recordarme',
		'Forgot your password?'           => '¿Olvidaste tu contraseña?',
		'Register'                        => 'Registrarse',
		'Email address'                   => 'Correo electrónico',
		'A password will be sent to your email address.' => 'Te enviaremos una contraseña a tu correo.',
	);

	public static function init() {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return; // solo el sitio (y sus respuestas AJAX, p. ej. el checkout al recalcular)
		}
		add_filter( 'gettext', array( __CLASS__, 'translate' ), 20, 3 );
		add_filter( 'woocommerce_shipping_package_name', array( __CLASS__, 'shipping_package_name' ), 99 );
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', array( __CLASS__, 'terms_checkbox_text' ), 20 );
		add_filter( 'woocommerce_gateway_description', array( __CLASS__, 'gateway_description' ), 20, 2 );
	}

	public static function translate( $translation, $text, $domain ) {
		if ( $translation === $text && isset( self::STRINGS[ $text ] ) && in_array( $domain, self::DOMAINS, true ) ) {
			return self::STRINGS[ $text ];
		}

		return $translation;
	}

	public static function shipping_package_name( $name ) {
		return in_array( trim( wp_strip_all_tags( (string) $name ) ), array( 'Shipment', 'Shipping' ), true ) ? 'Envío' : $name;
	}

	public static function terms_checkbox_text( $text ) {
		$english = 'I have read and agree to the website';
		if ( 0 === stripos( trim( (string) $text ), $english ) ) {
			$text = 'He leído y acepto los' . substr( trim( $text ), strlen( $english ) );
		}

		return $text;
	}

	public static function gateway_description( $description, $gateway_id ) {
		if ( 'wompi' === $gateway_id && 'Pay via Wompi gateway.' === trim( wp_strip_all_tags( (string) $description ) ) ) {
			return 'Paga de forma segura a través de Wompi.';
		}

		return $description;
	}
}
