<?php
$tagline     = isset($attributes['tagline'])     ? $attributes['tagline']     : 'Ochrona prawna';
$title       = isset($attributes['title'])       ? $attributes['title']       : 'Jedyna w swoim rodzaju';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$image       = isset($attributes['image']) && $attributes['image']
    ? $attributes['image']
    : esc_url(home_url('/wp-content/uploads/shav-design-pudelko.png'));
?>
<section class="blacktext-container-wysylka container">
    <div class="metody-wysylki-kup-container container--narrow2-important">
        <div class="metody-wysylki-left-container">
            <div class="test-flex" id="patnet-flex-test">
                <p id="patent-text"><?php echo wp_kses_post($tagline); ?></p>
            </div>
            <h2><?php echo wp_kses_post($title); ?></h2>
            <p><?php echo wp_kses_post($description); ?></p>
        </div>
        <div>
            <img class="metody-wysylki-right-image" src="<?php echo esc_url($image); ?>" alt="">
        </div>
    </div>
</section>
