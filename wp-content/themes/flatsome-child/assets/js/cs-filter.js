var cs_filter_posts; // Define globally

jQuery(document).ready(function ($) {
    console.log('CS Filter System Initiated (REST API Version)');

    $(document).on('click', '.cs-filter-checkbox', function (e) {
        var $this = $(this);
        
        setTimeout(function() {
            handle_checkbox_logic($this);
            
            // Chỉ tự động lọc nếu KHÔNG phải màn hình mobile
            if ($(window).width() >= 992) {
                cs_filter_posts(1);
            }
        }, 50);
    });

    function handle_checkbox_logic($this) {
        var $group = $this.closest('.cs-filter-items');
        var $allCheckbox = $group.find('.cs-filter-all');

        if ($this.hasClass('cs-filter-all')) {
            if ($this.is(':checked')) {
                $group.find('.cs-filter-checkbox').not('.cs-filter-all').prop('checked', false);
            } else {
                if ($group.find('.cs-filter-checkbox:checked').length === 0) {
                    $this.prop('checked', true);
                }
            }
        } else {
            if ($this.is(':checked')) {
                $allCheckbox.prop('checked', false);
            } else {
                if ($group.find('.cs-filter-checkbox:checked').length === 0) {
                    $allCheckbox.prop('checked', true);
                }
            }
        }
    }

    // Xử lý phân trang
    $(document).on('click', '.cs-pagination a', function (e) {
        e.preventDefault();
        var paged = 1;
        var text = $(this).text();
        
        if (!isNaN(text) && text !== "") {
            paged = parseInt(text);
        } else {
            var current = parseInt($('.cs-pagination .current').text());
            if ($(this).hasClass('prev') || $(this).find('.icon-angle-left').length) paged = current - 1;
            if ($(this).hasClass('next') || $(this).find('.icon-angle-right').length) paged = current + 1;
        }
        
        cs_filter_posts(paged);
        $('html, body').animate({ scrollTop: $('#cs-ajax-results').offset().top - 120 }, 500);
    });

    cs_filter_posts = function(paged) {
        var filters = {};
        $('.cs-filter-checkbox:checked').each(function () {
            var tax = $(this).data('tax');
            var val = $(this).val();
            if (val !== "") { 
                if (!filters[tax]) filters[tax] = [];
                filters[tax].push(val);
            }
        });

        $('#cs-ajax-results').addClass('is-loading');

        // GỌI REST API (Senior Way)
        $.ajax({
            url: cs_ajax_obj.rest_url,
            type: 'POST',
            data: {
                filters: filters,
                paged: paged,
                current_id: cs_ajax_obj.current_id,
                current_tax: cs_ajax_obj.current_tax,
                search_query: cs_ajax_obj.search_query,
                current_url: window.location.href
            },
            success: function (res) {
                var final_html = res.html;
                if (res.pagination) {
                    final_html += '<div class="cs-pagination">' + res.pagination + '</div>';
                }
                $('#cs-ajax-results').html(final_html).removeClass('is-loading');
                update_url(filters, paged);
            },
            error: function(xhr, status, error) {
                $('#cs-ajax-results').removeClass('is-loading');
                console.error('REST API Error:', status, error);
            }
        });
    }

    function update_url(filters, paged) {
        var baseUrl = window.location.pathname.replace(/\/page\/\d+\//g, '/').replace(/\/+$/, '');
        var newPath = baseUrl;
        
        if (paged > 1) {
            newPath += '/page/' + paged + '/';
        } else {
            newPath += '/';
        }

        var query = "";
        if (cs_ajax_obj.search_query) {
            query += "?s=" + encodeURIComponent(cs_ajax_obj.search_query);
        }

        for (var tax in filters) {
            if (filters[tax].length > 0) {
                var separator = (query === "") ? "?" : "&";
                query += separator + tax + "=" + encodeURIComponent(filters[tax].join(','));
            }
        }
        
        var new_url = newPath + query;
        window.history.pushState({ path: new_url }, '', new_url);
    }
});
