<?php
/**
 * The template for displaying search results pages.
 *
 * @package flatsome
 */

get_header();
?>

<div id="content" class="cs-dark-theme search-results-page">

    <!-- Hero Header -->
    <section class="cs-page-header"
        style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ninhbinh.png'); background-color: #0f0f0f;">
        <div class="cs-page-header-overlay"></div>
        <div class="cs-page-header-content">
            <span class="cs-category-badge-top">KẾT QUẢ TÌM KIẾM</span>
            <h1 class="cs-archive-search">
                <?php printf(esc_html__('"%s"', 'flatsome'), '<span>' . get_search_query() . '</span>'); ?>
            </h1>
        </div>
    </section>

    <!-- Main Content -->
    <section class="cs-main-content">
        <div class="cs-container-wide">
            <div id="cs-ajax-results" class="cs-results-area-full">
                <?php if (have_posts()): ?>
                    <div class="cs-post-grid">
                        <?php while (have_posts()):
                            the_post();
                            get_template_part('template-parts/post/content', 'luxury');
                        endwhile; ?>
                    </div>
                    <div class="cs-pagination">
                        <?php
                        global $wp_query;
                        $big = 999999999; // need an unlikely integer
                        echo paginate_links(array(
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $wp_query->max_num_pages,
                            'prev_text' => '<i class="icon-angle-left"></i>',
                            'next_text' => '<i class="icon-angle-right"></i>',
                        ));
                        ?>
                    </div>
                <?php else: ?>
                    <div class="cs-no-results-template">
                        <div class="cs-no-results-icon">
                            <i class="icon-search"></i>
                        </div>
                        <h2 class="cs-font-serif">KHÔNG TÌM THẤY KẾT QUẢ</h2>
                        <p>Rất tiếc, chúng tôi không tìm thấy bài viết nào phù hợp với từ khóa
                            "<strong><?php echo esc_html(get_search_query()); ?></strong>".</p>
                        <div class="cs-search-retry-box">
                            <form method="get" class="searchform" action="<?php echo esc_url(home_url('/')); ?>"
                                role="search">
                                <div class="flex-row relative">
                                    <div class="flex-col flex-grow">
                                        <input type="search" class="search-field mb-0" name="s"
                                            value="<?php echo get_search_query(); ?>" placeholder="Tìm kiếm lại..."
                                            autocomplete="off">
                                    </div>
                                    <div class="flex-col">
                                        <button type="submit"
                                            class="ux-search-submit submit-button secondary button icon mb-0">
                                            <i class="icon-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="<?php echo home_url(); ?>" class="cs-btn cs-btn-solid">QUAY LẠI TRANG CHỦ</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
    .search-results-page {
        background-color: var(--bg-dark);
        min-height: 80vh;
    }

    .cs-results-area-full {
        padding: 80px 0;
    }

    .cs-no-results-template {
        text-align: center;
        padding: 100px 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .cs-no-results-icon {
        font-size: 80px;
        color: var(--gold);
        margin-bottom: 30px;
        opacity: 0.3;
    }

    .cs-no-results-template h2 {
        font-size: 2.5rem;
        color: #fff;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }

    .cs-no-results-template p {
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-bottom: 40px;
    }

    .cs-search-retry-box {
        max-width: 500px;
        margin: 0 auto 50px;
    }

    /* Custom styles for search form in 404/No results */
    .cs-search-retry-box .search-field {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--border) !important;
        color: #fff !important;
        border-radius: 50px 0 0 50px !important;
        padding: 15px 25px !important;
        height: 55px !important;
    }

    .cs-search-retry-box .ux-search-submit {
        height: 55px !important;
        border-radius: 0 50px 50px 0 !important;
        background-color: var(--gold) !important;
        border: none !important;
        width: 60px !important;
    }

    .cs-search-retry-box .ux-search-submit i {
        color: #000 !important;
        font-size: 1.2rem;
    }

    /* Đảm bảo Grid luôn đẹp ở Full width */
    @media (min-width: 1200px) {
        .cs-post-grid {
            grid-template-columns: repeat(4, 1fr) !important;
        }
    }

    @media (max-width: 1199px) and (min-width: 768px) {
        .cs-post-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (max-width: 767px) {
        .cs-no-results-template h2 {
            font-size: 1.8rem;
        }
    }
</style>

<?php get_footer(); ?>