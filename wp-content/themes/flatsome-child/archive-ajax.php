<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * The archive template file (Modified for AJAX Filtering).
 *
 * @package flatsome
 */

get_header();

// Lấy danh sách Khu vực (Region) và Chủ đề (Category) để hiển thị ở Sidebar
$regions = get_terms(array('taxonomy' => 'region', 'hide_empty' => false));
$categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
$current_queried_id = get_queried_object_id();
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
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content Layout -->
    <section class="cs-container-wide" style="padding: 60px 0;">
        <div class="cs-row" style="display: flex; gap: 40px;">
            
            <!-- Sidebar: Bộ lọc -->
            <aside class="cs-filter-sidebar" style="width: 25%; min-width: 280px;">
                
                <!-- Khu Vực Filter -->
                <div class="cs-filter-group" style="margin-bottom: 40px;">
                    <h4 style="font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 1.1rem; border-bottom: 2px solid var(--gold); padding-bottom: 10px; margin-bottom: 20px;">KHU VỰC</h4>
                    <div class="cs-filter-items" style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach($regions as $region) : ?>
                            <label style="display: flex; align-items: center; cursor: pointer; font-size: 0.95rem;">
                                <input type="checkbox" class="cs-filter-region" value="<?php echo esc_attr($region->slug); ?>" style="margin-right: 12px; accent-color: var(--gold);">
                                <?php echo esc_html($region->name); ?>
                                <span style="margin-left: auto; font-size: 0.8rem; opacity: 0.5;">(<?php echo (int) $region->count; ?>)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Chủ Đề Filter -->
                <div class="cs-filter-group">
                    <h4 style="font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 1.1rem; border-bottom: 2px solid var(--gold); padding-bottom: 10px; margin-bottom: 20px;">CHỦ ĐỀ</h4>
                    <div class="cs-filter-items" style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach($categories as $cat) : 
                            $is_current = ($cat->term_id == $current_queried_id);
                            ?>
                             <label style="display: flex; align-items: center; cursor: pointer; font-size: 0.95rem;">
                                <input type="checkbox" class="cs-filter-category" value="<?php echo esc_attr($cat->slug); ?>" <?php checked($is_current); ?> style="margin-right: 12px; accent-color: var(--gold);">
                                <?php echo esc_html($cat->name); ?>
                                <span style="margin-left: auto; font-size: 0.8rem; opacity: 0.5;">(<?php echo (int) $cat->count; ?>)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <!-- Results: Danh sách bài viết -->
            <div id="cs-ajax-results" style="width: 75%; transition: opacity 0.3s ease;">
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
                                            $cats = get_the_category();
                                            echo !empty($cats) ? esc_html($cats[0]->name) : 'DU LỊCH';
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
                    <p style="text-align: center; color: var(--text-muted); padding: 40px 0;">Chưa có bài viết nào trong mục này.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
.cs-post-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}
@media (max-width: 991px) {
    .cs-row { flex-direction: column; }
    .cs-filter-sidebar, #cs-ajax-results { width: 100% !important; }
    .cs-post-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .cs-post-grid { grid-template-columns: 1fr; }
}
#cs-ajax-results.is-loading {
    position: relative;
}
#cs-ajax-results.is-loading::after {
    content: "";
    position: absolute;
    top: 100px;
    left: 50%;
    width: 40px;
    height: 40px;
    border: 3px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: var(--gold);
    animation: spin 1s ease-in-out infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<?php get_footer(); ?>
