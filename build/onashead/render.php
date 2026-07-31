<?php
$brand           = isset($attributes['brand'])           ? $attributes['brand']           : 'Shav';
$headline        = isset($attributes['headline'])        ? $attributes['headline']        : 'Kim jesteśmy?';
$description     = isset($attributes['description'])     ? $attributes['description']     : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'onas-head-container container mobile-onas-head',
    'style' => $inline_style,
]);
?>
<section <?php echo $wrapper_attrs; ?>>
    <div id="text-container">
        <div class="text-container-head">
            <h1 class="line line-head-1"><?php echo wp_kses_post($brand); ?></h1>
            <h2 class="line line-head-2"><?php echo wp_kses_post($headline); ?></h2>
        </div>
        <div class="line line-rest"><?php echo wp_kses_post($description); ?></div>
    </div>
</section>
<section class="onas-head-container-mobile container mobile-cechy-container cechy-mobile-container-2">
    <div id="text-container">
        <div class="text-container-head">
            <h1 class="line line-head-1"><?php echo wp_kses_post($brand); ?></h1>
            <h2 class="line line-head-2"><?php echo wp_kses_post($headline); ?></h2>
        </div>
        <div class="line line-rest"><?php echo wp_kses_post($description); ?></div>
    </div>
</section>
