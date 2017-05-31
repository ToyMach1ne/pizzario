<?php

class WooCommerce_Product_Options_Rest_Api {

    function __construct() {
        add_action( 'woocommerce_api_create_product', array( $this, 'woocommerce_api_edit_product' ), 10, 2 );
        add_action( 'woocommerce_api_edit_product', array( $this, 'woocommerce_api_edit_product' ), 10, 2 );
        add_filter( 'woocommerce_api_product_response', array( $this, 'woocommerce_api_product_response' ), 10, 4 );
        add_filter( 'woocommerce_api_classes', array( $this, 'woocommerce_api_classes' ) );
        add_action('woocommerce_api_loaded',array($this,'woocommerce_api_loaded'));
    }
    
    function woocommerce_api_loaded() {
        include_once 'rest-api-product-groups.php';
    }

    function woocommerce_api_classes( $classes ) {
        $classes[] = 'WooCommerce_Product_Options_Rest_API_Product_Groups';
        return $classes;
    }

    function woocommerce_api_edit_product( $product_id, $product_data ) {
        if ( isset( $product_data[ 'product-options' ] ) ) {
            $product_options = $product_data[ 'product-options' ];
            delete_post_meta( $product_id, 'backend-product-options' );
            add_post_meta( $product_id, 'backend-product-options', $product_options, true );
        }
        if ( isset( $product_data[ 'product-options-settings' ] ) ) {
            $product_options = $product_data[ 'product-options-settings' ];
            delete_post_meta( $product_id, 'product-options-settings' );
            add_post_meta( $product_id, 'product-options-settings', $product_options, true );
        }
    }

    function woocommerce_api_product_response( $product_data, $product, $fields, $server ) {
        $product_options = get_post_meta( $product->get_id(), 'backend-product-options', true );
        if ( !empty( $product_options ) ) {
            $product_data[ 'product-options' ] = $product_options;
        } else {
            $product_data[ 'product-options' ] = array();
        }
        $product_options_settings = get_post_meta( $product->get_id(), 'product-options-settings', true );
        if ( !empty( $product_options_settings ) ) {
            $product_data[ 'product-options-settings' ] = $product_options_settings;
        } else {
            $product_data[ 'product-options-settings' ] = array();
        }
        return $product_data;
    }

}

$woocommerce_product_options_rest_api = new WooCommerce_Product_Options_Rest_Api();
