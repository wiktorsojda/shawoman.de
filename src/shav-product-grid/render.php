<?php
$mainTitle = isset($attributes['mainTitle']) ? $attributes['mainTitle'] : '';
$subTitle = isset($attributes['subTitle']) ? $attributes['subTitle'] : '';
$categoryId = isset($attributes['categoryId']) ? $attributes['categoryId'] : '';
$limit = isset($attributes['limit']) ? (int) $attributes['limit'] : 12;

$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => $limit,
    'orderby' => 'menu_order',
    'order' => 'ASC',
);

if (!empty($categoryId)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $categoryId,
        ),
    );
}

$query = new WP_Query($args);

if (!$query->have_posts()) {
    return;
}

$title_class = 'shop-section--' . sanitize_html_class($categoryId ? $categoryId : 'default');
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
    <section class="shop-section <?php echo esc_attr($title_class); ?>">
        <?php if (!empty($mainTitle) || !empty($subTitle)) : ?>
            <header class="shop-section__header">
                <h2 class="shop-section__title">
                    <?php if (!empty($mainTitle)) : ?>
                        <span class="shop-section__title-main"><?php echo esc_html($mainTitle); ?></span>
                    <?php endif; ?>
                    
                    <?php if (!empty($subTitle)) : ?>
                        <span class="shop-section__title-brand"><?php echo esc_html($subTitle); ?></span>
                    <?php endif; ?>
                </h2>
            </header>
        <?php endif; ?>

        <ul class="shop-section__grid products columns-3">
            <?php while ($query->have_posts()) :
                $query->the_post();
                wc_get_template_part('content', 'product');
            endwhile; ?>
        </ul>
    </section>
</div>
<?php wp_reset_postdata(); ?>
