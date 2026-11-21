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
require_once __DIR__ . '/class-design-v1.php';
require_once __DIR__ . '/class-catalog.php';
require_once __DIR__ . '/class-product.php';
require_once __DIR__ . '/class-seo.php';
require_once __DIR__ . '/class-translations.php';
require_once __DIR__ . '/class-content.php';
require_once __DIR__ . '/class-migration.php';

Grupsity_Assets::init();
Grupsity_Updater::init();
Grupsity_Designs::init();
Grupsity_Design_V1::init();
Grupsity_Catalog::init();
Grupsity_Product::init();
Grupsity_Seo::init();
Grupsity_Translations::init();
Grupsity_Content::init();

// Fuera de is_admin(): "after_switch_theme" puede dispararse en la primera visita al front.
Grupsity_Migration::init();

if ( is_admin() ) {
	Grupsity_Admin::init();
}
