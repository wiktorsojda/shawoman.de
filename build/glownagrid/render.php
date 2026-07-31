<?php
$buttonLabel = isset($attributes['buttonLabel']) ? $attributes['buttonLabel'] : 'Zobacz';

$item_fallbacks = [
    1 => 'http://shavwoman.pl/wp-content/uploads/myjka-1000x1000-uzycie.png',
    2 => 'http://shavwoman.pl/wp-content/uploads/shav-woman-ostrze-beztla.png',
    3 => 'http://shavwoman.pl/wp-content/uploads/shav-woman-ostrze2-beztla.png',
    4 => 'http://shavwoman.pl/wp-content/uploads/4-1.png',
    5 => 'http://shavwoman.pl/wp-content/uploads/DSC03821-1.png',
    6 => 'http://shavwoman.pl/wp-content/uploads/golarka-dla-kobiet-produktowe2.jpg',
];

function glownagrid_get($attributes, $key, $default = '') {
    return isset($attributes[$key]) ? $attributes[$key] : $default;
}

function glownagrid_render_tile($attributes, $n, $fallback, $buttonLabel) {
    $img    = glownagrid_get($attributes, "item{$n}Image");
    if (!$img) { $img = $fallback; }
    $label  = glownagrid_get($attributes, "item{$n}Label");
    $accent = glownagrid_get($attributes, "item{$n}Accent");
    $url    = glownagrid_get($attributes, "item{$n}URL", '#');
    ?>
    <a class="glownagrid__tile glownagrid__tile--<?php echo (int) $n; ?>" href="<?php echo esc_url(home_url($url)); ?>">
        <img class="glownagrid__bg" src="<?php echo esc_url($img); ?>" alt="">
        <span class="glownagrid__label">
            <span><?php echo wp_kses_post($label); ?></span>
            <?php if ($accent): ?>
                <span class="glownagrid__label-accent"><?php echo wp_kses_post($accent); ?></span>
            <?php endif; ?>
        </span>
        <span class="glownagrid__cta">
            <span><?php echo wp_kses_post($buttonLabel); ?></span>
            <span class="glownagrid__cta-arrow" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14m-6-7 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </span>
    </a>
    <?php
}
?>
<section class="glownagrid">
    <div class="glownagrid__grid">
        <?php for ($n = 1; $n <= 6; $n++) {
            glownagrid_render_tile($attributes, $n, $item_fallbacks[$n], $buttonLabel);
        } ?>
    </div>
</section>
