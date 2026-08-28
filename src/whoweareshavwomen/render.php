<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : 'Dołącz do społeczności +100 tysięcy zadowolonych klientów';
$videoURL        = isset($attributes['videoURL'])        ? str_replace('http://', 'https://', $attributes['videoURL'])        : '';
$backgroundImage = isset($attributes['backgroundImage']) ? str_replace('http://', 'https://', $attributes['backgroundImage']) : '';
$overlayColor    = isset($attributes['overlayColor'])    ? $attributes['overlayColor']    : '#00000057';
$logos           = isset($attributes['logos'])           ? $attributes['logos']           : [];

$wrapper_style_parts = [];
if ($backgroundImage) {
    $wrapper_style_parts[] = 'background-image:url(' . esc_url($backgroundImage) . ')';
    $wrapper_style_parts[] = 'background-size:cover';
    $wrapper_style_parts[] = 'background-position:center';
}
$wrapper_style = $wrapper_style_parts ? implode(';', $wrapper_style_parts) . ';' : '';
?>
<div class="video-background-container video-woman" style="<?php echo esc_attr($wrapper_style); ?>">
<?php if ($videoURL): ?>
<video class="video-background" src="<?php echo esc_url($videoURL); ?>" autoplay loop muted playsinline></video>
<?php else: ?>
<div class="placeholder-video-woman"></div>
<?php endif; ?>
<section class="about-us-second" style="background-color: <?php echo esc_attr($overlayColor); ?>;">
        <div class="about-us-second-title">
            <span class="about-us-span first container--narrow2-important"><?php echo wp_kses_post($title); ?></span>    
        </div>
        <div class="about-us-logos">
            <div class="about-us-swiper">
                <?php if (!empty($logos)): ?>
                    <?php foreach ($logos as $logo): ?>
                        <img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? 'Logo'); ?>" />
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
.video-woman::after {
background: none !important;
}

.video-woman::before {
background: none !important;
}
</style>
