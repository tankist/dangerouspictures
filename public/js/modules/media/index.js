(function(window, document, $) {

    $(function() {

        (function() {
            var $vimeo = $('.add.vimeo'),
                vimeoRegExp = /https?\:\/\/vimeo\.com\/(\d+)/ig,
                vimeo_id,
                previewTemplate = doT.template(document.getElementById('vimeoPreview').innerHTML);
            $vimeo.find('input[name=url]')
                .change(function(e) {
                    var flag = $(this).val().match(vimeoRegExp);
                    if(flag) {
                        vimeo_id = RegExp.$1;
                        $.ajax({
                            url: 'http://vimeo.com/api/v2/video/' + vimeo_id + '.json',
                            dataType: 'jsonp',
                            success: function(data) {
                                if (data.length > 0) {
                                    data = data.shift();
                                    $vimeo
                                        .find('.thumbnails img').attr('src', data.thumbnail_medium).end()
                                        .find('.thumbnails-container').show();
                                }
                            }
                        });
                    }
                })
                .blur(function(e) {
                    if($(this).val().match(vimeoRegExp) && vimeo_id != RegExp.$1) {
                        $(this).change();
                    }
                });
            $vimeo.find('.preview').click(function(e) {
                e.preventDefault();
                if (vimeo_id) {
                    $('#mediaModal')
                        .find('.modal-header h3').html('Video Preview').end()
                        .find('.modal-body').html(previewTemplate({vimeo_id: vimeo_id})).end()
                        .modal();
                }
            })
        })();

        $('.add a.cancel').click(function(e) {
            e.preventDefault();
            $(this).closest('.add').slideUp();
        });

        $('a[data-toggle="vimeo"],a[data-toggle="image"]').click(function(e) {
            e.preventDefault();
            var toggle = $(this).data('toggle'), $block = $('.add').filter('.' + toggle);
            if ($block.is(':hidden')) {
                $('.add:visible').trigger('hidden').slideUp();
                $block.trigger('shown').slideDown();
            }
        });

    });

})(this, this.document, this.jQuery);