<?php // WKLEJKA do functions.php — pod enqueue shav-variations (bez tej linii <?php)

// =============================================================================
// Zestawy (WPC Grouped Product): zdjecie zestawu per wariant skladnika.
// Golarka w zestawie jest wielowariantowa — po wyborze koloru galeria zestawu
// ma pokazac dedykowane zdjecie CALEGO zestawu w tym kolorze (nie zdjecie
// samej golarki). Pola (URL) generuja sie w Dane produktu -> Zaawansowane,
// po jednym na kazda opcje wariantu kazdego wielowariantowego skladnika.
// Meta: _shav_woosg_variant_image_{atrybut}_{opcja}. Front: inc/shav-variations.js.
// =============================================================================

// Wielowariantowe skladniki zestawu (z meta `woosg_ids` pluginu, format "68/1,338/1")
function shav_woosg_get_variable_children($product_id)
{
    $ids = get_post_meta($product_id, 'woosg_ids', true);
    if (empty($ids)) {
        return array();
    }
    $pairs = is_array($ids) ? $ids : explode(',', (string) $ids);
    $children = array();
    foreach ($pairs as $pair) {
        if (is_array($pair)) {
            $pid = isset($pair['id']) ? (int) $pair['id'] : 0;
        } else {
            $pid = (int) strtok((string) $pair, '/');
        }
        if (!$pid) {
            continue;
        }
        $child = wc_get_product($pid);
        if ($child && $child->is_type('variable')) {
            $children[] = $child;
        }
    }
    return $children;
}

// Wszystkie klucze pol dla zestawu: [meta_key => [attr_field, option, label]]
function shav_woosg_variant_image_keys($product_id)
{
    $keys = array();
    foreach (shav_woosg_get_variable_children($product_id) as $child) {
        foreach ($child->get_variation_attributes() as $attribute_name => $options) {
            $attr_slug  = sanitize_title($attribute_name);
            $attr_field = 'attribute_' . $attr_slug;
            foreach ($options as $option) {
                $label = $option;
                if (taxonomy_exists($attribute_name)) {
                    $term = get_term_by('slug', $option, $attribute_name);
                    if ($term) {
                        $label = $term->name;
                    }
                }
                $meta_key = '_shav_woosg_variant_image_' . $attr_slug . '_' . sanitize_title($option);
                $keys[$meta_key] = array(
                    'attr_field' => $attr_field,
                    'option'     => $option,
                    'label'      => wc_attribute_label($attribute_name, $child) . ': ' . $label,
                );
            }
        }
    }
    return $keys;
}

// Pola w adminie (Dane produktu -> Zaawansowane)
add_action('woocommerce_product_options_advanced', function () {
    global $post;
    if (!$post) {
        return;
    }
    $keys = shav_woosg_variant_image_keys($post->ID);
    if (empty($keys)) {
        return;
    }
    echo '<div class="options_group">';
    echo '<p class="form-field"><strong>Zdjęcie zestawu per wariant</strong><br>';
    echo '<span class="description">URL zdjęcia całego zestawu pokazywanego w galerii po wyborze wariantu (puste = zdjęcie się nie zmienia).</span></p>';
    foreach ($keys as $meta_key => $info) {
        woocommerce_wp_text_input(array(
            'id'          => $meta_key,
            'label'       => $info['label'],
            'placeholder' => 'https://... (URL z biblioteki mediów)',
            'desc_tip'    => false,
            'value'       => get_post_meta($post->ID, $meta_key, true),
        ));
    }
    echo '</div>';
});

// Zapis pol
add_action('woocommerce_process_product_meta', function ($post_id) {
    foreach (array_keys(shav_woosg_variant_image_keys($post_id)) as $meta_key) {
        if (isset($_POST[$meta_key])) {
            update_post_meta($post_id, $meta_key, esc_url_raw(wp_unslash($_POST[$meta_key])));
        }
    }
});

// Mapa dla JS na stronie zestawu: { attribute_pa_kolor: { bialy: url } }
add_action('wp_footer', function () {
    if (!is_product()) {
        return;
    }
    $product = wc_get_product(get_the_ID());
    if (!$product || !$product->is_type('woosg')) {
        return;
    }
    $map = array();
    foreach (shav_woosg_variant_image_keys($product->get_id()) as $meta_key => $info) {
        $url = get_post_meta($product->get_id(), $meta_key, true);
        if ($url) {
            $map[$info['attr_field']][$info['option']] = esc_url_raw($url);
        }
    }
    if (!empty($map)) {
        echo '<script type="application/json" id="shav-woosg-variant-images">' . wp_json_encode($map) . '</script>';
    }
});
