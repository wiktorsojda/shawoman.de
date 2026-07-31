<?php
$videoURL        = isset($attributes['videoURL'])        ? $attributes['videoURL']        : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';
$title           = isset($attributes['title'])           ? $attributes['title']           : '';
$subtitle        = isset($attributes['subtitle'])        ? $attributes['subtitle']        : '';
$description     = isset($attributes['description'])     ? $attributes['description']     : '';

$inline_style = $backgroundImage
    ? 'background-image:url(' . esc_url($backgroundImage) . ');background-size:cover;background-position:center;'
    : '';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'video-background-container',
    'style' => $inline_style,
]);
?>
<div <?php echo $wrapper_attrs; ?>>
    <?php if ($videoURL) : ?>
        <video class="video-background" src="<?php echo esc_url($videoURL); ?>" autoplay loop muted playsinline></video>
    <?php endif; ?>
    <div class="content-overlay">
        <?php if ($title) : ?>
            <h1 class="title"><?php echo wp_kses_post($title); ?></h1>
        <?php endif; ?>
        <?php if ($subtitle) : ?>
            <h2 class="subtitle"><?php echo wp_kses_post($subtitle); ?></h2>
        <?php endif; ?>
        <?php if ($description) : ?>
            <p class="description"><?php echo wp_kses_post($description); ?></p>
        <?php endif; ?>
    </div>
</div>
