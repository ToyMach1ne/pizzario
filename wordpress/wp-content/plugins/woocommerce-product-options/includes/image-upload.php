<?php
require_once('../../../../wp-load.php');
//Save any submitted data
$cancel = false;
$post_id = intval( $_REQUEST[ 'post-id' ] );
$option_id = $_REQUEST[ 'option-id' ];
if( is_numeric( $option_id ) ) {
    $option_id = intval( $option_id );
}
if ( !empty( $_REQUEST[ 'cancel' ] ) && $_REQUEST[ 'cancel' ] == 'yes' && wp_verify_nonce( value( $_REQUEST, 'woocommerce_product_options_image_upload' ), 'woocommerce_product_options_image_upload_form' ) ) {
    unset( $_SESSION[ 'product_options_' . $post_id . '_' . $option_id ] );
    $value_chosen = '';
    $cancel = true;
}
if ( !$cancel && isset( $_POST[ 'post-id' ] ) && wp_verify_nonce( value( $_POST, 'woocommerce_product_options_image_upload' ), 'woocommerce_product_options_image_upload_form' ) ) {
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    $file = 'image-upload';
    $attachment_id = media_handle_upload( $file, 0 );
    $src = wp_get_attachment_url( $attachment_id );
    $_SESSION[ 'product_options_' . $post_id . '_' . $option_id ] = $src;
    $value_chosen = $src;
} else {//Nothing to save
    $options = get_post_meta( $post_id, 'backend-product-options', true );
    $value_chosen = '';
    if ( !empty( $options[ $option_id ] ) ) {
        $option = $options[ $option_id ];
        $value_chosen = value( $option, 'default', '' );
    }
    if ( isset( $_SESSION[ 'product_options_' . $post_id . '_' . $option_id ] ) ) {
        $value_chosen = $_SESSION[ 'product_options_' . $post_id . '_' . $option_id ];
    }
}

//The form
print '<html><body onload="parent.updateFrameSize(document.body.scrollHeight, \'iframe-' . $option_id . '\');">'
        . '<form id="image-upload-form" method="post" enctype="multipart/form-data" action="' . plugins_url() . '/woocommerce-product-options/includes/image-upload.php">'
        . '<input type="hidden" class="cancel-upload" value="no" name="cancel" />'
        . '<label for="image-upload">'
        . '<img style="max-width:90%;max-height:200px;" class="upload-image" src="' . $value_chosen . '" />
<br/></label>
<input type="file" name="image-upload';
if ( !empty( $option[ 'multiple' ] ) ) {
    print '[]';
}
print '" class="image-upload" value="' . $value_chosen . '" ';
if ( !empty( $option[ 'multiple' ] ) ) {
    print 'multiple ';
}
print '/>';
print '<br><a href="' . plugins_url() . '/woocommerce-product-options/includes/image-upload.php" class="cancel-image-upload">' . __( 'Cancel upload', 'woocommerce-product-options' ) . '</a>';
wp_nonce_field( 'woocommerce_product_options_image_upload_form', 'woocommerce_product_options_image_upload' );
print '<input type="hidden" name="post-id" value="' . $post_id . '" />' .
        '<input type="hidden" name="option-id" value="' . $option_id . '" /></form>';
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'woocommerce-product-options-image-upload', plugins_url() . '/woocommerce-product-options/assets/js/image-upload.js', array( 'jquery' ), false, true );
wp_footer();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.cancel-image-upload').click(function (e) {
            e.preventDefault();
            $('.cancel-upload').val('yes');
            $(this).closest('form').submit();
        });
    });
</script>
</body></html>
