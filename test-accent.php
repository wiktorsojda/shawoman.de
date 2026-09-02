<?php
require_once 'wp-load.php';
$shav_grid_accent_word = 'Woman';
$apply_accent = function($text) use ($shav_grid_accent_word) {
    $escaped = esc_html($text);
    if (!empty($shav_grid_accent_word) && function_exists('shav_highlight_accent_word')) {
        return shav_highlight_accent_word($escaped, $shav_grid_accent_word, 'shop-section__title-accent');
    }
    return $escaped;
};
echo "Output: " . $apply_accent('Shav Woman') . "\n";
