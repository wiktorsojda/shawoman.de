<?php
/**
 * Classic header.php — most do block-template-parts.
 * Wykorzystywane przez `get_header()` w klasycznych templatach (WC: archive-product.php,
 * single-product.php). Renderuje ten sam template-part "header" co block templates,
 * dzieki czemu user edytuje atrybuty w jednym miejscu (Site Editor → Czesci szablonu).
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php block_template_part('header'); ?>
