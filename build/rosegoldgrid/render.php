<?php
// =============================================================================
// Drop — Siatka produktów (rosegoldgrid)
// =============================================================================
$themeUri = get_template_directory_uri();

$heading1       = isset($attributes['heading1']) ? $attributes['heading1'] : 'Zobacz produkty dropu ';
$heading2Accent = isset($attributes['heading2Accent']) ? $attributes['heading2Accent'] : 'Rose Gold';
$showCart       = isset($attributes['showCart']) ? $attributes['showCart'] : true;

// Domyślne obrazy z motywu (per slot)
$defaultImages = [
    1 => $themeUri . '/images/rosegold/prod-1.png',
    2 => $themeUri . '/images/rosegold/prod-2.png',
    3 => $themeUri . '/images/rosegold/prod-3.png',
    4 => $themeUri . '/images/rosegold/prod-4.png',
];
?>
<section class="rosegoldgrid">
    <?php if ($heading1 || $heading2Accent): ?>
        <h2 class="rosegoldgrid__heading">
            <?php echo wp_kses_post($heading1); ?><span
                class="rosegoldgrid__heading-accent"><?php echo wp_kses_post($heading2Accent); ?></span>
        </h2>
    <?php endif; ?>

    <div class="rosegoldgrid__grid">
        <?php for ($n = 1; $n <= 4; $n++):
            $img       = isset($attributes["prod{$n}Image"]) && $attributes["prod{$n}Image"] ? $attributes["prod{$n}Image"] : $defaultImages[$n];
            $badge     = isset($attributes["prod{$n}Badge"]) ? $attributes["prod{$n}Badge"] : '';
            $ptitle    = isset($attributes["prod{$n}Title"]) ? $attributes["prod{$n}Title"] : '';
            $brand     = isset($attributes["prod{$n}Brand"]) ? $attributes["prod{$n}Brand"] : '';
            $sub       = isset($attributes["prod{$n}Sub"]) ? $attributes["prod{$n}Sub"] : '';
            $oldPrice  = isset($attributes["prod{$n}OldPrice"]) ? $attributes["prod{$n}OldPrice"] : '';
            $newPrice  = isset($attributes["prod{$n}NewPrice"]) ? $attributes["prod{$n}NewPrice"] : '';
            $bottom    = isset($attributes["prod{$n}BottomBadge"]) ? $attributes["prod{$n}BottomBadge"] : '';
            $productId = isset($attributes["prod{$n}ProductId"]) ? (int) $attributes["prod{$n}ProductId"] : 0;
            $variationId = isset($attributes["prod{$n}VariationId"]) ? (int) $attributes["prod{$n}VariationId"] : 0;
            $linkUrl   = isset($attributes["prod{$n}LinkUrl"]) ? $attributes["prod{$n}LinkUrl"] : '';

            // Slot pusty (użytkownik wyczyścił) → pomiń
            if (!$ptitle && !$sub && !$newPrice && !$oldPrice) continue;

            $link = function_exists('shav_rosegold_link') ? shav_rosegold_link($productId, $linkUrl) : $linkUrl;
            $imgTag = '<img src="' . esc_url($img) . '" alt="' . esc_attr(wp_strip_all_tags($ptitle . ' ' . $brand)) . '">';
            ?>
            <article class="rosegoldgrid__card">
                <?php if ($badge): ?><span class="rosegoldgrid__badge"><?php echo wp_kses_post($badge); ?></span><?php endif; ?>
                <div class="rosegoldgrid__media">
                    <?php if ($link): ?>
                        <a class="rosegoldgrid__media-link" href="<?php echo esc_url($link); ?>"><?php echo $imgTag; ?></a>
                    <?php else: ?>
                        <?php echo $imgTag; ?>
                    <?php endif; ?>
                    <?php if ($showCart && function_exists('shav_rosegold_add_to_cart')) {
                        echo shav_rosegold_add_to_cart($productId, 'rosegoldgrid__cart', $variationId);
                    } ?>
                </div>
                <div class="rosegoldgrid__info">
                    <div class="rosegoldgrid__text">
                        <p class="rosegoldgrid__title">
                            <?php echo wp_kses_post($ptitle); ?><?php if ($brand): ?><span class="rosegoldgrid__brand"><?php echo wp_kses_post($brand); ?></span><?php endif; ?>
                        </p>
                        <?php if ($sub): ?><p class="rosegoldgrid__sub"><?php echo wp_kses_post($sub); ?></p><?php endif; ?>
                    </div>
                    <div class="rosegoldgrid__price">
                        <?php if ($oldPrice): ?><span class="rosegoldgrid__price-old"><?php echo wp_kses_post($oldPrice); ?></span><?php endif; ?>
                        <?php if ($newPrice): ?><span class="rosegoldgrid__price-new<?php echo $oldPrice ? ' is-discounted' : ''; ?>"><?php echo wp_kses_post($newPrice); ?></span><?php endif; ?>
                    </div>
                    <?php if ($bottom): ?><span class="rosegoldgrid__bottom-badge"><?php echo wp_kses_post($bottom); ?></span><?php endif; ?>
                </div>
            </article>
        <?php endfor; ?>
    </div>
</section>
