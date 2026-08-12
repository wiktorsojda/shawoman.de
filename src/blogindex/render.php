<?php
// Central Blog Configuration logic:
// Fetch attributes from the page containing the blog block and merge them into current attributes.
// This allows the user to edit the Blog page in the standard editor (regardless of slug) and have the settings propagate to category archives.
$blog_pages = get_pages();
$blog_page = null;
foreach ($blog_pages as $p) {
    if (has_block('ourblocktheme/blogindex', $p)) {
        $blog_page = $p;
        break;
    }
}
if ($blog_page) {
    $blocks = parse_blocks($blog_page->post_content);
    foreach ($blocks as $block) {
        if (isset($block['blockName']) && $block['blockName'] === 'ourblocktheme/blogindex') {
            if (isset($block['attrs']) && is_array($block['attrs'])) {
                foreach ($block['attrs'] as $key => $value) {
                    if (empty($attributes[$key]) && !empty($value)) {
                        $attributes[$key] = $value;
                    }
                }
            }
            break;
        }
    }
}
 
$title = $attributes['title'] ?? 'BlendyBlog';
$subtitle = $attributes['subtitle'] ?? 'Twój codzienny mix przepisów i inspiracji lifestylowych.';
$button_text = $attributes['buttonText'] ?? 'Czytaj dalej';
$hero_button_text = $attributes['heroButtonText'] ?? 'Czytaj wpisy';
$grid_title = $attributes['gridTitle'] ?? 'Wszystkie wpisy';
$no_posts_text = $attributes['noPostsText'] ?? 'Brak wpisów do wyświetlenia.';
$sidebar_categories_title = $attributes['sidebarCategoriesTitle'] ?? 'KATEGORIE';
$featured_label = $attributes['featuredLabel'] ?? 'Najnowszy wpis';
$video_url = $attributes['videoUrl'] ?? 'https://blendygo.pl/wp-content/uploads/2023/08/lifestylowy-2.mp4';
$category_prefix_text = $attributes['categoryPrefixText'] ?? 'Przeglądasz wpisy z kategorii: ';

$about_image = $attributes['aboutImage'] ?? '';
$about_title = $attributes['aboutTitle'] ?? 'O NAS';
$about_text = $attributes['aboutText'] ?? "Cześć, tu ekipa BlendyGo! 🥤\n\nWierzymy, że zdrowe nawyki mogą być proste i przyjemne. Na tym blogu codziennie miksujemy dla Ciebie pyszne przepisy, porady i czystą motywację. Złap z nami swój rytm!\n\nWpadaj też na nasze social media po codzienną dawkę inspiracji! 👇";
$find_us_title = $attributes['findUsTitle'] ?? 'Znajdź nas na:';
$ig_link = $attributes['igLink'] ?? 'https://instagram.com/blendygo';
$tiktok_link = $attributes['tiktokLink'] ?? 'https://tiktok.com/@blendygo';
$fb_link = $attributes['fbLink'] ?? 'https://facebook.com/blendygo';
$show_ig = $attributes['showIg'] ?? true;
$show_tiktok = $attributes['showTiktok'] ?? true;
$show_fb = $attributes['showFb'] ?? true;

$is_cat_archive = is_category();
$current_cat_id = 0;
$current_cat_name = '';

if ($is_cat_archive) {
    $current_cat = get_queried_object();
    if ($current_cat) {
        $current_cat_id = $current_cat->term_id;
        $current_cat_name = $current_cat->name;
    }
}

// Pobieramy wpisy dla Hero Slidera (lub Bannera)
$slider_args = array(
    'post_type' => 'post',
    'posts_per_page' => $is_cat_archive ? 5 : 3,
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1
);
if ($is_cat_archive && $current_cat_id > 0) {
    $slider_args['cat'] = $current_cat_id;
}
$slider_posts = new WP_Query($slider_args);

$cat_banner_id = '';
if ($is_cat_archive && $current_cat_id > 0) {
    $cat_banner_id = get_term_meta($current_cat_id, 'category_banner_id', true);
}
?>
<div class="blog-index-wrapper">
    <!-- Ukryty H1 dla SEO -->
    <?php if ($is_cat_archive): ?>
    <!-- Category Archive Banner -->
    <header class="blog-category-banner">
        <?php if ($cat_banner_id) : ?>
            <div class="blog-category-banner__bg">
                <?php echo wp_get_attachment_image($cat_banner_id, 'full', false, array('class' => 'blog-category-banner__img', 'style' => 'width:100%;height:100%;object-fit:cover;')); ?>
            </div>
        <?php else : ?>
            <div class="glide blog-glide">
                <div class="glide__track" data-glide-el="track">
                    <ul class="glide__slides">
                        <?php 
                        $is_first = true;
                        if ($slider_posts->have_posts()): while ($slider_posts->have_posts()): $slider_posts->the_post(); 
                        ?>
                            <li class="glide__slide">
                                <div class="blog-category-banner__bg">
                                    <?php 
                                    if (has_post_thumbnail()) {
                                        $loading = $is_first ? 'eager' : 'lazy';
                                        $fetchpriority = $is_first ? 'high' : 'auto';
                                        the_post_thumbnail('full', array('class' => 'blog-category-banner__img', 'loading' => $loading, 'fetchpriority' => $fetchpriority, 'alt' => esc_attr(get_the_title()))); 
                                    } else {
                                        echo '<div class="blog-category-banner__img blog-category-banner__img--placeholder"></div>';
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php 
                        $is_first = false;
                        endwhile; wp_reset_postdata(); endif; 
                        ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="blog-category-banner__overlay">
            <div class="blog-category-banner__text-wrap">
                <span class="blog-category-banner__prefix"><?php echo esc_html($category_prefix_text); ?></span>
                <h1 class="blog-category-banner__title"><?php echo esc_html($current_cat_name); ?></h1>
            </div>
        </div>
    </header>
    <?php else: 
    $hero_bg_image = $attributes['heroBgImage'] ?? '';
    if (empty($hero_bg_image)) {
        $fallback_url = $attributes['videoUrl'] ?? '';
        if (!empty($fallback_url) && strpos($fallback_url, '.mp4') === false) {
            $hero_bg_image = $fallback_url;
        }
    }
    ?>
    <!-- Hero Section Simple Banner -->
    <style>
        /* Usuwamy domyślny górny margines dodawany przez .wp-site-blocks na stronie Bloga */
        .wp-site-blocks { margin-top: 0 !important; }
    </style>
    <header class="blog-hero-simple" style="position: relative; width: 100%; height: 450px; display: flex; align-items: center; justify-content: center; overflow: hidden; background-color: #333;">
        <!-- DEBUG HERO_BG_IMAGE: <?php echo htmlspecialchars($hero_bg_image); ?> -->
        <!-- DEBUG ALL ATTRS: <?php echo htmlspecialchars(json_encode($attributes)); ?> -->
        <?php if ($hero_bg_image): ?>
            <img src="<?php echo esc_url($hero_bg_image); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" loading="eager" fetchpriority="high" data-no-lazy="1" />
        <?php endif; ?>
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 2;"></div>
        <h1 class="section-main-title" style="color: #FFF; position: relative; z-index: 3; text-align: center; margin: 0; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: bold; text-transform: uppercase;">
            <?php echo wp_kses_post($title); ?>
        </h1>
    </header>
    <?php endif; ?>
    <div class="blog-container" id="blog-grid">
        
        <?php if ($is_cat_archive): 
            // Fallback to the discovered blog page URL, otherwise fallback to /blog/
            $default_blog_url = $blog_page ? get_permalink($blog_page->ID) : '/blog/';
            $blog_url = !empty($attributes['backToBlogUrl']) && $attributes['backToBlogUrl'] !== '/blog/' ? $attributes['backToBlogUrl'] : $default_blog_url;
        ?>
            <div class="blog-breadcrumbs">
                <a href="<?php echo esc_url($blog_url); ?>" class="blog-back-btn">
                    &larr; <?php echo esc_html__('Zurück zum Blog', 'mojmotyw'); ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Tytuł sekcji przeniesiony nad główny układ, by siatka i sidebar równo startowały -->
        <div class="blog-section-header">
            <h2 class="blog-section-header__title"><?php echo esc_html($grid_title); ?></h2>
        </div>
        
        <!-- Główna zawartość z siatką i sidebarem -->
        <div class="blog-layout">
            
            <main class="blog-main-content">
                <div class="blog-archive-grid-container">
                    <?php 
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $is_first_page = ($paged == 1);
                    
                    $grid_args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => 12,
                        'paged' => $paged
                    );
                    
                    if ($is_cat_archive && $current_cat_id > 0) {
                        $grid_args['cat'] = $current_cat_id;
                    }

                    $grid_posts = new WP_Query($grid_args);

                    if ($grid_posts->have_posts()) {

                        $post_index = 0;
                        while($grid_posts->have_posts()) : 
                            $grid_posts->the_post(); 
                            
                            // Na pierwszej stronie pierwszy wpis jest wyrozniony
                            if ($is_first_page && $post_index === 0) {
                                ?>
                                <article class="blog-featured-card">
                                    <div class="blog-featured-card__label"><?php echo esc_html($featured_label); ?></div>
                                    <div class="blog-featured-card__inner">
                                        <div class="blog-featured-card__image-col">
                                            <div class="blog-card__image-wrap">
                                                <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                                    <?php if (has_post_thumbnail()) {
                                                        the_post_thumbnail('large', array('class' => 'blog-featured-card__image', 'loading' => 'lazy', 'alt' => esc_attr(get_the_title())));
                                                    } ?>
                                                </a>
                                                <div class="blog-card__badges-wrapper">
                                                    <?php
                                                    $views = get_post_meta(get_the_ID(), '_post_views_count', true);
                                                    $views = $views ? intval($views) : 0;
                                                    if (current_user_can('edit_posts') || !empty($attributes['showViewsCounter'])) {
                                                        echo '<div class="blog-card__views-badge"><span class="dashicons dashicons-visibility"></span> ' . esc_html($views) . '</div>';
                                                    }
                                                    ?>
                                                    <time class="blog-card__date-badge" datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('F j, Y'); ?></time>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blog-featured-card__content-col">
                                            <div class="blog-card__meta">
                                                <div class="blog-card__categories">
                                                    <?php 
                                                    $categories = get_the_category();
                                                    if(!empty($categories)){
                                                        echo '<a href="'.esc_url(get_category_link($categories[0]->term_id)).'" class="blog-card__tag">'.esc_html($categories[0]->name).'</a>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <h2 class="blog-featured-card__title">
                                                <a href="<?php the_permalink(); ?>" class="blog-featured-card__link"><?php the_title(); ?></a>
                                            </h2>
                                            <div class="blog-featured-card__excerpt">
                                                <?php the_excerpt(); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="blog-card__overlay-btn" aria-label="<?php echo esc_attr($button_text . ': ' . get_the_title()); ?>">
                                        <span><?php echo esc_html($button_text); ?></span>
                                    </a>
                                </article>
                                <div class="blog-archive-grid">
                                <?php
                            } else {
                                // Rozpocznij siatke jesli nie jestesmy na 1 stronie a to 1 wpis
                                if (!$is_first_page && $post_index === 0) {
                                    echo '<div class="blog-archive-grid">';
                                }
                                ?>
                                <article class="blog-card blog-card--standard">
                                    <div class="blog-card__image-wrapper">
                                        <div class="blog-card__image-wrap">
                                            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                                <?php if (has_post_thumbnail()) {
                                                    the_post_thumbnail('medium_large', array('class' => 'blog-card__image', 'loading' => 'lazy', 'alt' => esc_attr(get_the_title())));
                                                } ?>
                                            </a>
                                            <div class="blog-card__badges-wrapper">
                                                <?php
                                                $views = get_post_meta(get_the_ID(), '_post_views_count', true);
                                                $views = $views ? intval($views) : 0;
                                                if (current_user_can('edit_posts') || !empty($attributes['showViewsCounter'])) {
                                                    echo '<div class="blog-card__views-badge"><span class="dashicons dashicons-visibility"></span> ' . esc_html($views) . '</div>';
                                                }
                                                ?>
                                                <time class="blog-card__date-badge" datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('F j, Y'); ?></time>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-card__content">
                                        <div class="blog-card__meta">
                                            <div class="blog-card__categories">
                                                <?php 
                                                $categories = get_the_category();
                                                if(!empty($categories)){
                                                    echo '<a href="'.esc_url(get_category_link($categories[0]->term_id)).'" class="blog-card__tag">'.esc_html($categories[0]->name).'</a>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <h3 class="blog-card__title">
                                            <a href="<?php the_permalink(); ?>" class="blog-card__link"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="blog-card__excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="blog-card__overlay-btn" aria-label="<?php echo esc_attr($button_text . ': ' . get_the_title()); ?>">
                                        <span><?php echo esc_html($button_text); ?></span>
                                    </a>
                                </article>
                                <?php 
                            }
                            $post_index++;
                        endwhile; 
                        
                        if ($post_index > 0) {
                            echo '</div>'; // Zamknij .blog-archive-grid jesli byly wpisy
                        }
                    } else {
                        echo '<p>' . esc_html($no_posts_text) . '</p>';
                    }

                    ?>
                </div>
                
                <div class="blog-pagination">
                    <?php 
                    echo paginate_links(array(
                        'total' => $grid_posts->max_num_pages,
                        'current' => $paged
                    )); 
                    wp_reset_postdata();
                    ?>
                </div>

                <div class="blog-widget blog-widget--about blog-widget--about-wide">
                    <?php if (!empty($about_image)): ?>
                        <div class="blog-widget-about-image-col">
                            <img src="<?php echo esc_url($about_image); ?>" alt="O blogu" class="blog-widget-about-image" width="400" height="400" loading="lazy" />
                        </div>
                    <?php endif; ?>
                    <div class="blog-widget-about-content-col">
                        <div class="blog-widget__header">
                            <h3 class="blog-widget__title"><?php echo esc_html($about_title); ?></h3>
                        </div>
                        <div class="blog-widget-about-text">
                            <?php echo wp_kses_post(nl2br($about_text)); ?>
                        </div>
                        
                        <div class="blog-widget-socials">
                            <?php if ($show_ig): ?>
                                <a href="<?php echo esc_url($ig_link); ?>" target="_blank" rel="noopener noreferrer" class="social-icon social-icon--ig" aria-label="Instagram">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm3.98-10.395a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($show_tiktok): ?>
                                <a href="<?php echo esc_url($tiktok_link); ?>" target="_blank" rel="noopener noreferrer" class="social-icon social-icon--tiktok" aria-label="TikTok">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.78-1.5 5.46-3.92 7-2.3 1.42-5.26 1.63-7.79.52-2.3-1-4.1-3.23-4.32-5.74-.24-2.5.83-5.06 2.82-6.62 1.73-1.35 3.99-1.8 6.09-1.38v4.19c-1.3-.2-2.62.13-3.6.93-1.02.8-1.57 2.1-1.39 3.39.18 1.25 1 2.37 2.13 2.93 1.15.55 2.55.51 3.65-.13 1.11-.64 1.83-1.85 1.86-3.12.04-5.2.01-10.4.02-15.6-.01-.01-.02-.02-.02-.03z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($show_fb): ?>
                                <a href="<?php echo esc_url($fb_link); ?>" target="_blank" rel="noopener noreferrer" class="social-icon social-icon--fb" aria-label="Facebook">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <div class="blog-widget blog-widget--categories">
                    <input type="checkbox" id="mobile-cat-toggle" class="mobile-cat-toggle-checkbox" hidden aria-label="<?php esc_attr_e('Kategorie otworz/zamknij', 'mojmotyw'); ?>">
                    <div class="mobile-cat-header-row">
                        <?php if ($show_ig || $show_tiktok || $show_fb): ?>
                            <div class="mobile-socials-inline">
                                <?php if ($show_ig && !empty($ig_link)): ?>
                                    <a href="<?php echo esc_url($ig_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if ($show_tiktok && !empty($tiktok_link)): ?>
                                    <a href="<?php echo esc_url($tiktok_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if ($show_fb && !empty($fb_link)): ?>
                                    <a href="<?php echo esc_url($fb_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="blog-widget__header">
                            <h3 class="blog-widget__title cat-toggle-pill">
                                <label for="mobile-cat-toggle" style="cursor: pointer; width: 100%; display: block;"><?php echo esc_html($sidebar_categories_title); ?></label>
                            </h3>
                        </div>
                    </div>
                    <div class="blog-widget__content">
                        <ul class="blog-widget__list">
                                        <?php
                                        $categories = get_categories(array('hide_empty' => true));
                                        if (!empty($categories)) {
                                            foreach($categories as $cat) {
                                                $is_active = ($is_cat_archive && $cat->term_id == $current_cat_id);
                                                $active_class = $is_active ? 'active' : '';
                                                $fill_color = $is_active ? '#FFCC91' : 'black';
                                                
                                                echo '<li class="'.esc_attr($active_class).'"><a href="'.esc_url(get_category_link($cat->term_id)).'"><span class="cat-bullet">
                                                        <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M3.09435 0.0179117C2.75295 0.0470203 2.22004 0.171771 1.89114 0.304839C1.37488 0.504441 0.70874 0.911962 0.458938 1.16978L0.346527 1.28622L0.637962 1.57314L0.925235 1.85591L1.15006 1.68126C1.91612 1.09077 2.97361 0.753944 3.83543 0.820478C4.78884 0.899487 5.73393 1.36107 6.24186 2.00146C6.46668 2.28423 6.72897 2.8165 6.79975 3.12838C6.89134 3.52342 6.86636 4.10559 6.74563 4.4549C6.5666 4.97054 6.15859 5.41132 5.72144 5.56518C5.30094 5.70657 4.73888 5.59845 4.56402 5.34063C4.48075 5.21588 4.43079 4.92063 4.48908 4.92063C4.50157 4.92063 4.58484 4.95806 4.67643 5.0038C4.92207 5.12855 5.23016 5.11608 5.49245 4.96638C6.17941 4.57133 6.37925 3.72718 5.97956 2.88303C5.76723 2.43809 5.28428 1.95987 4.79717 1.72285C3.63142 1.15315 2.15759 1.46503 1.17504 2.4963C0.79617 2.88719 0.454774 3.46936 0.229952 4.09312C-0.0281767 4.79588 -0.0698104 5.71073 0.109214 6.46339C0.288239 7.19942 0.683759 8.00615 1.09177 8.45941L1.2583 8.64238L1.53309 8.36793L1.81203 8.09347L1.61219 7.83981C1.24581 7.39071 0.954378 6.76279 0.825314 6.15567C0.750373 5.823 0.742047 5.01628 0.808661 4.7044C1.05846 3.486 1.90779 2.50462 2.94863 2.23848C4.02694 1.96403 5.05113 2.34244 5.45914 3.17412C5.60486 3.46936 5.63817 3.96005 5.52992 4.18876C5.45082 4.35094 5.17187 4.54638 5.01783 4.54638C4.90542 4.54638 4.90958 4.55886 5.0095 4.35925C5.10526 4.17213 5.11775 3.89352 5.03864 3.70223C4.95121 3.49431 4.55986 3.09511 4.33087 2.97867C3.46073 2.53789 2.28249 3.04937 1.77873 4.08896C1.50811 4.64202 1.45398 4.86658 1.45398 5.44043C1.46231 6.4925 1.90363 7.37408 2.76544 8.05605C3.53983 8.66733 4.50157 9 5.49245 9C6.49582 9 7.48254 8.66317 8.29024 8.04773L8.59416 7.81486L8.31105 7.53209C8.15701 7.37823 8.01545 7.24932 7.9988 7.24932C7.98215 7.24932 7.84475 7.34497 7.69071 7.45724C7.34931 7.71922 6.97045 7.91051 6.46252 8.08516C6.08365 8.21407 6.05034 8.21823 5.36339 8.22238C4.57651 8.22654 4.4183 8.19743 3.86457 7.93961C2.49899 7.3117 1.79538 5.96439 2.16176 4.69192C2.29498 4.23034 2.67801 3.76045 3.09019 3.56085C3.36081 3.42778 3.82294 3.38204 4.06858 3.4652C4.36418 3.56085 4.60982 3.88104 4.53488 4.07233C4.51406 4.12639 4.48908 4.12223 4.37667 4.04322C4.22262 3.93094 3.87706 3.89352 3.66473 3.96005C3.16096 4.12639 2.74046 4.96638 2.84455 5.59429C2.96529 6.33864 3.42326 6.9125 4.17683 7.26596C5.04697 7.67764 5.93377 7.661 6.86636 7.22437C7.09535 7.11626 7.56165 6.78774 7.79063 6.56735C8.02794 6.34696 8.41097 5.81053 8.59 5.45291C8.85645 4.91232 8.96886 4.45906 8.99384 3.78956C9.03131 2.88303 8.90225 2.29254 8.48175 1.46918C8.27358 1.06582 7.86973 0.496125 7.74067 0.421274C7.68238 0.388007 7.13282 0.857903 7.13282 0.941071C7.13282 0.97018 7.19943 1.06582 7.27854 1.15731C7.47005 1.38186 7.7823 1.90166 7.9072 2.21353C8.2028 2.93709 8.27358 3.83946 8.09456 4.54222C7.9988 4.92479 7.75316 5.44459 7.52417 5.75647C6.92465 6.57151 5.85883 7.05388 4.95538 6.9125C4.51822 6.8418 4.02695 6.60062 3.78131 6.32617C3.49403 6.00597 3.41493 5.83132 3.39411 5.44043C3.37746 5.15766 3.38995 5.07034 3.46905 4.88321C3.57314 4.64618 3.79796 4.46321 3.98947 4.46321C4.11021 4.46321 4.11854 4.49232 4.03527 4.61292C3.91037 4.78757 3.87706 5.03291 3.94368 5.26994C4.03527 5.58597 4.26842 5.83132 4.64312 6.00597C5.20934 6.26795 5.88381 6.21389 6.40423 5.86043C6.79142 5.59845 7.08286 5.26162 7.27854 4.85826C7.50752 4.37589 7.54915 4.19292 7.54915 3.63986C7.54915 2.65016 7.19943 1.88918 6.39174 1.13236C5.49661 0.292364 4.36001 -0.090206 3.09435 0.0179117Z" fill="'.esc_attr($fill_color).'"/>
                                                        </svg>
                                                    </span> 
                                                    <span class="cat-name">'.esc_html(mb_strtoupper($cat->name)).'</span> 
                                                    <span class="cat-count">('.intval($cat->count).')</span>
                                                </a></li>';
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo '<p>' . esc_html__('Keine Kategorien', 'mojmotyw') . '</p>';
                                        }
                                        ?>
                        </div>
                </div>

                <?php if ($show_ig || $show_tiktok || $show_fb): ?>
                <div class="blog-widget blog-widget--sidebar-socials" style="text-align: center; padding: 2rem; background: #FFFAF6; border-radius: 20px; box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.06);">
                    <h3 class="blog-widget__title" style="margin-bottom: 1rem; font-size: 1.1rem; color: #111;"><?php echo esc_html($find_us_title); ?></h3>
                    <div class="blog-widget-socials" style="justify-content: center; gap: 0.8rem;">
                        <?php if ($show_ig && !empty($ig_link)): ?>
                            <a href="<?php echo esc_url($ig_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                        <?php endif; ?>
                        <?php if ($show_tiktok && !empty($tiktok_link)): ?>
                            <a href="<?php echo esc_url($tiktok_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                            </a>
                        <?php endif; ?>
                        <?php if ($show_fb && !empty($fb_link)): ?>
                            <a href="<?php echo esc_url($fb_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</div>