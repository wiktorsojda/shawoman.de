<?php
$title    = isset($attributes['title'])    ? $attributes['title']    : '';
$subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : '';
$content  = isset($attributes['content'])  ? $attributes['content']  : '';
?>
<section class="faq-container">
    <div class="container--narrow2-important">
        <div class="regulamin-title"><?php echo wp_kses_post($title); ?></div>
        <div class="regulamin-subtitle"><?php echo wp_kses_post($subtitle); ?></div>
        <ul class="regulamin-list">
            <?php echo wp_kses_post($content); ?>
        </ul>
    </div>
</section>
