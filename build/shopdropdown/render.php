<?php
$buttonLabel         = isset($attributes['buttonLabel'])         ? $attributes['buttonLabel']         : 'Kup Teraz';
$buttonURL           = isset($attributes['buttonURL'])           ? $attributes['buttonURL']           : '/shop';
$categoryImage       = isset($attributes['categoryImage'])       ? $attributes['categoryImage']       : '';
$productsPerCategory = isset($attributes['productsPerCategory']) ? (int) $attributes['productsPerCategory'] : 4;
$excludeCategoryName = isset($attributes['excludeCategoryName']) ? $attributes['excludeCategoryName'] : 'Bez kategorii';

$image_inline_style = $categoryImage
    ? 'background-image:url(' . esc_url($categoryImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<div class="shop-nav">
    <div class="dropdown-content">
        <div class="dropdown-inner">
            <div class="categories-column">
                <?php
                if (function_exists('get_terms')) :
                    $product_categories = get_terms('product_cat', [
                        'orderby'    => 'name',
                        'hide_empty' => false,
                    ]);
                    foreach ($product_categories as $category) :
                        if ($category->name === $excludeCategoryName) continue;
                ?>
                    <div class="category">
                        <h4><a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a></h4>
                        <?php if (function_exists('wc_get_products')) :
                            $products = wc_get_products([
                                'category' => [$category->slug],
                                'limit'    => $productsPerCategory,
                            ]);
                            if (!empty($products)) : ?>
                                <ul class="product-list">
                                    <?php foreach ($products as $product) : ?>
                                        <li><a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"><?php echo esc_html($product->get_name()); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif;
                        endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
            <div class="image-column" style="height: 400px; width: 600px; <?php echo $image_inline_style; ?>">
                <button class="menu-slider-button">
                    <a href="<?php echo esc_url(site_url($buttonURL)); ?>"><?php echo wp_kses_post($buttonLabel); ?></a>
                </button>
            </div>
        </div>
    </div>
    <div id="mobile-menu-root"></div>
</div>
