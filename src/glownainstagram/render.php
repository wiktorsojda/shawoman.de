<?php
$sectionTitle     = isset($attributes['sectionTitle'])     ? $attributes['sectionTitle']     : 'Znajdziesz nas na Instagramie';
$profileLogo      = isset($attributes['profileLogo']) && $attributes['profileLogo']
    ? $attributes['profileLogo']
    : esc_url(home_url('/wp-content/uploads/shav-logo.png'));
$profileName      = isset($attributes['profileName'])      ? $attributes['profileName']      : 'shav_woman';
$profileFollowers = isset($attributes['profileFollowers']) ? $attributes['profileFollowers'] : '';
$profileURL       = isset($attributes['profileURL'])       ? $attributes['profileURL']       : '#';
$shortcode        = isset($attributes['shortcode'])        ? $attributes['shortcode']        : '[instagram-feed feed=1]';
?>
<section class="glownainstagram">
    <h2 class="glownainstagram__title"><?php echo wp_kses_post($sectionTitle); ?></h2>

    <?php /* Logo + nazwa + liczba obserwujacych — ukryte na zyczenie usera (2026-05-11)
    <a class="glownainstagram__profile" href="<?php echo esc_url($profileURL); ?>" target="_blank" rel="noopener noreferrer">
        <div class="glownainstagram__profile-avatar">
            <img src="<?php echo esc_url($profileLogo); ?>" alt="">
        </div>
        <div class="glownainstagram__profile-text">
            <p class="glownainstagram__profile-name"><?php echo wp_kses_post($profileName); ?></p>
            <p class="glownainstagram__profile-followers"><?php echo wp_kses_post($profileFollowers); ?></p>
        </div>
    </a>
    */ ?>

    <div class="glownainstagram__feed instagram-carousel-container">
        <?php echo do_shortcode($shortcode); ?>
    </div>

    <div class="glownainstagram__nav carousel-buttons">
        <button class="glownainstagram__nav-btn glownainstagram__nav-btn--prev prev-instagram" type="button" aria-label="Poprzednie">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 56 56" fill="none">
                <path d="M38.1047 30.2963L11.7778 30.2964L11.7778 25.9712L38.1031 25.9697L26.5027 14.3693L29.5615 11.3105L46.3848 28.1338L29.5615 44.9571L26.5027 41.8983L38.1047 30.2963Z" fill="currentColor"/>
            </svg>
        </button>
        <button class="glownainstagram__nav-btn glownainstagram__nav-btn--next next-instagram" type="button" aria-label="Następne">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 56 56" fill="none">
                <path d="M38.1047 30.2963L11.7778 30.2964L11.7778 25.9712L38.1031 25.9697L26.5027 14.3693L29.5615 11.3105L46.3848 28.1338L29.5615 44.9571L26.5027 41.8983L38.1047 30.2963Z" fill="currentColor"/>
            </svg>
        </button>
    </div>
</section>
