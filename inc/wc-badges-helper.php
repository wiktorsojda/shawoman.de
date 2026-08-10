<?php

/**
 * Zwraca pierwszą pasującą regułę etykiety tekstowej dla danego produktu.
 */
function shav_get_active_text_badge($product) {
    if (!$product) return null;
    
    $product_id = $product->get_id();
    $cat_ids = $product->get_category_ids();
    
    $rules_json = get_option('shav_text_badges_json', '[]');
    $rules = json_decode($rules_json, true);
    
    if (!is_array($rules)) return null;
    
    foreach ($rules as $rule) {
        if (empty(trim($rule['text']))) continue;

        if ($rule['type'] === 'global') {
            return $rule;
        } elseif ($rule['type'] === 'categories' && !empty($rule['categories'])) {
            $intersect = array_intersect($cat_ids, array_map('intval', $rule['categories']));
            if (!empty($intersect)) {
                return $rule;
            }
        } elseif ($rule['type'] === 'products' && !empty($rule['products'])) {
            $product_in_rule = false;
            foreach ($rule['products'] as $p) {
                if (intval($p['id']) === $product_id) {
                    $product_in_rule = true;
                    break;
                }
            }
            if ($product_in_rule) {
                return $rule;
            }
        }
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
    
    foreach ($rules as $rule) {
        if (empty(trim($rule['text']))) continue;

        if ($rule['type'] === 'global') {
            return $rule;
        } elseif ($rule['type'] === 'categories' && !empty($rule['categories'])) {
            $intersect = array_intersect($cat_ids, array_map('intval', $rule['categories']));
            if (!empty($intersect)) {
                return $rule;
            }
        } elseif ($rule['type'] === 'products' && !empty($rule['products'])) {
            $product_in_rule = false;
            foreach ($rule['products'] as $p) {
                if (intval($p['id']) === $product_id) {
                    $product_in_rule = true;
                    break;
                }
            }
            if ($product_in_rule) {
                return $rule;
            }
        }
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
    
    foreach ($rules as $rule) {
        $hasContent = !empty(trim($rule['text'])) || !empty(trim($rule['svgCode'])) || !empty(trim($rule['image']));
        if (!$hasContent) continue;

        if ($rule['type'] === 'global') {
            $matched[] = $rule;
        } elseif ($rule['type'] === 'categories' && !empty($rule['categories'])) {
            $intersect = array_intersect($cat_ids, array_map('intval', $rule['categories']));
            if (!empty($intersect)) {
                $matched[] = $rule;
            }
        } elseif ($rule['type'] === 'products' && !empty($rule['products'])) {
            $product_in_rule = false;
            foreach ($rule['products'] as $p) {
                if (intval($p['id']) === $product_id) {
                    $product_in_rule = true;
                    break;
                }
            }
            if ($product_in_rule) {
                $matched[] = $rule;
            }
        }
    }
    
    return $matched;
}
