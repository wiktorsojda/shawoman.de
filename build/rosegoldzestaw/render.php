<?php
// =============================================================================
// Drop — Zestaw (rosegoldzestaw)
// =============================================================================
$themeUri = get_template_directory_uri();

$image    = isset($attributes['image']) && $attributes['image'] ? $attributes['image'] : $themeUri . '/images/rosegold/zestaw.png';
$imageAlt = isset($attributes['imageAlt']) ? $attributes['imageAlt'] : '';
$imageSide = isset($attributes['imageSide']) ? $attributes['imageSide'] : 'left';
$badge    = isset($attributes['badge']) ? $attributes['badge'] : '';
$title    = isset($attributes['title']) ? $attributes['title'] : '';
$priceInLabel  = isset($attributes['priceInLabel']) ? $attributes['priceInLabel'] : '';
$discountBadge = isset($attributes['discountBadge']) ? $attributes['discountBadge'] : '';
$priceIn       = isset($attributes['priceIn']) ? $attributes['priceIn'] : '';
$priceOutLabel = isset($attributes['priceOutLabel']) ? $attributes['priceOutLabel'] : '';
$priceOut      = isset($attributes['priceOut']) ? $attributes['priceOut'] : '';
$buttonLabel   = isset($attributes['buttonLabel']) ? $attributes['buttonLabel'] : '';
$buttonURL     = isset($attributes['buttonURL']) ? $attributes['buttonURL'] : '';
$productId     = isset($attributes['productId']) ? (int) $attributes['productId'] : 0;

$buttonHref = function_exists('shav_rosegold_link') ? shav_rosegold_link($productId, $buttonURL) : $buttonURL;

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<section class="rosegoldzestaw rosegoldzestaw--img-<?php echo esc_attr($imageSide); ?>">
    <div class="rosegoldzestaw__inner">
        <div class="rosegoldzestaw__media">
            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($imageAlt); ?>">
        </div>

        <div class="rosegoldzestaw__content">
            <?php if ($badge): ?><span class="rosegoldzestaw__badge"><?php echo wp_kses_post($badge); ?></span><?php endif; ?>
            <?php if ($title): ?><h2 class="rosegoldzestaw__title"><?php echo wp_kses_post($title); ?></h2><?php endif; ?>
            <ul class="rosegoldzestaw__list">
                <?php for ($n = 1; $n <= 4; $n++):
                    $item = isset($attributes["item{$n}"]) ? $attributes["item{$n}"] : '';
                    if (!$item) continue; ?>
                    <li><?php echo wp_kses_post($item); ?></li>
                <?php endfor; ?>
            </ul>
        </div>

        <div class="rosegoldzestaw__pricebox">
            <div class="rosegoldzestaw__price-row">
                <span class="rosegoldzestaw__price-label"><?php echo wp_kses_post($priceInLabel); ?></span>
                <span class="rosegoldzestaw__price-right">
                    <?php if ($discountBadge): ?><span class="rosegoldzestaw__discount"><?php echo wp_kses_post($discountBadge); ?></span><?php endif; ?>
                    <span class="rosegoldzestaw__price-in"><?php echo wp_kses_post($priceIn); ?></span>
                </span>
            </div>
            <div class="rosegoldzestaw__price-row">
                <span class="rosegoldzestaw__price-label"><?php echo wp_kses_post($priceOutLabel); ?></span>
                <span class="rosegoldzestaw__price-out"><?php echo wp_kses_post($priceOut); ?></span>
            </div>
        </div>

        <?php if ($buttonLabel): ?>
            <?php $btnTag = $buttonHref ? 'a' : 'span'; ?>
            <<?php echo $btnTag; ?> class="rosegoldzestaw__button<?php echo $buttonHref ? '' : ' is-disabled'; ?>"<?php echo $buttonHref ? ' href="' . esc_url($buttonHref) . '"' : ''; ?>>
                <span><?php echo wp_kses_post($buttonLabel); ?></span>
                <span class="rosegoldzestaw__button-arrow"><?php echo $arrow_svg; ?></span>
            </<?php echo $btnTag; ?>>
        <?php endif; ?>
    </div>
</section>
