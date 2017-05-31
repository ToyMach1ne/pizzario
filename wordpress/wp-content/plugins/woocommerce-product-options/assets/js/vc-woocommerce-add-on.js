jQuery(document).ready(function ($) {

    function updateData(option) {
        var data = option.closest('.backend-product-option').find('select[name]').serialize() + '&' +
                option.closest('.backend-product-option').find('input[name]').serialize() + '&' +
                option.closest('.backend-product-option').find('textarea[name]').serialize();
        option.closest('.vc_panel-tabs').find('.wpb_vc_param_value').val(data.replace('&&', '&'));
    }

    $(document).on('change', '.vc_panel-tabs .backend-product-option input', function () {
        updateData($(this));
    });
    $(document).on('change', '.vc_panel-tabs .backend-product-option select', function () {
        updateData($(this));
    });
    $(document).on('change', '.vc_panel-tabs .backend-product-option textarea', function () {
        updateData($(this));
    });

});