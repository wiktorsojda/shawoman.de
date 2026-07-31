<?php
/**
 * Karta produktu na liscie sklepu (/sklep/).
 * Layout wg Figmy "Sklep - desktop - v2" (898:1595, 898:1634).
 *
 * Markup BEM:
 *   .product-card[--frame-{variant}]
 *     ├ .product-card__frame-stripe        (top 16px gradient — tylko gdy frame_variant)
 *     ├ .product-card__badges              (NOWOSC / BESTSELLER / RABAT)
 *     ├ .product-card__media (<a>)
 *     │   └ .product-card__image (<img>)
 *     └ .product-card__body
 *         ├ .product-card__title           ("Golarka damska" + brand span)
 *         ├ .product-card__subtitle
 *         └ .product-card__pricing
 *             ├ .product-card__savings     (zielony pill "OSZCZEDZASZ X zl")
 *             └ .product-card__price
 *                 ├ .product-card__price--old
 *                 └ .product-card__price--new
 *
 * Hooki WC sa wylaczone w tej szablonie — kazdy element renderujemy
 * jawnie. Stare display_custom_shop_image i display_product_title_and_subtitle_shop
 * w functions.php zostaja dla wstecznej kompatybilnosci ale nie sa wolane.
 *
 * @package ShavWoman
 */

defined('ABSPATH') || exit;

global $product;
if (empty($product) || !$product->is_visible()) {
    return;
}

$product_id   = $product->get_id();
$permalink    = get_permalink($product_id);
$title        = get_the_title();
$subtitle     = $product->get_meta('product_subtitle');
$shop_image   = $product->get_meta('product_shop_image');
$thumb_id     = $product->get_image_id();
$image_url    = $shop_image ?: ($thumb_id ? wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail') : wc_placeholder_img_src());

$frame_variant = function_exists('shav_get_product_frame_variant')
    ? shav_get_product_frame_variant($product_id)
    : '';

// Klasy karty
$classes = ['product-card'];
if ($frame_variant) {
    $classes[] = 'product-card--frame-' . $frame_variant;
}

// Konwencja z Figmy:
//   - Urzadzenia/Akcesoria (frame_variant pusty): badge w rogu (NOWOSC/BESTSELLER/wlasny),
//     BEZ pill OSZCZEDZASZ przy cenie
//   - Zestawy (frame_variant ustawione): pill OSZCZEDZASZ przy cenie, BEZ badge w rogu
//
// Priorytet badge'a: NOWOSC > BESTSELLER > wlasny (tekst + kolory z meta).
$is_set = !empty($frame_variant);

$badge_label  = '';
$badge_kind   = '';
$badge_style  = ''; // inline-style dla wlasnego badge'a
$show_savings = false;

if ($is_set) {
    $show_savings = $product->is_on_sale();
} else {
    if ($product->get_meta('_shav_badge_nowosc') === 'yes') {
        $badge_label = __('NOWOŚĆ', 'shav');
        $badge_kind  = 'new';
    } elseif ($product->get_meta('_shav_badge_bestseller') === 'yes') {
        $badge_label = __('BESTSELLER', 'shav');
        $badge_kind  = 'bestseller';
    } else {
        $custom_text  = (string) $product->get_meta('_shav_badge_custom_text');
        if ($custom_text !== '') {
            $badge_label  = $custom_text;
            $badge_kind   = 'custom';
            $custom_bg    = (string) $product->get_meta('_shav_badge_custom_bg');
            $custom_color = (string) $product->get_meta('_shav_badge_custom_color');
            $styles = [];
            if ($custom_bg) {
                $styles[] = 'background:' . esc_attr($custom_bg);
                $styles[] = 'background-image:none';
            }
            if ($custom_color) {
                $styles[] = 'color:' . esc_attr($custom_color);
            }
            if ($styles) {
                $badge_style = ' style="' . implode(';', $styles) . '"';
            }
        }
    }
}

// Ceny — produkt wielowariantowy nie ma wlasnych cen (get_regular_price()
// zwraca pusty string), wiec bierzemy min z wariacji.
$price_html    = '';
$is_on_sale    = $product->is_on_sale();
if ($product->is_type('variable')) {
    $regular_price = (float) $product->get_variation_regular_price('min', true);
    $current_price = (float) $product->get_variation_price('min', true);
} else {
    $regular_price = wc_get_price_to_display($product, ['price' => $product->get_regular_price()]);
    $current_price = wc_get_price_to_display($product);
}
$savings_amount = 0;
if ($show_savings && $is_on_sale && $regular_price > 0 && $current_price < $regular_price) {
    $savings_amount = $regular_price - $current_price;
}
?>
<li <?php wc_product_class(implode(' ', $classes), $product); ?>>
    <?php if ($frame_variant) : ?>
        <span class="product-card__frame-stripe" aria-hidden="true"></span>
    <?php endif; ?>

    <?php if ($badge_label) : ?>
        <div class="product-card__badges">
            <span class="product-card__badge product-card__badge--<?php echo esc_attr($badge_kind); ?>"<?php echo $badge_style; ?>>
                <?php echo esc_html($badge_label); ?>
            </span>
        </div>
    <?php endif; ?>

    <a class="product-card__media" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>">
        <img
            class="product-card__image"
            src="<?php echo esc_url($image_url); ?>"
            alt="<?php echo esc_attr($title); ?>"
            loading="lazy"
            width="350"
            height="350"
        />
    </a>

    <div class="product-card__body">
        <a class="product-card__title-link" href="<?php echo esc_url($permalink); ?>">
            <h3 class="product-card__title">
                <?php
                // Pierwszy wyraz w neutral-800, "Shav Woman" (lub reszta) w neutral-500.
                // Konwencja: jezeli tytul zawiera "Shav Woman" — split tam,
                // inaczej caly tytul w glownym kolorze.
                if (stripos($title, 'Shav Woman') !== false) {
                    [$main, $brand] = array_map('trim', preg_split('/\s*shav woman\s*/i', $title, 2));
                    echo '<span class="product-card__title-main">' . esc_html(trim($main)) . ' </span>';
                    echo '<span class="product-card__title-brand">Shav Woman</span>';
                    if ($brand) {
                        echo ' <span class="product-card__title-main">' . esc_html($brand) . '</span>';
                    }
                } else {
                    echo '<span class="product-card__title-main">' . esc_html($title) . '</span>';
                }
                ?>
            </h3>
        </a>

        <?php if ($subtitle) : ?>
            <p class="product-card__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <div class="product-card__pricing">
            <?php if ($savings_amount > 0) : ?>
                <span class="product-card__savings">
                    <span class="product-card__savings-label"><?php esc_html_e('OSZCZĘDZASZ', 'shav'); ?></span>
                    <span class="product-card__savings-amount"><?php echo wp_kses_post(wc_price($savings_amount)); ?></span>
                </span>
            <?php endif; ?>

            <div class="product-card__price">
                <?php if ($is_on_sale && $regular_price > 0 && $current_price < $regular_price) : ?>
                    <span class="product-card__price--old"><?php echo wp_kses_post(wc_price($regular_price)); ?></span>
                    <span class="product-card__price--new"><?php echo wp_kses_post(wc_price($current_price)); ?></span>
                <?php else : ?>
                    <span class="product-card__price--single"><?php echo wp_kses_post(wc_price($current_price)); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
