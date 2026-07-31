<?php
$topTitle       = isset($attributes['topTitle'])       ? $attributes['topTitle']       : 'Zarejestrowano';
$topDescription = isset($attributes['topDescription']) ? $attributes['topDescription'] : '';
$znakImage      = isset($attributes['znakImage']) && $attributes['znakImage']
    ? $attributes['znakImage']
    : esc_url(home_url('/wp-content/uploads/shav-znak-towarowy.png'));
$znakLabel      = isset($attributes['znakLabel'])      ? $attributes['znakLabel']      : 'ZNAK TOWAROWY';
$wzorImage      = isset($attributes['wzorImage']) && $attributes['wzorImage']
    ? $attributes['wzorImage']
    : esc_url(home_url('/wp-content/uploads/shav-wzor-przemyslowy.png'));
$wzorLabel      = isset($attributes['wzorLabel'])      ? $attributes['wzorLabel']      : 'WZÓR PRZEMYSŁOWY';
$pdfTitle       = isset($attributes['pdfTitle'])       ? $attributes['pdfTitle']       : 'podgląd dokumentów';
$pdfLinkText    = isset($attributes['pdfLinkText'])    ? $attributes['pdfLinkText']    : 'Znak towarowy SHAV';
$pdfLinkURL     = isset($attributes['pdfLinkURL'])     ? $attributes['pdfLinkURL']     : '';
?>
<section class="patent-father-container">
    <div class="container--narrow2-important patent-container">
        <div class="zarejestrowano-container">
            <div class="zarejestrowano-title-subcontainer">
                <div><?php echo wp_kses_post($topTitle); ?></div>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <path d="M16.001 30.3999C14.5344 30.3999 13.4677 29.1999 12.6677 28.2666C12.5344 28.1333 12.401 27.9999 12.2677 27.8666C11.4677 27.0666 11.201 26.9333 10.1344 26.9333C10.001 26.9333 9.86771 26.9333 9.60104 26.9333C8.53437 26.9333 7.06771 27.0666 6.00104 25.9999C4.93437 24.9333 5.06771 23.4666 5.06771 22.3999C5.06771 22.2666 5.06771 21.9999 5.06771 21.8666C5.06771 20.7999 4.93437 20.3999 4.13437 19.5999C2.53437 18.1333 1.73438 17.3333 1.73438 15.9999C1.73438 14.6666 2.53437 13.8666 4.13437 12.2666C4.80104 11.5999 5.06771 10.9333 5.06771 9.99994C5.06771 9.86661 5.06771 9.73328 5.06771 9.46661C5.06771 8.39994 4.93437 6.93328 6.00104 5.86661C7.06771 4.79994 8.53437 4.93328 9.60104 4.93328C10.801 4.93328 11.4677 4.79994 12.2677 3.99994C13.8677 2.53328 14.6677 1.73328 16.001 1.73328C17.3344 1.73328 18.1344 2.53328 19.7344 4.13328C20.5344 4.93328 21.201 5.06661 22.401 5.06661C23.4677 5.06661 24.9344 4.93328 26.001 5.99994C27.0677 7.06661 26.9344 8.53328 26.9344 9.59994V10.1333C26.9344 11.1999 27.0677 11.5999 27.8677 12.3999C29.4677 13.9999 30.2677 14.7999 30.2677 16.1333C30.2677 17.4666 29.4677 18.2666 27.8677 19.8666C27.0677 20.6666 26.9344 21.0666 26.9344 22.1333V22.6666C26.9344 23.7333 27.0677 25.3333 26.001 26.2666C24.9344 27.1999 23.4677 27.1999 22.401 27.1999C22.2677 27.1999 22.001 27.1999 21.8677 27.1999C20.801 27.1999 20.401 27.3333 19.7344 28.1333C19.601 28.2666 19.4677 28.3999 19.3344 28.5333C18.5344 29.1999 17.4677 30.3999 16.001 30.3999Z" fill="#0983A0"/>
                </svg>
            </div>
            <div class="zarejestrowano-rest"><?php echo wp_kses_post($topDescription); ?></div>
        </div>
        <div class="znak-container">
            <div class="znak-subcontainer">
                <div class="znak-image-container">
                    <img src="<?php echo esc_url($znakImage); ?>" alt="">
                </div>
                <div class="znak-subcontainer-text">
                    <span><?php echo wp_kses_post($znakLabel); ?></span>
                </div>
            </div>
            <div class="wzor-subcontainer">
                <div class="wzor-subcontainer-text">
                    <span><?php echo wp_kses_post($wzorLabel); ?></span>
                </div>
                <div class="wzor-image-container">
                    <img src="<?php echo esc_url($wzorImage); ?>" alt="">
                </div>
            </div>
        </div>
        <div class="zarejestrowano-container">
            <div class="zarejestrowano-title-subcontainer">
                <div><span><?php echo wp_kses_post($pdfTitle); ?></span></div>
            </div>
        </div>
        <div class="znak-dokumenty">
            <a href="<?php echo esc_url($pdfLinkURL); ?>" target="_blank" rel="noopener noreferrer"><?php echo wp_kses_post($pdfLinkText); ?></a>
        </div>
    </div>
</section>
