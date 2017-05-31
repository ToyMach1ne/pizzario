<?php

class Woocommerce_Product_Options_Settings extends WC_Settings_Page {

    function __construct() {
        $this->id = 'woocommerce-product-options-settings';
        $this->label = __( 'Product Options', 'woocommerce-product-options' );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 30 );
    }
    
    function admin_menu() {
        add_submenu_page( 'woocommerce', __( 'Product Option Settings', 'woocommerce-product-options' ), __( 'Product Option Settings', 'woocommerce-product-options' ), 'manage_woocommerce', 'woocommerce-product-options-settings', array( $this, 'settings_page' ) );
    }

    function get_settings() {
        $settings = apply_filters( 'woocommerce_' . $this->id . '_settings', array(
            array(
                'title' => __( 'Product Options', 'woocommerce-product-options' ),
                'type' => 'title',
                'desc' => __( 'This is the product options settings page.', 'woocommerce-product-options' ),
                'id' => 'product_options_settings_title'
            ),
            array(
                'title' => __( 'Options Position', 'woocommerce-product-options' ),
                'desc' => __( 'Enter a number to override the position of the options in your product summary (a value from 1 to about 70 which moves the options from the beginning to the end of the product summary)', 'woocommerce-product-options' ),
                'id' => 'woocommerce_product_options_settings[position]',
                'type' => 'number',
                'default' => '',
            ),
            array(
                'title' => __( 'Options underneath product image', 'woocommerce-product-options' ),
                'desc' => __( 'Would you like the product options to appear underneath the product image?', 'woocommerce-product-options' ),
                'id' => 'woocommerce_product_options_settings[display_options_below_product_image]',
                'type' => 'checkbox',
                'default' => '',
            ),
            array(
                'title' => __( 'Custom CSS', 'woocommerce-product-options' ),
                'desc' => __( 'Enter custom CSS. Each product option has class product-option and product-option-type (where type is the class of the product options). Each set of product options is surrounded by a product-options class.', 'woocommerce-product-options' ),
                'id' => 'woocommerce_product_options_settings[custom_css]',
                'type' => 'textarea',
                'default' => '',
                'css' => 'width:100%;height:200px;',
            ),
            array(
                'title' => __( 'Button Labels', 'woocommerce-product-options' ),
                'desc' => __( 'Change the link on the shop/category/search "add to cart" button to link to the product instead of adding the item to the cart.', 'woocommerce-product-options' ),
                'id' => 'woocommerce_product_options_settings[loop_button_label]',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'title' => __( 'Links to Products', 'woocommerce-product-options' ),
                'desc' => __( 'Change the link on the shop/category/search "add to cart" button to link to the product instead of adding the item to the cart.', 'woocommerce-product-options' ),
                'id' => 'woocommerce_product_options_settings[shop_page_link_to_product]',
                'type' => 'checkbox',
                'default' => '',
            ),
            array(
                'title' => __( 'Labels instead of Images in Cart', 'woocommerce-product-options' ),
                'desc' => __( 'Would you like labels instead of images to appear in the cart/checkour/order emails (this overrides what you choose within the products)?', 'woocommerce-product-options' ),
                'id' => 'woocommerce_product_options_settings[labels_instead_of_images]',
                'type' => 'checkbox',
                'default' => '',
            ),
            array( 'type' => 'sectionend', 'id' => 'product_options_settings_sectionend' ),
                ) );
        return $settings;
    }

}

return new Woocommerce_Product_Options_Settings();
?>