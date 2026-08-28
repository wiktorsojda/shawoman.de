<?php
/**
 * Render dla bloku: Opinie Produktowe
 */

if (!isset($attributes)) {
    $attributes = [];
}

// Wymuszamy język niemiecki w przypadku domyślnej polskiej wartości
$default_title = 'Opinie naszych klientek';
$title = !empty($attributes['title']) && $attributes['title'] !== $default_title 
    ? $attributes['title'] 
    : 'Kundenbewertungen';
?>

<div <?php echo get_block_wrapper_attributes(['class' => 'opinieproduktowe']); ?>>
    <div class="opinieproduktowe__inner">
        <div class="opinieproduktowe__reviews">
            <?php
            // Zabezpieczenie przed fatal errorem w edytorze wp_block / REST API
            if (is_product() && !is_admin() && !wp_is_json_request()) {
                
                // Sprawdzamy czy funkcja z functions.php istnieje i wywołujemy podsumowanie
                if (function_exists('custom_review_summary')) {
                    custom_review_summary();
                }
                
                // Nagłówek ląduje bezposrednio nad siatką opinii
                if (!empty($title)) : ?>
                    <h2 class="opinieproduktowe__title" style="max-width: 1300px; margin: 0 auto 32px auto;"><?php echo esc_html($title); ?></h2>
                <?php endif;

                // Standardowy mechanizm WC do renderowania opinii
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                
            } else {
                echo '<div style="padding:20px; border:1px dashed #ccc; text-align:center;">[Sekcja opinii WooCommerce - widoczna na żywej stronie produktu]</div>';
            }
            ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const reviewFormWrapper = document.getElementById("review_form_wrapper");
    const addReviewBtns = document.querySelectorAll(".btn-add-review");

    if (reviewFormWrapper) {
        // Zabezpieczenie przed podwójnym dodaniem przycisku
        if (!document.getElementById("close-review-modal")) {
            const closeBtn = document.createElement("button");
            closeBtn.id = "close-review-modal";
            closeBtn.innerHTML = '<svg style="width: 24px; height: 24px; flex-shrink: 0;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
            closeBtn.style.cssText = "position:absolute; top:24px; right:24px; background:none; border:none; cursor:pointer; color:#111; display:flex; align-items:center; justify-content:center; padding:0; z-index: 10;";
            
            const formContainer = document.getElementById("review_form");
            if (formContainer) {
                formContainer.appendChild(closeBtn);
            }

            closeBtn.addEventListener("click", function(e) {
                e.preventDefault();
                reviewFormWrapper.classList.remove("active");
                document.body.style.overflow = ""; // Przywracamy scrollowanie
            });
        }

        addReviewBtns.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                reviewFormWrapper.classList.add("active");
                document.body.style.overflow = "hidden"; // Blokujemy scrollowanie tła
            });
        });

        reviewFormWrapper.addEventListener("click", function(e) {
            if (e.target === reviewFormWrapper) {
                reviewFormWrapper.classList.remove("active");
                document.body.style.overflow = "";
            }
        });
    }
});
</script>

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

<script>
document.addEventListener("DOMContentLoaded", function() {
    let reviewsList = document.querySelector('.wcpr-grid');
    if (!reviewsList) reviewsList = document.querySelector('.commentlist');
    
    if (reviewsList) {
        let items = reviewsList.querySelectorAll('.wcpr-grid-item, li.comment');
        if (items.length > 9) {
            let visibleCount = 9;
            let step = 6;
            
            // Hide items beyond 9
            for (let i = visibleCount; i < items.length; i++) {
                items[i].style.display = 'none';
            }
            
            // Create "Show more" button
            let btnWrapper = document.createElement('div');
            btnWrapper.style.cssText = 'display: flex; justify-content: center; margin-top: 32px; width: 100%;';
            
            let btn = document.createElement('button');
            btn.className = 'shav-fake-tab-opinie'; 
            btn.innerText = 'Mehr anzeigen';
            
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let nextCount = visibleCount + step;
                for (let i = visibleCount; i < nextCount && i < items.length; i++) {
                    items[i].style.display = ''; // restore original display
                }
                visibleCount = nextCount;
                
                // Trigger window resize for masonry layout
                window.dispatchEvent(new Event('resize'));
                
                if (visibleCount >= items.length) {
                    btnWrapper.style.display = 'none';
                }
            });
            
            btnWrapper.appendChild(btn);
            
            // Append after the grid
            reviewsList.parentNode.insertBefore(btnWrapper, reviewsList.nextSibling);
        }
    }
});
</script>
