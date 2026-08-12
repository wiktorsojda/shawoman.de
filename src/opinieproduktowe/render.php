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
