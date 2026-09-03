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
                    <h2 class="opinieproduktowe__title"><?php echo esc_html($title); ?></h2>
                <?php endif;

                // Standardowy mechanizm WC do renderowania opinii
                if (comments_open() || get_comments_number()) {
                    
                    // Zliczamy całkowitą ilość zaaprobowanych opinii dla guzika
                    // Używamy zcache'owanego licznika WooCommerce zamiast robić pełne zapytanie do bazy, by nie spowalniać strony
                    $total_comments = get_comments_number();
                    
                    $has_more = ($total_comments > 9);
                    
                    // Używamy filtra, ponieważ $wp_query->comments może być jeszcze puste (wtedy comments_template() je pobiera)
                    $limit_filter = function($comments) {
                        return array_slice($comments, 0, 9);
                    };
                    add_filter('comments_array', $limit_filter, 999);
                    
                    comments_template();
                    
                    remove_filter('comments_array', $limit_filter, 999);
                    
                    if ($has_more) {
                        echo '<div id="shav-ajax-reviews-wrapper" style="display: flex; justify-content: center; margin-top: 32px; width: 100%;">';
                        echo '  <button class="shav-fake-tab-opinie" id="shav-load-more-reviews" data-product-id="' . esc_attr(get_the_ID()) . '" data-offset="9">Mehr anzeigen</button>';
                        echo '</div>';
                    }
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
                document.body.style.overflow = "";
            });
        }

        addReviewBtns.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                reviewFormWrapper.classList.add("active");
                document.body.style.overflow = "hidden";
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
        const maxHeight = 110; 
        if (p.offsetHeight > maxHeight + 10) {
            p.style.display = "-webkit-box";
            p.style.webkitLineClamp = "5";
            p.style.webkitBoxOrient = "vertical";
            p.style.overflow = "hidden";
            p.style.textOverflow = "ellipsis";
            
            const btn = document.createElement("button");
            btn.innerText = "Mehr anzeigen";
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
    const loadMoreBtn = document.getElementById('shav-load-more-reviews');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            const offset = parseInt(this.getAttribute('data-offset'));
            const limit = 6;
            const btnText = this.innerText;
            
            this.innerText = 'Wird geladen...';
            this.disabled = true;
            
            const formData = new FormData();
            formData.append('action', 'shav_load_more_reviews');
            formData.append('product_id', productId);
            formData.append('offset', offset);
            formData.append('limit', limit);
            
            fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data.html) {
                    const commentList = document.querySelector('.commentlist');
                    if (commentList) {
                        commentList.insertAdjacentHTML('beforeend', res.data.html);
                        
                        let newOffset = offset + res.data.count;
                        loadMoreBtn.setAttribute('data-offset', newOffset);
                        
                        if (res.data.count < limit || res.data.is_last) {
                            document.getElementById('shav-ajax-reviews-wrapper').style.display = 'none';
                        }
                    }
                } else {
                    document.getElementById('shav-ajax-reviews-wrapper').style.display = 'none';
                }
                
                loadMoreBtn.innerText = btnText;
                loadMoreBtn.disabled = false;
            })
            .catch(err => {
                console.error(err);
                loadMoreBtn.innerText = btnText;
                loadMoreBtn.disabled = false;
            });
        });
    }
});
</script>
