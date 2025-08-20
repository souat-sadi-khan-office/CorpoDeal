$(document).ready(function() {
    _formValidation();

    $('#submit').show();
    $('#submitting').hide();
})

var _formValidation = function () {
    if ($('#reset-password-form').length > 0) {
        $('#reset-password-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('#reset-password-form').on('submit', function (e) {
        e.preventDefault();

        $('#submit').hide();
        $('#submitting').show();

        $(".ajax_error").remove();

        var submit_url = $('#reset-password-form').attr('action');
        var formData = new FormData($("#reset-password-form")[0]);

        $.ajax({
            url: submit_url,
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                if (!data.status) {
                    if (data.validator) {
                        for (const [key, messages] of Object.entries(data.message)) {
                            messages.forEach(message => {
                                toastr.error(message);
                            });
                        }
                    } else {
                        toastr.error(data.message);
                    }
                } else {
                    toastr.success(data.message);
                    
                    $('#reset-password-form')[0].reset();
                    if (data.goto) {
                        setTimeout(function () {

                            window.location.href = data.goto;
                        }, 500);
                    }
                }

                $('#submit').show();
                $('#submitting').hide();
            },
            error: function (data) {
                var jsonValue = $.parseJSON(data.responseText);
                const errors = jsonValue.errors;
                if (errors) {
                    var i = 0;
                    $.each(errors, function (key, value) {
                        const first_item = Object.keys(errors)[i]
                        const message = errors[first_item][0];
                        if ($('#' + first_item).length > 0) {
                            $('#' + first_item).parsley().removeError('required', {
                                updateClass: true
                            });
                            $('#' + first_item).parsley().addError('required', {
                                message: value,
                                updateClass: true
                            });
                        }
                        toastr.error(value);
                        i++;

                    });
                } else {
                    toastr.warning(jsonValue.message);
                }

                $('#submit').show();
                $('#submitting').hide();
            }
        });
    });
};