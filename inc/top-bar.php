<?php
/**
 * Logika odpowiedzialna za Górny Pasek (Top Bar)
 */

if (!function_exists('shav_get_topbar_data')) {
    function shav_get_topbar_data() {
        // Zabezpieczenie przed błędem, jeśli funkcja automatyzacji nie istnieje
        $active_promo_id = function_exists('blendygo_get_active_promo') ? blendygo_get_active_promo() : false;
        
        // 1. TRYB AUTOMATYCZNY (Aktywna Promocja)
        if ($active_promo_id) {
            $coupon = get_post_meta($active_promo_id, 'promo_coupon_code', true);
            $text = get_post_meta($active_promo_id, 'promo_topbar_text', true);
            $bg = get_post_meta($active_promo_id, 'promo_badge_bg', true);
            $color = get_post_meta($active_promo_id, 'promo_badge_color', true);

            // Jeśli tekst jest pusty, fallback do promo_small_text
            if (empty($text)) {
                $text = get_post_meta($active_promo_id, 'promo_small_text', true);
            }
            if (empty($bg)) $bg = 'rgba(224, 224, 224, 0.8)';
            if (empty($color)) $color = 'linear-gradient(90deg, #630303 1.11%, #C90606 96.67%)';
            
            // Renderujemy nawet jeśli brakuje kuponu (może to być sam tekst ogłoszeniowy)
            return [
                'mode' => 'promo',
                'text' => $text,
                'coupon' => $coupon,
                'bg' => $bg,
                'color' => $color,
            ];
        }

        // 2. TRYB DAILY (Pozapromocyjny)
        $is_enabled = get_option('shav_topbar_enabled', 'yes');
        if ($is_enabled === 'yes') {
            $text = get_option('shav_topbar_text', '');
            $coupon = get_option('shav_topbar_coupon', '');
            $bg = get_option('shav_topbar_bg', '#252525');
            $color = get_option('shav_topbar_color', '#FAFAFA');

            if (!empty($text) || !empty($coupon)) {
                return [
                    'mode' => 'daily',
                    'text' => $text,
                    'coupon' => $coupon,
                    'bg' => $bg,
                    'color' => $color,
                ];
            }
        }

        return false;
    }
}

add_filter('body_class', function($classes) {
    if (function_exists('shav_get_topbar_data') && shav_get_topbar_data()) {
        $classes[] = 'has-shav-topbar';
    }
    return $classes;
});
