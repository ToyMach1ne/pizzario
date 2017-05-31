<?php

class Woocommerce_Product_Options_Conditional {

    function __construct() {
        add_action( 'woocommerce_product_options_backend_after_input_option', array( $this, 'woocommerce_product_options_backend_after_input_option' ), 10, 5 );
        add_action( 'woocommerce_product_options_backend_after_image_input_option', array( $this, 'woocommerce_product_options_backend_after_image_input_option' ), 10, 5 );
        add_action( 'woocommerce_product_options_frontend_after_input', array( $this, 'woocommerce_product_options_frontend_after_input' ), 10, 2 );
        add_action( 'woocommerce_product_options_after_product_options', array( $this, 'woocommerce_product_options_after_product_options' ), 10, 3 );
        add_action( 'woocommerce_product_options_backend_after_drag_and_drop_table', array( $this, 'woocommerce_product_options_backend_after_drag_and_drop_table' ), 10, 2 );
    }

    function woocommerce_product_options_backend_after_drag_and_drop_table() {
        wp_enqueue_script( 'woocommerce-product-options-conditional-backend', plugins_url( '/../assets/js/woocommerce-product-options-conditional-backend.js', __FILE__ ) );
    }

    function woocommerce_product_options_after_product_options( $options, $product_option_post_id, $post_id ) {
        wp_enqueue_script( 'woocommerce-product-options-conditional-frontend', plugins_url( '/../assets/js/woocommerce-product-options-conditional-frontend.js', __FILE__ ) );
    }

    function woocommerce_product_options_backend_after_image_input_option( $name, $option_id, $option, $default, $meta ) {
        $this->woocommerce_product_options_backend_after_input_option( $name, $option_id, $option, $default, $meta );
    }

    function woocommerce_product_options_backend_after_input_option( $name, $option_id, $option, $default, $meta ) {
        if ( strpos( $name, '[options]' ) === false ) {
            $name.='[options][' . $option_id . ']';
        }
        print '<tr><td colspan="6" style="padding-bottom:20px;">';
        print '<a class="show-conditional-logic button">' . __( 'Show/Hide Conditional Logic', 'woocommerce-product-options' ) . '</a>';
        print '<div class="conditional-logic">';
        print '<div class="backend-conditional-option-checkboxes">';
        global $post;
        if(empty($post)) {
            $post_id = value($_REQUEST,'post_id');
        } else {
            $post_id = $post->ID;
        }
        $product_meta = get_post_meta( $post_id, 'backend-product-options', true );
        $options = array();
        $id = value( $meta, 'product_option_id' );
        if ( !empty( $product_meta ) ) {
            foreach ( $product_meta as $product_meta_id => $meta_with_title ) {
                if ( $id == $product_meta_id ) {
                    continue;
                }
                $title = value( $meta_with_title, 'title' );
                $options[ $product_meta_id ] = '<span class="backend-conditional-title-text">' .
                        $title . '</span>';
            }
        }
        print '<input type="hidden" class="backend-option-conditional-checkboxes-base-name" value="' . $name . '[hides]" />';
        $values = value( $option, 'hides', array() );
        print '<p>' . __( 'Hide these options when this suboption is chosen:', 'woocommerce-product-options' ) . '</p>';
        wpshowcase_checkboxes( $options, $values, '', $name . '[hides]' );
        print '</div>';

        print '<div class="backend-conditional-option-checkboxes">';
        print '<input type="hidden" class="backend-option-conditional-checkboxes-base-name" value="' . $name . '[shows]" />';
        $values = value( $option, 'shows', array() );
        print '<p>' . __( 'Show these options when this suboption is chosen:', 'woocommerce-product-options' ) . '</p>';
        wpshowcase_checkboxes( $options, $values, '', $name . '[shows]' );
        print '</div>';
        print '</div>';
        print '</td></tr>';
    }

    function woocommerce_product_options_frontend_after_input( $name, $option ) {
        $type = value( $option, 'type' );
        if ( $type != 'select' && $type != 'radio' && $type != 'checkboxes' && $type != 'multi_select' && $type != 'radio_image' ) {
            return;
        }
        $suboptions = value( $option, 'options' );
        if ( !empty( $suboptions ) ) {
            foreach ( $suboptions as $suboption_id => $suboption ) {
                $shows = value( $suboption, 'shows' );
                if ( !empty( $shows ) ) {
                    foreach ( $shows as $show_id => $show ) {
                        print '<input type="hidden" class="show-when-suboption-selected-' . $suboption_id . '" value="' . $show_id . '" />';
                        print '<input type="hidden" class="suboptions-hide-options" value="yes" />';
                    }
                }
                $hides = value( $suboption, 'hides' );
                if ( !empty( $hides ) ) {
                    foreach ( $hides as $hide_id => $hide ) {
                        print '<input type="hidden" class="hide-when-suboption-selected-' . $suboption_id . '" value="' . $hide_id . '" />';
                    }
                    if ( empty( $shows ) ) {
                        print '<input type="hidden" class="suboptions-hide-options" value="yes" />';
                    }
                }
            }
        }
    }

}

$woocommerce_product_options_conditional = new Woocommerce_Product_Options_Conditional();
