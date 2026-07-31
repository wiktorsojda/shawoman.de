<?php
$heading      = isset($attributes['heading'])      ? $attributes['heading']      : '';
$description  = isset($attributes['description'])  ? $attributes['description']  : '';
$buttonLabel  = isset($attributes['buttonLabel'])  ? $attributes['buttonLabel']  : '';
$buttonURL    = isset($attributes['buttonURL'])    ? $attributes['buttonURL']    : '/';
$videoDesktop = isset($attributes['videoDesktop']) && $attributes['videoDesktop']
    ? $attributes['videoDesktop']
    : esc_url(home_url('/wp-content/uploads/animacja-shav-blink-strona-glowna-desktop.mp4'));
$videoMobile  = isset($attributes['videoMobile']) && $attributes['videoMobile']
    ? $attributes['videoMobile']
    : esc_url(home_url('/wp-content/uploads/animacja-shav-blink-strona-glowna-mobile.mp4'));
?>
<section class="blacktext-container-wysylka glowna-kup-container container">
    <div class="metody-wysylki-kup-container glowna-kup-subcontainer container--narrow2-important">
        <div class="metody-wysylki-left-container">
            <h1><?php echo wp_kses_post($heading); ?></h1>
            <p><?php echo wp_kses_post($description); ?></p>
            <a class="button-link" href="<?php echo esc_url(home_url($buttonURL)); ?>">
                <button><?php echo wp_kses_post($buttonLabel); ?></button>
            </a>
        </div>
        <div class="metody-wysylki-right-container-glowna desktop-glowna">
            <video class="metody-wysylki-right-image" src="<?php echo esc_url($videoDesktop); ?>" autoplay loop muted playsinline></video>
        </div>
        <div class="metody-wysylki-right-container-glowna mobile-glowna">
            <video class="metody-wysylki-right-image" src="<?php echo esc_url($videoMobile); ?>" autoplay loop muted playsinline></video>
        </div>
    </div>
</section>
