<?php
$videoURL        = isset($attributes['videoURL']) && $attributes['videoURL']
    ? $attributes['videoURL']
    : esc_url(home_url('/wp-content/uploads/animacja-shav-podstrony.mp4'));
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';
$title           = isset($attributes['title'])    ? $attributes['title']    : 'Patent i znak towarowy';
$subtitle        = isset($attributes['subtitle']) ? $attributes['subtitle'] : 'Poznaj szczegóły';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<div class="video-background-container-wysylka" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>>
    <?php if (!$backgroundImage && $videoURL) : ?>
        <video class="video-background-wysylka" src="<?php echo esc_url($videoURL); ?>" autoplay loop muted playsinline></video>
    <?php endif; ?>
    <section class="about-us-second-wysylka container--narrow2-important">
        <h1 class="about-us-second-title-wysylka">
            <span class="about-us-span-wysylka-first container--narrow2-important"><?php echo wp_kses_post($title); ?></span>
            <div class="about-us-span-wysylka-container">
                <span class="about-us-span-wysylka-second"><?php echo wp_kses_post($subtitle); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="15" viewBox="0 0 25 15" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.512563 0.858103C1.19598 0.158413 2.30402 0.158413 2.98744 0.858103L12.25 10.3412L21.5126 0.858103C22.196 0.158413 23.304 0.158413 23.9874 0.858103C24.6709 1.55779 24.6709 2.69221 23.9874 3.3919L13.4874 14.1419C12.804 14.8416 11.696 14.8416 11.0126 14.1419L0.512563 3.3919C-0.170854 2.69221 -0.170854 1.55779 0.512563 0.858103Z" fill="#065C70"/></svg>
            </div>
        </h1>
    </section>
</div>
