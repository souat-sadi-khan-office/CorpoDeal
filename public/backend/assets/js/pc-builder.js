$(document).ready(function() {

    // for changing core component type
    $(document).on('change', '#core_component_type', function() {
        let core_component_type = $(this).val();
        
        $('.core-component-item-area').hide();
        switch(core_component_type) {
            case 'processor':
                $('#cpu_area').show();
            break;
            case 'motherboard':
                $('#motherboard_area').show();
            break;
            case 'ram':
                $('#ram_area').show();
            break;
            case 'storage':
                $('#storage_area').show();
            break;
            case 'psu':
                $('#psu_area').show();
            break;
            case 'graphics-card':
                $('#gCard_area').show();
            break;
            case 'cpu_cooler':
                $('#cpu_cooler_area').show();
            break;
            case 'casing':
                $('#casing_area').show();
            break;
        }
    })

    // for changing component type
    $(document).on('change', '#component_type', function() {
        let component_type = $(this).val();
        if(component_type == 'core') {
            $('.core-component-area').show();
            $('.peri-component-area').hide();
        } else {
            $('.peri-component-area').show();
            $('.core-component-area').hide();
        }
    })


    // show or hide pc builder area
    $(document).on('change', '#add_to_pc_build', function() {
        let value = $(this).val();
        if(value == 1) {
            $('.pc-builder-area').show();
        } else {
            $('.pc-builder-area').hide();
        }
    });
});