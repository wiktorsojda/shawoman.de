<?php
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';
?>
<section class="pudelko-container pudelko-container-mobile container" <?php echo $inline_style ? 'style="' . $inline_style . '"' : ''; ?>></section>
