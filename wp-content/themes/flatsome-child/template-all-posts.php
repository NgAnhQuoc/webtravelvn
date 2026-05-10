<?php
/**
 * Template Name: CS All Posts Archive
 */

get_header();

$taxonomies = get_object_taxonomies('post', 'objects');
unset($taxonomies['post_tag'], $taxonomies['post_format']);
?>

<div id="content" class="cs-dark-theme">

    <!-- Main Content -->
    <section class="cs-main-content">
        <div class="cs-container-wide">

            <div class="cs-mobile-filter-btn">
                <i class="icon-menu"></i> BỘ LỌC TÌM KIẾM
            </div>

            <div class="cs-layout-flex">
                <!-- Sidebar -->
                <aside class="cs-sidebar">
                    <div class="cs-sidebar-card">
                        <div class="cs-sidebar-header-mobile">
                            <span>BỘ LỌC</span>
                            <div class="cs-sidebar-close">&times;</div>
                        </div>
                        <?php cs_render_filter_sidebar(); ?>

                        <div class="cs-mobile-apply-btn-wrapper">
                            <div class="cs-mobile-apply-btn">ÁP DỤNG BỘ LỌC</div>
                        </div>
                    </div>
                </aside>

                <!-- Results -->
                <div id="cs-ajax-results" class="cs-results-area">
                    <?php
                    if (get_query_var('paged')) {
                        $paged = get_query_var('paged');
                    } elseif (get_query_var('page')) {
                        $paged = get_query_var('page');
                    } elseif (isset($_GET['paged'])) {
                        $paged = $_GET['paged'];
                    } else {
                        $paged = 1;
                    }
                    
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => get_option('posts_per_page'),
                        'paged' => $paged
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()): ?>
                        <div class="cs-post-grid">
                            <?php while ($query->have_posts()):
                                $query->the_post();
                                get_template_part('template-parts/post/content', 'luxury');
                            endwhile;
                            wp_reset_postdata(); ?>
                        </div>
                        <div class="cs-pagination">
                            <?php echo paginate_links(array('total' => $query->max_num_pages, 'prev_text' => '<i class="icon-angle-left"></i>', 'next_text' => '<i class="icon-angle-right"></i>')); ?>
                        </div>
                    <?php else: ?>
                        <div class="cs-no-results-template">
                            <div class="cs-no-results-icon"><span class="dashicons dashicons-search"></span></div>
                            <h4 class="cs-font-serif">KHÔNG TÌM THẤY BÀI VIẾT NÀO</h4>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="cs-filter-overlay"></div>
        </div>
    </section>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('.cs-mobile-filter-btn').on('click', function () {
            $('.cs-sidebar').addClass('is-active');
            $('.cs-filter-overlay').fadeIn();
            $('body').css('overflow', 'hidden');
        });
        $('.cs-sidebar-close, .cs-filter-overlay').on('click', function () {
            $('.cs-sidebar').removeClass('is-active');
            $('.cs-filter-overlay').fadeOut();
            $('body').css('overflow', 'auto');
        });
        $('.cs-mobile-apply-btn').on('click', function () {
            if (typeof cs_filter_posts === 'function') { cs_filter_posts(1); }
            $('.cs-sidebar-close').trigger('click');
        });
    });
</script>

<?php get_footer(); ?>