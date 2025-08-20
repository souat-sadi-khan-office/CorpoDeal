$(document).ready(function() {
    _questionFormValidation();
    _reviewFormValidation();

    $('#submit_question_form').show();
    $('#submitting_question_form').hide();

    $('#submit_review_form').show();
    $('#submitting_review_form').hide();

    $(document).on('click', '.buy-now', function() {
        let id = $(this).data('id');
        $(this).attr('disabled', true);
        $(this).html('<i class="fas fa-spinner fa-spin fa-fw"></i> Loading')
        $.ajax({
            url: '/buy-now',
            method: 'POST',
            data: {
                slug: id 
            },
            dataType: 'json',
            success: function(data) {
                $('.buy-now').removeAttr('disabled');
                $('.buy-now').html('<i class="fas fa-shopping-bag"></i> Buy Now');
                
                if(!data.status) {
                    toastr.error(data.message);
                } else {
                    toastr.success(data.message);

                    if(data.goto) {
                        setTimeout(() => {
                            window.location.href=data.goto;
                        }, 2500);
                    }
                }
            }
        })
    });

    $('.zoom-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom mfp-img-mobile',

		// If you enable allowHTMLInTemplate - 
		// make sure your HTML attributes are sanitized if they can be created by a non-admin user
		allowHTMLInTemplate: true,
		image: {
			verticalFit: true,
			titleSrc: function(item) {
			}
		},

		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			opener: function(element) {
				return element.find('img');
			}
		}
		
	});
})

var _questionFormValidation = function () {
    if ($('#question-form').length > 0) {
        $('#question-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('#question-form').on('submit', function (e) {
        e.preventDefault();

        $('#submit_question_form').hide();
        $('#submitting_question_form').show();

        $(".ajax_error").remove();

        var submit_url = $('#question-form').attr('action');
        var formData = new FormData($("#question-form")[0]);

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
                    
                    $('#question-form')[0].reset();
                    if (data.load) {
                        setTimeout(function () {

                            window.location.href = "";
                        }, 500);
                    }
                }

                $('#submit_question_form').show();
                $('#submitting_question_form').hide();
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

                $('#submit_question_form').show();
                $('#submitting_question_form').hide();
            }
        });
    });
};

var _reviewFormValidation = function () {
    if ($('#review-form').length > 0) {
        $('#review-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('#review-form').on('submit', function (e) {
        e.preventDefault();

        $('#submit_review_form').hide();
        $('#submitting_review_form').show();

        $(".ajax_error").remove();

        var submit_url = $('#review-form').attr('action');
        var formData = new FormData($("#review-form")[0]);

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
                    
                    $('#review-form')[0].reset();
                    if (data.load) {
                        setTimeout(function () {

                            window.location.href = "";
                        }, 500);
                    }
                }

                $('#submit_review_form').show();
                $('#submitting_review_form').hide();
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

                $('#submit_review_form').show();
                $('#submitting_review_form').hide();
            }
        });
    });
};