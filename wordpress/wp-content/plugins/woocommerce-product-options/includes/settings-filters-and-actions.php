<?php

class WooCommerce_Product_Options_Settings_Filters_And_Actions {

    function __construct() {
        add_action( 'wp_head', array( $this, 'custom_css' ) );
        add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'woocommerce_product_add_to_cart_text' ), 10, 2 );
        add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'woocommerce_product_add_to_cart_url' ), 10, 2 );
        add_filter( 'option_woocommerce_enable_ajax_add_to_cart', array( $this, 'option_woocommerce_enable_ajax_add_to_cart' ) );
    }

    function option_woocommerce_enable_ajax_add_to_cart( $unserialized_value ) {
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( !empty( $settings[ 'shop_page_link_to_product' ] ) ) {
            return 'no';
        }
        return $unserialized_value;
    }

    function woocommerce_product_add_to_cart_url( $url, $product ) {
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( !empty( $settings[ 'shop_page_link_to_product' ] ) ) {
            return get_permalink( $product->get_id() );
        }
        return $url;
    }

    function woocommerce_product_add_to_cart_text( $text, $product ) {
        $product_options = get_post_meta( $product->get_id(), 'backend-product-options', true );
        global $woocommerce_product_options_product_option_group;
        $group_ids = $woocommerce_product_options_product_option_group->get_group_ids( $product->get_id() );
        if ( empty( $product_options ) && empty( $group_ids ) ) {
            return $text;
        }
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( !empty( $settings[ 'loop_button_label' ] ) ) {
            return $settings[ 'loop_button_label' ];
        }
        return $text;
    }

    function custom_css() {
        print '<style type="text/css">';
        $woocommerce_product_options_settings = get_option( 'woocommerce_product_options_settings', array() );
        print value( $woocommerce_product_options_settings, 'custom_css' );
        print '</style>';
    }

}

$woocommerce_product_options_settings_filters_and_actions = new WooCommerce_Product_Options_Settings_Filters_And_Actions();
