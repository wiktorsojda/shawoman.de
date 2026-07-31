<?php
/**
 * Archive Product — strona /sklep/ wg projektu Figma "Sklep - desktop - v2".
 *
 * Struktura:
 *   1. Header (FSE template-part)
 *   2. Banner sklepu (hook woocommerce_before_main_content @ 5)
 *   3. Lista sekcji (config: shav_get_shop_sections)
 *      każda sekcja = tytuł + grid kart produktow
 *   4. Footer (FSE template-part)
 *
 * Notatka: nie uzywamy domyslnej petli WC bo strona ma byc kuratorowana
 * po kategoriach (sekcjach), a nie paginowanym archiwum produktow.
 *
 * @package ShavWoman
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 * @hooked display_shop_banner_image_with_text - 5  (banner z meta master-product)
 */
do_action('woocommerce_before_main_content');

$shop_sections = shav_get_shop_sections();
?>

<div class="shop-archive">
    <?php foreach ($shop_sections as $section) :
        $query = shav_get_shop_section_query($section);
        if (!$query->have_posts()) {
            continue;
        }
        $title_class = 'shop-section--' . sanitize_html_class($section['category'] ?? 'default');
        ?>
        <section class="shop-section <?php echo esc_attr($title_class); ?>">
            <header class="shop-section__header">
                <h2 class="shop-section__title">
                    <span class="shop-section__title-main"><?php echo esc_html($section['title'] ?? ''); ?></span>
                    <?php if (!empty($section['brand_label'])) : ?>
                        <span class="shop-section__title-brand"><?php echo esc_html($section['brand_label']); ?></span>
                    <?php endif; ?>
                </h2>
            </header>

            <ul class="shop-section__grid products columns-3">
                <?php while ($query->have_posts()) :
                    $query->the_post();
                    wc_get_template_part('content', 'product');
                endwhile; ?>
            </ul>
        </section>
    <?php
    wp_reset_postdata();
    endforeach; ?>
</div>

<?php
do_action('woocommerce_after_main_content');

get_footer('shop');
