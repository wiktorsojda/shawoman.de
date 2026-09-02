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

$bgPositionDesktop = isset($attributes['bgPositionDesktop']) ? $attributes['bgPositionDesktop'] : 'center';
$bgPositionMobile  = isset($attributes['bgPositionMobile'])  ? $attributes['bgPositionMobile']  : 'center';

$style_parts = [];
if ($backgroundImage) {
    $style_parts[] = '--bg-desktop:url(' . esc_url($backgroundImage) . ')';
    $style_parts[] = '--bg-pos-desktop:' . esc_attr($bgPositionDesktop);
}
// Mobile fallback: jezeli mobile pusty, uzywa desktop (ten sam URL)
$mobile_url = $backgroundImageMobile ?: $backgroundImage;
if ($mobile_url) {
    $style_parts[] = '--bg-mobile:url(' . esc_url($mobile_url) . ')';
    // Jeśli mobile image jest pusty, to fallback użyje --bg-mobile z desktop image, ale pozycja mobile
    $style_parts[] = '--bg-pos-mobile:' . esc_attr($bgPositionMobile);
}
require_once get_theme_file_path('/src/components/spacing-helper.php');
$block_id = uniqid('shav-block-');
shav_render_responsive_spacing_css($block_id, $attributes);

$wrapper_style = $style_parts ? implode(';', $style_parts) . ';' : '';

$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => "szachglass szachglass--x-" . esc_attr($glassPositionX) . " szachglass--y-" . esc_attr($glassPositionY) . " " . esc_attr($block_id),
    'style' => $wrapper_style,
] );

$card_style = 'width:' . $glassWidth . 'px;max-width:100%;text-align:' . esc_attr($textAlign) . ';';
?>
<section <?php echo $wrapper_attributes; ?>>
    <div class="szachglass__card" style="<?php echo esc_attr($card_style); ?>">
        <?php if ($title): ?>
            <h2 class="szachglass__title" style="font-size:<?php echo $titleSize; ?>px;"><?php echo wp_kses_post($title); ?></h2>
        <?php endif; ?>
        <?php if ($description): ?>
            <p class="szachglass__description" style="font-size:<?php echo $descriptionSize; ?>px;"><?php echo wp_kses_post($description); ?></p>
        <?php endif; ?>
    </div>
</section>
