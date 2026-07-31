<?php
$leftImage      = isset($attributes['leftImage'])      ? $attributes['leftImage']      : '';
$p1             = isset($attributes['paragraph1'])     ? $attributes['paragraph1']     : '';
$p2             = isset($attributes['paragraph2'])     ? $attributes['paragraph2']     : '';
$p3             = isset($attributes['paragraph3'])     ? $attributes['paragraph3']     : '';
$signatureText  = isset($attributes['signatureText'])  ? $attributes['signatureText']  : '';
$signatureImage = isset($attributes['signatureImage']) ? $attributes['signatureImage'] : '';
$signatureSvg   = isset($attributes['signatureSvg'])   ? $attributes['signatureSvg']   : '';
$textSizeDesktop = isset($attributes['textSizeDesktop']) ? (int) $attributes['textSizeDesktop'] : 16;
$textSizeMobile  = isset($attributes['textSizeMobile'])  ? (int) $attributes['textSizeMobile']  : 16;
$signatureSizeDesktop = isset($attributes['signatureSizeDesktop']) ? (int) $attributes['signatureSizeDesktop'] : 36;
$signatureSizeMobile  = isset($attributes['signatureSizeMobile'])  ? (int) $attributes['signatureSizeMobile']  : 28;

$wrapper_style = sprintf(
    '--text-size-desktop:%dpx;--text-size-mobile:%dpx;--signature-size-desktop:%dpx;--signature-size-mobile:%dpx;',
    $textSizeDesktop, $textSizeMobile, $signatureSizeDesktop, $signatureSizeMobile
);
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'onasrozwijamy',
    'style' => $wrapper_style,
]);
?>
<section <?php echo $wrapper_attrs; ?>>
    <?php if ($leftImage) : ?>
        <div class="onasrozwijamy__media">
            <img src="<?php echo esc_url($leftImage); ?>" alt="" loading="lazy" />
        </div>
    <?php endif; ?>
    <div class="onasrozwijamy__text">
        <?php if ($p1) : ?><p class="onasrozwijamy__paragraph"><?php echo wp_kses_post($p1); ?></p><?php endif; ?>
        <?php if ($p2) : ?><p class="onasrozwijamy__paragraph"><?php echo wp_kses_post($p2); ?></p><?php endif; ?>
        <?php if ($p3) : ?><p class="onasrozwijamy__paragraph"><?php echo wp_kses_post($p3); ?></p><?php endif; ?>
        <div class="onasrozwijamy__signature">
            <?php if ($signatureText) : ?>
                <span class="onasrozwijamy__signature-text"><?php echo wp_kses_post($signatureText); ?></span>
            <?php endif; ?>
            <?php if ($signatureSvg) : ?>
                <span class="onasrozwijamy__signature-logo onasrozwijamy__signature-logo--svg">
                    <?php echo wp_kses($signatureSvg, [
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
                    ]); ?>
                </span>
            <?php elseif ($signatureImage) : ?>
                <img class="onasrozwijamy__signature-logo" src="<?php echo esc_url($signatureImage); ?>" alt="Shav Woman" />
            <?php endif; ?>
        </div>
    </div>
</section>
