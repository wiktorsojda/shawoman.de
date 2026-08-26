<?php
$a = $attributes;

$title = !empty($a['title']) ? $a['title'] : "Wie funktioniert's?";
$description = !empty($a['description']) ? $a['description'] : '';

$steps = [
  ['image' => !empty($a['image1']) ? $a['image1'] : '', 'imageMobile' => !empty($a['imageMobile1']) ? $a['imageMobile1'] : ''],
  ['image' => !empty($a['image2']) ? $a['image2'] : '', 'imageMobile' => !empty($a['imageMobile2']) ? $a['imageMobile2'] : ''],
  ['image' => !empty($a['image3']) ? $a['image3'] : '', 'imageMobile' => !empty($a['imageMobile3']) ? $a['imageMobile3'] : ''],
];

// Helper to force https for mixed content issues
function shav_force_https($url) {
    if (empty($url)) return '';
    return str_replace('http://', 'https://', $url);
}
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
        <?php 
          $imgDesktop = shav_force_https($step['image']);
          $imgMobile = shav_force_https($step['imageMobile']);
        ?>
        <figure class="jakuzywac__step">
          <div class="jakuzywac__image-wrapper">
            <?php if (!empty($imgDesktop) || !empty($imgMobile)) : ?>
              <picture style="display: block; width: 100%; height: auto;">
                <?php if (!empty($imgMobile)) : ?>
                  <source media="(max-width: 767px)" srcset="<?php echo esc_url($imgMobile); ?>" />
                <?php endif; ?>
                <?php if (!empty($imgDesktop)) : ?>
                  <source media="(min-width: 768px)" srcset="<?php echo esc_url($imgDesktop); ?>" />
                  <img src="<?php echo esc_url($imgDesktop); ?>" alt="Krok <?php echo $index + 1; ?>" class="jakuzywac__image" />
                <?php elseif (!empty($imgMobile)) : ?>
                  <img src="<?php echo esc_url($imgMobile); ?>" alt="Krok <?php echo $index + 1; ?>" class="jakuzywac__image" />
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
