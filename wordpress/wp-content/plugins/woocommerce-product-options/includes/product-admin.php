<?php

class Woocommerce_Product_Options_Product_Admin {

    //Creates object
    function __construct() {
        //Add fields to store in admin
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
        //Save fields
        add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );
    }

    function kses_array( $string ) {
        if ( is_string( $string ) ) {
            $string = wp_kses_data( $string );
        }
        return $string;
    }

    /**
     * Saves options
     */
    function save_meta_boxes( $post_id, $post ) {
        if ( ( ($post->post_type != 'product' && $post->post_type != 'product_option_group'
                && $post->post_type != 'order_option_group') ) || empty( $_POST[ 'saving_product_options' ] ) ) {
            return;
        }
        if ( empty( $_POST[ 'saving_options' ] ) ) {
            return;
        }
        $value = array();
        $pairs = explode( '&', file_get_contents( "php://input" ) );
        $posted_data = array();
        foreach ( $pairs as $pair ) {
            $parsed_string = array();
            parse_str( $pair, $parsed_string );
            if ( !empty( $parsed_string ) && is_array( $parsed_string ) ) {
                foreach ( $parsed_string as $parsed_key_1 => $parsed_value_1 ) {
                    if ( !empty( $parsed_value_1 ) && is_array( $parsed_value_1 ) ) {
                        if ( !isset( $posted_data[ $parsed_key_1 ] ) ) {
                            $posted_data[ $parsed_key_1 ] = array();
                        }
                        foreach ( $parsed_value_1 as $parsed_key_2 =>
                                    $parsed_value_2 ) {
                            if ( !empty( $parsed_value_2 ) && is_array( $parsed_value_2 ) ) {
                                if ( !isset( $posted_data[ $parsed_key_1 ][ $parsed_key_2 ] ) ) {
                                    $posted_data[ $parsed_key_1 ][ $parsed_key_2 ]
                                            = array();
                                }
                                foreach ( $parsed_value_2 as $parsed_key_3 =>
                                            $parsed_value_3 ) {
                                    if ( !empty( $parsed_value_3 ) && is_array( $parsed_value_3 ) ) {
                                        if ( !isset( $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ] ) ) {
                                            $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ]
                                                    = array();
                                        }
                                        foreach ( $parsed_value_3 as
                                                    $parsed_key_4 =>
                                                    $parsed_value_4 ) {
                                            if ( !empty( $parsed_value_4 ) && is_array( $parsed_value_4 ) ) {
                                                if ( !isset( $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ] ) ) {
                                                    $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ]
                                                            = array();
                                                }
                                                foreach ( $parsed_value_4 as
                                                            $parsed_key_5 =>
                                                            $parsed_value_5 ) {
                                                    if ( !empty( $parsed_value_5 )
                                                            && is_array( $parsed_value_5 ) ) {
                                                        if ( !isset( $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ][ $parsed_key_5 ] ) ) {
                                                            $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ][ $parsed_key_5 ]
                                                                    = array();
                                                        }
                                                        foreach ( $parsed_value_5 as
                                                                    $parsed_key_6 =>
                                                                    $parsed_value_6 ) {
                                                            $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ][ $parsed_key_5 ][ $parsed_key_6 ]
                                                                    = $parsed_value_6;
                                                        }
                                                    } else {
                                                        $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ][ $parsed_key_5 ]
                                                                = $parsed_value_5;
                                                    }
                                                }
                                            } else {
                                                $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ][ $parsed_key_4 ]
                                                        = $parsed_value_4;
                                            }
                                        }
                                    } else {
                                        $posted_data[ $parsed_key_1 ][ $parsed_key_2 ][ $parsed_key_3 ]
                                                = $parsed_value_3;
                                    }
                                }
                            } else {
                                $posted_data[ $parsed_key_1 ][ $parsed_key_2 ] = $parsed_value_2;
                            }
                        }
                    } else {
                        $posted_data[ $parsed_key_1 ] = $parsed_value_1;
                    }
                }
            }
        }
        if ( isset( $posted_data[ 'backend-product-options' ] ) ) {
            $value = $posted_data[ 'backend-product-options' ];
        } else {
            $value = value( $_POST, 'backend-product-options', array() );
        }
        $value = array_map( array( $this, 'kses_array' ), $value );
        if ( !add_post_meta( $post_id, 'backend-product-options', $value, true ) ) {
            update_post_meta( $post_id, 'backend-product-options', $value );
        }
        $value = array();
        if ( isset( $_POST[ 'product-options-settings' ] ) ) {
            $value = $_POST[ 'product-options-settings' ];
        }
        $value = array_map( array( $this, 'kses_array' ), $value );
        if ( !add_post_meta( $post_id, 'product-options-settings', $value, true ) ) {
            update_post_meta( $post_id, 'product-options-settings', $value );
        }
    }

    /**
     * Loads js and css
     */
    function admin_scripts( $base_id = 'backend-product-options' ) {
        $localize_array = array();
        $id = 'product_option_id';
        $meta = array();
        
        ob_start();
        $this->before_input( $id, array( 'type' => 'radio' ) );
        $this->radio_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'radio' ) );
        $localize_array[ 'radio' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'checkboxes' ) );
        $this->checkboxes_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'checkboxes' ) );
        $localize_array[ 'checkboxes' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'radio_image' ) );
        $this->radio_image_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'radio_image' ) );
        $localize_array[ 'radio_image' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'image_upload' ) );
        $this->image_upload_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'image_upload' ) );
        $localize_array[ 'image_upload' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'text' ) );
        $this->text_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'text' ) );
        $localize_array[ 'text' ] = ob_get_clean();
        
        ob_start();
        $this->before_input( $id, array( 'type' => 'color_picker' ) );
        $this->color_picker_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'color_picker' ) );
        $localize_array[ 'color_picker' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'checkbox' ) );
        $this->checkbox_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'checkbox' ) );
        $localize_array[ 'checkbox' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'textarea' ) );
        $this->textarea_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'textarea' ) );
        $localize_array[ 'textarea' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'datepicker' ) );
        $this->datepicker_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'datepicker' ) );
        $localize_array[ 'datepicker' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'select' ) );
        $this->select_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'select' ) );
        $localize_array[ 'select' ] = ob_get_clean();
        
        ob_start();
        $this->before_input( $id, array( 'type' => 'combobox' ) );
        $this->select_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'combobox' ) );
        $localize_array[ 'combobox' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'multi_select' ) );
        $this->multi_select_input( $base_id . '[product_option_id]', array() );
        $this->after_input( $id, array( 'type' => 'multi_select' ) );
        $localize_array[ 'multi_select' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'number' ) );
        $this->numeric_input( $base_id . '[product_option_id]', array(),
                'number' );
        $this->after_input( $id, array( 'type' => 'number' ) );
        $localize_array[ 'number' ] = ob_get_clean();
        
        ob_start();
        $this->before_input( $id, array( 'type' => 'range' ) );
        $this->numeric_input( $base_id . '[product_option_id]', array(), 'range' );
        $this->after_input( $id, array( 'type' => 'range' ) );
        $localize_array[ 'range' ] = ob_get_clean();

        ob_start();
        $this->before_input( $id, array( 'type' => 'number_as_text' ) );
        $this->numeric_input( $base_id . '[product_option_id]', array(),
                'number_as_text' );
        $this->after_input( $id, array( 'type' => 'number_as_text' ) );
        $localize_array[ 'number_as_text' ] = ob_get_clean();

        ob_start();
        $this->input_option( $base_id . '[product_option_id]', 'option_id',
                array(), '' );
        do_action( 'woocommerce_product_options_backend_after_input_option',
                $base_id . '[product_option_id]', 'option_id', array(), '',
                array() );
        $localize_array[ 'input_option' ] = ob_get_clean();

        ob_start();
        $this->image_input_option( $base_id . '[product_option_id]',
                'option_id', array(), '' );
        do_action( 'woocommerce_product_options_backend_after_image_input_option',
                $base_id . '[product_option_id]', 'option_id', array(), '',
                array() );
        $localize_array[ 'image_input_option' ] = ob_get_clean();

        wp_enqueue_script( 'jquery' );
        if ( function_exists( 'wp_enqueue_media' ) && !did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'woocommerce-product-options-backend',
                plugins_url() . '/woocommerce-product-options/assets/js/product-options-backend.js',
                array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker',
            'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable' ),
                false, true );
        wp_localize_script( 'woocommerce-product-options-backend',
                'woocommerce_product_options_backend_settings', $localize_array );
        wp_register_style( 'woocommerce-product-options-backend-css',
                plugins_url() . '/woocommerce-product-options/assets/css/product-options-backend.css' );
        wp_enqueue_style( 'woocommerce-product-options-backend-css' );

        wp_enqueue_style( 'vc-woocommerce-add-on-product-options',
                plugins_url() . '/woocommerce-product-options/assets/css/vc-woocommerce-add-on.css' );
        wp_enqueue_script( 'vc-woocommerce-add-on-product-options',
                plugins_url() . '/woocommerce-product-options/assets/js/vc-woocommerce-add-on.js' );
        global $woocommerce_product_options;
        $woocommerce_product_options->frontend_scripts();
    }

    /**
     * Adds options to product
     */
    function add_meta_boxes( $post_type, $post ) {
        add_meta_box(
                'woocommerce-product-options',
                __( 'Product Options', 'woocommerce-product-options' ),
                array( $this, 'add_meta_box' ), 'product', 'normal', 'low'
        );
    }

    function escape_html_tags( $array_or_string ) {
        if ( is_string( $array_or_string ) ) {
            return str_replace( '"', "&quot;",
                    str_replace( '<', '&lt;', $array_or_string ) );
        }
        return array_map( array( $this, 'escape_html_tags' ), $array_or_string );
    }

    /**
     * Adds options to product
     */
    function add_meta_box( $post ) {
        //Add existing options
        print '<input type="hidden" name="saving_product_options" value="yes" /> ';
        $product_meta_settings = get_post_meta( $post->ID,
                'product-options-settings', true );
        $show_in_list = value( $product_meta_settings, 'show_in_list', '' );
        $accordion = value( $product_meta_settings, 'accordion', '' );
        $accordion_keep_open_on_select = value( $product_meta_settings,
                'accordion_keep_open_on_select' );
        $accordion_keep_open = value( $product_meta_settings,
                'accordion_keep_open' );
        $revert_options = value( $product_meta_settings, 'revert_options', '' );
        $above_add_to_cart = value( $product_meta_settings, 'above_add_to_cart',
                '' );
        $in_variations_add_to_cart = value( $product_meta_settings,
                'in_variations_add_to_cart', '' );
        print '<table width="100%">'
                . '<tr class=""><td><label>'
                . __( 'Text to display using price using shortcode [price] if this is a product',
                        'woocommerce-product-options' ) . '<br>' .
                '<input type="text" name="product-options-settings[price_text]" '
                . ' value="' . value( $product_meta_settings, 'price_text',
                        '[price]' ) . '" '
                . ' placeholder="' . __( 'only [price]',
                        'woocommerce-product-options' ) . '" ';
        print ' />' . '</label><br></td></tr>'
                . '<tr class="checkboxes-rules"><td><label>'
                . __( 'Price per checkbox (counting the total number of checkboxes within the checkboxes in this post):',
                        'woocommerce-product-options' ) . '<br>' .
                '<input type="number" step="1" name="product-options-settings[cost_per_checkbox]" '
                . ' value="' . value( $product_meta_settings,
                        'cost_per_checkbox' ) . '" ';
        print ' />' . '</label><br></td></tr>';
        print '<tr class="checkboxes-rules"><td><label>'
                . __( 'Minimum number of checkboxes before cost per checkbox:',
                        'woocommerce-product-options' ) . '<br>' .
                '<input type="number" step="1" name="product-options-settings[minimum_number_of_checkboxes_before_cost]" '
                . ' value="' . value( $product_meta_settings,
                        'minimum_number_of_checkboxes_before_cost' ) . '" ';
        print ' />' . '</label><br></td></tr>';
        print '<tr class="order-group-option-hide"><td>';
        $location_of_options = value( $product_meta_settings,
                'location_of_options', 'above_add_to_cart' );
        if ( empty( $product_meta_settings[ 'location_of_options' ] ) ) {
            if ( value( $product_meta_settings, 'in_variations_add_to_cart' ) == 'yes' ) {
                $location_of_options = 'in_variations_add_to_cart';
            } elseif ( value( $product_meta_settings, 'above_add_to_cart' ) != 'yes' ) {
                $location_of_options = 'below_add_to_cart';
            }
        }
        $options = array(
            'above_add_to_cart' => __( 'Above the add to cart button', 'woocommerce-product-options' ),
            'below_add_to_cart' => __( 'Below the add to cart button', 'woocommerce-product-options' ),
            'in_variations_add_to_cart' => __( 'Between the variations and the add to cart button (for variable products)',
                    'woocommerce-product-options' ),
        );
        print __( 'Where would you like the options to be displayed?',
                        'woocommerce-product-options' ) . '<br>';
        wpshowcase_select( $options, $location_of_options, '',
                'product-options-settings[location_of_options]' );
        print '</td></tr>';
        /* print '<tr class="order-group-option-hide"><td><label><input type="checkbox" value="yes" name="product-options-settings[in_variations_add_to_cart]" ';
          if ( $in_variations_add_to_cart == 'yes' ) {
          print 'checked="checked"';
          }
          print ' />' . __( 'Display product options between the variations and the ADD TO CART button', 'woocommerce-product-options' ) . '</label><br></td></tr>';
          print '<tr class="order-group-option-hide"><td><label><input type="checkbox" value="yes" name="product-options-settings[above_add_to_cart]" ';
          if ( $above_add_to_cart == 'yes' ) {
          print 'checked="checked"';
          }
          print ' />' . __( 'Display product options above the ADD TO CART button', 'woocommerce-product-options' ) . '</label><br></td></tr>';
         */
        print '<tr class="order-group-option-hide"><td><label><input type="checkbox" value="yes" name="product-options-settings[show_in_list]" ';
        if ( $show_in_list == 'yes' ) {
            print 'checked="checked"';
        }
        print ' />' . __( 'Add product options to Shop and Search Result pages?',
                        'woocommerce-product-options' ) . '</label><br></td></tr>';
        print '<tr><td><label><input type="checkbox" value="yes" name="product-options-settings[accordion_keep_open]" ';
        if ( $accordion_keep_open == 'yes' ) {
            print 'checked="checked"';
        }
        print ' />' . __( 'Allow more than one option to be open in the accordion?',
                        'woocommerce-product-options' ) . '</label><br></td></tr>';
        print '<tr><td><label><input type="checkbox" value="yes" name="product-options-settings[accordion_keep_open_on_select]" ';
        if ( $accordion_keep_open_on_select == 'yes' ) {
            print 'checked="checked"';
        }
        print ' />' . __( 'Keep the accordion of the option open when a value for the option is selected?',
                        'woocommerce-product-options' ) . '</label><br></td></tr>';
        print '<tr><td><label><input type="checkbox" value="yes" name="product-options-settings[accordion]" ';
        if ( $accordion == 'yes' ) {
            print 'checked="checked"';
        }
        print ' />' . __( 'Accordion all the options (overrides whether or not each option is accordioned)?',
                        'woocommerce-product-options' ) . '</label><br></td></tr>';
        print '<tr><td><label><input type="checkbox" value="yes" name="product-options-settings[revert_options]" ';
        if ( $revert_options == 'yes' ) {
            print 'checked="checked"';
        }
        print ' />' . __( 'Revert options to default values when customer adds product to cart',
                        'woocommerce-product-options' ) . '</label><br></td></tr>';
        print '<tr><td></td></tr>';
        print '<tr><td id="options-droppable" class="backend-product-options"><input type="hidden" name="saving_options" value="yes" />'
                . '<div class="backend-placeholder">' . __( 'To add a new option, please drag-and-drop an option from below to this area.',
                        'woocommerce-product-options' ) . '</div>';
        $product_meta = get_post_meta( $post->ID, 'backend-product-options',
                true );
        if ( !empty( $product_meta ) && is_array( $product_meta ) ) {
            $product_meta = array_map( array( $this, 'escape_html_tags' ),
                    $product_meta );
        }
        if ( !empty( $product_meta ) ) {
            foreach ( $product_meta as $id => $meta ) {
                if( !is_numeric( $id ) ) {
                    continue;
                }
                $meta[ 'product_option_id' ] = $id;
                $this->product_meta( $id, $meta );
            }
        }
        //Add options to drag and drop
        print '</td></tr>';
        $this->add_drag_and_drop_box();
        print '</table>';
        do_action( 'woocommerce_product_options_backend_after_drag_and_drop_table' );
    }

    function add_drag_and_drop_box() {
        print '<tr><td id="option-draggable">';
        $this->widget( 'range' );
        $this->widget( 'number' );
        $this->widget( 'number_as_text' );
        $this->widget( 'select' );
        $this->widget( 'combobox' );
        $this->widget( 'multi_select' );
        $this->widget( 'radio' );
        $this->widget( 'checkboxes' );
        $this->widget( 'radio_image' );
        $this->widget( 'image_upload' );
        $this->widget( 'text' );
        $this->widget( 'color_picker' );
        $this->widget( 'checkbox' );
        $this->widget( 'textarea' );
        $this->widget( 'datepicker' );
        print '</td></tr>';
        $this->admin_scripts();
    }
    
    
    function get_option_label( $type ) {
        if ( $type == 'radio' ) {
            return __( 'radio buttons', 'qwer' );
        } elseif ( $type == 'radio_image' ) {
            return __( 'image options', 'qwer' );
        } elseif ( $type == 'select' ) {
            return __( 'dropdown', 'qwer' );
        } else {
            return str_replace( '_', ' ', $type );
        }
    }

    /**
     * A widget on the product page that can be dragged and dropped
     */
    function widget( $type ) {
        print '<div class="backend-' . $type . ' backend-widget-type-outer">'
                . '<input type="hidden" class="backend-widget-type" value="' . $type . '" />'
        ;
        print $this->get_option_label( $type );
        print '<br><img src="' . $this->dir() . '/../assets/images/' . $type . '.png" />' .
                '</div>';
    }

    /**
     * An option
     */
    function product_meta( $id, $meta ) {
        if ( empty( $meta[ 'type' ] ) ) {
            print 'Meta has no type!!!';
            return;
        }
        $type = $meta[ 'type' ];
        $this->before_input( $id, $meta );
        $name = 'backend-product-options[' . $id . ']';
        if ( $type == 'radio' ) {
            $this->radio_input( $name, $meta );
        } elseif ( $type == 'checkboxes' ) {
            $this->checkboxes_input( $name, $meta );
        } elseif ( $type == 'radio_image' ) {
            $this->radio_image_input( $name, $meta );
        } elseif ( $type == 'image_upload' ) {
            $this->image_upload_input( $name, $meta );
        } elseif ( $type == 'select' ) {
            $this->select_input( $name, $meta );
        } elseif ( $type == 'combobox' ) {
            $this->select_input( $name, $meta );
        } elseif ( $type == 'multi_select' ) {
            $this->multi_select_input( $name, $meta );
        } elseif ( $type == 'number_as_text' || $type == 'range' || $type == 'number' ) {
            $this->numeric_input( $name, $meta, $type );
        } elseif ( $type == 'checkbox' ) {
            $this->checkbox_input( $name, $meta );
        } elseif ( $type == 'text' ) {
            $this->text_input( $name, $meta );
        } elseif ( $type == 'color_picker' ) {
            $this->color_picker_input( $name, $meta );
        } elseif ( $type == 'textarea' ) {
            $this->textarea_input( $name, $meta );
        } elseif ( $type == 'datepicker' ) {
            $this->datepicker_input( $name, $meta );
        }
        $this->after_input( $id, $meta );
    }

    /**
     * Before the option
     */
    function before_input( $id, $meta ) {
        print '<div class="backend-product-option backend-product-option-' . $id . ' backend-product-option-' . value( $meta,
                        'type' ) . '">';
        ?>
        <h2 ><?php print $this->get_option_label( value( $meta, 'type', '' ) ); ?> : <span class="backend-option-title"><?php
                       print value( $meta, 'title', '' );
                       ?></span> <span class="backend-expand-hide">-</span></h2>
        <div class="backend-product-option-toggle"><div class="backend-outside-preview">
                       <?php
                       print '<input type="hidden" class="backend-product-option-id" value="' . $id . '" />' .
                               '<input type="hidden" name="backend-product-options[' . $id .
                               '][type]" value="' . value( $meta, 'type', '' ) . '" />';
                       ?>
                <a class="backend-remove-product-option button"><?php
               _e( 'Remove option', 'woocommerce-product-options' );
               ?></a>
                <br>
                <label for="backend-product-options[<?php print $id; ?>][title]"><?php
                    _e( 'Option Title', 'woocommerce-product-options' );
                    ?></label><br>
                <input class="backend-product-option-title" name="backend-product-options[<?php print $id; ?>][title]" value="<?php
                           print value( $meta, 'title', '' );
                           ?>" placeholder="<?php
                    _e( 'Title', 'woocommerce-product-options'
                    );
                    ?>   " type="text" />
                <br>
                <label for="backend-product-options[<?php print $id; ?>][label-before]"><?php
                    _e( 'Label', 'woocommerce-product-options'
                    );
                    ?></label><br>
                <input name="backend-product-options[<?php print $id; ?>][label-before]" value="<?php
                           print value( $meta, 'label-before', '' );
                           ?>" placeholder="<?php
            _e( 'Label before option (appears under title)',
                    'woocommerce-product-options' );
            ?>" type="text" />
                <br>
                <label for="backend-product-options[<?php print $id; ?>][label-after]"><?php
                    _e( 'Units (label that appears after option)',
                            'woocommerce-product-options'
                    );
                    ?></label><br>
                <input name="backend-product-options[<?php print $id; ?>][label-after]" value="<?php
                print value( $meta, 'label-after', '' );
                ?>" placeholder="<?php
                _e( 'Label after option - use for units (e.g. cm/km) or checkbox labels',
                        'woocommerce-product-options' );
                ?>" type="text" />
                <?php
            }

            /**
             * After the option
             */ function after_input( $id, $meta ) {
                $dropdown_options = array(
                    'no' => __( 'Do not display price on option',
                            'woocommerce-product-options' ),
                    'yes' => __( 'Show price of option (e.g. +$1 if this option adds $1 to the price of the product)?',
                            'woocommerce-product-options' ),
                );
                $type = value( $meta, 'type' );
                if ( $type === 'radio_image' || $type == 'radio' || $type == 'checkboxes'
                        || $type == 'select' || $type == 'multi_select' ) {
                    $dropdown_options[ 'total' ] = __( 'Show total price of option and product (e.g. $15 if this option adds $5 to the price of a $10 product)?',
                            'woocommerce-product-options' );
                }
                print '<br>';
                wpshowcase_dropdown( value( $meta, 'show-price' ),
                        array(
                    'label' => __( 'Display the price of this option?',
                            'woocommerce-product-options' ),
                    'options' => $dropdown_options,
                    'name' => 'backend-product-options[' . $id . '][show-price]',
                ) );
                ?>
                <br>
                <label for="backend-product-options[<?php print $id; ?>][hide-free]" class="hide-free-wrapper">
                    <input name="backend-product-options[<?php print $id; ?>][hide-free]" value="yes" <?php
                if ( value( $meta, 'hide-free', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Display price as "0.00" when price is 0 instead of "Free"?',
                        'woocommerce-product-options' );
                ?></label>
                <br>
                <label for="backend-product-options[<?php print $id; ?>][show-price-in-cart]">
                    <input name="backend-product-options[<?php print $id; ?>][show-price-in-cart]" value="yes" <?php
                if ( value( $meta, 'show-price-in-cart', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Show price in the cart?', 'woocommerce-product-options' );
                ?></label>
                <br>
                <label for="backend-product-options[<?php print $id; ?>][accordion]">
                    <input name="backend-product-options[<?php print $id; ?>][accordion]" value="yes" <?php
                if ( value( $meta, 'accordion', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Accordion this option?', 'woocommerce-product-options' );
                ?>   </label><br>   
                <?php
                print '<br><label>' . __( 'HTML to add before the option:',
                                'woocommerce-product-options' ) . '<br>';
                $html_before_option = value( $meta, 'html-before-option' );
                print '<textarea name="backend-product-options[' . $id . '][html-before-option]" '
                        . 'placeholder="&lt;h3>' . __( 'My Title',
                                'woocommerce-product-options' ) . '&lt/h3>&lt;div class=\'column-1\'>" >';
                print $html_before_option . '</textarea></label><br>';
                print '<br><label>' . __( 'HTML to add after the option',
                                'woocommerce-product-options' ) . '<br>';
                $html_after_option = value( $meta, 'html-after-option' );
                print '<textarea name="backend-product-options[' . $id . '][html-after-option]" placeholder="&lt;/div>" >';
                print $html_after_option . '</textarea></label><br>';
                print '</div><!--h2>' . __( 'Preview (Not Fully Functional)',
                                'woocommerce-product-options' )
                        . '</h2><div class="backend-product-option-preview"></div--></div></div>';
            }

            function maximum_and_minimum( $name, $meta ) {
                print '<div class="maximum-and-minimum">';
                $min_selected = value( $meta, 'min_selected' );
                print '<br><label>' . __( 'Minimum number of checkboxes that can be selected at the same time',
                                'woocommerce-product-options' ) .
                        '<br> <input type="number" name="' . $name . '[min_selected]" value="' . $min_selected . '"/></label>';
                $max_selected = value( $meta, 'max_selected' );
                print '<br><label>' . __( 'Maximum number of checkboxes that can be selected at the same time',
                                'woocommerce-product-options' ) .
                        '<br> <input type="number" name="' . $name . '[max_selected]" value="' . $max_selected . '"/></label>';
                print '</div>';
            }

            /**
             * Displays checkboxes option admin
             */
            function checkboxes_input( $name, $meta ) {
                $this->tooltip( $name, $meta );
                $this->box_effect( $name, $meta );
                $this->maximum_and_minimum( $name, $meta );
                $this->input_options( $name, $meta, true );
            }

            function yes_no_options() {
                return array(
                    'yes' => __( 'Yes', 'woocommerce-product-options' ),
                    'no' => __( 'No', 'woocommerce-product-options' ),
                );
            }

            function box_effect( $name, $meta ) {
                print '<br>';
                print '<label>' . __( 'Display a box effect around radio/checkbox buttons:',
                    'woocommerce-product-options' ) . '<br>';
                wpshowcase_select( $this->yes_no_options(),
                        value( $meta, 'box_effect', 'no' ), '',
                        $name . '[box_effect]',
                        array( 'class' => 'box-effect-select' ) ) . '</label>';
                print '<div class="box-effect-options"><label>';
                print __( 'Hide the radio/checkbox buttons:',
                                'woocommerce-product-options' ) . '<br>';
                wpshowcase_select( $this->yes_no_options(),
                        value( $meta, 'hide_checkboxes_and_radios', 'no' ), '',
                        $name . '[hide_checkboxes_and_radios]' );
                print '</label></div>';
            }

            /**
             * Displays radio option admin
             */ function radio_input( $name,
                    $meta
            ) {
                $this->tooltip( $name, $meta );
                $this->box_effect( $name, $meta );
                $this->input_options( $name, $meta );
            }

            /**
             * Displays image options admin
             */ function radio_image_input( $name,
                    $meta ) {
                $options = value( $meta, 'options', array() );
                $default = value( $meta, 'default', array() );
                $display_labels_in_cart = value( $meta, 'display_labels_in_cart' );
                ?> 
                <br>
                <label for="<?php print $name; ?>[multiple]">
                    <input class="allow-multiple" name="<?php print $name; ?>[multiple]" value="yes" <?php
                if ( value( $meta, 'multiple', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" class="allow-multiple-options" />
                <?php
                _e( 'Allow multiple options to be selected?',
                        'woocommerce-product-options' );
                ?></label><br>
                <?php
                $this->maximum_and_minimum( $name, $meta );
                ?>
                <br>
                <label for="<?php print $name; ?>[change-thumb-on-select]">
                    <input name="<?php print $name; ?>[change-thumb-on-select]" value="yes" <?php
                if ( value( $meta, 'change-thumb-on-select', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Change the thumbnail to the selected image?',
                        'woocommerce-product-options' );
                ?></label><br>
                <label for="<?php print $name; ?>[show-option-prices]">
                    <input name="<?php print $name; ?>[show-option-prices]" value="yes" <?php
                if ( value( $meta, 'show-option-prices', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Show suboption prices?', 'woocommerce-product-options' );
                ?></label><br>
                <?php
                $this->required( $name, $meta );

                print '<br><label>' .
                        '<br> <input name="' . $name . '[display_labels_in_cart]" type="checkbox" class="" value="yes" ';
                if ( !empty( $display_labels_in_cart ) ) {
                    print ' checked="checked" ';
                }
                print '/>' . __( 'Display a label instead of an image in the cart/checkout/emails',
                                'woocommerce-product-options' ) . '</label><br>';

                $no_default = value( $meta, 'no_default', '' );
                print '<br><label> ' .
                        ' <input name="' . $name . '[no_default]" type="checkbox" class="backend-no-default-checkbox" value="yes" ';
                if ( !empty( $no_default ) ) {
                    print ' checked="checked" ';
                }
                print '/>
                ' . __( 'No default option',
                                'woocommerce-product-options' ) . '</label>';

                $this->tooltip( $name, $meta );
                print '<table class="backend-options" width="100%">';
                print '<tr><th>' . __( 'Image', 'woocommerce-product-options' ) . '</th><th>' . __(
                                'Label', 'woocommerce-product-options' ) . '</th><th>' . __( 'Price',
                                'woocommerce-product-options' ) . '</th>' .
                        '<th class="default-column">' . __( 'Default',
                                'woocommerce-product-options' ) . '</th>'
                        . '<th>' . __( 'Text to display when selected',
                                'woocommerce-product-options' ) . '</th>'
                        . '<th></th></tr>';
                if ( !empty( $options ) ) {
                    foreach ( $options as $option_id => $option ) {
                        $this->image_input_option( $name, $option_id, $option,
                                $default );
                        do_action( 'woocommerce_product_options_backend_after_image_input_option',
                                $name, $option_id, $option, $default, $meta );
                    }
                }
                print '</table>';
                print '<br><a href="#" class="backend-add-new-image-option button">' . __(
                                'Add New Suboption',
                                'woocommerce-product-options' ) . '</a>';
            }

            /**
             * Displays one image option admin
             */
            function image_input_option( $name, $option_id, $option, $default ) {
                if ( !is_array( $default ) ) {
                    if ( !empty( $default ) ) {
                        $default = array( $default => $default );
                    } else {
                        $default = array();
                    }
                }
                $src = value( $option, 'src', '' );
                $id = value( $option, 'id', '' );
                if ( !empty( $option[ 'id' ] ) ) {
                    $src_array = wp_get_attachment_image_src( $option[ 'id' ] );
                    if ( !empty( $src_array ) ) {
                        $src = value( $src_array, 0 );
                    }
                }
                $label = value( $option, 'label', '' );
                $price = value( $option, 'price', '' );
                $message = value( $option, 'message' );
                $option_name = $name . '[options][' . $option_id . ']';
                print '<tr class="backend-option backend-option-' . $option_id . '"><td>';
                $upload_image = __( 'Choose image',
                        'woocommerce-product-options' );
                print '<div class="backend-upload-image">';
                print '<a href="#" class="backend-upload-image-button">
				<span class="backend-upload-image-container">';
                $src_of_img = $src;
                if ( empty( $src_of_img ) ) {
                    $src_of_img = $this->dir() . '/../assets/images/blank-upload.png';
                }
                print '<img class="backend-image-upload" src="' . $src_of_img . '" /><br>';
                if ( $src != '' ) {
                    $upload_image = __( 'Choose a different image',
                            'woocommerce-product-options' );
                }
                print '</span>';
                print $upload_image . '</a>
			<input class="backend-upload-image-src" type="hidden" name="' . $option_name . '[src]" value="' . $src . '">
			<input class="backend-upload-image-id" type="hidden" name="' . $option_name . '[id]" value="' . $id . '">
			</div>';
                print '</td><td><input type="hidden" class="backend-option-id" value="' . $option_id . '" />'
                        . '<input type="text" name="' . $option_name . '[label]" value="' . $label . '"/></td>'
                        . '<td><input step="0.01" type="number" name="' . $option_name . '[price]" value="' . $price . '"/></td>'
                        . '<td class="default-column"><input type="checkbox" name="' . $name . '[default][' . $option_id . ']" class="backend-option-default" value="' . $option_id . '" ';
                if ( !empty( $default[ $option_id ] ) ) {
                    print ' checked="checked" ';
                }
                print ' /></td>';
                print '<td><input type="text" placeholder="' . __( 'Selected text',
                                'woocommerce-product-options' ) . '" name="' . $option_name . '[message]" value="' . $message . '" />';
                print '<td><a href="#" class="backend-remove-option button">' . __( 'Remove option',
                                'woocommerce-product-options' ) . '</a></td></tr>';
            }

            /**
             * Displays image upload option admin
             */
            function image_upload_input( $name, $meta ) {
                $this->required( $name, $meta );
                print '<p>' . __( 'Uploaded images might be visible to everyone looking at your website.',
                                'woocommerce-product-options' ) . '</p>';
                ?><br><label for="<?php print $name; ?>[change-thumb]">
                    <input name="<?php print $name; ?>[change-thumb]"
                           id="<?php print $name; ?>[change-thumb]" value="yes" <?php
                    if ( value( $meta, 'change-thumb', '' ) === 'yes' ) {
                        print ' checked="checked" ';
                    }
                    ?> type="checkbox" />
                           <?php
                           _e( 'Change thumbnail to uploaded image?',
                                   'woocommerce-product-options' );
                           ?></label><br>
                <!--label for="<?php print $name; ?>[multiple]">
                    <input name="<?php print $name; ?>[multiple]"
                           id="<?php print $name; ?>[multiple]" value="yes" <?php
                if ( value( $meta, 'multiple', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Change thumbnail to uploaded image?',
                        'woocommerce-product-options' );
                ?></label><br-->
                <?php
                $upload_image = __( 'Choose a default image',
                        'woocommerce-product-options' );
                $default = value( $meta, 'default',
                        $this->dir() . '/../assets/images/blank-upload.png' );
                $price_for_image = value( $meta, 'price_for_image', 0 );
                print '<p>' . __( 'Price for uploading an image?',
                                'woocommerce-product-options' ) . '</p>';
                print '<input type="number" value="' . $price_for_image . '" name="' . $name . '[price_for_image]" step="0.001" />';
                print '<div class="backend-upload-image">';
                print '<a href="#" class="backend-upload-image-button">
				<span class="backend-upload-image-container">';
                $src_of_img = $default;
                if ( empty( $src_of_img ) ) {
                    $src_of_img = $this->dir() . '/../assets/images/blank-upload.png';
                    $default = $src_of_img;
                }
                print '<img class="backend-image-upload" src="' . $src_of_img . '" /><br>';
                if ( $default != '' ) {
                    $upload_image = __( 'Choose a different default image',
                            'woocommerce-product-options' );
                }
                print '</span>';
                print $upload_image . '</a>
				<input class="backend-upload-image-src" type="hidden" name="' . $name . '[default]" value="' . $default . '">
			</div>';
            }

            /**
             * Displays select option admin
             */
            function select_input( $name, $meta ) {
                $this->tooltip( $name, $meta );
                $this->input_options( $name, $meta );
            }

            /**
             * Displays multi select option admin
             */
            function multi_select_input( $name, $meta ) {
                $this->tooltip( $name, $meta );
                $this->maximum_and_minimum( $name, $meta );
                $this->input_options( $name, $meta );
            }

            /**
             * Displays radio/select option admin
             */
            function input_options( $name, $meta, $two_column_checkbox = false ) {
                print '<div class="backend-options-wrapper">';
                $this->required( $name, $meta );
                if ( !empty( $two_column_checkbox ) ) {
                    print '<br><label>' .
                            ' <input name="' . $name . '[two-columns]" type="checkbox" class="" value="yes" ';
                    $two_columns = value( $meta, 'two-columns' );
                    if ( !empty( $two_columns ) ) {
                        print ' checked="checked" ';
                    }
                    print '/>' . __( 'Display in two columns?',
                                    'woocommerce-product-options' ) . '</label><br>';
                }
                $no_default = value( $meta, 'no_default', '' );
                print '<br><label>' .
                        ' <input name="' . $name . '[no_default]" type="checkbox" class="backend-no-default-checkbox" value="yes" ';
                if ( !empty( $no_default ) ) {
                    print ' checked="checked" ';
                }
                print '/>' . __( 'No default option',
                                'woocommerce-product-options' ) . '</label><br>';
                $options = value( $meta, 'options', array() );
                $default = value( $meta, 'default', '' );
                ?>
                <br>
                <label for="<?php print $name; ?>[show-option-prices]">
                    <input name="<?php print $name; ?>[show-option-prices]" value="yes" <?php
                if ( value( $meta, 'show-option-prices', '' ) === 'yes' ) {


                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Show suboption prices?', 'woocommerce-product-options' );
                ?> 

                </label><br>
                <h3><?php _e( 'Suboptions', 'woocommerce-product-options' ) ?></h3>
                <?php
                print '<table class="backend-options" width="100%">';
                print '<tr><th>' . __(
                                'Label', 'woocommerce-product-options' ) . '</th><th>' . __(
                                'Price', 'woocommerce-product-options' ) . '</th><th>' . __( '% Price Increase',
                                'woocommerce-product-options' ) . '</th>'
                        . '<th>' . __( 'Text to display when selected',
                                'woocommerce-product-options' ) . '</th><th class="default-column">' . __(
                                'Default', 'woocommerce-product-options' ) . '</th>'
                        . '<th></th></tr>';
                if ( !empty( $options ) ) {
                    foreach ( $options as $option_id => $option ) {
                        $this->input_option( $name, $option_id, $option,
                                $default );
                        do_action( 'woocommerce_product_options_backend_after_input_option',
                                $name, $option_id, $option, $default, $meta );
                    }
                }
                print '</table>';
                print '<br><a href="#" class="backend-add-new-option button">' . __( 'Add New Suboption',
                                'woocommerce-product-options' ) . '</a>';
                print ' &nbsp; &nbsp; &nbsp; <a href="#" class="sort-input-options button">' . __( 'Sort Suboptions Alphabetically',
                                'woocommerce-product-options' ) . '</a>';
                print '</div>';
            }

            /**
             * Displays one radio/select option admin
             */
            function input_option( $name, $option_id, $option, $default ) {
                $label = value( $option, 'label', '' );
                $price = value( $option, 'price', '' );
                $percentage_price = value( $option, 'percentage_price', '0' );
                $option_name = $name . '[options][' . $option_id . ']';
                $message = value( $option, 'message' );
                print '<tr class="backend-option option-' . $option_id . '">'
                        . '<td><input type="hidden" class="backend-option-id" value="' . $option_id . '" />'
                        . '<input type="text" class="input-option-label" name="' . $option_name . '[label]" value="' . $label . '"/></td>'
                        . '<td>' . get_woocommerce_currency_symbol() . '<input step="0.001" type="number" name="' . $option_name . '[price]" value="' . $price . '"/></td>'
                        . '<td><input step="0.01" type="number" name="' . $option_name . '[percentage_price]"  value="' . $percentage_price . '"/>%</td>'
                        . '<td><input type="text" placeholder="' . __( 'Selected text',
                                'woocommerce-product-options' ) . '" name="' . $option_name . '[message]" value="' . $message . '" />'
                        . '<td class="default-column"><input type="radio" name="' . $name . '[default]" class="backend-option-default" value="' . $option_id . '" ';
                if ( $option_id == $default ) {
                    print ' checked="checked" ';
                }
                print ' /></td>';
                print '<td><a href="#" class="backend-remove-option button">' . __( 'Remove suboption',
                                'woocommerce-product-options' ) . '</a></td></tr>';
            }

            /**
             * Displays numeric option admin
             */
            function numeric_input( $name, $meta, $type ) {
                //slider, numeric or text
                //minimum maximum
                if ( $type == 'range' || $type == 'number' ) {
                    $step_size = value( $meta, 'step-size', '0.01' );
                    print '<br><label for="' . $name . '[step-size]">' . __( 'Step Size',
                                    'woocommerce-product-options' ) . '</label> <br>'
                            . '<input step="0.01" type="number" value="' . $step_size . '" placeholder="0" name="' . $name . '[step-size]" />';
                }
                $minimum = value( $meta, 'minimum' );
                $maximum = value( $meta, 'maximum' );
                if ( $type == 'range' ) {
                    $minimum = value( $meta, 'minimum', 0 );
                    $maximum = value( $meta, 'maximum', 100 );
                }
                $this->required( $name, $meta );
                print '<br><label for="' . $name . '[minimum]">' . __( 'Minimum',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.01" type="number" value="' . $minimum . '" placeholder="0" name="' . $name . '[minimum]" />';
                print '<br><label for="' . $name . '[maximum]">' . __( 'Maximum',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.01" type="number" value="' . $maximum . '" placeholder="1000" name="' . $name . '[maximum]" />';

                //price
                $percentage_price = value( $meta, 'percentage_price', 0 );
                print '<br><label for="' . $name . '[percentage_price]">' . __( 'Percentage Price (i.e. add x% of numeric value to input). If you would like to add a price per item you can set the Percentage Price as the price per item * 100% (e.g. $2 per item would be 2*100=200%)',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.001" type="number" value="' . $percentage_price . '" placeholder="100" name="' . $name . '[percentage_price]" />%';
                $starting_from = value( $meta, 'starting_from', 0 );
                print '<br><label for="' . $name . '[starting_from]">' . __( 'Calculate percentage starting from (e.g. 10% starting from 3 makes a value of 4 cost (4-3)*10%=$0.1)',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.01" type="number" value="' . $starting_from . '" placeholder="' . $minimum . '" name="' . $name . '[starting_from]" />';
                $default = value( $meta, 'default', $minimum );
                print '<br><label for="' . $name . '[default]">' . __( 'Default value',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.01" type="number" value="' . $default . '" placeholder="' . $minimum . '" name="' . $name . '[default]" />';
                //$product_percentage_price = value( $meta, 'product_percentage_price', 0 );
                //print '<br><label for="' . $name . '[product_percentage_price]">' . __( 'Product/Order Percentage Price (increase the price of the product or order depending on whether this is a product or order option). Changes price to x% multiplied by the value chosen by the customer multiplied by the price of the product/order. If you set this value to be 100% then it works like a quantity option - the price of the product/order increases multiplies by the value of this option.', 'woocommerce-product-options' ) . '</label> <br>'
                //        . '<input step="0.001" type="number" value="' . $product_percentage_price . '" placeholder="100" name="' . $name . '[product_percentage_price]" />%';
            }

            /**
             * Displays checkbox option admin
             */
            function checkbox_input( $name, $meta ) {
                $checked_price = value( $meta, 'checked_price', 0 );
                $unchecked_price = value( $meta, 'unchecked_price', 0 );
                print '<br><label for="' . $name . '[checked_price]">' . __( 'Checked Price',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.01" type="number" value="' . $checked_price . '" placeholder="0" name="' . $name . '[checked_price]" />';
                print '<br><label for="' . $name . '[unchecked_price]">' . __( 'Unchecked Price',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input step="0.01" type="number" value="' . $unchecked_price . '" placeholder="0" name="' . $name . '[unchecked_price]" />';
                $this->required( $name, $meta );

                $checked_by_default_checked = '';
                if ( !empty( $meta[ 'checked_by_default' ] ) ) {
                    $checked_by_default_checked = 'checked="checked" ';
                }
                print '<br><label for="' . $name . '[checked_by_default]">'
                        . '<input type="checkbox" value="yes" name="' . $name . '[checked_by_default]" ' . $checked_by_default_checked . '/>'
                        . __( 'Checked by Default?',
                                'woocommerce-product-options' ) . '</label> <br>';

                global $post;
                $product_meta = get_post_meta( $post->ID,
                        'backend-product-options', true );
                $options = array();
                $id = value(
                        $meta, 'product_option_id' );
                if ( !empty( $product_meta ) ) {
                    foreach ( $product_meta as $product_meta_id =>
                                $meta_with_title ) {
                        if ( $id == $product_meta_id ) {
                            continue;
                        }
                        $title = value( $meta_with_title, 'title' );
                        $options[ $product_meta_id ] = '<span class="backend-conditional-title-text">' .
                                $title . '</span>';
                    }
                }
                print '<br><a class="show-conditional-logic button">' . __( 'Show/Hide Conditional Logic',
                                'woocommerce-product-options' ) . '</a>';
                print '<div class="conditional-logic">';
                print '<div class="backend-conditional-option-checkboxes">';
                print '<input type="hidden" class="backend-option-conditional-checkboxes-base-name" value="' . $name . '[hides]" />';
                $values = value( $meta, 'hides', array() );
                print '<p>' . __( 'Hide these options when the checkbox is checked:',
                                'woocommerce-product-options' ) . '</p>';
                wpshowcase_checkboxes( $options, $values, '', $name . '[hides]' );
                print '</div>';

                print '<div class="backend-conditional-option-checkboxes">';
                print '<input type="hidden" class="backend-option-conditional-checkboxes-base-name" value="' . $name . '[shows]" />';
                $values = value( $meta, 'shows', array() );
                print '<p>' . __( 'Hide these options when the checkbox is unchecked:',
                                'woocommerce-product-options' ) . '</p>';
                wpshowcase_checkboxes( $options, $values, '', $name . '[shows]' );
                print '</div>';
                print '</div>';
            }

            /**
             * Displays color picker option admin
             */
            function color_picker_input( $name, $meta ) {
                $default = value( $meta, 'default', '' );
                print '<br><label for="' . $name . '[default]">' . __( 'Default Value',
                                'woocommerce-product-options' ) . '</label> <br>'
                        . '<input class="default-color-picker" type="text" value="' . $default . '" name="' . $name . '[default]" />';
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_style( 'wp-color-picker' );
            }

            function tooltip( $name, $meta ) {
                ?><br>
                <label for="<?php print $name; ?>-tooltip">
                    <input name="<?php print $name; ?>[tooltip]" id="<?php print $name; ?>-tooltip" value="yes" <?php
                if ( value( $meta, 'tooltip', '' ) === 'yes' ) {
                    print ' checked="checked" ';
                }
                ?> type="checkbox" />
                <?php
                _e( 'Display selected text as a tooltip?',
                        'woocommerce-product-options' );
                ?></label>
                <?php
            }

            function required( $name, $meta ) {
                ?><br>
                <label for="<?php print $name; ?>-required">
                    <input name="<?php print $name; ?>[required]" id="<?php print $name; ?>-required" value="yes" <?php
        if ( value( $meta, 'required', '' ) === 'yes' ) {
            print ' checked="checked" ';
        }
        ?> type="checkbox" />
        <?php
        _e( 'Required field?', 'woocommerce-product-options' );
        ?></label>
        <?php
    }

    /**
     * Displays text option admin
     */
    function text_input( $name, $meta ) {
        $price_if_not_empty = value( $meta, 'price_if_not_empty', 0 );
        $price_per_character = value( $meta, 'price_per_character', 0 );
        $price_per_word = value( $meta, 'price_per_word', 0 );
        $starting_from = value( $meta, 'starting_from', 0 );
        $price_per_lower_case_letter = value( $meta,
                'price_per_lower_case_letter', 0 );
        $price_per_upper_case_letter = value( $meta,
                'price_per_upper_case_letter', 0 );
        $maximum_number_of_characters = value( $meta,
                'maximum_number_of_characters', '' );
        $default = value( $meta, 'default', '' );
        $this->required( $name, $meta );
        print '<br><label for="' . $name . '[price_if_not_empty]">' . __( 'Price if any text entered',
                        'woocommerce-product-options' ) . '</label> <br>'
                . '<input type="text" value="' . $price_if_not_empty . '" placeholder="0" name="' . $name . '[price_if_not_empty]" />';
        print '<br><label for="' . $name . '[price_per_word]">' . __( 'Price per Word',
                        'woocommerce-product-options' ) .
                '<br> <input type="number" step="0.001" value="' . $price_per_word . '" placeholder="0" name="' . $name . '[price_per_word]" />' .
                '</label> <br>';
        print '<br><label for="' . $name . '[price_per_character]">' . __( 'Price per Character',
                        'woocommerce-product-options' ) .
                '<br> <input type="number" step="0.001" value="' . $price_per_character . '" placeholder="0" name="' . $name . '[price_per_character]" />' .
                '</label> <br>';
        /* print '<br><label for="' . $name . '[starting_from]">' . __( 'The minimum number of characters before the price per character starts (e.g. $1 per character starting from 10 characters means',
          'woocommerce-product-options' ) . '</label> <br>'
          . '<input type="text" value="' . $price_per_character . '" placeholder="0" name="' . $name . '[price_per_character]" />';
         */print '<br><label for="' . $name . '[price_per_lower_case_letter]">' . __(
                        'Price per Lower Case Letter',
                        'woocommerce-product-options' ) . '</label> <br>'
                . '<input type="number" step="0.001" value="' . $price_per_lower_case_letter . '" placeholder="0" name="' . $name . '[price_per_lower_case_letter]" />';
        print '<br><label for="' . $name . '[price_per_upper_case_letter]">' . __(
                        'Price per Upper Case Letter',
                        'woocommerce-product-options' ) . '</label> <br>'
                . '<input type="number" step="0.001" value="' . $price_per_upper_case_letter . '" placeholder="0" name="' . $name . '[price_per_upper_case_letter]" />';
        print '<br><label for="' . $name . '[maximum_number_of_characters]">' . __(
                        'Maximum Number of Characters (leave blank for unlimited)',
                        'woocommerce-product-options' ) . '</label> <br>'
                . '<input type="number" value="' . $maximum_number_of_characters . '" placeholder="0" name="' . $name . '[maximum_number_of_characters]" />';
        print '<br><label for="' . $name . '[default]">' . __( 'Default Value',
                        'woocommerce-product-options' ) . '</label> <br>'
                . '<input type="text" value="' . $default . '" placeholder="' . __( 'some text',
                        'woocommerce-product-options' ) . '" name="' . $name . '[default]" />';
    }

    /**
     * Displays date picker option admin
     */
    function datepicker_input( $name, $meta ) {
        $display = value( $meta, 'display' );
        $default = value( $meta, 'default', 'today' );
        print '<br><label for="' . $name . '[default]">' . __( 'Default value using <a href="http://php.net/manual/en/function.strtotime.php">strtotime</a> notation e.g. today, tomorrow, +10 days, first day of next month',
                        'woocommerce-product-options' ) . '</label> <br>'
                . '<input type="text" value="' . $default . '" placeholder="+1 days" name="' . $name . '[default]" />';
        print '<br>' . __( 'Display datepicker as:',
                        'woocommerce-product-options' ) . '<br><select name="' . $name . '[display]">'
                . '<option value="input" ';
        if ( $display == 'input' ) {
            print 'selected ';
        }
        print '>' . __( 'Popup from text input', 'woocommerce-product-options' ) . '</option>'
                . '<option value="inline" ';
        if ( $display == 'inline' ) {
            print 'selected ';
        }
        print '>' . __( 'Inline', 'woocommerce-product-options' ) . '</option>'
                . '<option value="button" ';
        if ( $display != 'input' && $display != 'inline' ) {
            print 'selected ';
        }
        print ' >' . __( 'Inline on icon click', 'woocommerce-product-options' ) . '</option>'
                . '</select>';
        $disallow_past = empty( $meta[ 'disallow_past' ] ) ? '' :
                ' checked="checked" ';
        print '<br><label>
						<input type="checkbox" value="yes"  name="' . $name . '[disallow_past]"  ' . $disallow_past . ' />'
                . __( 'Disallow user from choosing dates in the past not including the current date?',
                        'woocommerce-product-options' ) .
                '</label>';
        $disallow_today = empty( $meta[ 'disallow_today' ] ) ? '' : ' checked="checked" ';
        print '<br><label>
						<input type="checkbox" value="yes"  name="' . $name . '[disallow_today]"  ' . $disallow_today . ' />'
                . __( 'Disallow user from choosing the past including the current date?',
                        'woocommerce-product-options' ) . '</label>';
        $disallow_weekends = empty(
                        $meta[ 'disallow_weekends' ] ) ? '' : ' checked="checked" ';
        print '<br><label>
						<input type="checkbox" value="yes"  name="' . $name . '[disallow_weekends'
                . ']"  ' . $disallow_weekends . '/> '
                . __( 'Disallow user from choosing weekends?',
                        'woocommerce-product-options' ) . '</label>';
        $this->required( $name, $meta );
    }

    /**
     * Displays textarea option admin
     */
    function textarea_input( $name, $meta ) {
        $this->text_input( $name, $meta );
    }

    /**
     * Changes slashes to /
     */
    function change_slashes( $string ) {
        return str_replace( '\\', '/', str_replace( '\\\\', '/', $string ) );
    }

    /**
     * Gets the directory as a url
     * @return string
     */
    function dir() {

        return dirname( str_replace( $this->change_slashes( WP_CONTENT_DIR ),
                        $this->change_slashes( WP_CONTENT_URL ),
                        $this->change_slashes( __FILE__ ) ) );
    }

}

$woocommerce_product_options_product_admin = new Woocommerce_Product_Options_Product_Admin();
?>