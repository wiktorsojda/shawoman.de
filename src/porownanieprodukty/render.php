<?php
$a = $attributes;

// Fallbacki i wartości
$leftImage = !empty($a['leftImage']) ? $a['leftImage'] : get_template_directory_uri() . '/images/rosegold/prod-1.png'; // Tymczasowy fallback
$rightImage = !empty($a['rightImage']) ? $a['rightImage'] : get_template_directory_uri() . '/images/rosegold/prod-2.png';
$leftTitle = !empty($a['leftTitle']) ? $a['leftTitle'] : 'Shav Woman';
$rightTitle = !empty($a['rightTitle']) ? $a['rightTitle'] : 'Jednorazówka';
$features = !empty($a['features']) && is_array($a['features']) ? $a['features'] : [];

require_once get_theme_file_path('/src/components/spacing-helper.php');
$block_id = uniqid('shav-block-');
shav_render_responsive_spacing_css($block_id, $a);

$wrapper_attributes = get_block_wrapper_attributes(['class' => "porownanieprodukty $block_id"]);
?>
<section <?php echo $wrapper_attributes; ?>>
  <div class="porownanieprodukty__inner">
    <div class="porownanieprodukty__header">
      <div class="porownanieprodukty__product porownanieprodukty__product--left">
        <?php if ($leftImage): ?>
          <img src="<?php echo esc_url($leftImage); ?>" alt="Shav Woman" class="porownanieprodukty__image"
            style="max-width: 155px; height: 260px; object-fit: contain; width: 100%;" />
        <?php endif; ?>
        <h3 class="porownanieprodukty__title"><?php echo wp_kses_post($leftTitle); ?></h3>
      </div>
      <div class="porownanieprodukty__vs">VS</div>
      <div class="porownanieprodukty__product porownanieprodukty__product--right">
        <?php if ($rightImage): ?>
          <img src="<?php echo esc_url($rightImage); ?>" alt="Jednorazówka" class="porownanieprodukty__image"
            style="max-width: 155px; height: 260px; object-fit: contain; width: 100%;" />
        <?php endif; ?>
        <h3 class="porownanieprodukty__title"><?php echo wp_kses_post($rightTitle); ?></h3>
      </div>
    </div>

    <div class="porownanieprodukty__features">
      <?php foreach ($features as $feature):
        if (!is_array($feature)) continue;
        $f_left = isset($feature['left']) ? $feature['left'] : '';
        $f_center = isset($feature['center']) ? $feature['center'] : '';
        $f_right = isset($feature['right']) ? $feature['right'] : '';
      ?>
        <div class="porownanieprodukty__row">
          <!-- Desktop: Pokazuje nagłówek na środku. Mobile: Ukryte -->
          <div class="porownanieprodukty__feature porownanieprodukty__feature--left">
            <svg class="porownanieprodukty__dot" style="width: 18px; height: 18px; flex-shrink: 0;" width="18" height="18" viewBox="0 0 18 18" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <foreignObject x="-4" y="-4" width="26" height="26">
                <div xmlns="http://www.w3.org/1999/xhtml"
                  style="backdrop-filter:blur(2px);clip-path:url(#bgblur_0_1774_2246_clip_path);height:100%;width:100%">
                </div>
              </foreignObject>
              <g data-figma-bg-blur-radius="4">
                <rect width="18" height="18" rx="9" fill="#CFF2D6" />
                <rect x="4" y="4" width="10" height="10" rx="5" fill="#2D773D" />
              </g>
              <defs>
                <clipPath id="bgblur_0_1774_2246_clip_path" transform="translate(4 4)">
                  <rect width="18" height="18" rx="9" />
                </clipPath>
              </defs>
            </svg>
            <span class="porownanieprodukty__text"><?php echo wp_kses_post($f_left); ?></span>
          </div>

          <div class="porownanieprodukty__label">
            <?php echo wp_kses_post($f_center); ?>
          </div>

          <div class="porownanieprodukty__feature porownanieprodukty__feature--right">
            <svg class="porownanieprodukty__dot" style="width: 18px; height: 18px; flex-shrink: 0;" width="18" height="18" viewBox="0 0 18 18" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <foreignObject x="-4" y="-4" width="26" height="26">
                <div xmlns="http://www.w3.org/1999/xhtml"
                  style="backdrop-filter:blur(2px);clip-path:url(#bgblur_0_1774_2264_clip_path);height:100%;width:100%">
                </div>
              </foreignObject>
              <g data-figma-bg-blur-radius="4">
                <rect width="18" height="18" rx="9" fill="#FFC7C7" />
                <rect x="4" y="4" width="10" height="10" rx="5" fill="#AC0000" />
              </g>
              <defs>
                <clipPath id="bgblur_0_1774_2264_clip_path" transform="translate(4 4)">
                  <rect width="18" height="18" rx="9" />
                </clipPath>
              </defs>
            </svg>
            <span class="porownanieprodukty__text"><?php echo wp_kses_post($f_right); ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>