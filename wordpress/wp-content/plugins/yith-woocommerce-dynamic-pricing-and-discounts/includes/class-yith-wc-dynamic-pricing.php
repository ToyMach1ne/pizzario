<?php

if ( !defined( 'ABSPATH' ) || !defined( 'YITH_YWDPD_VERSION' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements features of YITH WooCommerce Dynamic Pricing and Discounts
 *
 * @class   YITH_WC_Dynamic_Pricing
 * @package YITH WooCommerce Dynamic Pricing and Discounts
 * @since   1.0.0
 * @author  Yithemes
 */
if ( !class_exists( 'YITH_WC_Dynamic_Pricing' ) ) {

	/**
	 * Class YITH_WC_Dynamic_Pricing
	 */
	class YITH_WC_Dynamic_Pricing {

        /**
         * Single instance of the class
         *
         * @var \YITH_WC_Dynamic_Pricing
         */

        protected static $instance;

        /**
         * The name for the plugin options
         *
         * @access public
         * @var string
         * @since 1.0.0
         */
        public $plugin_options = 'yit_ywdpd_options';

        public $validated_rules = array();

        public $exlusion_rules = array();

        public $adjust_rules  = array();

        public $adjust_counter  = array();

        public $pricing_rules_options = array();

        public $cart_rules_options = array();


        /**
         * Returns single instance of the class
         *
         * @return \YITH_WC_Dynamic_Pricing
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

            $this->pricing_rules_options = include( YITH_YWDPD_DIR.'plugin-options/pricing-rules-options.php');
            $this->cart_rules_options = include( YITH_YWDPD_DIR.'plugin-options/cart-rules-options.php');

            /* plugin */
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );


        }

        /**
         * Return pricing rules filtered and validates
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since  1.0.0
         * @author Emanuela Castorina
         */
        function get_pricing_rules(){
            $pricing_rules  = $this->filter_valid_rules(  $this->get_option( 'pricing-rules' ) );
            return $pricing_rules;
        }

		/**
		 * Return pricing rules validates
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 *
		 * @param $pricing_rules
		 *
		 * @return array
		 */
        function filter_valid_rules( $pricing_rules ){

            if ( !$pricing_rules || !is_array( $pricing_rules ) ) {
                return;
            }

            foreach( $pricing_rules as $key => $rule ){

                //check if the rule is active of the value of discount_amount is empty
                if( $rule['active'] != 'yes'){
                    continue;
                }

                //DATE SCHEDULE VALIDATION
                if ( isset($rule['schedule_from']) && isset($rule['schedule_to']) && ($rule['schedule_from'] != '' || $rule['schedule_to'] != '' )) {
                    if ( ! YITH_WC_Dynamic_Pricing_Helper()->validate_schedule( $rule['schedule_from'], $rule['schedule_to'] ) ) {
                        continue;
                    }
                }

                //USER VALIDATION
                if( isset($rule['user_rules']) &&  ( $rule['user_rules'] != 'everyone' && ! YITH_WC_Dynamic_Pricing_Helper()->validate_user( $rule['user_rules'], $rule['user_rules_'.$rule['user_rules']]) )){
                    continue;
                }

                //PRODUCTS VALIDATION (APPLY TO) check if the list of products or categories is empty
                if( isset($rule['apply_to']) && $rule['apply_to'] != 'all_products' &&  isset($rule['apply_to_'.$rule['apply_to']]) && ! is_array( $rule['apply_to_'.$rule['apply_to']] ) ){
                    continue;
                }
                //PRODUCTS VALIDATION (ADJUSTMENT) check if the list of products or categories is empty
                if( isset($rule['apply_adjustment']) && ( $rule['apply_adjustment'] != 'all_products' && $rule['apply_adjustment'] != 'same_product' ) && isset( $rule['apply_adjustment_'.$rule['apply_adjustment']] )  && ! is_array( $rule['apply_adjustment_'.$rule['apply_adjustment']] ) ){
                    continue;
                }

                //DISCOUNT RULES VALIDATION
                if ( isset( $rule['discount_mode'] )  && $rule['discount_mode'] == 'bulk' ) {
                    if ( isset( $rule['rules'] ) ) {
                        foreach ( $rule['rules'] as $index => $discount_rule ) {

                            if ( $discount_rule['min_quantity'] == '' || $discount_rule['min_quantity'] == 0 ) {
                                $rule['rules'][$index]['min_quantity'] = 1;
                            }

                            if ( $discount_rule['max_quantity'] == '' ) {
                                $rule['rules'][$index]['max_quantity'] = '*';
                            }

                            if ( isset( $discount_rule['type_discount'] ) && $discount_rule['type_discount'] == 'percentage' && $discount_rule['discount_amount'] > 1 ) {
                                $rule['rules'][$index]['discount_amount'] = $discount_rule['discount_amount'] / 100;
                            }
                        }
                    }
                }
                elseif (  isset( $rule['discount_mode'] )  && $rule['discount_mode'] == 'special_offer' ) {
                    $special_offer = $rule['so-rule'];

                    if ( $special_offer['purchase'] == '' || $special_offer['purchase'] == 0 ) {
                        $rule['so-rule']['purchase'] = 1;
                    }

                    if ( $special_offer['receive'] == '' ) {
                        $rule['so-rule']['receive'] = '*';
                    }

                    if ( $special_offer['type_discount'] == 'percentage' &&  $special_offer['discount_amount'] > 1 ) {
                        $rule['so-rule']['discount_amount'] = $special_offer['discount_amount'] / 100;
                    }
                }

                $this->validated_rules[$key] = $rule;

            }

            return $this->validated_rules;
        }

		/**
		 * Add applied rules to single cart item
		 *
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 *
		 * @param $cart_item_key
		 * @param $cart_item
		 *
		 * @return bool
		 */
        function get_applied_rules_to_product( $cart_item_key, $cart_item ) {

            $exclude = apply_filters( 'ywdpd_get_applied_rules_to_product_exclude' , empty( $cart_item ) , $cart_item );

            if ( $exclude ) {
                return false;
            }

            foreach ( $this->validated_rules as $key_rule => $rule ) {

	            // DISCOUNT CAN BE COMBINED WITH COUPON
	            if ( isset( $rule['disable_with_other_coupon'] ) && ywdpd_check_cart_coupon() ) {
//	            	error_log('nonvalida');
//	            	error_log( print_r( $rule, true) );
		            continue;
	            }

	            if ( !YITH_WC_Dynamic_Pricing_Helper()->validate_apply_to( $key_rule, $rule, $cart_item_key, $cart_item ) ) {
	            	continue;
                }
            }
        }


        /**
         * Add applied rules to single cart item
         *
         *
         * @since  1.0.0
         * @author Emanuela Castorina
         */
        function get_exlusion_rules(){
            if( ! empty($this->exlusion_rules) ){
                return $this->exlusion_rules;
            }

            $exclusion_rules = array();
            foreach( $this->validated_rules as $rule ){
                if( $rule['discount_mode'] == 'exclude_items' ){
                    $exclusion_rules[] = $rule;
                }
            }

            $this->exlusion_rules = $exclusion_rules;

            return $this->exlusion_rules;
        }

		function check_discount( $product ) {

        	if( apply_filters('ywdpd_exclude_products_from_discount', false, $product)){
				return false;
			}

			foreach ( $this->validated_rules as $rule ) {
				if ( YITH_WC_Dynamic_Pricing_Helper()->valid_product_to_apply_bulk( $rule, $product, true ) && ( is_single() || ( isset( $rule['show_in_loop'] ) && $rule['show_in_loop'] == 1 && $rule['apply_adjustment'] == 'same_product' ) ) ) {
					return true;
				}
			}
			return false;
		}

	    /**
	     * @param $default_price
	     * @param $product
	     *
	     * @return int
	     */
	    function get_discount_price( $default_price, $product ){

            $current_difference = 0;
		    $discount_price = $default_price;
            foreach( $this->validated_rules as $rule ){
                $even_onsale = ( isset( $rule['apply_on_sale'] ) ) ? 1 : 0;

				if( $product->sale_price != '' && $product->sale_price != $default_price && ! $even_onsale){
					continue;
				}

                if ( YITH_WC_Dynamic_Pricing_Helper()->valid_product_to_apply_bulk( $rule, $product, false ) && ( is_single() || ( isset( $rule['show_in_loop'] ) && $rule['show_in_loop'] == 1 && $rule['apply_adjustment'] == 'same_product' ) )  ) {

                	$is_exclusive = ( isset( $rule['apply_with_other_rules'] ) && $rule['apply_with_other_rules'] == 1 ) ? 0 : 1;

	                if( $rule && isset( $rule['rules'] ) &&  $rule['rules']){
	                    foreach( $rule['rules'] as $qty_rule ){
		                    if( $qty_rule['min_quantity'] == 1 ){
			                    switch( $qty_rule['type_discount'] ){
				                    case 'percentage':
					                    $current_difference = $discount_price * $qty_rule['discount_amount'];
					                    break;
				                    case 'price':
					                    $current_difference = $qty_rule['discount_amount'];
					                    break;
				                    case 'fixed-price':
					                    $current_difference = $discount_price - $qty_rule['discount_amount'];
					                    break;
				                    default:
			                    }
		                    }
	                    }

		                $discount_price = ( ( $discount_price - $current_difference ) < 0 ) ? 0 : ( $discount_price - $current_difference ) ;

                    }

                   // if( $is_exclusive ){
                        break;
                    //}
                }
            }


            return $discount_price;
        }

		/**
		 * Return all adjustments to single cart item
		 *
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 *
		 * @param $cart_item
		 * @param $cart_item_key
		 */
        public function apply_discount( $cart_item, $cart_item_key ){


	        $discounts     = $cart_item['ywdpd_discounts'];
	        $product_id    = ( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] != '' ) ? $cart_item['variation_id'] : $cart_item['product_id'];
	        $product       = wc_get_product( $product_id );
	        $has_exclusive = $this->has_exclusive( $discounts );

	        remove_filter( 'woocommerce_get_price', array( YITH_WC_Dynamic_Pricing_Frontend(), 'get_price' ), 10);
            $default_price =  $cart_item['data']->get_price();
	        $price = $current_price = $default_price;
            $difference = 0;

            foreach ( $discounts as $discount ) {

                if( ! isset( $discount['discount_amount'] ) || ! isset( $discount['discount_mode'] ) ){
	                continue;
                }

	            $dm = $discount['discount_amount'];
	            $key = $discount['key'];

                if( $dm == 'exclude' ){
                    $price = $current_price = $default_price;
                    $difference = 0;
                }

                if ( ! $discount['onsale'] && ( $product->get_sale_price() !== '' && $product->get_sale_price() !== $product->get_regular_price() ) ) {
	                continue;
                }

                //check if the discount has an exclusive rule
                if( $has_exclusive && !$discount['exclusive'] ){
	               continue;
                }

                $current_difference = 0;
                if( $discount['discount_mode'] == 'bulk' && isset( $dm['type']) ){
                    switch( $dm['type'] ){
                        case 'percentage':
                            $current_difference = $price * $dm['amount'];
                            break;
                        case 'price':
                            $current_difference = $dm['amount'];
                            break;
                        case 'fixed-price':
                            $current_difference = $price - $dm['amount'];
                            break;
                        default:
                    }
                }elseif( $discount['discount_mode'] == 'special_offer' && isset( $dm['type'])  ){

//	                error_log( 'id '. $cart_item['product_id'] );
//                	error_log('available_quantity '.$cart_item['available_quantity']);
					//calculate new price

	                if ( $dm['same_product'] && ( in_array( $dm['quantity_based'], array( 'cart_line', 'single_variation_product' ) ) || ( $dm['quantity_based'] == 'single_product' && $product->get_type() != 'variation' ) ) ) {
		                $adj_counter =  $this->adjust_counter[ $key ] = $dm['total_target'];
	                }elseif( $dm['same_product'] && ( $dm['quantity_based'] == 'single_product' && $product->get_type() == 'variation') ){
		                $parent_id = $product->post->ID;
		                $adj_counter = $this->adjust_counter[ $key.$parent_id ] = isset( $this->adjust_counter[ $key.$parent_id ]) ?  $this->adjust_counter[$key.$parent_id ] : $dm['total_target'];
	                }else{
		                $adj_counter = $this->adjust_counter[ $key ] = isset( $this->adjust_counter[ $key ] ) ?  $this->adjust_counter[ $key ] : $dm['total_target'];

	                }

//	                error_log( '$cart_item_key '. $cart_item_key );
//	                error_log( 'adjust_counter '. $adj_counter );

	                $a = ( $adj_counter > $cart_item['quantity'] ) ? $cart_item['quantity'] : $adj_counter;

	                $full_price_quantity = $cart_item['available_quantity'] - $a;
	                $discount_quantity = $a;
	                $normal_line_total   = $cart_item['quantity'] * $price;

	                switch ( $dm['type'] ) {
		                case 'percentage':
			                $difference_s        = $price - $price * $dm['amount'];
							//error_log($price);
			                $line_total          = ( $discount_quantity * $difference_s ) + ( $full_price_quantity * $price );
			                $current_difference  = ( $normal_line_total - $line_total ) / $cart_item['quantity'];
			                $current_difference  = $current_difference >= 0 ? $current_difference : 0;
			                break;

		                case 'price':
			                $difference_s        = floatval( $price ) - floatval( $dm['amount'] );
			                $difference_s        = $difference_s >= 0 ? $difference_s : 0;
			                $line_total          = ( $discount_quantity * $difference_s ) + ( $full_price_quantity * $price );
			                $current_difference  = ( $normal_line_total - $line_total ) / $cart_item['quantity'];
			                $current_difference  = $current_difference >= 0 ? $current_difference : 0;
			                break;
		                case 'fixed-price':
			                $difference_s = $dm['amount'];
			                $line_total = ($discount_quantity * $difference_s) + ($full_price_quantity * $price);
			                $current_difference  = ( $normal_line_total - $line_total ) / $cart_item['quantity'];
			                $current_difference  = $current_difference >= 0 ? $current_difference : 0;
		                default:
	                }

	                if( $dm['quantity_based'] == 'single_product' && $product->get_type() == 'variation' ){
		                if ( $dm['total_target'] > $cart_item['quantity'] ) {
			                $this->adjust_counter[ $key . $parent_id ] = $this->adjust_counter[ $key . $parent_id ] - $cart_item['quantity'];
			                WC()->cart->cart_contents[ $cart_item_key ]['available_quantity'] = 0;
		                }else{
			                WC()->cart->cart_contents[$cart_item_key]['available_quantity'] = $cart_item['quantity'] - $adj_counter;
			                $this->adjust_counter[ $key.$parent_id ] = 0;
		                }
	                }else{
		                if ( $dm['total_target'] > $cart_item['quantity'] ) {
			                $this->adjust_counter[ $key ] -= $cart_item['quantity'];
			                WC()->cart->cart_contents[ $cart_item_key ]['available_quantity'] = 0;
		                } else {
			                WC()->cart->cart_contents[ $cart_item_key ]['available_quantity'] = $cart_item['quantity'] - $adj_counter;
			                $this->adjust_counter[ $key ]                                     = 0;
		                }
	                }

                }

                $difference += $current_difference;
	            //error_log('$difference '.$difference);
                $price = ( ( $default_price - $difference ) < 0 ) ? 0 : ( $default_price - $difference ) ;
	            $price = round( $price , wc_get_price_decimals() );
	            //error_log('$price '.$price);
	            //error_log(' $this->adjust_counter[ $key ] '. $this->adjust_counter[ $key ] );
                WC()->cart->cart_contents[$cart_item_key]['ywdpd_discounts'][$discount['key']]['status']           = 'applied';
                WC()->cart->cart_contents[$cart_item_key]['ywdpd_discounts'][$discount['key']]['discount_applied'] = $current_difference;
                WC()->cart->cart_contents[$cart_item_key]['ywdpd_discounts'][$discount['key']]['current_price']    = $price;

                //check if the discount has an exclusive rule
                if( $has_exclusive && $discount['exclusive'] ){
                    break;
                }
            }
			remove_filter( 'woocommerce_get_price', array( YITH_WC_Dynamic_Pricing_Frontend(), 'get_price' ), 10);
            WC()->cart->cart_contents[$cart_item_key]['ywdpd_discounts']['default_price'] =  ( WC()->cart->tax_display_cart == 'excl' ) ? $product->get_price_excluding_tax() : $product->get_price_including_tax();
			add_filter( 'woocommerce_get_price', array( YITH_WC_Dynamic_Pricing_Frontend(), 'get_price' ), 10, 2 );

			WC()->cart->cart_contents[$cart_item_key]['data']->price = $price;
			WC()->cart->cart_contents[$cart_item_key]['data']->has_dynamic_price = true;

        }

	    /**
	     * @param $discounts
	     *
	     * @return bool
	     */
	    function has_exclusive( $discounts ){
            foreach( $discounts as $discount ){
                if( isset($discount['exclusive']) && $discount['exclusive'] == 1 ){
                    return true;
                }
            }
            return false;
        }

		/**
		 * Check if a product has specific categories
		 *
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 *
		 * @param $product_id
		 * @param $categories
		 * @param $min_amount
		 *
		 * @return bool
		 */
        function product_categories_validation( $product_id, $categories, $min_amount ) {
            $categories_cart = YITH_WC_Dynamic_Pricing_Helper()->cart_categories;
            $intersect_cart_category = array_intersect( $categories, $categories_cart );

            $return = false;

            if( is_array( $intersect_cart_category ) ){
                $categories_counter = YITH_WC_Dynamic_Pricing_Helper()->categories_counter;
                $categories_of_item = wc_get_product_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
                $intersect_product_category = array_intersect( $categories_of_item, $categories );

                if( is_array( $intersect_product_category ) ){
                    $tot = 0;
                    foreach( $categories as $cat ){
                        $tot += $categories_counter[$cat];
                    }

                    if( $tot >= $min_amount ){
                        $return = true;
                    }
                }

            }

            return $return;

        }

		/**
		 * Check if a product has specific tags
		 *
		 *
		 * @since  1.1.0
		 * @author Emanuela Castorina
		 *
		 * @param $product_id
		 * @param $tags
		 * @param $min_amount
		 *
		 * @return bool
		 */
        function product_tags_validation( $product_id, $tags, $min_amount ) {
            $tags_cart = YITH_WC_Dynamic_Pricing_Helper()->cart_tags;
            $intersect_cart_tag = array_intersect( $tags, $tags_cart );

            $return = false;

            if( is_array( $intersect_cart_tag ) ){
                $tags_counter = YITH_WC_Dynamic_Pricing_Helper()->tags_counter;
                $tags_of_item = wc_get_product_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );
                $intersect_product_tag = array_intersect( $tags_of_item, $tags );

                if( is_array( $intersect_product_tag ) ){
                    $tot = 0;
                    foreach( $tags as $tag ){
                        $tot += $tags_counter[$tag];
                    }

                    if( $tot >= $min_amount ){
                        $return = true;
                    }
                }

            }

            return $return;

        }

        /**
         * Load YIT Plugin Framework
         *
         * @since  1.0.0
         * @return void
         * @author Emanuela Castorina
         */
        public function plugin_fw_loader() {
            if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if( ! empty( $plugin_fw_data ) ){
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }

        /**
         * Get options from db
         *
         * @access public
         * @since 1.0.0
         * @author  Emanuela Castorina
         * @param $option string
         * @return mixed
         */
		public function get_option( $option, $value = false ) {
			// get all options
			$options = get_option( $this->plugin_options );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $value;
		}


    }
}

/**
 * Unique access to instance of YITH_WC_Dynamic_Pricing class
 *
 * @return \YITH_WC_Dynamic_Pricing
 */
function YITH_WC_Dynamic_Pricing() {
    return YITH_WC_Dynamic_Pricing::get_instance();
}

