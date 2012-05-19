(function(window, document, $) {

    $(function() {

        var $register = $('#register').modal('show')
            .on('hidden', function(e) {
                document.location = '/';
            })
            .on('click', '.btn-primary', function(e) {
                e.preventDefault();
                $register.find('form').submit();
            });

        $('form').ajaxForm({
            data: {
                format: 'json'
            },
            dataType: 'json',
            success: function(data, state, xhr, form) {
                if (data.error) {
                    form
                        .find('.control-group.error').removeClass('error').end()
                        .find('.help-inline').remove().end();
                    $.each(data.error, function(key, value) {
                        var $input = form.find('input[name=' + key + ']')
                            .closest('.control-group').addClass('error').end();
                        $.each(value, function(validator, message) {
                            $input.after($('<p>').addClass('help-inline').html(message));
                        });
                    });
                    return false;
                }
                document.location = '/admin';
            }
        });

    });

})(this, this.document, this.jQuery);