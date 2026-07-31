<?php
$logoImage   = isset($attributes['logoImage'])   ? $attributes['logoImage']   : '';
$logoImage2  = isset($attributes['logoImage2'])  ? $attributes['logoImage2']  : '';
$title       = isset($attributes['title'])       ? $attributes['title']       : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$titleSizeDesktop       = isset($attributes['titleSizeDesktop'])       ? (int) $attributes['titleSizeDesktop']       : 42;
$titleSizeMobile        = isset($attributes['titleSizeMobile'])        ? (int) $attributes['titleSizeMobile']        : 26;
$descriptionSizeDesktop = isset($attributes['descriptionSizeDesktop']) ? (int) $attributes['descriptionSizeDesktop'] : 18;
$descriptionSizeMobile  = isset($attributes['descriptionSizeMobile'])  ? (int) $attributes['descriptionSizeMobile']  : 16;

$wrapper_style = sprintf(
    '--title-size-desktop:%dpx;--title-size-mobile:%dpx;--description-size-desktop:%dpx;--description-size-mobile:%dpx;',
    $titleSizeDesktop, $titleSizeMobile, $descriptionSizeDesktop, $descriptionSizeMobile
);
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'misjarakroll',
    'style' => $wrapper_style,
]);
?>
<section <?php echo $wrapper_attrs; ?>>
    <div class="misjarakroll__card">
        <?php if ($logoImage) : ?>
            <div class="misjarakroll__logo">
                <img src="<?php echo esc_url($logoImage); ?>" alt="Fundacja Rak'n'Roll" loading="lazy" />
            </div>
        <?php endif; ?>
        <?php if ($logoImage2) : ?>
            <div class="misjarakroll__logo2">
                <img src="<?php echo esc_url($logoImage2); ?>" alt="Fundacja Pokonać Endometriozę" loading="lazy" />
            </div>
        <?php endif; ?>
    </div>
    <div class="misjarakroll__text">
        <?php if ($title) : ?>
            <h2 class="misjarakroll__title"><?php echo wp_kses_post($title); ?></h2>
        <?php endif; ?>
        <?php if ($description) : ?>
            <p class="misjarakroll__description"><?php echo wp_kses_post($description); ?></p>
        <?php endif; ?>
    </div>
</section>
