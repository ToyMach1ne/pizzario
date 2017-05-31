<?php

/**
 * Plugin Name: Product Options for WooCommerce
 * Plugin URI: http://codecanyon.net/user/wpshowcase/portfolio/?ref=WPShowCase
 * Description: Add custom options with prices to your WooCommerce products.
 * Author: WPShowCase
 * Version: 4.124
 * Author URI: http://codecanyon.net/user/wpshowcase/portfolio/?ref=WPShowCase
 */
if ( !isset( $_SESSION ) ) {
    @session_start();
}

require_once 'includes/functions.php';
require_once 'includes/product-admin.php';
require_once 'includes/product-group.php';
require_once 'includes/order-option-group.php';
require_once 'includes/product-frontend.php';
require_once 'includes/product-options-in-cart.php';
require_once 'includes/checkout.php';
require_once 'includes/woocommerce-product-options-ajax.php';
require_once 'includes/rest-api.php';
require_once 'includes/conditional.php';
require_once 'includes/settings-filters-and-actions.php';
require_once 'includes/vc-woocommerce-add-on.php';

class WooCommerce_Product_Options {

    //Creates the object
    function __construct() {
        add_filter( 'woocommerce_get_settings_pages', array( $this, 'woocommerce_get_settings_pages' ) );
        //Ajax for backend
        add_action( 'wp_ajax_get_product_option_preview', array( $this, 'ajax_get_product_option_preview' ) );
        //Multilanguage
        load_plugin_textdomain( 'woocommerce-product-options', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        // fix scripts/styles for https
        add_action( 'wp_print_scripts', array( $this, 'change_http_scripts_to_https' ), 200 );
        add_action( 'wp_print_styles', array( $this, 'change_http_styles_to_https' ), 200 );
        add_action( 'wp_ajax_is_upload_image_option_empty', array( $this, 'ajax_is_upload_image_option_empty' ) );
        add_action( 'wp_ajax_nopriv_is_upload_image_option_empty', array( $this, 'ajax_is_upload_image_option_empty' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'settings_link' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        try {
            $delete_filenames = glob( dirname( __FILE__ ) . "/includes/*.txt" );
            if ( !empty( $delete_filenames ) ) {
                foreach ( $delete_filenames as $filename ) {
                    @unlink( $filename );
                }
            }
        } catch ( Exception $exception ) {
            
        }
    }

    function admin_menu() {
        add_submenu_page( 'woocommerce', __( 'Product Option Settings', 'woocommerce-product-options' ), __( 'Product Option Settings', 'woocommerce-product-options' ), 'manage_woocommerce', 'woocommerce-product-options-settings', array( $this, 'settings_page' ) );
    }

    function settings_page() {
        wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-product-options-settings' ) );
    }

    /**
     * Add a setting link to the plugins page
     */
    function settings_link( $links ) {
        $settings = '<a href="edit.php?post_type=order_option_group">' . __( 'Order Option Groups', 'woocommerce-product-options' ) . '</a>';
        array_unshift( $links, $settings );
        $settings = '<a href="edit.php?post_type=product_option_group">' . __( 'Product Option Groups', 'woocommerce-product-options' ) . '</a>';
        array_unshift( $links, $settings );
        $settings = '<a href="admin.php?page=wc-settings&tab=woocommerce-product-options-settings">' . __( 'Product Option Settings', 'woocommerce-product-options' ) . '</a>';
        array_unshift( $links, $settings );
        return $links;
    }

    function woocommerce_get_settings_pages( $settings ) {
        $settings[] = include dirname( __FILE__ ) . '/includes/settings.php';
        return $settings;
    }

    function ajax_is_upload_image_option_empty() {
        $image_upload_product_options = value( $_POST, 'image_upload_product_options' );
        $json = array();
        if ( !empty( $image_upload_product_options ) ) {
            foreach ( $image_upload_product_options as $id => $value ) {
                $id = intval( $id );
                $product_options = get_post_meta( $id, 'backend-product-options', true );
                foreach ( $value as $i2 => $v2 ) {
                    if( is_numeric( $i2 ) ) {
                        $i2 = intval( $i2 );
                    }
                    $product_option = value( $product_options, $i2 );
                    $default = basename( value( $product_option, 'default' ) );
                    $value = value( $_SESSION, 'product_options_' . $id . '_' . $i2 );
                    $src = value( $product_option, 'default' );
                    if ( !empty( $value ) ) {
                        $src_array = wp_get_attachment_image_src( $value, 'shop_single' );
                        if ( !empty( $src_array ) ) {
                            $src = value( $src_array, 0 );
                        }
                    }
                    if ( strpos( $value, $default ) === false ) {
                        if ( empty( $json[ $id ] ) ) {
                            $json[ $id ] = array();
                        }
                        $json[ $id ][ $i2 ] = array( 'id' => $value, 'src' => $value );
                    }
                }
            }
        }
        print json_encode( $json );
        die();
    }

    function change_http_scripts_to_https() {
        if ( !empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER['HTTPS'] !== 'off' && !file_exists( WP_PLUGIN_URL . '/woocommerce-product-options/js/image-upload.js' ) ) {
            global $wp_scripts;
            foreach ( ( array ) $wp_scripts->registered as $script ) {
                if ( stripos( $script->src, 'http://', 0 ) !== false ) {
                    $script->src = str_replace( 'http://', 'https://', $script->src );
                }
            }
        }
    }

    function change_http_styles_to_https() {
        if ( !empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER['HTTPS'] !== 'off' && !file_exists( WP_PLUGIN_URL . '/woocommerce-product-options/js/image-upload.js' ) ) {
            global $wp_styles;
            foreach ( ( array ) $wp_styles->registered as $script ) {
                if ( stripos( $script->src, 'http://', 0 ) !== false )
                    $script->src = str_replace( 'http://', 'https://', $script->src );
            }
        }
    }

    //Ajax for the backend to preview the frontend
    function ajax_get_product_option_preview() {
        global $woocommerce_product_options_product_frontend;
        $woocommerce_product_options_product_frontend->ajax_get_product_option_preview();
    }

    //Loads frontend css and js
    function frontend_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-unserialize', plugins_url() . '/woocommerce-product-options/assets/js/jquery.unserialize.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'combobox-pro', plugins_url() . '/woocommerce-product-options/combobox-pro/js/combobox-pro.js', array( 'jquery' ), false, true );
        wp_enqueue_style( 'combox-pro', plugins_url() . '/woocommerce-product-options/combobox-pro/css/combobox-pro.css' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-mouse' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-button' );
        wp_enqueue_script( 'jquery-ui-spinner' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-tooltip' );
        wp_enqueue_script( 'wc-add-to-cart-variation' );
        wp_enqueue_script( 'spectrum', plugins_url() . '/woocommerce-product-options/spectrum-master/spectrum.js', array( 'jquery' ), false, true );
        wp_enqueue_style( 'spectrum-css', plugins_url() . '/woocommerce-product-options/spectrum-master/spectrum.css' );
        wp_enqueue_script( 'woocommerce-product-options-frontend', plugins_url() . '/woocommerce-product-options/assets/js/product-options-frontend.js', array( 'combobox-pro', 'jquery', 'jquery-ui-datepicker', 'jquery-ui-tooltip', 'jquery-ui-spinner', 'jquery-ui-slider' ) );
        $number_of_decimals = 2;
        if ( function_exists( 'wc_get_price_decimals' ) ) {
            $number_of_decimals = wc_get_price_decimals();
        }
        $currency_symbol = get_woocommerce_currency_symbol();
        $price_format = get_woocommerce_price_format();
        $decimal_separator = get_option( 'woocommerce_price_decimal_sep', '.' );
        $thousands_separator = get_option( 'woocommerce_price_thousand_sep', ',' );
        $currency_multiplier = 1;
        if ( class_exists( 'WOOCS' ) ) {
            global $WOOCS;
            if ( in_array( $WOOCS->current_currency, $WOOCS->no_cents ) ) {
                $number_of_decimals = 0;
            } else {
                $number_of_decimals = $WOOCS->get_currency_price_num_decimals( $WOOCS->current_currency, $WOOCS->price_num_decimals );
            }
            $currency_symbol = $WOOCS->woocommerce_currency_symbol( $currency_symbol );
            $price_format = $WOOCS->woocommerce_price_format();
            $decimal_separator = $WOOCS->decimal_sep;
            $thousands_separator = $WOOCS->thousands_sep;
            $currencies = $WOOCS->get_currencies();
            $currency_multiplier = floatval( $currencies[ $WOOCS->current_currency ][ 'rate' ] );
            if ( empty( $currency_multiplier ) ) {
                $currency_multiplier = floatval( $currencies[ $WOOCS->default_currency ][ 'rate' ] );
            }
            if ( empty( $currency_multiplier ) || $WOOCS->is_multiple_allowed ) {
                $currency_multiplier = 1;
            }
        }
        $localize_array = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'numeric_error' => __( 'Please enter a number', 'woocommerce-product-options' ),
            'required_error' => __( 'Required field - please enter a value', 'woocommerce-product-options' ),
            'number_error' => __( 'Number field - please enter a number', 'woocommerce-product-options' ),
            'min_error' => __( 'Please enter a value more than ', 'woocommerce-product-options' ),
            'max_error' => __( 'Please enter a value less than ', 'woocommerce-product-options' ),
            'checkboxes_max_error' => __( 'You have selected too many values', 'woocommerce-product-options' ),
            'checkboxes_min_error' => __( 'Please select more values', 'woocommerce-product-options' ),
            'currency_symbol' => $currency_symbol,
            'price_format' => $price_format,
            'free' => __( 'Free', 'woocommerce-product-options' ),
            'number_of_decimals' => $number_of_decimals,
            'error_exists' => __( 'There are errors in your options, please correct them before purchasing this product.', 'woocommerce-product-options' ),
            'decimal_separator' => $decimal_separator,
            'thousands_separator' => $thousands_separator,
            'currency_multiplier' => $currency_multiplier,
        );
        wp_localize_script( 'woocommerce-product-options-frontend', 'woocommerce_product_options_settings', $localize_array );
        wp_register_style( 'woocommerce-product-options-frontend-css', plugins_url() . '/woocommerce-product-options/assets/css/product-options-frontend.css' );
        wp_enqueue_style( 'woocommerce-product-options-frontend-css' );
        wp_enqueue_script( 'woocommerce-product-options-conditional-frontend', plugins_url( '/assets/js/woocommerce-product-options-conditional-frontend.js', __FILE__ ) );
    }

}

//Creates object
$woocommerce_product_options = new WooCommerce_Product_Options();
?>