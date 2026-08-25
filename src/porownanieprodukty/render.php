<?php
$a = $attributes;

// Fallbacki i wartości
$leftImage = !empty($a['leftImage']) ? $a['leftImage'] : get_template_directory_uri() . '/images/rosegold/prod-1.png'; // Tymczasowy fallback
$rightImage = !empty($a['rightImage']) ? $a['rightImage'] : get_template_directory_uri() . '/images/rosegold/prod-2.png';
$leftTitle = !empty($a['leftTitle']) ? $a['leftTitle'] : 'Shav Woman';
$rightTitle = !empty($a['rightTitle']) ? $a['rightTitle'] : 'Jednorazówka';
$features = !empty($a['features']) && is_array($a['features']) ? $a['features'] : [];
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'porownanieprodukty']); ?>>
  <div class="porownanieprodukty__inner">
    <div class="porownanieprodukty__header">
      <div class="porownanieprodukty__product porownanieprodukty__product--left">
        <?php if ($leftImage) : ?>
          <img src="<?php echo esc_url($leftImage); ?>" alt="Shav Woman" class="porownanieprodukty__image" />
        <?php endif; ?>
        <h3 class="porownanieprodukty__title"><?php echo wp_kses_post($leftTitle); ?></h3>
      </div>
      <div class="porownanieprodukty__vs">VS</div>
      <div class="porownanieprodukty__product porownanieprodukty__product--right">
        <?php if ($rightImage) : ?>
          <img src="<?php echo esc_url($rightImage); ?>" alt="Jednorazówka" class="porownanieprodukty__image" />
        <?php endif; ?>
        <h3 class="porownanieprodukty__title"><?php echo wp_kses_post($rightTitle); ?></h3>
      </div>
    </div>

    <div class="porownanieprodukty__features">
      <?php foreach ($features as $feature) : ?>
        <div class="porownanieprodukty__row">
          <!-- Desktop: Pokazuje nagłówek na środku. Mobile: Ukryte -->
          <div class="porownanieprodukty__feature porownanieprodukty__feature--left">
            <span class="porownanieprodukty__dot porownanieprodukty__dot--green"></span>
            <span class="porownanieprodukty__text"><?php echo wp_kses_post($feature['left']); ?></span>
          </div>
          
          <div class="porownanieprodukty__label">
            <?php echo wp_kses_post($feature['center']); ?>
          </div>
          
          <div class="porownanieprodukty__feature porownanieprodukty__feature--right">
            <span class="porownanieprodukty__dot porownanieprodukty__dot--red"></span>
            <span class="porownanieprodukty__text"><?php echo wp_kses_post($feature['right']); ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
