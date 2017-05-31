jQuery(document).ready(function ($) {

    function updateAnyProduct(checkbox) {
        if (!checkbox.is(':checked')) {
            $('.not-any-product').show();
        } else {
            $('.not-any-product').hide();
        }
    }

    $('.any-product').change(function () {
        updateAnyProduct($(this));
    });
    updateAnyProduct($('.any-product'));


    $('.product-option-group-categories').change(function () {
        if ($(this).find(':selected').length > 0) {
            $('.product-option-group-query').hide();
        } else {
            $('.product-option-group-query').show();
        }
    });

});