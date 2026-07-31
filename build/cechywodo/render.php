<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : 'NIEWIARYGODNIE WYGODNY';
$description     = isset($attributes['description'])     ? $attributes['description']     : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<section class="container mobile-cechy-container cechy-mobile-container-2">
    <div class="text-cechy-container-mobile cechy-mobile-2">
        <div class="line line-head"><?php echo wp_kses_post($title); ?></div>
        <div class="line line-rest"><?php echo wp_kses_post($description); ?></div>
            <div class="features-flex">
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72" fill="none"><path d="M33.75 14.25V3H38.25V14.25H33.75ZM52.95 22.2L49.8 19.05L57.75 11.025L60.9 14.25L52.95 22.2ZM57.75 38.25V33.75H69V38.25H57.75ZM33.75 69V57.75H38.25V69H33.75ZM18.975 22.125L11.1 14.25L14.25 11.1L22.2 19.05L18.975 22.125ZM57.825 60.9L49.8 52.95L52.875 49.875L60.975 57.675L57.825 60.9ZM3 38.25V33.75H14.25V38.25H3ZM14.325 60.9L11.1 57.75L18.975 49.875L20.625 51.375L22.275 52.95L14.325 60.9ZM36 54C31 54 26.75 52.25 23.25 48.75C19.75 45.25 18 41 18 36C18 31 19.75 26.75 23.25 23.25C26.75 19.75 31 18 36 18C41 18 45.25 19.75 48.75 23.25C52.25 26.75 54 31 54 36C54 41 52.25 45.25 48.75 48.75C45.25 52.25 41 54 36 54ZM36 49.5C39.75 49.5 42.9375 48.1875 45.5625 45.5625C48.1875 42.9375 49.5 39.75 49.5 36C49.5 32.25 48.1875 29.0625 45.5625 26.4375C42.9375 23.8125 39.75 22.5 36 22.5C32.25 22.5 29.0625 23.8125 26.4375 26.4375C23.8125 29.0625 22.5 32.25 22.5 36C22.5 39.75 23.8125 42.9375 26.4375 45.5625C29.0625 48.1875 32.25 49.5 36 49.5Z" fill="white"/></svg>
                    <div class="feature-title"><?php echo wp_kses_post(isset($attributes['feature1Title']) ? $attributes['feature1Title'] : ''); ?></div>
                </div>
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72" fill="none"><path d="M47.85 62.475L40.425 55.05L43.575 51.9L47.85 56.1L58.425 45.525L61.575 48.675L47.85 62.475ZM21 66V10.8H30V6H42V10.8H51V38.025C50.2001 38.025 49.425 38.075 48.675 38.175C47.925 38.2751 47.2001 38.4499 46.5 38.7V15.3H25.5V61.5H36.9C37.35 62.3501 37.875 63.15 38.475 63.9C39.075 64.65 39.725 65.3501 40.425 66H21Z" fill="white"/></svg>
                    <div class="feature-title"><?php echo wp_kses_post(isset($attributes['feature2Title']) ? $attributes['feature2Title'] : ''); ?></div>
                </div>
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72" fill="none"><path d="M27.9531 36H44.0688V44.0438C44.0691 45.4492 43.5186 46.7988 42.5353 47.803C41.5521 48.8073 40.2145 49.3862 38.8094 49.4156H33.1844C31.7597 49.4156 30.3933 48.8497 29.3859 47.8422C28.3785 46.8348 27.8125 45.4685 27.8125 44.0438V36H27.9531Z" stroke="white" stroke-width="4" stroke-miterlimit="10"/><path d="M30.625 25.2562H41.3688V36H30.625V25.2562Z" stroke="white" stroke-width="4" stroke-miterlimit="10"/><path d="M35.9967 49.4156V59.5687C35.9855 60.3576 36.1509 61.1391 36.481 61.8558C36.811 62.5724 37.2972 63.2062 37.904 63.7105C38.5109 64.2148 39.2228 64.5769 39.9878 64.7703C40.7527 64.9637 41.5513 64.9834 42.3249 64.8281C48.7218 63.4175 54.4673 59.9182 58.6558 54.8817C62.8443 49.8452 65.2376 43.5579 65.4583 37.0111C65.679 30.4642 63.7146 24.0301 59.8748 18.7229C56.035 13.4158 50.5382 9.53745 44.2508 7.69929C37.9634 5.86114 31.243 6.16772 25.1491 8.5707C19.0552 10.9737 13.9343 15.3364 10.5937 20.9711C7.25304 26.6059 5.88265 33.1921 6.69853 39.6917C7.51441 46.1912 10.4702 52.2345 15.0998 56.8687" stroke="white" stroke-width="4" stroke-miterlimit="10"/></svg>
                    <div class="feature-title"><?php echo wp_kses_post(isset($attributes['feature3Title']) ? $attributes['feature3Title'] : ''); ?></div>
                </div>
            </div>
    </div>
</section>
<section class="cechy-container cechy-image-container cechy-image-2 container" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>>
    <div class="cechy-inner-wrapper-start">
        <div class="container--narrow2-important content-position-helper-start">
            <div class="text-cechy-container cechy-2">
                <div class="line line-head desktop-cechy"><?php echo wp_kses_post($title); ?></div>
                <div class="line line-rest desktop-cechy"><?php echo wp_kses_post($description); ?></div>
            <div class="features-flex">
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72" fill="none"><path d="M33.75 14.25V3H38.25V14.25H33.75ZM52.95 22.2L49.8 19.05L57.75 11.025L60.9 14.25L52.95 22.2ZM57.75 38.25V33.75H69V38.25H57.75ZM33.75 69V57.75H38.25V69H33.75ZM18.975 22.125L11.1 14.25L14.25 11.1L22.2 19.05L18.975 22.125ZM57.825 60.9L49.8 52.95L52.875 49.875L60.975 57.675L57.825 60.9ZM3 38.25V33.75H14.25V38.25H3ZM14.325 60.9L11.1 57.75L18.975 49.875L20.625 51.375L22.275 52.95L14.325 60.9ZM36 54C31 54 26.75 52.25 23.25 48.75C19.75 45.25 18 41 18 36C18 31 19.75 26.75 23.25 23.25C26.75 19.75 31 18 36 18C41 18 45.25 19.75 48.75 23.25C52.25 26.75 54 31 54 36C54 41 52.25 45.25 48.75 48.75C45.25 52.25 41 54 36 54ZM36 49.5C39.75 49.5 42.9375 48.1875 45.5625 45.5625C48.1875 42.9375 49.5 39.75 49.5 36C49.5 32.25 48.1875 29.0625 45.5625 26.4375C42.9375 23.8125 39.75 22.5 36 22.5C32.25 22.5 29.0625 23.8125 26.4375 26.4375C23.8125 29.0625 22.5 32.25 22.5 36C22.5 39.75 23.8125 42.9375 26.4375 45.5625C29.0625 48.1875 32.25 49.5 36 49.5Z" fill="white"/></svg>
                    <div class="feature-title"><?php echo wp_kses_post(isset($attributes['feature1Title']) ? $attributes['feature1Title'] : ''); ?></div>
                </div>
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72" fill="none"><path d="M47.85 62.475L40.425 55.05L43.575 51.9L47.85 56.1L58.425 45.525L61.575 48.675L47.85 62.475ZM21 66V10.8H30V6H42V10.8H51V38.025C50.2001 38.025 49.425 38.075 48.675 38.175C47.925 38.2751 47.2001 38.4499 46.5 38.7V15.3H25.5V61.5H36.9C37.35 62.3501 37.875 63.15 38.475 63.9C39.075 64.65 39.725 65.3501 40.425 66H21Z" fill="white"/></svg>
                    <div class="feature-title"><?php echo wp_kses_post(isset($attributes['feature2Title']) ? $attributes['feature2Title'] : ''); ?></div>
                </div>
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 72 72" fill="none"><path d="M27.9531 36H44.0688V44.0438C44.0691 45.4492 43.5186 46.7988 42.5353 47.803C41.5521 48.8073 40.2145 49.3862 38.8094 49.4156H33.1844C31.7597 49.4156 30.3933 48.8497 29.3859 47.8422C28.3785 46.8348 27.8125 45.4685 27.8125 44.0438V36H27.9531Z" stroke="white" stroke-width="4" stroke-miterlimit="10"/><path d="M30.625 25.2562H41.3688V36H30.625V25.2562Z" stroke="white" stroke-width="4" stroke-miterlimit="10"/><path d="M35.9967 49.4156V59.5687C35.9855 60.3576 36.1509 61.1391 36.481 61.8558C36.811 62.5724 37.2972 63.2062 37.904 63.7105C38.5109 64.2148 39.2228 64.5769 39.9878 64.7703C40.7527 64.9637 41.5513 64.9834 42.3249 64.8281C48.7218 63.4175 54.4673 59.9182 58.6558 54.8817C62.8443 49.8452 65.2376 43.5579 65.4583 37.0111C65.679 30.4642 63.7146 24.0301 59.8748 18.7229C56.035 13.4158 50.5382 9.53745 44.2508 7.69929C37.9634 5.86114 31.243 6.16772 25.1491 8.5707C19.0552 10.9737 13.9343 15.3364 10.5937 20.9711C7.25304 26.6059 5.88265 33.1921 6.69853 39.6917C7.51441 46.1912 10.4702 52.2345 15.0998 56.8687" stroke="white" stroke-width="4" stroke-miterlimit="10"/></svg>
                    <div class="feature-title"><?php echo wp_kses_post(isset($attributes['feature3Title']) ? $attributes['feature3Title'] : ''); ?></div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
