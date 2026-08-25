<?php
$a = $attributes;

$title = !empty($a['title']) ? $a['title'] : "Wie funktioniert's?";
$description = !empty($a['description']) ? $a['description'] : '';
$steps = !empty($a['steps']) && is_array($a['steps']) ? $a['steps'] : [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'jakuzywac']); ?>>
  <div class="jakuzywac__inner">
    <div class="jakuzywac__content">
      <h2 class="jakuzywac__title"><?php echo wp_kses_post($title); ?></h2>
      <div class="jakuzywac__desc">
        <?php echo wp_kses_post($description); ?>
      </div>
    </div>
    
    <div class="jakuzywac__gallery">
      <?php foreach ($steps as $index => $step) : ?>
        <figure class="jakuzywac__step">
          <div class="jakuzywac__image-wrapper">
            <?php if (!empty($step['image'])) : ?>
              <img src="<?php echo esc_url($step['image']); ?>" alt="<?php echo esc_attr(strip_tags($step['label'])); ?>" class="jakuzywac__image" />
            <?php else : ?>
              <div class="jakuzywac__image-placeholder"></div>
            <?php endif; ?>
          </div>
          <figcaption class="jakuzywac__caption">
            <span class="jakuzywac__label"><?php echo wp_kses_post($step['label']); ?></span>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
