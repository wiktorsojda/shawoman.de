
<?php

/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Tytul: jesli checkbox `_title_accent_last_word` aktywny w produkcie — ostatnie
// slowo wyswietl czcionka akcentowa (Dolce, kursywa). Figma 390:1473.
global $product;
$accent_on = false;
if ($product) {
    $val = function_exists('shav_get_field')
        ? shav_get_field($product->get_id(), '_title_accent_last_word', 'title_accent')
        : $product->get_meta('_title_accent_last_word');
    $accent_on = ($val === 'yes');
}
$full_title = get_the_title();
$parts = preg_split('/\s+/', trim($full_title));

if ($accent_on && count($parts) > 1) {
    $accent = array_pop($parts);
    $main = implode(' ', $parts);
    printf(
        '<h1 class="product_title entry-title"><span class="product_title__main">%s</span> <span class="product_title__accent">%s</span></h1>',
        esc_html($main),
        esc_html($accent)
    );
} else {
    the_title('<h1 class="product_title entry-title">', '</h1>');
}
if (function_exists('get_field') && get_field('product_custom_name')) {
	the_field('product_custom_name');
}
