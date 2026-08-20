<?php
/**
 * Render dla bloku: Opinie Produktowe
 */

if (!isset($attributes)) {
    $attributes = [];
}

$title = $attributes['title'] ?? 'Opinie naszych klientek';
?>

<div <?php echo get_block_wrapper_attributes(['class' => 'opinieproduktowe']); ?>>
    <div class="opinieproduktowe__inner">
        <?php if (!empty($title)) : ?>
            <h2 class="opinieproduktowe__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <div class="opinieproduktowe__reviews">
            <?php
            // Sprawdzamy czy funkcja z functions.php istnieje i wywołujemy podsumowanie
            if (function_exists('custom_review_summary')) {
                custom_review_summary();
            }
            
            // Standardowy mechanizm WC do renderowania opinii (jeśli włączone)
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const descriptions = document.querySelectorAll(".commentlist .comment-text div.description p");
    descriptions.forEach(p => {
        // Obliczamy przybliżoną wysokość 5 linii (line-height w CSS to ok 22px, więc 5 * 22 = 110px)
        const maxHeight = 110; 
        
        if (p.offsetHeight > maxHeight + 10) {
            // Zapisujemy oryginalny stan
            p.style.display = "-webkit-box";
            p.style.webkitLineClamp = "5";
            p.style.webkitBoxOrient = "vertical";
            p.style.overflow = "hidden";
            p.style.textOverflow = "ellipsis";
            
            const btn = document.createElement("button");
            btn.innerText = "Mehr anzeigen"; // "Więcej" po niemiecku
            btn.className = "unfold-review-btn";
            btn.style.cssText = "background: none; border: none; color: #111; font-weight: 500; text-decoration: underline; padding: 0; margin-top: 8px; cursor: pointer; font-size: 14px; font-family: inherit;";
            
            p.parentNode.appendChild(btn);
            
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                p.style.display = "block";
                p.style.webkitLineClamp = "unset";
                btn.style.display = "none";
            });
        }
    });
});
</script>
