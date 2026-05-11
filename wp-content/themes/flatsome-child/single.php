<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * The blog template file.
 *
 * @package flatsome
 */

get_header();
?>

<div id="content" class="blog-wrapper blog-single unit-main">
    <?php if (have_posts()): ?>
        <?php while (have_posts()):
            the_post(); ?>

            <!-- Page Header (Featured Image) -->
            <?php
            $featured_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$featured_img)
                $featured_img = get_stylesheet_directory_uri() . '/assets/images/ninhbinh.png';
            ?>
            <section class="cs-page-header" style="background-image: url('<?php echo esc_url($featured_img); ?>');">
                <div class="cs-page-header-overlay"></div>
                <div class="cs-container cs-page-header-content">
                    <?php if (has_category()): ?>
                        <div class="cs-category-container">
                            <span class="cs-category-badge">
                                <?php the_category(', '); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <h1 class="cs-font-serif">
                        <?php
                        $hero_title_1 = get_field('hero_title_1');
                        echo $hero_title_1 ? esc_html($hero_title_1) : get_the_title();
                        ?>
                        <?php if ($subtitle = get_field('hero_subtitle')): ?>
                            <br><span class="cs-text-gold"><?php echo esc_html($subtitle); ?></span>
                        <?php endif; ?>
                    </h1>
                    <?php if (has_excerpt()): ?>
                        <p class="cs-page-header-excerpt">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Article Content -->
            <!-- Article Content -->
            <section class="cs-article-section cs-container">
                <div class="cs-article-body">
                    <!-- Author Info -->
                    <div class="cs-author-info">
                        <?php echo get_avatar(get_the_author_meta('ID'), 50, '', '', array('class' => 'cs-author-avatar')); ?>
                        <div class="cs-author-details">
                            <h4 class="cs-author-name">Bởi: <?php the_author(); ?></h4>
                            <p class="cs-article-meta">
                                Đăng tải: <?php echo get_the_date('j \T\h\á\n\g n, Y'); ?> &middot;
                                <?php echo flatsome_reading_time(); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <!-- Trip Info (Chỉ hiện nếu có ít nhất 1 dữ liệu) -->
                    <?php
                    $khoi_hanh = get_field('khoi_hanh');
                    $di_chuyen = get_field('di_chuyen');
                    $luu_tru = get_field('luu_tru');
                    $am_thuc = get_field('am_thuc');

                    if ($khoi_hanh || $di_chuyen || $luu_tru || $am_thuc):
                        ?>
                        <div class="cs-trip-info-box">
                            <h3 class="cs-trip-info-title">Thông tin chuyến đi</h3>
                            <div class="cs-trip-info-grid">
                                <?php if ($khoi_hanh): ?>
                                    <div class="cs-trip-info-item"><strong class="cs-trip-label">Khởi hành:</strong>
                                        <?php echo esc_html($khoi_hanh); ?></div>
                                <?php endif; ?>

                                <?php if ($di_chuyen): ?>
                                    <div class="cs-trip-info-item"><strong class="cs-trip-label">Di chuyển:</strong>
                                        <?php echo esc_html($di_chuyen); ?></div>
                                <?php endif; ?>

                                <?php if ($luu_tru): ?>
                                    <div class="cs-trip-info-item"><strong class="cs-trip-label">Lưu trú:</strong>
                                        <?php echo esc_html($luu_tru); ?></div>
                                <?php endif; ?>

                                <?php if ($am_thuc): ?>
                                    <div class="cs-trip-info-item"><strong class="cs-trip-label">Ẩm thực:</strong>
                                        <?php echo esc_html($am_thuc); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Tags & Share -->
                    <div class="cs-article-footer">
                        <?php if (has_tag()): ?>
                            <div class="cs-article-tags">
                                <?php the_tags('', ' ', ''); ?>
                            </div>
                        <?php endif; ?>
                        <div class="cs-article-share">
                            <?php echo do_shortcode('[share]'); ?>
                        </div>
                    </div>
                </div>
            </section>

        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>