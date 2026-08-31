<?php
/**
 * Shipping Methods Display
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 */

defined('ABSPATH') || exit;

$has_calculated_shipping = !empty($has_calculated_shipping);
$show_shipping_calculator = !empty($show_shipping_calculator);
$calculator_text = '';

// Get the chosen shipping method
$chosen_methods = WC()->session->get('chosen_shipping_methods');
$chosen_shipping = $chosen_methods ? $chosen_methods[0] : '';

// Get the shipping cost
$shipping_cost = WC()->cart->get_cart_shipping_total();

?>
<tr class="woocommerce-shipping-totals shipping">
	<th><?php esc_html_e('Versand', 'woocommerce'); ?></th>
	<td data-title="<?php esc_attr_e('Versand', 'woocommerce'); ?>">
		<?php if ($shipping_cost === 'Free!'): ?>
			<p><?php esc_html_e('Versand: Kostenlos', 'woocommerce'); ?></p>
		<?php else: ?>
			<p><?php echo sprintf(esc_html__('%s', 'woocommerce'), $shipping_cost); ?></p>
		<?php endif; ?>
	</td>
</tr>