<?php
$videoDesktop = isset($attributes['videoDesktop']) && $attributes['videoDesktop']
    ? $attributes['videoDesktop']
    : esc_url(home_url('/wp-content/uploads/animacja-shav-logo-desktop.mp4'));
$videoMobile  = isset($attributes['videoMobile']) && $attributes['videoMobile']
    ? $attributes['videoMobile']
    : esc_url(home_url('/wp-content/uploads/animacja-shav-logo-mobile.mp4'));
$title       = isset($attributes['title'])       ? $attributes['title']       : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
?>
<section class="video-container">
    <video class="logovideo-desktop" autoplay muted playsinline>
        <source src="<?php echo esc_url($videoDesktop); ?>" type="video/mp4">
    </video>
    <div class="logovideo-mobile">
        <video class="logovideo-mobile" autoplay muted playsinline>
            <source src="<?php echo esc_url($videoMobile); ?>" type="video/mp4">
        </video>
        <section class="blacktext-container-mobile container">
            <div id="text-container-mobile">
                <div class="line line-head-mobile"><?php echo wp_kses_post($title); ?></div>
                <div class="line line-rest-mobile"><?php echo wp_kses_post($description); ?></div>
            </div>
        </section>
    </div>
</section>
