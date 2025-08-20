// Wizard Initialization
$("#wizard").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "none",
    titleTemplate: '<span class="bd-wizard-step-indicator"></span><h6 class="bd-wizard-step-title">#title#</h6>',
    onStepChanging: function (event, currentIndex, newIndex) {
        // Perform validation on step change
        let isValid = validateStep(currentIndex);

        if (!isValid) {
            // Show SweetAlert if validation fails
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select your preferences from the above/below options.',
            });
            return false;
        }

        return true;
    },
    onStepChanged: function (e, index, previndex) {
        let steps = document.querySelector('#wizard .steps');

        if (index === 1) {
            steps.classList.add('second-step-active');
            steps.classList.remove('third-step-active', 'last-step-active');
        } else if (index === 2) {
            steps.classList.add('third-step-active');
            steps.classList.remove('second-step-active', 'last-step-active');
        } else if (index === 3) {
            steps.classList.add('last-step-active');
            steps.classList.remove('second-step-active', 'third-step-active');
        } else {
            steps.classList.remove('second-step-active', 'third-step-active', 'last-step-active');
        }
    },
    onFinished: function () {
        _sendTo_search_page();
    },
    labels: {
        previous: "Back",
    }
});

// Updated form validation function
function validateStep(stepIndex) {
    let isValid = true;

    // Check if any radio button within the current section is selected
    $(`#wizard section:eq(${stepIndex})`).find("input[type='radio'][name='laptop_budget_id']").each(function () {
        if (!$("input[name='laptop_budget_id']:checked").length) {
            isValid = false; // No radio button is selected
        }
    });
    
    return isValid;
}

$('input[name="laptop_budget_id"]').on('change', function() {
    $('.preloader').show();

    // Get the selected value
    let selectedBudgetId = $(this).val();

    // Send AJAX request
    $.ajax({
        url: '/get-laptop-by-finder',
        type: 'POST',
        data: {
            budget_id: selectedBudgetId,
        },
        success: function(response) {
            $('#total_available_products').val(response.counter);
            $('#go-to-laptop-search').html("Show Matched Laptops (" + response.counter + ")");
            $('.preloader').hide();
        },
        error: function(xhr, status, error) {
            $('.preloader').hide();
        }
    });
});

$(".custom-checkbox-group input[name='purpose']").on('change', function() {
    // Create an array to store selected purposes
    let selectedPurposes = [];

    // Loop through each checked checkbox and add its value to the array
    $(".custom-checkbox-group input[type='checkbox']:checked").each(function() {
        selectedPurposes.push($(this).val());
    });

    // Make an AJAX request with the selected purposes
    $.ajax({
        url: '/get-laptop-by-finder', // Replace with your endpoint URL
        method: 'POST',
        data: {
            purposes: selectedPurposes,
        },
        beforeSend: function() {
            $('.preloader').show();
        },
        success: function(response) {
            $('#go-to-laptop-search').html("Show Matched Laptops (" + response.counter + ")");
        },
        error: function(xhr, status, error) {
            
        },
        complete: function() {
            $('.preloader').hide();
        }
    });
});

$(".custom-radio-group input[name='screen_size']").on('change', function() {
    // Get the selected screen size value
    let selectedScreenSize = $(this).val();

    // Make sure a value is selected before making an AJAX request
    if (selectedScreenSize) {
        // Make an AJAX request with the selected screen size
        $.ajax({
            url: '/get-laptop-by-finder', // Replace with your endpoint URL
            method: 'POST',
            data: {
                screen_size: selectedScreenSize,
            },
            beforeSend: function() {
                // Show a loader before sending the request
                $('.preloader').show();
            },
            success: function(response) {
                $('#total_available_products').val(response.counter);
                $('#go-to-laptop-search').html("Show Matched Laptops (" + response.counter + ")");
            },
            error: function(xhr, status, error) {
               
            },
            complete: function() {
                $('.preloader').hide();
            }
        });
    }
});

$(".custom-radio-group input[name='portability']").on('change', function() {
    // Get the selected screen size value
    let selectedPortability= $(this).val();

    // Make sure a value is selected before making an AJAX request
    if (selectedPortability) {
        // Make an AJAX request with the selected screen size
        $.ajax({
            url: '/get-laptop-by-finder', // Replace with your endpoint URL
            method: 'POST',
            data: {
                portability: selectedPortability,
            },
            beforeSend: function() {
                // Show a loader before sending the request
                $('.preloader').show();
            },
            success: function(response) {
                $('#total_available_products').val(response.counter);
                $('#go-to-laptop-search').html("Show Matched Laptops (" + response.counter + ")");
            },
            error: function(xhr, status, error) {
               
            },
            complete: function() {
                $('.preloader').hide();
            }
        });
    }
});

$(".custom-checkbox-group input[name='features']").on('change', function() {
    // Create an array to store selected purposes
    let selectedFeatures = [];

    // Loop through each checked checkbox and add its value to the array
    $(".custom-checkbox-group input[name='features']:checked").each(function() {
        selectedFeatures.push($(this).val());
    });

    // Make an AJAX request with the selected purposes
    $.ajax({
        url: '/get-laptop-by-finder', // Replace with your endpoint URL
        method: 'POST',
        data: {
            features: selectedFeatures,
        },
        beforeSend: function() {
            $('.preloader').show();
        },
        success: function(response) {
            $('#total_available_products').val(response.counter);
            $('#go-to-laptop-search').html("Show Matched Laptops (" + response.counter + ")");
        },
        error: function(xhr, status, error) {
            
        },
        complete: function() {
            $('.preloader').hide();
        }
    });
});

$(document).on('click', '#clear-laptop-search', function() {
    $.ajax({
        url: '/clear-laptop-search',
        method: 'POST',
        beforeSend: function() {
            $('.preloader').show();
        },
        success: function(response) {
            if(response.status) {
                toastr.success(response.message);

                setTimeout(() => {
                    window.location.href="";
                }, 2000);
            } else {
                toastr.error(response.message);
            }

        },
        complete: function() {
            $('.preloader').hide();
        }
    });
});

$(document).on('click', '#go-to-laptop-search', function() {
    _sendTo_search_page();
})

var _sendTo_search_page = function () {
    let counter = $('#total_available_products').val();
    if(parseInt(counter) == 0) {
        Swal.fire({
            icon: 'error',
            title: 'No Laptop Found',
            text: 'No Laptop found for your searching criteria.',
        });

        return false;
    }

    window.location.href="search?search=laptop&terms=laptop_finder";
}