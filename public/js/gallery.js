(function(window, document, $) {

    var vimeoEmbedScript = doT.template('<div style="margin-bottom: 80px;"><iframe src="http://player.vimeo.com/video/{{=it.vimeo_id}}?api=1" width="{{=it.width}}" height="{{=it.height}}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>');

    $.fn.gallery = function(o) {
        var options = $.extend($.fn.gallery.defaults, o);
        return this.each(function () {
            var timer;

            function move(pos) {
                $images.animate({left: -cellSize * pos});
            }

            function show(a) {
                var $a = $(a), original = $a.data('original'),
                    vimeoRegExp = /https?:\/\/vimeo\.com\/(\d+)/ig,
                    maxWidth = $container.width(), maxHeight = $container.height();
                if (original) {
                    switch ($a.data('type')) {
                        case 'image':
                            var $containerImage = $('<img>', {src: original});
                            if ($a.data('width')) {
                                $containerImage.attr('width', Math.min($a.data('width'), maxWidth));
                            }
                            $containerImage.on('load', function() {
                                $container.find('.container').html($containerImage);
                            });
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
                                var $iframe = $(vimeoEmbedScript($.extend({width: w, height: h || w * 9 / 16}, o)));
                                $container.find('.container').html($iframe);
                                if (options.slideshow) {
                                    var player = $f($iframe.find('iframe')[0]);
                                    player.addEvent('ready', function() {
                                        player.addEvent('pause', resetTick);
                                        player.addEvent('finish', initTick);
                                        player.addEvent('play', resetTick);
                                    });
                                }
                            }
                    }
                    $('.shadow-text')
                        .find('h1').html($a.attr('title')).wrap($('<a>', {href: $a.attr('href')})).end()
                        .find('span').html($a.find('img').attr('alt')).end();
                }
            }

            function tick() {
                ++currentPosition;
                if (currentPosition == imagesCount) {
                    currentPosition = 0;
                }
                if (deltaImCnt > 0) {
                    move(currentPosition > deltaImCnt ? deltaImCnt : currentPosition);
                }
                show($images.find('a')[currentPosition]);
            }

            function resetTick() {
                if (timer) {
                    clearInterval(timer);
                }
            }

            function initTick() {
                resetTick();
                timer = setInterval(tick, options.interval);
            }

            var $container = $(options.container),
                $images = $('.images'),
                imagesCount = $images.find('img').length,
                cellSize = $images.find('li:first').width(),
                currentPosition = 0,
                visibleCount = cellSize ? Math.floor($(this).find('.slider-wrapper').width() / cellSize) : 0,
                deltaImCnt = imagesCount - visibleCount;
            if ($container.length > 0) {
                $(this)
                    .on('click', '.next', function(e) {
                        e.preventDefault();
                        ++currentPosition;
                        if (deltaImCnt > 0) {
                            move(currentPosition > deltaImCnt ? deltaImCnt : currentPosition);
                        }
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
                        currentPosition = $(this).closest('li').index();
                        show(this);
                        if (options.slideshow) {
                            initTick();
                        }
                    });
                if (!isNaN(parseInt(options.show))) {
                    currentPosition = parseInt(options.show);
                    if (currentPosition < 0 || currentPosition >= imagesCount) {
                        currentPosition = 0;
                    }
                    move(currentPosition);
                    show($images.find('a')[currentPosition]);
                }
                if (options.slideshow) {
                    initTick();
                }
            }
        });
    };

    $.fn.gallery.defaults = {
        show: 0,
        slideshow: true,
        interval: 10000
    };

})(this, this.document, this.jQuery);