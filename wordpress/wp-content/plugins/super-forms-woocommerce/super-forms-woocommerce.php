<?php
/**
 * Super Forms - WooCommerce Checkout
 *
 * @package   Super Forms - WooCommerce Checkout
 * @author    feeling4design
 * @link      http://codecanyon.net/item/super-forms-drag-drop-form-builder/13979866
 * @copyright 2015 by feeling4design
 *
 * @wordpress-plugin
 * Plugin Name: Super Forms - WooCommerce Checkout
 * Plugin URI:  http://codecanyon.net/item/super-forms-drag-drop-form-builder/13979866
 * Description: Checkout with WooCommerce after form submission. Charge users for registering or posting content.
 * Version:     1.2.0
 * Author:      feeling4design
 * Author URI:  http://codecanyon.net/user/feeling4design
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('SUPER_WooCommerce')) :


    /**
     * Main SUPER_WooCommerce Class
     *
     * @class SUPER_WooCommerce
     */
    final class SUPER_WooCommerce {
    
        
        /**
         * @var string
         *
         *	@since		1.0.0
        */
        public $version = '1.2.0';


        /**
         * @var string
         *
         *  @since      1.1.0
        */
        public $add_on_slug = 'woocommerce_checkout';
        public $add_on_name = 'WooCommerce Checkout';


        /**
         * @var SUPER_WooCommerce The single instance of the class
         *
         *	@since		1.0.0
        */
        protected static $_instance = null;

        
        /**
         * Main SUPER_WooCommerce Instance
         *
         * Ensures only one instance of SUPER_WooCommerce is loaded or can be loaded.
         *
         * @static
         * @see SUPER_WooCommerce()
         * @return SUPER_WooCommerce - Main instance
         *
         *	@since		1.0.0
        */
        public static function instance() {
            if(is_null( self::$_instance)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        
        /**
         * SUPER_WooCommerce Constructor.
         *
         *	@since		1.0.0
        */
        public function __construct(){
            $this->init_hooks();
            do_action('super_woocommerce_loaded');
        }

        
        /**
         * Define constant if not already set
         *
         * @param  string $name
         * @param  string|bool $value
         *
         *	@since		1.0.0
        */
        private function define($name, $value){
            if(!defined($name)){
                define($name, $value);
            }
        }

        
        /**
         * What type of request is this?
         *
         * string $type ajax, frontend or admin
         * @return bool
         *
         *	@since		1.0.0
        */
        private function is_request($type){
            switch ($type){
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return (!is_admin() || defined('DOING_AJAX')) && ! defined('DOING_CRON');
            }
        }

        
        /**
         * Hook into actions and filters
         *
         *	@since		1.0.0
        */
        private function init_hooks() {

            // @since 1.1.0
            register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
            // Filters since 1.1.0
            add_filter( 'super_after_activation_message_filter', array( $this, 'activation_message' ), 10, 2 );

            // Filters since 1.0.0
            add_filter( 'super_after_contact_entry_data_filter', array( $this, 'add_entry_order_link' ), 10, 2 );

            // Filters since 1.2.0
            add_filter( 'super_countries_list_filter', array( $this, 'return_wc_countries' ), 10, 2 );

            // Actions since 1.0.0
            add_action( 'super_front_end_posting_after_insert_post_action', array( $this, 'save_wc_order_post_session_data' ) );
            add_action( 'super_after_wp_insert_user_action', array( $this, 'save_wc_order_signup_session_data' ) );
            add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta' ), 10, 1 );
            add_action( 'woocommerce_checkout_order_processed', array( $this, 'woocommerce_checkout_order_processed' ) );

            // @deprecated since 1.0.2
            //add_action( 'woocommerce_payment_complete_order_status', array( $this, 'payment_complete_order_status' ) );
            
            add_action( 'woocommerce_order_status_completed', array( $this, 'order_status_completed' ) );
            add_action( 'woocommerce_order_status_changed', array( $this, 'order_status_changed' ), 1, 3 );
            add_action( 'super_after_saving_contact_entry_action', array( $this, 'set_contact_entry_order_id_session' ), 10, 3 );

            if ( $this->is_request( 'frontend' ) ) {

                // Actions since 1.0.0
                add_action( 'woocommerce_cart_calculate_fees', array( $this, 'additional_shipping_costs' ), 5 );

                // Filters since 1.2.0
                add_filter( 'woocommerce_checkout_get_value', array( $this, 'populate_billing_field_values' ), 10, 2 );

            }
            
            if ( $this->is_request( 'admin' ) ) {
                
                // Filters since 1.0.0
                add_filter( 'super_settings_after_smtp_server_filter', array( $this, 'add_settings' ), 10, 2 );

                // Filters since 1.1.0
                add_filter( 'super_settings_end_filter', array( $this, 'activation' ), 100, 2 );
                
                // Actions since 1.1.0
                add_action( 'init', array( $this, 'update_plugin' ) );

            }
            
            if ( $this->is_request( 'ajax' ) ) {

                // Filters since 1.0.0

                // Actions since 1.0.0
                add_action( 'super_before_email_success_msg_action', array( $this, 'before_email_success_msg' ) );

            }
            
        }


        /**
         * Automatically update plugin from the repository
         *
         *  @since      1.1.0
        */
        function update_plugin() {
            if( defined('SUPER_PLUGIN_DIR') ) {
                require_once ( SUPER_PLUGIN_DIR . '/includes/admin/update-super-forms.php' );
                $plugin_remote_path = 'http://f4d.nl/super-forms/';
                $plugin_slug = plugin_basename( __FILE__ );
                new SUPER_WP_AutoUpdate( $this->version, $plugin_remote_path, $plugin_slug, '', '', $this->add_on_slug );
            }
        }


        /**
         * Add the activation under the "Activate" TAB
         * 
         * @since       1.1.0
        */
        public function activation($array, $data) {
            if (method_exists('SUPER_Forms','add_on_activation')) {
                return SUPER_Forms::add_on_activation($array, $this->add_on_slug, $this->add_on_name);
            }else{
                return $array;
            }
        }


        /**  
         *  Deactivate
         *
         *  Upon plugin deactivation delete activation
         *
         *  @since      1.1.0
         */
        public static function deactivate(){
            if (method_exists('SUPER_Forms','add_on_deactivate')) {
                SUPER_Forms::add_on_deactivate(SUPER_WooCommerce()->add_on_slug);
            }
        }


        /**
         * Check license and show activation message
         * 
         * @since       1.1.0
        */
        public function activation_message( $activation_msg, $data ) {
            if (method_exists('SUPER_Forms','add_on_activation_message')) {
                $form_id = absint($data['id']);
                $settings = $data['settings'];
                if( (isset($settings['woocommerce_checkout'])) && ($settings['woocommerce_checkout']=='true') ) {
                    return SUPER_Forms::add_on_activation_message($activation_msg, $this->add_on_slug, $this->add_on_name);
                }
            }
            return $activation_msg;
        }


        /**
         * Return WC countries list for billing_country and shipping_country only
         *
         *  @since      1.2.0
        */
        public function return_wc_countries($countries, $data) {
            if( (class_exists('WC_Countries')) && ($data['settings']['woocommerce_checkout']=='true') && ( ($data['name']=='billing_country') || ($data['name']=='shipping_country') ) ) {
                $countries_obj = new WC_Countries();
                $countries = $countries_obj->__get('countries');
                return $countries;
            }
            return $countries;
        }

        /**
         * Auto popuplate field with form data value
         * 
         * @since       1.2.0
        */
        public static function populate_billing_field_values( $value, $input ) {
            global $woocommerce;
            $v = $woocommerce->session->get('_super_form_data', array() );
            if( (isset($v[$input])) && (isset($v[$input]['value'])) ) return $v[$input]['value'];
            $input = str_replace('billing_', '', $input);
            if( (isset($v[$input])) && (isset($v[$input]['value'])) ) return $v[$input]['value'];
            if($input=='address_1'){
                if( (isset($v['address'])) && (isset($v['address']['value'])) ) return $v['address']['value'];
            }
            return $value;
        }
        
        /**
         * Change required fields on checkout page (for future reference if we will implement this feature anytime soon)
         * 
         * @since       1.2.0
        */     
        /*
        add_filter( 'woocommerce_billing_fields', array( $this, 'wc_required_fields' ), 10, 1 );
        public static function wc_required_fields( $address_fields ) {
            $fields = array(
                'first_name' => array( 'required'=>false, 'validate'=>false ),
                'last_name' => array( 'required'=>false, 'validate'=>false ),
                'company' => array( 'required'=>false, 'validate'=>false ),
                'email' => array( 'required'=>false, 'validate'=>false ),
                'phone' => array( 'required'=>false, 'validate'=>false ),
                'country' => array( 'required'=>false, 'validate'=>false ),
                'address_1' => array( 'required'=>false, 'validate'=>false ),
                'address_2' => array( 'required'=>false, 'validate'=>false ),
                'city' => array( 'required'=>false, 'validate'=>false ),
                'state' => array( 'required'=>false, 'validate'=>false ),
                'postcode' => array( 'required'=>false, 'validate'=>false )
            );
            foreach($fields as $k => $v){
                $address_fields['billing_'.$k]['required'] = $v['required'];
                $address_fields['billing_'.$k]['validate'] = $v['validate'];
                //unset($address_fields['billing_phone']['validate']);
            }
            return $address_fields;
        }
        */


        /**
         * Add the WC Order link to the entry info/data page
         * 
         * @since       1.0.0
        */
        public static function add_entry_order_link( $result, $data ) {
            $order_id = get_post_meta( $data['entry_id'], '_super_contact_entry_wc_order_id', true );
            if ( ! empty( $order_id ) ) {
                $order_id = absint($order_id);
                if( $order_id!=0 ) {
                    $result .= '<tr><th align="right">' . __( 'WooCommerce Order', 'super-forms' ) . ':</th><td><span class="super-contact-entry-data-value">';
                    $result .= '<a href="' . get_admin_url() . 'post.php?post=' . $order_id . '&action=edit">' . get_the_title( $order_id ) . '</a>';
                    $result .= '</span></td></tr>';
                }
            }
            return $result;
        }


        /**
         * Save contact entry ID to session
         * 
         * @since       1.0.0
        */
        function set_contact_entry_order_id_session( $data ) {
            if ( class_exists( 'WooCommerce' ) ) {
                $post_type = get_post_type( $data['entry_id'] );

                // Check if post_type is super_contact_entry 
                global $woocommerce;
                if( $post_type=='super_contact_entry' ) {
                    $woocommerce->session->set( '_super_entry_id', array( 'entry_id'=>$data['entry_id'] ) );
                }else{
                    $woocommerce->session->set( '_super_entry_id', array() );
                }
            }
        }


        /**
         * Add additional shipping costs
         * 
         * @since       1.0.0
        */
        function additional_shipping_costs( ) {
            global $woocommerce;
            if( isset( $_SESSION['_super_wc_fee'] ) ) {
                foreach( $_SESSION['_super_wc_fee'] as $k => $v ) {
                    if( $v['amount']>0 ) {
                        $woocommerce->cart->add_fee( $v['name'], $v['amount'], false, '' );
                    }else{
                        $woocommerce->cart->add_fee( $v['name'], $v['amount'], false, '' );
                    }
                }
            }
        }


        /**
         * If Front-end posting add-on is activated and being used retrieve the inserted Post ID and save it to the WC Order
         *
         *  @since      1.0.0
        */
        function save_wc_order_post_session_data( $data ) {
            global $woocommerce;

            // Check if Front-end Posting add-on is activated
            if ( class_exists( 'SUPER_Frontend_Posting' ) ) {
                $post_id = absint($data['post_id']);
                $settings = $data['atts']['settings'];
                if( (isset($settings['frontend_posting_action']) ) && ($settings['frontend_posting_action']=='create_post') ) {
                    $woocommerce->session->set( '_super_wc_post', array( 'post_id'=>$post_id, 'status'=>$settings['woocommerce_post_status'] ) );
                }else{
                    $woocommerce->session->set( '_super_wc_post', array() );
                }
            }else{
                $woocommerce->session->set( '_super_wc_post', array() );
            }

        }


        /**
         * If Register & Login add-on is activated and being used retrieve the created User ID and save it to the WC Order
         *
         *  @since      1.0.0
        */
        function save_wc_order_signup_session_data( $data ) {
            global $woocommerce;

            // Check if Register & Login add-on is activated
            if ( class_exists( 'SUPER_Register_Login' ) ) {
                $user_id = absint($data['user_id']);
                $settings = $data['atts']['settings'];
                if( (isset($settings['register_login_action']) ) && ($settings['register_login_action']=='register') ) {
                    $woocommerce->session->set( '_super_wc_signup', array( 'user_id'=>$user_id, 'status'=>$settings['woocommerce_signup_status'] ) );
                }else{
                    $woocommerce->session->set( '_super_wc_signup', array() );
                }
            }else{
                $woocommerce->session->set( '_super_wc_signup', array() );
            }

        }


        /**
         * Set the post ID and status to the order post_meta so we can update it after payment completed
         * 
         * @since       1.0.0
        */
        public static function update_order_meta( $order_id) {
            global $woocommerce;
            $_super_wc_post = $woocommerce->session->get( '_super_wc_post', array() );
            update_post_meta( $order_id, '_super_wc_post', $_super_wc_post );

            $_super_wc_signup = $woocommerce->session->get( '_super_wc_signup', array() );
            update_post_meta( $order_id, '_super_wc_signup', $_super_wc_signup );

            $_super_entry_id = $woocommerce->session->get( '_super_entry_id', array() );
            update_post_meta( $_super_entry_id['entry_id'], '_super_contact_entry_wc_order_id', $order_id );

        }


        /**
         * After order processed
         * 
         * @since       1.0.0
        */
        public function woocommerce_checkout_order_processed( $order_id ) {
            // This could possibly be used to notify the user of an order being processed.
            // We might want to add an option to send an extra email function after payment processed etc.
        }


        /**
         * After complete order status
         * 
         * @since       1.0.0
        */
        public function payment_complete_order_status( $order_id ) {
            return 'completed';
        }


        /**
         * After order completed
         * 
         * @since       1.0.0
        */
        public function order_status_completed( $order_id ) {
            // What we could do here we do within the "order_status_changed()" function below:
        }


        /**
         * After order status changed
         * 
         * @since       1.0.0
        */
        public function order_status_changed( $order_id, $old_status, $new_status ) {
            if( $new_status=='completed' ) {
                $_super_wc_post = get_post_meta( $order_id, '_super_wc_post', true );
                if ( !empty( $_super_wc_post ) ) { // @since 1.0.2 - check if not empty
                    $my_post = array(
                        'ID' => $_super_wc_post['post_id'],
                        'post_status' => $_super_wc_post['status'],
                    );
                    wp_update_post( $my_post );

                    $_super_wc_signup = get_post_meta( $order_id, '_super_wc_signup', true );
                    update_user_meta( $_super_wc_signup['user_id'], 'super_user_login_status', $_super_wc_signup['status'] );
                }
            }
        }


        /**
         * Hook into before sending email and check if we need to create or update a post or taxonomy
         *
         *  @since      1.0.0
        */
        public static function before_email_success_msg( $atts ) {
            $settings = $atts['settings'];
            if( isset( $atts['data'] ) ) {
                $data = $atts['data'];
            }else{
                if( $settings['save_contact_entry']=='yes' ) {
                    $data = get_post_meta( $atts['entry_id'], '_super_contact_entry_data', true );
                }else{
                    $data = $atts['post']['data'];
                }
            }
            if( (isset($settings['woocommerce_checkout'])) && ($settings['woocommerce_checkout']=='true') ) {

                // No products defined to add to cart!
                if( (!isset($settings['woocommerce_checkout_products'])) || (empty($settings['woocommerce_checkout_products'])) ) {
                    $msg = __( 'You haven\'t defined what products should be added to the cart. Please <a href="' . get_admin_url() . 'admin.php?page=super_create_form&id=' . absint( $atts['post']['form_id'] ) . '">edit</a> your form settings and try again', 'super-forms' );
                    SUPER_Common::output_error(
                        $error = true,
                        $msg = $msg,
                        $redirect = null
                    );
                }

                $products = array();
                $woocommerce_checkout_products = explode( "\n", $settings['woocommerce_checkout_products'] );  
                $new_woocommerce_checkout_products = $woocommerce_checkout_products;
                foreach( $woocommerce_checkout_products as $k => $v ) {
                    $product =  explode( "|", $v );
                    if( isset( $product[0] ) ) $product_id_tag = trim($product[0], '{}');
                    if( isset( $product[1] ) ) $product_quantity_tag = trim($product[1], '{}');
                    if( isset( $product[2] ) ) $product_variation_id_tag = trim($product[2], '{}');
                    if( isset( $product[3] ) ) $product_price_tag = trim($product[3], '{}');

                    $looped = array();
                    $i=2;
                    while( isset( $data[$product_id_tag . '_' . ($i)]) ) {
                        if(!in_array($i, $looped)){
                            $new_line = '';
                            if( $product[0][0]=='{' ) { $new_line .= '{' . $product_id_tag . '_' . $i . '}'; }else{ $new_line .= $product[0]; }
                            if( $product[1][0]=='{' ) { $new_line .= '|{' . $product_quantity_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[1]; }
                            if( $product[2][0]=='{' ) { $new_line .= '|{' . $product_variation_id_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[2]; }
                            if( $product[3][0]=='{' ) { $new_line .= '|{' . $product_price_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[3]; }
                            $new_woocommerce_checkout_products[] = $new_line;
                            $looped[$i] = $i;
                            $i++;
                        }else{
                            break;
                        }
                    }

                    $i=2;
                    while( isset( $data[$product_quantity_tag . '_' . ($i)]) ) {
                        if(!in_array($i, $looped)){
                            $new_line = '';
                            if( $product[0][0]=='{' ) { $new_line .= '{' . $product_id_tag . '_' . $i . '}'; }else{ $new_line .= $product[0]; }
                            if( $product[1][0]=='{' ) { $new_line .= '|{' . $product_quantity_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[1]; }
                            if( $product[2][0]=='{' ) { $new_line .= '|{' . $product_variation_id_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[2]; }
                            if( $product[3][0]=='{' ) { $new_line .= '|{' . $product_price_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[3]; }
                            $new_woocommerce_checkout_products[] = $new_line;
                            $looped[$i] = $i;
                            $i++;
                        }else{
                            break;
                        }
                    }

                    $i=2;
                    while( isset( $data[$product_variation_id_tag . '_' . ($i)]) ) {
                        if(!in_array($i, $looped)){
                            $new_line = '';
                            if( $product[0][0]=='{' ) { $new_line .= '{' . $product_id_tag . '_' . $i . '}'; }else{ $new_line .= $product[0]; }
                            if( $product[1][0]=='{' ) { $new_line .= '|{' . $product_quantity_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[1]; }
                            if( $product[2][0]=='{' ) { $new_line .= '|{' . $product_variation_id_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[2]; }
                            if( $product[3][0]=='{' ) { $new_line .= '|{' . $product_price_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[3]; }
                            $new_woocommerce_checkout_products[] = $new_line;
                            $looped[$i] = $i;
                            $i++;
                        }else{
                            break;
                        }
                    }

                    $i=2;
                    while( isset( $data[$product_price_tag . '_' . ($i)]) ) {
                        if(!in_array($i, $looped)){
                            $new_line = '';
                            if( $product[0][0]=='{' ) { $new_line .= '{' . $product_id_tag . '_' . $i . '}'; }else{ $new_line .= $product[0]; }
                            if( $product[1][0]=='{' ) { $new_line .= '|{' . $product_quantity_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[1]; }
                            if( $product[2][0]=='{' ) { $new_line .= '|{' . $product_variation_id_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[2]; }
                            if( $product[3][0]=='{' ) { $new_line .= '|{' . $product_price_tag . '_' . $i . '}'; }else{ $new_line .= '|' . $product[3]; }
                            $new_woocommerce_checkout_products[] = $new_line;
                            $looped[$i] = $i;
                            $i++;
                        }else{
                            break;
                        }
                    }
                }

                foreach( $new_woocommerce_checkout_products as $k => $v ) {
                    $product =  explode( "|", $v );
                    $product_id = 0;
                    $product_quantity = 0;
                    $product_variation_id = '';
                    $product_price = '';
                    if( isset( $product[0] ) ) $product_id = SUPER_Common::email_tags( $product[0], $data, $settings );
                    if( isset( $product[1] ) ) $product_quantity = SUPER_Common::email_tags( $product[1], $data, $settings );
                    if( isset( $product[2] ) ) $product_variation_id = SUPER_Common::email_tags( $product[2], $data, $settings );
                    if( isset( $product[3] ) ) $product_price = SUPER_Common::email_tags( $product[3], $data, $settings );
                    $product_quantity = absint($product_quantity);
                    if( $product_quantity>0 ) {
                        $products[] = array(
                            'id' => absint($product_id),
                            'quantity' => absint($product_quantity),
                            'variation_id' => absint($product_variation_id),
                            'price' => $product_price,
                        );
                    }
                }

                global $woocommerce;

                // Empty the cart
                if( (isset($settings['woocommerce_checkout_empty_cart'])) && ($settings['woocommerce_checkout_empty_cart']=='true') ) {
                    $woocommerce->cart->empty_cart();
                }

                // Remove any coupons.
                if( (isset($settings['woocommerce_checkout_remove_coupons'])) && ($settings['woocommerce_checkout_remove_coupons']=='true') ) {
                    $woocommerce->cart->remove_coupons();
                }

                // Add discount
                if( (isset($settings['woocommerce_checkout_coupon'])) && ($settings['woocommerce_checkout_coupon']!='') ) {
                    $woocommerce->cart->add_discount($settings['woocommerce_checkout_coupon']);
                }

                // Delete any fees
                if( (isset($settings['woocommerce_checkout_remove_fees'])) && ($settings['woocommerce_checkout_remove_fees']=='true') ) {
                    $woocommerce->session->set( 'fees', array() );
                    unset($_SESSION['_super_wc_fee']);
                }

                // Add fee
                if( (isset($settings['woocommerce_checkout_fees'])) && ($settings['woocommerce_checkout_fees']!='') ) {
                    
                    $fees = array();
                    $woocommerce_checkout_fees = explode( "\n", $settings['woocommerce_checkout_fees'] );  
                    foreach( $woocommerce_checkout_fees as $k => $v ) {
                        $fee =  explode( "|", $v );
                        $name = '';
                        $amount = 0;
                        $taxable = false;
                        $tax_class = '';
                        if( isset( $fee[0] ) ) $name = SUPER_Common::email_tags( $fee[0], $data, $settings );
                        if( isset( $fee[1] ) ) $amount = SUPER_Common::email_tags( $fee[1], $data, $settings );
                        if( isset( $fee[2] ) ) $taxable = SUPER_Common::email_tags( $fee[2], $data, $settings );
                        if( isset( $fee[3] ) ) $tax_class = SUPER_Common::email_tags( $fee[3], $data, $settings );
                        if( $amount>0 ) {
                            $fees[] = array(
                                'name' => $name,            // ( string ) required – Unique name for the fee. Multiple fees of the same name cannot be added.
                                'amount' => $amount,        // ( float ) required – Fee amount.
                                'taxable' => $taxable,      // ( bool ) optional – (default: false) Is the fee taxable?
                                'tax_class' => $tax_class,  // ( string ) optional – (default: '') The tax class for the fee if taxable. A blank string is standard tax class.
                            );
                        }
                    }
                    $_SESSION['_super_wc_fee'] = $fees;
                }

                global $wpdb;

                // Now add the product(s) to the cart
                foreach( $products as $k => $v ) {

                    if( class_exists('WC_Name_Your_Price_Helpers') ) {
                        $posted_nyp_field = 'nyp' . apply_filters( 'nyp_field_prefix', '', $v['id'] );
                        $_REQUEST[$posted_nyp_field] = (double) WC_Name_Your_Price_Helpers::standardize_number($v['price']);
                    }

                    $new_attributes = array();
                    if( $v['variation_id']!=0 ) {
                        $product = wc_get_product( $v['id'] );
                        if( $product->product_type=='variable' ) {
                            $attributes = $product->get_variation_attributes();
                            foreach( $attributes as $ak => $av ) {
                                $new_attributes[$ak] = get_post_meta( $v['variation_id'], 'attribute_' . $ak, true );
                            }
                        }
                    }
                    $woocommerce->cart->add_to_cart(
                        $v['id'],               // ( int ) optional – contains the id of the product to add to the cart
                        $v['quantity'],         // ( int ) optional default: 1 – contains the quantity of the item to add
                        $v['variation_id'],     // ( int ) optional –
                        $new_attributes         // ( array ) optional – attribute values
                                                // ( array ) optional – extra cart item data we want to pass into the item
                    );

                }

                // Redirect to cart / checkout page
                if( isset($settings['woocommerce_redirect']) ) {
                    $woocommerce->session->set( '_super_form_data', $data ); // @since 1.2.0 - save data to session for billing fields
                    $redirect = null;
                    if( $settings['woocommerce_redirect']=='checkout' ) {
                        $redirect = $woocommerce->cart->get_checkout_url();
                    }
                    if( $settings['woocommerce_redirect']=='cart' ) {
                        $redirect = $woocommerce->cart->get_cart_url();
                    }
                    if( $redirect!=null ) {
                        SUPER_Common::output_error(
                            $error = false,
                            $msg = '',
                            $redirect = $redirect
                        );
                    }
                }
                exit;

            }

        }


        /**
         * Hook into settings and add WooCommerce settings
         *
         *  @since      1.0.0
        */
        public static function add_settings( $array, $settings ) {
            $array['woocommerce_checkout'] = array(        
                'hidden' => 'settings',
                'name' => __( 'WooCommerce Checkout', 'super-forms' ),
                'label' => __( 'WooCommerce Checkout', 'super-forms' ),
                'fields' => array(
                    'woocommerce_checkout' => array(
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout', $settings['settings'], '' ),
                        'type' => 'checkbox',
                        'filter'=>true,
                        'values' => array(
                            'true' => __( 'Enable WooCommerce Checkout', 'super-forms' ),
                        ),
                    ),               
                    'woocommerce_checkout_empty_cart' => array(
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout_empty_cart', $settings['settings'], '' ),
                        'type' => 'checkbox',
                        'values' => array(
                            'true' => __( 'Empty cart before adding products', 'super-forms' ),
                        ),
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ),
                    'woocommerce_checkout_remove_coupons' => array(
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout_remove_coupons', $settings['settings'], '' ),
                        'type' => 'checkbox',
                        'values' => array(
                            'true' => __( 'Remove/clear coupons before redirecting to cart', 'super-forms' ),
                        ),
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ), 
                    'woocommerce_checkout_remove_fees' => array(
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout_remove_fees', $settings['settings'], '' ),
                        'type' => 'checkbox',
                        'values' => array(
                            'true' => __( 'Remove/clear fees before redirecting to cart', 'super-forms' ),
                        ),
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ),
                    'woocommerce_checkout_products' => array(
                        'name' => __( 'Enter the product(s) ID that needs to be added to the cart', 'super-forms' ) . '<br /><i>' . __( 'If field is inside dynamic column, system will automatically add all the products. Put each product ID with it\'s quantity on a new line separated by pipes "|".<br /><strong>Example with tags:</strong> {id}|{quantity}<br /><strong>Example without tags:</strong> 82921|3<br /><strong>Example with variations:</strong> {id}|{quantity}|{variation_id}<br /><strong>Example with dynamic pricing:</strong> {id}|{quantity}|none|{price}<br /><strong>Allowed values:</strong> integer|integer|integer|float<br />(dynamic pricing requires <a target="_blank" href="https://woocommerce.com/products/name-your-price/">WooCommerce Name Your Price add-on</a>).', 'super-forms' ) . '</i>',
                        'desc' => __( 'Put each on a new line, {tags} can be used to retrieve data', 'super-forms' ),
                        'type' => 'textarea',
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout_products', $settings['settings'], "{id}|{quantity}|none|{price}" ),
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ),
               
                    'woocommerce_checkout_coupon' => array(
                        'name' => __( 'Apply the following coupon code (leave blank for none):', 'super-forms' ),
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout_coupon', $settings['settings'], '' ),
                        'type' => 'text',
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ),
                    'woocommerce_checkout_fees' => array(
                        'name' => __( 'Add checkout fee(s)', 'super-forms' ) . '<br /><i>' . __( 'Put each fee on a new line with values seperated by pipes "|".<br /><strong>Example with tags:</strong> {fee_name}|{amount}|{taxable}|{tax_class}<br /><strong>Example without tags:</strong> Administration fee|5|fales|\'\'<br /><strong>Allowed values:</strong> string|float|bool|string', 'super-forms' ) . '</i>',
                        'desc' => __( 'Leave blank for no fees', 'super-forms' ),
                        'type' => 'textarea',
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_checkout_fees', $settings['settings'], "{fee_name}|{amount}|{taxable}|{tax_class}" ),
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ),
                    'woocommerce_redirect' => array(
                        'name' => __( 'Redirect to Checkout page or Shopping Cart?', 'super-forms' ),
                        'default' => SUPER_Settings::get_value( 0, 'woocommerce_redirect', $settings['settings'], 'checkout' ),
                        'type' => 'select',
                        'values' => array(
                            'checkout' => __( 'Checkout page (default)', 'super-forms' ),
                            'cart' => __( 'Shopping Cart', 'super-forms' ),
                            'none' => __( 'None (use the form redirect)', 'super-forms' ),
                        ),
                        'filter' => true,
                        'parent' => 'woocommerce_checkout',
                        'filter_value' => 'true',
                    ),
                )
            );

            if ( class_exists( 'SUPER_Frontend_Posting' ) ) {
                $array['woocommerce_checkout']['fields']['woocommerce_post_status'] = array(
                    'name' => __( 'Post status after payment complete', 'super-forms' ),
                    'desc' => __( 'Only used for Front-end posting (publish, future, draft, pending, private, trash, auto-draft)?', 'super-forms' ),
                    'default' => SUPER_Settings::get_value( 0, 'woocommerce_post_status', $settings['settings'], 'publish' ),
                    'type' => 'select',
                    'values' => array(
                        'publish' => __( 'Publish (default)', 'super-forms' ),
                        'future' => __( 'Future', 'super-forms' ),
                        'draft' => __( 'Draft', 'super-forms' ),
                        'pending' => __( 'Pending', 'super-forms' ),
                        'private' => __( 'Private', 'super-forms' ),
                        'trash' => __( 'Trash', 'super-forms' ),
                        'auto-draft' => __( 'Auto-Draft', 'super-forms' ),
                    ),
                    'filter' => true,
                    'parent' => 'woocommerce_checkout',
                    'filter_value' => 'true',
                );
            }

            if ( class_exists( 'SUPER_Register_Login' ) ) {
                $array['woocommerce_checkout']['fields']['woocommerce_signup_status'] = array(
                    'name' => __( 'Registered user login status after payment complete', 'super-forms' ),
                    'desc' => __( 'Only used for Register & Login add-on (active, pending, blocked)?', 'super-forms' ),
                    'default' => SUPER_Settings::get_value( 0, 'woocommerce_signup_status', $settings['settings'], 'active' ),
                    'type' => 'select',
                    'values' => array(
                        'active' => __( 'Active (default)', 'super-forms' ),
                        'pending' => __( 'Pending', 'super-forms' ),
                        'blocked' => __( 'Blocked', 'super-forms' ),
                    ),
                    'filter' => true,
                    'parent' => 'woocommerce_checkout',
                    'filter_value' => 'true',
                );
            }

            return $array;
        }
    }
        
endif;


/**
 * Returns the main instance of SUPER_WooCommerce to prevent the need to use globals.
 *
 * @return SUPER_WooCommerce
 */
function SUPER_WooCommerce() {
    return SUPER_WooCommerce::instance();
}


// Global for backwards compatibility.
$GLOBALS['SUPER_WooCommerce'] = SUPER_WooCommerce();