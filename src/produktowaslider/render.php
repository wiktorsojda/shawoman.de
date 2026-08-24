<?php
/**
 * Sekcja slidera z galerią produktową (dynamiczna).
 * Używa biblioteki Swiper.js do zaawansowanych efektów (peek slider).
 */

if (!isset($attributes)) {
    $attributes = [];
}
$slides = $attributes['slides'] ?? [];
if (empty($slides)) {
    return;
}
?>

<div <?php echo get_block_wrapper_attributes(['class' => 'produktowaslider-block']); ?>>
    <!-- Biblioteka Swiper (CDN) -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <style>
        .produktowaslider-block .blendygo-gallery-container {
            margin-bottom: 20px;
        }
        .produktowaslider-block .blendygo-gallery-slider-desktop {
            display: none;
        }
        .produktowaslider-block .blendygo-gallery-slider-mobile {
            display: block;
            width: 100%;
        }
        @media (min-width: 768px) {
            .produktowaslider-block .blendygo-gallery-slider-desktop {
                display: block;
                width: 100%;
            }
            .produktowaslider-block .blendygo-gallery-slider-mobile {
                display: none;
            }
        }
        .produktowaslider-block img {
            width: 100%;
            height: auto;
            border-radius: 12px;
        }
    </style>

    <div class="blendygo-gallery-container">
        
            <!-- Desktop Slider -->
            <div class="swiper blendygo-gallery-slider-desktop">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide): ?>
                        <?php if (!empty($slide['desktopImage'])): ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($slide['desktopImage']); ?>" alt="<?php echo esc_attr($slide['altText'] ?? ''); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>

            <!-- Mobile Slider -->
            <div class="swiper blendygo-gallery-slider-mobile">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide): ?>
                        <?php if (!empty($slide['mobileImage'])): ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($slide['mobileImage']); ?>" alt="<?php echo esc_attr($slide['altText'] ?? ''); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            
    </div>

    <!-- Skrypt inicjalizujący Swiper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper !== 'undefined') {
                // Inicjalizacja slidera dla widoku desktop
                const blendygoDesktopSlider = new Swiper('.blendygo-gallery-slider-desktop', {
                    effect: 'slide',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    loop: true,
                    initialSlide: 1,
                    observer: true,
                    observeParents: true,
                    autoplay: {
                        delay: 3000,
                        pauseOnMouseEnter: false,
                        disableOnInteraction: false,
                        reverseDirection: true,
                    },
                    spaceBetween: 25,
                    speed: 700,
                    pagination: {
                        el: '.blendygo-gallery-slider-desktop .swiper-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        1000: {
                            spaceBetween: 30,
                        }
                    }
                });

                // Inicjalizacja slidera dla widoku mobilnego
                const blendygoMobileSlider = new Swiper('.blendygo-gallery-slider-mobile', {
                    effect: 'slide',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    loop: true,
                    initialSlide: 1,
                    observer: true,
                    observeParents: true,
                    autoplay: {
                        delay: 3000,
                        pauseOnMouseEnter: false,
                        disableOnInteraction: false,
                        reverseDirection: true,
                    },
                    spaceBetween: 15,
                    speed: 700,
                    pagination: {
                        el: '.blendygo-gallery-slider-mobile .swiper-pagination',
                        clickable: true,
                    },
                });
            }
        });
    </script>
</div>
