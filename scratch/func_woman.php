function custom_element_inside_cart_left_column() {
    if ( ! is_cart() || is_admin() ) {
        return;
    }

    $rules = get_option( 'wc_cs_rules', array() );
    if ( empty( $rules ) || ! is_array( $rules ) ) {
        return;
    }

    $cart = WC()->cart->get_cart();
    $cart_product_ids = array();
    foreach ( $cart as $cart_item ) {
        $cart_product_ids[] = $cart_item['product_id'];
        if ( ! empty( $cart_item['variation_id'] ) ) {
            $cart_product_ids[] = $cart_item['variation_id']; 
        }
    }

    $selected_rule = null;
    $selected_rule_index = null; 
    $matched_trigger_id = null; 
    $global_fallback = null;
    $global_fallback_index = null;

    foreach ( $rules as $index => $rule ) {
        if ( empty( $rule['product_id'] ) ) continue;
        if ( in_array( $rule['product_id'], $cart_product_ids ) ) continue;

        $target_ids = isset( $rule['target_ids'] ) && is_array( $rule['target_ids'] ) ? $rule['target_ids'] : array();

        $intersect = array_intersect( $cart_product_ids, $target_ids );
        if ( count( $intersect ) > 0 ) {
            $selected_rule = $rule;
            $selected_rule_index = $index;
            $matched_trigger_id = reset($intersect); 
            break; 
        }

        if ( is_null($global_fallback) && isset( $rule['is_global'] ) && $rule['is_global'] === 'yes' ) {
            $global_fallback = $rule;
            $global_fallback_index = $index;
        }
    }

    if ( empty( $selected_rule ) && ! empty( $global_fallback ) ) {
        $selected_rule = $global_fallback;
        $selected_rule_index = $global_fallback_index;
    }

    if ( empty( $selected_rule ) ) return;

    $cross_sell_product_id = $selected_rule['product_id'];
    $selected_attr = isset($selected_rule['sync_attr']) ? $selected_rule['sync_attr'] : '';
    
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
    if ( ! $product ) return;

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

    $header_main  = ! empty( $selected_rule['header_main'] ) ? $selected_rule['header_main'] : '';
    $header_sub   = ! empty( $selected_rule['header_sub'] ) ? $selected_rule['header_sub'] : '';
    $title        = ! empty( $selected_rule['title'] ) ? $selected_rule['title'] : $product->get_name();
    $attr_1       = isset( $selected_rule['attr_1'] ) ? $selected_rule['attr_1'] : '';
    $attr_2       = isset( $selected_rule['attr_2'] ) ? $selected_rule['attr_2'] : '';
    $price_reg    = isset( $selected_rule['price_reg'] ) ? $selected_rule['price_reg'] : '';
    $price_promo  = isset( $selected_rule['price_promo'] ) ? $selected_rule['price_promo'] : '';
    $custom_img   = isset( $selected_rule['custom_image'] ) ? $selected_rule['custom_image'] : '';
    
    // Przekazanie poprawnego UID (lub awaryjnie indexu) do analityki
    $rule_uid_to_log = isset($selected_rule['uid']) ? $selected_rule['uid'] : $selected_rule_index;
    
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

    ?>
    <style>
        .my-cart-element-container { margin: 50px 0; font-family: inherit; padding-top: 0; }
        .my-cart-element-container-header { font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; color: #000; }
        .my-cart-element { background: transparent; border: 1px solid #f2f2f2; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 0; position: relative; max-width: 700px; overflow: hidden; }
        .my-cart-text-header { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #C68E7A; padding: 20px 25px 12px 25px; letter-spacing: 0.8px; }
        .my-cart-items { display: grid; grid-template-columns: 180px 1fr; align-items: stretch; }
        .my-cart-product-img { display: flex; align-items: center; justify-content: center; padding: 20px; background: transparent; position: relative; }
        .my-cart-product-img::after { content: ""; position: absolute; right: 0; top: 20px; bottom: 20px; width: 1px; background: rgba(0,0,0,0.05); z-index: 1; }
        .my-cart-product-img img { max-width: 100%; max-height: 130px; height: auto; object-fit: contain; transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); backface-visibility: hidden; -webkit-font-smoothing: subpixel-antialiased; will-change: transform; }
        .my-cart-element:hover .my-cart-product-img img { transform: scale(1.08); }
        .my-cart-product-info { padding: 10px 25px 25px 25px; display: flex; flex-direction: column; justify-content: center; }
        .my-cart-product-title { font-size: 17px; font-weight: 700; margin-bottom: 6px; color: #000; }
        .my-cart-product-attribute { font-size: 13px; color: #666; margin-bottom: 5px; }
        .my-cart-product-price { margin-top: 15px; font-size: 14px; }
        .my-cart-product-price del { color: #999; margin-right: 8px; text-decoration: none; }
        .my-cart-product-price span { font-size: 19px; font-weight: 700; color: #000; }
        .my-cart-add-to-cart-button { position: absolute; bottom: 25px; right: 25px; }
        .my-cart-add-to-cart-button .button { background-color: #1a1a1a !important; color: #fff !important; border-radius: 8px !important; padding: 12px 28px !important; font-weight: 700 !important; text-transform: uppercase !important; font-size: 12px !important; letter-spacing: 1px; text-decoration: none !important; border: none !important; transition: all 0.3s ease !important; transform: scale(1.02); display: block; cursor: pointer; }
        .my-cart-add-to-cart-button .button:hover { background-color: #C68E7A !important; transform: scale(1.05); text-decoration: none !important; }
        @media (max-width: 600px) {
            .my-cart-items { grid-template-columns: 1fr; }
            .my-cart-product-img::after { display: none; }
            .my-cart-product-img { border-bottom: 1px solid rgba(0,0,0,0.05); }
            .my-cart-add-to-cart-button { position: relative; bottom: 0; right: 0; margin-top: 20px; padding: 0 25px 25px 25px; }
            .my-cart-add-to-cart-button .button { width: 100%; text-align: center; }
        }
        .my-cart-element.is-loading { opacity: 0.6; pointer-events: none; transition: opacity 0.3s ease; }
        .my-cart-add-to-cart-button .button.loading { background-color: #666 !important; cursor: wait !important; }
        .my-cart-element::before { content: ""; position: absolute; top: 0; left: 0; width: 0; height: 2px; background: #C68E7A; z-index: 10; transition: none; }
        .my-cart-element.is-loading::before { width: 100%; transition: width 4s linear; }
    </style>

    <div class="my-cart-element-container" id="cross-sell-cart-container">
        <div class="my-cart-element-container-header"><?php echo esc_html( $header_main ); ?></div>
        <div class="my-cart-element">
            <div class="my-cart-header-container">
                <div class="my-cart-header"> 
                    <div class="my-cart-text-header"><?php echo esc_html( $header_sub ); ?></div>
                </div>
            </div>
            <div class="my-cart-items">
                <div class="my-cart-product-img">
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
    </div>
    <script>
    jQuery(document).ready(function($) {
        var logAjaxUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'cs_log_event' );
        var currentRuleUid = '<?php echo esc_js($rule_uid_to_log); ?>';

        if (currentRuleUid !== '') {
            $.post(logAjaxUrl, { rule_uid: currentRuleUid, type: 'impression' });
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