$(document).ready(function() {
    _formValidation();
    $('#submit').show();
    $('#submitting').hide();

    $('.otp').on('input', function() {
        let value = $(this).val();
        value = value.replace(/\D/g, '');
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        $(this).val(value);
    });

    // Countdown Timer
    const countdownTime = $('#countdown_time').val();
    const targetTime = new Date(countdownTime).getTime();

    const timerInterval = setInterval(function() {
        const now = new Date().getTime();
        const distance = targetTime - now;
  
        if (distance <= 0) {
          clearInterval(timerInterval);
          $('#timeout-time').hide();
          $('#resend-link').addClass('active');
        } else {
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000);
          $('#countdown-timer').text(`${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
        }
    }, 1000);
  
});

var _formValidation = function () {
    if ($('#register-form').length > 0) {
        $('#register-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('#register-form').on('submit', function (e) {
        e.preventDefault();

        $('#submit').hide();
        $('#submitting').show();

        $(".ajax_error").remove();

        var submit_url = $('#register-form').attr('action');
        var formData = new FormData($("#register-form")[0]);

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
                        for (const [key, messages] of Object.entries(data.validator)) {
                            toastr.error(messages);
                        }
                    } else {
                        toastr.error(data.message);
                    }
                } else {
                    toastr.success(data.message);
                    
                    $('#register-form')[0].reset();
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
                        // $('#' + first_item).after('<div class="ajax_error" style="color:red">' + value + '</div');
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