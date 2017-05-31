<?php

//Update selected options using ajax
class WooCommerce_Product_Options_Ajax {

    /**
     * Creates object
     */
    function __construct() {
    }


    function kses_data( $string ) {
        return str_replace( "\'", "'", wp_kses_data( $string ) );
    }

    function ajax_update_product_options() {
        $post_id = value( $_POST, 'product_post_id', -1 );
        $variation_id = value( $_POST, 'variation_id', -1 );
        $this->get_price( $post_id );
        if ( !empty( $_POST[ 'product_options' ] ) ) {
            foreach ( $_POST[ 'product_options' ] as $product_options_post_id => $options ) {
                $product_options = get_post_meta( $product_options_post_id, 'backend-product-options', true );
                foreach ( $product_options as $option_id => $product_option ) {
                    $option_value = value( $options, $option_id );
                    if ( $option_value == '!!!Empty!!!' ) {
                        $option_value = '';
                    }
                    $this->update_product_option( $post_id, $option_id, $option_value, $product_options_post_id, $variation_id );
                }
            }
        }
    }

    /**
     * ajax - gets the price
     */
    function update_product_option( $post_id = -1 /* The product id */, $product_option_id = -1 /* The option id of the product option (1, 2, 3 depending on whether it is the 1st/2nd/3rd option) */, $option = '' /* The value of the option */, $product_option_post_id = -1 /* Either the product id or the product group post id */, $variation_id = -1 /* The selected variation id */ ) {
        if ( !empty( $_POST ) && !empty( $_POST[ 'product-options' ] ) ) {
            $post_id = intval( value($_REQUEST, 'product_post_id', $post_id) );
            $product_option_post_id = intval( value($_REQUEST, 'product_option_post_id', $product_option_post_id) );
            $product_option_id = value($_REQUEST, 'product_option_id', $product_option_id );
            $option = value( $_REQUEST, 'product_option', $option );
            $variation_id = value( $_REQUEST, 'variation_id', $variation_id );
        } else {
            if(!empty($_POST) && !empty($_POST['post_data'])) {
                $params = array();
                parse_str($_POST['post_data'],$params);
                if(empty($params['product-options'])) {
                    return;
                }
            }
        }
        if ( $product_option_post_id == -1 ) {
            $product_option_post_id = $post_id;
        }
        if ( is_string( $option ) ) {
            $product_option = $this->kses_data( $option );
        } elseif ( is_array( $option ) ) {
            $product_option = array_map( array( $this, 'kses_data' ), $option );
        }
        $src = '';
        $product_options = get_post_meta( $product_option_post_id, 'backend-product-options', true );
        if( !isset( $product_options[ $product_option_id ] ) ) {
            return;
        }
        if(!empty($_POST['product-options-hidden']) && 
                !empty($_POST['product-options-hidden'][$product_option_post_id])
                && !empty($_POST['product-options-hidden'][$product_option_post_id][$product_option_id])) {
            $_SESSION[ 'hide_product_options_' . $product_option_post_id . '_' . $product_option_id ]='yes';
            $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ]='';
            $price = 0;
        } elseif ( $product_options[ $product_option_id ][ 'type' ] == 'image_upload' ) {
            unset($_SESSION[ 'hide_product_options_' . $product_option_post_id . '_' . $product_option_id ]);
            $src = $this->upload_image( 'product-options[' . $product_option_id . ']' );
            if ( empty( $src ) && !empty( $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ] ) ) {
                $src = $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ];
            }
            $price = $this->get_price( $post_id, $variation_id );
        } else {
            unset($_SESSION[ 'hide_product_options_' . $product_option_post_id . '_' . $product_option_id ]);
            $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ] = $product_option;
            $price = $this->get_price( $post_id, $variation_id );
        }
        $_SESSION[ 'product_options_' . $post_id . '_price' ] = $price;
        $product = wc_get_product( $post_id );
        if(!empty($product)) {
        ob_start();
        $price_html = '<span class="amount">' . $this->get_price_html( $price, $product ) . '</span>';
        if ( $product && $product->is_on_sale() ) {
            $sale_price_difference = floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() );
            $price_html = $product->get_price_html_from_to( $price + $sale_price_difference, $price );
        }
        $response = array(
            'price' => $price_html . ob_get_clean(),
            'option_price' => wc_price( $this->get_option_price( $post_id, $product_option_post_id, $product_option_id, $product_options[ $product_option_id ] ) ),
            'src' => $src,
        );
        return json_encode( $response );
        }
    }

    function get_price_html( $price, $product ) {
        $price = wc_format_localized_price( wc_price( $price ) ) . $product->get_price_suffix();
        $price = apply_filters( 'woocommerce_price_html', $price, $product );
        return apply_filters( 'woocommerce_get_price_html', $price, $product );
    }

    function upload_image( $name ) {
        if ( !isset( $_POST[ 'post-id' ] ) ) {
            return '';
        }
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $post_id = intval( $_POST[ 'post-id' ] );
        $option_id = $_POST[ 'option-id' ];
        if( is_numeric( $option_id ) ) {
            $option_id = intval( $option_id );
        }
        $options = get_post_meta( $post_id, 'backend-product-options', true );
        $option = $options[ $option_id ];
        if ( !empty( $option[ 'multiple' ] ) ) {
            
        } else {
            $file = strval( $_POST[ 'file_name' ] );
            $post_id = 0;
            $attachment_id = media_handle_upload( $file, $post_id );
            $src = wp_get_attachment_url( $attachment_id );
            $_SESSION[ 'product_options_' . $post_id . '_' . $product_option_id ] = $src;
            return wp_get_attachment_url( $attachment_id );
        }
    }

    /**
     * Converts number to a float
     */
    function convert_to_number( $number ) {
        return floatval( $number );
    }

    /**
     * Gets the price of one option
     */
    function get_option_price( $product_post_id, $post_id, $product_option_id, $product_option ) {
        $is_order_price = false;
        $product_price = 0;
        if ( !empty( $post_id ) ) {
            $post_id_post = get_post( $post_id );
            if ( $post_id_post->post_type == 'order_option_group' ) {
                $is_order_price = true;
            }
            if ( $is_order_price ) {
                $cart = WC()->cart;
                $product_price = $cart->cart_contents_total;
            } else {
                $product_price = $this->get_product_price( $product_post_id );
            }
        }
        $value = value( $product_option, 'default', '' );
        if ( !empty( $product_option[ 'no_default' ] ) ) {
            $value = '';
        }
        if ( empty( $product_option[ 'type' ] ) ) {
            return 0;
        }
        $type = $product_option[ 'type' ];
        $multiple = value( $product_option, 'multiple', '' );
        if ( ($type == 'multi_select' || $type == 'radio_image') && !is_array($value) ) {
            $value = array( $value );
        }
        if ( isset( $_SESSION[ 'product_options_' . $post_id . '_' . $product_option_id ] ) ) {
            $value = $_SESSION[ 'product_options_' . $post_id . '_' . $product_option_id ];
        }
        $option_price = 0;
        if ( $type == 'radio' || $type == 'select' ) {
            $value = intval( $value );
            if ( !empty( $product_option[ 'options' ] ) && !empty( $product_option[ 'options' ][ $value ] ) ) {
                if ( !empty( $product_option[ 'options' ][ $value ][ 'price' ] ) ) {
                    $option_price = floatval( $product_option[ 'options' ][ $value ][ 'price' ] );
                }
                if ( !empty( $product_option[ 'options' ] ) && !empty( $product_option[ 'options' ][ $value ][ 'percentage_price' ] ) ) {
                    $option_price = $option_price + $product_price * floatval( $product_option[ 'options' ][ $value ][ 'percentage_price' ] ) / 100;
                }
            }
        } elseif ( $type == 'multi_select' || $type == 'radio_image' || $type == 'checkboxes' ) {
            if ( !empty( $value ) ) {
                if ( is_string( $value ) ) {
                    $value = array( $value );
                }
                foreach ( $value as $selected_option ) {
                    if( is_numeric( $selected_option ) ) {
                        $selected_option = intval( $selected_option );
                    }
                    if ( !empty( $product_option[ 'options' ] ) && !empty( $product_option[ 'options' ][ $selected_option ] ) && !empty( $product_option[ 'options' ][ $selected_option ][ 'price' ] ) ) {
                        $option_price = $option_price + floatval( $product_option[ 'options' ][ $selected_option ][ 'price' ] );
                    }
                    if ( !empty( $product_option[ 'options' ] ) && !empty( $product_option[ 'options' ][ $selected_option ] ) && !empty( $product_option[ 'options' ][ $selected_option ][ 'percentage_price' ] ) ) {  
                        $option_price = $option_price + $product_price * floatval( $product_option[ 'options' ][ $selected_option ][ 'percentage_price' ] ) / 100;
                    }
                }
            }
        } elseif ( $type == 'text' || $type == 'textarea' ) {
            $characters = strlen( $value );
            if ( !empty( $value ) && !empty( $product_option[ 'price_if_not_empty' ] ) ) {
                $option_price = floatval( $product_option[ 'price_if_not_empty' ] );
            }
            if ( !empty( $product_option[ 'price_per_word' ] ) ) {
                $price_per_word = floatval( $product_option[ 'price_per_word' ] );
                $words = str_word_count( $value );
                $option_price = $option_price + $price_per_word * $words;
            }
            if ( !empty( $product_option[ 'price_per_character' ] ) ) {
                $price_per_character = floatval( $product_option[ 'price_per_character' ] );
                $option_price = $option_price + $price_per_character * $characters;
            }
            $number_of_lower_case_letters = 0;
            $number_of_upper_case_letters = 0;
            for ( $i = 0; $i < strlen( $value ); $i++ ) {
                if ( ctype_lower( $value[ $i ] ) ) {
                    $number_of_lower_case_letters = $number_of_lower_case_letters + 1;
                } elseif ( ctype_upper( $value[ $i ] ) ) {
                    $number_of_upper_case_letters = $number_of_upper_case_letters + 1;
                }
            }
            if ( !empty( $product_option[ 'price_per_lower_case_letter' ] ) ) {
                $price_per_lower_case_letter = floatval( $product_option[ 'price_per_lower_case_letter' ] );
                $option_price = $option_price + $price_per_lower_case_letter * $number_of_lower_case_letters;
            }
            if ( !empty( $product_option[ 'price_per_upper_case_letter' ] ) ) {
                $price_per_upper_case_letter = floatval( $product_option[ 'price_per_upper_case_letter' ] );
                $option_price = $option_price + $price_per_upper_case_letter * $number_of_upper_case_letters;
            }
        } elseif ( $type == 'number' || $type == 'number_as_text' || $type == 'range' ) {
            $starting_from = 0;
            $percentage_price = 100;
            if ( !empty( $product_option[ 'starting_from' ] ) ) {
                $starting_from = $product_option[ 'starting_from' ];
            }
            if ( isset( $product_option[ 'percentage_price' ] ) ) {
                $percentage_price = $product_option[ 'percentage_price' ];
            }
            $option_price = (floatval( $value ) - floatval( $starting_from )) * floatval( $percentage_price ) / 100;
        } elseif ( $type == 'checkbox' ) {
            if ( !empty( $value ) && 'yes' == $value ) {
                $option_price = floatval( value( $product_option, 'checked_price', 0 ) );
            } else {
                $option_price = floatval( value( $product_option, 'unchecked_price', 0 ) );
            }
        } elseif ( $type == 'image_upload' ) {
            if ( !empty( $value ) ) {
                $option_price = floatval( value( $product_option, 'price_for_image' ) );
            }
        }
        if( !isset($_POST[ 'hide_product_options_' . $post_id . '_' . $product_option_id . '_price' ]) ) {
            $_SESSION[ 'product_options_' . $post_id . '_' . $product_option_id . '_price' ] = $option_price;
        }
        return $option_price;
    }

    function get_product_price( $post_id, $variation_id=-1 ) {
        $variation_id = value($_REQUEST,'variation_id',$variation_id);
        if(!empty($variation_id) && $variation_id!=-1) {
            $product = wc_get_product($variation_id);
        } else {
            $product = wc_get_product( $post_id );
        }
        if ( $product ) {
            if ( class_exists( 'WOOCS' ) ) {
                global $WOOCS;
                if ( $WOOCS->is_multiple_allowed ) {
                    remove_filter( 'woocommerce_get_price', array( $WOOCS, 'raw_woocommerce_price' ), 9999 );
                }
                $price = floatval( $product->get_price() );
                if ( $WOOCS->is_multiple_allowed ) {
                    add_filter( 'woocommerce_get_price', array( $WOOCS, 'raw_woocommerce_price' ), 9999 );
                }
                return $price;
            }
            return floatval( $product->get_price() );
        }
        $order = wc_get_order( $post_id );
        if( $order ) {
            return floatval( $order->get_total() );
        }
        return 0;
    }

    /**
     * Gets the price of the product with id $product_post_id including all the options
     */
    function get_price( $product_post_id, $variation_id=-1 ) {
        $product_options = get_post_meta( $product_post_id, 'backend-product-options', true );
        $price = $this->get_product_price( $product_post_id, $variation_id );
        if ( !empty( $product_options ) ) {
            foreach ( $product_options as $product_option_id => $product_option ) {
                $option_price = floatval( $this->get_option_price( $product_post_id, $product_post_id, $product_option_id, $product_option ) );
                $price = $price + $option_price;
            }
        }
        $price = $price + $this->get_rules_price( $product_post_id );
        global $woocommerce_product_options_product_option_group;
        $price = $price + $woocommerce_product_options_product_option_group->group_option_price( $product_post_id );
        return $price;
    }

    function get_rules_price( $product_option_post_id ) {
        $price = 0;
        $settings = get_post_meta( $product_option_post_id, 'product-options-settings', true );
        $cost_per_checkbox = value( $settings, 'cost_per_checkbox' );
        if ( !empty( $cost_per_checkbox ) ) {
            $minimum_number_of_checkboxes_before_cost = value( $settings, 'minimum_number_of_checkboxes_before_cost', 0 );
            $options = get_post_meta( $product_option_post_id, 'backend-product-options' );
            if ( isset( $options[ 0 ] ) && !isset( $options[ 0 ][ 'type' ] ) ) {
                $options = $options[ 0 ];
            } $number_of_checkboxes = 0;
            foreach ( $options as $product_option_id => $option ) {
                if ( value( $option, 'type' ) == 'checkboxes' ) {
                    if ( !empty( $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ] ) ) {
                        $product_option = $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ];
                        $number_of_checkboxes += count( $product_option );
                    }
                }
                if ( !empty( $option[ 'product_percentage_price' ] ) ) {
                    $product_option = $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $product_option_id ];
                    $price = $price * floatval( $product_option ) * floatval( $option[ 'product_percentage_price' ] ) / 100;
                }
            }
            if ( $number_of_checkboxes > $minimum_number_of_checkboxes_before_cost ) {
                $price = $price + ($number_of_checkboxes - $minimum_number_of_checkboxes_before_cost) * $cost_per_checkbox;
            }
        }
        return $price;
    }

}

$woocommerce_product_options_ajax = new WooCommerce_Product_Options_Ajax();
?>