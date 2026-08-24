<?php
/**
 * Sekcja slidera z galerią produktową (dynamiczna).
 * Używa biblioteki Swiper.js do zaawansowanych efektów (peek slider).
 */

if (!isset($attributes)) {
    $attributes = [];
}
$slides = $attributes['slides'] ?? [];
$original_count = count($slides);
if (empty($slides)) {
    return;
}

// Ensure enough slides for a seamless infinite loop (Swiper needs more slides for 'auto' width)
if ($original_count > 0 && $original_count < 6) {
    $original_slides = $slides;
    while(count($slides) < 6) {
        $slides = array_merge($slides, $original_slides);
    }
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
                const originalCount = <?php echo $original_count; ?>;
                
                function fixPagination(swiper) {
                    if (!swiper.pagination || !swiper.pagination.bullets) return;
                    const bullets = Array.from(swiper.pagination.bullets);
                    const targetIndex = swiper.realIndex % originalCount;
                    
                    bullets.forEach((b, i) => {
                        if (i >= originalCount) {
                            b.style.display = 'none';
                        }
                        b.classList.remove('swiper-pagination-bullet-active');
                    });
                    
                    if (bullets[targetIndex]) {
                        bullets[targetIndex].classList.add('swiper-pagination-bullet-active');
                    }
                }

                // Inicjalizacja slidera dla widoku desktop
                const blendygoDesktopSlider = new Swiper('.blendygo-gallery-slider-desktop', {
                    effect: 'slide',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    loop: true,
                    loopAdditionalSlides: 5,
                    loopedSlides: 5,
                    initialSlide: 0,
                    observer: true,
                    observeParents: true,
                    autoplay: {
                        delay: 3000,
                        pauseOnMouseEnter: false,
                        disableOnInteraction: false,
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
                    },
                    on: {
                        paginationUpdate: function (swiper) {
                            fixPagination(swiper);
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
                    loopAdditionalSlides: 5,
                    loopedSlides: 5,
                    initialSlide: 0,
                    observer: true,
                    observeParents: true,
                    autoplay: {
                        delay: 3000,
                        pauseOnMouseEnter: false,
                        disableOnInteraction: false,
                    },
                    spaceBetween: 15,
                    speed: 700,
                    pagination: {
                        el: '.blendygo-gallery-slider-mobile .swiper-pagination',
                        clickable: true,
                    },
                    on: {
                        paginationUpdate: function (swiper) {
                            fixPagination(swiper);
                        }
                    }
                });
            }
        });
    </script>
</div>
