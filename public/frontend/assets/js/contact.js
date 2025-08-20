
var _contactFormValidation = function () {
    if ($('#contact_form').length > 0) {
        $('#contact_form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('#contact_form').on('submit', function (e) {
        e.preventDefault();

        $('#submit').hide();
        $('#submitting').show();

        $(".ajax_error").remove();

        var submit_url = $('#contact_form').attr('action');
        var formData = new FormData($("#contact_form")[0]);
        
        //Start Ajax
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

                    if (data.errors) {
                        for (const [key, message] of Object.entries(data.errors)) {
                            toastr.error(message);
                        }
                    }
                } else {
                    toastr.success(data.message);

                    $('#contact_form')[0].reset();
                    if (data.load) {
                        setTimeout(function () {

                            window.location.href = "";
                        }, 500);
                    }
                }

                $('#contact_submit').show();
                $('#contact_submitting').hide();
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

                $('#contact_submit').show();
                $('#contact_submitting').hide();
            }
        });
    });
};

$(document).ready(function() {
    
    $('#contact_submitting').hide();
    $('#contact_submit').show();
    
    _contactFormValidation();
})