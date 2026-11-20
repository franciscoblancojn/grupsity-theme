<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

$next_text = sprintf( '%s <i class="icon-chevron-right"></i>', esc_html__( 'Next Page', 'martfury' ) );
$prev_text = sprintf( '<i class="icon-chevron-left"></i> %s', esc_html__( 'Previous Page', 'martfury' ) );

$type = 'list';
if ( $total <= 1 ) {
	return;
}

// Grupsity: en scroll infinito solo se imprime el enlace a la página siguiente (Martfury imprimía
// también los números, que quedaban a la vista). El JS de Martfury (productsLoadInfinite) lo
// pulsa al verlo en pantalla, añade los productos y reemplaza este <nav> por el de la página
// cargada; por eso en la última página se imprime vacío (si no, volvería a cargar la última).
// Sin JS, o para los buscadores, es un enlace normal. Mismo arreglo que el tema TeDotamos.
if ( martfury_get_option( 'catalog_nav_type' ) == 'infinite' ) {
	// WooCommerce 11 pasa $base con el marcador %_% y $format como "page/%#%/": si solo se
	// sustituye %#%, el enlace queda en "…/tienda/%_%" (400). Primero se expande %_%.
	$next_url = $base;
	if ( false !== strpos( $next_url, '%_%' ) ) {
		$next_format = $format;
		if ( '' === $next_format ) {
			global $wp_rewrite;
			$next_format = user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' );
		}
		$next_url = str_replace( '%_%', $next_format, $next_url );
	}
	$next_url = str_replace( '%#%', $current + 1, $next_url );

	printf(
		'<nav class="woocommerce-pagination grupsity-infinite" aria-label="%s">%s</nav>',
		esc_attr__( 'Product Pagination', 'woocommerce' ),
		$current < $total ? sprintf(
			'<a class="next page-numbers" href="%s"><span id="martfury-products-loading" class="grupsity-infinite__loading">%s</span></a>',
			esc_url( $next_url ),
			esc_html( 'Cargando más productos' )
		) : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
	return;
}
?>
<nav class="woocommerce-pagination" aria-label="<?php esc_attr_e( 'Product Pagination', 'woocommerce' ); ?>">
	<?php
	echo paginate_links( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
		'base'      => $base,
		'format'    => $format,
		'add_args'  => false,
		'current'   => max( 1, $current ),
		'total'     => $total,
		'prev_text' => $prev_text,
		'next_text' => $next_text,
		'type'      => $type,
		'end_size'  => 3,
		'mid_size'  => 3,
	) ) );
	?>
</nav>
