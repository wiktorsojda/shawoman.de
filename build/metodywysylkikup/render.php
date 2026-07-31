<?php
$tagline      = isset($attributes['tagline'])      ? $attributes['tagline']      : 'Gwarantujemy';
$changeText1  = isset($attributes['changeText1'])  ? $attributes['changeText1']  : 'Wysoką jakość';
$changeText2  = isset($attributes['changeText2'])  ? $attributes['changeText2']  : 'Szybką wysyłkę';
$changeText3  = isset($attributes['changeText3'])  ? $attributes['changeText3']  : 'Gładkie 🥚🥚';
$changeText4  = isset($attributes['changeText4'])  ? $attributes['changeText4']  : 'Brak zacięć';
$heading      = isset($attributes['heading'])      ? $attributes['heading']      : '';
$description  = isset($attributes['description'])  ? $attributes['description']  : '';
$buttonLabel  = isset($attributes['buttonLabel'])  ? $attributes['buttonLabel']  : 'Kup teraz';
$buttonURL    = isset($attributes['buttonURL'])    ? $attributes['buttonURL']    : '/';
$image        = isset($attributes['image']) && $attributes['image']
    ? $attributes['image']
    : esc_url(home_url('/wp-content/uploads/shav-dwa-ostrza-mobile.jpg'));
?>
<section class="blacktext-container-wysylka container">
    <div class="metody-wysylki-kup-container container--narrow2-important">
        <div class="metody-wysylki-left-container">
            <div class="test-flex">
                <p class="kup-text"><?php echo wp_kses_post($tagline); ?>
                    <span class="changebox">
                        <span><?php echo wp_kses_post($changeText1); ?></span><br>
                        <span><?php echo wp_kses_post($changeText2); ?></span><br>
                        <span><?php echo wp_kses_post($changeText3); ?></span><br>
                        <span><?php echo wp_kses_post($changeText4); ?></span><br>
                    </span>
                </p>
            </div>
            <h2><?php echo wp_kses_post($heading); ?></h2>
            <p><?php echo wp_kses_post($description); ?></p>
            <a class="button-link" href="<?php echo esc_url(home_url($buttonURL)); ?>">
                <button><?php echo wp_kses_post($buttonLabel); ?></button>
            </a>
        </div>
        <div class="metody-wysylki-right-container">
            <img class="metody-wysylki-right-image" src="<?php echo esc_url($image); ?>" alt="">
        </div>
    </div>
</section>
