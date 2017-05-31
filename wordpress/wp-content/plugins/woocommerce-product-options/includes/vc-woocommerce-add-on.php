<?php

class WooCommerce_Product_Options_VC_WooCommerce_Add_On {

    private $option_type_functions;

    function __construct() {
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
        add_filter( 'vc_woocommerce_add_on_convert_to_vc',
                array( $this, 'vc_woocommerce_add_on_convert_to_vc' ), 10, 2 );
        $this->option_type_functions = array( 'radio' => 'radio', 'checkboxes' => 'checkboxes',
            'radio_image' => 'radio_image', 'image_upload' => 'image_upload',
            'text' => 'text', 'color_picker' => 'color_picker',
            'checkbox' => 'checkbox', 'textarea' => 'textarea', 'datepicker' => 'datepicker',
            'select' => 'select', 'combobox' => 'select', 'multi_select' => 'multi_select',
            'number' => 'numeric', 'range' => 'numeric', 'number_as_text' => 'numeric' );
        add_filter( 'vc_woocommerce_add_on_use_standard_class',
                array( $this, 'vc_woocommerce_add_on_use_standard_class' ), 10,
                5 );
        add_action( 'save_post', array( $this, 'save_post' ), 20, 2 );
    }

    function save_post( $post_id, $post ) {
        if ( $post->post_type != 'product' ) {
            return;
        }
        $product_options = get_post_meta( $post_id, 'backend-product-options',
                true );
        if ( empty( $product_options ) ) {
            $product_options = array();
        }
        $content = $post->post_content;
        $option_shortcodes = array();
        preg_match_all( '/\[product_option_(.*?)\]/', $content,
                $option_shortcodes );
        if ( !empty( $option_shortcodes[ 0 ] ) ) {
            foreach ( $option_shortcodes[ 0 ] as $shortcode_content ) {
                $first_space = strpos( $shortcode_content, ' ' );
                $shortcode = substr( $shortcode_content,
                        strlen( '[product_option_' ),
                        $first_space - strlen( '[product_option_' ) );
                $attributes = array();
                preg_match_all( '/' . $shortcode . '\s*=\s*"(.*?)"/',
                        $shortcode_content, $attributes );
                if ( !empty( $attributes[ 1 ] ) && !empty( $attributes[ 1 ][ 0 ] ) ) {
                    $meta = array();
                    parse_str( str_replace( '&amp;', '&', $attributes[ 1 ][ 0 ] ),
                            $meta );
                    if ( !empty( $meta[ 'backend-product-options' ] ) ) {
                        $keys = array_keys( $meta[ 'backend-product-options' ] );
                        $key = $keys[ 0 ];
                        $meta[ 'backend-product-options' ][ $key ][ 'type' ] = $shortcode;
                        $product_options = array_merge( $product_options,
                                $meta[ 'backend-product-options' ] );
                    }
                }
            }
        }
        delete_post_meta( $post_id, 'backend-product-options' );
        add_post_meta( $post_id, 'backend-product-options', $product_options,
                true );
    }

    function vc_woocommerce_add_on_convert_to_vc( $settings, $shortcode ) {
        if ( strpos( $shortcode, 'product_option_' ) !== 0 ) {
            return $settings;
        }
        $option_type = str_replace( 'product_option_', '',
                str_replace( '_input', '', $shortcode ) );
        global $woocommerce_product_options_product_admin;
        $settings[ 'params' ] = array( array(
                'group' => __( 'Configure Product Option',
                        'woocommerce-product-options' ),
                'type' => 'product_option_' . $option_type . '_input',
                'param_name' => 'product_option_' . $option_type . '_input',
                'heading' => sprintf( __( 'Product Option: %s', 'qwer' ),
                        $woocommerce_product_options_product_admin->get_option_label( $option_type ) ) ) )
                + $settings[ 'params' ];
        $settings[ 'show_settings_on_create' ] = true;
        return $settings;
    }

    function vc_woocommerce_add_on_use_standard_class( $use_standard_class,
            $shortcode, $class_name, $properties, $settings ) {
        if ( strpos( $shortcode, 'product_option_' ) === 0 ) {
            return false;
        }
        return $use_standard_class;
    }

    function option_input( $settings, $value ) {
        $option_type = str_replace( 'product_option_', '',
                str_replace( '_input', '', $settings[ 'param_name' ] ) );
        global $woocommerce_product_options_product_admin;
        ob_start();
        $id = md5( microtime() . rand() );
        $meta = array();
        parse_str( $value, $meta );
        if ( empty( $meta ) ) {
            $meta = array( 'type' => $option_type );
        } else {
            $meta = array_pop( value( $meta, 'backend-product-options', array() ) );
        }
        $woocommerce_product_options_product_admin->before_input( $id, $meta );
        $function_name = $this->option_type_functions[ $option_type ] . '_input';
        $woocommerce_product_options_product_admin->$function_name( 'backend-product-options[' . $id . ']',
                $meta );
        $woocommerce_product_options_product_admin->after_input( $id, $meta );
        print '<input type="hidden" name="product_option_' . $option_type . '_input" class="wpb_vc_param_value product_option_' . $option_type . '_input" value="' . $value . '" />';
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.vc_panel-tabs .backend-product-option').find('input, select, textarea').change();
            });
        </script>
        <?php

        return ob_get_clean();
    }

    function do_woocommerce_shortcode( $atts, $content, $shortcode ) {
        $option_type = str_replace( 'product_option_', '',
                str_replace( '_input', '', $shortcode ) );
        $func = $this->option_type_functions[ $option_type ];
        global $post;
        $product_option_post_id = $post->ID;
        $post_id = $post->ID;
        $value = str_replace( '&amp;', '&', value( $atts, $shortcode ) );
        $meta = array();
        parse_str( $value, $meta );
        global $woocommerce_product_options_product_frontend;
        global $product_option_product_id;
        global $woocommerce_product_options_ajax;
        $product_option_product_id = $post_id;
        $total_price = $woocommerce_product_options_ajax->get_price( $post_id );
        $product_meta_settings = get_post_meta( $product_option_post_id,
                'product-options-settings', true );
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
        <input type="hidden" class="product-options-post-type" value="' . $post_type . '" />
		<input type="hidden" class="product-post-id" name="product_post_id" value="' . $post_id . '" />';
        foreach ( value( $meta, 'backend-product-options', array() ) as
                    $option_id => $option ) {
            $option[ 'type' ] = $option_type;
            $value_chosen = $woocommerce_product_options_product_frontend->get_value_chosen( $option,
                    $option_id, $product_option_post_id );
            $option = $woocommerce_product_options_product_frontend->prepare_option( $option,
                    $post_id, $product_option_post_id, $option_id, $total_price );
            $name = 'product-options[' . $product_option_post_id . '][' . $option_id . ']';
            $woocommerce_product_options_product_frontend->before_input( $option_id,
                    $name, $option, $product_option_post_id );
            if ( $option_type == 'image_upload' ) {
                $woocommerce_product_options_product_frontend->image_upload_input( $name,
                        $option, $value_chosen, $option_id,
                        $product_option_post_id );
            } else {
                $function_name = $func . '_input';
                $woocommerce_product_options_product_frontend->$function_name( $name,
                        $option, $value_chosen );
            }
            $woocommerce_product_options_product_frontend->after_input( $option_id,
                    $name, $option );
        }
        print '</div>';
        global $woocommerce_product_options;
        $woocommerce_product_options->frontend_scripts();
    }

    function plugins_loaded() {
        global $woocommerce_product_options_product_admin, $woocommerce_shortcodes;
        foreach ( $this->option_type_functions as $option_type => $func ) {
            $shortcode = 'product_option_' . $option_type . '_input';
            vc_add_shortcode_param( $shortcode, array( $this, 'option_input' ) );
            $woocommerce_shortcodes[ 'single_product' ][ 'items' ][ $shortcode ]
                    = array( 'label' => sprintf( __( 'Product Option: %s',
                                'qwer' ),
                        ucwords( $woocommerce_product_options_product_admin->get_option_label( $option_type ) ) ), );
            $class_name = 'WPBakeryShortcode_' . str_replace( ' ', '_',
                            ucwords( str_replace( '_', ' ', $shortcode ) ) );
            global $woocommerce_product_options_product_frontend;
            add_action( $shortcode, array( $this, 'do_woocommerce_shortcode' ),
                    10, 3 );
        }
    }

}

if ( class_exists( 'Vc_Manager' ) && class_exists( 'Woocommerce_Shortcodes_Factory' ) ) {
    $woocommerce_product_options_vc_woocommerce_add_on = new WooCommerce_Product_Options_VC_WooCommerce_Add_On();
}
