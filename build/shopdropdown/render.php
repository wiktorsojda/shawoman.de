
                    <div class="shop-nav">
                    <div class="dropdown-content">
                        <div class="dropdown-inner">
                            <div class="categories-column">
                                <?php
                                $product_categories = get_terms('product_cat', array(
                                    'orderby' => 'name',
                                    'hide_empty' => false,
                                ));

                                foreach ($product_categories as $category) {
                                    if ($category->name == 'Bez kategorii') {
                                        continue;
                                    }
                                    echo '<div class="category">';
                                    echo '<h4><a href="' . get_term_link($category) . '">' . $category->name . '</a></h4>';

                                    $products = wc_get_products(array(
                                        'category' => array($category->slug),
                                        'limit' => 4,
                                    ));

                                    if (!empty($products)) {
                                        echo '<ul class="product-list">';
                                        foreach ($products as $product) {
                                            echo '<li><a href="' . get_permalink($product->get_id()) . '">' . $product->get_name() . '</a></li>';
                                        }
                                        echo '</ul>';
                                    }

                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <div class="image-column" style=" height: 400px; width: 600px; background-size: cover; background-position: center;">
                                <button class="menu-slider-button"><a href="href="<?php echo site_url('/shop'); ?>">Kup Teraz</a></button>
                            </div>
                        </div>
                        
                    </div>
                    <div id="mobile-menu-root"></div>
                    </div>
  
