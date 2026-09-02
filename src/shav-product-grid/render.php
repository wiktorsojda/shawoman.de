<?php
$mainTitle = isset($attributes['mainTitle']) ? $attributes['mainTitle'] : '';
$subTitle = isset($attributes['subTitle']) ? $attributes['subTitle'] : '';
$accentWord = isset($attributes['accentWord']) ? $attributes['accentWord'] : '';
$selectionType = isset($attributes['selectionType']) ? $attributes['selectionType'] : 'category';
$categoryId = isset($attributes['categoryId']) ? $attributes['categoryId'] : '';
$productIds = isset($attributes['productIds']) ? $attributes['productIds'] : array();
$customCategoryOrder = isset($attributes['customCategoryOrder']) ? $attributes['customCategoryOrder'] : array();
$orderBy = isset($attributes['orderBy']) ? $attributes['orderBy'] : 'menu_order';
$limit = isset($attributes['limit']) ? (int) $attributes['limit'] : 12;

global $shav_grid_product_gradients;
$shav_grid_product_gradients = isset($attributes['productGradients']) ? $attributes['productGradients'] : array();

global $shav_grid_global_savings;
$shav_grid_global_savings = isset($attributes['globalSavingsPill']) ? $attributes['globalSavingsPill'] : false;

global $shav_grid_product_savings;
$shav_grid_product_savings = isset($attributes['productSavingsPills']) ? $attributes['productSavingsPills'] : array();
$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => $limit,
);

// Check if we are freezing the category manually
$isFrozenCategory = ($selectionType === 'category' && $orderBy === 'menu_order' && !empty($customCategoryOrder));

// Source Selection
if ($selectionType === 'manual' && !empty($productIds)) {
    $args['post__in'] = array_map('intval', $productIds);
    // If order is menu_order with specific post__in, preserve the array order
    if ($orderBy === 'menu_order') {
        $args['orderby'] = 'post__in';
    }
} elseif ($isFrozenCategory) {
    // If category is manually sorted in the editor, we use the saved customCategoryOrder
    $args['post__in'] = array_map('intval', $customCategoryOrder);
    $args['orderby'] = 'post__in';
} elseif ($selectionType === 'category' && !empty($categoryId)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $categoryId,
        ),
    );
}

// Sorting logic (only if not using post__in order)
if (!(($selectionType === 'manual' || $isFrozenCategory) && $orderBy === 'menu_order')) {
    if ($orderBy === 'date') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($orderBy === 'title') {
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
    } elseif ($orderBy === 'popularity') {
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } else {
        $args['orderby'] = 'menu_order title';
        $args['order'] = 'ASC';
    }
}

$query = new WP_Query($args);

if (!$query->have_posts()) {
    return;
}

$title_class = 'shop-section--' . sanitize_html_class($categoryId ? $categoryId : 'default');
if ($selectionType === 'manual') {
    $title_class = 'shop-section--manual';
}
?>

<div <?php echo get_block_wrapper_attributes(['class' => 'container--narrow2-important shop-archive']); ?>>
    <section class="shop-section <?php echo esc_attr($title_class); ?>">
        <?php if (!empty($mainTitle) || !empty($subTitle)) : ?>
            <header class="shop-section__header">
                <h2 class="shop-section__title">
                    <?php if (!empty($mainTitle)) : ?>
                        <span class="shop-section__title-main">
                            <?php
                            if ($accentWord && function_exists('shav_highlight_accent_word')) {
                                echo wp_kses_post(shav_highlight_accent_word($mainTitle, $accentWord, 'shop-section__title-accent'));
                            } else {
                                echo esc_html($mainTitle);
                            }
                            ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($subTitle)) : ?>
                        <span class="shop-section__title-brand">
                            <?php
                            if ($accentWord && function_exists('shav_highlight_accent_word')) {
                                echo wp_kses_post(shav_highlight_accent_word($subTitle, $accentWord, 'shop-section__title-accent'));
                            } else {
                                echo esc_html($subTitle);
                            }
                            ?>
                        </span>
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
