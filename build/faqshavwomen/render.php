<?php
$showTopTitle = isset($attributes['showTopTitle']) ? $attributes['showTopTitle'] : true;
$topTitle     = isset($attributes['topTitle'])     ? $attributes['topTitle']     : 'Często zadawane pytania';
$headingTag   = isset($attributes['headingTag'])   ? $attributes['headingTag']   : 'h2';
$faqItems     = isset($attributes['faqItems'])     ? $attributes['faqItems']     : [];

if (empty($faqItems)) {
    for ($i = 1; $i <= 10; $i++) {
        $q   = isset($attributes["question{$i}"]) ? $attributes["question{$i}"] : '';
        $ans = isset($attributes["answer{$i}"])   ? $attributes["answer{$i}"]   : '';
        if ($q || $ans) {
            $faqItems[] = ['question' => $q, 'answer' => $ans];
        }
    }
}

$allowed_tags = ['h2', 'h3', 'h4', 'div'];
if (!in_array($headingTag, $allowed_tags, true)) $headingTag = 'h2';
?>
<section class="faq-container">
    <div class="faq-wrapper container--narrow2-important">
        <?php if ($showTopTitle && $topTitle): ?>
            <div class="faq-title"><?php echo wp_kses_post($topTitle); ?></div>
        <?php endif; ?>
        
        <div class="faq-wrapper-questions">
            <?php foreach ($faqItems as $item):
                $q   = isset($item['question']) ? $item['question'] : '';
                $ans = isset($item['answer'])   ? $item['answer']   : '';
                if (!$q && !$ans) continue;
            ?>
            <div class="faq">
                <button class="faq-accordion" type="button" aria-expanded="false">
                    <<?php echo $headingTag; ?> class="faq-header"><?php echo wp_kses_post($q); ?></<?php echo $headingTag; ?>>
                    <svg class="shav-faq-icon shav-faq-plus" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="h-line" d="M4 10h12" stroke="#3F3F3F" stroke-width="2" stroke-linecap="round"/>
                        <path class="v-line" d="M10 4v12" stroke="#3F3F3F" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <div class="faq-pannel">
                    <p><?php echo wp_kses_post($ans); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>