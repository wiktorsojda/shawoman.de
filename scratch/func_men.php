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
        .my-cart-global-sub-header { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #ac0000; margin-top: -10px; margin-bottom: 20px; letter-spacing: 0.8px; padding-left: 2%; }
        
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
        .cs-carousel-nav-arrow:hover { color: #0883A0; background: transparent; transform: translateY(-50%) scale(1.15); }
        .cs-nav-prev { left: 3%; } 
        .cs-nav-next { right: 3%; } 
        @media (max-width: 768px) { .cs-carousel-nav-arrow { display: none !important; } } 
        
        /* WSPÓLNE STYLE DLA KARUZELI I SIATKI */
        .my-cart-element { background: transparent; border: 1px solid rgba(0,0,0,.1); border-radius: 4px; padding: 0; position: relative; max-width: 100%; overflow: hidden; box-sizing: border-box; height: 100%; }

        .my-cart-product-img { display: flex; align-items: center; justify-content: center; padding: 20px; background: transparent; position: relative; }
        .my-cart-product-img::after { content: ""; position: absolute; right: 0; top: 20px; bottom: 20px; width: 1px; background: rgba(0,0,0,0.05); z-index: 1; }
        .my-cart-product-img img { max-width: 100%; max-height: 130px; height: auto; object-fit: contain; transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); backface-visibility: hidden; -webkit-font-smoothing: subpixel-antialiased; will-change: transform; }
        .my-cart-element:hover .my-cart-product-img img { transform: scale(1.08); }
        .my-cart-product-title { font-size: 17px; font-weight: 700; margin-bottom: 6px; color: #000; }
        .my-cart-product-attribute { font-size: 13px; color: #666; margin-bottom: 5px; }
        .my-cart-product-price { margin-top: 15px; font-size: 14px; }
        .my-cart-product-price del { color: #999; margin-right: 8px; text-decoration: none; }
        .my-cart-product-price span { font-size: 19px; font-weight: 700; color: #000; }
        
        .my-cart-add-to-cart-button .button { background-color: #000 !important; color: #fff !important; border-radius: 4px !important; padding: 12px 28px !important; font-weight: 700 !important; text-transform: uppercase !important; font-size: 12px !important; letter-spacing: 1px; text-decoration: none !important; border: none !important; transition: all 0.2s ease !important; transform: scale(1.02); display: block; cursor: pointer; box-sizing: border-box !important; margin: 0; }
        .my-cart-add-to-cart-button .button:hover { background-color: #ac0000 !important; transform: scale(1.05); text-decoration: none !important; }
        
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
                scrollbar-color: #ac0000 #e5e5e5 !important;
                align-items: stretch !important;
            }

            /* PASEK PROGRESU (SCROLLBAR) */
            .cs-layout-carousel::-webkit-scrollbar, .cs-layout-grid::-webkit-scrollbar { display: block !important; height: 6px !important; }
            .cs-layout-carousel::-webkit-scrollbar-thumb, .cs-layout-grid::-webkit-scrollbar-thumb { background: #ac0000 !important; border-radius: 4px; }
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
        .my-cart-element::before { content: ""; position: absolute; top: 0; left: 0; width: 0; height: 2px; background: #ac0000; z-index: 10; transition: none; }
        .my-cart-element.is-loading::before { width: 100%; transition: width 4s linear; }

        /* FIX: BADGE OSZCZĘDNOŚCI Z PRAWEJ STRONY */
        .cs-savings-badge { position: absolute; top: 15px; right: 15px; left: auto; background: #ac0000; color: #fff; padding: 4px 8px; font-size: 11px; font-weight: 700; border-radius: 4px; z-index: 5; text-transform: uppercase; pointer-events: none; }
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

                $title        = ! empty($selected_rule['title']) ? $selected_rule['title'] : $product->get_name();
                $attr_1       = isset($selected_rule['attr_1']) ? $selected_rule['attr_1'] : '';
                $attr_2       = isset($selected_rule['attr_2']) ? $selected_rule['attr_2'] : '';
                $price_reg    = isset($selected_rule['price_reg']) ? $selected_rule['price_reg'] : '';
                $price_promo  = isset($selected_rule['price_promo']) ? $selected_rule['price_promo'] : '';
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