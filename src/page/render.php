<?php
$backLabel = isset($attributes['backLabel']) ? $attributes['backLabel'] : 'Back to';
?>
<div class="container container--narrow">
    <?php
        $theParent = wp_get_post_parent_id(get_the_ID());
        if ($theParent) :
    ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <?php echo wp_kses_post($backLabel); ?> <?php echo get_the_title($theParent); ?>
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
    <?php endif; ?>

    <?php
    $testArray = get_pages(['child_of' => get_the_ID()]);
    if ($theParent || $testArray) :
        $findChildrenOf = $theParent ? $theParent : get_the_ID();
    ?>
        <div class="page-links">
            <h2 class="page-links__title">
                <a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a>
            </h2>
            <ul class="min-list">
                <?php wp_list_pages([
                    'title_li' => NULL,
                    'child_of' => $findChildrenOf,
                    'sort_column' => 'menu_order',
                ]); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="generic-content"><?php the_content(); ?></div>
</div>
