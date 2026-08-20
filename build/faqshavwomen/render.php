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
    <div class="faq-wrapper container--narrow2-important" style="max-width: 1300px !important;">
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
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-pannel">
                    <p><?php echo wp_kses_post($ans); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
(function() {
    document.querySelectorAll('.faq-container .faq').forEach((item) => {
        const trigger = item.querySelector('.faq-accordion');
        if (!trigger) return;
        // Sprawdzamy czy to ma obsługę click uogólnioną w głównym main.js motywu
        // Jeśli nie, dodajemy własną logikę toggle. Zazwyczaj stare faq-accordion mają globalny skrypt.
        // Ale dla bezpieczeństwa lepiej dodać:
        if (!trigger.dataset.initialized) {
            trigger.dataset.initialized = 'true';
            trigger.addEventListener('click', () => {
                item.classList.toggle('active');
                trigger.classList.toggle('active');
                
                const panel = item.querySelector('.faq-pannel');
                if (panel) {
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    }
                }
            });
        }
    });
})();
</script>