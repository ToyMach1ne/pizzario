<?php

class WooCommerce_Product_Options_Checkout {

    function __construct() {
        add_action( 'woocommerce_add_order_item_meta', array( $this, 'woocommerce_add_order_item_meta' ), 10, 3 );
    }

    /**
     * Adds options to order item meta
     */
    function woocommerce_add_order_item_meta( $item_id, $values, $cart_item_key ) {
        if ( !empty( $values[ '_product_options' ] ) ) {
            $product_options = $values[ '_product_options' ];
            foreach ( $product_options as $product_option ) {
                wc_add_order_item_meta( $item_id, $product_option[ 'name' ], $product_option[ 'value' ] );
            }
        }
    }

}

$woocommerce_product_options_checkout = new WooCommerce_Product_Options_Checkout();
?>