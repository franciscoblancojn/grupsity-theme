<?php
/**
 * Grupsity: secciones por categoría en la portada (diseño V1).
 *
 * Cada sección repite lo que la portada ya tiene para los relojes: un título, un carrusel de
 * logos de marcas y un carrusel de banners. Sirve para que el visitante identifique las
 * categorías por secciones (Perfumería, Accesorios…) sin tocar la página de Elementor.
 *
 * Se configura en Apariencia → Grupsity → General → «Secciones de la portada» (opción
 * v1_home_sections), un bloque por sección:
 *
 *   # Perfumería|/perfumeria/              ← título y enlace «Ver todo» (opcional)
 *   marca|1234|/Marca/carolina-herrera/    ← logo: ID de la biblioteca de medios o URL, y enlace
 *   banner|https://…/banner.jpg|/perfumeria/
 *
 * Las secciones se imprimen justo antes del carrusel «Ofertas del día» de la portada
 * (primer widget martfury-product-deals-carousel), con el gancho
 * elementor/frontend/widget/before_render. Si la portada no lo tiene, no salen.
 * Los carruseles son CSS (scroll-snap) con flechas y un JS pequeño; sin JS se deslizan igual.
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Home_Sections {

	/** Widget de Elementor antes del cual van las secciones. */
	const ANCHOR_WIDGET = 'martfury-product-deals-carousel';

	/** Ya se imprimieron (solo una vez por página). */
	private static $done = false;

	public static function init() {
		if ( 'v1' !== Grupsity_Designs::current() ) {
			return;
		}
		add_action( 'elementor/frontend/widget/before_render', array( __CLASS__, 'before_widget' ) );
	}

	/**
	 * Secciones de la opción v1_home_sections.
	 *
	 * @return array[] Cada una: title, url, brands[], banners[] (cada ítem: image, url).
	 */
	public static function sections() {
		$raw = (string) Grupsity_Admin::get_option( 'v1_home_sections' );
		$out = array();
		$cur = null;
		foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}
			if ( '#' === $line[0] ) {
				if ( $cur ) {
					$out[] = $cur;
				}
				$parts = array_pad( array_map( 'trim', explode( '|', ltrim( $line, "# \t" ), 2 ) ), 2, '' );
				$cur   = array(
					'title'   => $parts[0],
					'url'     => $parts[1],
					'brands'  => array(),
					'banners' => array(),
				);
				continue;
			}
			$parts = array_pad( array_map( 'trim', explode( '|', $line, 3 ) ), 3, '' );
			$kind  = strtolower( $parts[0] );
			if ( ! $cur || '' === $parts[1] ) {
				continue;
			}
			$item = array(
				'image' => $parts[1],
				'url'   => $parts[2],
			);
			if ( in_array( $kind, array( 'marca', 'logo' ), true ) ) {
				$cur['brands'][] = $item;
			} elseif ( 'banner' === $kind ) {
				$cur['banners'][] = $item;
			}
		}
		if ( $cur ) {
			$out[] = $cur;
		}

		return array_values(
			array_filter(
				$out,
				function ( $section ) {
					return $section['brands'] || $section['banners'];
				}
			)
		);
	}

	/**
	 * Antes de «Ofertas del día» en la portada.
	 *
	 * @param \Elementor\Widget_Base $widget
	 */
	public static function before_widget( $widget ) {
		if ( self::$done
			|| ! is_front_page()
			|| ! is_object( $widget )
			|| ! method_exists( $widget, 'get_name' )
			|| self::ANCHOR_WIDGET !== $widget->get_name() ) {
			return;
		}
		self::$done = true;
		$sections   = self::sections();
		if ( ! $sections ) {
			return;
		}
		foreach ( $sections as $i => $section ) {
			self::render_section( $section, $i );
		}
		self::script();
	}

	private static function render_section( $section, $index ) {
		$title_id = 'gv1-hsec-title-' . $index;
		printf( '<section class="gv1-hsec"%s>', '' !== $section['title'] ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : '' );
		if ( '' !== $section['title'] ) {
			echo '<div class="gv1-hsec__head">';
			printf( '<h3 id="%s" class="gv1-hsec__title">%s</h3>', esc_attr( $title_id ), esc_html( $section['title'] ) );
			$url = Grupsity_Design_V1::url( $section['url'] );
			if ( $url ) {
				printf( '<a class="gv1-hsec__more" href="%s">Ver todo <span aria-hidden="true">→</span></a>', esc_url( $url ) );
			}
			echo '</div>';
		}
		if ( $section['brands'] ) {
			self::render_rail( $section['brands'], 'brands', 'Marcas', 'medium' );
		}
		if ( $section['banners'] ) {
			self::render_rail( $section['banners'], 'banners', 'Banners', 'large' );
		}
		echo '</section>';
	}

	private static function render_rail( $items, $kind, $label, $size ) {
		printf( '<div class="gv1-rail gv1-rail--%s" data-gv1-rail>', esc_attr( $kind ) );
		printf( '<button type="button" class="gv1-rail__btn gv1-rail__btn--prev" aria-label="%s" hidden>%s</button>', esc_attr( $label . ': anterior' ), self::chevron( 'left' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '<ul class="gv1-rail__track" aria-label="%s">', esc_attr( $label ) );
		foreach ( $items as $item ) {
			$img = self::image( $item['image'], $size );
			if ( '' === $img ) {
				continue;
			}
			$url = Grupsity_Design_V1::url( $item['url'] );
			printf(
				'<li class="gv1-rail__item">%s</li>',
				$url ? '<a class="gv1-rail__link" href="' . esc_url( $url ) . '">' . $img . '</a>' : '<span class="gv1-rail__link">' . $img . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</ul>';
		printf( '<button type="button" class="gv1-rail__btn gv1-rail__btn--next" aria-label="%s" hidden>%s</button>', esc_attr( $label . ': siguiente' ), self::chevron( 'right' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}

	/**
	 * Imagen desde un ID de la biblioteca de medios o una URL. Si la URL es de la biblioteca,
	 * se usa el adjunto (srcset y tamaños); si no, la URL tal cual.
	 */
	private static function image( $value, $size ) {
		$id = ctype_digit( $value ) ? (int) $value : 0;
		if ( ! $id && preg_match( '#^(https?:)?//|^/#', $value ) ) {
			$id = attachment_url_to_postid( $value );
		}
		if ( $id ) {
			$html = wp_get_attachment_image( $id, $size, false, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
			if ( $html ) {
				return $html;
			}
		}
		if ( ! preg_match( '#^(https?:)?//|^/#', $value ) ) {
			return '';
		}

		return sprintf( '<img src="%s" alt="" loading="lazy" decoding="async">', esc_url( $value ) );
	}

	private static function chevron( $dir ) {
		$path = 'left' === $dir ? 'M15 6l-6 6 6 6' : 'M9 6l6 6-6 6';

		return '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="' . $path . '" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}

	/**
	 * Flechas de los carruseles: avanzan una «página» del carril y se ocultan si no hay más.
	 * Los logos de marcas avanzan solos cada 3,5 s (como el carrusel de marcas de los relojes),
	 * salvo con el ratón encima, con el foco dentro o con «reducir movimiento».
	 */
	private static function script() {
		?>
<script>
(function () {
	var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	Array.prototype.forEach.call(document.querySelectorAll('[data-gv1-rail]'), function (rail) {
		var track = rail.querySelector('.gv1-rail__track');
		var prev = rail.querySelector('.gv1-rail__btn--prev');
		var next = rail.querySelector('.gv1-rail__btn--next');
		if (!track || !prev || !next) {
			return;
		}
		var paused = false;
		function max() { return track.scrollWidth - track.clientWidth - 2; }
		function update() {
			var overflow = max() > 0;
			prev.hidden = next.hidden = !overflow;
			prev.disabled = track.scrollLeft <= 2;
			next.disabled = track.scrollLeft >= max();
			rail.classList.toggle('is-scrollable', overflow);
		}
		function go(dir) {
			track.scrollBy({ left: dir * track.clientWidth, behavior: still ? 'auto' : 'smooth' });
		}
		prev.addEventListener('click', function () { go(-1); });
		next.addEventListener('click', function () { go(1); });
		track.addEventListener('scroll', update, { passive: true });
		window.addEventListener('resize', update);
		window.addEventListener('load', update);
		update();
		if (still || !rail.classList.contains('gv1-rail--brands')) {
			return;
		}
		['mouseenter', 'focusin', 'touchstart'].forEach(function (e) { rail.addEventListener(e, function () { paused = true; }, { passive: true }); });
		['mouseleave', 'focusout'].forEach(function (e) { rail.addEventListener(e, function () { paused = false; }); });
		setInterval(function () {
			if (paused || document.hidden || max() <= 0) {
				return;
			}
			if (track.scrollLeft >= max()) {
				track.scrollTo({ left: 0, behavior: 'smooth' });
			} else {
				go(1);
			}
		}, 3500);
	});
})();
</script>
		<?php
	}
}
