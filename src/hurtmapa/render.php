<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : '';
$description     = isset($attributes['description'])     ? $attributes['description']     : '';
$stat1           = isset($attributes['stat1'])           ? $attributes['stat1']           : '';
$stat2           = isset($attributes['stat2'])           ? $attributes['stat2']           : '';
$stat3           = isset($attributes['stat3'])           ? $attributes['stat3']           : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<section class="container mobile-cechy-container cechy-mobile-container-1 mapa-cechy">
    <div class="text-cechy-container-mobile cechy-mobile-1">
        <div class="line line-head"><?php echo wp_kses_post($title); ?></div>
        <div class="line line-rest"><?php echo wp_kses_post($description); ?></div>
    </div>
</section>
<section class="cechy-container cechy-image-container cechy-image-hurt container" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>>
    <div class="cechy-inner-wrapper cechy-inner-wrapper-mapa">
        <div class="container--narrow2-important content-position-helper-mapa">
            <div class="text-cechy-container text-cechy-container-mapa cechy-1">
                <div class="line line-head desktop-cechy"><?php echo wp_kses_post($title); ?></div>
                <div class="line line-rest desktop-cechy"><?php echo wp_kses_post($description); ?></div>
            </div>
        </div>
        <div class="container--narrow2-important content-position-helper-mapa-text">
            <span><?php echo wp_kses_post($stat1); ?></span>
            <span><?php echo wp_kses_post($stat2); ?></span>
            <span><?php echo wp_kses_post($stat3); ?></span>
        </div>
    </div>
</section>
