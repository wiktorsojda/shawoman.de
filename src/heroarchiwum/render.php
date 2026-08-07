<?php
$imageDesktop = isset($attributes['imageDesktop']) ? $attributes['imageDesktop'] : '';
$imageMobile = isset($attributes['imageMobile']) ? $attributes['imageMobile'] : '';
$text1 = isset($attributes['text1']) ? $attributes['text1'] : '';
$text2 = isset($attributes['text2']) ? $attributes['text2'] : '';
$linkURL = isset($attributes['linkURL']) ? $attributes['linkURL'] : '';

if (!$imageDesktop && !$imageMobile) {
    return;
}

$wrapperStart = '';
$wrapperEnd = '';
if (!empty($linkURL)) {
    $wrapperStart = '<a href="' . esc_url($linkURL) . '" style="display: block;">';
    $wrapperEnd = '</a>';
}
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'container--narrow2-important shop-archive']); ?>>
    <?php echo $wrapperStart; ?>
    <div class="shop-banner-image" style="position: relative;">
        <?php if ($imageDesktop) : ?>
            <img class="shop-banner-image-desktop" src="<?php echo esc_url($imageDesktop); ?>" alt="<?php echo esc_attr__('Shop Banner Image Desktop', 'woocommerce'); ?>" style="width: 100%; height: auto;">
        <?php endif; ?>
        
        <?php if ($imageMobile) : ?>
            <img class="shop-banner-image-mobile" src="<?php echo esc_url($imageMobile); ?>" alt="<?php echo esc_attr__('Shop Banner Image Mobile', 'woocommerce'); ?>" style="width: 100%; height: auto;">
        <?php endif; ?>

        <div class="shop-banner-textcontainer">
            <?php if (!empty($text1)) : ?>
                <div class="banner-text banner-text-1"><?php echo wp_kses_post($text1); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($text2)) : ?>
                <div class="banner-text banner-text-2"><?php echo esc_html($text2); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php echo $wrapperEnd; ?>
</div>
