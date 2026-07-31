<?php
$title                 = isset($attributes['title']) ? $attributes['title'] : '';
$backgroundImage       = isset($attributes['backgroundImage'])       ? $attributes['backgroundImage']       : '';
$backgroundImageMobile = isset($attributes['backgroundImageMobile']) ? $attributes['backgroundImageMobile'] : '';
$backgroundVideo       = isset($attributes['backgroundVideo'])       ? $attributes['backgroundVideo']       : '';
$backgroundVideoMobile = isset($attributes['backgroundVideoMobile']) ? $attributes['backgroundVideoMobile'] : '';
$titleSizeDesktop      = isset($attributes['titleSizeDesktop']) ? (int) $attributes['titleSizeDesktop'] : 44;
$titleSizeMobile       = isset($attributes['titleSizeMobile'])  ? (int) $attributes['titleSizeMobile']  : 32;
$logoHeightDesktop     = isset($attributes['logoHeightDesktop']) ? (int) $attributes['logoHeightDesktop'] : 31;
$logoHeightMobile      = isset($attributes['logoHeightMobile'])  ? (int) $attributes['logoHeightMobile']  : 24;
$marginTopDesktop      = isset($attributes['marginTopDesktop']) ? (int) $attributes['marginTopDesktop'] : 80;
$marginTopMobile       = isset($attributes['marginTopMobile'])  ? (int) $attributes['marginTopMobile']  : 24;
// Logos — kazdy moze byc img (URL) lub inline SVG (string). SVG nadpisuje img.
$logos = [];
for ($i = 1; $i <= 4; $i++) {
    $svg   = isset($attributes["logo{$i}Svg"])   ? $attributes["logo{$i}Svg"]   : '';
    $image = isset($attributes["logo{$i}Image"]) ? $attributes["logo{$i}Image"] : '';
    if ($svg) {
        $logos[] = ['kind' => 'svg', 'value' => $svg];
    } elseif ($image) {
        $logos[] = ['kind' => 'img', 'value' => $image];
    }
}

// Whitelist tagow SVG dla wp_kses
$svg_allowed = [
    'svg' => ['xmlns'=>1,'viewbox'=>1,'viewBox'=>1,'width'=>1,'height'=>1,'fill'=>1,'stroke'=>1,'class'=>1,'preserveaspectratio'=>1,'preserveAspectRatio'=>1,'aria-hidden'=>1,'role'=>1],
    'g' => ['fill'=>1,'stroke'=>1,'transform'=>1,'opacity'=>1,'clip-path'=>1,'mask'=>1,'filter'=>1,'fill-rule'=>1,'clip-rule'=>1],
    'path' => ['d'=>1,'fill'=>1,'stroke'=>1,'stroke-width'=>1,'fill-rule'=>1,'clip-rule'=>1,'stroke-linecap'=>1,'stroke-linejoin'=>1,'fill-opacity'=>1,'stroke-opacity'=>1,'opacity'=>1,'transform'=>1],
    'circle' => ['cx'=>1,'cy'=>1,'r'=>1,'fill'=>1,'stroke'=>1,'stroke-width'=>1,'opacity'=>1],
    'rect' => ['x'=>1,'y'=>1,'width'=>1,'height'=>1,'rx'=>1,'ry'=>1,'fill'=>1,'stroke'=>1,'stroke-width'=>1,'opacity'=>1],
    'ellipse' => ['cx'=>1,'cy'=>1,'rx'=>1,'ry'=>1,'fill'=>1,'stroke'=>1,'stroke-width'=>1,'opacity'=>1],
    'line' => ['x1'=>1,'y1'=>1,'x2'=>1,'y2'=>1,'stroke'=>1,'stroke-width'=>1],
    'polygon' => ['points'=>1,'fill'=>1,'stroke'=>1,'stroke-width'=>1],
    'polyline' => ['points'=>1,'fill'=>1,'stroke'=>1,'stroke-width'=>1],
    'defs' => [],
    'lineargradient' => ['id'=>1,'x1'=>1,'y1'=>1,'x2'=>1,'y2'=>1,'gradientunits'=>1,'gradientUnits'=>1,'gradienttransform'=>1,'gradientTransform'=>1],
    'linearGradient' => ['id'=>1,'x1'=>1,'y1'=>1,'x2'=>1,'y2'=>1,'gradientunits'=>1,'gradientUnits'=>1,'gradienttransform'=>1,'gradientTransform'=>1],
    'radialgradient' => ['id'=>1,'cx'=>1,'cy'=>1,'r'=>1,'fx'=>1,'fy'=>1,'gradientunits'=>1,'gradientUnits'=>1],
    'radialGradient' => ['id'=>1,'cx'=>1,'cy'=>1,'r'=>1,'fx'=>1,'fy'=>1,'gradientunits'=>1,'gradientUnits'=>1],
    'stop' => ['offset'=>1,'stop-color'=>1,'stop-opacity'=>1],
    'filter' => ['id'=>1,'x'=>1,'y'=>1,'width'=>1,'height'=>1,'filterUnits'=>1,'filterunits'=>1,'color-interpolation-filters'=>1],
    'feFlood' => ['flood-opacity'=>1,'result'=>1,'flood-color'=>1],
    'feColorMatrix' => ['in'=>1,'type'=>1,'values'=>1,'result'=>1],
    'feOffset' => ['dx'=>1,'dy'=>1,'in'=>1,'result'=>1],
    'feGaussianBlur' => ['stdDeviation'=>1,'stddeviation'=>1,'in'=>1,'result'=>1],
    'feComposite' => ['in'=>1,'in2'=>1,'operator'=>1,'result'=>1],
    'feBlend' => ['mode'=>1,'in'=>1,'in2'=>1,'result'=>1],
    'mask' => ['id'=>1,'maskUnits'=>1,'maskunits'=>1],
    'clipPath' => ['id'=>1],
    'clippath' => ['id'=>1],
    'use' => ['href'=>1,'xlink:href'=>1,'x'=>1,'y'=>1,'width'=>1,'height'=>1],
    'title' => [],
    'desc' => [],
];

// Mobile video fallback do desktopowego
$videoDesktop = $backgroundVideo;
$videoMobile  = $backgroundVideoMobile ?: $backgroundVideo;
$videoOnly    = $videoDesktop && !$backgroundImage;

$style_parts = [
    '--title-size-desktop:' . $titleSizeDesktop . 'px',
    '--title-size-mobile:' . $titleSizeMobile . 'px',
    '--logo-height-desktop:' . $logoHeightDesktop . 'px',
    '--logo-height-mobile:' . $logoHeightMobile . 'px',
    '--margin-top-desktop:' . $marginTopDesktop . 'px',
    '--margin-top-mobile:' . $marginTopMobile . 'px',
];
// Image fallback (poster gdy video, lub samodzielny background)
if ($backgroundImage) {
    $style_parts[] = '--bg-desktop:url(' . esc_url($backgroundImage) . ')';
}
$mobile_url = $backgroundImageMobile ?: $backgroundImage;
if ($mobile_url) {
    $style_parts[] = '--bg-mobile:url(' . esc_url($mobile_url) . ')';
}
$wrapper_style = implode(';', $style_parts) . ';';

$wrapper_class = 'onasstandard';
if ($videoDesktop) $wrapper_class .= ' onasstandard--has-video';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => $wrapper_class,
    'style' => $wrapper_style,
]);
?>
<section <?php echo $wrapper_attrs; ?>>
    <?php if ($videoDesktop) : ?>
        <video class="onasstandard__video onasstandard__video--desktop"
               autoplay muted loop playsinline preload="metadata"
               <?php if ($backgroundImage) echo 'poster="' . esc_url($backgroundImage) . '"'; ?>>
            <source src="<?php echo esc_url($videoDesktop); ?>" type="video/mp4" />
        </video>
    <?php endif; ?>
    <?php if ($videoMobile) : ?>
        <video class="onasstandard__video onasstandard__video--mobile"
               autoplay muted loop playsinline preload="metadata"
               <?php if ($mobile_url) echo 'poster="' . esc_url($mobile_url) . '"'; ?>>
            <source src="<?php echo esc_url($videoMobile); ?>" type="video/mp4" />
        </video>
    <?php endif; ?>

    <div class="onasstandard__content">
        <h2 class="onasstandard__title"><?php echo wp_kses_post($title); ?></h2>
        <?php if (!empty($logos)) : ?>
            <div class="onasstandard__logos">
                <?php foreach ($logos as $logo) : ?>
                    <?php if ($logo['kind'] === 'svg') : ?>
                        <span class="onasstandard__logo onasstandard__logo--svg" aria-hidden="true"><?php echo wp_kses($logo['value'], $svg_allowed); ?></span>
                    <?php else : ?>
                        <img class="onasstandard__logo" src="<?php echo esc_url($logo['value']); ?>" alt="" loading="lazy" />
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
