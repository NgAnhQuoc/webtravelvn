<?php
/**
 * CS Premium Filter System - FULL VERSION (Admin + REST API)
 */

/* =============================================
   1. QUẢN LÝ ADMIN (PREMIUM DASHBOARD)
   ============================================= */

add_action('admin_menu', 'cs_taxonomy_manager_menu');
function cs_taxonomy_manager_menu()
{
    add_submenu_page('edit.php', 'Quản lý Bộ lọc', 'Quản lý Bộ lọc', 'manage_options', 'cs-tax-manager', 'cs_taxonomy_manager_page');
}

function cs_taxonomy_manager_page()
{
    // Xử lý lưu dữ liệu bộ lọc
    if (isset($_POST['cs_save_taxonomies'])) {
        $labels = $_POST['tax_labels'];
        $slugs = $_POST['tax_slugs'];
        $new_data = array();
        for ($i = 0; $i < count($labels); $i++) {
            if (!empty($labels[$i]) && !empty($slugs[$i])) {
                $new_data[] = trim($labels[$i]) . '|' . sanitize_title(trim($slugs[$i]));
            }
        }
        update_option('cs_custom_taxonomies', implode("\n", $new_data));
        echo '<div class="updated"><p>Đã cập nhật danh sách Bộ lọc!</p></div>';
    }

    // Xử lý thêm mục con (Term)
    if (isset($_POST['cs_add_term'])) {
        $tax = sanitize_text_field($_POST['target_tax']);
        $term_name = sanitize_text_field($_POST['term_name']);
        if (!empty($term_name)) {
            $res = wp_insert_term($term_name, $tax);
            if (is_wp_error($res)) {
                echo '<div class="error"><p>Lỗi: ' . $res->get_error_message() . '</p></div>';
            } else {
                echo '<div class="updated"><p>Đã thêm mục "' . $term_name . '" thành công!</p></div>';
            }
        }
    }

    // Xử lý xóa mục con
    if (isset($_GET['delete_term']) && isset($_GET['tax'])) {
        wp_delete_term(intval($_GET['delete_term']), sanitize_text_field($_GET['tax']));
        echo '<div class="updated"><p>Đã xóa mục thành công!</p></div>';
    }

    $tax_list = get_option('cs_custom_taxonomies', "");
    $tax_lines = array_filter(explode("\n", $tax_list));
    ?>

    <style>
        .cs-admin-wrap {
            margin: 20px 20px 0 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }

        .cs-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .cs-header i {
            font-size: 32px;
            color: #b58e3e;
        }

        .cs-header h1 {
            margin: 0;
            font-weight: 800;
            font-size: 28px;
            color: #1d2327;
        }

        .cs-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e5e5;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .cs-card-header {
            background: #f9f9f9;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cs-card-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1d2327;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cs-card-body {
            padding: 25px;
        }

        .cs-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cs-table th {
            text-align: left;
            padding: 12px 15px;
            background: #f0f0f1;
            color: #50575e;
            font-weight: 600;
            font-size: 13px;
        }

        .cs-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f1;
            vertical-align: middle;
        }

        .cs-input {
            width: 100%;
            border: 1px solid #ccd0d4;
            padding: 8px 12px;
            border-radius: 6px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        }

        .cs-input:focus {
            border-color: #b58e3e;
            box-shadow: 0 0 0 1px #b58e3e;
            outline: none;
        }

        .cs-btn {
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .cs-btn-primary {
            background: #b58e3e;
            color: #fff;
        }

        .cs-btn-primary:hover {
            background: #967633;
        }

        .cs-btn-add {
            background: #2271b1;
            color: #fff;
            margin-top: 15px;
        }

        .cs-btn-danger {
            background: #d63638;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
        }

        .cs-badge {
            background: #e7e7e7;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: #646970;
        }

        .cs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 25px;
        }
    </style>

    <div class="cs-admin-wrap">
        <div class="cs-header">
            <span class="dashicons dashicons-filter"></span>
            <h1>Hệ thống Quản lý Bộ lọc Du lịch</h1>
        </div>

        <div class="cs-card">
            <div class="cs-card-header">
                <h2>1. Định nghĩa các loại Bộ lọc</h2>
                <button type="button" class="cs-btn cs-btn-add" id="cs-add-tax-row" style="margin:0">+ Thêm bộ lọc</button>
            </div>
            <div class="cs-card-body">
                <form method="post">
                    <table class="cs-table" id="cs-tax-table">
                        <thead>
                            <tr>
                                <th>Tên hiển thị (Vd: Khu vực)</th>
                                <th>Slug (Hệ thống - Tự sinh)</th>
                                <th width="100">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tax_lines as $line):
                                $parts = explode('|', $line);
                                if (count($parts) < 2)
                                    continue;
                                ?>
                                <tr>
                                    <td><input type="text" name="tax_labels[]" value="<?php echo esc_attr(trim($parts[0])); ?>"
                                            class="cs-input cs-label-input" placeholder="Ví dụ: Khu vực"></td>
                                    <td><input type="text" name="tax_slugs[]" value="<?php echo esc_attr(trim($parts[1])); ?>"
                                            class="cs-input cs-slug-input" placeholder="khu-vuc" data-auto="false"></td>
                                    <td style="text-align: center;"><button type="button"
                                            class="cs-btn cs-btn-danger cs-remove-row">Xóa</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px;">
                        <button type="submit" name="cs_save_taxonomies" class="cs-btn cs-btn-primary">
                            <span class="dashicons dashicons-saved"></span> LƯU CẤU HÌNH BỘ LỌC
                        </button>
                        <p style="color: #666; font-style: italic; margin-top: 10px;">* Lưu ý: Sau khi thêm bộ lọc mới, bạn
                            hãy vào <strong>Cài đặt -> Đường dẫn tĩnh</strong> và bấm Lưu 2 lần.</p>
                    </div>
                </form>
            </div>
        </div>

        <div class="cs-header" style="margin-top: 50px;">
            <span class="dashicons dashicons-category"></span>
            <h1>2. Quản lý nội dung các Bộ lọc</h1>
        </div>

        <div class="cs-grid">
            <?php foreach ($tax_lines as $line):
                $parts = explode('|', $line);
                if (count($parts) < 2)
                    continue;
                $label = trim($parts[0]);
                $slug = trim($parts[1]);
                $terms = get_terms(array('taxonomy' => $slug, 'hide_empty' => false));
                ?>
                <div class="cs-card">
                    <div class="cs-card-header">
                        <h2>Bộ lọc: <?php echo esc_html($label); ?></h2>
                        <span class="cs-badge"><?php echo count($terms); ?> mục</span>
                    </div>
                    <div class="cs-card-body">
                        <form method="post"
                            style="display: flex; gap: 10px; margin-bottom: 20px; background: #fcfcfc; padding: 15px; border-radius: 8px; border: 1px dashed #ddd;">
                            <input type="hidden" name="target_tax" value="<?php echo esc_attr($slug); ?>">
                            <input type="text" name="term_name" placeholder="Tên mục con mới..." required class="cs-input">
                            <button type="submit" name="cs_add_term" class="cs-btn cs-btn-primary"
                                style="white-space: nowrap;">+ Thêm</button>
                        </form>

                        <table class="cs-table">
                            <thead>
                                <tr>
                                    <th>Tên mục con</th>
                                    <th width="80">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($terms) && !is_wp_error($terms)):
                                    foreach ($terms as $t): ?>
                                        <tr>
                                            <td>
                                                <div style="font-weight: 600; color: #1d2327;"><?php echo esc_html($t->name); ?></div>
                                                <div style="font-size: 11px; color: #a7aaad;">ID: #<?php echo $t->term_id; ?> | Bài
                                                    viết: <?php echo $t->count; ?></div>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="<?php echo admin_url('edit.php?page=cs-tax-manager&delete_term=' . $t->term_id . '&tax=' . $slug); ?>"
                                                    class="cs-btn cs-btn-danger" onclick="return confirm('Xóa mục này?')">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            function slugify(str) {
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                str = str.replace(/[^a-z0-9 -]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-");
                return str;
            }

            $(document).on('keyup', '.cs-label-input', function () {
                var label = $(this).val();
                var slugInput = $(this).closest('tr').find('.cs-slug-input');
                if (slugInput.data('auto') !== false) {
                    slugInput.val(slugify(label));
                }
            });

            $(document).on('change', '.cs-slug-input', function () { $(this).data('auto', false); });

            $('#cs-add-tax-row').on('click', function () {
                var newRow = `<tr>
                <td><input type="text" name="tax_labels[]" class="cs-input cs-label-input" placeholder="Tên hiển thị..."></td>
                <td><input type="text" name="tax_slugs[]" class="cs-input cs-slug-input" placeholder="Slug..."></td>
                <td style="text-align: center;"><button type="button" class="cs-btn cs-btn-danger cs-remove-row">Xóa</button></td>
            </tr>`;
                $('#cs-tax-table tbody').append(newRow);
            });

            $(document).on('click', '.cs-remove-row', function () { $(this).closest('tr').remove(); });
        });
    </script>
    <?php
}

/* =============================================
   2. ĐĂNG KÝ TAXONOMY TỰ ĐỘNG (DYNAMIC)
   ============================================= */

add_action('init', 'cs_register_dynamic_taxonomies');
function cs_register_dynamic_taxonomies()
{
    $tax_list = get_option('cs_custom_taxonomies', "");
    if (empty($tax_list))
        return;
    $lines = array_filter(explode("\n", $tax_list));
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) >= 2) {
            $name = trim($parts[0]);
            $slug = sanitize_title(trim($parts[1]));
            if (empty($slug))
                continue;
            register_taxonomy($slug, array('post'), array(
                'hierarchical' => true,
                'labels' => array('name' => $name, 'singular_name' => $name, 'menu_name' => $name),
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug),
                'show_in_rest' => true // Quan trọng để hỗ trợ REST API
            ));
        }
    }
}

/* =============================================
   3. SIDEBAR UI (CHỦ ĐỀ & TAXONOMIES)
   ============================================= */

function cs_render_filter_sidebar()
{
    $taxonomies = get_object_taxonomies('post', 'objects');
    unset($taxonomies['post_tag'], $taxonomies['post_format']);

    foreach ($taxonomies as $tax_slug => $tax_obj):
        if (is_category() && $tax_slug === 'category')
            continue;
        if (is_tax() && get_queried_object()->taxonomy === $tax_slug)
            continue;

        $terms = get_terms(array('taxonomy' => $tax_slug, 'hide_empty' => false));
        if (empty($terms) || is_wp_error($terms))
            continue;
        ?>
        <div class="cs-filter-group">
            <h4 class="cs-filter-title">
                <?php echo ($tax_slug === 'category') ? 'Danh mục' : esc_html($tax_obj->label); ?>
            </h4>
            <div class="cs-filter-items">
                <label class="cs-checkbox-label">
                    <input type="checkbox" class="cs-filter-checkbox cs-filter-all" value="" data-tax="<?php echo $tax_slug; ?>"
                        checked>
                    <span class="cs-checkmark"></span>
                    <span class="cs-term-name">Tất cả</span>
                </label>
                <?php foreach ($terms as $term):
                    $is_checked = false;
                    if (isset($_GET[$tax_slug])) {
                        $url_terms = explode(',', $_GET[$tax_slug]);
                        if (in_array($term->slug, $url_terms))
                            $is_checked = true;
                    }
                    ?>
                    <label class="cs-checkbox-label">
                        <input type="checkbox" class="cs-filter-checkbox" value="<?php echo $term->slug; ?>"
                            data-tax="<?php echo $tax_slug; ?>" <?php checked($is_checked); ?>>
                        <span class="cs-checkmark"></span>
                        <span class="cs-term-name"><?php echo $term->name; ?></span>
                        <span class="cs-term-count">(<?php echo $term->count; ?>)</span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach;
}

/* =============================================
   4. REST API FILTER HANDLER
   ============================================= */

add_action('rest_api_init', function () {
    register_rest_route('cs/v1', '/filter', array(
        'methods' => 'POST',
        'callback' => 'cs_rest_filter_posts',
        'permission_callback' => '__return_true',
    ));
});

function cs_rest_filter_posts($request)
{
    $params = $request->get_params();
    $filters = isset($params['filters']) ? $params['filters'] : array();
    $paged = isset($params['paged']) ? intval($params['paged']) : 1;
    $current_id = isset($params['current_id']) ? intval($params['current_id']) : 0;
    $current_tax = isset($params['current_tax']) ? sanitize_text_field($params['current_tax']) : '';
    $current_url = isset($params['current_url']) ? esc_url_raw($params['current_url']) : '';
    $search_query = isset($params['search_query']) ? sanitize_text_field($params['search_query']) : '';

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $paged,
        'post_status' => 'publish',
        's' => $search_query
    );

    $tax_query = array('relation' => 'AND');
    if ($current_id > 0 && !empty($current_tax)) {
        $tax_query[] = array('taxonomy' => $current_tax, 'field' => 'term_id', 'terms' => $current_id);
    }
    if (!empty($filters)) {
        foreach ($filters as $tax => $terms) {
            if (!empty($terms))
                $tax_query[] = array('taxonomy' => $tax, 'field' => 'slug', 'terms' => $terms, 'operator' => 'IN');
        }
    }
    if (count($tax_query) > 1)
        $args['tax_query'] = $tax_query;

    $query = new WP_Query($args);
    $html = '';
    $pagination = '';

    if ($query->have_posts()):
        ob_start();
        echo '<div class="cs-post-grid">';
        while ($query->have_posts()):
            $query->the_post();
            get_template_part('template-parts/post/content', 'luxury');
        endwhile;
        echo '</div>';
        $html = ob_get_clean();

        ob_start();
        $big = 999999999;
        $base_url = preg_replace('#/page/\d+/#', '', $current_url);
        $base_url = remove_query_arg('paged', $base_url);
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', user_trailingslashit(trailingslashit($base_url) . 'page/' . $big)),
            'format' => '',
            'total' => $query->max_num_pages,
            'current' => $paged,
            'prev_text' => '<i class="icon-angle-left"></i>',
            'next_text' => '<i class="icon-angle-right"></i>',
            'type' => 'plain'
        ));
        $pagination = ob_get_clean();
    else:
        ob_start();
        ?>
        <div class="cs-no-results-template">
            <div class="cs-no-results-icon"><i class="icon-search"></i></div>
            <h4 class="cs-font-serif">KHÔNG TÌM THẤY BÀI VIẾT PHÙ HỢP</h4>
        </div>
        <?php
        $html = ob_get_clean();
    endif;
    wp_reset_postdata();
    return new WP_REST_Response(array('html' => $html, 'pagination' => $pagination, 'found' => $query->found_posts), 200);
}

/* =============================================
   5. ENQUEUE SCRIPTS
   ============================================= */

add_action('wp_enqueue_scripts', 'cs_enqueue_filter_script', 20);
function cs_enqueue_filter_script()
{
    if (is_archive() || is_home() || is_category() || is_tax() || is_tag() || is_search() || is_page_template('template-all-posts.php')) {
        wp_enqueue_style('cs-filter-css', get_stylesheet_directory_uri() . '/assets/css/cs-filter.css', array(), '1.9');
        wp_enqueue_script('cs-filter-js', get_stylesheet_directory_uri() . '/assets/js/cs-filter.js', array('jquery'), '1.9', true);
        $current_obj_id = 0;
        if (is_category() || is_tax() || is_tag())
            $current_obj_id = get_queried_object_id();
        wp_localize_script('cs-filter-js', 'cs_ajax_obj', array(
            'rest_url' => esc_url_raw(rest_url('cs/v1/filter')),
            'current_id' => $current_obj_id,
            'current_tax' => is_tax() ? get_queried_object()->taxonomy : (is_category() ? 'category' : (is_tag() ? 'post_tag' : '')),
            'search_query' => get_search_query()
        ));
    }
}
