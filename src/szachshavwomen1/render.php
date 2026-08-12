<?php
$backgroundImage       = isset($attributes['backgroundImage'])       ? $attributes['backgroundImage']       : '';
$backgroundImageMobile = isset($attributes['backgroundImageMobile']) ? $attributes['backgroundImageMobile'] : '';
$title                 = isset($attributes['title'])                 ? $attributes['title']                 : '';
$description           = isset($attributes['description'])           ? $attributes['description']           : '';
$titleSize             = isset($attributes['titleSize'])             ? (int) $attributes['titleSize']       : 42;
$descriptionSize       = isset($attributes['descriptionSize'])       ? (int) $attributes['descriptionSize'] : 18;
$glassPositionX        = isset($attributes['glassPositionX'])        ? $attributes['glassPositionX']        : 'left';
$glassPositionY        = isset($attributes['glassPositionY'])        ? $attributes['glassPositionY']        : 'middle';
$glassWidth            = isset($attributes['glassWidth'])            ? (int) $attributes['glassWidth']      : 726;

$textAlign             = isset($attributes['textAlign'])             ? $attributes['textAlign']             : 'left';

$allowed_x = ['left', 'center', 'right'];
$allowed_y = ['top', 'middle', 'bottom'];
$allowed_align = ['left', 'center', 'right'];
if (!in_array($glassPositionX, $allowed_x, true)) $glassPositionX = 'left';
if (!in_array($glassPositionY, $allowed_y, true)) $glassPositionY = 'middle';
if (!in_array($textAlign, $allowed_align, true)) $textAlign = 'left';

$style_parts = [];
if ($backgroundImage) {
    $style_parts[] = '--bg-desktop:url(' . esc_url($backgroundImage) . ')';
}
// Mobile fallback: jezeli mobile pusty, uzywa desktop (ten sam URL)
$mobile_url = $backgroundImageMobile ?: $backgroundImage;
if ($mobile_url) {
    $style_parts[] = '--bg-mobile:url(' . esc_url($mobile_url) . ')';
}
$wrapper_style = $style_parts ? implode(';', $style_parts) . ';' : '';

$card_style = 'width:' . $glassWidth . 'px;max-width:100%;text-align:' . esc_attr($textAlign) . ';';
?>
<section class="szachglass szachglass--x-<?php echo esc_attr($glassPositionX); ?> szachglass--y-<?php echo esc_attr($glassPositionY); ?>" style="<?php echo esc_attr($wrapper_style); ?>">
    <div class="szachglass__card" style="<?php echo esc_attr($card_style); ?>">
        <?php if ($title): ?>
            <h2 class="szachglass__title" style="font-size:<?php echo $titleSize; ?>px;"><?php echo wp_kses_post($title); ?></h2>
        <?php endif; ?>
        <?php if ($description): ?>
            <p class="szachglass__description" style="font-size:<?php echo $descriptionSize; ?>px;"><?php echo wp_kses_post($description); ?></p>
        <?php endif; ?>
    </div>
</section>
