jQuery(document).ready(function ($) {

    $(document).on('click', '.backend-remove-option', function (event) {
        event.preventDefault();
        var rowToRemove = $(this).closest('.backend-option').next();
        rowToRemove.hide(500);
        setTimeout(function () {
            rowToRemove.remove();
        }, 500);
    });

    $('.sort-input-options').remove();
    $(document).on('click', '.backend-add-new-option', function (event) {
        $('.sort-input-options').remove();
    });

    $('.conditional-logic').hide();
    $(document).on('click', '.show-conditional-logic', function () {
        $(this).closest('.backend-product-option, tr').find('.conditional-logic').toggle();
    });

});