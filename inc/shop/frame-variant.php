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

// =============================================================================
// Badge'y na karcie sklepu — NOWOSC / BESTSELLER
// (RABAT -X% liczony automatycznie z sale_price)
// =============================================================================

add_action('woocommerce_product_options_general_product_data', function () {
    echo '<div class="options_group">';
    echo '<h3 style="padding:0 12px;margin:8px 0 4px;">Badge na karcie sklepu</h3>';
    echo '<p style="padding:0 12px;color:#666;font-size:12px;">Wybierz jeden badge w prawym górnym rogu karty (lub żaden). Priorytet: NOWOŚĆ → BESTSELLER → Własny.</p>';

    woocommerce_wp_checkbox([
        'id'          => '_shav_badge_nowosc',
        'label'       => __('Pokaż badge NOWOŚĆ', 'shav'),
        'description' => __('Pomarańczowo-bronzowy badge "NOWOŚĆ" w prawym górnym rogu karty.', 'shav'),
    ]);

    woocommerce_wp_checkbox([
        'id'          => '_shav_badge_bestseller',
        'label'       => __('Pokaż badge BESTSELLER', 'shav'),
        'description' => __('Pomarańczowo-bronzowy badge "BESTSELLER" w prawym górnym rogu karty.', 'shav'),
    ]);

    echo '<hr style="margin:12px;">';
    echo '<p style="padding:0 12px;color:#666;font-size:12px;"><strong>Własny badge</strong> — wpisz tekst i wybierz kolory (np. "RABAT -22%", "PROMOCJA", "-30%"). Zostaw puste żeby nie pokazywać.</p>';

    woocommerce_wp_text_input([
        'id'          => '_shav_badge_custom_text',
        'label'       => __('Tekst własnego badge', 'shav'),
        'placeholder' => 'RABAT -22%',
        'desc_tip'    => true,
        'description' => __('Tekst wyświetlany w badge. Pusto = brak własnego badge.', 'shav'),
    ]);

    woocommerce_wp_text_input([
        'id'          => '_shav_badge_custom_bg',
        'label'       => __('Kolor tła własnego badge', 'shav'),
        'placeholder' => '#fff0f0',
        'desc_tip'    => true,
        'description' => __('Hex koloru tła (np. #fff0f0). Domyślnie #fff0f0.', 'shav'),
        'type'        => 'color',
    ]);

    woocommerce_wp_text_input([
        'id'          => '_shav_badge_custom_color',
        'label'       => __('Kolor tekstu własnego badge', 'shav'),
        'placeholder' => '#ac0000',
        'desc_tip'    => true,
        'description' => __('Hex koloru tekstu (np. #ac0000). Domyślnie #ac0000.', 'shav'),
        'type'        => 'color',
    ]);

    echo '</div>';
});

add_action('woocommerce_process_product_meta', function ($post_id) {
    $nowosc     = isset($_POST['_shav_badge_nowosc'])     ? 'yes' : 'no';
    $bestseller = isset($_POST['_shav_badge_bestseller']) ? 'yes' : 'no';
    update_post_meta($post_id, '_shav_badge_nowosc', $nowosc);
    update_post_meta($post_id, '_shav_badge_bestseller', $bestseller);

    $custom_text  = isset($_POST['_shav_badge_custom_text'])  ? sanitize_text_field($_POST['_shav_badge_custom_text'])  : '';
    $custom_bg    = isset($_POST['_shav_badge_custom_bg'])    ? sanitize_hex_color($_POST['_shav_badge_custom_bg'])    : '';
    $custom_color = isset($_POST['_shav_badge_custom_color']) ? sanitize_hex_color($_POST['_shav_badge_custom_color']) : '';
    update_post_meta($post_id, '_shav_badge_custom_text',  $custom_text);
    update_post_meta($post_id, '_shav_badge_custom_bg',    $custom_bg);
    update_post_meta($post_id, '_shav_badge_custom_color', $custom_color);
});
