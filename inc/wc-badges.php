<?php

/**
 * Zwraca pierwszą pasującą regułę etykiety tekstowej dla danego produktu.
 */
function shav_get_active_text_badge($product, $context = 'both') {
    if (!$product) return null;
    
    $product_id = $product->get_id();
    $cat_ids = $product->get_category_ids();
    
    $rules_json = get_option('shav_text_badges_json', '[]');
    $rules = json_decode($rules_json, true);
    
    if (!is_array($rules)) return null;
    
    // Filter rules by context
    $filtered_rules = [];
    foreach ($rules as $rule) {
        if (empty(trim($rule['text']))) continue;
        
        $location = isset($rule['displayLocation']) ? $rule['displayLocation'] : 'both';
        if ($context !== 'both' && $location !== 'both' && $location !== $context) {
            continue; // Skip if location doesn't match the current context
        }
        $filtered_rules[] = $rule;
    }
    
    // Pass 1: Products
    foreach ($filtered_rules as $rule) {
        if ($rule['type'] === 'products' && !empty($rule['products'])) {
            foreach ($rule['products'] as $p) {
                if (intval($p['id']) === $product_id) return $rule;
            }
        }
    }
    // Pass 2: Categories
    foreach ($filtered_rules as $rule) {
        if ($rule['type'] === 'categories' && !empty($rule['categories'])) {
            $intersect = array_intersect($cat_ids, array_map('intval', $rule['categories']));
            if (!empty($intersect)) return $rule;
        }
    }
    // Pass 3: Global
    foreach ($filtered_rules as $rule) {
        if ($rule['type'] === 'global') return $rule;
    }
    
    return null;
}

/**
 * Zwraca pierwszą pasującą regułę procentowej odznaki promocyjnej dla danego produktu.
 */
function shav_get_active_promo_badge($product) {
    if (!$product) return null;
    
    $product_id = $product->get_id();
    $cat_ids = $product->get_category_ids();
    
    $rules_json = get_option('shav_promo_badges_json', '[]');
    $rules = json_decode($rules_json, true);
    
    if (!is_array($rules)) return null;
    
    // Pass 1: Products
    foreach ($rules as $rule) {
        if (empty(trim($rule['text']))) continue;
        if ($rule['type'] === 'products' && !empty($rule['products'])) {
            foreach ($rule['products'] as $p) {
                if (intval($p['id']) === $product_id) return $rule;
            }
        }
    }
    // Pass 2: Categories
    foreach ($rules as $rule) {
        if (empty(trim($rule['text']))) continue;
        if ($rule['type'] === 'categories' && !empty($rule['categories'])) {
            $intersect = array_intersect($cat_ids, array_map('intval', $rule['categories']));
            if (!empty($intersect)) return $rule;
        }
    }
    // Pass 3: Global
    foreach ($rules as $rule) {
        if (empty(trim($rule['text']))) continue;
        if ($rule['type'] === 'global') return $rule;
    }
    
    return null;
}

/**
 * Zwraca WSZYSTKIE pasujące reguły odznak SVG dla danego produktu.
 */
function shav_get_active_svg_badges($product) {
    if (!$product) return [];
    
    $product_id = $product->get_id();
    $cat_ids = $product->get_category_ids();
    
    $rules_json = get_option('shav_svg_badges_json', '[]');
    // decode the base64 encoded SVG badge JSON if necessary
    $rawVal = trim($rules_json);
    if (!empty($rawVal) && $rawVal !== '[]') {
        if (substr($rawVal, 0, 1) === '[') {
            $rules = json_decode($rawVal, true);
        } else {
            // JavaScript uses: btoa(unescape(encodeURIComponent(JSON.stringify(svgBadgeData))))
            $rules = json_decode(rawurldecode(base64_decode($rawVal)), true);
        }
    } else {
        $rules = [];
    }
    
    if (!is_array($rules)) return [];
    
    $matched = [];
    
    // W SVG zwracamy tablicę reguł. Jeśli mają nie przeszkadzać sobie, tutaj po prostu je odfiltrujemy, 
    // ale możemy je posortować po priorytecie: Products, Categories, Global.
    // Pass 1: Products
    foreach ($rules as $rule) {
        $hasContent = !empty(trim($rule['text'])) || !empty(trim($rule['svgCode'])) || !empty(trim($rule['image']));
        if (!$hasContent) continue;
        if ($rule['type'] === 'products' && !empty($rule['products'])) {
            foreach ($rule['products'] as $p) {
                if (intval($p['id']) === $product_id) {
                    $matched[] = $rule;
                    break;
                }
            }
        }
    }
    // Pass 2: Categories
    foreach ($rules as $rule) {
        $hasContent = !empty(trim($rule['text'])) || !empty(trim($rule['svgCode'])) || !empty(trim($rule['image']));
        if (!$hasContent) continue;
        if ($rule['type'] === 'categories' && !empty($rule['categories'])) {
            $intersect = array_intersect($cat_ids, array_map('intval', $rule['categories']));
            if (!empty($intersect)) {
                $matched[] = $rule;
            }
        }
    }
    // Pass 3: Global
    foreach ($rules as $rule) {
        $hasContent = !empty(trim($rule['text'])) || !empty(trim($rule['svgCode'])) || !empty(trim($rule['image']));
        if (!$hasContent) continue;
        if ($rule['type'] === 'global') {
            $matched[] = $rule;
        }
    }
    
    return $matched;
}


function add_new_custom_promo_fields()
{
    echo '<div class="options_group">';

    // New Promo Text field
    woocommerce_wp_text_input(
        array(
            'id' => 'new_promo_text',
            'label' => __('New Promo Text', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the text for the new promotional element.', 'woocommerce')
        )
    );

    // New Background Image URL field
    woocommerce_wp_text_input(
        array(
            'id' => 'new_promo_bg_image',
            'label' => __('New Promo Background Image', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the URL of the background image for the new promotional element.', 'woocommerce')
        )
    );

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_new_custom_promo_fields');

function save_new_custom_promo_fields($post_id)
{
    $product = wc_get_product($post_id);

    // Save new promo text
    $new_promo_text = isset($_POST['new_promo_text']) ? sanitize_text_field($_POST['new_promo_text']) : '';
    $product->update_meta_data('new_promo_text', $new_promo_text);

    // Save new background image URL
    $new_promo_bg_image = isset($_POST['new_promo_bg_image']) ? sanitize_text_field($_POST['new_promo_bg_image']) : '';
    $product->update_meta_data('new_promo_bg_image', $new_promo_bg_image);

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_new_custom_promo_fields');

// Badge "Nowość" (peach gradient) — w prawym gornym rogu zdjecia produktowego (Figma 390:1455)
function display_new_promotional_element()
{
    global $product;
    if (!$product)
        return;
    if (function_exists('shav_is_hidden') && shav_is_hidden($product->get_id(), 'badges'))
        return;

    // 1. Sprawdzamy nowy silnik z kokpitu (JSON)
    $active_text_badge = shav_get_active_text_badge($product, 'product');
    if ($active_text_badge && !empty($active_text_badge['text'])) {
        $badge_label = $active_text_badge['text'];
        $custom_bg   = $active_text_badge['color'];
        $custom_color = $active_text_badge['textColor'];

        $styles = [];
        if ($custom_bg) {
            $styles[] = 'background:' . esc_attr($custom_bg) . ' !important';
            if (strpos($custom_bg, 'gradient') === false) {
                $styles[] = 'background-image:none !important';
            }
        }
        if ($custom_color) {
            $styles[] = 'color:' . esc_attr($custom_color) . ' !important';
        }
        $styles[] = 'text-transform: uppercase !important';
        $badge_style = $styles ? ' style="' . implode(';', $styles) . '"' : '';
        
        // Wyświetlamy dla każdego tekstu, nie tylko 'NOWOŚĆ' lub 'NEW',
        // z zachowaniem klasy bazowej (żeby struktura html była OK).
        echo '<span class="product-gallery__badge product-gallery__badge--custom"'. $badge_style .'>' . esc_html($badge_label) . '</span>';
        return; 
    }

    // 2. Fallback na stare meta
    $new_promo_text = shav_get_field($product->get_id(), 'new_promo_text', 'badges');
    if (empty($new_promo_text))
        return;

    echo '<span class="product-gallery__badge product-gallery__badge--new">' . esc_html($new_promo_text) . '</span>';
}
add_action('shav_product_gallery_badges', 'display_new_promotional_element', 10);


// second promotion tag on product page
function add_custom_promo_fields_two_lines()
{
    echo '<div class="options_group">';

    // Promo Percentage field (for bigger text, -17%)
    woocommerce_wp_text_input(
        array(
            'id' => 'promo_percentage_text',
            'label' => __('Promo Percentage Text', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the percentage text to display on the first line (e.g., "-17%").', 'woocommerce')
        )
    );

    // Background Gradient for percentage text
    woocommerce_wp_textarea_input(
        array(
            'id' => 'promo_percentage_gradient',
            'label' => __('Promo Percentage Gradient', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the CSS gradient for the percentage text (e.g., linear-gradient(to right, #ff0000, #00ff00)).', 'woocommerce')
        )
    );

    // Promo Text field (for "promocja")
    woocommerce_wp_text_input(
        array(
            'id' => 'promo_small_text',
            'label' => __('Promo Small Text', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Enter the small text to display below the percentage (e.g., "promocja").', 'woocommerce')
        )
    );

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_promo_fields_two_lines');

function save_custom_promo_fields_two_lines($post_id)
{
    $product = wc_get_product($post_id);

    // Save percentage text
    $promo_percentage_text = isset($_POST['promo_percentage_text']) ? $_POST['promo_percentage_text'] : '';
    $product->update_meta_data('promo_percentage_text', sanitize_text_field($promo_percentage_text));

    // Save percentage gradient
    $promo_percentage_gradient = isset($_POST['promo_percentage_gradient']) ? $_POST['promo_percentage_gradient'] : '';
    $product->update_meta_data('promo_percentage_gradient', sanitize_textarea_field($promo_percentage_gradient));

    // Save small promo text
    $promo_small_text = isset($_POST['promo_small_text']) ? $_POST['promo_small_text'] : '';
    $product->update_meta_data('promo_small_text', sanitize_text_field($promo_small_text));

    $product->save();
}
add_action('woocommerce_process_product_meta', 'save_custom_promo_fields_two_lines');


// Badge "-25%" (czerwony pill) — w prawym gornym rogu zdjecia produktowego (Figma 390:1457)
function display_promotional_element_two_lines()
{
    global $product;
    if (!$product)
        return;
    if (function_exists('shav_is_hidden') && shav_is_hidden($product->get_id(), 'badges'))
        return;

    // 1. Sprawdzamy nowy silnik (JSON)
    $badge = shav_get_active_promo_badge($product);
    if ($badge && !empty($badge['text'])) {
        $pct = $badge['text'];
        $bg = $badge['color'] ?: '';
        $color = $badge['textColor'] ?: '';
        
        $style = '';
        if ($bg || $color) {
            $style = ' style="';
            if ($bg) $style .= 'background: ' . esc_attr($bg) . '; ';
            if ($color) $style .= 'color: ' . esc_attr($color) . '; ';
            $style .= '"';
        }
        
        echo '<span class="product-gallery__badge product-gallery__badge--sale"' . $style . '>-' . esc_html($pct) . '%</span>';
        return;
    }

    // 2. Fallback na stare meta
    $promo_percentage_text = shav_get_field($product->get_id(), 'promo_percentage_text', 'badges');
    if (empty($promo_percentage_text))
        return;

    // Próba pobrania gradientu, by działał stary system
    $promo_gradient = shav_get_field($product->get_id(), 'promo_percentage_gradient', 'badges');
    $style = '';
    if (!empty($promo_gradient)) {
        $style = ' style="background: ' . esc_attr($promo_gradient) . ';"';
    }

    echo '<span class="product-gallery__badge product-gallery__badge--sale"' . $style . '>' . esc_html($promo_percentage_text) . '</span>';
}
add_action('shav_product_gallery_badges', 'display_promotional_element_two_lines', 20);
