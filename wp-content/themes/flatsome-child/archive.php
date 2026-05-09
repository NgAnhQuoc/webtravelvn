<?php
/**
 * The archive template file.
 *
 * @package flatsome
 */

get_header();
?>

<div id="content" class="blog-wrapper blog-archive page-wrapper">
    
    <!-- Hero Header -->
    <section class="cs-page-header" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ninhbinh.png');">
        <div class="cs-page-header-overlay"></div>
        <div class="cs-container-wide cs-page-header-content" style="text-align: center;">
            <div class="cs-category-container">
                <span class="cs-category-badge">BỘ SƯU TẬP HÀNH TRÌNH</span>
            </div>
            <h1 class="cs-font-serif cs-archive-title">
                <?php echo get_the_archive_title(); ?>
            </h1>
            <?php if (get_the_archive_description()) : ?>
                <div class="cs-page-header-excerpt">
                    <?php echo get_the_archive_description(); ?>
                </div>
            <?php else : ?>
                <p class="cs-page-header-excerpt">
                    Khám phá những câu chuyện văn hóa, ẩm thực và hành trình di sản qua những bài viết chọn lọc nhất.
                </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content Grid -->
    <section class="cs-container-wide" style="padding: 80px 0;">

        <?php if (have_posts()) : ?>
            <div class="cs-post-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="cs-post-card">
                        <a href="<?php the_permalink(); ?>" class="cs-post-card-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="cs-post-card-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');"></div>
                            <?php else : ?>
                                <div class="cs-post-card-image" style="background-color: #333;"></div>
                            <?php endif; ?>
                            
                            <div class="cs-post-card-overlay"></div>
                            
                            <div class="cs-post-card-content">
                                <span class="cs-post-card-category">
                                    <?php 
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo esc_html($categories[0]->name);
                                    } else {
                                        echo 'DU LỊCH';
                                    }
                                    ?>
                                </span>
                                <h3 class="cs-post-card-title"><?php the_title(); ?></h3>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="cs-pagination">
                <?php echo paginate_links(array(
                    'prev_text' => '<i class="icon-angle-left"></i>',
                    'next_text' => '<i class="icon-angle-right"></i>',
                )); ?>
            </div>

        <?php else : ?>
            <p style="text-align: center; color: var(--text-muted);">Chưa có bài viết nào trong mục này.</p>
        <?php endif; ?>
    </section>
</div>

<?php get_footer(); ?>
