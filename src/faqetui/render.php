<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : '';
$showTitle       = isset($attributes['showTitle'])       ? $attributes['showTitle']       : true;
$headingTag      = isset($attributes['headingTag'])      ? $attributes['headingTag']      : 'h2';
$containerClass  = isset($attributes['containerClass'])  ? $attributes['containerClass']  : 'faq-container';

$allowed_tags = ['h2', 'h3', 'h4', 'div'];
if (!in_array($headingTag, $allowed_tags, true)) $headingTag = 'h2';
?>
<section class="<?php echo esc_attr($containerClass); ?>">
    <div class="faq-wrapper container--narrow2-important">
        <?php if ($showTitle && $title): ?>
            <div class="faq-title"><?php echo wp_kses_post($title); ?></div>
        <?php endif; ?>
        <div class="faq-wrapper-questions">
            <?php for ($i = 1; $i <= 10; $i++):
                $q   = isset($attributes["question{$i}"]) ? $attributes["question{$i}"] : '';
                $ans = isset($attributes["answer{$i}"])   ? $attributes["answer{$i}"]   : '';
                if (!$q && !$ans) continue;
            ?>
                <div class="faq">
                    <button class="faq-accordion" type="button">
                        <<?php echo $headingTag; ?> class="faq-header"><?php echo wp_kses_post($q); ?></<?php echo $headingTag; ?>>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-pannel">
                        <p><?php echo wp_kses_post($ans); ?></p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
