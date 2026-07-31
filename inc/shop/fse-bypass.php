<?php
/**
 * Wymusza klasyczny PHP template dla /sklep/ (archive-product).
 *
 * WooCommerce 8+ z FSE domyslnie laduje block template `archive-product`
 * (Query Loop z blokow) i ignoruje override z motywu `woocommerce/archive-product.php`.
 *
 * Ten filtr mowi WC: "dla archive-product NIE uzywaj block templatu" — wtedy
 * naturalny fallback do klasycznego PHP zadziala, i nasz archive-product.php
 * z sekcjami (shav_get_shop_sections) renderuje sie poprawnie.
 *
 * Single-product NIE jest dotkniety — tam dalej dziala FSE jezeli go uzywasz.
 */

defined('ABSPATH') || exit;

add_filter('woocommerce_has_block_template', function ($has_block_template, $template_name) {
    if ($template_name === 'archive-product' || $template_name === 'taxonomy-product_cat') {
        return false;
    }
    return $has_block_template;
}, 10, 2);

// Drugi filtr — niektore wersje WC sprawdzaja tez tak:
add_filter('woocommerce_blocks_template_should_use_block_template', function ($should_use, $template_name) {
    if ($template_name === 'archive-product' || $template_name === 'taxonomy-product_cat') {
        return false;
    }
    return $should_use;
}, 10, 2);
