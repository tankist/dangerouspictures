(function($) {

    "use strict"

    $.fn.autoModal = function() {
        var minWidth = $(this).width();
        return $(this).css({
            width: 'auto',
            'min-width': minWidth,
            'margin-left': function () {
                return -($(this).width() / 2);
            }
        });
    }
})(window.jQuery);