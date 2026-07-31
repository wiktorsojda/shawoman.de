<?php
// =============================================================================
// Drop — Produkt wyróżniony (rosegoldfeatured)
// =============================================================================
$themeUri = get_template_directory_uri();

$badge   = isset($attributes['badge']) ? $attributes['badge'] : 'EDYCJA LIMITOWANA';
$image   = isset($attributes['image']) && $attributes['image'] ? $attributes['image'] : $themeUri . '/images/rosegold/featured.png';
$imageAlt = isset($attributes['imageAlt']) ? $attributes['imageAlt'] : '';
$title   = isset($attributes['title']) ? $attributes['title'] : 'Idealne połączenie';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$namePart1 = isset($attributes['namePart1']) ? $attributes['namePart1'] : '';
$nameBrand = isset($attributes['nameBrand']) ? $attributes['nameBrand'] : '';
$namePart2 = isset($attributes['namePart2']) ? $attributes['namePart2'] : '';
$productSub = isset($attributes['productSub']) ? $attributes['productSub'] : '';
$discountBadge = isset($attributes['discountBadge']) ? $attributes['discountBadge'] : '';
$oldPrice = isset($attributes['oldPrice']) ? $attributes['oldPrice'] : '';
$newPrice = isset($attributes['newPrice']) ? $attributes['newPrice'] : '';
$productId = isset($attributes['productId']) ? (int) $attributes['productId'] : 0;
$variationId = isset($attributes['variationId']) ? (int) $attributes['variationId'] : 0;
$linkUrl  = isset($attributes['linkUrl']) ? $attributes['linkUrl'] : '';
$showCart = isset($attributes['showCart']) ? $attributes['showCart'] : true;

$link = function_exists('shav_rosegold_link') ? shav_rosegold_link($productId, $linkUrl) : $linkUrl;

$imgTag = '<img src="' . esc_url($image) . '" alt="' . esc_attr($imageAlt) . '">';
?>
<section class="rosegoldfeatured">
    <div class="rosegoldfeatured__card">
        <?php if ($badge): ?><span class="rosegoldfeatured__badge"><?php echo wp_kses_post($badge); ?></span><?php endif; ?>
        <div class="rosegoldfeatured__body">
            <div class="rosegoldfeatured__media">
                <?php if ($link): ?>
                    <a class="rosegoldfeatured__media-link" href="<?php echo esc_url($link); ?>"><?php echo $imgTag; ?></a>
                <?php else: ?>
                    <?php echo $imgTag; ?>
                <?php endif; ?>
                <?php if ($showCart && function_exists('shav_rosegold_add_to_cart')) {
                    echo shav_rosegold_add_to_cart($productId, 'rosegoldfeatured__cart', $variationId);
                } ?>
            </div>

            <div class="rosegoldfeatured__content">
                <div class="rosegoldfeatured__head">
                    <h2 class="rosegoldfeatured__title"><?php echo wp_kses_post($title); ?></h2>
                    <?php if ($description): ?><p class="rosegoldfeatured__desc"><?php echo wp_kses_post($description); ?></p><?php endif; ?>
                </div>
                <div class="rosegoldfeatured__meta">
                    <p class="rosegoldfeatured__name">
                        <?php echo wp_kses_post($namePart1); ?><span class="rosegoldfeatured__name-brand"><?php echo wp_kses_post($nameBrand); ?></span><?php echo wp_kses_post($namePart2); ?>
                    </p>
                    <?php if ($productSub): ?><p class="rosegoldfeatured__sub"><?php echo wp_kses_post($productSub); ?></p><?php endif; ?>
                    <div class="rosegoldfeatured__price">
                        <?php if ($discountBadge): ?><span class="rosegoldfeatured__discount"><?php echo wp_kses_post($discountBadge); ?></span><?php endif; ?>
                        <span class="rosegoldfeatured__price-row">
                            <?php if ($oldPrice): ?><span class="rosegoldfeatured__price-old"><?php echo wp_kses_post($oldPrice); ?></span><?php endif; ?>
                            <?php if ($newPrice): ?><span class="rosegoldfeatured__price-new"><?php echo wp_kses_post($newPrice); ?></span><?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
