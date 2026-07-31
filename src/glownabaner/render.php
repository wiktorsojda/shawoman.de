<?php
$showRating      = isset($attributes['showRating'])      ? $attributes['showRating']      : true;
$ratingCount     = isset($attributes['ratingCount'])     ? $attributes['ratingCount']     : '';
$ratingScore     = isset($attributes['ratingScore'])     ? $attributes['ratingScore']     : '';

$bannerImage     = isset($attributes['bannerImage']) && $attributes['bannerImage']
    ? $attributes['bannerImage']
    : esc_url(home_url('/wp-content/uploads/2026/05/Frame-7177.png'));
$bannerImageMobile = isset($attributes['bannerImageMobile']) && $attributes['bannerImageMobile']
    ? $attributes['bannerImageMobile']
    : $bannerImage;
$bannerTitle     = isset($attributes['bannerTitle'])       ? $attributes['bannerTitle']       : 'Shav ';
$bannerTitleAccent = isset($attributes['bannerTitleAccent']) ? $attributes['bannerTitleAccent'] : 'Days';
$bannerSubtitle  = isset($attributes['bannerSubtitle'])  ? $attributes['bannerSubtitle']  : '';
$bannerCtaLabel  = isset($attributes['bannerCtaLabel'])  ? $attributes['bannerCtaLabel']  : 'Dowiedz się więcej';
$bannerCtaURL    = isset($attributes['bannerCtaURL'])    ? $attributes['bannerCtaURL']    : '/';

$avatars = [];
for ($i = 1; $i <= 5; $i++) {
    $key = "avatar{$i}";
    if (!empty($attributes[$key])) {
        $avatars[] = $attributes[$key];
    }
}
?>
<section class="glownabaner">
    <?php if ($showRating) : ?>
        <div class="glownabaner__rating">
            <span class="glownabaner__rating-line" aria-hidden="true"></span>
            <div class="glownabaner__rating-pill">
                <?php if (!empty($avatars)) : ?>
                    <div class="avatars">
                        <?php foreach ($avatars as $av) : ?>
                            <img class="avatars__item" src="<?php echo esc_url($av); ?>" alt="">
                        <?php endforeach; ?>
                    </div>
                    <span class="glownabaner__rating-sep" aria-hidden="true"></span>
                <?php endif; ?>

                <span class="glownabaner__rating-count"><?php echo wp_kses_post($ratingCount); ?></span>
                <span class="glownabaner__rating-sep" aria-hidden="true"></span>
                <span class="glownabaner__rating-score"><?php echo wp_kses_post($ratingScore); ?></span>
                <span class="glownabaner__rating-stars" aria-label="5 z 5 gwiazdek">
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#e9bd0b">
                            <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    <?php endfor; ?>
                </span>
            </div>
            <span class="glownabaner__rating-line" aria-hidden="true"></span>
        </div>
    <?php endif; ?>

    <div class="glownabaner__hero">
        <picture class="glownabaner__hero-picture">
            <source media="(max-width: 767px)" srcset="<?php echo esc_url($bannerImageMobile); ?>">
            <img class="glownabaner__hero-image" src="<?php echo esc_url($bannerImage); ?>" alt="">
        </picture>
        <div class="glownabaner__hero-content">
            <h1 class="glownabaner__hero-title">
                <span><?php echo wp_kses_post($bannerTitle); ?></span><span class="glownabaner__hero-title-accent"><?php echo wp_kses_post($bannerTitleAccent); ?></span>
            </h1>
            <p class="glownabaner__hero-subtitle"><?php echo wp_kses_post($bannerSubtitle); ?></p>
            <a class="glownabaner__hero-cta" href="<?php echo esc_url(home_url($bannerCtaURL)); ?>">
                <span><?php echo wp_kses_post($bannerCtaLabel); ?></span>
                <span class="glownabaner__hero-cta-arrow" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14m-6-7 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>
