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
    <div class="blog-hero-wrapper" style="padding: 16px; margin-top: 40px; margin-bottom: 4rem;"><header class="blog-category-banner" style="border-radius: 24px; overflow: hidden; margin-bottom: 0;">
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
        <button aria-label="Scroll to posts" onclick="document.getElementById('blog-grid').scrollIntoView({behavior: 'smooth'})" style="background: transparent; border: 0; cursor: pointer; transition: transform 0.2s ease; position: relative; margin-top: 1rem; z-index: 10; display: inline-flex; align-items: center; justify-content: center; padding: 0; pointer-events: auto; align-self: flex-start;" onmouseover="this.style.transform='translateY(2px)'" onmouseout="this.style.transform='none'">
            <svg width="71" height="71" viewBox="0 0 71 71" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <g filter="url(#filter0_d_wysylka)">
                    <path d="M6.2 30.2C6.2 14.736 18.736 2.2 34.2 2.2C49.6639 2.2 62.2 14.736 62.2 30.2C62.2 45.664 49.6639 58.2 34.2 58.2C18.736 58.2 6.2 45.664 6.2 30.2Z" fill="url(#paint0_wysylka)" fill-opacity="0.1" shape-rendering="crispEdges"/>
                    <path d="M34.2 2.7C49.3878 2.7 61.7 15.0122 61.7 30.2C61.7 45.3878 49.3878 57.7 34.2 57.7C19.0121 57.7 6.7 45.3878 6.7 30.2C6.7 15.0122 19.0121 2.7 34.2 2.7Z" stroke="url(#paint1_wysylka)" shape-rendering="crispEdges"/>
                    <path d="M34.2065 39.7C34.9084 39.6934 35.5796 39.4057 36.0745 38.8993L41.8119 33.0772C42.0605 32.8229 42.2 32.4789 42.2 32.1204C42.2 31.7618 42.0605 31.4179 41.8119 31.1636C41.6879 31.0364 41.5403 30.9354 41.3777 30.8665C41.2151 30.7976 41.0407 30.7621 40.8646 30.7621C40.6885 30.7621 40.5141 30.7976 40.3515 30.8665C40.1889 30.9354 40.0413 31.0364 39.9173 31.1636L35.5408 35.6286V22.0572C35.5408 21.6972 35.4002 21.352 35.15 21.0975C34.8997 20.843 34.5603 20.7 34.2065 20.7C33.8526 20.7 33.5132 20.843 33.263 21.0975C33.0128 21.352 32.8722 21.6972 32.8722 22.0572V35.6286L28.4823 31.1636C28.2329 30.908 27.8938 30.7637 27.5397 30.7625C27.1856 30.7612 26.8456 30.903 26.5943 31.1568C26.3431 31.4106 26.2012 31.7554 26.2 32.1156C26.1987 32.4757 26.3382 32.8216 26.5876 33.0772L32.3251 38.8993C32.8233 39.409 33.4999 39.697 34.2065 39.7Z" fill="white"/>
                </g>
                <defs>
                    <filter id="filter0_d_wysylka" x="0" y="0" width="70.4" height="70.4" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                        <feOffset dx="1" dy="5"/>
                        <feGaussianBlur stdDeviation="3.6"/>
                        <feComposite in2="hardAlpha" operator="out"/>
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.12 0"/>
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                    </filter>
                    <linearGradient id="paint0_wysylka" x1="17.91" y1="7.71" x2="44.21" y2="58.19" gradientUnits="userSpaceOnUse">
                        <stop offset="0.122" stop-color="#9E9E9E"/>
                        <stop offset="0.44" stop-color="white"/>
                    </linearGradient>
                    <linearGradient id="paint1_wysylka" x1="15.36" y1="8.56" x2="43.79" y2="59.89" gradientUnits="userSpaceOnUse">
                        <stop stop-color="white"/>
                        <stop offset="1" stop-color="white" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </button>
            </div>
        </div>
    </header></div>
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
    <div class="blog-hero-wrapper" style="padding: 16px; margin-top: 40px;">
    <header class="blog-hero-simple" style="border-radius: 24px; overflow: hidden; position: relative;">
        <!-- DEBUG HERO_BG_IMAGE: <?php echo htmlspecialchars($hero_bg_image); ?> -->
        <!-- DEBUG ALL ATTRS: <?php echo htmlspecialchars(json_encode($attributes)); ?> -->
        <?php if ($hero_bg_image): ?>
            <img src="<?php echo esc_url($hero_bg_image); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" loading="eager" fetchpriority="high" data-no-lazy="1" />
        <?php endif; ?>
        <div class="blog-category-banner__overlay">
            <div class="blog-category-banner__text-wrap">
                <h1 class="section-main-title" style="margin-bottom: 0;">
                    <?php echo wp_kses_post($title); ?>
                </h1>
                <button class="about-us-second-scroll blog-hero-scroll-btn" type="button" aria-label="Nach unten scrollen" onclick="document.getElementById('blog-grid').scrollIntoView({behavior:'smooth'})" style="background: transparent; border: 0; cursor: pointer; transition: transform 0.2s ease; position: relative; margin-top: 1rem; z-index: 10; display: inline-flex; align-items: center; justify-content: center; padding: 0; pointer-events: auto; align-self: flex-start;" onmouseover="this.style.transform='translateY(2px)'" onmouseout="this.style.transform='none'">
                    <svg width="54" height="54" viewBox="0 0 67 67" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="33.5" cy="33.5" r="32.5" stroke="white" stroke-width="2"/>
                        <path d="M34 46L34 21M34 46L24.5 37.1364M34 46L43.5 37.1364" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </header></div>
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
                                    <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($show_tiktok): ?>
                                <a href="<?php echo esc_url($tiktok_link); ?>" target="_blank" rel="noopener noreferrer" class="social-icon social-icon--tiktok" aria-label="TikTok">
                                    <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V8.69a8.16 8.16 0 0 0 4.77 1.52V6.84a4.85 4.85 0 0 1-1.84-.15"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($show_fb): ?>
                                <a href="<?php echo esc_url($fb_link); ?>" target="_blank" rel="noopener noreferrer" class="social-icon social-icon--fb" aria-label="Facebook">
                                    <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.76 8.43-4.92 8.43-9.94"/></svg>
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
                                                
                                                echo '<li class="'.esc_attr($active_class).'"><a href="'.esc_url(get_category_link($cat->term_id)).'"> 
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
                <div class="blog-widget blog-widget--sidebar-socials" style="text-align: center;">
                    <h3 class="blog-widget__title" style="margin-bottom: 1rem; font-size: 1.1rem; color: #111;"><?php echo esc_html($find_us_title); ?></h3>
                    <div class="blog-widget-socials" style="justify-content: center; gap: 0.8rem;">
                        <?php if ($show_ig && !empty($ig_link)): ?>
                            <a href="<?php echo esc_url($ig_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if ($show_tiktok && !empty($tiktok_link)): ?>
                            <a href="<?php echo esc_url($tiktok_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V8.69a8.16 8.16 0 0 0 4.77 1.52V6.84a4.85 4.85 0 0 1-1.84-.15"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if ($show_fb && !empty($fb_link)): ?>
                            <a href="<?php echo esc_url($fb_link); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.76 8.43-4.92 8.43-9.94"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</div>