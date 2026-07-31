<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : '';
$ctaLabel        = isset($attributes['ctaLabel'])        ? $attributes['ctaLabel']        : '';
$ctaURL          = isset($attributes['ctaURL'])          ? $attributes['ctaURL']          : '/';
$videoURL        = isset($attributes['videoURL'])        ? $attributes['videoURL']        : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<div class="video-background-container" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>>
    <?php if ($videoURL && !$backgroundImage): ?>
        <video class="video-background" src="<?php echo esc_url($videoURL); ?>" autoplay loop muted playsinline></video>
    <?php else: ?>
        <div class="placeholder-video-new-film"></div>
    <?php endif; ?>
    <section class="about-us-second">
        <div class="about-us-second-title">
            <span class="about-us-span first container--narrow2-important"><?php echo wp_kses_post($title); ?></span>
            <div class="cta-glowna">
                <a href="<?php echo esc_url(home_url($ctaURL)); ?>" class="cta-button">
                    <?php echo wp_kses_post($ctaLabel); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.748537 14C1.00911 12.3211 1.75489 10.7457 2.90138 9.45236C4.04788 8.159 5.54926 7.19936 7.23541 6.68216V9.35158C7.23545 9.45258 7.2648 9.55157 7.32018 9.63748C7.37556 9.7234 7.45479 9.79284 7.54899 9.83803C7.6432 9.88322 7.74866 9.90238 7.85358 9.89337C7.9585 9.88436 8.05873 9.84753 8.14306 9.787L11.0572 7.6939L14.2728 5.38429C14.3434 5.33361 14.4006 5.26787 14.44 5.19229C14.4795 5.11671 14.5 5.03336 14.5 4.94886C14.5 4.86435 14.4795 4.781 14.44 4.70542C14.4006 4.62984 14.3434 4.5641 14.2728 4.51342L11.0572 2.20373L8.14306 0.110602C8.05933 0.048816 7.95898 0.0111903 7.85375 0.00213411C7.74852 -0.00692205 7.64278 0.0129666 7.54892 0.0594691C7.45383 0.103609 7.37384 0.172823 7.31831 0.258998C7.26278 0.345172 7.23403 0.444737 7.23541 0.546022V2.92385C4.91267 3.66808 2.96189 5.21439 1.76281 7.26177C0.563737 9.30916 0.202184 11.7111 0.748537 14Z" fill="#1CC9F2"/>
            </svg>
                </a>
            </div>
        </div>
    </section>
</div>
