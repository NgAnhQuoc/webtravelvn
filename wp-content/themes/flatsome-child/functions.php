<?php
// Thêm trường Up ảnh cho Category
add_action('category_add_form_fields', 'cs_add_category_image_field', 10, 2);
function cs_add_category_image_field($taxonomy)
{
    ?>
    <div class="form-field term-group">
        <label for="category-image-id"><?php _e('Ảnh đại diện', 'hero'); ?></label>
        <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
        <div id="category-image-wrapper"></div>
        <p>
            <input type="button" class="button button-secondary cs_tax_media_button" id="cs_tax_media_button"
                name="cs_tax_media_button" value="<?php _e('Thêm ảnh', 'hero'); ?>" />
            <input type="button" class="button button-secondary cs_tax_media_remove" id="cs_tax_media_remove"
                name="cs_tax_media_remove" value="<?php _e('Xóa ảnh', 'hero'); ?>" />
        </p>
    </div>
    <?php
}

add_action('category_edit_form_fields', 'cs_edit_category_image_field', 10, 2);
function cs_edit_category_image_field($term, $taxonomy)
{
    $image_id = get_term_meta($term->term_id, 'featured_image_id', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="category-image-id"><?php _e('Ảnh đại diện', 'hero'); ?></label></th>
        <td>
            <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
            <div id="category-image-wrapper">
                <?php if ($image_id)
                    echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
            </div>
            <p>
                <input type="button" class="button button-secondary cs_tax_media_button" id="cs_tax_media_button"
                    name="cs_tax_media_button" value="<?php _e('Thêm/Sửa ảnh', 'hero'); ?>" />
                <input type="button" class="button button-secondary cs_tax_media_remove" id="cs_tax_media_remove"
                    name="cs_tax_media_remove" value="<?php _e('Xóa ảnh', 'hero'); ?>" />
            </p>
        </td>
    </tr>
    <?php
}

add_action('created_category', 'cs_save_category_image', 10, 2);
add_action('edited_category', 'cs_save_category_image', 10, 2);
function cs_save_category_image($term_id, $tt_id)
{
    if (isset($_POST['category-image-id']) && '' !== $_POST['category-image-id']) {
        update_term_meta($term_id, 'featured_image_id', $_POST['category-image-id']);
    } else {
        delete_term_meta($term_id, 'featured_image_id');
    }
}

// Nhúng script Media - Chuyển xuống Footer để tránh lỗi jQuery
add_action('admin_enqueue_scripts', 'cs_load_media_script');
function cs_load_media_script($hook)
{
    if ('edit-tags.php' !== $hook && 'term.php' !== $hook)
        return;
    wp_enqueue_media();
}

add_action('admin_footer', 'cs_add_category_script_footer');
function cs_add_category_script_footer()
{
    $screen = get_current_screen();
    if ($screen->id !== 'edit-category' && $screen->id !== 'category')
        return;
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var file_frame;
            $('body').on('click', '.cs_tax_media_button', function (e) {
                e.preventDefault();
                if (file_frame) {
                    file_frame.open();
                    return;
                }
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Chọn hoặc tải ảnh lên',
                    button: { text: 'Sử dụng ảnh này' },
                    multiple: false
                });
                file_frame.on('select', function () {
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    $('#category-image-id').val(attachment.id);
                    $('#category-image-wrapper').html('<img src="' + attachment.url + '" style="max-width:150px;display:block;margin-top:10px;border:1px solid #ddd;" />');
                });
                file_frame.open();
            });
            $('body').on('click', '.cs_tax_media_remove', function (e) {
                e.preventDefault();
                $('#category-image-id').val('');
                $('#category-image-wrapper').html('');
            });
        });
    </script>
    <?php
}


// Add custom Theme Functions here

// Nhúng các thư viện cần thiết (Slick Slider, Font Awesome)
add_action('wp_enqueue_scripts', 'cs_enqueue_assets');
function cs_enqueue_assets()
{
    // Slick Slider
    wp_enqueue_style('slick-css', get_stylesheet_directory_uri() . '/assets/css/slick.css', array(), '1.8.1');
    wp_enqueue_style('slick-theme-css', get_stylesheet_directory_uri() . '/assets/css/slick-theme.css', array('slick-css'), '1.8.1');
    wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('jquery'), '1.8.1', true);

    // Font Awesome
    wp_enqueue_style('font-awesome-5', get_stylesheet_directory_uri() . '/assets/all.min.css', array(), '5.15.4');
}


// Custom Sticky Header Threshold
add_action('wp_footer', 'custom_sticky_header_script');
function custom_sticky_header_script()
{
    ?>
    <script>
        window.addEventListener('scroll', function () {
            var header = document.querySelector('.header-wrapper');
            if (!header) return;
            // Bạn có thể chỉnh số 100 thành bất kỳ số nào bạn muốn
            if (window.scrollY > 100) {
                header.classList.add('custom-stuck');
            } else {
                header.classList.remove('custom-stuck');
            }
        });
    </script>
    <?php
}

// Tính thời gian đọc bài viết
function flatsome_reading_time()
{
    $post = get_post();
    if (!$post || empty($post->post_content))
        return '1 phút đọc';

    // Đếm từ chuẩn xác cho Tiếng Việt bằng cách tách khoảng trắng
    $content = strip_tags($post->post_content);
    $word_count = count(preg_split('/\s+/u', $content, -1, PREG_SPLIT_NO_EMPTY));

    $reading_time = ceil($word_count / 200); // 200 từ/phút

    return $reading_time . ' phút đọc';
}


// Tự động thêm field Hero Subtitle vào bài viết
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
        'key' => 'group_hero_settings',
        'title' => 'Cài đặt Hero Header',
        'fields' => array(
            array(
                'key' => 'field_hero_title_1',
                'label' => 'Tiêu đề chính (Dòng 1 - Màu trắng)',
                'name' => 'hero_title_1',
                'type' => 'text',
                'instructions' => 'Nếu để trống, sẽ tự động lấy Tiêu đề bài viết.',
            ),
            array(
                'key' => 'field_hero_subtitle',
                'label' => 'Tiêu đề phụ (Dòng 2 - Màu vàng)',
                'name' => 'hero_subtitle',
                'type' => 'text',
                'instructions' => 'Nhập phần tiêu đề muốn hiển thị màu vàng ở dòng 2 (Vd: Ký Ức Sống)',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
    ));


endif;

// Shortcode hiển thị Video với style Premium
// Cách dùng: [cs_video url="link_youtube"]
function cs_video_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'url' => '',
    ), $atts);

    if (empty($atts['url']))
        return '';

    $url = $atts['url'];

    // Sử dụng Regex để lấy ID video YouTube chuẩn xác nhất
    $video_id = '';
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
        $video_id = $match[1];
    }

    if (empty($video_id))
        return '<!-- Invalid Video URL -->';

    $embed_url = 'https://www.youtube.com/embed/' . $video_id;

    return '<div class="cs-video-responsive">
                <iframe src="' . esc_url($embed_url) . '" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                </iframe>
            </div>';
}

add_shortcode('cs_video', 'cs_video_shortcode');

// Đăng ký Element vào UX Builder của Flatsome
add_action('ux_builder_setup', 'cs_register_ux_builder_video_element');
function cs_register_ux_builder_video_element()
{
    add_ux_builder_shortcode('cs_video', array(
        'name' => 'Premium Video',
        'category' => 'Content',
        'priority' => 1,
        'options' => array(
            'url' => array(
                'type' => 'textfield',
                'heading' => 'Video URL',
                'default' => '',
                'placeholder' => 'Dán link YouTube vào đây...',
            ),
        ),
    ));

    add_ux_builder_shortcode('cs_quote', array(
        'name' => 'Premium Quote',
        'category' => 'Content',
        'priority' => 2,
        'options' => array(
            'content' => array(
                'type' => 'textarea',
                'heading' => 'Nội dung trích dẫn',
                'default' => 'Nhập nội dung trích dẫn ở đây...',
            ),
        ),
    ));


    // Register CS Quote Box
    add_ux_builder_shortcode('cs_quote_box', array(
        'name' => 'CS Quote Box',
        'category' => 'Content',
        'priority' => 3,
        'options' => array(
            'bg' => array(
                'type' => 'image',
                'heading' => 'Ảnh nền',
            ),
            'text' => array(
                'type' => 'textarea',
                'heading' => 'Nội dung câu trích',
                'default' => '',
            ),
            'btn_text' => array(
                'type' => 'textfield',
                'heading' => 'Văn bản nút',
                'default' => 'TÌM HIỂU THÊM',
            ),
            'btn_link' => array(
                'type' => 'textfield',
                'heading' => 'Link nút',
                'default' => '#',
            ),
        ),
    ));

    // Register CS Feature Column
    add_ux_builder_shortcode('cs_feature_col', array(
        'name' => 'CS Feature Column',
        'category' => 'Content',
        'priority' => 4,
        'options' => array(
            'icon' => array(
                'type' => 'textfield',
                'heading' => 'Icon Class',
                'default' => 'fas fa-star',
                'placeholder' => 'e.g., fas fa-map-marked-alt',
            ),
            'title' => array(
                'type' => 'textfield',
                'heading' => 'Tiêu đề',
                'default' => 'TRẢI NGHIỆM THỰC',
            ),
            'text' => array(
                'type' => 'textarea',
                'heading' => 'Mô tả',
                'default' => '',
            ),
            'link' => array(
                'type' => 'textfield',
                'heading' => 'Link liên kết',
                'default' => '',
            ),
        ),
    ));

    add_ux_builder_shortcode('cs_destinations_slider', array(
        'name' => 'CS Destination Categories',
        'category' => 'Content',
        'priority' => 10,
        'options' => array(
            'ids' => array(
                'type' => 'select',
                'heading' => 'Chọn danh mục',
                'config' => array(
                    'multiple' => true,
                    'placeholder' => 'Chọn danh mục...',
                    'termSelect' => array(
                        'post_type' => 'post',
                        'taxonomies' => 'category',
                    ),
                ),
            ),




            'icon' => array(
                'type' => 'textfield',
                'heading' => 'Icon (FontAwesome)',
                'default' => 'fas fa-map-marker-alt',
                'placeholder' => 'Ví dụ: fas fa-plane',
            ),
            'layout_style' => array(
                'type' => 'select',
                'heading' => 'Kiểu hiển thị',
                'default' => 'style1',
                'options' => array(
                    'style1' => 'Kiểu 1 (Tiêu đề trên - Icon dưới)',
                    'style2' => 'Kiểu 2 (Icon + Tiêu đề - Số lượng dưới)',
                )
            ),
            'columns' => array(
                'type' => 'select',
                'heading' => 'Số cột hiển thị',
                'default' => '3',
                'options' => array(
                    '1' => '1 Cột',
                    '2' => '2 Cột',
                    '3' => '3 Cột',
                    '4' => '4 Cột',
                    '5' => '5 Cột',
                    '6' => '6 Cột',
                )
            ),
            'gap' => array(
                'type' => 'slider',
                'heading' => 'Khoảng cách giữa các mục (Gap)',
                'default' => 25,
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
            ),
            'show_title' => array(
                'type' => 'checkbox',
                'heading' => 'Hiện Tiêu đề',
                'default' => 'true',
            ),
            'show_desc' => array(
                'type' => 'checkbox',
                'heading' => 'Hiện Mô tả',
                'default' => 'true',
            ),
            'show_count' => array(
                'type' => 'checkbox',
                'heading' => 'Hiện Số địa điểm',
                'default' => 'true',
            ),
            'count_text' => array(
                'type' => 'textfield',
                'heading' => 'Chữ hiển thị sau số',
                'default' => 'địa điểm',
                'placeholder' => 'Ví dụ: địa điểm, bài viết...',
            ),
            'show_arrows' => array(
                'type' => 'checkbox',
                'heading' => 'Hiện Nút điều hướng',
                'default' => 'true',
            ),
            'autoplay' => array(
                'type' => 'slider',
                'heading' => 'Tự động chạy (mili giây)',
                'default' => 5000,
                'unit' => 'ms',
                'min' => 0,
                'max' => 10000,
                'step' => 500,
            ),
        ),
    ));


    // Register CS Contact List (Parent)
    add_ux_builder_shortcode('cs_contact_list', array(
        'name' => 'CS Contact List',
        'category' => 'Content',
        'priority' => 5,
        'type' => 'container',
        'options' => array(
            'class' => array(
                'type' => 'textfield',
                'heading' => 'Custom Class',
            ),
        ),
    ));

    // Register CS Contact Item (Child)
    add_ux_builder_shortcode('cs_contact_item', array(
        'name' => 'CS Contact Item',
        'category' => 'Content',
        'priority' => 6,
        'require' => array('cs_contact_list'),
        'options' => array(
            'icon' => array(
                'type' => 'textfield',
                'heading' => 'Icon Class',
                'default' => 'far fa-envelope',
            ),
            'val' => array(
                'type' => 'textfield',
                'heading' => 'Nội dung',
                'default' => '',
            ),
            'link' => array(
                'type' => 'textfield',
                'heading' => 'Link (Tùy chọn)',
                'default' => '',
            ),
        ),
    ));
}

// Shortcode cho CS Quote Box
function cs_quote_box_shortcode($atts)
{
    extract(shortcode_atts(array(
        'bg' => '',
        'text' => 'Chúng tôi tin rằng, mỗi vùng đất đều có câu chuyện riêng.',
        'btn_text' => 'TÌM HIỂU THÊM',
        'btn_link' => '#',
    ), $atts));

    $bg_url = '';
    if ($bg) {
        $bg_img = wp_get_attachment_image_src($bg, 'original');
        $bg_url = $bg_img ? $bg_img[0] : '';
    }

    ob_start();
    ?>
    <div class="cs-quote-box">
        <div class="cs-quote-bg">
            <?php if ($bg_url): ?>
                <img src="<?php echo esc_url($bg_url); ?>" alt="Background">
            <?php endif; ?>
        </div>
        <div class="cs-quote-overlay"></div>
        <i class="fas fa-quote-left"></i>
        <p><?php echo esc_html($text); ?></p>
        <?php if ($btn_text): ?>
            <a href="<?php echo esc_url($btn_link); ?>" class="cs-btn cs-btn-outline" style="align-self: flex-start;">
                <?php echo esc_html($btn_text); ?>
            </a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('cs_quote_box', 'cs_quote_box_shortcode');

// Shortcode cho CS Feature Column
function cs_feature_col_shortcode($atts)
{
    extract(shortcode_atts(array(
        'icon' => 'fas fa-star',
        'title' => 'TRẢI NGHIỆM THỰC',
        'text' => 'Khám phá tận cùng vẻ đẹp của cảnh quan và con người Việt Nam.',
        'link' => '',
    ), $atts));

    $tag = $link ? 'a' : 'div';
    $href = $link ? ' href="' . esc_url($link) . '"' : '';

    return '<' . $tag . $href . ' class="cs-feature-col">
                <i class="' . esc_attr($icon) . '"></i>
                <h4>' . esc_html($title) . '</h4>
                <p>' . esc_html($text) . '</p>
            </' . $tag . '>';
}
add_shortcode('cs_feature_col', 'cs_feature_col_shortcode');

// Shortcode hiển thị Slider Chuyên mục Điểm Đến
add_shortcode('cs_destinations_slider', 'cs_destinations_slider_shortcode');
function cs_destinations_slider_shortcode($atts)
{
    extract(shortcode_atts(array(
        'ids' => '',
        'icon' => 'fas fa-map-marker-alt',
        'layout_style' => 'style1',
        'columns' => '3',
        'gap' => '25',
        'autoplay' => '5000',
        'show_title' => 'true',
        'show_desc' => 'true',
        'show_count' => 'true',
        'count_text' => 'địa điểm',
        'show_arrows' => 'true',
    ), $atts));

    // Xử lý biến ids: Chuyển mọi định dạng về mảng các ID số nguyên
    $term_ids = array();
    if (!empty($ids)) {
        $raw_ids = is_array($ids) ? $ids : explode(',', $ids);
        foreach ($raw_ids as $rid) {
            $rid = trim($rid);
            if (!empty($rid))
                $term_ids[] = intval($rid);
        }
    }

    $terms = array();
    if (!empty($term_ids)) {
        foreach ($term_ids as $t_id) {
            $term_obj = get_term($t_id, 'category');
            if ($term_obj && !is_wp_error($term_obj)) {
                $terms[] = $term_obj;
            }
        }
    }

    if (empty($terms)) {
        // Nếu không chọn ID nào, lấy 4 cái mới nhất mặc định
        $terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false, 'number' => 4));
    }

    if (empty($terms) || is_wp_error($terms))
        return '<p>Chưa có danh mục nào.</p>';

    $id = 'cs-slick-' . uniqid();
    $term_count = count($terms);

    // Nhân bản item nếu số lượng ít hơn 2 lần số cột để Slick Slider hoạt động mượt mà hơn (tránh lỗi khi số cột lớn)
    if ($term_count > 0 && $term_count < (intval($columns) * 2)) {
        $terms = array_merge($terms, $terms);
        if (count($terms) < (intval($columns) * 2)) {
            $terms = array_merge($terms, $terms);
        }
        $term_count = count($terms);
    }

    $term_count = count($terms);
    $gap_val = intval($gap);
    $padding_val = $gap_val / 2;

    // Tự động thêm padding nếu đang ở trong trình UX Builder để hiện nút bấm
    $builder_style = (isset($_GET['app-uxbuilder']) || is_admin()) ? 'padding: 0 70px !important;' : '';

    ob_start();
    ?>
    <div class="cs-slider-outer <?php echo ($show_arrows === 'true') ? 'has-arrows' : 'no-arrows'; ?>"
        style="<?php echo $builder_style; ?>">
        <div class="cs-slider-inner">

            <div id="<?php echo $id; ?>" class="cs-slick-slider" data-columns="<?php echo $columns; ?>"
                style="margin: 0 -<?php echo $padding_val; ?>px;">



                <?php foreach ($terms as $term):
                    $image_id = get_term_meta($term->term_id, 'featured_image_id', true);
                    $img_url = $image_id ? wp_get_attachment_image_src($image_id, 'large')[0] : get_stylesheet_directory_uri() . '/assets/images/placeholder.png';
                    ?>
                    <div class="slick-item" style="padding: 0 <?php echo $padding_val; ?>px !important;">
                        <div class="cs-card <?php echo ($layout_style === 'style2') ? 'cs-card-s2' : ''; ?>"
                            onclick="window.location.href='<?php echo get_term_link($term); ?>'">
                            <div class="cs-card-img">
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($term->name); ?>">
                            </div>
                            <div class="cs-card-overlay">
                                <div class="cs-card-content">
                                    <?php if ($layout_style === 'style1'): ?>
                                        <?php if ($show_title === 'true'): ?>
                                            <h3 class="cs-card-title"><?php echo esc_html($term->name); ?></h3>
                                            <?php if ($show_desc === 'true' && !empty($term->description)): ?>
                                                <div class="cs-card-desc"
                                                    style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-bottom: 10px; line-height: 1.4;">
                                                    <?php echo nl2br(esc_html($term->description)); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($show_desc === 'true' && !empty($term->description)): ?>
                                                <h3 class="cs-card-title"><?php echo nl2br(esc_html($term->description)); ?></h3>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($show_count === 'true'): ?>
                                            <div class="cs-card-meta">
                                                <i class="<?php echo esc_attr($icon); ?>"></i> <?php echo $term->count; ?>
                                                <?php echo esc_html(mb_strtoupper($count_text)); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: // Style 2 ?>
                                        <div class="cs-card-title-s2"
                                            style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem !important; color: #a3a3a3 !important; text-transform: uppercase !important; margin-bottom: 5px !important; font-weight: 600 !important;">
                                            <i class="<?php echo esc_attr($icon); ?>"
                                                style="color: var(--gold); font-size: 0.9rem;"></i>
                                            <?php
                                            if ($show_title === 'true') {
                                                echo esc_html($term->name);
                                            } else if (!empty($term->description)) {
                                                echo esc_html($term->description);
                                            }
                                            ?>
                                        </div>

                                        <?php if ($show_count === 'true'): ?>
                                            <div class="cs-card-meta-s2"
                                                style="color: #fff !important; font-size: 0.75rem !important; font-weight: 400 !important; text-transform: none !important;">
                                                <?php echo $term->count; ?>                 <?php echo esc_html($count_text); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>



                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="<?php echo $id; ?>-nav"
            class="cs-slider-nav <?php echo ($layout_style === 'style2') ? 'cs-slider-nav-s2' : ''; ?>"></div>
    </div>




    <script>
        (function ($) {
            var initSlick_<?php echo str_replace('-', '_', $id); ?> = function () {
                var $slider = $('#<?php echo $id; ?>');
                if (!$slider.length) return;

                // Nếu đã init rồi thì unslick để init lại với cấu hình mới
                if ($slider.hasClass('slick-initialized')) {
                    $slider.slick('unslick');
                }

                var cols = parseInt($slider.attr('data-columns')) || 3;
                var total = <?php echo intval($term_count); ?>;

                $slider.slick({
                    dots: false,
                    infinite: total > cols,
                    speed: 500,
                    slidesToShow: cols,
                    slidesToScroll: 1,
                    autoplay: <?php echo intval($autoplay) > 0 ? 'true' : 'false'; ?>,
                    autoplaySpeed: <?php echo intval($autoplay); ?>,
                    arrows: <?php echo ($show_arrows === 'true') ? 'true' : 'false'; ?>,
                    appendArrows: $('#<?php echo $id; ?>-nav'),
                    responsive: [
                        {
                            breakpoint: 1100,
                            settings: {
                                slidesToShow: (cols > 4) ? 4 : cols
                            }
                        },
                        {
                            breakpoint: 850,
                            settings: {
                                slidesToShow: (cols > 3) ? 3 : (cols > 2 ? 2 : cols)
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            };

            $(document).ready(function () {
                initSlick_<?php echo str_replace('-', '_', $id); ?>();
            });

            // Hỗ trợ UX Builder render lại
            $(document).on('ux_builder_rendered', function () {
                initSlick_<?php echo str_replace('-', '_', $id); ?>();
            });

            // Timeout dự phòng cho builder
            setTimeout(initSlick_<?php echo str_replace('-', '_', $id); ?>, 300);
        })(jQuery);
    </script>
    <?php
    return ob_get_clean();
}

// Shortcode cho CS Contact List (Parent)
function cs_contact_list_shortcode($atts, $content = null)
{
    extract(shortcode_atts(array(
        'class' => '',
    ), $atts));

    return '<ul class="cs-contact-list ' . esc_attr($class) . '">' . do_shortcode($content) . '</ul>';
}
add_shortcode('cs_contact_list', 'cs_contact_list_shortcode');

// Shortcode cho CS Contact Item (Child)
function cs_contact_item_shortcode($atts, $content = null)
{
    extract(shortcode_atts(array(
        'icon' => 'far fa-envelope',
        'val' => '',
        'link' => '',
    ), $atts));

    // Lấy nội dung từ attribute 'val' hoặc từ nội dung bên trong shortcode
    $display_text = !empty($val) ? $val : $content;

    $tag = $link ? 'a' : 'li';
    $href = $link ? ' href="' . esc_url($link) . '"' : '';
    $link_class = $link ? ' has-link' : '';

    return '<' . $tag . $href . ' class="cs-contact-item' . $link_class . '">
                <i class="' . esc_attr($icon) . '"></i>
                <span>' . $display_text . '</span>
            </' . $tag . '>';
}
add_shortcode('cs_contact_item', 'cs_contact_item_shortcode');


// Shortcode cho Trích dẫn
function cs_quote_shortcode($atts, $content = null)
{
    return '<blockquote class="wp-block-quote"><p>' . do_shortcode($content) . '</p></blockquote>';
}
add_shortcode('cs_quote', 'cs_quote_shortcode');


// Thêm nút bấm [cs_video] vào thanh công cụ Classic Editor (TinyMCE)
add_action('admin_init', 'cs_add_tinymce_video_button');
function cs_add_tinymce_video_button()
{
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'cs_add_tinymce_video_plugin');
        add_filter('mce_buttons', 'cs_register_tinymce_video_button');
    }
}

// Liên kết file JS của plugin
function cs_add_tinymce_video_plugin($plugin_array)
{
    $plugin_array['cs_video_plugin'] = get_stylesheet_directory_uri() . '/assets/js/tinymce-video.js';
    return $plugin_array;
}

// Đăng ký tên nút bấm để hiển thị
function cs_register_tinymce_video_button($buttons)
{
    array_push($buttons, 'cs_video_button', 'cs_quote_button');
    return $buttons;
}