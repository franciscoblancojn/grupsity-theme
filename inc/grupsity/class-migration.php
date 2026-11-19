<?php
/**
 * Grupsity: copia la configuración del Customizer de Martfury a Grupsity.
 *
 * WordPress guarda las opciones del Customizer por carpeta de tema (opción
 * "theme_mods_<carpeta>"). Sin esta copia, al activar Grupsity se pierden el header,
 * logo, colores, barra de promoción, ubicaciones de menús, CSS adicional, etc.
 *
 * - Automática: la primera vez que se activa Grupsity (si nunca se migró).
 * - Manual: acciones admin-post con nonce, lanzadas desde Apariencia → Grupsity → Migración.
 * Antes de sobrescribir se guarda una copia de lo que tenía Grupsity (se puede restaurar).
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Grupsity_Migration {

	const SOURCE     = 'martfury';
	const FLAG       = 'grupsity_mods_migrated';
	const BACKUP     = 'grupsity_theme_mods_backup';

	public static function init() {
		// Prioridad 1: antes de que el tema y WordPress recalculen menús, widgets y caché de estilos.
		add_action( 'after_switch_theme', array( __CLASS__, 'on_activate' ), 1 );
		add_action( 'admin_post_grupsity_import_martfury', array( __CLASS__, 'handle_import' ) );
		add_action( 'admin_post_grupsity_restore_backup', array( __CLASS__, 'handle_restore' ) );
	}

	public static function source_mods() {
		$mods = get_option( 'theme_mods_' . self::SOURCE );

		return is_array( $mods ) ? $mods : array();
	}

	/**
	 * Estado de la copia para la carpeta activa. Grupsity: el sitio puede instalarse como
	 * "grupsity" o como "grupsity-theme-master" (zip de la rama), y cada carpeta tiene su
	 * propia opción theme_mods_<carpeta>; una copia hecha en otra carpeta no cuenta.
	 *
	 * @return array|false
	 */
	public static function status() {
		$flag = get_option( self::FLAG );
		if ( ! is_array( $flag ) ) {
			return false;
		}
		if ( isset( $flag['stylesheet'] ) && get_stylesheet() !== $flag['stylesheet'] ) {
			return false;
		}

		return $flag;
	}

	/**
	 * Opciones de Martfury que faltan en la carpeta activa.
	 *
	 * Si faltan muchas, el sitio sale con el header genérico de Martfury (layout 1, logo
	 * del tema, textos en inglés, color amarillo) en lugar de la configuración de Grupsity.
	 *
	 * @return string[]
	 */
	public static function missing_keys() {
		$current = get_option( 'theme_mods_' . get_stylesheet() );

		return array_values( array_diff( array_keys( self::source_mods() ), array_keys( is_array( $current ) ? $current : array() ) ) );
	}

	/**
	 * true si la carpeta activa no tiene la configuración de Martfury (falta más de un 25 %).
	 */
	public static function needs_import() {
		$source = self::source_mods();
		if ( ! $source || self::SOURCE === get_stylesheet() ) {
			return false;
		}

		return count( self::missing_keys() ) > count( $source ) * 0.25;
	}

	public static function on_activate() {
		if ( ( self::status() && ! self::needs_import() ) || ! self::source_mods() ) {
			return;
		}
		self::import( 'activación automática' );
	}

	/**
	 * Reemplaza las opciones del Customizer de Grupsity por las de Martfury.
	 *
	 * @param string $how Origen de la acción (para el registro).
	 * @return int|WP_Error Número de opciones copiadas.
	 */
	public static function import( $how ) {
		$source = self::source_mods();
		if ( ! $source ) {
			return new WP_Error( 'grupsity_no_source', 'No hay configuración de Martfury (theme_mods_martfury) para copiar.' );
		}

		$target_key = 'theme_mods_' . get_stylesheet();
		update_option( self::BACKUP, array( 'saved_at' => time(), 'mods' => get_option( $target_key ) ), false );
		update_option( $target_key, $source );
		update_option(
			self::FLAG,
			array(
				'from'       => self::SOURCE,
				'stylesheet' => get_stylesheet(),
				'at'         => time(),
				'count'      => count( $source ),
				'how'        => $how,
			),
			false
		);

		self::clear_caches();

		return count( $source );
	}

	public static function restore_backup() {
		$backup = get_option( self::BACKUP );
		if ( ! is_array( $backup ) || ! array_key_exists( 'mods', $backup ) ) {
			return new WP_Error( 'grupsity_no_backup', 'No hay copia de seguridad para restaurar.' );
		}

		$target_key = 'theme_mods_' . get_stylesheet();
		if ( false === $backup['mods'] ) {
			delete_option( $target_key );
		} else {
			update_option( $target_key, $backup['mods'] );
		}
		delete_option( self::FLAG );
		self::clear_caches();

		return true;
	}

	/**
	 * Estilos en línea del tema (transient de 1 día) y caché de página de LiteSpeed.
	 */
	private static function clear_caches() {
		if ( function_exists( 'martfury_clear_inline_styles_cache' ) ) {
			martfury_clear_inline_styles_cache();
		}
		do_action( 'litespeed_purge_all' );
	}

	public static function handle_import() {
		self::guard( 'grupsity_import_martfury' );
		$result = self::import( 'manual' );
		self::redirect( is_wp_error( $result ) ? $result->get_error_message() : sprintf( 'Se copiaron %d opciones desde Martfury.', $result ), ! is_wp_error( $result ) );
	}

	public static function handle_restore() {
		self::guard( 'grupsity_restore_backup' );
		$result = self::restore_backup();
		self::redirect( is_wp_error( $result ) ? $result->get_error_message() : 'Se restauró la configuración anterior de Grupsity.', ! is_wp_error( $result ) );
	}

	private static function guard( $action ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( 'No tienes permisos para cambiar la configuración del tema.', 403 );
		}
		check_admin_referer( $action );
	}

	private static function redirect( $message, $ok ) {
		set_transient( 'grupsity_admin_message', array( 'text' => $message, 'ok' => $ok ), 60 );
		// Grupsity: el aviso se muestra en la pestaña Migración de nuestra página de admin.
		$url = class_exists( 'Grupsity_Admin' ) ? Grupsity_Admin::page_url( 'migration' ) : admin_url( 'themes.php' );
		wp_safe_redirect( $url );
		exit;
	}
}
