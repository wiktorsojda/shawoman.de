<?php
/**
 * Logika odpowiedzialna za Górny Pasek (Top Bar)
 */

if (!function_exists('shav_get_topbar_data')) {
    function shav_get_topbar_data() {
        // Zabezpieczenie przed błędem, jeśli funkcja automatyzacji nie istnieje
        $active_promo_id = function_exists('blendygo_get_active_promo') ? blendygo_get_active_promo() : false;
        
        $mode = '';
        $text = '';
        $coupon = '';
        $percentage = '';
        $bg = '';
        $color = '';
        $trustpilot = '';
        $hide_stars = false;

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
            
            $pct_val = get_post_meta($active_promo_id, 'promo_percentage_text', true);
            $percentage = !empty($pct_val) ? '-' . trim($pct_val, '-%') . '%' : '';

            $trustpilot = get_option('shav_topbar_trustpilot_link', '');
            $hide_stars = get_option('shav_topbar_trustpilot_hide_stars', 'no') === 'yes';
            $mode = 'promo';
        }
        // 2. TRYB DAILY (Pozapromocyjny)
        else {
            $is_enabled = get_option('shav_topbar_enabled', 'yes');
            if ($is_enabled === 'yes') {
                $text = get_option('shav_topbar_text', '');
                $coupon = get_option('shav_topbar_coupon', '');
                $percentage = get_option('shav_topbar_percentage', '');
                $bg = get_option('shav_topbar_bg', '#252525');
                $color = get_option('shav_topbar_color', '#FAFAFA');

                $trustpilot = get_option('shav_topbar_trustpilot_link', '');
                $hide_stars = get_option('shav_topbar_trustpilot_hide_stars', 'no') === 'yes';

                if (!empty($text) || !empty($coupon) || !empty($trustpilot)) {
                    $mode = 'daily';
                }
            }
        }

        if ($mode !== '') {
            // Zamień tagi
            if (!empty($text)) {
                $text = str_replace('{procent}', esc_html($percentage), $text);
                $text = str_replace('{kod}', esc_html($coupon), $text);
            }

            return [
                'mode' => $mode,
                'text' => $text,
                'coupon' => $coupon,
                'bg' => $bg,
                'color' => $color,
                'trustpilot' => $trustpilot,
                'hide_stars' => $hide_stars,
            ];
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
