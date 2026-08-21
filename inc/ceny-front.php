<?php
// Placeholder dla kodu 'ceny front'

/**
 * Automatyczne wyliczanie wyświetlanej ceny zestawów na kaflach i stronie produktu.
 *
 * Podmienia domyślną cenę wyświetlaną przez WooCommerce/WPClever dla zestawów "Smart Grouped".
 * Całkowicie ignoruje pole "Cena wyświetlana" z edytora produktu.
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Hook z wysokim priorytetem, żeby nadpisać generowanie HTML z WPClever (i WooCommerce)
add_filter('woocommerce_get_price_html', 'shav_auto_grouped_product_price_html', 9999, 2);

function shav_auto_grouped_product_price_html($price_html, $product)
{

    // Upewniamy się, że modyfikujemy wyłącznie produkty (główne) typu Smart Grouped
    if (!$product || !is_a($product, 'WC_Product') || !$product->is_type('woosg')) {
        return $price_html . '<!-- ABORT: Not woosg -->';
    }

    $parent_id = $product->get_id();

    // Pobieramy zawartość zestawu używając natywnej metody WPClever 4.0+
    // Bezpośrednie odczytywanie get_post_meta psuło się przy nowym formacie JSON wtyczki.
    if (!method_exists($product, 'get_items')) {
        return $price_html . '<!-- ABORT: No get_items method -->';
    }

    $items = $product->get_items();
    if (empty($items)) {
        return $price_html . '<!-- ABORT: items is empty. Type: ' . gettype($items) . ' -->';
    }

    $parsed_items = array();

    foreach ($items as $item) {
        $id = intval($item['id'] ?? 0);
        $qty = floatval($item['qty'] ?? 1);

        if ($id > 0) {
            if (isset($parsed_items[$id])) {
                $parsed_items[$id] += $qty;
            } else {
                $parsed_items[$id] = $qty;
            }
        }
    }

    $totals = shav_get_woosg_totals($product);
    if (!$totals) {
        return $price_html . '<!-- ABORT: parsed_items empty -->';
    }

    $total_regular_price = $totals['regular'];
    $total_bundle_price = $totals['bundle'];
    $debug_log = $totals['debug'];

    // --- BUDOWANIE WYNIKOWEGO HTML ---
    $debug_html = '<!-- DEBUG PRICES: ' . $debug_log . ' -->';

    if ($total_regular_price > $total_bundle_price) {
        // Mamy obniżkę! Renderujemy standardowe formatowanie WooCommerce (Przekreślona cena i Nowa)
        return $debug_html . wc_format_sale_price($total_regular_price, $total_bundle_price) . $product->get_price_suffix();
    } else {
        // Brak obniżki (np. to jest domyślny zestaw) - pokazujemy jedną sumę
        // Debug: Jeśli wpada tutaj, pokażmy DLACZEGO (np. jakie są kwoty)
        return $debug_html . '<!-- ABORT: No discount. Reg: ' . $total_regular_price . ' Bundle: ' . $total_bundle_price . ' -->' . wc_price($total_bundle_price) . $product->get_price_suffix();
    }
}

/**
 * Zwraca wyliczone sumy (regularną i z zestawu) dla produktu typu Smart Grouped (woosg).
 * Używane m.in. przez kafelki sklepowe.
 *
 * @param WC_Product $product Produkt główny typu woosg
 * @return array|false Tablica ['regular' => float, 'bundle' => float, 'debug' => string] lub false, jeśli błąd
 */
function shav_get_woosg_totals($product) {
    if (!$product || !is_a($product, 'WC_Product') || !$product->is_type('woosg')) {
        return false;
    }

    $parent_id = $product->get_id();

    if (!method_exists($product, 'get_items')) {
        return false;
    }

    $items = $product->get_items();
    if (empty($items)) {
        return false;
    }

    $parsed_items = array();

    foreach ($items as $item) {
        $id = intval($item['id'] ?? 0);
        $qty = floatval($item['qty'] ?? 1);

        if ($id > 0) {
            if (isset($parsed_items[$id])) {
                $parsed_items[$id] += $qty;
            } else {
                $parsed_items[$id] = $qty;
            }
        }
    }

    if (empty($parsed_items)) {
        return false;
    }

    $total_regular_price = 0.0;
    $total_bundle_price = 0.0;
    $debug_log = '';

    foreach ($parsed_items as $item_id => $qty) {
        $item_product = wc_get_product($item_id);
        if (!$item_product) {
            continue;
        }

        // --- POBIERANIE CENY REGULARNEJ ---
        $item_regular_price = $item_product->get_regular_price();
        if ($item_regular_price === '') {
            $item_regular_price = $item_product->get_price(); // Jeśli produkt nie ma przekreślonej ceny, bierzemy aktualną
        }

        // Zabezpieczenie przed brakiem jakiejkolwiek ceny
        if ($item_regular_price === '' || !is_numeric($item_regular_price)) {
            $item_regular_price = 0;
        }

        // Przepuszczamy cenę przez filtr wyświetlania, żeby uwzględnić podatki!
        $item_regular_price_taxed = wc_get_price_to_display($item_product, array('price' => floatval($item_regular_price)));
        $total_regular_price += ($item_regular_price_taxed * $qty);

        // --- POBIERANIE CENY W ZESTAWIE (NASZEJ PROMOCYJNEJ) ---
        // Używamy helpera utworzonego w pliku ceny-zestawow.php, który sam szuka lokalnych nadpisań!
        if (function_exists('shav_get_product_price_in_bundle')) {
            $item_bundled_price = shav_get_product_price_in_bundle($item_product, $parent_id);
        } else {
            $item_bundled_price = ''; // Fallback, jeśli helpera nie ma
        }

        // Jeśli zestaw nie nadpisuje ceny, używamy zwykłej ceny produktu
        if ($item_bundled_price === '' || !is_numeric($item_bundled_price)) {
            $item_bundled_price = $item_product->get_price();
        }

        if ($item_bundled_price === '' || !is_numeric($item_bundled_price)) {
            $item_bundled_price = 0;
        }

        $item_bundled_price_taxed = wc_get_price_to_display($item_product, array('price' => floatval($item_bundled_price)));
        $total_bundle_price += ($item_bundled_price_taxed * $qty);

        $debug_log .= ' [Item: ' . $item_id . ' | Qty: ' . $qty . ' | Reg: ' . $item_regular_price_taxed . ' | Bundle: ' . $item_bundled_price_taxed . ']';
    }

    return array(
        'regular' => $total_regular_price,
        'bundle'  => $total_bundle_price,
        'debug'   => $debug_log
    );
}
