<?php
$blogHomeLabel  = isset($attributes['blogHomeLabel'])  ? $attributes['blogHomeLabel']  : 'Blog Home';
$blogHomeURL    = isset($attributes['blogHomeURL'])    ? $attributes['blogHomeURL']    : '/blog';
$postedByPrefix = isset($attributes['postedByPrefix']) ? $attributes['postedByPrefix'] : 'Posted by';
$inText         = isset($attributes['inText'])         ? $attributes['inText']         : 'in';

if (function_exists('pageBanner')) {
    pageBanner();
}
?>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo esc_url(site_url($blogHomeURL)); ?>">
                <i class="fa fa-home" aria-hidden="true"></i> <?php echo wp_kses_post($blogHomeLabel); ?>
            </a>
            <span class="metabox__main"><?php echo wp_kses_post($postedByPrefix); ?> <?php the_author_posts_link(); ?> <?php the_time('n.j.y'); ?> <?php echo wp_kses_post($inText); ?> <?php echo get_the_category_list(', '); ?></span>
        </p>
    </div>
    <div class="generic-content"><?php the_content(); ?></div>
</div>
