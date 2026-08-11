<?php
/**
 * Meta-field "Ramka karty" na produkcie WooCommerce.
 * Steruje gradientową ramką na karcie produktu w sklepie (/sklep/).
 * Wartości: brak | zloty | srebrny | platynowy | duo | wojownik | handler
 */

defined('ABSPATH') || exit;

if (!function_exists('shav_frame_variant_options')) {
    function shav_frame_variant_options(): array
    {
        return apply_filters('shav_frame_variant_options', [
            ''          => '— brak —',
            'zloty'     => 'Złoty',
            'srebrny'   => 'Srebrny',
            'platynowy' => 'Platynowy',
            'duo'       => 'Dla dwojga',
            'wojownik'  => 'Wojownik',
            'handler'   => 'Handler',
        ]);
    }
}

// Pole select w zakładce "Ogólne" produktu.
add_action('woocommerce_product_options_general_product_data', function () {
    woocommerce_wp_select([
        'id'          => '_shav_frame_variant',
        'label'       => __('Ramka karty (sklep)', 'shav'),
        'description' => __('Gradientowy pasek na górze karty produktu w sklepie.', 'shav'),
        'desc_tip'    => true,
        'options'     => shav_frame_variant_options(),
    ]);
});

add_action('woocommerce_process_product_meta', function ($post_id) {
    $value = isset($_POST['_shav_frame_variant']) ? sanitize_key($_POST['_shav_frame_variant']) : '';
    if (array_key_exists($value, shav_frame_variant_options())) {
        update_post_meta($post_id, '_shav_frame_variant', $value);
    } else {
        delete_post_meta($post_id, '_shav_frame_variant');
    }
});

if (!function_exists('shav_get_product_frame_variant')) {
    function shav_get_product_frame_variant($product_id): string
    {
        $value = get_post_meta($product_id, '_shav_frame_variant', true);
        return is_string($value) ? $value : '';
    }
}


