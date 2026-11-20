<?php
/**
 * Grupsity: diseño "V1" (estructura del tema TeDotamos V1, colores y fuentes de Grupsity).
 *
 * Solo actúa con grupsity_options['design'] = 'v1':
 * - Fuerza el header layout 1 de Martfury (logo + buscador + iconos, y debajo la barra de menú).
 * - Pinta su propia franja superior (3 avisos con ícono) y desactiva el topbar/promoción de Martfury.
 * - Reemplaza el menú y el "Header Bar" por una barra con enlaces y el teléfono (gancho
 *   grupsity_header_nav de template-parts/headers/layout-1.php).
 * - Reemplaza la lista del desplegable "Categorías" por las categorías de producto con ícono y
 *   panel de subcategorías (filtro grupsity_department_menu de martfury_extra_department()).
 * No escribe opciones del Customizer: todo es en tiempo de ejecución (filtro martfury_get_option).
 *
 * Textos y enlaces: Apariencia → Grupsity → General (sección "Diseño V1").
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Design_V1 {

	const DEFAULTS = array(
		'v1_topbar'  => "truck|Entrega gratuita en todas tus compras|\nshield|Productos 100% originales|\ncard|Pago seguro: transferencias, TC y créditos|",
		'v1_nav'     => "Relojes de Hombre|/hombre/\nRelojes de Mujer|/mujer/\nOfertas del Día|/ofertas-del-dia/\nSeguimiento de pedidos|/seguimiento-de-pedidos/\nTedotamos.com|https://tedotamos.com/",
		'v1_hotline' => '+57 318 888 0385',
		'v1_whatsapp' => '573188880385',
	);

	public static function init() {
		if ( 'v1' !== Grupsity_Designs::current() ) {
			return;
		}
		add_filter( 'martfury_get_option', array( __CLASS__, 'override_options' ), 20, 2 );
		add_action( 'martfury_before_header', array( __CLASS__, 'render_topbar' ), 1 );
		add_action( 'grupsity_header_nav', array( __CLASS__, 'render_nav' ) );
		add_filter( 'grupsity_department_menu', array( __CLASS__, 'department_menu' ) );
	}

	public static function get( $key ) {
		$value = Grupsity_Admin::get_option( $key );

		return null === $value || '' === $value ? self::DEFAULTS[ $key ] : $value;
	}

	/**
	 * Líneas "a|b|c" de una opción de texto.
	 *
	 * @return array[] Cada línea con exactamente $columns columnas.
	 */
	public static function lines( $key, $columns ) {
		$out = array();
		foreach ( preg_split( '/\r\n|\r|\n/', (string) self::get( $key ) ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}
			$out[] = array_pad( array_map( 'trim', explode( '|', $line, $columns ) ), $columns, '' );
		}

		return $out;
	}

	public static function url( $url ) {
		$url = trim( $url );
		if ( '' === $url ) {
			return '';
		}

		return 0 === strpos( $url, '/' ) ? home_url( $url ) : $url;
	}

	public static function override_options( $value, $name ) {
		switch ( $name ) {
			case 'header_layout':
				return '1';
			case 'topbar':
			case 'promotion':
			case 'header_hot_words_enable':
				return 0;
			case 'department_open_homepage':
				// Martfury deja el desplegable "Categorías" abierto en la portada (por defecto
				// "open"): en la barra V1 quedaba fijo encima del contenido. Se abre al pasar el
				// ratón, igual que en el resto de páginas.
				return 'close';
			case 'department_space_homepage':
			case 'department_space_2_homepage':
				return ''; // relleno superior pensado para el menú abierto en la portada
			case 'custom_department_text':
				// Sin theme_mods sale el texto por defecto en inglés.
				return ! $value || 'Shop By Department' === $value ? 'Categorías' : $value;
			case 'custom_search_text':
				return ! $value || "I'm shopping for..." === $value ? 'Busca tu marca favorita' : $value;
			case 'custom_search_button':
				return ! $value || 'Search' === $value ? 'Buscar' : $value;
			case 'footer_newsletter':
				// El formulario del boletín es [mc4wp_form …]: si su plugin no está activo, el
				// shortcode sale como texto. En ese caso no se muestra el bloque.
				return self::newsletter_form_ok() ? $value : 0;
		}

		return $value;
	}

	private static function newsletter_form_ok() {
		remove_filter( 'martfury_get_option', array( __CLASS__, 'override_options' ), 20 );
		$form = (string) martfury_get_option( 'footer_newsletter_form' );
		add_filter( 'martfury_get_option', array( __CLASS__, 'override_options' ), 20, 2 );

		if ( preg_match_all( '/\[([a-zA-Z0-9_-]+)/', $form, $m ) ) {
			foreach ( $m[1] as $tag ) {
				if ( ! shortcode_exists( $tag ) ) {
					return false;
				}
			}
		}

		return true;
	}

	public static function render_topbar() {
		$items = self::lines( 'v1_topbar', 3 );
		if ( ! $items ) {
			return;
		}
		echo '<div class="gv1-topbar"><div class="gv1-container"><ul class="gv1-topbar__items">';
		foreach ( $items as $item ) {
			list( $icon, $text, $url ) = $item;
			$inner = self::icon( $icon ) . '<span>' . esc_html( $text ) . '</span>';
			$url   = self::url( $url );
			printf(
				'<li>%s</li>',
				$url ? '<a class="gv1-topbar__item" href="' . esc_url( $url ) . '">' . $inner . '</a>' : '<span class="gv1-topbar__item">' . $inner . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</ul></div></div>';
	}

	public static function render_nav() {
		echo '<nav class="gv1-nav" aria-label="Menú principal"><ul>';
		foreach ( self::lines( 'v1_nav', 2 ) as $item ) {
			$url = self::url( $item[1] );
			if ( $url ) {
				printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $item[0] ) );
			}
		}
		$hotline = trim( (string) self::get( 'v1_hotline' ) );
		if ( $hotline ) {
			printf(
				'<li class="gv1-nav__hotline"><a href="tel:%s">%s<span>%s</span></a></li>',
				esc_attr( preg_replace( '/[^\d+]/', '', $hotline ) ),
				self::icon( 'headset' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $hotline )
			);
		}
		echo '</ul></nav>';
	}

	/**
	 * Categorías del desplegable. Si la tienda tiene una sola categoría raíz con hijas
	 * (p. ej. "Relojes" → Hombre / Mujer / Niños), se muestran sus hijas: una lista de un
	 * solo elemento no sirve como menú.
	 *
	 * @return WP_Term[]
	 */
	public static function top_categories() {
		$args = array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
			'menu_order' => 'ASC',
		);
		$cats = get_terms( $args );
		if ( is_wp_error( $cats ) ) {
			return array();
		}
		if ( 1 === count( $cats ) ) {
			$children = get_terms( array_merge( $args, array( 'parent' => $cats[0]->term_id ) ) );
			if ( ! is_wp_error( $children ) && $children ) {
				return $children;
			}
		}

		return $cats;
	}

	public static function department_menu( $menu ) {
		$cats = self::top_categories();
		if ( ! $cats ) {
			return $menu;
		}
		ob_start();
		echo '<ul class="menu gv1-dcats">';
		foreach ( $cats as $cat ) {
			$link     = get_term_link( $cat );
			$children = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'parent'     => $cat->term_id,
					'hide_empty' => true,
					'menu_order' => 'ASC',
				)
			);
			$children = is_wp_error( $children ) ? array() : $children;
			printf(
				'<li class="menu-item gv1-dcats__item%s"><a href="%s"><span class="gv1-dcats__icon">%s</span><span class="gv1-dcats__name">%s</span>%s</a>',
				$children ? ' gv1-dcats__item--parent' : '',
				esc_url( $link ),
				self::icon( self::category_icon( $cat ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $cat->name ),
				$children ? self::icon( 'chevron', 'gv1-icon gv1-dcats__chevron' ) : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			if ( $children ) {
				echo '<div class="gv1-dcats__panel">';
				printf(
					'<a class="gv1-dcats__panel-title" href="%s">%s<span>Ver todo %s</span></a>',
					esc_url( $link ),
					esc_html( $cat->name ),
					self::icon( 'arrow' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				echo '<div class="gv1-dcats__links">';
				foreach ( $children as $child ) {
					printf(
						'<a href="%s">%s <span class="gv1-dcats__count">(%d)</span></a>',
						esc_url( get_term_link( $child ) ),
						esc_html( $child->name ),
						(int) $child->count
					);
				}
				echo '</div></div>';
			}
			echo '</li>';
		}
		$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
		printf(
			'<li class="menu-item gv1-dcats__all"><a href="%s"><span class="gv1-dcats__icon">%s</span><span class="gv1-dcats__name">Ver toda la tienda</span></a></li>',
			esc_url( $shop ),
			self::icon( 'bag' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
		echo '</ul>';

		return ob_get_clean();
	}

	public static function category_icon( $term ) {
		$name = remove_accents( strtolower( $term->name . ' ' . $term->slug ) );
		$map  = array(
			'oferta'  => 'tag',
			'perfum'  => 'drop',
			'bolso'   => 'bag',
			'gafa'    => 'glasses',
			'joya'    => 'gem',
			'nino'    => 'smile',
			'mujer'   => 'watch',
			'hombre'  => 'watch',
			'reloj'   => 'watch',
		);
		foreach ( $map as $needle => $icon ) {
			if ( false !== strpos( $name, $needle ) ) {
				return $icon;
			}
		}

		return 'tag';
	}

	/**
	 * Íconos de trazo (24×24, estilo Lucide), como los de TeDotamos.
	 */
	public static function icon( $name, $class = 'gv1-icon' ) {
		$paths = array(
			'truck'   => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
			'shield'  => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
			'card'    => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
			'headset' => '<path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/><path d="M21 16v2a4 4 0 0 1-4 4h-5"/>',
			'tag'     => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r="1"/>',
			'watch'   => '<circle cx="12" cy="12" r="6"/><path d="M12 10v2l1 1"/><path d="m16.13 7.66-.81-4.05a2 2 0 0 0-2-1.61h-2.68a2 2 0 0 0-2 1.61l-.78 4.05"/><path d="m7.88 16.36.8 4a2 2 0 0 0 2 1.61h2.72a2 2 0 0 0 2-1.61l.81-4.05"/>',
			'drop'    => '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
			'bag'     => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
			'glasses' => '<circle cx="6" cy="15" r="4"/><circle cx="18" cy="15" r="4"/><path d="M14 15a2 2 0 0 0-2-2 2 2 0 0 0-2 2"/><path d="M2.5 13 5 7c.7-1.3 1.4-2 3-2"/><path d="M21.5 13 19 7c-.7-1.3-1.5-2-3-2"/>',
			'gem'     => '<path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/>',
			'smile'   => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/>',
			'arrow'   => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
			'chevron' => '<path d="m9 18 6-6-6-6"/>',
			'check'   => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
		);
		if ( ! isset( $paths[ $name ] ) ) {
			$name = 'check';
		}

		return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $name ] . '</svg>';
	}

	public static function icon_names() {
		return 'truck, shield, card, headset, tag, watch, drop, bag, glasses, gem, smile, check';
	}
}
