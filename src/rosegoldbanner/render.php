<?php
// =============================================================================
// Drop — Baner (rosegoldbanner)
// =============================================================================
$themeUri = get_template_directory_uri();

$image       = isset($attributes['image']) && $attributes['image'] ? str_replace('http://', 'https://', $attributes['image']) : $themeUri . '/images/rosegold/banner.jpg';
$imageMobile = isset($attributes['imageMobile']) && $attributes['imageMobile'] ? str_replace('http://', 'https://', $attributes['imageMobile']) : $themeUri . '/images/rosegold/banner-m.jpg';
$imageAlt    = isset($attributes['imageAlt']) ? $attributes['imageAlt'] : '';
$badge       = isset($attributes['badge']) ? $attributes['badge'] : '';
$title       = isset($attributes['title']) ? $attributes['title'] : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$align       = isset($attributes['contentAlign']) ? $attributes['contentAlign'] : 'right';
$linkUrl     = isset($attributes['linkUrl']) ? $attributes['linkUrl'] : '';
$showButton  = isset($attributes['showButton']) ? $attributes['showButton'] : false;
$buttonLabel = isset($attributes['buttonLabel']) ? $attributes['buttonLabel'] : '';
$buttonURL   = isset($attributes['buttonURL']) ? $attributes['buttonURL'] : '';

$tag = $linkUrl ? 'a' : 'div';
$hrefAttr = $linkUrl ? ' href="' . esc_url($linkUrl) . '"' : '';

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<section class="rosegoldbanner rosegoldbanner--<?php echo esc_attr($align); ?>">
    <<?php echo $tag; ?> class="rosegoldbanner__bg"<?php echo $hrefAttr; ?>
        style="--rg-bg-d:url('<?php echo esc_url($image); ?>'); --rg-bg-m:url('<?php echo esc_url($imageMobile); ?>');"
        role="img" aria-label="<?php echo esc_attr($imageAlt); ?>">
        <div class="rosegoldbanner__inner">
            <div class="rosegoldbanner__content">
                <?php if ($badge): ?><span class="rosegoldbanner__badge"><?php echo wp_kses_post($badge); ?></span><?php endif; ?>
                <?php if ($title): ?><h2 class="rosegoldbanner__title"><?php echo wp_kses_post($title); ?></h2><?php endif; ?>
                <?php if ($description): ?><p class="rosegoldbanner__desc"><?php echo wp_kses_post($description); ?></p><?php endif; ?>
            </div>
        </div>
    </<?php echo $tag; ?>>
    <?php if ($showButton):
        $blabel = $buttonLabel ? $buttonLabel : 'Przejdź';
        if ($buttonURL): ?>
            <a class="rosegoldbanner__button" href="<?php echo esc_url($buttonURL); ?>" aria-label="<?php echo esc_attr($blabel); ?>"><?php echo $arrow_svg; ?></a>
        <?php else: ?>
            <span class="rosegoldbanner__button is-disabled" aria-hidden="true"><?php echo $arrow_svg; ?></span>
        <?php endif; ?>
    <?php endif; ?>
</section>
