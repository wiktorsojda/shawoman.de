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
        .faq-kontakt-container {
            max-width: 992px !important;
            margin: 0 auto;
            width: 100%;
        }
        .faq-kontakt-container .faq-title {
            text-align: center;
            font-family: var(--wp--preset--font-family--base, "Be Vietnam Pro", sans-serif);
            font-weight: 500;
            font-style: normal;
            font-size: var(--wp--preset--font-size--h-1, clamp(44px, 4vw, 64px));
            line-height: 120%;
            letter-spacing: -0.04em;
            vertical-align: middle;
            color: #252525;
            margin-bottom: 48px;
        }
        .faq-kontakt-container .shav-faq-item {
            background: #F2F2F2;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .faq-kontakt-container .shav-faq-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 32px 24px;
        }
        .faq-kontakt-container .shav-faq-question {
            font-size: 15px;
            font-weight: 500;
            color: #3F3F3F;
            margin: 0;
        }
        .faq-kontakt-container .shav-faq-icon {
            transition: transform 0.3s ease;
            flex-shrink: 0;
            margin-left: 10px;
        }
        .faq-kontakt-container .shav-faq-content {
            display: none;
            padding: 0 24px 32px 24px;
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }
    </style>
    <div class="faq-wrapper">
        <?php if ($showTitle && $title): ?>
            <<?php echo $headingTag; ?> class="faq-title"><?php echo wp_kses_post($title); ?></<?php echo $headingTag; ?>>
        <?php endif; ?>
        
        <div class="shav-product-faq">
            <?php for ($i = 1; $i <= 10; $i++):
                $q   = isset($attributes["question{$i}"]) ? $attributes["question{$i}"] : '';
                $ans = isset($attributes["answer{$i}"])   ? $attributes["answer{$i}"]   : '';
                if (!$q && !$ans) continue;
            ?>
                <div class="shav-faq-item">
                    <div class="shav-faq-header">
                        <span class="shav-faq-question"><?php echo wp_kses_post($q); ?></span>
                        <svg class="shav-faq-icon shav-faq-plus" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path class="h-line" d="M4 10h12" stroke="#3F3F3F" stroke-width="2" stroke-linecap="round"/>
                            <path class="v-line" d="M10 4v12" stroke="#3F3F3F" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="shav-faq-content">
                        <?php echo wpautop(wp_kses_post($ans)); ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <script>
    (function() {
        function initFAQ() {
            var headers = document.querySelectorAll(".faq-kontakt-container .shav-faq-header");
            headers.forEach(function(header) {
                if (header.dataset.faqListener) return;
                header.dataset.faqListener = 'true';
                
                header.addEventListener("click", function() {
                    var content = this.nextElementSibling;
                    var icon = this.querySelector(".shav-faq-icon");
                    var vLine = icon ? icon.querySelector(".v-line") : null;
                    
                    var isOpen = (content.style.display !== "none" && content.style.display !== "");
                    
                    if (isOpen) {
                        content.style.display = "none";
                        if (vLine) vLine.style.opacity = "1";
                        if (icon) icon.style.transform = "rotate(0deg)";
                    } else {
                        var container = this.closest(".shav-product-faq");
                        if (container) {
                            var allHeaders = container.querySelectorAll(".shav-faq-header");
                            allHeaders.forEach(function(otherHeader) {
                                if (otherHeader !== header) {
                                    var otherContent = otherHeader.nextElementSibling;
                                    var otherIcon = otherHeader.querySelector(".shav-faq-icon");
                                    var otherVLine = otherIcon ? otherIcon.querySelector(".v-line") : null;
                                    
                                    if (otherContent) otherContent.style.display = "none";
                                    if (otherVLine) otherVLine.style.opacity = "1";
                                    if (otherIcon) otherIcon.style.transform = "rotate(0deg)";
                                }
                            });
                        }
                        
                        content.style.display = "block";
                        if (vLine) vLine.style.opacity = "0";
                        if (icon) icon.style.transform = "rotate(180deg)";
                    }
                });
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener("DOMContentLoaded", initFAQ);
        } else {
            initFAQ();
        }
    })();
    </script>
</section>
