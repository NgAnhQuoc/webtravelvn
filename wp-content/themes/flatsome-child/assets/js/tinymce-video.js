(function() {
    tinymce.create('tinymce.plugins.cs_video_plugin', {
        init : function(ed, url) {
            // Thêm nút bấm vào thanh công cụ
            ed.addButton('cs_video_button', {
                title : 'Chèn Video Premium',
                icon: 'media',
                onclick : function() {
                    var video_url = prompt("Dán link YouTube vào đây:", "");
                    if (video_url !== null && video_url !== '') {
                        ed.execCommand('mceInsertContent', false, '[cs_video url="' + video_url + '"]');
                    }
                }
            });

            ed.addButton('cs_quote_button', {
                title : 'Chèn Trích dẫn Premium',
                icon: 'blockquote',
                onclick : function() {
                    var quote_text = prompt("Nhập nội dung trích dẫn:", "");
                    if (quote_text !== null && quote_text !== '') {
                        ed.execCommand('mceInsertContent', false, '[cs_quote]' + quote_text + '[/cs_quote]');
                    }
                }
            });
        },

        createControl : function(n, cm) {
            return null;
        },
    });
    // Đăng ký plugin
    tinymce.PluginManager.add('cs_video_plugin', tinymce.plugins.cs_video_plugin);
})();
