<?php
$title       = isset($attributes['title'])       ? $attributes['title']       : '';
$description = isset($attributes['description']) ? $attributes['description'] : '';
?>
<section class="blacktext-container container">
    <div id="text-container">
        <div class="line line-head"><?php echo wp_kses_post($title); ?></div>
        <div class="line line-rest"><?php echo wp_kses_post($description); ?></div>
    </div>
</section>
