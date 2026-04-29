<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<!-- <#?php if ( ! WC()->cart->is_empty() ) : ?> -->
    <div>


        <div class="cart-details">
            <ul class="cart-products">
                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>
                        <li class="cart-product">
                            <div class="product-thumbnail">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                if (!$product_permalink) {
                                    echo $thumbnail;
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                }
                                ?>
                            </div>
                            <div class="product-details">
                                <div class="product-details-sub">
                                    <span class="product-name"><?php echo $_product->get_name(); ?></span>
                                    <span class="product-subtotal"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?></span>
                                </div>

                                <!-- Quantity Input Field -->
                                <div class="product-quantity">
                                <span class="product-quantity"><?php echo $cart_item['quantity']; ?></span>
                                </div>
                                <!-- <div class="product-quantity">
                                    <input type="number" name="cart[<#?php echo $cart_item_key; ?>][qty]" value="<#?php echo esc_attr($cart_item['quantity']); ?>" min="1" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">
                                </div> -->
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>

            <div class="cart-text-subtotal">
                <span>Do zapłaty:</span>
                <?php if ($_product && $_product instanceof WC_Product): ?>
                    <span class="product-subtotal"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?></span>
                <?php else: ?>
                    <span class="product-subtotal">Brak produktów</span>
                <?php endif; ?>
            </div>
            <div class="checkout-button-parent">
                <a class="button checkout-button" href="<?php echo wc_get_cart_url(); ?>">Zobacz koszyk</a>
            </div>
        </div>
    </div>
<!-- <#?php else : ?>
    <p class="woocommerce-mini-cart__empty-message"><#?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p> -->
<!-- <#?php endif; ?> -->

<?php do_action('woocommerce_after_mini_cart'); ?>