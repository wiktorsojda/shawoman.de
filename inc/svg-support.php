<?php
/**
 * Zezwala na upload plikow SVG w Mediach.
 * UWAGA: SVG moze zawierac JS/XSS — wgrywaj tylko zaufane pliki (od grafika / Figmy).
 */

defined('ABSPATH') || exit;

// 1. Dorzuc SVG do dozwolonych mime types
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
});

// 2. WP sprawdza zawartosc pliku w stosunku do rozszerzenia — dla SVG default fail.
// Naprawiamy by SVG przeszlo check.
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    if (!current_user_can('upload_files')) return $data;
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (strtolower($ext) === 'svg') {
        $data['ext']             = 'svg';
        $data['type']            = 'image/svg+xml';
        $data['proper_filename'] = $filename;
    }
    return $data;
}, 10, 4);

// 3. Dodaj wsparcie miniaturek SVG w bibliotece mediow (admin)
add_filter('wp_prepare_attachment_for_js', function ($response, $attachment, $meta) {
    if ($response['mime'] === 'image/svg+xml' && empty($response['sizes'])) {
        $svg_url = wp_get_attachment_url($attachment->ID);
        $response['image'] = ['src' => $svg_url];
        $response['thumb'] = ['src' => $svg_url];
        $response['sizes'] = [
            'thumbnail' => ['url' => $svg_url, 'width' => 150, 'height' => 150, 'orientation' => 'portrait'],
            'medium'    => ['url' => $svg_url, 'width' => 300, 'height' => 300, 'orientation' => 'portrait'],
            'full'      => ['url' => $svg_url, 'width' => 300, 'height' => 300, 'orientation' => 'portrait'],
        ];
    }
    return $response;
}, 10, 3);
