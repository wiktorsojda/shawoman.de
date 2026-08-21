<?php
// Placeholder dla kodu 'ceny w zestawie'
// 1. Dodanie pola do produktów
add_action('woocommerce_product_options_general_product_data', 'shav_add_bundled_price_field');
function shav_add_bundled_price_field()
{
    echo '<div class="options_group show_if_simple show_if_variable">';
    woocommerce_wp_text_input(array(
        'id' => '_shav_bundled_price',
        'label' => 'Cena gdy w zestawie (€)',
        'desc_tip' => 'true',
        'description' => 'Ta cena zostanie użyta, jeśli klient kupi ten produkt w ramach zestawu (Smart Grouped).',
        'data_type' => 'price'
    ));
    woocommerce_wp_text_input(array(
        'id' => '_shav_bundled_promo_price',
        'label' => 'Cena w zestawie PODCZAS PROMOCJI (€)',
        'desc_tip' => 'true',
        'description' => 'Używana zamiast powyższej, jeśli trwa jakakolwiek kampania Promocje (Faza 1 lub 2).',
        'data_type' => 'price'
    ));
    echo '</div>';
}

// 2. Zapisywanie pola dla produktów prostych
add_action('woocommerce_process_product_meta', 'shav_save_bundled_price_field');
function shav_save_bundled_price_field($post_id)
{
    if (isset($_POST['_shav_bundled_price'])) {
        update_post_meta($post_id, '_shav_bundled_price', wc_format_decimal($_POST['_shav_bundled_price']));
    }
    if (isset($_POST['_shav_bundled_promo_price'])) {
        update_post_meta($post_id, '_shav_bundled_promo_price', wc_format_decimal($_POST['_shav_bundled_promo_price']));
    }
}

// 3. Dodanie pola do wariantów (Variable Products)
add_action('woocommerce_variation_options_pricing', 'shav_add_bundled_price_to_variations', 10, 3);
function shav_add_bundled_price_to_variations($loop, $variation_data, $variation)
{
    woocommerce_wp_text_input(array(
        'id' => '_shav_bundled_price[' . $loop . ']',
        'wrapper_class' => 'form-row form-row-full',
        'label' => 'Cena gdy w zestawie (€) - Smart Grouped',
        'value' => get_post_meta($variation->ID, '_shav_bundled_price', true),
        'data_type' => 'price'
    ));
    woocommerce_wp_text_input(array(
        'id' => '_shav_bundled_promo_price[' . $loop . ']',
        'wrapper_class' => 'form-row form-row-full',
        'label' => 'Cena w zestawie PODCZAS PROMOCJI (€)',
        'value' => get_post_meta($variation->ID, '_shav_bundled_promo_price', true),
        'data_type' => 'price'
    ));
}

// 4. Zapisywanie pola dla wariantów
add_action('woocommerce_save_product_variation', 'shav_save_bundled_price_variations', 10, 2);
function shav_save_bundled_price_variations($variation_id, $i)
{
    if (isset($_POST['_shav_bundled_price'][$i])) {
        update_post_meta($variation_id, '_shav_bundled_price', wc_format_decimal($_POST['_shav_bundled_price'][$i]));
    }
    if (isset($_POST['_shav_bundled_promo_price'][$i])) {
        update_post_meta($variation_id, '_shav_bundled_promo_price', wc_format_decimal($_POST['_shav_bundled_promo_price'][$i]));
    }
}

// 5. Zablokowanie możliwości zmiany ilości dla produktów z zestawu w koszyku
add_filter('woocommerce_cart_item_quantity', 'shav_disable_quantity_change_for_bundle', 10, 3);
function shav_disable_quantity_change_for_bundle($product_quantity, $cart_item_key, $cart_item)
{
    if (isset($cart_item['woosg_parent_id'])) {
        // Blokujemy pole do zmiany ilości, pokazując tekstowo ile sztuk zawiera zestaw i ukryty input zachowujący tę ilość dla WooCommerce
        return sprintf('%d <input type="hidden" name="cart[%s][qty]" value="%d" />', $cart_item['quantity'], $cart_item_key, $cart_item['quantity']);
    }
    return $product_quantity;
}

// Funkcja sprawdzająca czy zestaw WPClever jest kompletny w koszyku
function shav_is_wpc_bundle_complete_in_cart($parent_id, $cart)
{
    $woosg_ids_meta = get_post_meta($parent_id, 'woosg_ids', true);
    if (empty($woosg_ids_meta)) {
        return true; // Jeśli z jakiegoś powodu brak danych o strukturze zestawu, pomijamy restrykcję
    }

    $required_ids = array();

    // Zabezpieczenie (PHP 8.0+): WPClever czasami zapisuje woosg_ids jako zserializowaną tablicę (array), a czasami jako string
    $items = is_array($woosg_ids_meta) ? $woosg_ids_meta : explode(',', $woosg_ids_meta);

    foreach ($items as $item) {
        if (is_string($item)) {
            $parts = explode('/', trim($item));
            if (!empty($parts[0])) {
                $required_ids[] = intval($parts[0]);
            }
        } elseif (is_numeric($item)) {
            $required_ids[] = intval($item);
        } elseif (is_array($item) && isset($item['id'])) {
            $required_ids[] = intval($item['id']);
        }
    }

    $found_ids = array();
    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['woosg_parent_id']) && intval($cart_item['woosg_parent_id']) === intval($parent_id)) {
            $found_ids[] = intval($cart_item['product_id']);
            if (!empty($cart_item['variation_id'])) {
                $found_ids[] = intval($cart_item['variation_id']);
            }
        }
    }

    foreach ($required_ids as $req_id) {
        if (!in_array($req_id, $found_ids)) {
            // Sprawdzenie na wypadek gdy req_id jest produktem wariantowym, a w koszyku siedzi jego konkretny wariant
            $product = wc_get_product($req_id);
            $found_variation = false;
            if ($product && $product->is_type('variable')) {
                foreach ($found_ids as $fid) {
                    $fp = wc_get_product($fid);
                    if ($fp && $fp->get_parent_id() === $req_id) {
                        $found_variation = true;
                        break;
                    }
                }
            }
            if (!$found_variation) {
                return false; // Brakuje tego elementu z zestawu! Zestaw jest rozerwany.
            }
        }
    }

    return true;
}

// 7. Nadpisywanie ceny w koszyku (Uruchamiane na samym końcu - najwyższy priorytet)
add_action('woocommerce_before_calculate_totals', 'shav_apply_bundle_price_in_cart', PHP_INT_MAX, 1);
function shav_apply_bundle_price_in_cart($cart)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    $complete_bundles = array();

    // Sprawdzamy, które z zestawów WPClever dodanych do koszyka są kompletne
    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['woosg_parent_id'])) {
            $parent_id = intval($cart_item['woosg_parent_id']);
            if (!isset($complete_bundles[$parent_id])) {
                $complete_bundles[$parent_id] = shav_is_wpc_bundle_complete_in_cart($parent_id, $cart);
            }
        }
    }

    foreach ($cart->get_cart() as $cart_item) {
        // Jeśli produkt należy do WPClever, aplikuj cenę tylko wtedy gdy zestaw nie jest "rozerwany"
        if (isset($cart_item['woosg_parent_id'])) {
            $parent_id = intval($cart_item['woosg_parent_id']);

            if (!empty($complete_bundles[$parent_id])) {
                $item_id = $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : $cart_item['product_id'];
                $item_product = wc_get_product($item_id);
                if ($item_product) {
                    $bundled_price = shav_get_product_price_in_bundle($item_product, $parent_id);

                    if ($bundled_price !== '' && is_numeric($bundled_price)) {
                        $cart_item['data']->set_price(floatval($bundled_price));
                    }
                }
            }
        }
    }
}

// 8. Dodanie opcji do menu rozwijanego nad wariantami (Bulk Edit)
add_action('woocommerce_variable_product_bulk_edit_actions', 'shav_add_bundled_price_bulk_action');
function shav_add_bundled_price_bulk_action()
{
    echo '<optgroup label="Ceny w zestawie (Smart Grouped)">';
    echo '<option value="shav_set_bundled_price">Ustaw cenę w zestawie</option>';
    echo '<option value="shav_set_bundled_promo_price">Ustaw cenę w zestawie podczas promocji</option>';
    echo '</optgroup>';
}

// 9. Skrypt JS do masowej edycji wariantów
add_action('admin_print_footer_scripts', 'shav_bundled_price_bulk_edit_js');
function shav_bundled_price_bulk_edit_js()
{
    global $pagenow, $post_type;
    if ('post.php' === $pagenow && 'product' === $post_type) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select.variation_actions').on('shav_set_bundled_price_ajax_data', function (e, data) {
                    var value = window.prompt('Podaj cenę w zestawie dla wszystkich wariantów (zostaw puste, aby usunąć):');
                    if (value !== null) {
                        data.value = value;
                        $('#_shav_bundled_price').val(value);
                    } else {
                        data.value = 'CANCEL';
                    }
                    return data;
                });
                $('select.variation_actions').on('shav_set_bundled_promo_price_ajax_data', function (e, data) {
                    var value = window.prompt('Podaj cenę promocyjną w zestawie dla wszystkich wariantów (zostaw puste, aby usunąć):');
                    if (value !== null) {
                        data.value = value;
                        $('#_shav_bundled_promo_price').val(value);
                    } else {
                        data.value = 'CANCEL';
                    }
                    return data;
                });
            });
        </script>
        <?php
    }
}

// 10. Zapisanie masowo zmienionych wartości dla wariantów oraz głównego produktu
add_action('woocommerce_bulk_edit_variations_default', 'shav_save_bundled_price_bulk_edit', 10, 4);
function shav_save_bundled_price_bulk_edit($bulk_action, $data, $product_id, $variations)
{
    if ('shav_set_bundled_price' === $bulk_action) {
        if (isset($data['value']) && $data['value'] !== 'CANCEL') {
            $new_price = $data['value'] === '' ? '' : wc_format_decimal($data['value']);
            foreach ($variations as $variation_id) {
                if ($new_price === '')
                    delete_post_meta($variation_id, '_shav_bundled_price');
                else
                    update_post_meta($variation_id, '_shav_bundled_price', $new_price);
            }
            if ($new_price === '')
                delete_post_meta($product_id, '_shav_bundled_price');
            else
                update_post_meta($product_id, '_shav_bundled_price', $new_price);
        }
    }
    if ('shav_set_bundled_promo_price' === $bulk_action) {
        if (isset($data['value']) && $data['value'] !== 'CANCEL') {
            $new_price = $data['value'] === '' ? '' : wc_format_decimal($data['value']);
            foreach ($variations as $variation_id) {
                if ($new_price === '')
                    delete_post_meta($variation_id, '_shav_bundled_promo_price');
                else
                    update_post_meta($variation_id, '_shav_bundled_promo_price', $new_price);
            }
            if ($new_price === '')
                delete_post_meta($product_id, '_shav_bundled_promo_price');
            else
                update_post_meta($product_id, '_shav_bundled_promo_price', $new_price);
        }
    }
}

// 11. Nadpisywanie ceny produktu na stronie zestawu (Frontend & AJAX)
function shav_is_wpc_grouped_context()
{
    if (wp_doing_ajax()) {
        if (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'woosg') !== false) {
            return true;
        }
        if (isset($_REQUEST['woosg_ids'])) {
            return true;
        }
    }

    if (is_singular('product')) {
        global $post;
        if ($post) {
            $parent_product = wc_get_product($post->ID);
            if ($parent_product && $parent_product->is_type('woosg')) {
                return $post->ID;
            }
        }
    }

    // Obsługa ekranu edycji produktu w wp-admin
    if (is_admin() && !wp_doing_ajax()) {
        global $pagenow;
        if ($pagenow === 'post.php' && isset($_GET['post'])) {
            $parent_product = wc_get_product(intval($_GET['post']));
            if ($parent_product && $parent_product->is_type('woosg')) {
                return intval($_GET['post']);
            }
        }
    }

    return false;
}

// Zwraca czy jest aktywna globalnie jakakolwiek promocja
function shav_is_any_promo_active()
{
    $promos = get_posts(['post_type' => 'promocje', 'post_status' => 'publish', 'posts_per_page' => -1]);
    foreach ($promos as $p) {
        if (function_exists('shav_get_promo_phase')) {
            $phase = shav_get_promo_phase($p->ID);
            error_log('Promo ID ' . $p->ID . ' phase: ' . $phase);
            if ($phase > 0)
                return true;
        } else {
            error_log('shav_get_promo_phase DOES NOT EXIST in this context!');
        }
    }
    return false;
}

// Helper pobierający cenę. Zwraca cenę nadpisaną w danym Zestawie, a jeśli jej brak - cenę globalną z edycji produktu.
function shav_get_product_price_in_bundle($product, $bundle_id)
{
    $item_id = $product->get_id();
    $is_promo = shav_is_any_promo_active();

    $local_promo = '';
    $local_regular = '';

    if (is_numeric($bundle_id) && $bundle_id > 0) {
        $promo_overrides = get_post_meta($bundle_id, '_shav_bundle_item_promo_prices', true);
        $reg_overrides = get_post_meta($bundle_id, '_shav_bundle_item_prices', true);

        if (is_array($promo_overrides) && isset($promo_overrides[$item_id]) && $promo_overrides[$item_id] !== '') {
            $local_promo = $promo_overrides[$item_id];
        } elseif (is_array($promo_overrides) && $product->is_type('variation') && isset($promo_overrides[$product->get_parent_id()]) && $promo_overrides[$product->get_parent_id()] !== '') {
            $local_promo = $promo_overrides[$product->get_parent_id()];
        }

        if (is_array($reg_overrides) && isset($reg_overrides[$item_id]) && $reg_overrides[$item_id] !== '') {
            $local_regular = $reg_overrides[$item_id];
        } elseif (is_array($reg_overrides) && $product->is_type('variation') && isset($reg_overrides[$product->get_parent_id()]) && $reg_overrides[$product->get_parent_id()] !== '') {
            $local_regular = $reg_overrides[$product->get_parent_id()];
        }
    }

    $global_promo = get_post_meta($item_id, '_shav_bundled_promo_price', true);
    if ($global_promo === '' && $product->is_type('variation')) {
        $global_promo = get_post_meta($product->get_parent_id(), '_shav_bundled_promo_price', true);
    }

    $global_regular = get_post_meta($item_id, '_shav_bundled_price', true);
    if ($global_regular === '' && $product->is_type('variation')) {
        $global_regular = get_post_meta($product->get_parent_id(), '_shav_bundled_price', true);
    }

    // Priority 1: Local Promo
    if ($is_promo && $local_promo !== '')
        return str_replace(',', '.', $local_promo);
    // Priority 2: Global Promo
    if ($is_promo && $global_promo !== '')
        return str_replace(',', '.', $global_promo);
    // Priority 3: Local Regular
    if ($local_regular !== '')
        return str_replace(',', '.', $local_regular);
    // Priority 4: Global Regular
    if ($global_regular !== '')
        return str_replace(',', '.', $global_regular);

    return '';
}

function shav_wpc_grouped_custom_price($price, $product)
{
    $bundle_id = shav_is_wpc_grouped_context();
    if ($bundle_id) {
        $bundled_price = shav_get_product_price_in_bundle($product, $bundle_id);

        if ($bundled_price !== '' && is_numeric($bundled_price)) {
            return floatval($bundled_price);
        }
    }
    return $price;
}

add_filter('woocommerce_product_get_price', 'shav_wpc_grouped_custom_price', 999, 2);
add_filter('woocommerce_product_variation_get_price', 'shav_wpc_grouped_custom_price', 999, 2);

// Uwaga: Usunięto nadpisywanie 'get_regular_price', aby WooCommerce pamiętał starą cenę
// i mógł użyć jej do efektu przekreślenia (sale price).

// 12. Nadpisywanie wizualnej ceny (HTML) na stronie zestawu
// Rozwiązuje problem, w którym WooCommerce ignoruje filtry get_price przy generowaniu np. widełek cenowych dla produktów z wariantami
add_filter('woocommerce_get_price_html', 'shav_wpc_grouped_custom_price_html', 999, 2);
add_filter('woocommerce_get_variation_price_html', 'shav_wpc_grouped_custom_price_html', 999, 2);

function shav_wpc_grouped_custom_price_html($price_html, $product)
{
    $bundle_id = shav_is_wpc_grouped_context();
    if ($bundle_id) {
        $bundled_price = shav_get_product_price_in_bundle($product, $bundle_id);

        if ($bundled_price !== '' && is_numeric($bundled_price)) {
            $regular_price = $product->get_regular_price();
            // Jeśli produkt ma wyższą cenę regularną, prezentujemy cenę zestawową jako promocję (przekreślenie)
            if ($regular_price !== '' && is_numeric($regular_price) && floatval($regular_price) > floatval($bundled_price)) {
                return wc_format_sale_price($regular_price, $bundled_price);
            }
            return wc_price($bundled_price);
        }
    }
    return $price_html;
}

// 13. Rozdzielenie w koszyku produktów z zestawu i dodanych osobno
// Bezpieczne wstrzyknięcie unikalnego klucza (aby nie zakłócać działania WPClever oryginalnym kluczem woosg_parent_id na wczesnym etapie)
add_filter('woosg_cart_item_data', 'shav_add_woosg_split_key', 10, 3);
function shav_add_woosg_split_key($item_data, $item, $product_id)
{
    $item_data['shav_woosg_split'] = $product_id;
    return $item_data;
}

// Modyfikacja hasha koszyka przez WooCommerce na podstawie naszego unikalnego klucza
add_filter('woocommerce_cart_id', 'shav_force_unique_cart_id_for_bundles', 999, 5);
function shav_force_unique_cart_id_for_bundles($cart_id, $product_id, $variation_id, $variation, $cart_item_data)
{
    if (isset($cart_item_data['shav_woosg_split'])) {
        return $cart_id . '_bundle_' . $cart_item_data['shav_woosg_split'];
    }
    return $cart_id;
}

// 14. Metabox dla specyficznych cen w danym zestawie
add_action('add_meta_boxes', 'shav_add_bundle_prices_metabox');
function shav_add_bundle_prices_metabox()
{
    add_meta_box(
        'shav_bundle_prices_box',
        'Ceny w tym zestawie (Lokalne Nadpisanie)',
        'shav_render_bundle_prices_metabox',
        'product',
        'normal',
        'high'
    );
}

function shav_render_bundle_prices_metabox($post)
{
    $product = wc_get_product($post->ID);
    if (!$product || !$product->is_type('woosg')) {
        echo '<p>Te opcje są dostępne tylko dla produktów typu Smart Grouped (Zestawy).</p>';
        return;
    }

    wp_nonce_field('shav_save_bundle_prices', 'shav_bundle_prices_nonce');

    // Zabezpieczenie przed pobraniem starego woosg_ids: Zapisz zestaw, aby odświeżyć listę.
    $woosg_ids_meta = get_post_meta($post->ID, 'woosg_ids', true);
    if (empty($woosg_ids_meta)) {
        echo '<p style="margin: 15px 0;">Najpierw dodaj produkty do zestawu i <strong>Zaktualizuj (zapisz) post</strong>, aby móc ustawić im dedykowane ceny lokalne.</p>';
        return;
    }

    $required_ids = array();
    $items = is_array($woosg_ids_meta) ? $woosg_ids_meta : explode(',', $woosg_ids_meta);
    foreach ($items as $item) {
        if (is_string($item)) {
            $parts = explode('/', trim($item));
            if (!empty($parts[0])) {
                $required_ids[] = intval($parts[0]);
            }
        } elseif (is_numeric($item)) {
            $required_ids[] = intval($item);
        } elseif (is_array($item) && isset($item['id'])) {
            $required_ids[] = intval($item['id']);
        }
    }

    $saved_prices = get_post_meta($post->ID, '_shav_bundle_item_prices', true);
    if (!is_array($saved_prices))
        $saved_prices = array();

    $saved_promo_prices = get_post_meta($post->ID, '_shav_bundle_item_promo_prices', true);
    if (!is_array($saved_promo_prices))
        $saved_promo_prices = array();

    echo '<table class="form-table" style="text-align:left;"><thead><tr><th>Produkt</th><th>Zwykła Cena Zestawowa (€)</th><th>Cena Zestawowa PODCZAS PROMOCJI (€)</th></tr></thead><tbody>';
    foreach ($required_ids as $req_id) {
        $req_product = wc_get_product($req_id);
        if (!$req_product)
            continue;

        $name = $req_product->get_name();
        $global_price = get_post_meta($req_id, '_shav_bundled_price', true);
        if ($global_price === '' && $req_product->is_type('variation')) {
            $global_price = get_post_meta($req_product->get_parent_id(), '_shav_bundled_price', true);
        }

        $global_promo_price = get_post_meta($req_id, '_shav_bundled_promo_price', true);
        if ($global_promo_price === '' && $req_product->is_type('variation')) {
            $global_promo_price = get_post_meta($req_product->get_parent_id(), '_shav_bundled_promo_price', true);
        }

        $current_override = isset($saved_prices[$req_id]) ? $saved_prices[$req_id] : '';
        $current_promo_override = isset($saved_promo_prices[$req_id]) ? $saved_promo_prices[$req_id] : '';

        echo '<tr style="border-bottom: 1px solid #f0f0f1;">';
        echo '<th scope="row" style="padding: 15px 10px 15px 0;"><label for="shav_price_' . $req_id . '">' . esc_html($name) . ' <br><span style="color:#888; font-weight:normal; font-size:12px;">(ID: ' . $req_id . ')</span></label></th>';
        echo '<td style="padding: 15px 10px 15px 0;">';
        echo '<input type="number" step="0.01" min="0" id="shav_price_' . $req_id . '" name="shav_bundle_prices[' . $req_id . ']" value="' . esc_attr(str_replace(',', '.', $current_override)) . '" style="width: 90px; margin-right: 15px;">';
        if ($global_price !== '') {
            $formatted_global = floatval(str_replace(',', '.', $global_price));
            echo '<br><span class="description" style="font-size: 11px;">Globalna: <strong>' . wc_price($formatted_global) . '</strong></span>';
        } else {
            echo '<br><span class="description" style="color:#d63638; font-size: 11px;">Brak globalnej (użyje zwykłej)</span>';
        }
        echo '</td>';
        echo '<td style="padding: 15px 10px 15px 0; background: #fff3f3; border-left: 1px solid #f0f0f1;">';
        echo '<input type="number" step="0.01" min="0" name="shav_bundle_promo_prices[' . $req_id . ']" value="' . esc_attr(str_replace(',', '.', $current_promo_override)) . '" style="width: 90px; margin-right: 15px;">';
        if ($global_promo_price !== '') {
            $formatted_global_promo = floatval(str_replace(',', '.', $global_promo_price));
            echo '<br><span class="description" style="font-size: 11px;">Globalna promo: <strong>' . wc_price($formatted_global_promo) . '</strong></span>';
        } else {
            echo '<br><span class="description" style="color:#d63638; font-size: 11px;">Brak globalnej promo</span>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}

add_action('save_post', 'shav_save_bundle_prices_meta');
function shav_save_bundle_prices_meta($post_id)
{
    if (!isset($_POST['shav_bundle_prices_nonce']) || !wp_verify_nonce($_POST['shav_bundle_prices_nonce'], 'shav_save_bundle_prices')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['shav_bundle_prices']) && is_array($_POST['shav_bundle_prices'])) {
        $clean_prices = array();
        foreach ($_POST['shav_bundle_prices'] as $item_id => $price) {
            $price_val = wc_format_decimal($price);
            if ($price_val !== '')
                $clean_prices[intval($item_id)] = $price_val;
        }
        update_post_meta($post_id, '_shav_bundle_item_prices', $clean_prices);
    } else {
        delete_post_meta($post_id, '_shav_bundle_item_prices');
    }

    if (isset($_POST['shav_bundle_promo_prices']) && is_array($_POST['shav_bundle_promo_prices'])) {
        $clean_promo = array();
        foreach ($_POST['shav_bundle_promo_prices'] as $item_id => $price) {
            $price_val = wc_format_decimal($price);
            if ($price_val !== '')
                $clean_promo[intval($item_id)] = $price_val;
        }
        update_post_meta($post_id, '_shav_bundle_item_promo_prices', $clean_promo);
    } else {
        delete_post_meta($post_id, '_shav_bundle_item_promo_prices');
    }
}

// 15. Przekreślona cena w koszyku dla produktów w zestawie
add_filter('woocommerce_cart_item_price', 'shav_cart_item_sale_price_html', 10, 3);
function shav_cart_item_sale_price_html($price_html, $cart_item, $cart_item_key)
{
    if (isset($cart_item['woosg_parent_id'])) {
        $parent_id = intval($cart_item['woosg_parent_id']);
        $product = $cart_item['data'];

        // Sprawdzamy czy zestaw jest kompletny (żeby nie pokazywać promocji, gdy rozerwaliśmy zestaw)
        if (shav_is_wpc_bundle_complete_in_cart($parent_id, WC()->cart)) {
            $regular_price = $product->get_regular_price();
            $current_price = $product->get_price();

            if ($regular_price !== '' && is_numeric($regular_price) && floatval($regular_price) > floatval($current_price)) {
                $regular_price_to_display = wc_get_price_to_display($product, array('price' => $regular_price));
                $current_price_to_display = wc_get_price_to_display($product);

                return wc_format_sale_price($regular_price_to_display, $current_price_to_display);
            }
        }
    }
    return $price_html;
}