<?php

if ( !defined( 'ABSPATH' ) || !defined( 'YITH_YWDPD_VERSION' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements features of YITH WooCommerce Dynamic Pricing and Discounts
 *
 * @class   YITH_WC_Dynamic_Discounts
 * @package YITH WooCommerce Dynamic Pricing and Discounts
 * @since   1.0.0
 * @author  Yithemes
 */
if ( !class_exists( 'YITH_WC_Dynamic_Discounts' ) ) {

	/**
	 * Class YITH_WC_Dynamic_Discounts
	 */
	class YITH_WC_Dynamic_Discounts {

        /**
         * Single instance of the class
         *
         * @var \YITH_WC_Dynamic_Discounts
         */

        protected static $instance;

	    /**
	     * Plugin option name
	     * @var string
	     */
	    public $plugin_options = 'yit_ywdpd_options';

	    /**
	     * Array with discount rules
	     * @var array
	     */
	    public $discount_rules = array();

	    /**
	     * Discount amount for dynami coupon
	     * @var int
	     */
	    public $discount_amount = 0;

	    /**
	     * Label of coupon
	     * @var string
	     */
	    public $label_coupon = 'discount';


        /**
         * Returns single instance of the class
         *
         * @return \YITH_WC_Dynamic_Discounts
         * @since 1.0.0
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since  1.0.0
         * @author Emanuela Castorina
         */
        public function __construct() {
            $label = preg_replace('/\s+/', '', YITH_WC_Dynamic_Pricing()->get_option( 'coupon_label' ));
            $this->label_coupon = strtolower($label);

            add_action('woocommerce_cart_updated', array($this, 'apply_coupon_cart_discount'));
            add_action('woocommerce_removed_coupon', array($this, 'apply_discount') );
        }

        /**
         * Return pricing rules filtered and validates
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since  1.0.0
         * @author Emanuela Castorina
         */
        function get_discount_rules(){
            $this->discount_rules = $this->filter_valid_rules(  YITH_WC_Dynamic_Pricing()->get_option( 'cart-rules' ) );
            return $this->discount_rules;
        }



	    /**
	     * Filter valid cart discount rules
	     * @param $cart_rules
	     *
	     * @return array
	     */
	    function filter_valid_rules( $cart_rules ){

            $valid_rules = array();

            if( !$cart_rules || empty( $cart_rules ) || ! array($cart_rules) || $cart_rules=='no' ){
                return $valid_rules;
            }

            // check if cart have coupon
            $cart_have_coupon = ywdpd_check_cart_coupon();

            foreach( $cart_rules as $key=>$cart_rule ){

                if( $cart_rule['active'] != 'yes' ){
                    continue;
                }

                if( isset( $cart_rule['discount_amount'] ) && $cart_rule['discount_amount'] == '' ){
                    continue;
                }elseif( isset( $cart_rule['discount_type'] ) && $cart_rule['discount_type'] == 'percentage'){
                    $cart_rule['discount_amount'] = ( $cart_rule['discount_amount'] > 1) ? $cart_rule['discount_amount']/100 : $cart_rule['discount_amount'];
                }

                //DATE SCHEDULE VALIDATION
                if ( $cart_rule['schedule_from'] != '' || $cart_rule['schedule_to'] != '' ) {
                    if (!YITH_WC_Dynamic_Pricing_Helper()->validate_schedule($cart_rule['schedule_from'], $cart_rule['schedule_to'])) {
                        continue;
                    }
                }

                // DISCOUNT CAN BE COMBINED WITH COUPON
                if ( ! isset( $cart_rule['discount_combined'] ) && $cart_have_coupon ) {
                    continue;
                }


                $sub_rules_valid = true;

                if ( !empty( $cart_rule['rules'] ) ) {

                    foreach ( $cart_rule['rules'] as $index => $r ) {

                        if ( !$sub_rules_valid ) {

                            break;
                        }

                        $discount_type = $r['rules_type'];

                        switch ( $discount_type ) {
                            case '':
                                continue;
                                break;
                            case 'customers_list':
                            case 'customers_list_excluded':
                            case 'role_list':
                            case 'role_list_excluded':

                               if ( !isset( $r['rules_type_' . $discount_type] ) || ! YITH_WC_Dynamic_Pricing_Helper()->validate_user( $discount_type, $r['rules_type_' . $discount_type] ) ) {
                                    $sub_rules_valid = false;
                                    continue;
                                }

                                break;
                            case 'products_list':
                            case 'products_list_and':
                            case 'products_list_excluded':
                            case 'categories_list':
                            case 'categories_list_and':
                            case 'categories_list_excluded':                            
                            case 'tags_list':
                            case 'tags_list_and':
                            case 'tags_list_excluded':
	                        case 'brand_list':
	                        case 'brand_list_and':
	                        case 'brand_list_excluded':
                                if ( ! isset( $r['rules_type_' . $discount_type] ) || empty( $r['rules_type_' . $discount_type] ) || !YITH_WC_Dynamic_Pricing_Helper()->validate_product_in_cart( $discount_type, $r['rules_type_' . $discount_type] ) ) {
                                    $sub_rules_valid = false;
                                    continue;
                                }

                                break;
                            case 'num_of_orders':
                            case 'amount_spent':
                            case 'sum_item_quantity':
                            case 'sum_item_quantity_less':
                            case 'count_cart_items_less':
                            case 'count_cart_items_at_least':
                            case 'subtotal_at_least':
                            case 'subtotal_less':
                                    $s = 'valid_' . $discount_type;

                                    if ( !isset( $r['rules_type_' . $discount_type] ) || $r['rules_type_' . $discount_type] == '' || !YITH_WC_Dynamic_Pricing_Helper()->$s( $r['rules_type_' . $discount_type] ) ) {
                                        $sub_rules_valid = false;

                                        continue;
                                    }
                                    break;
                            default:

                        }

                        $sub_rules_valid = apply_filters( 'yit_ywdpd_sub_rules_valid', $sub_rules_valid, $discount_type, $r );
                    }
                }


                if( $sub_rules_valid ){
                    $valid_rules[$key] = $cart_rule;
                }

            }

           return $valid_rules;
        }

	    /**
	     * Apply discount to cart items
	     * @return void
	     */
	    public function apply_discount() {
            $rules    = $this->get_discount_rules();
            $discount = $this->get_discount_amount();

            if ( ! empty ( $rules ) && $discount > 0 ) {
                add_filter( 'woocommerce_get_shop_coupon_data', array( $this, 'create_coupon_cart_discount' ), 10, 2 );
                add_filter( 'woocommerce_cart_totals_coupon_html', array( $this, 'coupon_cart_html'), 10, 2);
                add_filter( 'woocommerce_coupon_message', array( $this, 'coupon_cart_discount_message' ), 10, 3 );
            }
        }

	    /**
	     * Create coupon cart discount
	     * @param $args
	     * @param $code
	     *
	     * @return array
	     */
	    function create_coupon_cart_discount( $args, $code ) {

            if ( $code == $this->label_coupon ) {
                $args = array(
                    'amount'           => $this->discount_amount,
                    'apply_before_tax' => 'yes',
                    'type'             => 'fixed_cart',
                    'free_shipping'    => 'no',
                    'individual_use'   => 'no',
                );
                return $args;
            }
        }

		/**
		 * Apply coupon cart discount to the cart
		 *
		 */
	    function  apply_coupon_cart_discount(){

            $rules    = $this->get_discount_rules();
            $discount = $this->get_discount_amount();

            if ( empty ( $rules ) || $discount <= 0 ) {
                return;
            }

            $coupon = new WC_Coupon( $this->label_coupon );
            $coupon->coupon_amount = $this->get_discount_amount();

            if ( $coupon->is_valid() && ! WC()->cart->has_discount( $this->label_coupon ) ){
                WC()->cart->add_discount( $this->label_coupon );
            }
        }

		/**
		 * Change the label of coupon
		 *
		 * @since   1.0.0
		 * @author  Emanuela Castorina
		 *
		 * @param $string
		 * @param $coupon
		 *
		 * @return string
		 */
        public function label_coupon( $string, $coupon ) {

            //change the label if the order is generated from a quote
            if ( $coupon->code != $this->label_coupon ) {
                return $string;
            }

            return $this->label_coupon;
        }

	    /**
	     * @param $value
	     * @param $coupon
	     *
	     * @return string
	     */
	    function coupon_cart_html( $value, $coupon ){
            if( $coupon->code == $this->label_coupon ){
                $amount = WC()->cart->get_coupon_discount_amount( $coupon->code, WC()->cart->display_cart_ex_tax );
                $discount_html = '-' . wc_price( $amount );
                return $discount_html;
            }
            return $value;
        }

	    /**
	     * @param $msg
	     * @param $msg_code
	     * @param $coupon
	     *
	     * @return string
	     */
	    function coupon_cart_discount_message( $msg, $msg_code, $coupon ){
            if( $coupon->code == $this->label_coupon ){
                return '';
            }else{
                return $msg;
            }
        }

	    /**
	     * @return float|int
	     */
	    function get_discount_amount(){
		    $discount = 0;

		    if ( ! empty( $this->discount_rules ) ) {

			    $subtotal = apply_filters( 'ywdpd_get_subtotal', YITH_WC_Dynamic_Pricing()->get_option( 'calculate_discounts_tax' ) == 'tax_excluded' ?  WC()->cart->subtotal_ex_tax : WC()->cart->subtotal );

			    foreach ( $this->discount_rules as $rule ) {
				    if ( $rule['discount_type'] == 'percentage' ) {
					    $discount += $subtotal * $rule['discount_amount'];
				    } elseif ( $rule['discount_type'] == 'price' ) {
					    $discount += $rule['discount_amount'];
				    }elseif ( $rule['discount_type'] == 'fixed-price' ) {
					    $discount += ( $subtotal - $rule['discount_amount'] ) > 0 ? ($subtotal - $rule['discount_amount']) : 0 ;
				    }
			    }
		    }

            $this->discount_amount = $discount;
            return $discount;
        }
    }
}

/**
 * Unique access to instance of YITH_WC_Dynamic_Pricing class
 *
 * @return \YITH_WC_Dynamic_Discounts
 */
function YITH_WC_Dynamic_Discounts() {
    return YITH_WC_Dynamic_Discounts::get_instance();
}

