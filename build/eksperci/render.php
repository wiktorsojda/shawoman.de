<?php
$a = $attributes;

// Fallbacki i wartości
$title = !empty($a['title']) ? $a['title'] : 'Kilka słów od naszego zespołu';
$experts = !empty($a['experts']) && is_array($a['experts']) ? $a['experts'] : [];

require_once get_theme_file_path('/src/components/spacing-helper.php');
$block_id = uniqid('shav-block-');
shav_render_responsive_spacing_css($block_id, $a);

$wrapper_attributes = get_block_wrapper_attributes(['class' => "eksperci $block_id"]);
?>
<section <?php echo $wrapper_attributes; ?>>
  <div class="eksperci__inner">
    <h2 class="eksperci__title"><?php echo wp_kses_post($title); ?></h2>
    
    <div class="eksperci__grid">
      <?php foreach ($experts as $expert) : ?>
        <div class="eksperci__card">
          <div class="eksperci__header">
            <?php if (!empty($expert['image'])) : ?>
              <img src="<?php echo esc_url($expert['image']); ?>" class="eksperci__avatar" alt="<?php echo esc_attr($expert['name']); ?>" />
            <?php else : ?>
              <div class="eksperci__avatar eksperci__avatar--placeholder"></div>
            <?php endif; ?>
            
            <div class="eksperci__info">
              <div class="eksperci__name"><?php echo wp_kses_post($expert['name']); ?></div>
              <div class="eksperci__role"><?php echo wp_kses_post($expert['role']); ?></div>
            </div>
          </div>
          
          <div class="eksperci__desc1"><?php echo wp_kses_post($expert['desc1']); ?></div>
          <div class="eksperci__desc2"><?php echo wp_kses_post($expert['desc2']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
