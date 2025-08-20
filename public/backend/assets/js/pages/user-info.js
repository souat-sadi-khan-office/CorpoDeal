$(function() {
    _componentRemoteModalLoadAfterAjax();
    
    // load address data table
    // $('#user-address-table').DataTable({
    //     order: ([0, 'DESC'])
    // });

    $('#customer_id').select2({
        width: '100%',
        placeholder: 'Select Customer',
        ajax: {
            url: '/search/customers',
            method: 'POST',
            dataType: 'JSON',
            delay: 250,
            cache: true,
            data: function (data) {
                return {
                    searchTerm: data.term
                };
            },

            processResults: function (response) {
                return {
                    results: response
                };
            }
        }
    });

    $(document).on('change', '#customer_id', function() {
        let id = $(this).val();
        window.location.href="/admin/customer/view/"+id;
    })

    $(document).on('change', '#zone_id', function() {
        var zoneId = $(this).val();
        if (zoneId) {
            $.ajax({
                url: "/get-countries",
                type: "GET",
                data: { zone_id: zoneId },
                success: function(data) {
                    $('#country_id').empty();
                    $('#country_id').append('<option value="">Select Country</option>');
                    $.each(data, function(key, country) {
                        $('#country_id').append('<option value="'+ country.id +'">'+ country.name +'</option>');
                    });
                    $('#country_id').trigger('change');
                }
            });
        } else {
            $('#country_id').empty();
            $('#country_id').append('<option value="">Select Country</option>');
        }
    });

    $(document).on('change', '#country_id', function() {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                url: "/get-cities",
                type: "GET",
                data: { country_id: countryId },
                success: function(data) {
                    $('#city_id').empty();
                    $('#city_id').append('<option value="">Select City</option>');
                    $.each(data, function(key, city) {
                        $('#city_id').append('<option value="'+ city.id +'">'+ city.name +'</option>');
                    });
                    $('#city_id').trigger('change');
                }
            });
        } else {
            $('#city_id').empty();
            $('#city_id').append('<option value="">Select City</option>');
        }
    });
})