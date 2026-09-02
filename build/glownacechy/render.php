<?php
$title = isset($attributes['title']) && !empty($attributes['title']) ? $attributes['title'] : '';
if (!$title) {
    // Fallback do starych atrybutów
    $titleLine1 = isset($attributes['titleLine1']) ? $attributes['titleLine1'] : 'Maszynka';
    $titleLine2Before = isset($attributes['titleLine2Before']) ? $attributes['titleLine2Before'] : 'Shav ';
    $titleLine2Accent = isset($attributes['titleLine2Accent']) ? $attributes['titleLine2Accent'] : 'woman.';
    $title = $titleLine1 . '<br>' . $titleLine2Before . $titleLine2Accent;
}
$accentWord = isset($attributes['accentWord']) ? $attributes['accentWord'] : 'woman.';

$image = isset($attributes['image']) && $attributes['image']
    ? $attributes['image']
    : esc_url(home_url('/wp-content/uploads/2026/05/Frame-7173.png'));
$imageAlt = isset($attributes['imageAlt']) ? $attributes['imageAlt'] : '';
?>
<section class="glownacechy" style="padding-bottom: 120px;">
    <div class="glownacechy__inner">
        <div class="glownacechy__col">
            <h1 class="glownacechy__title">
                <span class="glownacechy__title-line">
                    <?php 
                    if (function_exists('shav_highlight_accent_word')) {
                        echo wp_kses_post(shav_highlight_accent_word($title, $accentWord, 'glownacechy__title-accent'));
                    } else {
                        echo wp_kses_post($title);
                    }
                    ?>
                </span>
            </h1>

            <ul class="glownacechy__list">
                <?php for ($i = 1; $i <= 4; $i++):
                    $t = isset($attributes["feature{$i}Title"]) ? $attributes["feature{$i}Title"] : '';
                    $s = isset($attributes["feature{$i}Sub"]) ? $attributes["feature{$i}Sub"] : '';
                    if (!$t && !$s)
                        continue;
                    $is_active_class = $i === 1 ? ' is-active' : '';
                    ?>
                    <li class="glownacechy__item<?php echo $is_active_class; ?>">
                        <span class="glownacechy__bar" aria-hidden="true"></span>
                        <div class="glownacechy__text">
                            <p class="glownacechy__item-title"><?php echo wp_kses_post($t); ?></p>
                            <p class="glownacechy__item-sub"><?php echo wp_kses_post($s); ?></p>
                        </div>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>

        <div class="glownacechy__media">
            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($imageAlt); ?>">
        </div>
    </div>
</section>