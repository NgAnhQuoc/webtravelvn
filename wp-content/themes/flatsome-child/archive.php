<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * The template for displaying archive pages.
 *
 * @package flatsome
 */

get_header();

$taxonomies = get_object_taxonomies('post', 'objects');
unset($taxonomies['post_tag'], $taxonomies['post_format']);
?>

<div id="content" class="cs-dark-theme">

    <!-- Hero Header -->
    <section class="cs-page-header"
        style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ninhbinh.png');">
        <div class="cs-page-header-overlay"></div>
        <div class="cs-page-header-content">
            <span class="cs-category-badge-top">CHUYÊN MỤC</span>
            <h1 class="cs-archive-title"><?php single_cat_title(); ?></h1>
        </div>
    </section>

    <!-- Main Content -->
    <section class="cs-main-content">
        <div class="cs-container-wide">
            <!-- Nút mở bộ lọc trên Mobile -->
            <div class="cs-mobile-filter-btn">
                <i class="icon-menu"></i> BỘ LỌC TÌM KIẾM
            </div>

            <div class="cs-layout-flex">
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
                    <?php if (have_posts()): ?>
                        <div class="cs-post-grid">
                            <?php while (have_posts()):
                                the_post();
                                get_template_part('template-parts/post/content', 'luxury');
                            endwhile; ?>
                        </div>
                        <div class="cs-pagination">
                            <?php echo paginate_links(array('prev_text' => '<i class="icon-angle-left"></i>', 'next_text' => '<i class="icon-angle-right"></i>')); ?>
                        </div>
                    <?php else: ?>
                        <div class="cs-no-results-template">
                            <div class="cs-no-results-icon">
                                <i class="icon-search"></i>
                            </div>
                            <h4 class="cs-font-serif">KHÔNG TÌM THẤY BÀI VIẾT PHÙ HỢP</h4>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
            <div class="cs-filter-overlay"></div>
    </section>
</div>

<script>
    jQuery(document).ready(function ($) {
        // Mở bộ lọc
        $('.cs-mobile-filter-btn').on('click', function () {
            $('.cs-sidebar').addClass('is-active');
            $('.cs-filter-overlay').fadeIn();
            $('body').css('overflow', 'hidden');
        });

        // Đóng bộ lọc
        $('.cs-sidebar-close, .cs-filter-overlay').on('click', function () {
            $('.cs-sidebar').removeClass('is-active');
            $('.cs-filter-overlay').fadeOut();
            $('body').css('overflow', 'auto');
        });

        // Nút Áp dụng trên Mobile
        $('.cs-mobile-apply-btn').on('click', function () {
            if (typeof cs_filter_posts === 'function') {
                cs_filter_posts(1);
            }
            $('.cs-sidebar-close').trigger('click');
        });
    });
</script>

<?php get_footer(); ?>