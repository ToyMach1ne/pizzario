<?php

class Woocommerce_Product_Options_Order_Option_Group {

    function __construct() {
        //Add fields to store in admin
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
        //Save fields
        add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
        //Register post type
        add_action( 'init', array( $this, 'register_post_type' ) );
        //Add the order items
        add_filter( 'woocommerce_cart_calculate_fees', array( $this, 'woocommerce_cart_calculate_fees' ), 10, 2 );
//Show free fees
        add_filter( 'woocommerce_get_order_item_totals_excl_free_fees', array( $this, 'woocommerce_get_order_item_totals_excl_free_fees' ), 10, 2 );
        add_filter( 'woocommerce_email_order_meta_fields', array( $this, 'woocommerce_email_order_meta_fields' ), 10, 3 );
        add_action( 'woocommerce_checkout_order_review', array( $this, 'woocommerce_checkout_order_review_5' ), 5 );
        
        $cart_locations = $this->get_cart_location_options();
        $checkout_locations = $this->get_checkout_location_options();
        $locations = array_merge( $cart_locations, $checkout_locations );
        foreach ( $locations as $location => $value ) {
            $func = create_function( '', 'global $woocommerce_product_options_order_option_group; $woocommerce_product_options_order_option_group->print_order_options( "' . $location . '"); return;' );
            add_action( $location, $func );
        }
    }

    function woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
        $fees = value( $_SESSION, 'order_option_fees_in_email', 0 );
        if ( !empty( $fees ) ) {
            foreach ( $fees as $fee ) {
                $fields[] = $fee;
            }
        }
        return $fields;
    }

    function woocommerce_get_order_item_totals_excl_free_fees( $is_free, $id ) {
        return false;
    }

    function print_order_options( $location ) {
        $order_option_groups = get_posts( array( 'post_type' => 'order_option_group' ) );
        if ( !empty( $order_option_groups ) ) {
            foreach ( $order_option_groups as $order_option_group ) {
                $meta = get_post_meta( $order_option_group->ID, 'group_options', true );
                if ( !empty( $meta[ $location ] ) ) {
                    $this->print_options( $order_option_group->ID );
                }
            }
        }
    }

    function get_cart_location_options() {
        return array( 'woocommerce_before_cart' => __( 'Before cart', 'woocommerce-product-options' ),
            'woocommerce_before_cart_table' => __( 'Before cart table', 'woocommerce-product-options' ),
            'woocommerce_after_cart_table' => __( 'After cart table', 'woocommerce-product-options' ),
            'woocommerce_after_cart' => __( 'After cart', 'woocommerce-product-options' ), );
    }

    function get_checkout_location_options() {
        return array(
            'woocommerce_before_checkout_form' => __( 'Before checkout form', 'woocommerce-product-options' ),
            'woocommerce_checkout_before_customer_details' => __( 'Before customer details', 'woocommerce-product-options' ),
            'woocommerce_checkout_after_customer_details' => __( 'After customer details', 'woocommerce-product-options' ),
            'woocommerce_checkout_before_order_review' => __( 'Before order review', 'woocommerce-product-options' ),
            'woocommerce_checkout_at_beginning_of_order_review' => __( 'At the beginning of order review', 'woocommerce-product-options' ),
            'woocommerce_checkout_after_order_review' => __( 'After order review', 'woocommerce-product-options' ),
        );
    }

    function woocommerce_checkout_order_review_5() {
        do_action( 'woocommerce_checkout_at_beginning_of_order_review' );
    }

    function woocommerce_cart_calculate_fees( $cart ) {
        global $wpdb;
        global $woocommerce_product_options_ajax;
        global $product_options_in_cart;
        $sql = "SELECT ID from {$wpdb->prefix}posts WHERE post_type='order_option_group' AND post_status='publish'";
        $post_ids = $wpdb->get_col( $sql );
        $_SESSION[ 'order_option_fees_in_email' ] = array();
        if ( !empty( $post_ids ) ) {
            foreach ( $post_ids as $post_id ) {
                $this->update_session( $post_id );
                $items = array();
                $items = $product_options_in_cart->woocommerce_add_cart_item_data( $items, $post_id );
                $product_options = get_post_meta( $post_id, 'backend-product-options', true );
                if ( !empty( $product_options ) ) {
                    $item_id = 0;
                    foreach ( $product_options as $product_option_id => $product_option ) {
                        $amount = $woocommerce_product_options_ajax->get_option_price( $post_id, $post_id, $product_option_id, $product_option );
                        if ( !empty( $items[ '_product_options' ][ $item_id ] ) ) {
                            $name = $items[ '_product_options' ][ $item_id ][ 'name' ] . __( ' - ', 'woocommerce-product-options' ) . $items[ '_product_options' ][ $item_id ][ 'value' ];
                            $cart->add_fee( $name, $amount );
                            $_SESSION[ 'order_option_fees_in_email' ] = array( 'label' => $name, 'value' => $amount );
                        }
                        $item_id++;
                    }
                }
            }
        }
    }

    function update_session( $product_option_post_id ) {
        global $woocommerce_product_options_product_frontend;
        global $woocommerce_product_options_ajax;
        $product_options = get_post_meta( $product_option_post_id, 'backend-product-options', true );
        if ( !empty( $product_options ) ) {
            foreach ( $product_options as $option_id => $option ) {
                $value_chosen = $woocommerce_product_options_product_frontend->get_value_chosen( $option, $option_id, $product_option_post_id );
                $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $option_id ] = $value_chosen;
                $woocommerce_product_options_ajax->update_product_option( $product_option_post_id, $option_id, $value_chosen, $product_option_post_id );
            }
        }
    }

    function group_option_price( $product_post_id ) {
        $price = 0;
        $product_options = get_post_meta( $product_group_post_id, 'backend-product-options', true );
        if ( !empty( $product_options ) ) {
            foreach ( $product_options as $product_option_id => $product_option ) {
                global $woocommerce_product_options_ajax;
                $option_price = $woocommerce_product_options_ajax->get_option_price( $product_post_id, $product_group_post_id, $product_option_id, $product_option );
                $price = $price + floatval( $option_price );
            }
        }
        $price = $price + $woocommerce_product_options_ajax->get_rules_price( $product_group_post_id );

        return $price;
    }

    function print_options( $order_option_group_id ) {
        global $post;
        print '<div class="product-option-groups">';

        $settings = get_post_meta( $order_option_group_id, 'product-options-settings', true );
        $above_add_to_cart = value( $settings, 'above_add_to_cart', '' );
        $options = get_post_meta( $order_option_group_id, 'backend-product-options' );
        if ( isset( $options[ 0 ] ) && !isset( $options[ 0 ][ 'type' ] ) ) {
            $options = $options[ 0 ];
        }
        $this->_print_options( $options, $order_option_group_id, $order_option_group_id );
        print '</div>';
    }

    function _print_options( $options, $product_option_post_id, $post_id ) {
        //Add the legend
        $meta = get_post_meta( $product_option_post_id, 'group_options', true );
        $legend = value( $meta, 'legend' );
        //Add the accordion
        $accordion = value( $meta, 'accordion' );
        $accordion_html = '';
        $accordion_title_class = '';
        $accordion_content_class = '';
        if ( !empty( $accordion ) ) {
            $accordion_html = ' <span class="accordion-group-expand">+</span> ';
            $accordion_content_class = 'product-option-accordion-group-content';
            $accordion_title_class = 'accordion-group';
        }
        print '<div class="product-option-group">';
        if ( !empty( $legend ) ) {
            print '<fieldset><legend class="' . $accordion_title_class . '">' . get_the_title( $product_option_post_id ) . $accordion_html . '</legend>';
        } else {
            print '<h2 class="' . $accordion_title_class . ' product-option-group-title">' . get_the_title( $product_option_post_id ) . $accordion_html . '</h2>';
        }
        print '<div class="' . $accordion_content_class . '">';
        //Add the options
        global $woocommerce_product_options_product_frontend;
        $woocommerce_product_options_product_frontend->_print_options( $options, $product_option_post_id, $post_id );

        if ( !empty( $legend ) ) {
            print '</fieldset>';
        }
        print '</div>';
        print '</div>';
    }

    function save_meta_boxes( $post_id, $post ) {
        global $woocommerce_product_options_product_admin;
        $woocommerce_product_options_product_admin->save_meta_boxes( $post_id, $post );
//Save group options
        if ( $post->post_type != 'order_option_group' ) {
            return;
        }
        $value = array();
        if ( isset( $_POST[ 'group_options' ] ) ) {
            $value = $_POST[ 'group_options' ];
        }
        if ( !add_post_meta( $post_id, 'group_options', $value, true ) ) {
            update_post_meta( $post_id, 'group_options', $value );
        }
    }

    function register_post_type() {
        register_post_type( 'order_option_group', array( 'labels' => array(
                'name' => __( 'Order Option Groups', 'woocommerce-product-options' ),
                'singular_name' => __( 'Order Option Group', 'woocommerce-product-options' ),
                'menu_name' => __( 'Order Option Groups', 'woocommerce-product-options' ),
                'add_new' => __( 'Add Order Option Group', 'woocommerce-product-options' ),
                'add_new_item' => __( 'Add New Order Option Group', 'woocommerce-product-options' ),
                'edit' => __( 'Edit', 'woocommerce-product-options' ),
                'edit_item' => __( 'Edit Order Option Group', 'woocommerce-product-options' ),
                'new_item' => __( 'New Order Option Group', 'woocommerce-product-options' ),
                'view' => __( 'View Order Option Group', 'woocommerce-product-options' ),
                'view_item' => __( 'View Order Option Group', 'woocommerce-product-options' ),
                'search_items' => __( 'Search Order Option Groups', 'woocommerce-product-options' ),
                'not_found' => __( 'No Order Option Groups found', 'woocommerce-product-options' ),
                'not_found_in_trash' => __( 'No Order Option Groups found in trash', 'woocommerce-product-options' ),
                'parent' => __( 'Parent Order Option Group', 'woocommerce-product-options' )
            ),
            'description' => __( 'This is where you can add/edit your Order Option Groups.', 'woocommerce-product-options' ),
            'public' => false,
            'show_ui' => true,
            'capability_type' => 'shop_order',
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_menu' => current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true,
            'hierarchical' => false,
            'show_in_nav_menus' => false,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array( 'title', 'page-attributes' ),
            'has_archive' => false,
                )
        );
    }

    function add_meta_boxes() {
        global $woocommerce_product_options_product_admin;
        add_meta_box(
                'woocommerce-product-group-options', __( 'Group Options', 'woocommerce-product-options' ), array( $this, 'add_meta_box' ), 'order_option_group', 'normal', 'default'
        );
        add_meta_box(
                'woocommerce-order-options', __( 'Order Options', 'woocommerce-product-options' ), array( $woocommerce_product_options_product_admin, 'add_meta_box' ), 'order_option_group', 'normal', 'default'
        );
        $this->enqueue_scripts();
    }

    function add_meta_box() {
        global $post;
        $post_id = $post->ID;
        $meta = get_post_meta( $post_id, 'group_options', true );
        $checkout_location_options = $this->get_checkout_location_options();
        wpshowcase_checkboxes( $checkout_location_options, $meta, __( 'Where would you like these options to appear on the checkout page?', 'woocommerce-product-options' ), 'group_options' );
        //$cart_location_options = $this->get_cart_location_options();
        //wpshowcase_checkboxes( $cart_location_options, $meta, __( 'Where would you like these options to appear on the cart page?', 'woocommerce-product-options' ), 'group_options' );
        //Option to accordion or legend group
        $legend = value( $meta, 'legend' );
        print '<br>';
        print '<label><input type="checkbox" class="legend" name="group_options[legend]" value="yes" ';
        if ( !empty( $legend ) ) {
            print 'checked="checked" ';
        }
        print '/>';
        _e( 'Display a box around this group?', 'woocommerce-product-options' );
        print '</label>';

        $accordion = value( $meta, 'accordion' );
        print '<br>';
        print '<label><input type="checkbox" class="accordion" name="group_options[accordion]" value="yes" ';
        if ( !empty( $accordion ) ) {
            print 'checked="checked" ';
        }
        print '/>';
        _e( 'Accordion this group?', 'woocommerce-product-options' );
        print '</label>';
        $this->enqueue_scripts();
    }

    function enqueue_scripts() {
        wp_enqueue_script( 'woocommerce-product-options-product-group', plugins_url() . '/woocommerce-product-options/assets/js/product-group.js', array( 'jquery' ), false, true );
        $localize_array = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
        wp_localize_script( 'woocommerce-product-group', 'woocommerce_product_option_group_settings', $localize_array );
        wp_register_style( 'woocommerce-product-group-css', plugins_url() . '/woocommerce-product-options/assets/css/product-group.css' );
        wp_enqueue_style( 'woocommerce-product-group-css' );
    }

}

$woocommerce_product_options_order_option_group = new Woocommerce_Product_Options_Order_Option_Group();
?>
