<?php
$bannerTitle    = isset($attributes['bannerTitle'])    ? $attributes['bannerTitle']    : 'Welcome to our blog!';
$bannerSubtitle = isset($attributes['bannerSubtitle']) ? $attributes['bannerSubtitle'] : 'Keep up with our latest news.';
$continueLabel  = isset($attributes['continueLabel'])  ? $attributes['continueLabel']  : 'Continue reading »';
$postedByPrefix = isset($attributes['postedByPrefix']) ? $attributes['postedByPrefix'] : 'Posted by';
$inText         = isset($attributes['inText'])         ? $attributes['inText']         : 'in';

if (function_exists('pageBanner')) {
    pageBanner([
        'title' => $bannerTitle,
        'subtitle' => $bannerSubtitle,
    ]);
}
?>
<div class="container container--narrow page-section">
    <?php while (have_posts()) : the_post(); ?>
        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="metabox">
                <p><?php echo wp_kses_post($postedByPrefix); ?> <?php the_author_posts_link(); ?> <?php the_time('n.j.y'); ?> <?php echo wp_kses_post($inText); ?> <?php echo get_the_category_list(', '); ?></p>
            </div>
            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p><a class="btn btn--blue" href="<?php the_permalink(); ?>"><?php echo wp_kses_post($continueLabel); ?></a></p>
            </div>
        </div>
    <?php endwhile;
    echo paginate_links(); ?>
</div>
