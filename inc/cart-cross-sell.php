<?php
// START CROSSELL + GRATIS W KOSZYKU

// -----------------------------------------------------------------------------
// 0. MIGRACJA NA SYSTEM UID (URUCHAMIANA AUTOMATYCZNIE W TLE)
// -----------------------------------------------------------------------------
add_action( 'admin_init', 'cs_uid_migration' );
function cs_uid_migration() {
    if ( get_option( 'cs_uid_migrated_v11' ) ) return;
    
    $rules = get_option( 'wc_cs_rules', array() );
    if ( empty( $rules ) ) { 
        update_option( 'cs_uid_migrated_v11', true ); 
        return; 
    }
    
    $migrated = false;
    foreach ( $rules as $idx => &$rule ) {
        if ( empty( $rule['uid'] ) ) {
            $rule['uid'] = uniqid( 'cs_' );
            $migrated = true;
        }
    }
    
    if ( $migrated ) {
        update_option( 'wc_cs_rules', $rules );
        $daily_stats = get_option( 'wc_cs_daily_stats', array() );
        
        if ( ! empty( $daily_stats ) ) {
            $stats_migrated = false;
            foreach ( $daily_stats as $date => &$stats ) {
                foreach ( $stats as $key => $data ) {
                    // JEŚLI KLUCZ JEST STARYM NUMEREM INDEXU, PRZEMAPUJ NA NOWY UID
                    if ( is_numeric( $key ) && isset( $rules[$key]['uid'] ) ) {
                        $stats[$rules[$key]['uid']] = $data;
                        unset( $stats[$key] );
                        $stats_migrated = true;
                    }
                }
            }
            if ( $stats_migrated ) update_option( 'wc_cs_daily_stats', $daily_stats );
        }
    }
    update_option( 'cs_uid_migrated_v11', true );
}

// -----------------------------------------------------------------------------
// 1. LOGIKA KOSZYKA - GRATISY (AUTOMATYCZNE DODAWANIE/USUWANIE)
// -----------------------------------------------------------------------------
add_action( 'woocommerce_before_calculate_totals', 'cs_process_gratis_rules', 10, 1 );
function cs_process_gratis_rules( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    
    // ZABEZPIECZENIE PRZED PĘTLĄ (ANTI-LOOP)
    static $processing = false;
    if ( $processing ) return;
    $processing = true;

    $rules = get_option('wc_cs_gratis_rules', array());
    if (empty($rules)) {
        $processing = false;
        return;
    }

    $cart_total = 0;
    $main_product_qtys = array();

    // POBRANIE INFORMACJI O ODRZUCONYCH PREZENTACH PRZEZ KLIENTA Z SESJI
    $rejected_gifts = WC()->session->get('cs_rejected_gifts', array());

    // SUMOWANIE KOSZYKA Z POMINIĘCIEM PREZENTÓW
    foreach ( $cart->get_cart() as $key => $item ) {
        if ( ! isset($item['is_free_gift']) ) {
            $cart_total += $item['line_total'];
            
            $pid = $item['product_id'];
            $vid = $item['variation_id'];
            
            if (!isset($main_product_qtys[$pid])) $main_product_qtys[$pid] = 0;
            $main_product_qtys[$pid] += $item['quantity'];
            
            if ($vid) {
                if (!isset($main_product_qtys[$vid])) $main_product_qtys[$vid] = 0;
                $main_product_qtys[$vid] += $item['quantity'];
            }
        }
    }

    // PRZETWARZANIE REGUŁ
    foreach ($rules as $idx => $rule) {
        if ( empty($rule['active']) || $rule['active'] !== 'yes' ) continue;
        if ( ! empty($rule['admin_only']) && $rule['admin_only'] === 'yes' && ! current_user_can('manage_options') ) continue;

        $gift_id = intval($rule['gift_id']);
        if (!$gift_id) continue;

        // JEŚLI KLIENT ODRZUCIŁ TEN PREZENT, POMIJAMY JEGO DODAWANIE
        if (in_array($gift_id, $rejected_gifts)) continue;

        $required_qty = 0;

        if (isset($rule['trigger_type']) && $rule['trigger_type'] === 'amount') {
            $threshold = (float) str_replace(',', '.', isset($rule['threshold']) ? $rule['threshold'] : 0);
            if ($threshold > 0 && $cart_total >= $threshold) {
                $required_qty = 1; // 1 GRATIS NA ZAMÓWIENIE DLA KWOTY
            }
        } else {
            $target_ids = isset($rule['target_ids']) ? (array)$rule['target_ids'] : array();
            $sync_qty = isset($rule['sync_qty']) && $rule['sync_qty'] === 'yes';
            
            foreach ($target_ids as $tid) {
                if (isset($main_product_qtys[$tid]) && $main_product_qtys[$tid] > 0) {
                    if ($sync_qty) {
                        $required_qty += $main_product_qtys[$tid];
                    } else {
                        $required_qty = 1;
                        break; 
                    }
                }
            }
        }

        // ZNAJDŹ PREZENT W KOSZYKU
        $gift_key = false;
        foreach ($cart->get_cart() as $key => $item) {
            if ( isset($item['is_free_gift']) && intval($item['is_free_gift']) === $idx ) {
                $gift_key = $key;
                break;
            }
        }

        if ($required_qty > 0) {
            if ($gift_key) {
                if ($cart->cart_contents[$gift_key]['quantity'] != $required_qty) {
                    $cart->set_quantity($gift_key, $required_qty, false);
                }
            } else {
                $cart->add_to_cart($gift_id, $required_qty, 0, array(), array('is_free_gift' => $idx));
            }
        } else {
            if ($gift_key) {
                $cart->remove_cart_item($gift_key);
            }
        }
    }
    
    $processing = false;
}

// -----------------------------------------------------------------------------
// 1B. LOGIKA SESJI - REJESTROWANIE USUNIĘCIA GRATISU PRZEZ KLIENTA
// -----------------------------------------------------------------------------
add_action( 'woocommerce_remove_cart_item', 'cs_register_gift_rejection', 10, 2 );
function cs_register_gift_rejection( $cart_item_key, $cart ) {
    if ( isset( $cart->cart_contents[ $cart_item_key ]['is_free_gift'] ) ) {
        $gift_id = $cart->cart_contents[ $cart_item_key ]['product_id'];
        $rejected_gifts = WC()->session->get('cs_rejected_gifts', array());
        
        if (!in_array($gift_id, $rejected_gifts)) {
            $rejected_gifts[] = $gift_id;
            WC()->session->set('cs_rejected_gifts', $rejected_gifts);
        }
    }
}

// CZYSZCZENIE SESJI ODRZUCONYCH PREZENTÓW PO ZŁOŻENIU ZAMÓWIENIA LUB OPRÓŻNIENIU KOSZYKA
add_action( 'woocommerce_cart_emptied', 'cs_clear_gift_rejection_session' );
add_action( 'woocommerce_thankyou', 'cs_clear_gift_rejection_session' );
function cs_clear_gift_rejection_session() {
    WC()->session->__unset('cs_rejected_gifts');
}

// -----------------------------------------------------------------------------
// 2. MODYFIKACJA CEN W KOSZYKU (CROSSELL I GRATISY)
// -----------------------------------------------------------------------------
add_action( 'woocommerce_before_calculate_totals', 'cs_modify_prices_all', 20, 1 );
function cs_modify_prices_all( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    
    static $processing_prices = false;
    if ( $processing_prices ) return;
    $processing_prices = true;

    $cs_rules = get_option( 'wc_cs_rules', array() );
    
    // AKTUALIZACJA: DYNAMICZNY SUFIKS DLA PREZENTÓW POBIERANY Z BAZY
    $gift_suffix = get_option('wc_cs_gift_suffix', ' (Prezent)');

    foreach ( $cart->get_cart() as $key => $item ) {
        // A. GRATIS
        if ( isset( $item['is_free_gift'] ) ) {
            // AKTUALIZACJA: CENA 1 GROSZ ZAMIAST ZERA
            $item['data']->set_price( 0.01 );
            $name = $item['data']->get_name();
            // AKTUALIZACJA: ELASTYCZNE DOKLEJANIE SUFIKSU BEZ TWARDEGO ZAKODOWANIA W PHP
            if ( strpos( $name, $gift_suffix ) === false ) {
                $item['data']->set_name( $name . ' ' . trim($gift_suffix) );
            }
        } 
        // B. CROSSELL
        elseif ( isset( $item['is_cross_sell_origin'] ) ) {
            $rule_uid = $item['is_cross_sell_origin'];
            
            // ZNAJDŹ REGUŁĘ PO UID LUB STARYM INDEKSIE
            $found_rule = null;
            foreach ($cs_rules as $idx => $r) {
                if ( (isset($r['uid']) && $r['uid'] === $rule_uid) || (strval($idx) === strval($rule_uid)) ) {
                    $found_rule = $r;
                    break;
                }
            }
            
            if ( $found_rule ) {
                $price_promo = isset( $found_rule['price_promo'] ) ? str_replace( ',', '.', $found_rule['price_promo'] ) : '';
                $price_reg   = isset( $found_rule['price_reg'] ) ? str_replace( ',', '.', $found_rule['price_reg'] ) : '';
                $new_price = '';
                if ( ! empty( $price_promo ) && is_numeric( $price_promo ) ) {
                    $new_price = (float) $price_promo;
                } elseif ( ! empty( $price_reg ) && is_numeric( $price_reg ) ) {
                    $new_price = (float) $price_reg;
                }
                if ( $new_price !== '' ) $item['data']->set_price( $new_price );
            }
        }
    }
    $processing_prices = false;
}

// -----------------------------------------------------------------------------
// 3. BLOKADY UX W KOSZYKU DLA GRATISÓW
// -----------------------------------------------------------------------------
add_filter( 'woocommerce_cart_item_quantity', 'cs_disable_gift_quantity_input', 10, 3 );
function cs_disable_gift_quantity_input( $product_quantity, $cart_item_key, $cart_item ) {
    if ( isset( $cart_item['is_free_gift'] ) ) {
        return sprintf( '%s <input type="hidden" name="cart[%s][qty]" value="%s" />', $cart_item['quantity'], $cart_item_key, $cart_item['quantity'] );
    }
    return $product_quantity;
}

// AKTUALIZACJA: USUNIĘTO FILTR `woocommerce_cart_item_remove_link`
// KRZYŻYK USUWANIA JEST TERAZ WIDOCZNY, ABY KLIENT MÓGŁ USUNĄĆ GRATIS.

// -----------------------------------------------------------------------------
// 4. GŁÓWNA FUNKCJA WYŚWIETLAJĄCA OFERTĘ W KOSZYKU (Z OBSŁUGĄ KARUZELI I STRZAŁKĄ)
// -----------------------------------------------------------------------------
function custom_element_inside_cart_left_column() {
    if ( ! is_cart() || is_admin() ) {
        return;
    }

    $rules = get_option( 'wc_cs_rules', array() );
    if ( empty( $rules ) || ! is_array( $rules ) ) {
        return;
    }

    // SORTOWANIE REGUŁ PO PRIORYTECIE (MNIEJSZA LICZBA = WYŻSZY PRIORYTET / WYŚWIETLA SIĘ WCZEŚNIEJ)
    usort($rules, function($a, $b) {
        $p_a = isset($a['priority']) && is_numeric($a['priority']) ? (int)$a['priority'] : 0;
        $p_b = isset($b['priority']) && is_numeric($b['priority']) ? (int)$b['priority'] : 0;
        return $p_a - $p_b;
    });

    $cart = WC()->cart->get_cart();
    $cart_product_ids = array();
    foreach ( $cart as $cart_item ) {
        $cart_product_ids[] = $cart_item['product_id'];
        if ( ! empty( $cart_item['variation_id'] ) ) {
            $cart_product_ids[] = $cart_item['variation_id']; 
        }
    }

    $matched_rules = array();

    // ZBIERANIE WSZYSTKICH PASUJĄCYCH REGUŁ (TYLKO WŁĄCZONYCH) DLA KARUZELI LUB SIATKI
    foreach ( $rules as $index => $rule ) {
        if ( isset($rule['is_active']) && $rule['is_active'] === 'no' ) continue; // POMIJAJ WYŁĄCZONE REGUŁY
        if ( empty( $rule['product_id'] ) ) continue;
        if ( in_array( $rule['product_id'], $cart_product_ids ) ) continue;

        $target_ids = isset( $rule['target_ids'] ) && is_array( $rule['target_ids'] ) ? $rule['target_ids'] : array();

        $intersect = array_intersect( $cart_product_ids, $target_ids );
        if ( count( $intersect ) > 0 ) {
            $rule['_matched_trigger_id'] = reset($intersect);
            $rule['_rule_index'] = $index;
            $matched_rules[] = $rule;
        }
    }

    // JEŚLI NIE MA STANDARDOWYCH, UŻYJ GLOBALNYCH (TEŻ Z UWZGLĘDNIENIEM PRIORYTETU I STATUSU)
    if ( empty( $matched_rules ) ) {
        foreach ( $rules as $index => $rule ) {
            if ( isset($rule['is_active']) && $rule['is_active'] === 'no' ) continue;
            if ( empty( $rule['product_id'] ) ) continue;
            if ( in_array( $rule['product_id'], $cart_product_ids ) ) continue;
            if ( isset( $rule['is_global'] ) && $rule['is_global'] === 'yes' ) {
                $rule['_rule_index'] = $index;
                $matched_rules[] = $rule;
            }
        }
    }

    if ( empty( $matched_rules ) ) return;
    
    // POBIERANIE USTAWIEN GLOBALNYCH I USTAWIEN ODZNAKI
    $global_header = get_option('wc_cs_global_header', '');
    $global_sub_header = get_option('wc_cs_global_sub_header', '');
    $layout_mode = get_option('wc_cs_layout_mode', 'carousel');
    $show_savings_badge = get_option('wc_cs_show_savings_badge', 'no');
    $savings_badge_type = get_option('wc_cs_savings_badge_type', 'amount');
    $savings_badge_text = get_option('wc_cs_savings_badge_text', '');

    ?>
<style>
        /* WYRÓWNANIE DO LEWEJ I OGRANICZENIE DO 700PX ZGODNIE ZE STARYM PLIKIEM */
        .my-cart-element-container { margin: 50px 0; max-width: 700px; font-family: inherit; padding-top: 0; position: relative; }
        .my-cart-element-container-header { font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; color: #000; }
        .my-cart-global-sub-header { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #C68E7A; margin-top: -10px; margin-bottom: 20px; letter-spacing: 0.8px; padding-left: 2%; }
        
        /* KARUZELA */
        .cs-layout-carousel { display: flex; overflow-x: auto; gap: 15px; scroll-snap-type: x mandatory; padding-bottom: 15px; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; scroll-behavior: smooth; }
        .cs-layout-carousel::-webkit-scrollbar { display: none; }
        .cs-layout-carousel .my-cart-element { flex: 0 0 100%; scroll-snap-align: start; }
        
        /* SIATKA Z ODZYSKANEJ WERSJI (W PEŁNI RESPANSYWNA EQUAL HEIGHT) */
        .cs-layout-grid { display: grid; gap: 15px; padding-bottom: 10px; align-items: stretch; }
        
        @media (min-width: 769px) {
            /* POPRAWKA: AUTO-FILL ZAPOBIEGA ROZCIĄGANIU GRIDU NA PEŁNĄ SZEROKOŚĆ */
            .cs-layout-grid { grid-template-columns: repeat(auto-fill, minmax(215px, 1fr)); }
            /* POPRAWKA: SZTYWNY SUFIT SZEROKOŚCI DLA KAFELKA, ABY PRZY 1 LUB 2 PRODUKTACH NIE ROZLAŁY SIĘ NA CAŁĄ SEKCJE */
            .cs-layout-grid .my-cart-element { max-width: 220px; }
            
            /* PRZYWRÓCONY WYGLĄD KARUZELI NA DESKTOPIE ZE STAREGO KODU Z DODATKOWYM ODDECHEM W WEWNĘTRZNYM KONTENERZE */
            .cs-layout-carousel .my-cart-element { padding: 4% 2%; }
            .cs-layout-carousel .my-cart-items { grid-template-columns: 180px 1fr; align-items: stretch; display: grid; }
            .cs-layout-carousel .my-cart-product-info { padding: 10px 25px 25px 25px; display: flex; flex-direction: column; justify-content: center; }
            .cs-layout-carousel .my-cart-add-to-cart-button { position: absolute; bottom: 40px; right: 25px; padding: 0; width: auto; margin: 0; display: block; }
            .cs-layout-carousel .my-cart-add-to-cart-button .button { width: auto !important; }
        }

        /* USUNIĘTO DEPENDENCY OD .HAS-MULTIPLE, ABY JEDEN KAFEL WYGLĄDAŁ IDENTYCZNIE */
        .cs-layout-grid .my-cart-items { grid-template-columns: 1fr; grid-template-rows: auto 1fr auto; height: 100%; display: grid; }
        .cs-layout-grid .my-cart-product-img::after { display: none; }
        .cs-layout-grid .my-cart-product-img { border-bottom: 1px solid rgba(0,0,0,0.05); }
        
        /* ZMIANA DISPLAY NA FLEX W KOLUMNIE ABY CENA MOGŁA SPAŚĆ NA DÓŁ */
        .cs-layout-grid .my-cart-product-info { padding: 10px 15px 15px 15px; align-items: center; text-align: center; display: flex; flex-direction: column; }
        
        /* WYRÓWNANIE CENY DO DOŁU W GRIDZIE DESKTOPOWYM (ZABEZPIECZENIE PRZED RÓŻNYMI DŁUGOŚCIAMI ATRYBUTÓW) */
        .cs-layout-grid .my-cart-product-price { margin-top: auto; }
        
        .cs-layout-grid .my-cart-add-to-cart-button { padding: 15px; width: 100%; display: flex; align-items: center; justify-content: center; position: relative; bottom: auto; right: auto; margin-top: 0; box-sizing: border-box; }
        .cs-layout-grid .my-cart-add-to-cart-button .button { width: 100%; text-align: center; margin: 0; box-sizing: border-box !important; }
        
        /* STRZAŁKI NAWIGACYJNE WCIĄGNIĘTE DO ŚRODKA RAMKI Z EFEKTEM PREMIUM - ODSUNIĘTE OD KRAWĘDZI NA 3% */
        .cs-carousel-nav-arrow { display: none; position: absolute; top: 50%; transform: translateY(-50%); background: transparent; border: none; box-shadow: none; z-index: 20; cursor: pointer; align-items: center; justify-content: center; font-size: 2.5rem; color: #555; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); padding: 0; }
        .cs-carousel-nav-arrow:hover { color: #C68E7A; background: transparent; transform: translateY(-50%) scale(1.15); }
        .cs-nav-prev { left: 3%; } 
        .cs-nav-next { right: 3%; } 
        @media (max-width: 768px) { .cs-carousel-nav-arrow { display: none !important; } } 
        
        /* WSPÓLNE STYLE DLA KARUZELI I SIATKI */
        .my-cart-element { background: transparent; border: 1px solid #f2f2f2; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 0; position: relative; max-width: 100%; overflow: hidden; box-sizing: border-box; height: 100%; }

        .my-cart-product-img { display: flex; align-items: center; justify-content: center; padding: 20px; background: transparent; position: relative; }
        .my-cart-product-img::after { content: ""; position: absolute; right: 0; top: 20px; bottom: 20px; width: 1px; background: rgba(0,0,0,0.05); z-index: 1; }
        .my-cart-product-img img { max-width: 100%; max-height: 130px; height: auto; object-fit: contain; transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); backface-visibility: hidden; -webkit-font-smoothing: subpixel-antialiased; will-change: transform; }
        .my-cart-element:hover .my-cart-product-img img { transform: scale(1.08); }
        .my-cart-product-title { font-size: 17px; font-weight: 700; margin-bottom: 6px; color: #000; }
        .my-cart-product-attribute { font-size: 13px; color: #666; margin-bottom: 5px; }
        .my-cart-product-price { margin-top: 15px; font-size: 14px; }
        .my-cart-product-price del { color: #999; margin-right: 8px; text-decoration: none; }
        .my-cart-product-price span { font-size: 19px; font-weight: 700; color: #000; }
        
        .my-cart-add-to-cart-button .button { background-color: #1a1a1a !important; color: #fff !important; border-radius: 8px !important; padding: 12px 28px !important; font-weight: 700 !important; text-transform: uppercase !important; font-size: 12px !important; letter-spacing: 1px; text-decoration: none !important; border: none !important; transition: all 0.2s ease !important; transform: scale(1.02); display: block; cursor: pointer; box-sizing: border-box !important; margin: 0; }
        .my-cart-add-to-cart-button .button:hover { background-color: #C68E7A !important; transform: scale(1.05); text-decoration: none !important; }
        
        /* WIDOK MOBILNY - WYMUSZONY SLIDER ZE SCROLLBAREM (PASKIEM PROGRESU) ORAZ PRAWDZIWE EQUAL HEIGHT DLA OBU TRYBÓW */
        @media (max-width: 768px) {
            .cs-layout-carousel, .cs-layout-grid { 
                display: flex !important;
                flex-wrap: nowrap !important; 
                overflow-x: auto !important; 
                scroll-snap-type: x mandatory !important; 
                padding-bottom: 15px !important; 
                -webkit-overflow-scrolling: touch; 
                scrollbar-width: thin !important; 
                scrollbar-color: #C68E7A #e5e5e5 !important;
                align-items: stretch !important;
            }

            /* PASEK PROGRESU (SCROLLBAR) */
            .cs-layout-carousel::-webkit-scrollbar, .cs-layout-grid::-webkit-scrollbar { display: block !important; height: 6px !important; }
            .cs-layout-carousel::-webkit-scrollbar-thumb, .cs-layout-grid::-webkit-scrollbar-thumb { background: #C68E7A !important; border-radius: 4px; }
            .cs-layout-carousel::-webkit-scrollbar-track, .cs-layout-grid::-webkit-scrollbar-track { background: #e5e5e5 !important; border-radius: 4px; margin: 0 10px; }

            .cs-layout-carousel .my-cart-element, .cs-layout-grid .my-cart-element {
                flex: 0 0 85% !important;
                max-width: 85% !important;
                scroll-snap-align: start; 
                display: flex !important;
                flex-direction: column !important;
                height: auto !important; /* ODBLOKOWUJE ROZCIĄGANIE W PIONIE (STRETCH) NA BAZIE NAJWYŻSZEGO ELEMENTU */
            }

            .my-cart-items, .cs-layout-carousel .my-cart-items, .cs-layout-grid .my-cart-items { display: flex !important; flex-direction: column !important; flex-grow: 1; height: 100%; }
            .my-cart-product-img, .cs-layout-carousel .my-cart-product-img { height: 160px; padding: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); flex-shrink: 0; }
            .my-cart-product-img::after { display: none !important; }
            
            /* WYMUSZENIE WYPEŁNIENIA PUSTEJ PRZESTRZENI PRZEZ INFO, CO SPYCHA PRZYCISKI DO JEDNEJ LINI NA DOLE */
            .my-cart-product-info, .cs-layout-carousel .my-cart-product-info { padding: 15px !important; text-align: center; align-items: center !important; justify-content: flex-start !important; flex-grow: 1; display: flex; flex-direction: column; }
            
            /* WYRÓWNANIE CENY DO DOŁU KONTENERA ABY ZNIWELOWAĆ RÓŻNICE W DŁUGOŚCI TEKSTU */
            .my-cart-product-price { margin-top: auto !important; }

            .my-cart-add-to-cart-button, .cs-layout-carousel .my-cart-add-to-cart-button { padding: 15px !important; width: 100%; display: flex; align-items: center; justify-content: center; position: relative !important; bottom: auto !important; right: auto !important; margin-top: auto !important; box-sizing: border-box; flex-shrink: 0; }
            
            /* WYŚRODKOWANIE NAPISU NA PRZYCISKU */
            .my-cart-add-to-cart-button .button, .cs-layout-carousel .my-cart-add-to-cart-button .button { width: 100% !important; max-width: none !important; display: flex !important; justify-content: center !important; align-items: center !important; }
        }
        
        .my-cart-element.is-loading { opacity: 0.6; pointer-events: none; transition: opacity 0.3s ease; }
        .my-cart-add-to-cart-button .button.loading { background-color: #666 !important; cursor: wait !important; }
        .my-cart-element::before { content: ""; position: absolute; top: 0; left: 0; width: 0; height: 2px; background: #C68E7A; z-index: 10; transition: none; }
        .my-cart-element.is-loading::before { width: 100%; transition: width 4s linear; }

        /* FIX: BADGE OSZCZĘDNOŚCI Z PRAWEJ STRONY */
        .cs-savings-badge { position: absolute; top: 15px; right: 15px; left: auto; background: #C68E7A; color: #fff; padding: 4px 8px; font-size: 11px; font-weight: 700; border-radius: 8px; z-index: 5; text-transform: uppercase; pointer-events: none; }
</style>

    <div class="my-cart-element-container" id="cross-sell-cart-container" style="position: relative;">
        <?php if (!empty($global_header)): ?>
            <div class="my-cart-element-container-header"><?php echo esc_html( $global_header ); ?></div>
        <?php endif; ?>
        
        <?php 
        // FIX: WYŚWIETLANIE GLOBALNEGO CZERWONEGO PODTYTUŁU TYLKO POD GŁÓWNYM NAGŁÓWKIEM W TRYBIE SIATKI
        if ($layout_mode === 'grid' && !empty($global_sub_header)): ?>
            <div class="my-cart-global-sub-header"><?php echo esc_html( $global_sub_header ); ?></div>
        <?php endif; ?>
        
        <div class="cs-layout-<?php echo esc_attr($layout_mode); ?> <?php echo count($matched_rules) > 1 ? 'has-multiple' : ''; ?>" id="cs-carousel-scroll">
            <?php foreach ($matched_rules as $selected_rule): 
                $cross_sell_product_id = $selected_rule['product_id'];
                $selected_attr = isset($selected_rule['sync_attr']) ? $selected_rule['sync_attr'] : '';
                $matched_trigger_id = isset($selected_rule['_matched_trigger_id']) ? $selected_rule['_matched_trigger_id'] : null;

                // BACKUP DLA ZACHOWANIA KOMPATYBILNOŚCI Z ZAPISANYMI JUŻ REGUŁAMI
                if ( ! empty($selected_rule['sync_color']) && $selected_rule['sync_color'] === 'yes' && $matched_trigger_id && !empty($selected_attr) ) {
                    $trigger_product = wc_get_product($matched_trigger_id);
                    $trigger_val = '';
                    
                    if ( $trigger_product ) {
                        if ( $trigger_product->is_type( 'variation' ) ) {
                            $trigger_attrs = $trigger_product->get_variation_attributes();
                            if ( isset( $trigger_attrs['attribute_' . $selected_attr] ) ) {
                                $trigger_val = $trigger_attrs['attribute_' . $selected_attr];
                            }
                        } else {
                            $trigger_val = $trigger_product->get_attribute( $selected_attr );
                        }
                    }

                    if ( $trigger_val ) {
                        $offered_parent_product = wc_get_product($cross_sell_product_id);
                        if ( $offered_parent_product && $offered_parent_product->is_type('variable') ) {
                            foreach ( $offered_parent_product->get_visible_children() as $variation_id ) {
                                $variation = wc_get_product($variation_id);
                                $var_attrs = $variation->get_variation_attributes();
                                
                                if ( isset($var_attrs['attribute_' . $selected_attr]) && strcasecmp($var_attrs['attribute_' . $selected_attr], $trigger_val) === 0 ) {
                                    $cross_sell_product_id = $variation_id; 
                                    break;
                                }
                            }
                        }
                    }
                }

                $product = wc_get_product( $cross_sell_product_id );
                if ( ! $product ) continue;

                $is_variation = $product->is_type( 'variation' );
                $add_to_cart_id = $cross_sell_product_id; 
                $variation_data = '';

                if ( $is_variation ) {
                    $attributes = $product->get_variation_attributes();
                    foreach ( $attributes as $attr_name => $attr_value ) {
                        $variation_data .= ' data-' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
                    }
                    $variation_data .= ' data-variation_id="' . esc_attr( $cross_sell_product_id ) . '"';
                }

                $title        = ! empty($selected_rule['title']) ? $selected_rule['title'] : '';
                if (empty($title)) {
                    $title = $product->is_type('woosg') ? 'Spar-Set' : $product->get_name();
                }

                $attr_1       = isset($selected_rule['attr_1']) && $selected_rule['attr_1'] !== '' ? $selected_rule['attr_1'] : '';
                if (empty($attr_1)) {
                    $attr_1 = get_post_meta($product->get_id(), 'product_subtitle', true);
                }

                $attr_2       = isset($selected_rule['attr_2']) ? $selected_rule['attr_2'] : '';
                
                $price_reg    = isset($selected_rule['price_reg']) ? $selected_rule['price_reg'] : '';
                $price_promo  = isset($selected_rule['price_promo']) ? $selected_rule['price_promo'] : '';

                if (empty($price_reg) && empty($price_promo)) {
                    if ($product->is_type('woosg') && function_exists('shav_get_woosg_totals')) {
                        $totals = shav_get_woosg_totals($product);
                        if ($totals) {
                            $price_reg = (string) $totals['regular'];
                            $price_promo = (string) $totals['bundle'];
                        }
                    } else {
                        $reg = $product->get_regular_price();
                        if ($reg === '') $reg = $product->get_price();
                        $price_reg = (string) wc_get_price_to_display($product, ['price' => $reg]);
                        $price_promo = (string) wc_get_price_to_display($product);
                    }
                }
                $custom_img   = isset($selected_rule['custom_image']) ? $selected_rule['custom_image'] : '';
                
                $rule_uid_to_log = isset($selected_rule['uid']) ? $selected_rule['uid'] : $selected_rule['_rule_index'];
                
                $display_price_reg = '';
                $display_price_promo = '';
                
                if ( ! empty( $price_promo ) ) {
                    $display_price_reg = wp_strip_all_tags( wc_price( (float) str_replace( ',', '.', $price_reg ) ) );
                    $display_price_promo = wp_strip_all_tags( wc_price( (float) str_replace( ',', '.', $price_promo ) ) );
                } elseif ( ! empty( $price_reg ) ) {
                    $display_price_promo = wp_strip_all_tags( wc_price( (float) str_replace( ',', '.', $price_reg ) ) );
                }

                if ( ! empty( $custom_img ) && empty($selected_rule['sync_color']) ) {
                    $image_url = $custom_img;
                } else {
                    $image_id = $product->get_image_id();
                    if ( ! $image_id && $is_variation ) {
                        $image_id = wc_get_product( $product->get_parent_id() )->get_image_id();
                    }
                    $image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
                }

                // WYLICZENIE BADGE OSZCZĘDNOŚCI
                $badge_html = '';
                if ( $show_savings_badge === 'yes' && ! empty( $price_reg ) && ! empty( $price_promo ) ) {
                    $reg_val = (float) str_replace( ',', '.', $price_reg );
                    $promo_val = (float) str_replace( ',', '.', $price_promo );
                    if ( $reg_val > $promo_val ) {
                        if ( $savings_badge_type === 'percent' ) {
                            $discount = round( ( ( $reg_val - $promo_val ) / $reg_val ) * 100 );
                            $badge_value = '-' . $discount . '%';
                        } else {
                            $discount = $reg_val - $promo_val;
                            $badge_value = '-' . wp_strip_all_tags( wc_price( $discount ) );
                        }
                        $badge_label = ! empty( $savings_badge_text ) ? $savings_badge_text . ' ' : '';
                        $badge_html = '<div class="cs-savings-badge">' . esc_html( $badge_label ) . $badge_value . '</div>';
                    }
                }
            ?>
            
            <div class="my-cart-element">
                <!-- FIX: USUNIĘTO INDYWIDUALNY CZERWONY NAGŁÓWEK (.my-cart-header-container) -->
                <div class="my-cart-items">
                    <div class="my-cart-product-img">
                        <?php echo $badge_html; ?>
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                    </div>
                    <div class="my-cart-product-info">
                        <div class="my-cart-product-title"><?php echo esc_html( $title ); ?></div>
                        <?php if ( ! empty( $attr_1 ) ) : ?><div class="my-cart-product-attribute"><?php echo esc_html( $attr_1 ); ?><br></div><?php endif; ?>
                        <?php if ( ! empty( $attr_2 ) ) : ?><div class="my-cart-product-attribute"><?php echo esc_html( $attr_2 ); ?></div><?php endif; ?>
                        <?php if ( ! empty( $display_price_reg ) || ! empty( $display_price_promo ) ) : ?>
                        <div class="my-cart-product-price">
                            <?php esc_html_e( 'Price', 'woocommerce' ); ?>: 
                            <?php if ( ! empty( $display_price_reg ) ) : ?><del><?php echo wp_kses_post( $display_price_reg ); ?></del><?php endif; ?>
                            <?php if ( ! empty( $display_price_promo ) ) : ?><span><?php echo wp_kses_post( $display_price_promo ); ?></span><?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="my-cart-add-to-cart-button my-add-to-cart-button">
                        <a href="#" 
                           data-quantity="1" 
                           data-product_id="<?php echo esc_attr( $add_to_cart_id ); ?>"
                           data-is_cs="<?php echo esc_attr( $rule_uid_to_log ); ?>"
                           <?php echo $variation_data; ?>
                           class="button cs_ajax_add_to_cart">
                           <?php esc_html_e( 'Add to cart', 'woocommerce' ); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <?php endforeach; ?>
        </div>
        
        <?php if ($layout_mode === 'carousel' && count($matched_rules) > 1): ?>
            <div class="cs-carousel-nav-arrow cs-nav-prev" id="cs-nav-prev">&#10094;</div>
            <div class="cs-carousel-nav-arrow cs-nav-next" id="cs-nav-next">&#10095;</div>
        <?php endif; ?>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var logAjaxUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'cs_log_event' );
        
        // LOGOWANIE WYŚWIETLEŃ DLA WSZYSTKICH WYRENDEROWANYCH REGUŁ W KARUZELI
        var uidsToLog = <?php echo json_encode(array_values(array_unique(array_map(function($r){ return isset($r['uid']) ? $r['uid'] : $r['_rule_index']; }, $matched_rules)))); ?>;
        if (uidsToLog.length > 0) {
            uidsToLog.forEach(function(uid) {
                $.post(logAjaxUrl, { rule_uid: uid, type: 'impression' });
            });
        }
        
        // LOGIKA STRZAŁEK NAWIGACYJNYCH W KARUZELI
        var $wrapper = $('#cs-carousel-scroll');
        if ($wrapper.length > 0 && $wrapper.hasClass('cs-layout-carousel') && $wrapper.hasClass('has-multiple')) {
            var wrapperNode = $wrapper[0];

            function updateArrows() {
                var maxScroll = wrapperNode.scrollWidth - wrapperNode.offsetWidth;
                // STRZAŁKA WSTECZ
                if (wrapperNode.scrollLeft > 10) {
                    $('#cs-nav-prev').css('display', 'flex');
                } else {
                    $('#cs-nav-prev').hide();
                }
                // STRZAŁKA W PRZÓD
                if (wrapperNode.scrollLeft >= maxScroll - 10) {
                    $('#cs-nav-next').hide();
                } else {
                    $('#cs-nav-next').css('display', 'flex');
                }
            }

            // INICJALIZACJA I NASŁUCHIWANIE SCROLLA
            updateArrows();
            $wrapper.on('scroll', function() {
                updateArrows();
            });

            // KLIKNIĘCIE DALEJ - UWZGLĘDNIONO GAP DLA PŁYNNEGO PRZEWIJANIA
            $('#cs-nav-next').on('click', function() {
                var scrollAmount = wrapperNode.offsetWidth + 15;
                wrapperNode.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            // KLIKNIĘCIE WSTECZ - UWZGLĘDNIONO GAP DLA PŁYNNEGO PRZEWIJANIA
            $('#cs-nav-prev').on('click', function() {
                var scrollAmount = wrapperNode.offsetWidth + 15;
                wrapperNode.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }

        $(document).on('click', '.cs_ajax_add_to_cart', function(e) {
            e.preventDefault();
            var $button = $(this);
            $button.closest('.my-cart-element').addClass('is-loading');

            $.post(logAjaxUrl, { rule_uid: $button.data('is_cs'), type: 'click' });

            var data = {
                product_id: $button.data('product_id'), 
                quantity: $button.data('quantity'),
                is_cs: $button.data('is_cs')
            };

            if ($button.attr('data-variation_id')) {
                data.variation_id = $button.attr('data-variation_id');
                $.each($button[0].attributes, function(index, attribute) {
                    if (attribute.name.startsWith('data-attribute_')) {
                        var attrName = attribute.name.replace('data-', '');
                        data[attrName] = attribute.value;
                    }
                });
            }

            var wc_ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' );

            $.post( wc_ajax_url, data, function( response ) {
                if ( ! response ) return;
                if ( response.error && response.product_url ) {
                    window.location = response.product_url;
                    return;
                }
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
            });
        });

        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            if ($button && $button.hasClass('cs_ajax_add_to_cart')) {
                $('#cross-sell-cart-container').css('transition', 'all 0.5s ease').css('opacity', '0').slideUp(500, function() {
                    $(this).remove();
                });
            }
        });
    });
    </script>
    <?php
}
add_action( 'woocommerce_after_cart_table', 'custom_element_inside_cart_left_column' );

// -----------------------------------------------------------------------------
// 5. STATYSTYKI ZAMÓWIEŃ (AJAX I ZAPIS DANYCH)
// -----------------------------------------------------------------------------
add_action( 'wc_ajax_cs_log_event', 'cs_log_analytic_event' );
function cs_log_analytic_event() {
    $rule_uid = isset( $_POST['rule_uid'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_uid'] ) ) : '';
    $type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

    if ( ! empty($rule_uid) && in_array( $type, array( 'impression', 'click' ) ) ) {
        $date = current_time( 'Y-m-d' );
        $daily_stats = get_option( 'wc_cs_daily_stats', array() );

        if ( ! isset( $daily_stats[$date] ) ) $daily_stats[$date] = array();
        if ( ! isset( $daily_stats[$date][$rule_uid] ) ) $daily_stats[$date][$rule_uid] = array( 'i' => 0, 'c' => 0, 's' => 0, 'r' => 0 );

        if ( $type === 'impression' ) $daily_stats[$date][$rule_uid]['i']++;
        if ( $type === 'click' ) $daily_stats[$date][$rule_uid]['c']++;

        update_option( 'wc_cs_daily_stats', $daily_stats );
        wp_send_json_success();
    }
    wp_send_json_error();
}

// ZAPIS NAZWY WŁASNEJ
add_action( 'wp_ajax_cs_save_analytic_name', 'cs_save_analytic_name' );
function cs_save_analytic_name() {
    if ( isset($_POST['rule_uid']) && isset($_POST['analytic_name']) ) {
        $uid = sanitize_text_field(wp_unslash($_POST['rule_uid']));
        $name = sanitize_text_field(wp_unslash($_POST['analytic_name']));
        $rules = get_option('wc_cs_rules', array());
        
        foreach ( $rules as &$r ) {
            if ( isset($r['uid']) && $r['uid'] === $uid ) {
                $r['analytic_name'] = $name;
                update_option('wc_cs_rules', $rules);
                wp_send_json_success();
            }
        }
    }
    wp_send_json_error();
}

// USUNIĘCIE STATYSTYK REGUŁY
add_action( 'wp_ajax_cs_delete_analytic_data', 'cs_delete_analytic_data' );
function cs_delete_analytic_data() {
    $uid = isset($_POST['rule_uid']) ? sanitize_text_field(wp_unslash($_POST['rule_uid'])) : '';
    if ( $uid ) {
        $daily_stats = get_option('wc_cs_daily_stats', array());
        foreach ( $daily_stats as $date => &$stats ) {
            if ( isset($stats[$uid]) ) unset($stats[$uid]);
        }
        update_option('wc_cs_daily_stats', $daily_stats);
        
        $rules = get_option('wc_cs_rules', array());
        foreach ( $rules as &$r ) {
            if ( isset($r['uid']) && $r['uid'] === $uid ) {
                $r['sales_count'] = 0;
                $r['sales_revenue'] = 0;
            }
        }
        update_option('wc_cs_rules', $rules);
        
        wp_send_json_success();
    }
    wp_send_json_error();
}

add_filter( 'woocommerce_add_cart_item_data', 'cs_mark_cart_item', 10, 2 );
function cs_mark_cart_item( $cart_item_data, $product_id ) {
    if ( isset( $_POST['is_cs'] ) ) {
        $cart_item_data['is_cross_sell_origin'] = sanitize_text_field( $_POST['is_cs'] );
    } elseif ( isset( $_GET['is_cs'] ) ) {
        $cart_item_data['is_cross_sell_origin'] = sanitize_text_field( $_GET['is_cs'] );
    }
    return $cart_item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'cs_add_meta_to_order_item', 10, 4 );
function cs_add_meta_to_order_item( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['is_cross_sell_origin'] ) ) $item->add_meta_data( '_cs_rule_index', $values['is_cross_sell_origin'] );
}

add_action( 'woocommerce_order_status_processing', 'cs_increment_stats' );
function cs_increment_stats( $order_id ) {
    $order = wc_get_order( $order_id );
    $rules = get_option( 'wc_cs_rules', array() );
    $daily_stats = get_option( 'wc_cs_daily_stats', array() );
    $date = current_time( 'Y-m-d' );
    $updated = false;

    foreach ( $order->get_items() as $item ) {
        $rule_uid = $item->get_meta( '_cs_rule_index' ); 
        if ( $rule_uid !== '' ) {
            $qty = $item->get_quantity();
            $rev = $item->get_total() + $item->get_total_tax();

            // SZUKANIE REGUŁY W CELU POPRAWNEGO MAPOWANIA UID
            $found_idx = null;
            foreach ($rules as $idx => $r) {
                if ( (isset($r['uid']) && $r['uid'] === $rule_uid) || (strval($idx) === strval($rule_uid)) ) {
                    $found_idx = $idx;
                    break;
                }
            }

            $uid_to_log = $rule_uid;
            if ( $found_idx !== null ) {
                $rules[$found_idx]['sales_count'] = ( isset($rules[$found_idx]['sales_count']) ? $rules[$found_idx]['sales_count'] : 0 ) + $qty;
                $rules[$found_idx]['sales_revenue'] = ( isset($rules[$found_idx]['sales_revenue']) ? $rules[$found_idx]['sales_revenue'] : 0 ) + $rev;
                if ( isset($rules[$found_idx]['uid']) ) {
                    $uid_to_log = $rules[$found_idx]['uid']; // GWARANCJA LOGOWANIA NA NOWE UID
                }
            }
            
            if ( ! isset( $daily_stats[$date] ) ) $daily_stats[$date] = array();
            if ( ! isset( $daily_stats[$date][$uid_to_log] ) ) $daily_stats[$date][$uid_to_log] = array( 'i' => 0, 'c' => 0, 's' => 0, 'r' => 0 );
            
            $daily_stats[$date][$uid_to_log]['s'] += $qty;
            $daily_stats[$date][$uid_to_log]['r'] += $rev;

            $updated = true;
        }
    }
    if ( $updated ) {
        update_option( 'wc_cs_rules', $rules );
        update_option( 'wc_cs_daily_stats', $daily_stats );
    }
}

// -----------------------------------------------------------------------------
// 6. KOKPIT I ZARZĄDZANIE REGUŁAMI
// -----------------------------------------------------------------------------
add_action('admin_menu', 'wc_cross_sell_register_menu');
function wc_cross_sell_register_menu() {
    add_submenu_page('edit.php?post_type=product', 'Cross-sell w koszyku', 'Cross-sell w koszyku', 'manage_options', 'cross-sell-koszyk', 'wc_cross_sell_settings_render');
}

function wc_cross_sell_settings_render() {
    // ZMIANY: DODANIE ZAPISU USTAWIEN GLOBALNYCH I USTAWIEN ODZNAKI
    if (isset($_POST['wc_cs_save'])) {
        if (isset($_POST['wc_cs_global_header'])) update_option('wc_cs_global_header', sanitize_text_field($_POST['wc_cs_global_header']));
        if (isset($_POST['wc_cs_global_sub_header'])) update_option('wc_cs_global_sub_header', sanitize_text_field($_POST['wc_cs_global_sub_header']));
        if (isset($_POST['wc_cs_layout_mode'])) update_option('wc_cs_layout_mode', sanitize_text_field($_POST['wc_cs_layout_mode']));
        
        $show_savings_badge = isset($_POST['wc_cs_show_savings_badge']) ? 'yes' : 'no';
        update_option('wc_cs_show_savings_badge', $show_savings_badge);
        if (isset($_POST['wc_cs_savings_badge_type'])) update_option('wc_cs_savings_badge_type', sanitize_text_field($_POST['wc_cs_savings_badge_type']));
        if (isset($_POST['wc_cs_savings_badge_text'])) update_option('wc_cs_savings_badge_text', sanitize_text_field($_POST['wc_cs_savings_badge_text']));
        
        // AKTUALIZACJA: ZAPIS SUFIKSU DLA GRATISÓW ZAKŁADKA USTAWIENIA GLOBLANE
        if (isset($_POST['wc_cs_gift_suffix'])) update_option('wc_cs_gift_suffix', sanitize_text_field($_POST['wc_cs_gift_suffix']));

        $rules = array();
        if (!empty($_POST['wc_cs_rules']) && is_array($_POST['wc_cs_rules'])) {
            foreach ($_POST['wc_cs_rules'] as $rule) {
                if (empty($rule['product_id'])) continue;
                
                $generate = isset($rule['generate_variants']) ? $rule['generate_variants'] : 'no';
                $sync_attr = isset($rule['sync_attr']) ? sanitize_text_field($rule['sync_attr']) : '';
                $rule_uid = isset($rule['uid']) && !empty($rule['uid']) ? sanitize_text_field($rule['uid']) : uniqid('cs_');

                if ($generate === 'yes' && !empty($sync_attr)) {
                    $offered_parent = wc_get_product(sanitize_text_field($rule['product_id']));
                    if ($offered_parent && $offered_parent->is_type('variable')) {
                        $target_ids_input = isset($rule['target_ids']) ? $rule['target_ids'] : array();
                        foreach ($offered_parent->get_visible_children() as $variation_id) {
                            $variation = wc_get_product($variation_id);
                            if (!$variation) continue;
                            $var_attrs = $variation->get_attributes();
                            $var_attr_slug = isset($var_attrs[$sync_attr]) ? $var_attrs[$sync_attr] : '';
                            if (empty($var_attr_slug)) continue;
                            
                            $new_target_ids = array();
                            foreach ($target_ids_input as $tid) {
                                $t_prod = wc_get_product($tid);
                                if (!$t_prod) continue;
                                if ($t_prod->is_type('variable')) {
                                    foreach ($t_prod->get_visible_children() as $t_var_id) {
                                        $t_var = wc_get_product($t_var_id);
                                        if ($t_var) {
                                            $t_attrs = $t_var->get_attributes();
                                            if (isset($t_attrs[$sync_attr]) && strcasecmp($t_attrs[$sync_attr], $var_attr_slug) === 0) {
                                                $new_target_ids[] = $t_var_id; 
                                            }
                                        }
                                    }
                                } elseif ($t_prod->is_type('variation')) {
                                    $t_attrs = $t_prod->get_attributes();
                                    if (isset($t_attrs[$sync_attr]) && strcasecmp($t_attrs[$sync_attr], $var_attr_slug) === 0) {
                                        $new_target_ids[] = $tid;
                                    }
                                }
                            }
                            if (empty($new_target_ids)) continue; 

                            $v_img_id = $variation->get_image_id() ? $variation->get_image_id() : $offered_parent->get_image_id();
                            $v_img_url = $v_img_id ? wp_get_attachment_image_url($v_img_id, 'woocommerce_thumbnail') : '';
                            $v_reg_price = $variation->get_regular_price();
                            if (empty($v_reg_price)) $v_reg_price = $variation->get_price();
                            $v_sale_price = $variation->get_sale_price();

                            $rules[] = array(
                                'uid'          => uniqid('cs_'), 
                                'product_id'   => $variation_id,
                                'is_global'    => isset($rule['is_global']) ? 'yes' : 'no',
                                'is_active'    => isset($rule['is_active']) ? $rule['is_active'] : 'yes',
                                'priority'     => isset($rule['priority']) ? intval($rule['priority']) : 0,
                                'sync_color'   => 'no',
                                'sync_attr'    => '',
                                'target_ids'   => array_map('sanitize_text_field', $new_target_ids),
                                'header_sub'   => sanitize_text_field($rule['header_sub']),
                                'title'        => sanitize_text_field($variation->get_name()),
                                'attr_1'       => sanitize_text_field($rule['attr_1']),
                                'attr_2'       => sanitize_text_field($rule['attr_2']),
                                'price_reg'    => $v_reg_price,
                                'price_promo'  => $v_sale_price,
                                'custom_image' => esc_url_raw($v_img_url),
                                'sales_count'  => 0,
                                'sales_revenue'=> 0,
                                'analytic_name'=> isset($rule['analytic_name']) ? sanitize_text_field($rule['analytic_name']) : '',
                            );
                        }
                    }
                    continue; 
                }

                $rules[] = array(
                    'uid'          => $rule_uid,
                    'product_id'   => sanitize_text_field($rule['product_id']),
                    'is_global'    => isset($rule['is_global']) ? 'yes' : 'no',
                    'is_active'    => isset($rule['is_active']) ? $rule['is_active'] : 'yes',
                    'priority'     => isset($rule['priority']) ? intval($rule['priority']) : 0,
                    'sync_color'   => isset($rule['sync_color']) ? 'yes' : 'no',
                    'sync_attr'    => sanitize_text_field(isset($rule['sync_attr']) ? $rule['sync_attr'] : ''),
                    'target_ids'   => isset($rule['target_ids']) ? array_map('sanitize_text_field', $rule['target_ids']) : array(),
                    'header_sub'   => sanitize_text_field($rule['header_sub']),
                    'title'        => sanitize_text_field($rule['title']),
                    'attr_1'       => sanitize_text_field($rule['attr_1']),
                    'attr_2'       => sanitize_text_field($rule['attr_2']),
                    'price_reg'    => sanitize_text_field($rule['price_reg']),
                    'price_promo'  => sanitize_text_field($rule['price_promo']),
                    'custom_image' => esc_url_raw($rule['custom_image']),
                    'sales_count'  => isset($rule['sales_count']) ? intval($rule['sales_count']) : 0,
                    'sales_revenue'=> isset($rule['sales_revenue']) ? floatval($rule['sales_revenue']) : 0,
                    'analytic_name'=> isset($rule['analytic_name']) ? sanitize_text_field($rule['analytic_name']) : '',
                );
            }
        }
        update_option('wc_cs_rules', $rules);

        // ZAPIS GRATISÓW
        $gratis_rules = array();
        if (!empty($_POST['wc_cs_gratis']) && is_array($_POST['wc_cs_gratis'])) {
            foreach ($_POST['wc_cs_gratis'] as $g_rule) {
                if (empty($g_rule['gift_id'])) continue;
                $gratis_rules[] = array(
                    'active'       => (isset($g_rule['active']) && $g_rule['active'] === 'yes') ? 'yes' : 'no',
                    'admin_only'   => (isset($g_rule['admin_only']) && $g_rule['admin_only'] === 'yes') ? 'yes' : 'no',
                    'gift_id'      => intval($g_rule['gift_id']),
                    'trigger_type' => sanitize_text_field($g_rule['trigger_type']),
                    'threshold'    => sanitize_text_field($g_rule['threshold']),
                    'target_ids'   => isset($g_rule['target_ids']) ? array_map('intval', $g_rule['target_ids']) : array(),
                    'sync_qty'     => isset($g_rule['sync_qty']) ? 'yes' : 'no',
                );
            }
        }
        update_option('wc_cs_gratis_rules', $gratis_rules);

        // ZAPIS ZESTAWÓW PROMOCYJNYCH
        $promo_sets = array();
        if (!empty($_POST['wc_cs_promo_sets']) && is_array($_POST['wc_cs_promo_sets'])) {
            foreach ($_POST['wc_cs_promo_sets'] as $p_set) {
                if (empty($p_set['target_id'])) continue;
                $promo_sets[] = array(
                    'active'       => (isset($p_set['active']) && $p_set['active'] === 'yes') ? 'yes' : 'no',
                    'target_id'    => intval($p_set['target_id']), // Produkt docelowy dodawany do koszyka
                    'target_ids'   => isset($p_set['target_ids']) ? array_map('intval', $p_set['target_ids']) : array(), // Na jakich produktach wyświetlać
                    'is_global'    => (isset($p_set['is_global']) && $p_set['is_global'] === 'yes') ? 'yes' : 'no',
                    'header_label' => sanitize_text_field($p_set['header_label']),
                    'badge_type'   => isset($p_set['badge_type']) ? sanitize_text_field($p_set['badge_type']) : '',
                    'badge_custom' => isset($p_set['badge_custom']) ? sanitize_text_field($p_set['badge_custom']) : '',
                    'title'        => sanitize_text_field($p_set['title']),
                    'image'        => esc_url_raw($p_set['image']),
                    'items'        => sanitize_textarea_field($p_set['items']),
                    'price_reg'    => sanitize_text_field($p_set['price_reg']),
                    'price_promo'  => sanitize_text_field($p_set['price_promo']),
                    'btn_label'    => sanitize_text_field($p_set['btn_label']),
                );
            }
        }
        update_option('wc_cs_promo_sets', $promo_sets);

        echo '<div class="updated"><p>Zapisano ustawienia na wszystkich zakładkach!</p></div>';
    }

    $global_header = get_option('wc_cs_global_header', '');
    $global_sub_header = get_option('wc_cs_global_sub_header', '');
    $layout_mode = get_option('wc_cs_layout_mode', 'carousel');
    
    // AKTUALIZACJA: DYNAMICZNY SUFIKS DLA PREZENTÓW Z OPCJI GLOBLANYCH W PANELU 
    $gift_suffix = get_option('wc_cs_gift_suffix', ' (Prezent)');
    
    $show_savings_badge = get_option('wc_cs_show_savings_badge', 'no');
    $savings_badge_type = get_option('wc_cs_savings_badge_type', 'amount');
    $savings_badge_text = get_option('wc_cs_savings_badge_text', '');

    $saved_rules = get_option('wc_cs_rules', array());
    if (empty($saved_rules)) $saved_rules[] = array( 'uid' => uniqid('cs_'), 'is_active' => 'yes', 'priority' => 0 ); 
    
    $saved_gratis = get_option('wc_cs_gratis_rules', array());
    $saved_promo_sets = get_option('wc_cs_promo_sets', array());
    $daily_stats = get_option('wc_cs_daily_stats', array());
    
    $rule_names = array();
    $rule_triggers = array();
    
    foreach ($saved_rules as $idx => $r) {
        $uid = isset($r['uid']) ? $r['uid'] : $idx;
        $base_name = 'Reguła ' . $idx;
        if (!empty($r['title'])) {
            $base_name = $r['title'];
        } elseif (!empty($r['product_id'])) {
            $p = wc_get_product($r['product_id']);
            $base_name = $p ? $p->get_name() : 'Reguła ' . $idx;
        }
        
        // ZAPISANIE INDEKSU FORMULARZA W TABLICY
        $rule_names[$uid] = array(
            'base' => $base_name,
            'analytic' => isset($r['analytic_name']) ? $r['analytic_name'] : '',
            'form_index' => $idx
        );
        
        if (isset($r['is_global']) && $r['is_global'] === 'yes') {
            $rule_triggers[$uid] = 'GLOBAL';
        } else {
            $t_names = array();
            $t_ids = isset($r['target_ids']) ? (array)$r['target_ids'] : array();
            foreach (array_slice($t_ids, 0, 5) as $tid) {
                $p = wc_get_product($tid);
                if ($p) $t_names[] = $p->get_name();
            }
            $rule_triggers[$uid] = array('names' => $t_names, 'total' => count($t_ids));
        }
    }
    
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    ?>
    <style>
        /* AKTUALIZACJA: GLOBALNY RESET ARCHITEKTURY KOKPITU DO STANDARDU SaaS */
        .wrap { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
        .wrap h1 { font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 24px; }
        
        /* AKTUALIZACJA: NOWOCZESNY DESIGN ZAKŁADEK NAWIGACYJNYCH */
        .cs-nav-tabs.nav-tab-wrapper { border-bottom: 1px solid #e2e8f0; padding-top: 0; padding-bottom: 0; margin-bottom: 24px; gap: 8px; display: flex; }
        .cs-nav-tabs .nav-tab { background: transparent; border: none; border-bottom: 2px solid transparent; color: #64748b; font-weight: 500; font-size: 14px; padding: 12px 16px; margin: 0; transition: all 0.2s ease; cursor: pointer; border-radius: 0; }
        .cs-nav-tabs .nav-tab:hover { color: #0f172a; background: #f8fafc; border-bottom-color: #cbd5e1; }
        .cs-nav-tabs .nav-tab-active, .cs-nav-tabs .nav-tab-active:hover { background: transparent; border-bottom: 2px solid #C68E7A; color: #C68E7A; font-weight: 600; box-shadow: none; }

        /* AKTUALIZACJA: KARTY POSTBOX W WYGLĄDZIE SaaS CLEAN UI */
        .postbox { background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 12px !important; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important; margin-bottom: 20px !important; overflow: hidden; transition: box-shadow 0.2s ease; }
        .postbox:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05) !important; }
        .postbox-header { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; padding: 14px 20px !important; display: flex; align-items: center; justify-content: space-between; cursor: pointer; flex-wrap: wrap; }
        .postbox-header h2 { color: #0f172a !important; font-size: 15px !important; font-weight: 600 !important; margin: 0; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; flex-grow: 1; }
        .cs-header-stats { background: #f1f5f9 !important; color: #475569 !important; font-weight: 500; border-radius: 6px !important; padding: 4px 10px !important; font-size: 12px !important; border: 1px solid #e2e8f0; margin-left: auto; }
        
        /* AKTUALIZACJA: ERGONOMICZNE PRZYCISKI AKCJI W NAGŁÓWKU */
        .handle-actions { display: flex; align-items: center; gap: 8px; }
        .handle-actions .button-link { background: #fff !important; border: 1px solid #e2e8f0 !important; border-radius: 6px !important; padding: 6px 12px !important; font-size: 12px !important; font-weight: 500 !important; text-decoration: none !important; transition: all 0.2s ease !important; display: inline-flex; align-items: center; cursor: pointer; }
        .wc-cs-rule-card .postbox-header .toggle-indicator { margin-left: 5px; }
        .wc-cs-clone-rule { color: #2271b1 !important; }
        .wc-cs-clone-rule:hover { background: #f0fdf4 !important; border-color: #bbf7d0 !important; color: #166534 !important; }
        .wc-cs-remove-rule, .wc-cs-remove-gratis { color: #df2c2c !important; }
        .wc-cs-remove-rule:hover, .wc-cs-remove-gratis:hover { background: #fef2f2 !important; border-color: #fca5a5 !important; color: #991b1b !important; }
        
        /* AKTUALIZACJA: CSS TOGGLE SWITCHES ZAMIAST CHECKBOXÓW WRAZ Z BEZPIECZNYM UKRYCIEM NATYWNYCH BOKSÓW */
        .cs-switch-container { display: inline-flex; align-items: center; cursor: pointer; position: relative; }
        .cs-switch-container input[type="checkbox"] { position: absolute !important; opacity: 0 !important; width: 1px !important; height: 1px !important; margin: -1px !important; overflow: hidden !important; clip: rect(0, 0, 0, 0) !important; appearance: none !important; -webkit-appearance: none !important; border: 0 !important; padding: 0 !important; }
        .cs-switch-slider { position: relative; width: 36px; height: 20px; background-color: #cbd5e1; border-radius: 20px; transition: .3s ease; flex-shrink: 0; margin-right: 10px; border: 1px solid #cbd5e1; }
        .cs-switch-slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 2px; bottom: 2px; background-color: white; border-radius: 50%; transition: .3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
        .cs-switch-container input[type="checkbox"]:checked + .cs-switch-slider { background-color: #C68E7A; border-color: #C68E7A; }
        .cs-switch-container input[type="checkbox"]:checked + .cs-switch-slider:before { transform: translateX(16px); }
        .cs-switch-text { font-weight: 600; font-size: 13px; color: #334155; }
        
        /* AKTUALIZACJA: NOWOCZESNE POLA FORMULARZY I ZEWNĘTRZNY STAN SKUPIENIA (BEZ USZKADZANIA OUTLINE'U) */
        .wrap input[type="text"], .wrap input[type="number"], .wrap select, .wrap input[type="date"] { background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; min-height: 36px; color: #334155; font-size: 13px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02); transition: all 0.15s ease-in-out; width: 100%; max-width: 100%; box-sizing: border-box; }
        .wrap input[type="text"]:focus, .wrap input[type="number"]:focus, .wrap select:focus, .wrap input[type="date"]:focus { border-color: #C68E7A !important; box-shadow: 0 0 0 3px rgba(172, 0, 0, 0.15) !important; outline: none !important; outline-offset: 0 !important; color: #0f172a; }
        .wrap .description { color: #64748b; font-size: 12px; margin-top: 6px; line-height: 1.4; }
        
        /* AKTUALIZACJA: LIKWIDACJA SUROWYCH STRUKTUR TABELI FORM-TABLE NA RZECZ SIATKI CSS GRID */
        .postbox .inside { padding: 20px !important; margin: 0 !important; }
        .form-table { margin: 0 !important; width: 100%; display: block; }
        .form-table tbody { display: block; width: 100%; }
        .form-table tr { display: grid; grid-template-columns: 220px 1fr; gap: 16px; margin-bottom: 18px; border-bottom: none; width: 100%; align-items: start; }
        .form-table tr:last-child { margin-bottom: 0; }
        .form-table th { width: auto !important; padding: 8px 0 !important; font-weight: 600 !important; color: #334155 !important; font-size: 13px !important; text-align: left; }
        .form-table td { width: auto !important; padding: 0 !important; margin: 0; display: block; }
        
        .generate-attr-container { margin-top: 10px !important; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: inline-block; width: 100%; box-sizing: border-box; }
        
        /* AKTUALIZACJA: FORMATOWANIE WIELOKROTNYCH INPUTÓW W JEDNEJ LINII (SEKCJA TREŚCI I CENY - STACKED LABELS) */
        .cs-inputs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .cs-input-group { display: flex; flex-direction: column; gap: 6px; }
        .cs-input-group.full-width { grid-column: span 2; }
        .cs-input-group label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .cs-input-group input { margin-bottom: 0 !important; width: 100% !important; }
        
        /* AKTUALIZACJA: INTERFEJS PRZESYŁANIA GRAPHICS/ZDJĘĆ PREVIEW */
        .wc_cs_image_preview img { border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .wc_cs_upload_image_btn { background: #fff !important; border: 1px solid #cbd5e1 !important; border-radius: 6px !important; padding: 6px 14px !important; font-size: 13px !important; color: #334155 !important; font-weight: 500 !important; cursor: pointer !important; transition: all 0.2s !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; margin-top: 10px !important; }
        .wc_cs_upload_image_btn:hover { background: #f8fafc !important; border-color: #cbd5e1 !important; color: #0f172a !important; }

        /* KPI DASHBOARD CARDS SaaS STYLING */
        .cs-dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 20px; margin-bottom: 24px; }
        .cs-kpi-card { background: #ffffff; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) ; }
        .cs-kpi-title { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 8px; border-bottom: 1px dotted #e2e8f0; padding-bottom: 4px; }
        .cs-kpi-value { font-size: 26px; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .cs-filter-bar { padding: 16px 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px; }
        
        /* TABLE ANALYSIS DESIGN */
        .cs-table-wrapper { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); overflow: hidden; }
        .cs-table { width: 100%; border-collapse: collapse; margin: 0; }
        .cs-table th, .cs-table td { padding: 14px 20px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #334155; }
        .cs-table th { background: #f8fafc; font-weight: 600; color: #0f172a; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .cs-table tr:last-child td { border-bottom: none; }
        .cs-table tr:hover td { background-color: #f8fafc; }
        
        /* BUTTONS STYLING */
        .button-primary.button-large { background-color: #000000 !important; border: none !important; border-radius: 6px !important; padding: 0 24px !important; min-height: 40px !important; line-height: 40px !important; font-weight: 600 !important; font-size: 13px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; cursor: pointer !important; transition: all 0.2s ease !important; }
        .button-primary.button-large:hover { background-color: #C68E7A !important; }
        #wc-cs-add-rule, #wc-cs-add-gratis { background: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #334155 !important; border-radius: 6px !important; padding: 0 16px !important; min-height: 36px !important; line-height: 34px !important; font-weight: 500 !important; font-size: 13px !important; cursor: pointer !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; transition: all 0.2s ease !important; }
        #wc-cs-add-rule:hover, #wc-cs-add-gratis:hover { background: #f8fafc !important; border-color: #cbd5e1 !important; color: #0f172a !important; }

        /* ANALYTICS STATUS BADGES IMPROVED */
        .cs-badge { padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; display: inline-block; }
        .cs-badge-good { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .cs-badge-warn { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .cs-badge-bad { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
        .cs-badge-neutral { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
        .cs-badge-info { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        
        /* TOOLTIPS CSS FIXED */
        [data-tooltip] { position: relative; cursor: help; }
        [data-tooltip]::after { content: attr(data-tooltip); position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: #0f172a; color: #ffffff; padding: 8px 12px; border-radius: 6px; font-size: 11px; font-weight: 400; white-space: normal; max-width: 240px; width: max-content; text-align: center; opacity: 0; visibility: hidden; transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1); z-index: 99999; pointer-events: none; margin-bottom: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); line-height: 1.4; text-transform: none !important; letter-spacing: normal !important; }
        [data-tooltip]:not(.dashicons)::before { content: ""; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); border: 5px solid transparent; border-top-color: #0f172a; opacity: 0; visibility: hidden; transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1); z-index: 99999; pointer-events: none; margin-bottom: -2px; }
        [data-tooltip]:hover::after, [data-tooltip]:hover::before { opacity: 1; visibility: visible; }
        
        /* AKTUALIZACJA: KLUCZOWA RESPONSYWNOŚĆ I NAPRAWA HEADERÓW DLA URZĄDZEŃ MOBILNYCH */
        @media (max-width: 768px) {
            .postbox-header { align-items: flex-start !important; }
            .postbox-header h2 { width: 100%; }
            .cs-header-stats { width: 100%; margin-left: 0 !important; margin-top: 10px; display: block; text-align: center; box-sizing: border-box; }
            .handle-actions { width: 100%; justify-content: flex-end; margin-top: 12px; }
            
            .form-table tr { grid-template-columns: 1fr; gap: 8px; margin-bottom: 24px; }
            .cs-inputs-grid { grid-template-columns: 1fr; }
            .cs-input-group.full-width, .cs-input-group.half-width { grid-column: span 1; }
            
            .cs-filter-bar { flex-direction: column; align-items: flex-start; gap: 12px; }
            .cs-table-wrapper { border: none; box-shadow: none; background: transparent; }
            .cs-table, .cs-table thead, .cs-table tbody, .cs-table th, .cs-table td, .cs-table tr { display: block; width: 100%; box-sizing: border-box; }
            .cs-table thead { display: none; }
            .cs-table tr { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
            .cs-table tr:hover td { background-color: transparent; }
            .cs-table td { display: flex; justify-content: space-between; align-items: center; padding: 10px 0 !important; border-bottom: 1px solid #f1f5f9; text-align: right; }
            .cs-table td:last-child { border-bottom: none; }
            .cs-table td::before { content: attr(data-label); font-weight: 600; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: left; margin-right: 16px; letter-spacing: 0.5px; }
            .cs-rule-name-container { width: auto; justify-content: flex-end; }
        }
    </style>

    <div class="wrap">
        <h1>Ustawienia Cross-sell & Gratis</h1>

        <h2 class="nav-tab-wrapper cs-nav-tabs">
            <a href="#tab-rules" class="nav-tab nav-tab-active">Reguły Cross-sell</a>
            <a href="#tab-analysis" class="nav-tab">Analiza</a>
            <a href="#tab-gratis" class="nav-tab">Gratis</a>
            <a href="#tab-promo-sets" class="nav-tab">Zestawy promocyjne</a>
            <a href="#tab-settings" class="nav-tab">Ustawienia</a>
        </h2>

        <form method="post" id="wc-cs-form">
            <div id="tab-settings" class="cs-tab-content" style="display: none; margin-top: 15px;">
                <div class="postbox" style="max-width: 900px;">
                    <div class="postbox-header">
                        <h2>Ustawienia globalne</h2>
                    </div>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th style="width: 30%;">Główny nagłówek nad sekcją:</th>
                                <td>
                                    <input type="text" name="wc_cs_global_header" value="<?php echo esc_attr($global_header); ?>" class="regular-text" placeholder="np. Mogą Cię również zainteresować...">
                                    <p class="description">Ten tekst będzie wyświetlał się globalnie nad wszystkimi ofertami cross-sell.</p>
                                </td>
                            </tr>
                            <tr class="cs-global-sub-header-row">
                                <th>Globalny podtytuł (czerwony) dla trybu siatki:</th>
                                <td>
                                    <input type="text" name="wc_cs_global_sub_header" value="<?php echo esc_attr($global_sub_header); ?>" class="regular-text" placeholder="np. Ostatnia szansa na zakup!">
                                    <p class="description">Ten tekst wyświetli się pod głównym nagłówkiem. Zastępuje on indywidualne podtytuły z reguł, gdy włączony jest układ siatki.</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Układ wyświetlania:</th>
                                <td>
                                    <select name="wc_cs_layout_mode" style="max-width: 300px; width: 100%;">
                                        <option value="carousel" <?php selected($layout_mode, 'carousel'); ?>>Karuzela (przewijana w poziomie)</option>
                                        <option value="grid" <?php selected($layout_mode, 'grid'); ?>>Siatka (1-3 kafelków obok siebie)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Opcje Gratisu w Koszyku:</th>
                                <td>
                                    <div class="cs-input-group" style="max-width: 300px; margin-bottom: 10px;">
                                        <label>Sufiks (tekst) dodawany do darmowego produktu:</label>
                                        <input type="text" name="wc_cs_gift_suffix" value="<?php echo esc_attr($gift_suffix); ?>" class="regular-text" placeholder="np. (Prezent)">
                                    </div>
                                    <p class="description">Domyślnie system dodaje np. " (Prezent)" do nazwy produktu, aby klient wiedział, że to gratis. Możesz dostosować to do języka Twojego sklepu.</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Badge oszczędności:</th>
                                <td>
                                    <label class="cs-switch-container">
                                        <input type="hidden" name="wc_cs_show_savings_badge" value="no">
                                        <input type="checkbox" name="wc_cs_show_savings_badge" value="yes" <?php checked($show_savings_badge, 'yes'); ?>> 
                                        <span class="cs-switch-slider"></span>
                                        <span class="cs-switch-text">Wyświetlaj etykietę oszczędności w prawym górnym rogu produktu</span>
                                    </label>
                                    <div style="margin-top: 15px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <select name="wc_cs_savings_badge_type" style="width: auto; min-width: 150px;">
                                            <option value="amount" <?php selected($savings_badge_type, 'amount'); ?>>Kwota (np. -20 zł)</option>
                                            <option value="percent" <?php selected($savings_badge_type, 'percent'); ?>>Procent (np. -20%)</option>
                                        </select>
                                        <input type="text" name="wc_cs_savings_badge_text" value="<?php echo esc_attr($savings_badge_text); ?>" placeholder="Etykieta (np. Oszczędzasz)" class="regular-text" style="width: 200px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <input type="submit" name="wc_cs_save" class="button button-primary button-large" value="Zapisz wszystkie ustawienia">
            </div>

            <div id="tab-promo-sets" class="cs-tab-content" style="display: none;">
                <div style="margin-bottom: 20px; margin-top: 15px;">
                    <button type="button" class="button" id="wc-cs-add-promo-set">+ Dodaj nowy zestaw promocyjny</button>
                    <p class="description">Zestawy będą wyświetlane na stronach produktowych jako opcja domyślna, o ile CPT promocje nie wymusi innego zestawu.</p>
                </div>
                <div id="promo-sets-wrapper">
                    <?php foreach ($saved_promo_sets as $index => $p_set) : 
                        $target_id = isset($p_set['target_id']) ? $p_set['target_id'] : '';
                        $is_active = isset($p_set['active']) ? $p_set['active'] : 'yes';
                        $title_display = 'Nowy zestaw';
                        if (!empty($p_set['title'])) {
                            $title_display = $p_set['title'];
                        } elseif ($target_id) {
                            $prod_obj = wc_get_product($target_id);
                            if ($prod_obj) $title_display = $prod_obj->get_name(); 
                        }
                        $inactive_class = $is_active === 'no' ? 'is-inactive' : '';
                    ?>
                    <div class="wc-cs-promo-set-card postbox closed <?php echo $inactive_class; ?>" data-index="<?php echo $index; ?>" style="max-width: 900px;">
                        <div class="postbox-header">
                            <h2>
                                <span class="rule-title-text">
                                    <strong><?php echo esc_html($title_display); ?></strong>
                                </span>
                                <?php if ($is_active === 'no'): ?>
                                    <span style="color: #d63638; font-weight: normal; font-size: 12px; margin-left: 5px;">(Wyłączony)</span>
                                <?php endif; ?>
                            </h2>
                            <div class="handle-actions">
                                <button type="button" class="button-link wc-cs-remove-promo-set">Usuń</button>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th style="width: 25%;">Status:</th>
                                    <td>
                                        <label class="cs-switch-container">
                                            <input type="hidden" name="wc_cs_promo_sets[<?php echo $index; ?>][active]" value="no">
                                            <input type="checkbox" name="wc_cs_promo_sets[<?php echo $index; ?>][active]" class="wc-cs-active-toggle" value="yes" <?php checked($is_active, 'yes'); ?>> 
                                            <span class="cs-switch-slider"></span>
                                            <span class="cs-switch-text">Zestaw włączony</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gdzie wyświetlać zestaw:</th>
                                    <td>
                                        <label class="cs-switch-container" style="margin-bottom: 15px;">
                                            <input type="hidden" name="wc_cs_promo_sets[<?php echo $index; ?>][is_global]" value="no">
                                            <input type="checkbox" name="wc_cs_promo_sets[<?php echo $index; ?>][is_global]" value="yes" <?php checked(isset($p_set['is_global']) ? $p_set['is_global'] : 'no', 'yes'); ?>> 
                                            <span class="cs-switch-slider"></span>
                                            <span class="cs-switch-text">Globalny (pokaż na każdej stronie produktu)</span>
                                        </label>
                                        <select class="wc-product-search" name="wc_cs_promo_sets[<?php echo $index; ?>][target_ids][]" multiple="multiple" style="width: 100%;" data-placeholder="Produkty, na których wyświetli się zestaw..." data-action="woocommerce_json_search_products_and_variations">
                                            <?php if (!empty($p_set['target_ids'])) {
                                                foreach ($p_set['target_ids'] as $tid) {
                                                    $t_prod = wc_get_product($tid);
                                                    $t_name = $t_prod ? $t_prod->get_name() : $tid;
                                                    echo '<option value="'.esc_attr($tid).'" selected>'.esc_html($t_name).'</option>';
                                                }
                                            } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Produkt do dodania (zestaw):</th>
                                    <td>
                                        <select class="wc-product-search wc-cs-promo-set-target-select" name="wc_cs_promo_sets[<?php echo $index; ?>][target_id]" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations">
                                            <?php if ($target_id) : ?>
                                                <option value="<?php echo esc_attr($target_id); ?>" selected><?php echo esc_html($title_display); ?></option>
                                            <?php endif; ?>
                                        </select>
                                        <p class="description">Ten produkt zostanie dodany do koszyka po kliknięciu w przycisk.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Treści i Ceny:</th>
                                    <td>
                                        <div class="cs-inputs-grid">
                                            <div class="cs-input-group full-width">
                                                <label>Nagłówek zestawu (np. Zestaw Premium)</label>
                                                <input type="text" name="wc_cs_promo_sets[<?php echo $index; ?>][header_label]" value="<?php echo esc_attr(isset($p_set['header_label']) ? $p_set['header_label'] : ''); ?>" placeholder="Wpisz nagłówek...">
                                            </div>
                                            <div class="cs-input-group full-width">
                                                <label>Typ odznaki (Pill na zestawie)</label>
                                                <select name="wc_cs_promo_sets[<?php echo $index; ?>][badge_type]" style="width: 100%;">
                                                    <option value="bestseller" <?php selected(isset($p_set['badge_type']) ? $p_set['badge_type'] : '', 'bestseller'); ?>>Najczęściej wybierane (ze słownika)</option>
                                                    <option value="new" <?php selected(isset($p_set['badge_type']) ? $p_set['badge_type'] : '', 'new'); ?>>Nowość (ze słownika)</option>
                                                    <option value="none" <?php selected(isset($p_set['badge_type']) ? $p_set['badge_type'] : '', 'none'); ?>>Brak odznaki</option>
                                                    <option value="custom" <?php selected(isset($p_set['badge_type']) ? $p_set['badge_type'] : '', 'custom'); ?>>Własny tekst</option>
                                                </select>
                                            </div>
                                            <div class="cs-input-group full-width">
                                                <label>Własny tekst odznaki</label>
                                                <input type="text" name="wc_cs_promo_sets[<?php echo $index; ?>][badge_custom]" value="<?php echo esc_attr(isset($p_set['badge_custom']) ? $p_set['badge_custom'] : ''); ?>" placeholder="Wpisz własny tekst...">
                                            </div>
                                            <div class="cs-input-group full-width">
                                                <label>Tytuł zestawu (np. Zestaw Shav)</label>
                                                <input type="text" name="wc_cs_promo_sets[<?php echo $index; ?>][title]" value="<?php echo esc_attr(isset($p_set['title']) ? $p_set['title'] : ''); ?>" placeholder="Tytuł produktu...">
                                            </div>
                                            <div class="cs-input-group full-width">
                                                <label>Tekst na przycisku (np. Dodaj Zestaw)</label>
                                                <input type="text" name="wc_cs_promo_sets[<?php echo $index; ?>][btn_label]" value="<?php echo esc_attr(isset($p_set['btn_label']) ? $p_set['btn_label'] : ''); ?>" placeholder="Dodaj Zestaw">
                                            </div>
                                            <div class="cs-input-group">
                                                <label>Stara Cena (Przekreślona)</label>
                                                <input type="text" name="wc_cs_promo_sets[<?php echo $index; ?>][price_reg]" value="<?php echo esc_attr(isset($p_set['price_reg']) ? $p_set['price_reg'] : ''); ?>" placeholder="Domyślnie z produktu">
                                            </div>
                                            <div class="cs-input-group">
                                                <label>Nowa Cena (Promocyjna)</label>
                                                <input type="text" name="wc_cs_promo_sets[<?php echo $index; ?>][price_promo]" value="<?php echo esc_attr(isset($p_set['price_promo']) ? $p_set['price_promo'] : ''); ?>" placeholder="Domyślnie z produktu">
                                            </div>
                                            <div class="cs-input-group full-width">
                                                <label>Składniki (jeden punkt na linię)</label>
                                                <textarea name="wc_cs_promo_sets[<?php echo $index; ?>][items]" rows="4" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px; font-size: 13px;"><?php echo esc_textarea(isset($p_set['items']) ? $p_set['items'] : ''); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Zdjęcie zestawu:</th>
                                    <td>
                                        <div class="wc_cs_image_preview"><?php if (!empty($p_set['image'])) : ?><img src="<?php echo esc_url($p_set['image']); ?>" style="max-width:150px; display:block;"><br><?php endif; ?></div>
                                        <input type="hidden" name="wc_cs_promo_sets[<?php echo $index; ?>][image]" class="wc_cs_custom_image" value="<?php echo esc_attr(isset($p_set['image']) ? $p_set['image'] : ''); ?>">
                                        <button type="button" class="button wc_cs_upload_image_btn">Wybierz zdjęcie</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="tab-rules" class="cs-tab-content">
                <div id="wc-cs-rules-wrapper" style="margin-top: 15px;">
                    <?php foreach ($saved_rules as $index => $rule) : 
                        $product_id = isset($rule['product_id']) ? $rule['product_id'] : '';
                        $rule_uid = isset($rule['uid']) ? $rule['uid'] : uniqid('cs_');
                        $is_active = isset($rule['is_active']) ? $rule['is_active'] : 'yes';
                        $priority = isset($rule['priority']) ? intval($rule['priority']) : 0;
                        $title_display = 'Nowa reguła';
                        if ($product_id) {
                            $prod_obj = wc_get_product($product_id);
                            if ($prod_obj) $title_display = $prod_obj->get_name(); 
                        }
                        $sales = isset($rule['sales_count']) ? $rule['sales_count'] : 0;
                        $revenue = isset($rule['sales_revenue']) ? $rule['sales_revenue'] : 0;
                        $sync_attr = isset($rule['sync_attr']) ? $rule['sync_attr'] : '';
                        
                        $inactive_class = $is_active === 'no' ? 'is-inactive' : '';
                    ?>
                    <div class="wc-cs-rule-card postbox closed <?php echo $inactive_class; ?>" data-index="<?php echo $index; ?>" style="max-width: 900px;">
                        <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][uid]" value="<?php echo esc_attr($rule_uid); ?>">
                        <div class="postbox-header">
                            <h2>
                                <span class="rule-title-text">
                                    <strong><?php echo esc_html($title_display); ?></strong>
                                </span>
                                <?php if ($is_active === 'no'): ?>
                                    <span style="color: #d63638; font-weight: normal; font-size: 12px; margin-left: 5px;">(Wyłączona)</span>
                                <?php endif; ?>
                                <span class="cs-header-stats">
                                    Sprzedano: <?php echo $sales; ?> | Przychód: <?php echo wc_price($revenue); ?>
                                </span>
                            </h2>
                            <div class="handle-actions">
                                <button type="button" class="button-link wc-cs-clone-rule">Powiel</button>
                                <button type="button" class="button-link wc-cs-remove-rule">Usuń</button>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th style="width: 25%;">Status i Priorytet:</th>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                                            <label class="cs-switch-container">
                                                <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][is_active]" value="no">
                                                <input type="checkbox" name="wc_cs_rules[<?php echo $index; ?>][is_active]" class="wc-cs-active-toggle" value="yes" <?php checked($is_active, 'yes'); ?>> 
                                                <span class="cs-switch-slider"></span>
                                                <span class="cs-switch-text">Reguła włączona</span>
                                            </label>
                                            <div data-tooltip="Mniejsza liczba wyświetla się jako pierwsza w karuzeli (np. 0 będzie przed 10)" style="display: flex; align-items: center; gap: 10px;">
                                                <span class="cs-switch-text">Priorytet sortowania:</span> 
                                                <input type="number" name="wc_cs_rules[<?php echo $index; ?>][priority]" class="wc-cs-priority-input" value="<?php echo esc_attr($priority); ?>" style="width: 80px;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">Produkt w ofercie:</th>
                                    <td>
                                        <select class="wc-product-search wc-cs-product-select" name="wc_cs_rules[<?php echo $index; ?>][product_id]" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations">
                                            <?php if ($product_id) : ?>
                                                <option value="<?php echo esc_attr($product_id); ?>" selected><?php echo esc_html($title_display); ?></option>
                                            <?php endif; ?>
                                        </select>
                                        <div style="margin-top: 15px;">
                                            <label class="cs-switch-container">
                                                <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][generate_variants]" value="no">
                                                <input type="checkbox" name="wc_cs_rules[<?php echo $index; ?>][generate_variants]" class="generate-checkbox" value="yes"> 
                                                <span class="cs-switch-slider"></span>
                                                <span class="cs-switch-text">Utwórz osobne reguły dla wariantów (zapisz, aby wygenerować)</span>
                                            </label>
                                        </div>
                                        <div class="generate-attr-container" style="display:none;">
                                            <select name="wc_cs_rules[<?php echo $index; ?>][sync_attr]" style="max-width: 200px;">
                                                <option value="">Wybierz atrybut...</option>
                                                <?php foreach ( $attribute_taxonomies as $tax ) : 
                                                    $tax_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                                                    $selected = ($sync_attr === $tax_name) ? 'selected' : '';
                                                    echo '<option value="' . esc_attr($tax_name) . '" ' . $selected . '>' . esc_html($tax->attribute_label) . '</option>';
                                                endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Zasięg (Gdzie pokazać):</th>
                                    <td>
                                        <label class="cs-switch-container" style="margin-bottom: 15px;">
                                            <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][is_global]" value="no">
                                            <input type="checkbox" name="wc_cs_rules[<?php echo $index; ?>][is_global]" value="yes" <?php checked(isset($rule['is_global']) ? $rule['is_global'] : 'no', 'yes'); ?>> 
                                            <span class="cs-switch-slider"></span>
                                            <span class="cs-switch-text">Globalny (pokaż w każdym koszyku)</span>
                                        </label>
                                        <select class="wc-product-search" name="wc_cs_rules[<?php echo $index; ?>][target_ids][]" multiple="multiple" style="width: 100%;" data-placeholder="Produkty aktywujące ofertę..." data-action="woocommerce_json_search_products_and_variations">
                                            <?php if (!empty($rule['target_ids'])) {
                                                foreach ($rule['target_ids'] as $tid) {
                                                    $t_prod = wc_get_product($tid);
                                                    $t_name = $t_prod ? $t_prod->get_name() : $tid;
                                                    echo '<option value="'.esc_attr($tid).'" selected>'.esc_html($t_name).'</option>';
                                                }
                                            } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Treści i Ceny:</th>
                                    <td>
                                        <div class="cs-inputs-grid">
                                            <div class="cs-input-group full-width">
                                                <label>Podtytuł w kafelku (np. Prezent)</label>
                                                <input type="text" name="wc_cs_rules[<?php echo $index; ?>][header_sub]" value="<?php echo esc_attr(isset($rule['header_sub']) ? $rule['header_sub'] : ''); ?>" placeholder="Wpisz podtytuł...">
                                            </div>
                                            <div class="cs-input-group full-width">
                                                <label>Własna nazwa produktu</label>
                                                <input type="text" name="wc_cs_rules[<?php echo $index; ?>][title]" value="<?php echo esc_attr(isset($rule['title']) ? $rule['title'] : ''); ?>" class="wc-cs-title-input" placeholder="Zostaw puste, by pobrać automatycznie...">
                                            </div>
                                            <div class="cs-input-group">
                                                <label>Własny Atrybut 1</label>
                                                <input type="text" name="wc_cs_rules[<?php echo $index; ?>][attr_1]" value="<?php echo esc_attr(isset($rule['attr_1']) ? $rule['attr_1'] : ''); ?>" placeholder="Opcjonalnie...">
                                            </div>
                                            <div class="cs-input-group">
                                                <label>Własny Atrybut 2</label>
                                                <input type="text" name="wc_cs_rules[<?php echo $index; ?>][attr_2]" value="<?php echo esc_attr(isset($rule['attr_2']) ? $rule['attr_2'] : ''); ?>" placeholder="Opcjonalnie...">
                                            </div>
                                            <div class="cs-input-group">
                                                <label>Stara Cena (Przekreślona)</label>
                                                <input type="text" name="wc_cs_rules[<?php echo $index; ?>][price_reg]" value="<?php echo esc_attr(isset($rule['price_reg']) ? $rule['price_reg'] : ''); ?>" class="wc-cs-price-reg" placeholder="Domyślnie z produktu">
                                            </div>
                                            <div class="cs-input-group">
                                                <label>Nowa Cena (Promocyjna)</label>
                                                <input type="text" name="wc_cs_rules[<?php echo $index; ?>][price_promo]" value="<?php echo esc_attr(isset($rule['price_promo']) ? $rule['price_promo'] : ''); ?>" placeholder="Domyślnie z produktu">
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][sales_count]" value="<?php echo $sales; ?>">
                                        <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][sales_revenue]" value="<?php echo $revenue; ?>">
                                        <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][analytic_name]" value="<?php echo esc_attr(isset($rule['analytic_name']) ? $rule['analytic_name'] : ''); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Zdjęcie (Własne):</th>
                                    <td>
                                        <div class="wc_cs_image_preview"><?php if (!empty($rule['custom_image'])) : ?><img src="<?php echo esc_url($rule['custom_image']); ?>" style="max-width:100px; display:block;"><br><?php endif; ?></div>
                                        <input type="hidden" name="wc_cs_rules[<?php echo $index; ?>][custom_image]" class="wc_cs_custom_image" value="<?php echo esc_attr(isset($rule['custom_image']) ? $rule['custom_image'] : ''); ?>">
                                        <button type="button" class="button wc_cs_upload_image_btn">Wybierz zdjęcie graficzne</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="wc-cs-add-rule" class="button button-secondary">+ Dodaj regułę</button>
                <br><br>
                <input type="submit" name="wc_cs_save" class="button button-primary button-large" value="Zapisz wszystkie ustawienia">
            </div>

            <div id="tab-analysis" class="cs-tab-content" style="display: none; margin-top: 15px;">
                <div class="cs-filter-bar">
                    <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <strong>Raport wydajności Cross-sell</strong>
                        <select id="cs-date-filter" style="max-width: 200px;">
                            <option value="1">Dziś</option>
                            <option value="7">Ostatnie 7 dni</option>
                            <option value="30" selected>Ostatnie 30 dni</option>
                            <option value="90">Ostatnie 90 dni</option>
                            <option value="9999">Cały okres</option>
                        </select>
                        <span style="color: #888; font-size: 13px;">lub wybierz datę:</span>
                        <input type="date" id="cs-specific-date" style="max-width: 150px;">
                    </div>
                </div>

                <div class="cs-dashboard-grid">
                    <div class="cs-kpi-card">
                        <div class="cs-kpi-title" data-tooltip="Całkowity przychód wygenerowany bezpośrednio z ofert cross-sell w wybranym okresie.">Łączny Przychód</div>
                        <div class="cs-kpi-value" id="cs-val-revenue">0 zł</div>
                    </div>
                    <div class="cs-kpi-card">
                        <div class="cs-kpi-title" data-tooltip="Procent wyświetleń, które zakończyły się dodaniem produktu do koszyka i zakupem (Sprzedaż / Wyświetlenia * 100).">Średnia Konwersja (CR)</div>
                        <div class="cs-kpi-value" id="cs-val-cr">0.0%</div>
                    </div>
                    <div class="cs-kpi-card">
                        <div class="cs-kpi-title" data-tooltip="Procent wyświetleń, które zakończyły się kliknięciem w ofertę przez klienta (Kliknięcia / Wyświetlenia * 100).">Klikalność (CTR)</div>
                        <div class="cs-kpi-value" id="cs-val-ctr">0.0%</div>
                    </div>
                    <div class="cs-kpi-card">
                        <div class="cs-kpi-title" data-tooltip="Łączna liczba produktów kupionych dzięki kliknięciu w regułę cross-sell.">Sprzedane Sztuki</div>
                        <div class="cs-kpi-value" id="cs-val-sales">0</div>
                    </div>
                </div>

                <div class="cs-table-wrapper">
                    <table class="cs-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Reguła / Produkt (Kliknij w ikonę, aby zmienić nazwę)</th>
                                <th>Wyświetlenia</th>
                                <th>Kliknięcia</th>
                                <th>Sprzedaż</th>
                                <th>Przychód</th>
                                <th>CR</th>
                                <th>Status</th>
                                <th style="text-align: center;">Akcje</th>
                            </tr>
                        </thead>
                        <tbody id="cs-table-body">
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-gratis" class="cs-tab-content" style="display: none; margin-top: 15px;">
                <div class="notice notice-info inline" style="margin-bottom: 20px; border-radius: 8px; border: 1px solid #bae6fd; border-left: 4px solid #3b82f6;">
                    <p style="color: #1e3a8a;"><strong>Zasada działania:</strong> Gratisy dodawane są automatycznie po spełnieniu warunku. Jeśli klient usunie produkt główny z koszyka lub zmniejszy zamówienie, gratis zostanie odjęty. KLIENT MOŻE TERAZ SAMODZIELNIE USUNĄĆ PREZENT Z KOSZYKA - SYSTEM ZAPAMIĘTA TĘ DECYZJĘ.</p>
                </div>
                
                <div id="gratis-rules-wrapper">
                    <?php foreach ($saved_gratis as $g_index => $g_rule) : 
                        $gift_id = isset($g_rule['gift_id']) ? $g_rule['gift_id'] : '';
                        $title_display = 'Nowy gratis';
                        if ($gift_id) {
                            $prod_obj = wc_get_product($gift_id);
                            if ($prod_obj) $title_display = $prod_obj->get_name(); 
                        }
                        $trigger_type = isset($g_rule['trigger_type']) ? $g_rule['trigger_type'] : 'amount';
                    ?>
                    <div class="wc-cs-gratis-card postbox closed" data-index="<?php echo $g_index; ?>" style="max-width: 900px;">
                        <div class="postbox-header">
                            <h2>
                                <span class="rule-title-text"><strong><?php echo esc_html($title_display); ?></strong></span>
                            </h2>
                            <div class="handle-actions">
                                <button type="button" class="button-link wc-cs-remove-gratis">Usuń</button>
                                <span class="toggle-indicator"></span>
                            </div>
                        </div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th style="width: 30%;">Status reguły:</th>
                                    <td>
                                        <label class="cs-switch-container" style="margin-bottom: 10px;">
                                            <input type="hidden" name="wc_cs_gratis[<?php echo $g_index; ?>][active]" value="no">
                                            <input type="checkbox" name="wc_cs_gratis[<?php echo $g_index; ?>][active]" value="yes" <?php checked(isset($g_rule['active']) ? $g_rule['active'] : 'no', 'yes'); ?>> 
                                            <span class="cs-switch-slider"></span>
                                            <span class="cs-switch-text">Aktywna</span>
                                        </label><br>
                                        <label class="cs-switch-container">
                                            <input type="hidden" name="wc_cs_gratis[<?php echo $g_index; ?>][admin_only]" value="no">
                                            <input type="checkbox" name="wc_cs_gratis[<?php echo $g_index; ?>][admin_only]" value="yes" <?php checked(isset($g_rule['admin_only']) ? $g_rule['admin_only'] : 'no', 'yes'); ?>> 
                                            <span class="cs-switch-slider"></span>
                                            <span class="cs-switch-text" style="color: #64748b;">Tryb testowy (widoczny tylko dla administratora)</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Produkt w prezencie:</th>
                                    <td>
                                        <select class="wc-product-search wc-cs-gratis-select" name="wc_cs_gratis[<?php echo $g_index; ?>][gift_id]" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations">
                                            <?php if ($gift_id) : ?>
                                                <option value="<?php echo esc_attr($gift_id); ?>" selected><?php echo esc_html($title_display); ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Warunek przyznania:</th>
                                    <td>
                                        <select name="wc_cs_gratis[<?php echo $g_index; ?>][trigger_type]" class="cs-gratis-trigger-type" style="width: 100%; max-width: 400px;">
                                            <option value="amount" <?php selected($trigger_type, 'amount'); ?>>Daj prezent, gdy suma koszyka przekroczy...</option>
                                            <option value="product" <?php selected($trigger_type, 'product'); ?>>Daj prezent, gdy w koszyku jest...</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="gratis-amount-row" style="<?php echo $trigger_type === 'amount' ? '' : 'display:none;'; ?>">
                                    <th>Wymagana kwota zakupów (zł):</th>
                                    <td><input type="number" name="wc_cs_gratis[<?php echo $g_index; ?>][threshold]" value="<?php echo esc_attr(isset($g_rule['threshold']) ? $g_rule['threshold'] : ''); ?>" placeholder="np. 200" style="max-width: 200px;"></td>
                                </tr>
                                <tr class="gratis-product-row" style="<?php echo $trigger_type === 'product' ? '' : 'display:none;'; ?>">
                                    <th>Kupowane produkty (wyzwalacze):</th>
                                    <td>
                                        <select class="wc-product-search" name="wc_cs_gratis[<?php echo $g_index; ?>][target_ids][]" multiple="multiple" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations">
                                            <?php if (!empty($g_rule['target_ids'])) {
                                                foreach ($g_rule['target_ids'] as $tid) {
                                                    $t_prod = wc_get_product($tid);
                                                    $t_name = $t_prod ? $t_prod->get_name() : $tid;
                                                    echo '<option value="'.esc_attr($tid).'" selected>'.esc_html($t_name).'</option>';
                                                }
                                            } ?>
                                        </select>
                                        <div style="margin-top: 15px;">
                                            <label class="cs-switch-container">
                                                <input type="hidden" name="wc_cs_gratis[<?php echo $g_index; ?>][sync_qty]" value="no">
                                                <input type="checkbox" name="wc_cs_gratis[<?php echo $g_index; ?>][sync_qty]" value="yes" <?php checked(isset($g_rule['sync_qty']) ? $g_rule['sync_qty'] : 'no', 'yes'); ?>> 
                                                <span class="cs-switch-slider"></span>
                                                <span class="cs-switch-text">Dodaj tyle samo prezentów, ile jest produktów głównych</span>
                                            </label>
                                            <p class="description" style="margin-left: 46px; margin-top: 0;">Odznacz, jeśli klient ma otrzymać maksymalnie 1 gratis niezależnie od ilości.</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="wc-cs-add-gratis" class="button button-secondary">+ Dodaj gratis</button>
                <br><br>
                <input type="submit" name="wc_cs_save" class="button button-primary button-large" value="Zapisz wszystkie ustawienia">
            </div>
        </form>
    </div>

    <script type="text/template" id="wc-cs-rule-template">
        <div class="wc-cs-rule-card postbox closed" data-index="{{INDEX}}" style="max-width: 900px;">
            <input type="hidden" name="wc_cs_rules[{{INDEX}}][uid]" value="{{UID}}">
            <div class="postbox-header">
                <h2>
                    <span class="rule-title-text"><strong>Nowa reguła</strong></span> 
                    <span class="cs-header-stats">Sprzedano: 0 | Przychód: 0,00 zł</span>
                </h2>
                <div class="handle-actions">
                    <button type="button" class="button-link wc-cs-clone-rule">Powiel</button>
                    <button type="button" class="button-link wc-cs-remove-rule">Usuń</button>
                    <span class="toggle-indicator"></span>
                </div>
            </div>
            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th style="width: 25%;">Status i Priorytet:</th>
                        <td>
                            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                                <label class="cs-switch-container">
                                    <input type="hidden" name="wc_cs_rules[{{INDEX}}][is_active]" value="no">
                                    <input type="checkbox" name="wc_cs_rules[{{INDEX}}][is_active]" class="wc-cs-active-toggle" value="yes" checked> 
                                    <span class="cs-switch-slider"></span>
                                    <span class="cs-switch-text">Reguła włączona</span>
                                </label>
                                <div data-tooltip="Mniejsza liczba wyświetla się jako pierwsza w karuzeli (np. 0 będzie przed 10)" style="display: flex; align-items: center; gap: 10px;">
                                    <span class="cs-switch-text">Priorytet sortowania:</span> 
                                    <input type="number" name="wc_cs_rules[{{INDEX}}][priority]" class="wc-cs-priority-input" value="0" style="width: 80px;">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 25%;">Produkt w ofercie:</th>
                        <td><select class="wc-product-search wc-cs-product-select" name="wc_cs_rules[{{INDEX}}][product_id]" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations"></select>
                        <div style="margin-top: 15px;">
                            <label class="cs-switch-container">
                                <input type="hidden" name="wc_cs_rules[{{INDEX}}][generate_variants]" value="no">
                                <input type="checkbox" name="wc_cs_rules[{{INDEX}}][generate_variants]" class="generate-checkbox" value="yes"> 
                                <span class="cs-switch-slider"></span>
                                <span class="cs-switch-text">Utwórz osobne reguły dla wariantów (zapisz, aby wygenerować)</span>
                            </label>
                        </div>
                        <div class="generate-attr-container" style="display:none;">
                            <select name="wc_cs_rules[{{INDEX}}][sync_attr]" style="max-width: 200px;">
                                <option value="">Wybierz atrybut...</option>
                                <?php foreach ( $attribute_taxonomies as $tax ) : 
                                    $tax_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                                    echo '<option value="' . esc_attr($tax_name) . '">' . esc_html($tax->attribute_label) . '</option>';
                                endforeach; ?>
                            </select>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Zasięg (Gdzie pokazać):</th>
                        <td>
                            <label class="cs-switch-container" style="margin-bottom: 15px;">
                                <input type="hidden" name="wc_cs_rules[{{INDEX}}][is_global]" value="no">
                                <input type="checkbox" name="wc_cs_rules[{{INDEX}}][is_global]" value="yes"> 
                                <span class="cs-switch-slider"></span>
                                <span class="cs-switch-text">Globalny (pokaż w każdym koszyku)</span>
                            </label>
                            <select class="wc-product-search" name="wc_cs_rules[{{INDEX}}][target_ids][]" multiple="multiple" style="width: 100%;" data-placeholder="Produkty aktywujące ofertę..." data-action="woocommerce_json_search_products_and_variations"></select>
                        </td>
                    </tr>
                    <tr>
                        <th>Treści i Ceny:</th>
                        <td>
                            <div class="cs-inputs-grid">
                                <div class="cs-input-group full-width">
                                    <label>Podtytuł w kafelku (np. Prezent)</label>
                                    <input type="text" name="wc_cs_rules[{{INDEX}}][header_sub]" placeholder="Wpisz podtytuł...">
                                </div>
                                <div class="cs-input-group full-width">
                                    <label>Własna nazwa produktu</label>
                                    <input type="text" name="wc_cs_rules[{{INDEX}}][title]" class="wc-cs-title-input" placeholder="Zostaw puste, by pobrać automatycznie...">
                                </div>
                                <div class="cs-input-group">
                                    <label>Własny Atrybut 1</label>
                                    <input type="text" name="wc_cs_rules[{{INDEX}}][attr_1]" placeholder="Opcjonalnie...">
                                </div>
                                <div class="cs-input-group">
                                    <label>Własny Atrybut 2</label>
                                    <input type="text" name="wc_cs_rules[{{INDEX}}][attr_2]" placeholder="Opcjonalnie...">
                                </div>
                                <div class="cs-input-group">
                                    <label>Stara Cena (Przekreślona)</label>
                                    <input type="text" name="wc_cs_rules[{{INDEX}}][price_reg]" class="wc-cs-price-reg" placeholder="Domyślnie z produktu">
                                </div>
                                <div class="cs-input-group">
                                    <label>Nowa Cena (Promocyjna)</label>
                                    <input type="text" name="wc_cs_rules[{{INDEX}}][price_promo]" placeholder="Domyślnie z produktu">
                                </div>
                            </div>
                            <input type="hidden" name="wc_cs_rules[{{INDEX}}][analytic_name]" value="">
                        </td>
                    </tr>
                    <tr>
                        <th>Zdjęcie (Własne):</th>
                        <td><div class="wc_cs_image_preview"></div><input type="hidden" name="wc_cs_rules[{{INDEX}}][custom_image]" class="wc_cs_custom_image" value=""><button type="button" class="button wc_cs_upload_image_btn">Wybierz zdjęcie graficzne</button></td>
                    </tr>
                </table>
            </div>
        </div>
    </script>

    <script type="text/template" id="wc-cs-gratis-template">
        <div class="wc-cs-gratis-card postbox closed" data-index="{{INDEX}}" style="max-width: 900px;">
            <div class="postbox-header">
                <h2>
                    <span class="rule-title-text"><strong>Nowy gratis</strong></span>
                </h2>
                <div class="handle-actions">
                    <button type="button" class="button-link wc-cs-remove-gratis">Usuń</button>
                    <span class="toggle-indicator"></span>
                </div>
            </div>
            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th style="width: 30%;">Status reguły:</th>
                        <td>
                            <label class="cs-switch-container" style="margin-bottom: 10px;">
                                <input type="hidden" name="wc_cs_gratis[{{INDEX}}][active]" value="no">
                                <input type="checkbox" name="wc_cs_gratis[{{INDEX}}][active]" value="yes"> 
                                <span class="cs-switch-slider"></span>
                                <span class="cs-switch-text">Aktywna</span>
                            </label><br>
                            <label class="cs-switch-container">
                                <input type="hidden" name="wc_cs_gratis[{{INDEX}}][admin_only]" value="no">
                                <input type="checkbox" name="wc_cs_gratis[{{INDEX}}][admin_only]" value="yes"> 
                                <span class="cs-switch-slider"></span>
                                <span class="cs-switch-text" style="color: #64748b;">Tryb testowy (widoczny tylko dla administratora)</span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Produkt w prezencie:</th>
                        <td><select class="wc-product-search wc-cs-gratis-select" name="wc_cs_gratis[{{INDEX}}][gift_id]" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations"></select></td>
                    </tr>
                    <tr>
                        <th>Warunek przyznania:</th>
                        <td>
                            <select name="wc_cs_gratis[{{INDEX}}][trigger_type]" class="cs-gratis-trigger-type" style="width: 100%; max-width: 400px;">
                                <option value="amount">Daj prezent, gdy suma koszyka przekroczy...</option>
                                <option value="product">Daj prezent, gdy w koszyku jest...</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="gratis-amount-row">
                        <th>Wymagana kwota zakupów (zł):</th>
                        <td><input type="number" name="wc_cs_gratis[{{INDEX}}][threshold]" placeholder="np. 200" style="max-width: 200px;"></td>
                    </tr>
                    <tr class="gratis-product-row" style="display:none;">
                        <th>Kupowane produkty (wyzwalacze):</th>
                        <td>
                            <select class="wc-product-search" name="wc_cs_gratis[{{INDEX}}][target_ids][]" multiple="multiple" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations"></select>
                            <div style="margin-top: 15px;">
                                <label class="cs-switch-container">
                                    <input type="hidden" name="wc_cs_gratis[{{INDEX}}][sync_qty]" value="no">
                                    <input type="checkbox" name="wc_cs_gratis[{{INDEX}}][sync_qty]" value="yes"> 
                                    <span class="cs-switch-slider"></span>
                                    <span class="cs-switch-text">Dodaj tyle samo prezentów, ile jest produktów głównych</span>
                                </label>
                                <p class="description" style="margin-left: 46px; margin-top: 0;">Odznacz, jeśli klient ma otrzymać maksymalnie 1 gratis niezależnie od ilości.</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </script>

    <script type="text/template" id="wc-cs-promo-set-template">
        <div class="wc-cs-promo-set-card postbox closed" data-index="{{INDEX}}" style="max-width: 900px;">
            <div class="postbox-header">
                <h2>
                    <span class="rule-title-text">
                        <strong>Nowy zestaw</strong>
                    </span>
                </h2>
                <div class="handle-actions">
                    <button type="button" class="button-link wc-cs-remove-promo-set">Usuń</button>
                    <span class="toggle-indicator" aria-hidden="true"></span>
                </div>
            </div>
            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th style="width: 25%;">Status:</th>
                        <td>
                            <label class="cs-switch-container">
                                <input type="hidden" name="wc_cs_promo_sets[{{INDEX}}][active]" value="no">
                                <input type="checkbox" name="wc_cs_promo_sets[{{INDEX}}][active]" class="wc-cs-active-toggle" value="yes" checked> 
                                <span class="cs-switch-slider"></span>
                                <span class="cs-switch-text">Zestaw włączony</span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Gdzie wyświetlać zestaw:</th>
                        <td>
                            <label class="cs-switch-container" style="margin-bottom: 15px;">
                                <input type="hidden" name="wc_cs_promo_sets[{{INDEX}}][is_global]" value="no">
                                <input type="checkbox" name="wc_cs_promo_sets[{{INDEX}}][is_global]" value="yes"> 
                                <span class="cs-switch-slider"></span>
                                <span class="cs-switch-text">Globalny (pokaż na każdej stronie produktu)</span>
                            </label>
                            <select class="wc-product-search" name="wc_cs_promo_sets[{{INDEX}}][target_ids][]" multiple="multiple" style="width: 100%;" data-placeholder="Produkty, na których wyświetli się zestaw..." data-action="woocommerce_json_search_products_and_variations"></select>
                        </td>
                    </tr>
                    <tr>
                        <th>Produkt do dodania (zestaw):</th>
                        <td>
                            <select class="wc-product-search wc-cs-promo-set-target-select" name="wc_cs_promo_sets[{{INDEX}}][target_id]" style="width: 100%;" data-action="woocommerce_json_search_products_and_variations"></select>
                            <p class="description">Ten produkt zostanie dodany do koszyka po kliknięciu w przycisk.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Treści i Ceny:</th>
                        <td>
                            <div class="cs-inputs-grid">
                                <div class="cs-input-group full-width">
                                    <label>Nagłówek zestawu (np. Zestaw Premium)</label>
                                    <input type="text" name="wc_cs_promo_sets[{{INDEX}}][header_label]" placeholder="Wpisz nagłówek...">
                                </div>
                                <div class="cs-input-group full-width">
                                    <label>Typ odznaki (Pill na zestawie)</label>
                                    <select name="wc_cs_promo_sets[{{INDEX}}][badge_type]" style="width: 100%;">
                                        <option value="bestseller" selected>Najczęściej wybierane (ze słownika)</option>
                                        <option value="new">Nowość (ze słownika)</option>
                                        <option value="none">Brak odznaki</option>
                                        <option value="custom">Własny tekst</option>
                                    </select>
                                </div>
                                <div class="cs-input-group full-width">
                                    <label>Własny tekst odznaki</label>
                                    <input type="text" name="wc_cs_promo_sets[{{INDEX}}][badge_custom]" placeholder="Wpisz własny tekst...">
                                </div>
                                <div class="cs-input-group full-width">
                                    <label>Tytuł zestawu (np. Zestaw Shav)</label>
                                    <input type="text" name="wc_cs_promo_sets[{{INDEX}}][title]" class="wc-cs-promo-set-title-input" placeholder="Tytuł produktu...">
                                </div>
                                <div class="cs-input-group full-width">
                                    <label>Tekst na przycisku (np. Dodaj Zestaw)</label>
                                    <input type="text" name="wc_cs_promo_sets[{{INDEX}}][btn_label]" placeholder="Dodaj Zestaw">
                                </div>
                                <div class="cs-input-group">
                                    <label>Stara Cena (Przekreślona)</label>
                                    <input type="text" name="wc_cs_promo_sets[{{INDEX}}][price_reg]" class="wc-cs-promo-set-price-reg" placeholder="Domyślnie z produktu">
                                </div>
                                <div class="cs-input-group">
                                    <label>Nowa Cena (Promocyjna)</label>
                                    <input type="text" name="wc_cs_promo_sets[{{INDEX}}][price_promo]" placeholder="Domyślnie z produktu">
                                </div>
                                <div class="cs-input-group full-width">
                                    <label>Składniki (jeden punkt na linię)</label>
                                    <textarea name="wc_cs_promo_sets[{{INDEX}}][items]" rows="4" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px; font-size: 13px;"></textarea>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Zdjęcie zestawu:</th>
                        <td>
                            <div class="wc_cs_image_preview"></div>
                            <input type="hidden" name="wc_cs_promo_sets[{{INDEX}}][image]" class="wc_cs_custom_image" value="">
                            <button type="button" class="button wc_cs_upload_image_btn">Wybierz zdjęcie</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var rawStats = <?php echo json_encode($daily_stats); ?>;
        var ruleNames = <?php echo json_encode($rule_names); ?>;
        var ruleTriggers = <?php echo json_encode($rule_triggers); ?>;

        function renderDashboard(daysLimit, specificDate = null) {
            var cutoffDate = new Date();
            if (daysLimit !== 1) {
                cutoffDate.setDate(cutoffDate.getDate() - daysLimit);
            }
            var cutoffStr = cutoffDate.toISOString().split('T')[0];
            var todayStr = new Date().toISOString().split('T')[0];

            var agg = {};
            var totals = { i: 0, c: 0, s: 0, r: 0 };

            $.each(ruleNames, function(rUid, data) {
                if (data.form_index !== null) {
                    agg[rUid] = { i: 0, c: 0, s: 0, r: 0 };
                }
            });

            $.each(rawStats, function(dateStr, rules) {
                var includeData = false;
                
                if (specificDate) {
                    includeData = (dateStr === specificDate);
                } else {
                    if (daysLimit === 9999) {
                        includeData = true;
                    } else if (daysLimit === 1) {
                        includeData = (dateStr === todayStr);
                    } else {
                        includeData = (dateStr >= cutoffStr);
                    }
                }

                if (includeData) {
                    $.each(rules, function(rUid, data) {
                        if (!agg[rUid]) agg[rUid] = { i: 0, c: 0, s: 0, r: 0 };
                        agg[rUid].i += data.i;
                        agg[rUid].c += data.c;
                        agg[rUid].s += data.s;
                        agg[rUid].r += data.r;

                        totals.i += data.i;
                        totals.c += data.c;
                        totals.s += data.s;
                        totals.r += data.r;
                    });
                }
            });

            var overallCR = totals.i > 0 ? ((totals.s / totals.i) * 100).toFixed(1) : 0;
            var overallCTR = totals.i > 0 ? ((totals.c / totals.i) * 100).toFixed(1) : 0;
            
            $('#cs-val-revenue').text(totals.r.toFixed(2) + ' zł');
            $('#cs-val-cr').text(overallCR + '%');
            $('#cs-val-ctr').text(overallCTR + '%');
            $('#cs-val-sales').text(totals.s);

            var tbody = '';
            var sortedRules = Object.keys(agg).sort(function(a, b) { return agg[b].r - agg[a].r; }); 
            
            $.each(sortedRules, function(_, rUid) {
                var d = agg[rUid];
                var ruleNameData = ruleNames[rUid] || { base: 'Nieaktywna/Usunięta reguła', analytic: '', form_index: null };
                var displayName = ruleNameData.analytic !== '' ? ruleNameData.analytic : ruleNameData.base;
                
                var nameHtml = '<div class="cs-rule-name-container" style="display:flex; align-items:center;">';
                nameHtml += '<span class="cs-name-display" style="font-weight:700;">' + displayName + '</span>';
                
                if (ruleNameData.form_index !== null) {
                    nameHtml += '<span class="dashicons dashicons-edit cs-edit-icon" data-tooltip="Zmień nazwę reguły" data-uid="' + rUid + '" data-base="' + ruleNameData.base + '"></span>';
                    nameHtml += '<input type="text" class="cs-analytic-name-input" data-uid="' + rUid + '" data-form-index="' + ruleNameData.form_index + '" value="' + ruleNameData.analytic + '" placeholder="' + ruleNameData.base + '" style="display:none;">';
                }
                nameHtml += '</div>';

                var triggerInfo = '';
                if (ruleTriggers[rUid] === 'GLOBAL') {
                    triggerInfo = '<div style="font-size:10px; color:#2271b1; font-weight:600; margin-top:2px;">[WSZYSTKIE PRODUKTY]</div>';
                } else if (ruleTriggers[rUid] && ruleTriggers[rUid].names) {
                    var names = ruleTriggers[rUid].names;
                    var total = ruleTriggers[rUid].total;
                    var displayNames = names.slice(0, 2).join(', ');
                    var more = total > 2 ? ' + ' + (total - 2) + ' inne' : '';
                    
                    triggerInfo = '<div style="font-size:10px; color:#72777c; margin-top:2px; line-height:1.2;" data-tooltip="' + (total > 2 ? 'Ilość wyzwalaczy: ' + total : '') + '">';
                    triggerInfo += 'Z: ' + displayNames + more;
                    triggerInfo += '</div>';
                }

                var cr = d.i > 0 ? ((d.s / d.i) * 100).toFixed(1) : 0;
                var statusHtml = '';
                
                if (d.i === 0) {
                    statusHtml = '<span class="cs-badge cs-badge-neutral" data-tooltip="Brak danych sprzedażowych w wybranym okresie.">Brak danych</span>';
                } else {
                    if (cr < 2) {
                        statusHtml = '<span class="cs-badge cs-badge-bad" data-tooltip="KONWERSJA PONIŻEJ 2%. PRODUKT RZADKO WYBIERANY PRZEZ KLIENTÓW.">Nieefektywny</span>';
                    } else if (cr < 4) {
                        statusHtml = '<span class="cs-badge cs-badge-warn" data-tooltip="PRODUKT MA POTENCJAŁ, ALE COŚ BLOKUJE KLIENTA (NP. BALSAM LUB OSTRZA WOMAN).">Do poprawy</span>';
                    } else if (cr <= 7) {
                        statusHtml = '<span class="cs-badge cs-badge-info" data-tooltip="STABILNY DODATEK. DOBRY WYNIK DLA NOWYCH PRODUKTÓW LUB SPECYSFICZNYCH AKCESORIÓW (NP. CROWNER).">Optymalny</span>';
                    } else {
                        statusHtml = '<span class="cs-badge cs-badge-good" data-tooltip="PRODUKT KOMPLETNY. TRAFIA W POTRZEBY WIĘKSZOŚCI KLIENTÓW (ZARÓWNO HANDLER CZARNY, JAK I ZŁOTY).">Bestseller</span>';
                    }
                }

                var actionsHtml = '<span class="dashicons dashicons-no-alt cs-delete-stat" data-tooltip="Usuń trwale te statystyki" data-uid="' + rUid + '" style="color:#d63638; cursor:pointer; font-size:18px;"></span>';

                tbody += '<tr>';
                tbody += '<td data-label="Reguła / Produkt">' + nameHtml + triggerInfo + '</td>';
                tbody += '<td data-label="Wyświetlenia">' + d.i + '</td>';
                tbody += '<td data-label="Kliknięcia">' + d.c + '</td>';
                tbody += '<td data-label="Sprzedaż">' + d.s + '</td>';
                tbody += '<td data-label="Przychód">' + d.r.toFixed(2) + ' zł</td>';
                tbody += '<td data-label="CR">' + cr + '%</td>';
                tbody += '<td data-label="Status">' + statusHtml + '</td>';
                tbody += '<td data-label="Akcje" style="text-align: center;">' + actionsHtml + '</td>';
                tbody += '</tr>';
            });

            if (tbody === '') {
                tbody = '<tr><td colspan="8">Brak danych sprzedażowych w wybranym okresie.</td></tr>';
            }

            $('#cs-table-body').html(tbody);
        }

        $('#cs-date-filter').on('change', function() {
            $('#cs-specific-date').val(''); 
            renderDashboard(parseInt($(this).val()));
        });
        
        $('#cs-specific-date').on('change', function() {
            if($(this).val()) {
                $('#cs-date-filter').val('9999'); 
                renderDashboard(null, $(this).val());
            }
        });

        $(document).on('click', '.cs-delete-stat', function() {
            if(confirm('UWAGA! Czy na pewno chcesz trwale usunąć statystyki przypisane do tej reguły? Tej operacji nie można cofnąć.')) {
                var uid = $(this).data('uid');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: { action: 'cs_delete_analytic_data', rule_uid: uid },
                    success: function(res) {
                        if(res.success) location.reload();
                    }
                });
            }
        });

        $(document).on('click', '.cs-edit-icon', function() {
            var $container = $(this).closest('.cs-rule-name-container');
            $(this).hide();
            $container.find('.cs-name-display').hide();
            $container.find('.cs-analytic-name-input').show().focus();
        });

        $(document).on('keypress', '.cs-analytic-name-input', function(e) {
            if(e.which == 13) {
                e.preventDefault();
                $(this).blur();
            }
        });

        $(document).on('blur', '.cs-analytic-name-input', function() {
            var $input = $(this);
            var rUid = $input.data('uid');
            var formIdx = $input.data('form-index');
            var newName = $input.val().trim();
            var baseName = $input.attr('placeholder');
            var $container = $input.closest('.cs-rule-name-container');
            
            var nameToDisplay = newName === '' ? baseName : newName;
            
            $input.hide();
            $container.find('.cs-name-display').text(nameToDisplay).show();
            $container.find('.cs-edit-icon').show();

            if (formIdx !== null) {
                $('input[name="wc_cs_rules[' + formIdx + '][analytic_name]"]').val(newName);
            }

            if (ruleNames[rUid] && ruleNames[rUid].analytic === newName) return;

            $container.find('.cs-name-display').css('opacity', '0.5');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'cs_save_analytic_name',
                    rule_uid: rUid,
                    analytic_name: newName
                },
                success: function(response) {
                    $container.find('.cs-name-display').css('opacity', '1');
                    if(response.success) {
                        ruleNames[rUid].analytic = newName;
                        $container.find('.cs-name-display').css('color', '#0f5132');
                        setTimeout(function(){ 
                            $container.find('.cs-name-display').css('color', ''); 
                        }, 1000);
                    }
                }
            });
        });

        function handleLayoutModeChange() {
            if ($('select[name="wc_cs_layout_mode"]').val() === 'grid') {
                $('#wc-cs-form').addClass('cs-layout-is-grid').removeClass('cs-layout-is-carousel');
                $('.cs-global-sub-header-row').show();
            } else {
                $('#wc-cs-form').removeClass('cs-layout-is-grid').addClass('cs-layout-is-carousel');
                $('.cs-global-sub-header-row').hide();
            }
        }
        
        $('select[name="wc_cs_layout_mode"]').on('change', handleLayoutModeChange);
        handleLayoutModeChange();

        $('.cs-nav-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            $('.cs-nav-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            $('.cs-tab-content').hide();
            
            var target = $(this).attr('href');
            $(target).fadeIn(200);
            
            if (target === '#tab-analysis') {
                if($('#cs-specific-date').val()) {
                    renderDashboard(null, $('#cs-specific-date').val());
                } else {
                    renderDashboard(parseInt($('#cs-date-filter').val()));
                }
            }

            localStorage.setItem('wc_cs_active_tab', target);
        });

        var activeTab = localStorage.getItem('wc_cs_active_tab');
        if (activeTab && $(activeTab).length) {
            $('.cs-nav-tabs .nav-tab[href="' + activeTab + '"]').click();
        } else {
            renderDashboard(30);
        }

        var ruleIndex = <?php echo count($saved_rules); ?>;
        $(document).on('click', '.postbox-header', function(e) { 
            if($(e.target).closest('.button-link').length > 0) return;
            $(this).closest('.postbox').toggleClass('closed'); 
        });

        $('#wc-cs-add-rule').on('click', function() {
            var newUid = 'cs_' + Math.random().toString(36).substr(2, 9);
            var template = $('#wc-cs-rule-template').html().replace(/{{INDEX}}/g, ruleIndex).replace(/{{UID}}/g, newUid);
            $('#wc-cs-rules-wrapper').append(template);
            $(document.body).trigger('wc-enhanced-select-init');
            ruleIndex++;
        });
        
        $(document).on('change', '.generate-checkbox', function() {
            var $container = $(this).closest('td').find('.generate-attr-container');
            if(this.checked) $container.show(); else $container.hide();
        });

        $(document).on('click', '.wc-cs-clone-rule', function(e) {
            e.stopPropagation();
            var $card = $(this).closest('.wc-cs-rule-card');
            var oldIndex = $card.attr('data-index');
            var newIndex = ruleIndex;
            var newUid = 'cs_' + Math.random().toString(36).substr(2, 9);
            var $clone = $card.clone();
            
            $clone.attr('data-index', newIndex);
            var regex = new RegExp('wc_cs_rules\\[' + oldIndex + '\\]', 'g');
            $clone.find('[name]').each(function() {
                var name = $(this).attr('name');
                if(name) { $(this).attr('name', name.replace(regex, 'wc_cs_rules[' + newIndex + ']')); }
            });
            
            $clone.find('.select2-container').remove();
            $clone.find('select').removeClass('select2-hidden-accessible enhanced').removeAttr('data-select2-id tabindex aria-hidden');
            $clone.find('option').removeAttr('data-select2-id');
            $clone.find('input[name$="[sales_count]"]').val(0);
            $clone.find('input[name$="[sales_revenue]"]').val(0);
            $clone.find('input[name$="[analytic_name]"]').val(''); 
            $clone.find('input[name$="[uid]"]').val(newUid); 
            $clone.find('.cs-header-stats').text('Sprzedano: 0 | Przychód: 0,00 zł');
            $('#wc-cs-rules-wrapper').append($clone);
            $(document.body).trigger('wc-enhanced-select-init');
            ruleIndex++;
        });
        
        $(document).on('click', '.wc-cs-remove-rule', function(e) {
            e.stopPropagation();
            if(confirm('Usunąć? (Statystyki wciąż będą widoczne w zakładce Analiza, dopóki ich tam nie wyczyścisz)')) $(this).closest('.wc-cs-rule-card').remove();
        });
        
        $(document).on('change', '.wc-cs-product-select', function() {
            var $select = $(this), product_id = $select.val(), $card = $select.closest('.postbox');
            if (product_id) {
                $card.find('.rule-title-text strong').text($select.find('option:selected').text());
                $.ajax({
                    url: ajaxurl,
                    data: { action: 'get_wc_cs_product_data', product_id: product_id },
                    success: function(response) {
                        if (response.success) {
                            var $titleInput = $card.find('.wc-cs-title-input');
                            if (!$titleInput.val() && response.data.name) { $titleInput.val(response.data.name); }
                            
                            if (!$card.find('.wc_cs_custom_image').val() && response.data.url) {
                                $card.find('.wc_cs_custom_image').val(response.data.url);
                                $card.find('.wc_cs_image_preview').html('<img src="'+response.data.url+'" style="max-width:100px; display:block; margin-bottom:5px;">');
                            }
                            
                            // Wyłączone automatyczne wypełnianie cen (żeby działały dynamicznie z produktu, jeśli pole jest puste)
                            // var $priceRegInput = $card.find('.wc-cs-price-reg');
                            // var $pricePromoInput = $card.find('input[name$="[price_promo]"]');
                            // if (!$priceRegInput.val() && response.data.regular_price) { $priceRegInput.val(response.data.regular_price); }
                            // if (!$pricePromoInput.val() && response.data.sale_price) { $pricePromoInput.val(response.data.sale_price); }
                        }
                    }
                });
            }
        });
        
        $(document).on('click', '.wc_cs_upload_image_btn', function(e) {
            e.preventDefault();
            var $btn = $(this), $card = $btn.closest('.wc-cs-rule-card, .wc-cs-promo-set-card');
            var frame = wp.media({ title: 'Wybierz zdjęcie', multiple: false }).on('select', function() {
                var url = frame.state().get('selection').first().toJSON().url;
                $card.find('.wc_cs_custom_image').val(url);
                $card.find('.wc_cs_image_preview').html('<img src="'+url+'" style="max-width:100px; margin-bottom:5px;">');
            }).open();
        });

        var gratisIndex = <?php echo count($saved_gratis); ?>;
        
        $('#wc-cs-add-gratis').on('click', function() {
            var template = $('#wc-cs-gratis-template').html().replace(/{{INDEX}}/g, gratisIndex);
            $('#gratis-rules-wrapper').append(template);
            $(document.body).trigger('wc-enhanced-select-init');
            gratisIndex++;
        });

        $(document).on('click', '.wc-cs-remove-gratis', function(e) {
            e.stopPropagation();
            if(confirm('Usunąć ten gratis?')) $(this).closest('.wc-cs-gratis-card').remove();
        });

        $(document).on('change', '.wc-cs-gratis-select', function() {
            var $select = $(this), product_id = $select.val(), $card = $select.closest('.postbox');
            if (product_id) {
                $card.find('.rule-title-text strong').text($select.find('option:selected').text());
            }
        });

        $(document).on('change', '.cs-gratis-trigger-type', function() {
            var $card = $(this).closest('.inside');
            if ($(this).val() === 'amount') {
                $card.find('.gratis-amount-row').show();
                $card.find('.gratis-product-row').hide();
            } else {
                $card.find('.gratis-amount-row').hide();
                $card.find('.gratis-product-row').show();
            }
        });
        
        // ZESTAWY PROMOCYJNE
        var promoSetIndex = <?php echo count($saved_promo_sets); ?>;
        
        $('#wc-cs-add-promo-set').on('click', function() {
            var template = $('#wc-cs-promo-set-template').html().replace(/{{INDEX}}/g, promoSetIndex);
            $('#promo-sets-wrapper').append(template);
            $(document.body).trigger('wc-enhanced-select-init');
            promoSetIndex++;
        });

        $(document).on('click', '.wc-cs-remove-promo-set', function(e) {
            e.stopPropagation();
            if(confirm('Usunąć ten zestaw promocyjny?')) $(this).closest('.wc-cs-promo-set-card').remove();
        });

        $(document).on('change', '.wc-cs-promo-set-target-select', function() {
            var $select = $(this), product_id = $select.val(), $card = $select.closest('.postbox');
            if (product_id) {
                $card.find('.rule-title-text strong').text($select.find('option:selected').text());
                $.ajax({
                    url: ajaxurl,
                    data: { action: 'get_wc_cs_product_data', product_id: product_id },
                    success: function(response) {
                        if (response.success) {
                            var $titleInput = $card.find('.wc-cs-promo-set-title-input');
                            if (!$titleInput.val() && response.data.name) { $titleInput.val(response.data.name); }
                            
                            if (!$card.find('.wc_cs_custom_image').val() && response.data.url) {
                                $card.find('.wc_cs_custom_image').val(response.data.url);
                                $card.find('.wc_cs_image_preview').html('<img src="'+response.data.url+'" style="max-width:150px; display:block; margin-bottom:5px;">');
                            }
                            
                            // Wyłączone automatyczne wypełnianie cen (żeby działały dynamicznie z produktu, jeśli pole jest puste)
                            // var $priceRegInput = $card.find('.wc-cs-promo-set-price-reg');
                            // var $pricePromoInput = $card.find('input[name$="[price_promo]"]');
                            // if (!$priceRegInput.val() && response.data.regular_price) { $priceRegInput.val(response.data.regular_price); }
                            // if (!$pricePromoInput.val() && response.data.sale_price) { $pricePromoInput.val(response.data.sale_price); }
                        }
                    }
                });
            }
        });
        
    });
    </script>
    <?php
}

add_action('admin_enqueue_scripts', function($hook) {
    if ('product_page_cross-sell-koszyk' === $hook) {
        wp_enqueue_media(); 
        wp_enqueue_script('wc-enhanced-select');
        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css');
    }
});

add_action('wp_ajax_get_wc_cs_product_data', function() {
    $product_id = intval($_GET['product_id']);
    $product = wc_get_product($product_id);
    if ($product) {
        $image_id = $product->get_image_id();
        if ( ! $image_id && $product->is_type( 'variation' ) ) {
            $image_id = wc_get_product( $product->get_parent_id() )->get_image_id();
        }
        $url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        if (empty($regular_price)) { $regular_price = $product->get_price(); }
        $name = $product->get_name(); 
        wp_send_json_success(['url' => $url, 'regular_price' => $regular_price, 'sale_price' => $sale_price, 'name' => $name]);
    } else {
        wp_send_json_error();
    }
});
// KONIEC CROSSELL + GRATIS W KOSZYKU
?>