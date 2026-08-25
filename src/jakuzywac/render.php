<?php
$a = $attributes;

$title = !empty($a['title']) ? $a['title'] : "Wie funktioniert's?";
$description = !empty($a['description']) ? $a['description'] : '';

$steps = [
  ['image' => !empty($a['image1']) ? $a['image1'] : '', 'imageMobile' => !empty($a['imageMobile1']) ? $a['imageMobile1'] : ''],
  ['image' => !empty($a['image2']) ? $a['image2'] : '', 'imageMobile' => !empty($a['imageMobile2']) ? $a['imageMobile2'] : ''],
  ['image' => !empty($a['image3']) ? $a['image3'] : '', 'imageMobile' => !empty($a['imageMobile3']) ? $a['imageMobile3'] : ''],
];
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
            <?php if (!empty($step['image']) || !empty($step['imageMobile'])) : ?>
              <picture>
                <?php if (!empty($step['imageMobile'])) : ?>
                  <source media="(max-width: 767px)" srcset="<?php echo esc_url($step['imageMobile']); ?>" />
                <?php endif; ?>
                <?php if (!empty($step['image'])) : ?>
                  <source media="(min-width: 768px)" srcset="<?php echo esc_url($step['image']); ?>" />
                  <img src="<?php echo esc_url($step['image']); ?>" alt="Krok <?php echo $index + 1; ?>" class="jakuzywac__image" />
                <?php elseif (!empty($step['imageMobile'])) : ?>
                  <img src="<?php echo esc_url($step['imageMobile']); ?>" alt="Krok <?php echo $index + 1; ?>" class="jakuzywac__image" />
                <?php endif; ?>
              </picture>
            <?php else : ?>
              <div class="jakuzywac__image-placeholder"></div>
            <?php endif; ?>
          </div>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
