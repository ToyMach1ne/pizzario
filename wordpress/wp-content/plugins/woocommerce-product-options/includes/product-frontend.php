<?php

$product_option_product_id = 0;

class Woocommerce_Product_Options_Product_Frontend {

    //Creates object
    function __construct() {
        //The content of the post
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( !empty( $settings[ 'display_options_below_product_image' ] ) && $settings[ 'display_options_below_product_image' ]=='yes' ) {
            add_action( 'woocommerce_before_single_product_summary', array( $this, 'woocommerce_before_single_product_summary' ), 1 );
            add_action( 'woocommerce_after_single_product_summary', array( $this, 'woocommerce_after_single_product_summary' ), 1 );
        } else {
            if ( !empty( $settings[ 'position' ] ) ) {
                add_action( 'woocommerce_single_product_summary', array( $this, 'print_options' ), $settings[ 'position' ], 0 );
                add_action( 'woocommerce_single_product_summary', array( $this, 'print_group_options' ), $settings[ 'position' ], 0 );
            } else {
                add_action( 'woocommerce_single_variation', array( $this, 'woocommerce_before_single_variation' ), 15 );
                add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_single_product_summary_end' ), 60, 0 );
                add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_single_product_summary_before_add_to_cart' ), 25, 0 );
                add_action( 'woocommerce_before_single_product_summary', array( $this, 'move_price_above_add_to_cart' ) );
            }
        }
        add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'woocommerce_loop_add_to_cart_link' ) );
        add_action( 'init', array( $this, 'add_shortcodes' ) );
        add_filter( 'wc_price', array( $this, 'wc_price' ), 10, 3 );
        add_filter( 'woocommerce_is_purchasable', array( $this, 'woocommerce_is_purchasable' ), 10, 2 );
    }

    function woocommerce_loop_add_to_cart_link( $data ) {
        $this->woocommerce_after_shop_loop_item();
        wp_enqueue_style( 'woocommmerce-product-options-woocommerce-loop', plugins_url() . '/woocommerce-product-options/assets/css/woocommerce-loop.css' );
        wp_enqueue_script( 'woocommmerce-product-options-woocommerce-loop', plugins_url() . '/woocommerce-product-options/assets/js/woocommerce-loop.js' );
        return $data;
    }

    function woocommerce_is_purchasable( $is_purchasable, $product ) {
        if ( $is_purchasable ) {
            return $is_purchasable;
        }
        $product_type = $product->get_type();
        if ( $product_type != 'simple' ) {
            return false;
        }
        $price = $product->get_price();
        if ( $price == 0 ) {
            $options = get_post_meta( $product->get_id(), 'backend-product-options', true );
            if ( !empty( $options ) ) {
                return true;
            }
        }
        return false;
    }

    function wc_price( $price_html, $price, $product ) {
        global $post;
        if ( isset( $post ) ) {
            $product_id = $post->ID;
        } else {
            return $price_html;
        }
        $product_meta_settings = get_post_meta( $product_id, 'product-options-settings', true );
        if ( !empty( $product_meta_settings[ 'price_text' ] ) ) {
            $price_html = str_replace( '[price]', $price_html, $product_meta_settings[ 'price_text' ] );
        }
        return $price_html;
    }

    function woocommerce_before_single_product_summary() {
        print '<div class="product-option-summary-wrapper">';
    }

    function woocommerce_after_single_product_summary() {
        print '<div class="product-option-and-groups-wrapper">';
        $this->print_options();
        global $woocommerce_product_options_product_option_group;
        $woocommerce_product_options_product_option_group->print_options( false );
        print '</div>';
        print '</div><br>';
    }

    function print_group_options() {
        $woocommerce_product_options_settings = get_option( 'woocommerce_product_options_settings', array() );
        global $post;
        $post_id = $post->ID;
        $settings = get_post_meta( $post_id, 'product-options-settings', true );
        if ( !empty( $settings[ 'position' ] ) || !empty( $woocommerce_product_options_settings[ 'position' ] ) ) {
            global $woocommerce_product_options_product_option_group;
            $woocommerce_product_options_product_option_group->print_options( false );
        }
    }

    /**
     * Ajax that shows frontend product option for backend preview
     */
    function ajax_get_product_option_preview() {
        if ( empty( $_POST[ 'options_chosen' ] ) ) {
            die();
        }
        $options_chosen = ( array ) $_POST[ 'options_chosen' ];
        $this->_print_options( $options_chosen, 238, 238 );
        die();
    }

    /**
     * To show the options on the shop and search pages
     */
    function woocommerce_after_shop_loop_item() {
        global $post;
        $post_id = $post->ID;
        $settings = get_post_meta( $post_id, 'product-options-settings', true );
        $show_in_list = value( $settings, 'show_in_list', '' );
        if ( !empty( $show_in_list ) ) {
            $this->print_options();
        }
        global $woocommerce_product_options_product_option_group;
        $woocommerce_product_options_product_option_group->print_options( 'show_in_list' );
    }

    function add_shortcodes() {
        add_shortcode( 'productoptions', array( $this, 'print_options' ) );
    }

    function move_price_above_add_to_cart() {
        global $post;
        $settings = get_post_meta( $post->ID, 'product-options-settings', true );
        $above_add_to_cart = value( $settings, 'above_add_to_cart', '' );
        $in_variations_add_to_cart = value( $settings, 'in_variations_add_to_cart', '' );
        if ( ( !empty( $above_add_to_cart ) || value( $settings, 'location_of_options' ) == 'above_add_to_cart' ) && has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' ) === 10 ) {
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
            add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 27 );
        }
    }

    /**
     * Displays options after summary
     */
    function woocommerce_single_product_summary_before_add_to_cart() {
        global $post;
        $settings = get_post_meta( $post->ID, 'product-options-settings', true );
        $above_add_to_cart = value( $settings, 'above_add_to_cart', '' );
        if ( !empty( $above_add_to_cart ) || value( $settings, 'location_of_options' ) == 'above_add_to_cart' ) {
            $this->print_options( 'above_add_to_cart' );
        }
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( empty( $settings[ 'position' ] ) ) {
            global $woocommerce_product_options_product_option_group;
            $woocommerce_product_options_product_option_group->print_options( 'above_add_to_cart' );
        }
    }

    function woocommerce_before_single_variation() {
        global $post;
        $settings = get_post_meta( $post->ID, 'product-options-settings', true );
        $in_variations_add_to_cart = value( $settings, 'in_variations_add_to_cart', '' );
        if ( ( empty( $above_add_to_cart ) && !empty( $in_variations_add_to_cart ) ) || value( $settings, 'location_of_options' ) == 'in_variations_add_to_cart' ) {
            $this->print_options( 'in_variations_add_to_cart' );
        }
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( empty( $settings[ 'position' ] ) ) {
            global $woocommerce_product_options_product_option_group;
            $woocommerce_product_options_product_option_group->print_options( 'in_variations_add_to_cart' );
        }
    }

    /**
     * Displays options after summary
     */
    function woocommerce_single_product_summary_end() {
        global $post;
        $settings = get_post_meta( $post->ID, 'product-options-settings', true );
        $above_add_to_cart = value( $settings, 'above_add_to_cart', '' );
        $in_variations_add_to_cart = value( $settings, 'in_variations_add_to_cart', '' );
        if ( ( empty( $above_add_to_cart ) && empty( $in_variations_add_to_cart ) && empty( $settings[ 'location_of_options' ] ) ) || value( $settings, 'location_of_options' ) == 'below_add_to_cart' ) {
            $this->print_options( 'below_add_to_cart' );
        }
        $settings = get_option( 'woocommerce_product_options_settings', array() );
        if ( empty( $settings[ 'position' ] ) ) {
            global $woocommerce_product_options_product_option_group;
            $woocommerce_product_options_product_option_group->print_options( 'below_add_to_cart' );
        }
    }

    /**
     * Displays options
     */
    function print_options( $position = false ) {
        global $post;
        $post_id = $post->ID;
        $options = get_post_meta( $post_id, 'backend-product-options' );
        if ( isset( $options[ 0 ] ) && !isset( $options[ 0 ][ 'type' ] ) ) {
            $options = $options[ 0 ];
        }
        $this->_print_options( $options, $post_id, $post_id );
        $settings = get_option( 'woocommerce_product_options_settings', array() );
    }

    function get_value_chosen( $option, $option_id, $product_option_post_id ) {
        $value_chosen = value( $option, 'default', '' );
        $no_default = value( $option, 'no_default', '' );
        if ( 'checkbox' == value( $option, 'type' ) ) {
            if ( !empty( $option[ 'checked_by_default' ] ) ) {
                $value_chosen = 'yes';
            }
        }
        if ( !empty( $no_default ) ) {
            $value_chosen = '';
        }
        if ( isset( $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $option_id ] ) ) {
            $value_chosen = $_SESSION[ 'product_options_' . $product_option_post_id . '_' . $option_id ];
        }
        return $value_chosen;
    }
    
    function prepare_option( $option, $post_id, $product_option_post_id, $option_id, $total_price ) {
        if ( !empty( $option[ 'show-price' ] ) && $option[ 'show-price' ] == 'yes' ) {
            global $woocommerce_product_options_ajax;
            $option[ 'current-price' ] = $woocommerce_product_options_ajax->get_option_price( $post_id,
                    $product_option_post_id, $option_id, $option );
        } elseif ( !empty( $option[ 'show-price' ] ) && $option[ 'show-price' ] == 'total' ) {
            global $woocommerce_product_options_ajax;
            $option[ 'base-price' ] = $total_price - $woocommerce_product_options_ajax->get_option_price( $post_id,
                            $product_option_post_id, $option_id, $option );
        }
        print '<input type="hidden" class="show-price" value="' . value( $option,
                        'show-price' ) . '" />';
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
            foreach ( $option as $option_meta_name => $option_meta_value ) {
                if ( strpos( $option_meta_name, 'price' ) !== false && is_numeric( $option_meta_value )
                        && strpos( $option_meta_name, 'percentage' ) === false ) {
                    $option[ $option_meta_name ] = $currency_multiplier * floatval( $option_meta_value );
                }
            }
            if ( !empty( $option[ 'options' ] ) ) {
                foreach ( $option[ 'options' ] as $suboption_id => $suboption ) {
                    foreach ( $suboption as $option_meta_name =>
                                $option_meta_value ) {
                        if ( strpos( $option_meta_name, 'price' ) !== false && is_numeric( $option_meta_value )
                                && strpos( $option_meta_name, 'percentage' ) === false ) {
                            $option[ 'options' ][ $suboption_id ][ $option_meta_name ]
                                    = $currency_multiplier * floatval( $option_meta_value );
                        }
                    }
                }
            }
        }
        return $option;
    }

    /**
     * Displays options
     */
    function _print_options( $options, $product_option_post_id, $post_id ) {
        global $product_option_product_id;
        global $woocommerce_product_options_ajax;
        global $product;
        $post_id_post = get_post($post_id);
        if($post_id_post->post_type=='product_option_group') {
            $post_id = $product->get_id();
        }
        $product_option_product_id = $post_id;
        $total_price = $woocommerce_product_options_ajax->get_price( $post_id );
        $product_meta_settings = get_post_meta( $product_option_post_id, 'product-options-settings', true );
        print '<div class="product-options product-options-' . $product_option_post_id . '">';
        $product = wc_get_product( $post_id );
        if ( $product ) {
            $product_price = floatval( $product->get_price() );
            $product_nonsale_price = $product->get_regular_price();
        } else {
            $product_price = 0;
            $product_nonsale_price = 0;
        }
        $product_option_post = get_post( $product_option_post_id );
        $post_type = $product_option_post->post_type;
        print '<input type="hidden" class="product-options-product-price" value="' . $product_price . '" />
            <input type="hidden" class="product-options-product-nonsale-price" value="' . $product_nonsale_price . '" />
            <input type="hidden" class="product-options-post-type" value="' . $post_type . '" />'
                . '<input type="hidden" class="product-post-id" name="product_post_id" value="' . $post_id . '" />
                    <input type="hidden" class="product-options-post-id" value="' . $post_id . '" />
				<script>
function updateFrameSize(pixels, id){
    pixels+=12;
    document.getElementById(id).style.height=pixels+"px";
}
</script>';
        print '<input type="hidden" value="' . value( $product_meta_settings, 'accordion_keep_open' ) . '" class="accordion-keep-open" />';
        print '<input type="hidden" value="' . value( $product_meta_settings, 'accordion_keep_open_on_select' ) . '" class="accordion-keep-open-on-select" />';
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                $value_chosen = $this->get_value_chosen( $option, $option_id, $product_option_post_id );
                $option = $this->prepare_option( $option, $post_id, $product_option_post_id, $option_id, $total_price );
                $name = 'product-options[' . $product_option_post_id . '][' . $option_id . ']';
                $type = value( $option, 'type', '' );
                $this->before_input( $option_id, $name, $option, $product_option_post_id );
                if ( $type == 'radio' ) {
                    $this->radio_input( $name, $option, $value_chosen );
                } elseif ( $type == 'checkboxes' ) {
                    $this->checkboxes_input( $name, $option, $value_chosen );
                } elseif ( $type == 'image_upload' ) {
                    $this->image_upload_input( $name, $option, $value_chosen, $option_id, $product_option_post_id );
                } elseif ( $type == 'radio_image' ) {
                    $this->radio_image_input( $name, $option, $value_chosen );
                } elseif ( $type == 'select' ) {
                    $this->select_input( $name, $option, $value_chosen );
                } elseif ( $type == 'combobox' ) {
                    $this->select_input( $name, $option, $value_chosen );
                } elseif ( $type == 'multi_select' ) {
                    $this->multi_select_input( $name, $option, $value_chosen );
                } elseif ( $type == 'color_picker' ) {
                    $this->color_picker( $name, $option, $value_chosen );
                } elseif ( $type == 'number' || $type == 'range' || $type == 'number_as_text' ) {
                    $this->numeric_input( $name, $option, $value_chosen );
                } elseif ( $type == 'checkbox' ) {
                    $this->checkbox_input( $name, $option, $value_chosen );
                } elseif ( $type == 'text' ) {
                    $this->text_input( $name, $option, $value_chosen );
                } elseif ( $type == 'textarea' ) {
                    $this->textarea_input( $name, $option, $value_chosen );
                } elseif ( $type == 'datepicker' ) {
                    $this->datepicker_input( $name, $option, $value_chosen );
                }
                $this->after_input( $option_id, $name, $option );
            }
        }
        do_action( 'woocommerce_product_options_after_product_options', $options, $product_option_post_id, $post_id );
        print '</div>';
        global $woocommerce_product_options;
        $woocommerce_product_options->frontend_scripts();
    }

    /**
     * Before the option
     */
    function before_input( $option_id, $name, $option, $product_option_post_id ) {
        print value( $option, 'html-before-option' );
        global $post;
        if ( !empty( $post ) ) {
            $post_id = $post->ID;
        } else {
            $post_id = 0;
        }
        $type = value( $option, 'type', '' );
        $tooltip = value( $option, 'tooltip', '' );
        $tooltip_class = '';
        if ( !empty( $tooltip ) ) {
            $tooltip_class = 'product-option-tooltip';
        }
        print '<div class="product-option product-option-' . $type . ' ' . $tooltip_class . ' product-option-' . $option_id . '">';
        print '<input type="hidden" class="product-option-post-id" value="' . $product_option_post_id . '" />';
        print '<input type="hidden" class="product-option-id" value="' . $option_id . '" />';
        print '<input type="hidden" class="product-option-type" value="' . $type . '" />';
        if ( !empty( $option[ 'maximum_number_of_characters' ] ) ) {
            $maximum_number_of_characters = value( $option, 'maximum_number_of_characters', 0 );
            print '<input type="hidden" class="maximum-number-of-characters" value="' . $maximum_number_of_characters . '" />';
        }
        $title = value( $option, 'title', '' );
        $label = value( $option, 'label-before', '' );
        $show_price = value( $option, 'show-price', '' );
        $settings = get_post_meta( $post_id, 'product-options-settings', true );
        $accordion = value( $settings, 'accordion', value( $option, 'accordion' ) );
        $price = value( $option, 'price', '' );
        $required = value( $option, 'required', false );
        if ( !empty( $title ) ) {
            $title_classes = 'product-option-title';
            if ( !empty( $accordion ) ) {
                $title_classes.=' accordion product-option-accordion';
            }
            print '<h3 class=" ' . $title_classes . '">';
            if ( !empty( $accordion ) ) {
                print '<span class="accordion-expand">+</span> ';
            }
            print $title;
            $show_free = value( $option, 'hide-free', 'yes' );
            print '<input type="hidden" class="hide-free" value="' . value( $option, 'hide-free' ) . '" />';
            if ( isset( $option[ 'current-price' ] ) ) {
                $plus = __( '+', 'woocommerce-product-options' );
                if ( floatval( $option[ 'current-price' ] ) < 0 ) {
                    $plus = '';
                }
                if ( floatval( $option[ 'current-price' ] ) != 0 ) {
                    print ' (<span class="current-price">' . $plus . wc_price( $option[ 'current-price' ] ) . '</span>) ';
                } else {
                    print ' (<span class="current-price">' . '</span>) ';
                }
            }
            if ( $required ) {
                print ' *';
            }
            print '</h3>';
        }
        print '<div class="product-option-content';
        if ( !empty( $accordion ) ) {
            print ' product-option-accordion-content';
        }
        print '">';
        if ( !empty( $label ) ) {
            print '<p class="product-option-label">' . $label . '</p>';
        }
    }

    /**
     * After the option
     */
    function after_input( $option_id, $name, $option ) {
        $label = value( $option, 'label-after', '' );
        print ' <span class="product-option-label-after">' . $label . '</span>';
        $type = value( $option, 'type' );
        do_action( 'woocommerce_product_options_frontend_after_input', $name, $option );
        print '</div></div>';
        print value( $option, 'html-after-option' );
    }

    /**
     * Displays numeric option
     */
    function numeric_input( $name, $option, $value_chosen ) {
        $display = value( $option, 'type', 'number' );
        $minimum = value( $option, 'minimum', '' );
        $maximum = value( $option, 'maximum', '' );
        $step_size = value( $option, 'step-size', '0.01' );
        $starting_from = value( $option, 'starting_from', 0 );
        $percentage_price = value( $option, 'percentage_price', 0 );
        print '<input type="hidden" class="product-option-starting-from" value="' . $starting_from . '" />';
        print '<input type="hidden" class="product-option-percentage-price" value="' . $percentage_price . '" />';
        $required_class = '';
        $required = value( $option, 'required', false );
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        if ( $display == 'range' ) {
            if ( empty( $value_chosen ) && !empty( $minimum ) ) {
                $value_chosen = $minimum;
            } else {
                $value_chosen = 0;
            }
        }
        $min = '';
        $max = '';
        if ( $minimum != '' ) {
            $min = 'min="' . $minimum . '"';
        }
        if ( $maximum != '' ) {
            $max = 'max="' . $maximum . '"';
        }
        if ( $display == 'number' ) {
            print '<input step="' . $step_size . '"  class="product-option-value' . $required_class . '" type="number" name="' . $name . '" ' . $min . ' ' . $max . ' value="' . $value_chosen . '" />';
            //print '<div class="product-option-spinner" ' . $min . $max . ' step="' . $step_size . '"></div>';
        } elseif ( $display == 'range' ) {
            print sprintf( __( '(%d - %d)', 'woocommerce-product-options' ), $minimum, $maximum );
            print '<input type="range" class="product-option-slider product-option-value' . $required_class . '" name="' . $name . '" ' . $min . ' ' . $max . ' value="' . $value_chosen . '" />';
            print '<input type="hidden" class="range-unit' . $required_class . '" value="' . value( $option, 'label-after', '' ) . '"/>';
            //  ' <input class="product-option-value" type="hidden" step="' . $step_size . '" name="' . $name . '" ' . $min . ' ' . $max . ' value="' . $value_chosen . '" />';
            //print '<div class="product-option-slider" ' . $min . $max . ' step="' . $step_size . '"></div>' . value( $option, 'label-after', '' );
        } elseif ( $display == 'number_as_text' ) {
            print '<input type="text" class="number-as-text product-option-value' . $required_class . '" name="' . $name . '" ' . $min . ' ' . $max . ' value="' . $value_chosen . '" />';
        }
    }

    /**
     * Displays select option
     */
    function select_input( $name, $product_option, $value_chosen ) {
        $options = value( $product_option, 'options', array() );
        $no_default = value( $product_option, 'no_default', '' );
        if ( !empty( $no_default ) ) {
            $options = array( '' => array( 'label' => __( 'Choose an Option', 'woocommerce-product-options' ) ) ) + $options;
        }
        $required = value( $product_option, 'required' );
        $required_class = '';
        if ( !empty( $required ) ) {
            $required_class = ' required-product-option';
        }
        print '<select class="product-option-value ' . $required_class . '" name="' . $name . '">';
        if ( !empty( $options ) ) {
            global $product_option_product_id;
            $product = wc_get_product( $product_option_product_id );
            $product_price = floatval( $product->get_price() );
            foreach ( $options as $option_id => $option ) {
                $label = $this->add_suboption_price_to_label( $option_id, $product_option, $option, value( $option, 'label', '' ) );
                print '<option value="' . $option_id . '" ';
                if ( strval( $value_chosen ) == strval( $option_id ) ) {
                    print 'selected';
                }
                print ' title="' . $this->get_message( $product_option, $option, $option_id, true ) . '" >';
                print $label . '</option>';
            }
        }
        print '</select>';
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                print '<span class="product-option-option-data product-option-option-' . $option_id . '-data">';
                $this->option_ajax_info( $option_id, $option );
                print '</span>';
            }
        }
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                print $this->get_message( $product_option, $option, $option_id );
            }
        }
    }

    function color_picker( $name, $product_option, $value_chosen ) {
        print '<input type="color" class="product-option-value product-options-color-picker" name="' . $name . '" value=' . $value_chosen . ' />';
    }

    function add_suboption_price_to_label( $suboption_id, $option, $suboption, $label, $add_html = false ) {
        $show_option_prices = value( $option, 'show-option-prices', 'no' );
        if ( !empty( $show_option_prices ) && $show_option_prices == 'yes' ) {
            $option_price = 0;
            if ( !empty( $option[ 'price' ] ) ) {
                $option_price += floatval( $option[ 'price' ] );
            }
            if ( !empty( $option[ 'percentage_price' ] ) ) {
                global $product_option_product_id;
                $product = wc_get_product( $product_option_product_id );
                $product_price = floatval( $product->get_price() );
                $option_price += $product_price * floatval( $option[ 'percentage_price' ] ) / 100;
            }
            $plus = '+';
            if ( floatval( $option_price ) < 0 ) {
                $plus = '';
            }
            if ( floatval( $option_price ) != 0 ) {
                $label = $label . ' (' . $plus . wc_price( $option_price ) . ')';
            }
        }
        if ( $add_html ) {
            return '<span class="product-option-suboption-label product-option-suboption-label-' . $suboption_id . '">' . $label . '</span>';
        }
        return $label;
    }

    /**
     * Displays multi-select option
     */
    function multi_select_input( $name, $product_option, $value_chosen ) {
        if ( is_string( $value_chosen ) ) {
            $value_chosen = array( $value_chosen );
        }
        $this->max_and_min_selected( $product_option );
        $options = value( $product_option, 'options', array() );
        $required = value( $product_option, 'required' );
        $required_class = '';
        if ( !empty( $required ) ) {
            $required_class = ' required-product-option';
        }
        print '<select class="product-option-value' . $required_class . '" name="' . $name . '[]" multiple >';
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                $label = $this->add_suboption_price_to_label( $option_id, $product_option, $option, value( $option, 'label', '' ) );
                print '<option value="' . $option_id . '" ';
                if ( in_array( $option_id, $value_chosen ) ) {
                    print 'selected';
                }
                print ' title="' . $this->get_message( $product_option, $option, $option_id, true ) . '" >' . $label . '</option>' . $this->get_message( $product_option, $option, $option_id );
            }
        }
        print '</select>';
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                print '<span class="product-option-option-data product-option-option-' . $option_id . '-data">';
                $this->option_ajax_info( $option_id, $option );
                print '</span>';
            }
        }
    }

    /**
     * Displays image options
     */
    function radio_image_input( $name, $product_option, $value_chosen ) {
        $options = value( $product_option, 'options', array() );
        $change_thumb_on_select = value( $product_option, 'change-thumb-on-select', '' );
        $multiple = value( $product_option, 'multiple', '' );
        if ( !is_array( $value_chosen ) ) {
            if ( !empty( $value_chosen ) ) {
                $array_of_values = array( $value_chosen => $value_chosen );
            } else {
                $array_of_values = array();
            }
        } else {
            $array_of_values = $value_chosen;
        }
        $required = value( $product_option, 'required', false );
        $required_class = '';
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        if ( is_string( $array_of_values ) ) {
            $array_of_values = array( $array_of_values );
        }
        $this->max_and_min_selected( $product_option );
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                $label = $this->add_suboption_price_to_label( $option_id, $product_option, $option, value( $option, 'label', '' ), true );
                $src = value( $option, 'src', '' );
                $id = value( $option, 'id', '' );
                if ( !empty( $option[ 'id' ] ) ) {
                    $src_array = wp_get_attachment_image_src( $option[ 'id' ], 'shop_single' );
                    if ( !empty( $src_array ) ) {
                        $src = value( $src_array, 0 );
                    }
                }
                if ( !empty( $src ) ) {
                    print '<label class="radio-image-label';
                    if ( in_array( $option_id, $array_of_values ) ) {
                        print ' selected-radio-image';
                    }
                    print '" for="' . $name . '" title="' . $this->get_message( $product_option, $option, $option_id, true ) . '" >' .
                            '<img src="' . $src . '" class="radio-image-image';
                    if ( !empty( $change_thumb_on_select ) ) {
                        print ' change-thumb-on-select';
                    }
                    print '" ';
                    $image_post_id = $id;
                    $srcset = '';
                    if ( !empty( $image_post_id ) && function_exists( 'wp_get_attachment_image_srcset' ) ) {
                        $srcset = wp_get_attachment_image_srcset( $image_post_id, 'shop_single' );
                    }
                    if ( !empty( $srcset ) ) {
                        print 'srcset="' . $srcset . '"';
                    }
                    print ' />';
                    print '<br class="line-break-between-input-options"><span class="radio-image-option">' . $label . '</span>';
                    print '<input class="radio-image-radio' . $required_class . '" type="';
                    if ( empty( $multiple ) ) {
                        print 'radio';
                    } else {
                        print 'checkbox';
                    }
                    print '" name="' . $name;
                    if ( !empty( $multiple ) ) {
                        print '[' . $option_id . ']';
                    }
                    print '" value="' . $option_id . '" ';
                    if ( !empty( $array_of_values[ $option_id ] ) ) {
                        print 'checked="checked"';
                    }
                    print ' />' . $this->get_message( $product_option, $option );
                    $this->option_ajax_info( $option_id, $option );
                    print '</label>';
                }
            }
        }
    }

    function option_ajax_info( $option_id, $option ) {
        $price = value( $option, 'price', 0, true );
        if ( empty( $price ) ) {
            $price = 0;
        }
        print '<input type="hidden" class="product-option-option-price" value="' . $price . '" />';
        $percentage_price = value( $option, 'percentage_price', 0, true );
        print '<input type="hidden" class="product-option-option-percentage-price" value="' . $percentage_price . '" />';
        print '<input type="hidden" class="product-option-option-label" value="' . value( $option, 'label' ) . '" />';
        print '<input type="hidden" class="product-option-option-id" value="' . $option_id . '" />';
    }

    function max_and_min_selected( $product_option ) {
        $max_selected = value( $product_option, 'max_selected' );
        if ( !empty( $max_selected ) ) {
            print '<input type="hidden" class="max-selected" value="' . $max_selected . '"/>';
        }
        $min_selected = value( $product_option, 'min_selected' );
        if ( !empty( $min_selected ) ) {
            print '<input type="hidden" class="min-selected" value="' . $min_selected . '"/>';
        }
    }

    function add_box_effect( $product_option ) {
        $box_effect = value( $product_option, 'box_effect' );
        if ( $box_effect == 'yes' ) {
            $hide_checkboxes_and_radios = value( $product_option, 'hide_checkboxes_and_radios' );
            if ( $hide_checkboxes_and_radios == 'yes' ) {
                print '<input type="hidden" class="box-effect-no-icon" value="yes" />';
            } else {
                print '<input type="hidden" class="box-effect" value="yes" />';
            }
        }
    }

    /**
     * Displays checkboxes option
     */
    function checkboxes_input( $name, $product_option, $value_chosen ) {
        $required = value( $product_option, 'required', false );
        $required_class = '';
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        $this->add_box_effect( $product_option );
        $options = value( $product_option, 'options', array() );
        if ( is_string( $value_chosen ) ) {
            $value_chosen = array( $value_chosen );
        }
        $this->max_and_min_selected( $product_option );
        $two_columns = value( $product_option, 'two-columns' );
        if ( !empty( $two_columns ) ) {
            print '<div class="two-column-product-option">';
        }
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                $label = $this->add_suboption_price_to_label( $option_id, $product_option, $option, value( $option, 'label', '' ), true );
                $option_for_id = $this->convert_name_to_id( $name . '[' . $option_id . ']' );
                print '<label for="' . $option_for_id . '" title="' . $this->get_message( $product_option, $option, $option_id, true ) . '"> ';
                $this->option_ajax_info( $option_id, $option );
                print '<input type="checkbox" id="' . $option_for_id . '" name="' . $name . '[' . $option_id . ']" class="checkboxes-checkbox ' . $required_class . '" value="' . $option_id . '" ';
                if ( in_array( $option_id, $value_chosen ) ) {
                    print 'checked="checked"';
                }
                print ' /> ';
                print $label . $this->get_message( $product_option, $option ) . '</label>';
                print '<br class="line-break-between-input-options">';
            }
        }
        if ( !empty( $two_columns ) ) {
            print '</div>';
        }
    }

    function convert_name_to_id( $name ) {
        return str_replace( ']', '-0-', str_replace( '[', '-9-', $name ) );
    }

    function get_message( $product_option, $option, $option_id = -1, $without_span = false ) {
        $tooltip = value( $product_option, 'tooltip' );
        if ( empty( $tooltip ) && $without_span ) {
            return '';
        } elseif ( !empty( $tooltip ) && !$without_span ) {
            return '';
        }
        $message = value( $option, 'message' );
        if ( !empty( $message ) ) {
            $src = value( $option, 'src' );
            $image = '';
            if ( !empty( $src ) ) {
                $image = '<img src="' . $src . '" />';
            }
            if ( $without_span ) {
                return str_replace( '"', '\"', $message );
            }
            return '<span class="product-option-message product-option-message-' . $option_id . '">' . $image . $message . '</span>';
        }
        return '';
    }

    /**
     * Displays radio buttons option
     */
    function radio_input( $name, $product_option, $value_chosen ) {
        $options = value( $product_option, 'options', array() );
        $required = value( $product_option, 'required', false );
        $required_class = '';
        if ( $required ) {
            $required_class = 'required-product-option';
        }
        $this->add_box_effect( $product_option );
        if ( !empty( $options ) ) {
            foreach ( $options as $option_id => $option ) {
                $label = $this->add_suboption_price_to_label( $option_id, $product_option, $option, value( $option, 'label', '' ), true );
                print '<label title="' . $this->get_message( $product_option, $option, $option_id, true ) . '">';
                $this->option_ajax_info( $option_id, $option );
                print '<input type="radio" name="' . $name . '" value="' . $option_id . '" ';
                if ( strval( $value_chosen ) == strval( $option_id ) ) {
                    print 'checked="checked"';
                }
                print ' class="' . $required_class . '" /> ' . $label;
                print $this->get_message( $product_option, $option ) . '</label>';
            }
        }
    }

    function image_upload_input( $name, $option, $value_chosen, $option_id, $post_id ) {
        $change_thumb = value( $option, 'change-thumb' );
        if ( !empty( $change_thumb ) ) {
            print '<input type="hidden" class="change-thumb" value="' . $change_thumb . '" />';
        }
        $default = value( $option, 'default' );
        $default_file_name = basename( $default );
        print '<input type="hidden" class="product-option-default-file-name" value="' . $default_file_name . '" />';
        $price_for_image = value( $option, 'price_for_image', 0 );
        $required_class = '';
        $required = value( $option, 'required', false );
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        print '<input type="hidden" class="product-option-price-for-image" value="' . $price_for_image . '" />';
        print '<input class="product-options-image-upload ' . $required_class . '" type="hidden" name="' . $name . '" value="' . $value_chosen . '" />'
                . '<iframe id="iframe-' . $option_id . '" style="width:90%;border:none;" scrolling="no" src="' . plugins_url() . '/woocommerce-product-options/includes/image-upload.php?post-id=' . $post_id . '&option-id=' . $option_id . '"></iframe><br>';
    }

    /**
     * Displays text option
     */
    function text_input( $name, $option, $value_chosen ) {
        $this->text_input_price_data( $option );
        $required = value( $option, 'required', false );
        $required_class = '';
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        print '<input class="product-option-value' . $required_class . '" type="text" name="' . $name . '" value="' . $value_chosen . '" />';
    }

    function text_input_price_data( $option ) {
        $price_if_not_empty = value( $option, 'price_if_not_empty', 0 );
        print '<input type="hidden" value="' . $price_if_not_empty . '" class="product-option-price-if-not-empty" />';
        $price_per_word = value( $option, 'price_per_word', 0 );
        print '<input type="hidden" value="' . $price_per_word . '" class="product-option-price-per-word" />';
        $price_per_character = value( $option, 'price_per_character', 0 );
        print '<input type="hidden" value="' . $price_per_character . '" class="product-option-price-per-character" />';
        $price_per_upper_case_letter = value( $option, 'price_per_upper_case_letter', 0 );
        print '<input type="hidden" value="' . $price_per_upper_case_letter . '" class="product-option-price-per-upper-case-character" />';
        $price_per_lower_case_letter = value( $option, 'price_per_lower_case_letter', 0 );
        print '<input type="hidden" value="' . $price_per_lower_case_letter . '" class="product-option-price-per-lower-case-character" />';
    }

    /**
     * Displays textarea option
     */
    function textarea_input( $name, $option, $value_chosen ) {
        $this->text_input_price_data( $option );
        $required = value( $option, 'required', false );
        $required_class = '';
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        print '<textarea class="product-option-value' . $required_class . '" name="' . $name . '">' . $value_chosen . '</textarea>';
    }

    /**
     * Displays datepicker option
     */
    function datepicker_input( $name, $option, $value_chosen ) {
        $display = value( $option, 'display' );
        if ( value( $option, 'default' ) === $value_chosen ) {
            $value_chosen = date( 'F j, Y', strtotime( value( $option, 'default' ) ) );
        }
        if ( $display == 'input' ) {
            print '<input type="text" class="product-option-input-datepicker product-option-value" name="' . $name . '" value="' . $value_chosen . '" />';
        } elseif ( $display == 'inline' ) {
            print '<div class="product-option-datepicker-div"><input type="hidden" class="name-of-input" value="' . $name . '" /></div>'
                    . '<input type="text" name="' . $name . '" value="' . $value_chosen . '" class="datepicker-value product-option-value" />';
        } else {
            print '<a class="datepicker-icon"><img src="' . plugins_url() . '/woocommerce-product-options/assets/images/datepicker-icon.png" /></a>'
                    . '<div class="product-option-datepicker-div"><input type="hidden" class="name-of-input" value="' . $name . '" /></div>'
                    . '<input type="text" name="' . $name . '" value="' . $value_chosen . '" class="datepicker-value datepicker-icon-value product-option-value" />';
        }
        print '<input type="hidden" class="date-chosen" value="' . $value_chosen . '" />';
        print '<input type="hidden" class="disallow-past" value="' . value( $option, 'disallow_past' ) . '" />';
        print '<input type="hidden" class="disallow-today" value="' . value( $option, 'disallow_today' ) . '" />';
        print '<input type="hidden" class="disallow-weekends" value="' . value( $option, 'disallow_weekends' ) . '" />';
        $locale = get_locale();
        if ( $locale != '' ) {
            $locale = str_replace( '_', '-', $locale );
            if ( !file_exists( dirname( __FILE__ ) . '/../assets/js/globalization/datepicker-' . $locale . '.js' ) ) {
                $locale = substr( $locale, 0, strpos( $locale, '-' ) );
                if ( !file_exists( dirname( __FILE__ ) . '/../assets/js/globalization/datepicker-' . $locale . '.js' ) ) {
                    $locale = '';
                } else {
                    wp_enqueue_script( 'jqueryui-datepicker-' . $locale, plugins_url() . '/woocommerce-product-options/assets/js/globalization/datepicker-' . $locale . '.js' );
                }
            } else {
                wp_enqueue_script( 'jqueryui-datepicker-' . $locale, plugins_url() . '/woocommerce-product-options/assets/js/globalization/datepicker-' . $locale . '.js' );
            }
        }
        print '<input type="hidden" class="locale" value="' . $locale . '" />';
        wp_enqueue_style( 'jquery-ui-datepicker-css', plugins_url() . '/woocommerce-product-options/assets/css/jquery-ui.css' );
    }

    /**
     * Displays checkbox option
     */
    function checkbox_input( $name, $option, $value_chosen ) {
        $required = value( $option, 'required', false );
        $checked_price = value( $option, 'checked_price', 0 );
        $unchecked_price = value( $option, 'unchecked_price', 0 );
        print '<input type="hidden" class="product-option-checked-price" value="' . $checked_price . '" />';
        print '<input type="hidden" class="product-option-unchecked-price" value="' . $unchecked_price . '" />';
        $required_class = '';
        if ( $required ) {
            $required_class = ' required-product-option';
        }
        print '<input class="product-option-value product-option-checkbox ' . $required_class . '" type="checkbox" name="' . $name . '" value="yes" ';
        if ( $value_chosen == 'yes' ) {
            print ' checked="checked" ';
        }
        print '/>';
        $shows = value( $option, 'shows' );
        print '<div class="show-product-options">';
        if ( !empty( $shows ) ) {
            foreach ( $shows as $show_id => $show ) {
                print '<input class="show-product-option" type="hidden" value="' . $show_id . '" />';
            }
        }
        print '</div>';
        $shows = value( $option, 'hides' );
        print '<div class="hide-product-options">';
        if ( !empty( $shows ) ) {
            foreach ( $shows as $show_id => $show ) {
                print '<input class="hide-product-option" type="hidden" value="' . $show_id . '" />';
            }
        }
        print '</div>';
    }

}

$woocommerce_product_options_product_frontend = new Woocommerce_Product_Options_Product_Frontend();
?>