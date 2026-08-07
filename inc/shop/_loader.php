<?php
/**
 * Bootstrap modułu sklepu.
 * Wszystkie pliki shop-related (sekcje, ramki, banner, badge'y) ładowane stąd.
 */

defined('ABSPATH') || exit;

// require_once __DIR__ . '/fse-bypass.php';
// require_once __DIR__ . '/sections-config.php';
require_once __DIR__ . '/frame-variant.php';
require_once __DIR__ . '/product-img.php';

if (is_admin()) {
    // require_once __DIR__ . '/admin-page.php';
}
