jQuery(document).ready(function ($) {

    $('.upload-image').click(function () {
        $('.image-upload').click();
    })

    $('.image-upload').change(function () {
        $(this).closest('form').submit();
    });

    var frame = $('#image-upload-form', window.parent.document);
    var height = jQuery("#image-upload-form").height();
    frame.height(height + 15);

});