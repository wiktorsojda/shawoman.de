<?php
function render_video_background_block($attributes) {
    if (!isset($attributes['videoURL'])) {
        return '';
    }

    $title = isset($attributes['title']) ? $attributes['title'] : '';
    $subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : '';
    $description = isset($attributes['description']) ? $attributes['description'] : '';

    ob_start();
    ?>
    <div class="wp-block-mytheme-video-background video-background-container">
        <video class="video-background" src="<?php echo esc_url($attributes['videoURL']); ?>" autoplay loop muted playsinline></video>
        <div class="content-overlay">
            <?php if ($title) : ?>
                <h1 class="title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>
            <?php if ($subtitle) : ?>
                <h2 class="subtitle"><?php echo esc_html($subtitle); ?></h2>
            <?php endif; ?>
            <?php if ($description) : ?>
                <p class="description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}