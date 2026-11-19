<?php
/**
 * Grupsity: carga el código propio del tema (todo lo que no viene de Martfury).
 *
 * @package Grupsity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-assets.php';
require_once __DIR__ . '/class-updater.php';
require_once __DIR__ . '/class-admin.php';
require_once __DIR__ . '/class-designs.php';
require_once __DIR__ . '/class-migration.php';

Grupsity_Assets::init();
Grupsity_Updater::init();
Grupsity_Designs::init();

// Fuera de is_admin(): "after_switch_theme" puede dispararse en la primera visita al front.
Grupsity_Migration::init();

if ( is_admin() ) {
	Grupsity_Admin::init();
}
