(function(window, document, $) {
    $.fn.partial = function(params, cb) {
        switch (arguments.length) {
            case 1:
                if (typeof params == 'function') {
                    cb = params;
                    params = {};
                }
                break;
        }
        return this.each(function() {
            var url = $(this).data('url');
            var o = $.extend({}, params || {});
            $(this).load(url, o, (cb && typeof cb == 'function')?cb:$.noop);
        });
    };
})(this, this.document, this.jQuery);