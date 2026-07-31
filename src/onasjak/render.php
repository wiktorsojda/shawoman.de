<?php
$title       = isset($attributes['title'])       ? $attributes['title']       : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$image1      = isset($attributes['image1'])      ? $attributes['image1']      : '';
$image2      = isset($attributes['image2'])      ? $attributes['image2']      : '';
$image3      = isset($attributes['image3'])      ? $attributes['image3']      : '';
$titleSizeDesktop = isset($attributes['titleSizeDesktop']) ? (int) $attributes['titleSizeDesktop'] : 32;
$titleSizeMobile  = isset($attributes['titleSizeMobile'])  ? (int) $attributes['titleSizeMobile']  : 26;
$descSizeDesktop  = isset($attributes['descriptionSizeDesktop']) ? (int) $attributes['descriptionSizeDesktop'] : 16;
$descSizeMobile   = isset($attributes['descriptionSizeMobile'])  ? (int) $attributes['descriptionSizeMobile']  : 16;

$wrapper_style = sprintf(
    '--title-size-desktop:%dpx;--title-size-mobile:%dpx;--description-size-desktop:%dpx;--description-size-mobile:%dpx;',
    $titleSizeDesktop, $titleSizeMobile, $descSizeDesktop, $descSizeMobile
);
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'onasjak',
    'style' => $wrapper_style,
]);
?>
<section <?php echo $wrapper_attrs; ?>>
    <header class="onasjak__head">
        <?php if ($title) : ?><h2 class="onasjak__title"><?php echo wp_kses_post($title); ?></h2><?php endif; ?>
        <?php if ($description) : ?><p class="onasjak__description"><?php echo wp_kses_post($description); ?></p><?php endif; ?>
    </header>
    <div class="onasjak__gallery">
        <?php if ($image1) : ?><div class="onasjak__slot onasjak__slot--wide"><img src="<?php echo esc_url($image1); ?>" alt="" loading="lazy"/></div><?php endif; ?>
        <?php if ($image2) : ?><div class="onasjak__slot"><img src="<?php echo esc_url($image2); ?>" alt="" loading="lazy"/></div><?php endif; ?>
        <?php if ($image3) : ?><div class="onasjak__slot"><img src="<?php echo esc_url($image3); ?>" alt="" loading="lazy"/></div><?php endif; ?>
    </div>
</section>
