<?php
$title           = isset($attributes['title'])           ? $attributes['title']           : '';
$showTitle       = isset($attributes['showTitle'])       ? $attributes['showTitle']       : true;
$headingTag      = isset($attributes['headingTag'])      ? $attributes['headingTag']      : 'h2';
$containerClass  = isset($attributes['containerClass'])  ? $attributes['containerClass']  : 'faq-kontakt-container';

$allowed_tags = ['h2', 'h3', 'h4', 'div'];
if (!in_array($headingTag, $allowed_tags, true)) $headingTag = 'h2';
?>
<section class="<?php echo esc_attr($containerClass); ?>">
    <style>
        .faq-kontakt-container .faq-wrapper {
            max-width: 992px !important;
            margin: 0 auto;
        }
        .faq-kontakt-container .faq-title {
            text-align: center;
            font-family: var(--wp--preset--font-family--base, "Be Vietnam Pro", sans-serif);
            font-weight: 500;
            font-style: normal;
            font-size: var(--wp--preset--font-size--h-1, clamp(44px, 4vw, 64px));
            line-height: 120%;
            letter-spacing: -0.04em; /* H1 Headline/Letter spacing z DS to -4% */
            vertical-align: middle;
            color: #252525;
            margin-bottom: 48px;
        }
    </style>
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
