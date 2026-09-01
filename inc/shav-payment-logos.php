<?php
/**
 * Frontend display logic for checkout / buybox payment logos.
 */

// Wyświetlanie logotypów płatności w koszyku i checkout (z zakładki Checkout / buybox)
function shav_display_payment_logos() {
    $payment_logos = get_option('shav_checkout_payment_logos', '');
    if (!empty($payment_logos)) {
        echo '<div class="shav-payment-logos" style="margin-top: 20px; margin-bottom: 20px; text-align: center; width: 100%;">';
        echo '<img src="' . esc_url($payment_logos) . '" alt="Payment methods" style="max-width: 100%; height: auto; max-height: 40px; display: inline-block;" />';
        echo '</div>';
    }
}
// Wyświetlanie pod przyciskiem add to cart na stronie produktu (buybox)
add_action('woocommerce_after_add_to_cart_form', 'shav_display_payment_logos', 20);
// Wyświetlanie w checkoucie (pod przyciskiem place order)
add_action('woocommerce_review_order_after_submit', 'shav_display_payment_logos', 10);
