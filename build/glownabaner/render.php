<?php
$showRating      = isset($attributes['showRating'])      ? $attributes['showRating']      : true;
$ratingCount     = isset($attributes['ratingCount'])     ? $attributes['ratingCount']     : '+300K Opinii';
$ratingScore     = isset($attributes['ratingScore'])     ? $attributes['ratingScore']     : '4.9';

$bannerImage     = isset($attributes['bannerImage']) && $attributes['bannerImage']
    ? str_replace('http://', 'https://', $attributes['bannerImage'])
    : esc_url(home_url('/wp-content/uploads/2026/05/Frame-7177.png'));
$bannerImageMobile = isset($attributes['bannerImageMobile']) && $attributes['bannerImageMobile']
    ? str_replace('http://', 'https://', $attributes['bannerImageMobile'])
    : $bannerImage;

$bannerTitle       = isset($attributes['bannerTitle'])       ? $attributes['bannerTitle']       : '15% zniżki z kodem:';
$bannerTitleAccent = isset($attributes['bannerTitleAccent']) ? $attributes['bannerTitleAccent'] : 'WOMAN15';
$bannerSubtitle    = isset($attributes['bannerSubtitle'])    ? $attributes['bannerSubtitle']    : '';
$bannerCtaLabel    = isset($attributes['bannerCtaLabel'])    ? $attributes['bannerCtaLabel']    : 'Dowiedz się więcej';
$bannerCtaLabelMobile = !empty($attributes['bannerCtaLabelMobile']) ? $attributes['bannerCtaLabelMobile'] : $bannerCtaLabel;
$bannerCtaURL      = isset($attributes['bannerCtaURL'])      ? $attributes['bannerCtaURL']      : '/sklep';

// Atrybuty stylów - Tytuł (Desktop + Mobile)
$titleColor          = isset($attributes['titleColor'])          ? $attributes['titleColor']          : '#ffffff';
$titleFontSize       = isset($attributes['titleFontSize'])       ? $attributes['titleFontSize']       : '42px';
$titleFontSizeMobile = isset($attributes['titleFontSizeMobile']) ? $attributes['titleFontSizeMobile'] : '24px';
$titleFontFamily     = isset($attributes['titleFontFamily'])     ? $attributes['titleFontFamily']     : 'Be Vietnam Pro';
$titleFontWeight     = isset($attributes['titleFontWeight'])     ? $attributes['titleFontWeight']     : '500';
$titleLineHeight     = isset($attributes['titleLineHeight'])     ? $attributes['titleLineHeight']     : '100%';
$titleLineHeightMobile=isset($attributes['titleLineHeightMobile'])?$attributes['titleLineHeightMobile']: '110%';
$titleLetterSpacing  = isset($attributes['titleLetterSpacing'])  ? $attributes['titleLetterSpacing']  : '-4%';
$titleTextTransform  = isset($attributes['titleTextTransform'])  ? $attributes['titleTextTransform']  : 'uppercase';
$titleTextShadow     = isset($attributes['titleTextShadow'])     ? $attributes['titleTextShadow']     : '0px 1px 1px #00000080';

// Atrybuty stylów - Akcent (Desktop + Mobile)
$accentColor           = isset($attributes['accentColor'])           ? $attributes['accentColor']           : '#ffffff';
$accentFontSize        = isset($attributes['accentFontSize'])        ? $attributes['accentFontSize']        : '98px';
$accentFontSizeMobile  = isset($attributes['accentFontSizeMobile'])  ? $attributes['accentFontSizeMobile']  : '48px';
$accentFontFamily      = isset($attributes['accentFontFamily'])      ? $attributes['accentFontFamily']      : 'Be Vietnam Pro';
$accentFontWeight      = isset($attributes['accentFontWeight'])      ? $attributes['accentFontWeight']      : '700';
$accentLineHeight      = isset($attributes['accentLineHeight'])      ? $attributes['accentLineHeight']      : '120%';
$accentLineHeightMobile= isset($attributes['accentLineHeightMobile'])? $attributes['accentLineHeightMobile']: '110%';
$accentLetterSpacing   = isset($attributes['accentLetterSpacing'])   ? $attributes['accentLetterSpacing']   : '-4%';
$accentTextShadow      = isset($attributes['accentTextShadow'])      ? $attributes['accentTextShadow']      : '0px 1px 1px #00000080';

// Atrybuty stylów - Przycisk CTA (Desktop + Mobile)
$ctaMarginTop       = isset($attributes['ctaMarginTop'])       ? $attributes['ctaMarginTop']       : '0px';
$ctaMarginTopMobile = isset($attributes['ctaMarginTopMobile']) ? $attributes['ctaMarginTopMobile'] : '0px';
$ctaFontFamily      = isset($attributes['ctaFontFamily'])      ? $attributes['ctaFontFamily']      : 'Be Vietnam Pro';
$ctaFontSize        = isset($attributes['ctaFontSize'])        ? $attributes['ctaFontSize']        : '16px';
$ctaFontSizeMobile  = isset($attributes['ctaFontSizeMobile'])  ? $attributes['ctaFontSizeMobile']  : '14px';

$avatars = [];
for ($i = 1; $i <= 5; $i++) {
    $key = "avatar{$i}";
    if (!empty($attributes[$key])) {
        $avatars[] = $attributes[$key];
    }
}

// Generowanie unikalnego ID dla bloku, by style w <style> nie nadpisywały się nawzajem przy wielu banerach
$block_id = wp_unique_id('glownabaner-');
?>

<style>
    #<?php echo esc_attr($block_id); ?> .glownabaner__hero {
        width: 100%;
        background-image: url('<?php echo esc_url($bannerImage); ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    @media (max-width: 767px) {
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero {
            background-image: url('<?php echo esc_url($bannerImageMobile); ?>');
        }
    }

    #<?php echo esc_attr($block_id); ?> .glownabaner__hero-title-main {
        color: <?php echo esc_attr($titleColor); ?>;
        font-size: <?php echo esc_attr($titleFontSize); ?>;
        font-family: <?php echo esc_attr($titleFontFamily); ?>;
        font-weight: <?php echo esc_attr($titleFontWeight); ?>;
        line-height: <?php echo esc_attr($titleLineHeight); ?>;
        letter-spacing: <?php echo esc_attr($titleLetterSpacing); ?>;
        text-transform: <?php echo esc_attr($titleTextTransform); ?>;
        <?php if ($titleTextShadow !== 'none') : ?>
        text-shadow: <?php echo esc_attr($titleTextShadow); ?>;
        <?php endif; ?>
    }

    #<?php echo esc_attr($block_id); ?> .glownabaner__hero-title-accent {
        color: <?php echo esc_attr($accentColor); ?>;
        font-size: <?php echo esc_attr($accentFontSize); ?>;
        font-family: <?php echo esc_attr($accentFontFamily); ?>;
        font-weight: <?php echo esc_attr($accentFontWeight); ?>;
        line-height: <?php echo esc_attr($accentLineHeight); ?>;
        letter-spacing: <?php echo esc_attr($accentLetterSpacing); ?>;
        <?php if ($accentTextShadow !== 'none') : ?>
        text-shadow: <?php echo esc_attr($accentTextShadow); ?>;
        <?php endif; ?>
    }

    #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta {
        margin-top: <?php echo esc_attr($ctaMarginTop); ?>;
    }

    #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta-text {
        font-family: <?php echo esc_attr($ctaFontFamily); ?>;
        font-size: <?php echo esc_attr($ctaFontSize); ?>;
    }

    #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta-text-mobile {
        display: none;
    }

    @media (max-width: 767px) {
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero-title-main {
            font-size: <?php echo esc_attr($titleFontSizeMobile); ?>;
            line-height: <?php echo esc_attr($titleLineHeightMobile); ?>;
        }
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero-title-accent {
            font-size: <?php echo esc_attr($accentFontSizeMobile); ?>;
            line-height: <?php echo esc_attr($accentLineHeightMobile); ?>;
        }
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta {
            margin-top: <?php echo esc_attr($ctaMarginTopMobile); ?>;
        }
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta-text {
            font-size: <?php echo esc_attr($ctaFontSizeMobile); ?>;
        }
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta-text-desktop {
            display: none;
        }
        #<?php echo esc_attr($block_id); ?> .glownabaner__hero-cta-text-mobile {
            display: inline;
        }
    }
</style>

<section id="<?php echo esc_attr($block_id); ?>" class="glownabaner" style="padding-bottom: 120px;">
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
        <div class="glownabaner__hero-content">
            <h2 class="glownabaner__hero-title">
                <span class="glownabaner__hero-title-main">
                    <?php echo wp_kses_post($bannerTitle); ?>
                </span>
                <span class="glownabaner__hero-title-accent">
                    <?php echo wp_kses_post($bannerTitleAccent); ?>
                </span>
            </h2>
            <p class="glownabaner__hero-subtitle"><?php echo wp_kses_post($bannerSubtitle); ?></p>
            <a class="glownabaner__hero-cta" href="<?php echo esc_url(home_url($bannerCtaURL)); ?>">
                <span class="glownabaner__hero-cta-text glownabaner__hero-cta-text-desktop"><?php echo wp_kses_post($bannerCtaLabel); ?></span>
                <span class="glownabaner__hero-cta-text glownabaner__hero-cta-text-mobile"><?php echo wp_kses_post($bannerCtaLabelMobile); ?></span>
                <span class="glownabaner__hero-cta-arrow" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14m-6-7 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>