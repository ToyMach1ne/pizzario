jQuery(document).ready(function ($) {

    var products = $('.product-options').closest('.product');
    products.each(function () {
        if($(this).find('.single_add_to_cart_button').length===0) {
            $(this).addClass('product-with-options');
        }
    });

});