
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
    
    $custom_word = function_exists('shav_get_field')
        ? shav_get_field($product->get_id(), '_title_accent_custom_word', 'title_accent')
        : $product->get_meta('_title_accent_custom_word');
}
$full_title = get_the_title();

if ($accent_on) {
    $word_to_highlight = '';
    
    if (!empty($custom_word) && stripos($full_title, $custom_word) !== false) {
        $word_to_highlight = $custom_word;
    } elseif (stripos($full_title, 'woman') !== false) {
        // Szukaj dokładnie słowa woman ignorując wielkość liter
        preg_match('/\bwoman\b/i', $full_title, $matches);
        if (!empty($matches)) {
            $word_to_highlight = $matches[0];
        } else {
            $word_to_highlight = 'Woman';
        }
    } else {
        $parts = preg_split('/\s+/', trim($full_title));
        if (count($parts) > 1) {
            $word_to_highlight = array_pop($parts);
        }
    }
    
    if (!empty($word_to_highlight)) {
        $pattern = '/' . preg_quote($word_to_highlight, '/') . '/i';
        $title_html = preg_replace_callback($pattern, function($matches) {
            return '</span> <span class="product_title__accent">' . esc_html($matches[0]) . '</span><span class="product_title__main">';
        }, esc_html($full_title), 1);
        
        // Zastąpienie dodaje tag zamykający i otwierający wokół akcentu, 
        // więc musimy owinąć całość w główny tag
        $final_html = '<span class="product_title__main">' . $title_html . '</span>';
        
        // Wyczyść ewentualnie puste spany na początku i końcu
        $final_html = str_replace('<span class="product_title__main"></span>', '', $final_html);
        $final_html = str_replace('<span class="product_title__main"> </span>', ' ', $final_html);

        echo '<h1 class="product_title entry-title">' . $final_html . '</h1>';
    } else {
        the_title('<h1 class="product_title entry-title">', '</h1>');
    }
} else {
    the_title('<h1 class="product_title entry-title">', '</h1>');
}
if (function_exists('get_field') && get_field('product_custom_name')) {
	the_field('product_custom_name');
}
