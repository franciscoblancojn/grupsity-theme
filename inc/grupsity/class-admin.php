<?php
/**
 * Grupsity: página "Apariencia → Grupsity" para gestionar actualizaciones del tema,
 * el diseño activo y la migración del Customizer de Martfury.
 *
 * Nuevas opciones: añadirlas a DEFAULTS, a sanitize() y a render_general_tab().
 * Leerlas con Grupsity_Admin::get_option( 'clave' ).
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Admin {

	const PAGE   = 'grupsity';
	const OPTION = 'grupsity_options';

	const DEFAULTS = array(
		'cache_hours'   => 6, // cada cuánto se consulta GitHub
		'update_notice' => 1, // aviso en el escritorio cuando hay versión nueva
		'design'        => 'martfury', // diseño activo (ver Grupsity_Designs::DESIGNS)
		'v1_topbar'     => '', // diseño V1: vacío = Grupsity_Design_V1::DEFAULTS
		'v1_nav'        => '',
		'v1_hotline'    => '',
		'catalog_nav'   => 'infinite', // ver Grupsity_Catalog::NAV_CHOICES
	);

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_post_grupsity_check_updates', array( __CLASS__, 'handle_check_updates' ) );
		add_action( 'admin_notices', array( __CLASS__, 'update_notice' ) );
		add_action( 'admin_notices', array( __CLASS__, 'migration_notice' ) );
	}

	public static function get_option( $key ) {
		$options = wp_parse_args( (array) get_option( self::OPTION, array() ), self::DEFAULTS );

		return isset( $options[ $key ] ) ? $options[ $key ] : null;
	}

	public static function page_url( $tab = 'updates', $args = array() ) {
		return add_query_arg( array_merge( array( 'page' => self::PAGE, 'tab' => $tab ), $args ), admin_url( 'themes.php' ) );
	}

	public static function add_menu() {
		add_theme_page( 'Grupsity', 'Grupsity', 'edit_theme_options', self::PAGE, array( __CLASS__, 'render_page' ) );
	}

	public static function register_settings() {
		register_setting(
			'grupsity_general',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => self::DEFAULTS,
			)
		);
	}

	public static function sanitize( $input ) {
		$input = (array) $input;

		$hours = isset( $input['cache_hours'] ) ? absint( $input['cache_hours'] ) : self::DEFAULTS['cache_hours'];
		$clean = array(
			'cache_hours'   => min( 24, max( 1, $hours ) ),
			'update_notice' => empty( $input['update_notice'] ) ? 0 : 1,
			'design'        => isset( $input['design'], Grupsity_Designs::DESIGNS[ $input['design'] ] ) ? $input['design'] : 'martfury',
			'v1_topbar'     => isset( $input['v1_topbar'] ) ? sanitize_textarea_field( $input['v1_topbar'] ) : '',
			'v1_nav'        => isset( $input['v1_nav'] ) ? sanitize_textarea_field( $input['v1_nav'] ) : '',
			'v1_hotline'    => isset( $input['v1_hotline'] ) ? sanitize_text_field( $input['v1_hotline'] ) : '',
			'catalog_nav'   => isset( $input['catalog_nav'], Grupsity_Catalog::NAV_CHOICES[ $input['catalog_nav'] ] ) ? $input['catalog_nav'] : '',
		);

		// Si cambia la frecuencia, que la próxima consulta use el nuevo valor.
		if ( (int) $clean['cache_hours'] !== (int) self::get_option( 'cache_hours' ) && class_exists( 'Grupsity_Updater' ) ) {
			delete_transient( Grupsity_Updater::CACHE_KEY );
		}

		return $clean;
	}

	public static function handle_check_updates() {
		if ( ! current_user_can( 'update_themes' ) ) {
			wp_die( esc_html__( 'No tienes permisos para actualizar temas.', 'martfury' ), 403 );
		}
		check_admin_referer( 'grupsity_check_updates' );

		Grupsity_Updater::force_check();

		wp_safe_redirect( self::page_url( 'updates', array( 'checked' => 1 ) ) );
		exit;
	}

	public static function update_notice() {
		if ( ! self::get_option( 'update_notice' ) || ! current_user_can( 'update_themes' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_' . self::PAGE === $screen->id ) {
			return;
		}
		$update = Grupsity_Updater::pending_update();
		if ( ! $update ) {
			return;
		}

		printf(
			'<div class="notice notice-info"><p><strong>Grupsity %1$s</strong> está disponible (instalada: %2$s). <a href="%3$s">Ver actualización</a></p></div>',
			esc_html( $update['new_version'] ),
			esc_html( Grupsity_Updater::installed_version() ),
			esc_url( self::page_url() )
		);
	}

	/**
	 * Grupsity: aviso cuando la carpeta activa no tiene la configuración del Customizer.
	 * No copia nada por su cuenta: solo enlaza a la pestaña Migración.
	 */
	public static function migration_notice() {
		if ( ! current_user_can( 'edit_theme_options' ) || ! Grupsity_Migration::needs_import() ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_' . self::PAGE === $screen->id ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p><strong>Grupsity:</strong> la carpeta activa <code>%1$s</code> no tiene la configuración del Customizer de Martfury (faltan %2$d de %3$d opciones), por eso el sitio sale con el header, el logo y los textos por defecto. <a href="%4$s">Revisar y copiar</a></p></div>',
			esc_html( get_stylesheet() ),
			count( Grupsity_Migration::missing_keys() ),
			count( Grupsity_Migration::source_mods() ),
			esc_url( self::page_url( 'migration' ) )
		);
	}

	// ------------------------------------------------------------------ vistas

	public static function render_page() {
		$tabs = array(
			'updates'   => 'Actualizaciones',
			'general'   => 'General',
			'migration' => 'Migración',
			'info'      => 'Información',
		);
		$tab  = isset( $_GET['tab'] ) && isset( $tabs[ $_GET['tab'] ] ) ? sanitize_key( $_GET['tab'] ) : 'updates'; // phpcs:ignore WordPress.Security.NonceVerification
		?>
		<div class="wrap">
			<h1>Grupsity</h1>
			<nav class="nav-tab-wrapper">
				<?php foreach ( $tabs as $key => $label ) : ?>
					<a href="<?php echo esc_url( self::page_url( $key ) ); ?>" class="nav-tab <?php echo $key === $tab ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</nav>
			<?php
			$message = get_transient( 'grupsity_admin_message' );
			if ( $message ) {
				delete_transient( 'grupsity_admin_message' );
				printf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $message['ok'] ? 'success' : 'error', esc_html( $message['text'] ) );
			}
			if ( 'general' === $tab ) {
				self::render_general_tab();
			} elseif ( 'migration' === $tab ) {
				self::render_migration_tab();
			} elseif ( 'info' === $tab ) {
				self::render_info_tab();
			} else {
				self::render_updates_tab();
			}
			?>
		</div>
		<?php
	}

	private static function render_updates_tab() {
		$installed = Grupsity_Updater::installed_version();
		$release   = Grupsity_Updater::get_latest_release();
		$pending   = Grupsity_Updater::pending_update();
		$can       = current_user_can( 'update_themes' );
		$date_fmt  = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		if ( isset( $_GET['checked'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			echo '<div class="notice notice-success is-dismissible"><p>Se consultó GitHub.</p></div>';
		}
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">Versión instalada</th>
				<td><strong><?php echo esc_html( $installed ); ?></strong></td>
			</tr>
			<tr>
				<th scope="row">Última versión publicada</th>
				<td>
					<?php if ( ! empty( $release['error'] ) ) : ?>
						<span style="color:#b32d2e"><?php echo esc_html( $release['error'] ); ?></span>
					<?php else : ?>
						<strong><?php echo esc_html( $release['version'] ); ?></strong>
						— <?php echo esc_html( mysql2date( $date_fmt, get_date_from_gmt( gmdate( 'Y-m-d H:i:s', strtotime( $release['published_at'] ) ) ) ) ); ?>
						— <a href="<?php echo esc_url( $release['url'] ); ?>" target="_blank" rel="noopener">ver en GitHub</a>
						<?php if ( 'zipball' === $release['package_type'] ) : ?>
							<p class="description">La release no tiene el asset <code><?php echo esc_html( Grupsity_Updater::ASSET_NAME ); ?></code>: se instalará el código fuente del tag.</p>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Estado</th>
				<td>
					<?php if ( $pending ) : ?>
						<span class="dashicons dashicons-update" style="color:#dba617"></span>
						Hay una actualización disponible: <strong><?php echo esc_html( $pending['new_version'] ); ?></strong>
					<?php elseif ( ! empty( $release['version'] ) && version_compare( $release['version'], $installed, '>' ) ) : ?>
						<span class="dashicons dashicons-info" style="color:#2271b1"></span>
						Hay una versión nueva en GitHub; pulsa "Buscar actualizaciones" para que WordPress la registre.
					<?php else : ?>
						<span class="dashicons dashicons-yes-alt" style="color:#00a32a"></span>
						El tema está al día.
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Última consulta</th>
				<td>
					<?php echo esc_html( isset( $release['checked_at'] ) ? wp_date( $date_fmt, $release['checked_at'] ) : '—' ); ?>
					<p class="description">
						Repositorio: <a href="<?php echo esc_url( 'https://github.com/' . Grupsity_Updater::REPO ); ?>" target="_blank" rel="noopener"><?php echo esc_html( Grupsity_Updater::REPO ); ?></a>
						· Token de GitHub: <?php echo Grupsity_Updater::token() ? 'definido en wp-config.php' : 'no (no hace falta para repos públicos)'; ?>
					</p>
				</td>
			</tr>
		</table>

		<?php if ( $can ) : ?>
			<div style="display:flex;gap:8px;align-items:center;margin:1em 0">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline">
					<input type="hidden" name="action" value="grupsity_check_updates">
					<?php wp_nonce_field( 'grupsity_check_updates' ); ?>
					<?php submit_button( 'Buscar actualizaciones', 'secondary', 'submit', false ); ?>
				</form>
				<?php if ( $pending ) : ?>
					<a href="<?php echo esc_url( Grupsity_Updater::upgrade_url() ); ?>" class="button button-primary"
						onclick="return confirm('¿Actualizar el tema Grupsity a la versión <?php echo esc_js( $pending['new_version'] ); ?>? Se recomienda tener un backup.');">
						Actualizar a <?php echo esc_html( $pending['new_version'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p class="description">Tu usuario no tiene permisos para actualizar temas.</p>
		<?php endif; ?>

		<details style="margin:1em 0">
			<summary style="cursor:pointer">Diagnóstico de actualizaciones</summary>
			<table class="widefat striped" style="margin-top:8px;max-width:900px">
				<tbody>
					<?php foreach ( Grupsity_Updater::diagnostics() as $label => $value ) : ?>
						<tr><th style="width:280px"><?php echo esc_html( $label ); ?></th><td><code><?php echo esc_html( $value ); ?></code></td></tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</details>

		<?php if ( ! empty( $release['notes'] ) ) : ?>
			<h2>Notas de la versión <?php echo esc_html( $release['version'] ); ?></h2>
			<div class="card" style="max-width:none">
				<pre style="white-space:pre-wrap;font-family:inherit;margin:0"><?php echo esc_html( $release['notes'] ); ?></pre>
			</div>
		<?php endif; ?>
		<?php
	}

	private static function render_general_tab() {
		$name = self::OPTION;
		?>
		<form method="post" action="options.php">
			<?php settings_fields( 'grupsity_general' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="grupsity-design">Diseño</label></th>
					<td>
						<select id="grupsity-design" name="<?php echo esc_attr( $name ); ?>[design]">
							<?php foreach ( Grupsity_Designs::DESIGNS as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( Grupsity_Designs::current(), $key ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description">
							<strong>Martfury</strong> es el tema como está ahora. <strong>JomaShop</strong> aplica en todo el sitio la
							forma de <a href="https://www.jomashop.com/" target="_blank" rel="noopener">jomashop.com</a>
							(franja superior oscura, buscador grande, menú en mayúsculas, filtros limpios, tarjetas sin cajas y
							footer oscuro, tipografía Inter) con los colores de Grupsity (verde #93b700, tinta y blanco). No cambia el contenido ni la configuración del
							Customizer; al volver a Martfury el sitio queda como estaba. <strong>V1</strong> usa la estructura del tema TeDotamos (franja con avisos, buscador, barra con
							«Categorías», enlaces y teléfono, contenido en container de 1320 px) con los colores y fuentes de
							Grupsity; sus textos se configuran abajo. Al cambiar de diseño se purga la caché.
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="grupsity-catalog-nav">Navegación del catálogo</label></th>
					<td>
						<select id="grupsity-catalog-nav" name="<?php echo esc_attr( $name ); ?>[catalog_nav]">
							<?php foreach ( Grupsity_Catalog::NAV_CHOICES as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( Grupsity_Catalog::nav(), $key ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description">Tienda, categorías, marcas y búsqueda. Con scroll infinito, al llegar al final se cargan los productos de la página siguiente (sin JavaScript, o para los buscadores, es un enlace normal).</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Diseño V1</th>
					<td>
						<p><label for="grupsity-v1-topbar"><strong>Franja superior</strong> — una línea por aviso: <code>ícono|texto|enlace</code> (el enlace es opcional)</label></p>
						<textarea id="grupsity-v1-topbar" name="<?php echo esc_attr( $name ); ?>[v1_topbar]" rows="3" class="large-text code" placeholder="<?php echo esc_attr( Grupsity_Design_V1::DEFAULTS['v1_topbar'] ); ?>"><?php echo esc_textarea( self::get_option( 'v1_topbar' ) ); ?></textarea>
						<p class="description">Íconos: <?php echo esc_html( Grupsity_Design_V1::icon_names() ); ?>. Vacío = los avisos de ejemplo.</p>
						<p><label for="grupsity-v1-nav"><strong>Barra de menú</strong> — una línea por enlace: <code>texto|enlace</code> (<code>/hombre/</code> o una URL completa)</label></p>
						<textarea id="grupsity-v1-nav" name="<?php echo esc_attr( $name ); ?>[v1_nav]" rows="5" class="large-text code" placeholder="<?php echo esc_attr( Grupsity_Design_V1::DEFAULTS['v1_nav'] ); ?>"><?php echo esc_textarea( self::get_option( 'v1_nav' ) ); ?></textarea>
						<p><label for="grupsity-v1-hotline"><strong>Teléfono</strong> (al final de la barra)</label></p>
						<input type="text" id="grupsity-v1-hotline" name="<?php echo esc_attr( $name ); ?>[v1_hotline]" value="<?php echo esc_attr( self::get_option( 'v1_hotline' ) ); ?>" placeholder="<?php echo esc_attr( Grupsity_Design_V1::DEFAULTS['v1_hotline'] ); ?>" class="regular-text">
						<p class="description">Solo se usan con el diseño V1. El desplegable «Categorías» se arma solo con las categorías de producto.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="grupsity-cache-hours">Consultar GitHub cada</label></th>
					<td>
						<input type="number" min="1" max="24" id="grupsity-cache-hours" name="<?php echo esc_attr( $name ); ?>[cache_hours]" value="<?php echo esc_attr( self::get_option( 'cache_hours' ) ); ?>" class="small-text"> horas
						<p class="description">Entre 1 y 24. Sin token, GitHub permite 60 consultas por hora.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Avisos</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[update_notice]" value="1" <?php checked( self::get_option( 'update_notice' ) ); ?>>
							Mostrar un aviso en el escritorio cuando haya una versión nueva del tema
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	private static function render_migration_tab() {
		$status   = Grupsity_Migration::status();
		$source   = Grupsity_Migration::source_mods();
		$current  = get_theme_mods();
		$backup   = get_option( Grupsity_Migration::BACKUP );
		$date_fmt = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
		$can      = current_user_can( 'edit_theme_options' );
		?>
		<p>WordPress guarda la configuración del Customizer (header, logo, colores, barra de promoción,
			ubicación de menús, CSS adicional…) <strong>por tema</strong>. Aquí se copia la de Martfury a Grupsity.
			La copia se hace sola la primera vez que se activa Grupsity.</p>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">Estado</th>
				<td>
					<?php if ( Grupsity_Migration::needs_import() ) : ?>
						<span class="dashicons dashicons-warning" style="color:#b32d2e"></span>
						A la carpeta activa <code><?php echo esc_html( get_stylesheet() ); ?></code> le faltan
						<strong><?php echo count( Grupsity_Migration::missing_keys() ); ?></strong> opciones de Martfury:
						el sitio usa el header, el logo, los colores y los textos por defecto.
						<?php if ( is_array( get_option( Grupsity_Migration::FLAG ) ) ) : ?>
							(Ya hubo una copia, pero no cubre esta carpeta.)
						<?php endif; ?>
					<?php elseif ( $status ) : ?>
						<span class="dashicons dashicons-yes-alt" style="color:#00a32a"></span>
						Migrado el <?php echo esc_html( wp_date( $date_fmt, $status['at'] ) ); ?>
						(<?php echo esc_html( $status['count'] ); ?> opciones, <?php echo esc_html( $status['how'] ); ?>).
					<?php else : ?>
						<span class="dashicons dashicons-warning" style="color:#dba617"></span>
						Todavía no se ha copiado la configuración de Martfury.
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Opciones</th>
				<td>Martfury: <strong><?php echo count( $source ); ?></strong> · Grupsity (<code>theme_mods_<?php echo esc_html( get_stylesheet() ); ?></code>): <strong><?php echo count( (array) $current ); ?></strong></td>
			</tr>
		</table>
		<?php if ( $can ) : ?>
			<div style="display:flex;gap:8px;margin:1em 0">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
					onsubmit="return confirm('Se reemplazará la configuración del Customizer de Grupsity por la de Martfury. Se guarda una copia de la actual para poder restaurarla. ¿Continuar?');">
					<input type="hidden" name="action" value="grupsity_import_martfury">
					<?php wp_nonce_field( 'grupsity_import_martfury' ); ?>
					<?php submit_button( 'Copiar configuración desde Martfury', 'primary', 'submit', false, $source ? array() : array( 'disabled' => 'disabled' ) ); ?>
				</form>
				<?php if ( is_array( $backup ) ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
						onsubmit="return confirm('¿Restaurar la configuración que tenía Grupsity antes de la última copia?');">
						<input type="hidden" name="action" value="grupsity_restore_backup">
						<?php wp_nonce_field( 'grupsity_restore_backup' ); ?>
						<?php submit_button( 'Restaurar copia anterior (' . wp_date( $date_fmt, $backup['saved_at'] ) . ')', 'secondary', 'submit', false ); ?>
					</form>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
	}

	private static function render_info_tab() {
		$theme = wp_get_theme( Grupsity_Updater::slug() );
		?>
		<table class="form-table" role="presentation">
			<tr><th scope="row">Tema</th><td><?php echo esc_html( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) ); ?></td></tr>
			<tr><th scope="row">Carpeta</th><td><code><?php echo esc_html( $theme->get_stylesheet_directory() ); ?></code></td></tr>
			<tr><th scope="row">Basado en</th><td>Martfury 3.2.8 (DrFuri). No recibe actualizaciones de Martfury.</td></tr>
			<tr><th scope="row">Update URI</th><td><code><?php echo esc_html( $theme->get( 'UpdateURI' ) ); ?></code></td></tr>
			<tr><th scope="row">Diseño activo</th><td><?php echo esc_html( Grupsity_Designs::DESIGNS[ Grupsity_Designs::current() ] ); ?></td></tr>
			<tr><th scope="row">Documentación</th><td><code>GRUPSITY.md</code> en la carpeta del tema.</td></tr>
		</table>
		<?php
	}
}
