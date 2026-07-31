<?php

/**
 * Variable product add to cart — override ShavWoman.
 *
 * Produkt wielowariantowy ma wygladac 1:1 jak prosty — box ceny bez zmian,
 * a warianty jako okragle swatche nad przyciskiem "Dodaj do koszyka" (Figma 1292:300).
 *
 * Natywna tabela .variations z selectami ZOSTAJE w DOM (ukryta w CSS) — na niej
 * operuje skrypt wc-add-to-cart-variation. Swatche tylko ustawiaja wartosc
 * selecta i triggeruja change (inc/shav-variations.js).
 *
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

defined('ABSPATH') || exit;

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

// Mapa: [attribute_pa_kolor][slug-opcji] => URL miniatury wariacji (obrazek swatcha)
$shav_swatch_images = array();
if (is_array($available_variations)) {
	foreach ($available_variations as $variation_data) {
		if (empty($variation_data['attributes']) || empty($variation_data['image'])) {
			continue;
		}
		$img = '';
		foreach (array('gallery_thumbnail_src', 'thumb_src', 'src') as $img_key) {
			if (!empty($variation_data['image'][$img_key])) {
				$img = $variation_data['image'][$img_key];
				break;
			}
		}
		if (!$img) {
			continue;
		}
		foreach ($variation_data['attributes'] as $attr_key => $attr_value) {
			if ('' === $attr_value || isset($shav_swatch_images[$attr_key][$attr_value])) {
				continue; // "" = opcja "dowolny" — nie da sie przypisac obrazka
			}
			$shav_swatch_images[$attr_key][$attr_value] = $img;
		}
	}
}

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?></p>
	<?php else : ?>

		<div class="shav-variations">
			<?php foreach ($attributes as $attribute_name => $options) : ?>
				<?php
				$attr_field   = 'attribute_' . sanitize_title($attribute_name);
				$selected_val = isset($_REQUEST[$attr_field])
					? wc_clean(wp_unslash($_REQUEST[$attr_field]))
					: $product->get_variation_default_attribute($attribute_name);

				// Opcje jako [slug => nazwa] (dla atrybutow globalnych bierzemy nazwy termow).
				// Obrazek swatcha — priorytet: 1) obrazek wgrany w atrybucie (term meta
				// `product_attribute_image` z pluginu woo-variation-swatches),
				// 2) miniatura wariacji, 3) brak -> tekstowy pill.
				$option_labels = array();
				$option_images = array();
				if (taxonomy_exists($attribute_name)) {
					$terms = wc_get_product_terms($product->get_id(), $attribute_name, array('fields' => 'all'));
					foreach ($terms as $term) {
						if (!in_array($term->slug, $options, true)) {
							continue;
						}
						$option_labels[$term->slug] = $term->name;
						$term_image_id = get_term_meta($term->term_id, 'product_attribute_image', true);
						if ($term_image_id) {
							$term_image_url = wp_get_attachment_image_url($term_image_id, 'thumbnail');
							if ($term_image_url) {
								$option_images[$term->slug] = $term_image_url;
							}
						}
					}
				} else {
					foreach ($options as $option) {
						$option_labels[$option] = $option;
					}
				}
				?>
				<div class="shav-variations__row" data-attribute="<?php echo esc_attr($attr_field); ?>">
					<p class="shav-variations__label">
						<span class="shav-variations__label-name"><?php echo wc_attribute_label($attribute_name); // WPCS: XSS ok. ?></span><span class="shav-variations__label-sep"<?php echo $selected_val ? '' : ' style="display:none"'; ?>> - </span><span class="shav-variations__label-value"><?php echo esc_html(isset($option_labels[$selected_val]) ? $option_labels[$selected_val] : ''); ?></span>
					</p>
					<div class="shav-variations__swatches">
						<?php foreach ($option_labels as $option_slug => $option_label) : ?>
							<?php
							$swatch_img = '';
							if (!empty($option_images[$option_slug])) {
								$swatch_img = $option_images[$option_slug];
							} elseif (isset($shav_swatch_images[$attr_field][$option_slug])) {
								$swatch_img = $shav_swatch_images[$attr_field][$option_slug];
							}
							?>
							<button type="button"
								class="shav-variations__swatch<?php echo $swatch_img ? '' : ' shav-variations__swatch--text'; ?><?php echo ((string) $option_slug === (string) $selected_val) ? ' is-selected' : ''; ?>"
								data-value="<?php echo esc_attr($option_slug); ?>"
								data-label="<?php echo esc_attr($option_label); ?>"
								aria-label="<?php echo esc_attr(wc_attribute_label($attribute_name) . ': ' . $option_label); ?>">
								<?php if ($swatch_img) : ?>
									<img src="<?php echo esc_url($swatch_img); ?>" alt="<?php echo esc_attr($option_label); ?>">
								<?php else : ?>
									<span><?php echo esc_html($option_label); ?></span>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php // Natywne selecty WooCommerce — ukryte w CSS, obsluguje je wc-add-to-cart-variation ?>
		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ($attributes as $attribute_name => $options) : ?>
					<tr>
						<th class="label"><label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name); // WPCS: XSS ok. ?></label></th>
						<td class="value">
							<?php
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
								)
							);
							echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__('Clear options', 'woocommerce') . '">' . esc_html__('Clear', 'woocommerce') . '</a>')) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action('woocommerce_after_variations_table'); ?>

		<div class="single_variation_wrap">
			<?php
			/**
			 * Hook: woocommerce_before_single_variation.
			 */
			do_action('woocommerce_before_single_variation');

			/**
			 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
			 *
			 * @since 2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action('woocommerce_single_variation');

			/**
			 * Hook: woocommerce_after_single_variation.
			 */
			do_action('woocommerce_after_single_variation');
			?>
		</div>
	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php
do_action('woocommerce_after_add_to_cart_form');
