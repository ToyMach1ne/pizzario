<?php

class Product_Options_In_Cart {

    function __construct() {
        add_filter( 'woocommerce_get_item_data', array( &$this, 'woocommerce_get_item_data' ), 10, 2 );
        add_filter( 'woocommerce_get_cart_item_from_session', array( &$this, 'woocommerce_get_cart_item_from_session' ), 10, 2 );
        add_filter( 'woocommerce_add_cart_item_data', array( &$this, 'woocommerce_add_cart_item_data' ), 1, 2 );
        add_action( 'woocommerce_cart_loaded_from_session', array( $this, 'woocommerce_cart_loaded_from_session' ) );
    }

    /**
     * Adds data to the cart
     */
    function woocommerce_get_item_data( $data, $cart_item ) {
        if ( !empty( $cart_item[ '_product_options' ] ) ) {
            foreach ( $cart_item[ '_product_options' ] as $name_and_value ) {
                $data[] = $name_and_value;
            }
        }
        return $data;
    }

    function get_product_options_from_request( $product_id ) {
        global $woocommerce_product_options_ajax;
        $product_option_post_id = value($_POST,'product_post_id');
        if ( !empty( $_POST[ 'product-options' ] ) ) {
            foreach ( $_POST[ 'product-options' ] as $product_option_post_id => $options ) {
                if ( !empty( $options ) ) {
                    foreach ( $options as $product_option_id => $option_value ) {
                        $woocommerce_product_options_ajax->update_product_option( $product_id, $product_option_id, $option_value, $product_option_post_id );
                    }
                }
            }
        }
        if ( !empty( $_POST[ 'post_data' ] ) ) {
            $params = array();
            parse_str( $_POST[ 'post_data' ], $params );
            if ( !empty( $params[ 'product-options' ] ) ) {
                foreach ( $params[ 'product-options' ] as $product_option_post_id => $options ) {
                    if ( !empty( $options ) ) {
                        foreach ( $options as $product_option_id => $option_value ) {
                                        $woocommerce_product_options_ajax->update_product_option( $product_option_post_id, $product_option_id, $option_value, $product_option_post_id );
                        }
                    }
                }
            }
        }
        if ( !empty( $_GET ) ) {
            $product_options = get_post_meta( $product_id, 'backend-product-options', true );
            $request_options = array();
            foreach ( $_GET as $key => $value ) {
                $request_options[ $this->urldecode( $key ) ] = $this->urldecode( $value );
            }
            if ( !empty( $product_options ) ) {
                foreach ( $product_options as $product_option_id => $product_option ) {
                    if ( !empty( $product_option[ 'title' ] ) ) {
                        $title = trim( $product_option[ 'title' ] );
                        if ( isset( $request_options[ $title ] ) ) {
                            $woocommerce_product_options_ajax->update_product_option( $product_id, $product_option_id, $request_options[ $title ] );
                        }
                    }
                }
            }
        }
    }

    function urldecode( $string ) {
        $firstChar = $string[ 0 ];
        $decoded_string = str_replace( '_', ' ', urldecode( $string ) );
        if ( $firstChar == '_' ) {
            $decoded_string[ 0 ] = '_';
        }
        return $decoded_string;
    }

    /**
     * Saves data from session variable
     */
    function woocommerce_add_cart_item_data( $cart_item_meta, $product_id ) {
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        $this->get_product_options_from_request( $product_id );
        global $woocommerce_product_options_product_option_group;
        $option_post_ids = $woocommerce_product_options_product_option_group->get_group_ids( $product_id );
        $option_post_ids[] = $product_id;
        $options_for_cart = array();
        //if ( isset( $_SESSION['product_options_' . $product_id . '_price'] ) ) {
        $current_option = 1;
        foreach ( $option_post_ids as $option_post_id ) {
            $product_options = get_post_meta( $option_post_id, 'backend-product-options', true );
            $product_meta_settings = get_post_meta( $option_post_id, 'product-options-settings', true );
            $revert_options = value( $product_meta_settings, 'revert_options', '' );
            if ( !empty( $product_options ) ) {
                foreach ( $product_options as $product_option_id => $product_option ) {
                    $type = value( $product_option, 'type' );
                    if ( $type == 'checkbox' ) {
                        $value = '';
                        if ( !empty( $_SESSION[ 'product_options_' . $option_post_id . '_' . $product_option_id ] ) ) {
                            $value = $_SESSION[ 'product_options_' . $option_post_id . '_' . $product_option_id ];
                        }
                        /*if ( !empty( $value ) && 'yes' == $value ) {
                            $hides = value( $product_option, 'hides' );
                            if ( !empty( $hides ) ) {
                                foreach ( $hides as $hide_id => $hide_value ) {
                                    unset( $product_options[ $hide_id ] );
                                }
                            }
                        } else {
                            $shows = value( $product_option, 'shows' );
                            if ( !empty( $shows ) ) {
                                foreach ( $shows as $show_id => $show_value ) {
                                    unset( $product_options[ $show_id ] );
                                }
                            }
                        }*/
                    }
                }
                foreach ( $product_options as $product_option_id => $product_option ) {
                    if ( isset( $_SESSION[ 'product_options_' . $option_post_id . '_' . $product_option_id ] ) &&  !isset( $_SESSION[ 'hide_product_options_' . $option_post_id . '_' . $product_option_id ] ) ) {
                        $value = $_SESSION[ 'product_options_' . $option_post_id . '_' . $product_option_id ];
                        if ( empty( $product_option[ 'type' ] ) ) {
                            continue;
                        }
                        if ( $product_option[ 'type' ] == 'color_picker' ) {
                            $value = '<span style="color:' . $value . '">&#9608;</span>';
                        } elseif ( $product_option[ 'type' ] == 'multi_select' || $product_option[ 'type' ] == 'radio_image' || $product_option[ 'type' ] == 'checkboxes' ) {
                            $list = '';
                            if ( !empty( $value ) && !empty( $product_option[ 'options' ] ) ) {
                                if ( !is_array( $value ) ) {
                                    $value = array( $value );
                                }
                                foreach ( $value as $selected_option ) {
                                    $selected_option = $selected_option;
                                    if( is_numeric( $selected_option ) ) {
                                        $selected_option = intval( $selected_option );
                                    }
                                    if ( !empty( $product_option[ 'options' ][ $selected_option ] ) && isset( $product_option[ 'options' ][ $selected_option ][ 'src' ] ) && empty( $product_option['display_labels_in_cart'] ) && value( $settings, 'labels_instead_of_images') != 'yes' ) {
                                        global $wpdb;
                                        $src = $product_option[ 'options' ][ $selected_option ][ 'src' ];
                                        $id = value( $product_option[ 'options' ][ $selected_option ], 'id' );
                                        $image_post_id = $id;
                                        $srcset = '';
                                        if ( !empty( $image_post_id ) && function_exists( 'wp_get_attachment_image_srcset' ) ) {
                                            $srcset = wp_get_attachment_image_srcset( $image_post_id, 'shop_single' );
                                        }
                                        if ( !empty( $srcset ) ) {
                                            $srcset = 'srcset="' . $srcset . '"';
                                        }
                                        $image_src = value( wp_get_attachment_image_src( $image_post_id ), 0 );
                                        if ( empty( $image_src ) ) {
                                            $image_src = $src;
                                        }
                                        $list = $list . ' ' . '<img style="max-width:300px;max-height:300px;" ' . $srcset . ' src="' . $image_src . '" alt="' . $product_option[ 'options' ][ $selected_option ][ 'label' ] . '" title="' . $product_option[ 'options' ][ $selected_option ][ 'label' ] . '" />';
                                    } elseif ( !empty( $product_option[ 'options' ][ $selected_option ] ) && isset( $product_option[ 'options' ][ $selected_option ][ 'label' ] ) ) {
                                        $list = $list . ', ' . $product_option[ 'options' ][ $selected_option ][ 'label' ];
                                    } elseif ( !empty( $product_option[ 'options' ][ $selected_option ] ) && isset( $product_option[ 'options' ][ $selected_option ][ 'label-after' ] ) ) {
                                        $list = $list . ', ' . $product_option[ 'options' ][ $selected_option ][ 'label-after' ];
                                    }
                                }
                                $value = trim( $list, ', ' );
                            } else {
                                $value = __( 'None', 'woocommerce-product-options' );
                            }
                        } elseif ( $product_option[ 'type' ] == 'checkbox' ) {
                            if ( !empty( $value ) && 'yes' == $value ) {
                                $value = __( 'yes', 'woocommerce-product-options' );
                            } else {
                                $value = __( 'no', 'woocommerce-product-options' );
                            }
                        } elseif ( !empty( $product_option[ 'options' ] ) && !empty( $product_option[ 'options' ][ $value ] ) && isset( $product_option[ 'options' ][ $value ][ 'label' ] ) ) {
                            $value = $product_option[ 'options' ][ $value ][ 'label' ];
                        }
                        $cart_name = value( $product_option, 'title' );
                        if ( empty( $cart_name ) ) {
                            $cart_name = value( $product_option, 'label-before' );
                        }
                        if ( empty( $cart_name ) ) {
                            $cart_name = value( $product_option, 'label-after' );
                        }
                        if ( empty( $cart_name ) ) {
                            $cart_name = __( 'Option', 'woocommerce-product-options' ) .' '. $current_option;
                        }
                        if ( !empty( $product_option[ 'show-price-in-cart' ] ) ) {
                            $price = $_SESSION[ 'product_options_' . $option_post_id . '_' . $product_option_id . '_price' ];
                            if ( class_exists( 'WOOCS' ) ) {
                                global $WOOCS;
                                $currencies = $WOOCS->get_currencies();
                                $currency_multiplier = floatval( $currencies[ $WOOCS->current_currency ][ 'rate' ] );
                                if ( empty( $currency_multiplier ) ) {
                                    $currency_multiplier = floatval( $currencies[ $WOOCS->default_currency ][ 'rate' ] );
                                }
                                if ( empty( $currency_multiplier ) ) {
                                    $currency_multiplier = 1;
                                }
                                $price = floatval( $price ) * $currency_multiplier;
                            }
                            $plus = '';
                            if ( floatval( $price ) > 0 ) {
                                $plus = '+';
                            }
                            $cart_name .= sprintf( __( ' (%s%s)', 'woocommerce-product-options' ), $plus, wc_price( $price ) );
                        }
                        $options_for_cart[ $current_option ] = array( 'name' => $cart_name,
                            'value' => $value );
                        if ( $product_option[ 'type' ] == 'image_upload' ) {
                            if ( !is_array( $value ) ) {
                                $value = array( $value );
                            }
                            $value_for_cart = '';
                            foreach ( $value as $img_src ) {
                                $value_for_cart = $value_for_cart . '<img src="' . $img_src . '" /> ';
                            }
                            $options_for_cart[ $current_option ] [ 'value' ] = $value_for_cart;
                        }
                        if ( !isset( $options_for_cart[ $current_option ] [ 'value' ] ) || $options_for_cart[ $current_option ] [ 'value' ] == '' ) {
                            unset( $options_for_cart[ $current_option ] );
                        }
                    }
                    $current_option = $current_option + 1;
                }
            }
        }
        $cart_item_meta[ '_product_options' ] = $options_for_cart;
        $product = wc_get_product( $product_id );
        global $woocommerce_product_options_ajax;
        $cart_item_meta[ '_custom_price' ] = $woocommerce_product_options_ajax->get_price( $product_id );
        if ( isset( $_SESSION[ 'product_options_' . $product_id . '_price' ] ) ) {
            $current_option = 0;
            foreach ( $option_post_ids as $option_post_id ) {
                $product_options = get_post_meta( $option_post_id, 'backend-product-options', true );
                $product_meta_settings = get_post_meta( $option_post_id, 'product-options-settings', true );
                $revert_options = value( $product_meta_settings, 'revert_options', '' );
                if ( !empty( $revert_options ) && !empty( $product_options ) ) {
                    foreach ( $product_options as $product_option_id => $product_option ) {
                        unset( $_SESSION[ 'product_options_' . $option_post_id . '_' . $product_option_id ] );
                    }
                }
            }
        }
        //}
        return $cart_item_meta;
    }

    function woocommerce_cart_loaded_from_session( $cart ) {
        $cart->calculate_totals();
    }

    /**
     * Gets the data from the woocommerce session
     */
    function woocommerce_get_cart_item_from_session( $cart_item, $values ) {
        if ( !empty( $values[ '_product_options' ] ) ) {
            $cart_item[ '_product_options' ] = $values[ '_product_options' ];
            $cart_item[ '_custom_price' ] = $values[ '_custom_price' ];
            $cart_item[ 'data' ]->set_price( $this->convert_to_number( $cart_item[ '_custom_price' ] ) );
        }
        return $cart_item;
    }

    /**
     * Adds data to the order
     */
    function woocommerce_add_order_item_meta_action( $item_id, $cart_item ) {
        if ( !empty( $cart_item[ '_product_options' ] ) ) {
            foreach ( $cart_item[ '_product_options' ] as $product_option_id => $name_and_value ) {
                $name = $name_and_value[ 'name' ];
                $value = $name_and_value[ 'value' ];
                woocommerce_add_order_item_meta( $item_id, $name, $value );
            }
        }
    }

    /**
     * Converts number to flat
     */
    function convert_to_number( $number ) {
        return floatval( $number );
    }

}

$product_options_in_cart = new Product_Options_In_Cart();
?>