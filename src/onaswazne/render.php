<?php
$leftTitle       = isset($attributes['leftTitle'])       ? $attributes['leftTitle']       : '';
$leftDescription = isset($attributes['leftDescription']) ? $attributes['leftDescription'] : '';
$leftLogoImage   = isset($attributes['leftLogoImage'])   ? $attributes['leftLogoImage']   : '';
$leftLogoImage2  = isset($attributes['leftLogoImage2'])  ? $attributes['leftLogoImage2']  : '';
$leftLogoSvg     = isset($attributes['leftLogoSvg'])     ? $attributes['leftLogoSvg']     : '';
$rightTitle       = isset($attributes['rightTitle'])       ? $attributes['rightTitle']       : '';
$rightDescription = isset($attributes['rightDescription']) ? $attributes['rightDescription'] : '';
$rightButtonText  = isset($attributes['rightButtonText'])  ? $attributes['rightButtonText']  : '';
$rightButtonUrl   = isset($attributes['rightButtonUrl'])   ? $attributes['rightButtonUrl']   : '#';
$rightDecoration     = isset($attributes['rightDecoration'])     ? $attributes['rightDecoration']     : '';
$rightDecorationSvg  = isset($attributes['rightDecorationSvg'])  ? $attributes['rightDecorationSvg']  : '';
$titleSizeDesktop = isset($attributes['titleSizeDesktop']) ? (int) $attributes['titleSizeDesktop'] : 32;
$titleSizeMobile  = isset($attributes['titleSizeMobile'])  ? (int) $attributes['titleSizeMobile']  : 26;
$descSizeDesktop  = isset($attributes['descriptionSizeDesktop']) ? (int) $attributes['descriptionSizeDesktop'] : 16;
$descSizeMobile   = isset($attributes['descriptionSizeMobile'])  ? (int) $attributes['descriptionSizeMobile']  : 16;
$buttonSize       = isset($attributes['buttonSize'])             ? (int) $attributes['buttonSize']             : 12;

// Whitelist tagow/atrybutow SVG dla wp_kses
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

$wrapper_style = sprintf(
    '--title-size-desktop:%dpx;--title-size-mobile:%dpx;--description-size-desktop:%dpx;--description-size-mobile:%dpx;--button-size:%dpx;',
    $titleSizeDesktop, $titleSizeMobile, $descSizeDesktop, $descSizeMobile, $buttonSize
);
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'onaswazne',
    'style' => $wrapper_style,
]);
?>
<section <?php echo $wrapper_attrs; ?>>
    <article class="onaswazne__card onaswazne__card--left">
        <div class="onaswazne__card-content">
            <div class="onaswazne__text-block">
                <?php if ($leftTitle) : ?><h2 class="onaswazne__title"><?php echo wp_kses_post($leftTitle); ?></h2><?php endif; ?>
                <?php if ($leftDescription) : ?><p class="onaswazne__description"><?php echo wp_kses_post($leftDescription); ?></p><?php endif; ?>
            </div>
            <?php
            // Z drugim logiem okrag zamienia sie w zaokraglona karte (--duo)
            $has_logo1 = $leftLogoSvg || $leftLogoImage;
            $logo_class = 'onaswazne__logo' . ($leftLogoImage2 ? ' onaswazne__logo--duo' : '');
            ?>
            <?php if ($has_logo1 || $leftLogoImage2) : ?>
                <div class="<?php echo esc_attr($logo_class); ?>">
                    <?php if ($leftLogoSvg) : ?>
                        <div class="onaswazne__logo-inner onaswazne__logo-inner--svg">
                            <?php echo wp_kses($leftLogoSvg, $svg_allowed); ?>
                        </div>
                    <?php elseif ($leftLogoImage) : ?>
                        <div class="onaswazne__logo-inner">
                            <img src="<?php echo esc_url($leftLogoImage); ?>" alt="" loading="lazy" />
                        </div>
                    <?php endif; ?>
                    <?php if ($leftLogoImage2) : ?>
                        <div class="onaswazne__logo-second">
                            <img src="<?php echo esc_url($leftLogoImage2); ?>" alt="" loading="lazy" />
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </article>

    <article class="onaswazne__card onaswazne__card--right">
        <div class="onaswazne__card-content">
            <div class="onaswazne__text-block">
                <?php if ($rightTitle) : ?><h2 class="onaswazne__title onaswazne__title--light"><?php echo wp_kses_post($rightTitle); ?></h2><?php endif; ?>
                <?php if ($rightDescription) : ?><p class="onaswazne__description onaswazne__description--light"><?php echo wp_kses_post($rightDescription); ?></p><?php endif; ?>
            </div>
            <?php if ($rightButtonText) : ?>
                <a class="onaswazne__cta" href="<?php echo esc_url($rightButtonUrl); ?>">
                    <span class="onaswazne__cta-text"><?php echo esc_html($rightButtonText); ?></span>
                    <span class="onaswazne__cta-icon" aria-hidden="true">
                        <svg width="10" height="9" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12h14M13 5l7 7-7 7" stroke="white" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            <?php endif; ?>
        </div>
        <?php if ($rightDecorationSvg) : ?>
            <div class="onaswazne__decoration onaswazne__decoration--svg" aria-hidden="true">
                <?php echo wp_kses($rightDecorationSvg, $svg_allowed); ?>
            </div>
        <?php elseif ($rightDecoration) : ?>
            <div class="onaswazne__decoration" aria-hidden="true">
                <img src="<?php echo esc_url($rightDecoration); ?>" alt="" />
            </div>
        <?php endif; ?>
    </article>
</section>
