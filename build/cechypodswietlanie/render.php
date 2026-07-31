<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : 'Podświetlenie obszaru golenia';
$description     = isset($attributes['description'])     ? $attributes['description']     : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<section class="container mobile-cechy-container cechy-mobile-container-4">
    <div class="text-cechy-container-mobile cechy-mobile-4">
        <div class="line line-head"><?php echo wp_kses_post($title); ?></div>
        <div class="line line-rest"><?php echo wp_kses_post($description); ?></div>

    </div>
</section>
<section class="cechy-container cechy-image-container cechy-image-4 container" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>>
    <div class="cechy-inner-wrapper-start">
        <div class="container--narrow2-important content-position-helper-start">
            <div class="text-cechy-container cechy-4">
                <div class="line line-head desktop-cechy"><?php echo wp_kses_post($title); ?></div>
                <div class="line line-rest desktop-cechy"><?php echo wp_kses_post($description); ?></div>

            </div>
        </div>
    </div>
</section>
