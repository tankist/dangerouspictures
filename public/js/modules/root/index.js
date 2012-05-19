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
            beforeSubmit: function() {
                $('.alert').remove();
            },
            success: function(data, state, xhr, form) {
                if (data.error) {
                    form
                        .find('.control-group.error').removeClass('error').end()
                        .find('.help-inline').remove().end();
                    if ($.isPlainObject(data.error())) {
                        $.each(data.error, function (key, value) {
                            var $input = form.find('input[name=' + key + ']')
                                .closest('.control-group').addClass('error').end();
                            $.each(value, function (validator, message) {
                                $input.after($('<p>').addClass('help-inline').html(message));
                            });
                        });
                    }
                    return false;
                }
                document.location = '/admin';
            },
            error: function(xhr) {
                var error = $.parseJSON(xhr.responseText);
                $register.find('form').before($('<div>').addClass('alert alert-error').html(error.message));
            }
        });

    });

})(this, this.document, this.jQuery);