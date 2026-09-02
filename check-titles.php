<?php
require_once 'wp-load.php';
$products = get_posts(array('post_type' => 'product', 'posts_per_page' => 5));
foreach ($products as $p) {
    echo "Title of " . $p->ID . ": " . $p->post_title . "\n";
}
