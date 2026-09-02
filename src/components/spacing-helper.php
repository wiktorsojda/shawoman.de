<?php
function shav_render_responsive_spacing_css($block_id, $attributes) {
    $marginDesktop = $attributes['marginDesktop'] ?? [];
    $paddingDesktop = $attributes['paddingDesktop'] ?? [];
    $marginMobile = $attributes['marginMobile'] ?? [];
    $paddingMobile = $attributes['paddingMobile'] ?? [];

    $cssDesktop = '';
    $cssMobile = '';

    // Desktop
    if (!empty($marginDesktop['top'])) $cssDesktop .= "margin-top: {$marginDesktop['top']}; ";
    if (!empty($marginDesktop['right'])) $cssDesktop .= "margin-right: {$marginDesktop['right']}; ";
    if (!empty($marginDesktop['bottom'])) $cssDesktop .= "margin-bottom: {$marginDesktop['bottom']}; ";
    if (!empty($marginDesktop['left'])) $cssDesktop .= "margin-left: {$marginDesktop['left']}; ";

    if (!empty($paddingDesktop['top'])) $cssDesktop .= "padding-top: {$paddingDesktop['top']}; ";
    if (!empty($paddingDesktop['right'])) $cssDesktop .= "padding-right: {$paddingDesktop['right']}; ";
    if (!empty($paddingDesktop['bottom'])) $cssDesktop .= "padding-bottom: {$paddingDesktop['bottom']}; ";
    if (!empty($paddingDesktop['left'])) $cssDesktop .= "padding-left: {$paddingDesktop['left']}; ";

    // Mobile
    if (!empty($marginMobile['top'])) $cssMobile .= "margin-top: {$marginMobile['top']} !important; ";
    if (!empty($marginMobile['right'])) $cssMobile .= "margin-right: {$marginMobile['right']} !important; ";
    if (!empty($marginMobile['bottom'])) $cssMobile .= "margin-bottom: {$marginMobile['bottom']} !important; ";
    if (!empty($marginMobile['left'])) $cssMobile .= "margin-left: {$marginMobile['left']} !important; ";

    if (!empty($paddingMobile['top'])) $cssMobile .= "padding-top: {$paddingMobile['top']} !important; ";
    if (!empty($paddingMobile['right'])) $cssMobile .= "padding-right: {$paddingMobile['right']} !important; ";
    if (!empty($paddingMobile['bottom'])) $cssMobile .= "padding-bottom: {$paddingMobile['bottom']} !important; ";
    if (!empty($paddingMobile['left'])) $cssMobile .= "padding-left: {$paddingMobile['left']} !important; ";

    if ($cssDesktop || $cssMobile) {
        echo '<style>';
        if ($cssDesktop) {
            echo ".$block_id { $cssDesktop } ";
        }
        if ($cssMobile) {
            echo "@media (max-width: 767px) { .$block_id { $cssMobile } } ";
        }
        echo '</style>';
    }
}
