(function(window, document, $) {

    var vimeoEmbedScript = doT.template('<iframe src="http://player.vimeo.com/video/{{=it.vimeo_id}}?autoplay=1" width="{{=it.width}}" height="{{=it.height}}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');

    $.fn.gallery = function(o) {
        var options = $.extend($.fn.gallery.defaults, o);
        return this.each(function () {
            function move(pos) {
                $images.animate({left: -cellSize * pos});
            }

            function show(a) {
                var $a = $(a), original = $a.data('original'),
                    vimeoRegExp = /https?\:\/\/vimeo\.com\/(\d+)/ig,
                    maxWidth = $container.width(), maxHeight = $container.height();
                if (original) {
                    switch ($a.data('type')) {
                        case 'image':
                            var $containerImage = $('<img>', {src: original});
                            if ($a.data('width')) {
                                $containerImage.attr('width', Math.min($a.data('width'), maxWidth));
                            }
                            $container.find('.container').html($containerImage);
                            break;
                        case 'vimeo':
                            var flag = original.match(vimeoRegExp);
                            if(flag) {
                                var o = {vimeo_id: RegExp.$1};
                                var w = $a.data('width'), h = $a.data('height');
                                if (w > 0 && h > 0) {
                                    if (w > maxWidth) {
                                        var ratio = w / maxWidth;
                                        h /= ratio;
                                        w /= ratio;
                                    }
                                }
                                $container.find('.container').html(vimeoEmbedScript($.extend({width: w, height: h || w * 9 / 16}, o)));
                            }
                    }

                }
            }

            var $container = $(options.container),
                $images = $('.images'),
                imagesCount = $images.find('img').length,
                cellSize = $images.find('li:first').width(),
                currentPosition = 0,
                visibleCount = cellSize ? Math.floor($(this).find('.slider-wrapper').width() / cellSize) : 0,
                containerWidth = $container.width();
            if ($container.length > 0) {
                $(this)
                    .on('click', '.next', function(e) {
                        e.preventDefault();
                        ++currentPosition;
                        if (currentPosition + visibleCount > imagesCount) {
                            currentPosition = imagesCount - visibleCount;
                        }
                        move(currentPosition);
                    })
                    .on('click', '.prev', function(e) {
                        e.preventDefault();
                        --currentPosition;
                        if (currentPosition < 0) {
                            currentPosition = 0;
                        }
                        move(currentPosition);
                    })
                    .on('click', '.images a', function(e) {
                        e.preventDefault();
                        show(this);
                    });
                if (!isNaN(parseInt(options.show))) {
                    var pos = parseInt(options.show);
                    if (pos < 0 || pos >= imagesCount) {
                        pos = 0;
                    }
                    move(pos);
                    show($images.find('a')[pos]);
                }
            }
        });
    };

    $.fn.gallery.defaults = {
        show: 0
    };

})(this, this.document, this.jQuery);