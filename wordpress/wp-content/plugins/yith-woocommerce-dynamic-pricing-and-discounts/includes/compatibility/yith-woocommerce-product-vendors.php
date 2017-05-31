<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWDPD_VERSION' ) ) {
	exit; // Exit if accessed directly
}


/**
 * YWDPD_Multivendor class to add compatibility with YITH WooCommerce Multivendor
 *
 * @class   YWDPD_Multivendor
 * @package YITH WooCommerce Dynamic Pricing and Discounts
 * @since   1.0.0
 * @author  Yithemes
 */
if ( ! class_exists( 'YWDPD_Multivendor' ) ) {

	/**
	 * Class YWDPD_Multivendor
	 */
	class YWDPD_Multivendor {

		/**
		 * Single instance of the class
		 *
		 * @var \YWDPD_Multivendor
		 */
		protected static $instance;



		/**
		 * Returns single instance of the class
		 *
		 * @return \YWDPD_Multivendor
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
		 * Initialize class and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 */
		public function __construct() {

			add_filter('yit_ywdpd_pricing_rules_options', array($this, 'add_pricing_rule_option'));
			add_filter('yit_ywdpd_cart_rules_options', array($this, 'add_cart_rule_option'));
			add_filter('yith_ywdpd_admin_localize', array($this, 'add_localize_params'));

			// panel type category search
			add_action( 'wp_ajax_ywdpd_vendor_search', array( $this, 'json_search_vendors' ) );
			add_action( 'wp_ajax_nopriv_ywdpd_vendor_search', array( $this, 'json_search_vendors' ) );

		}


		/**
		 * Add pricing rules options in settings panels
		 * @param $rules
		 *
		 * @return array
		 */
		public function add_pricing_rule_option( $rules ) {
			$new_rule = array();
			foreach ( $rules as $key =>$rule ) {
				$new_rule[ $key ] = $rule;

				if( $key == 'apply_to' || $key == 'apply_adjustment' ){
					$new_rule[ $key ]['vendor_list'] = __( 'Include a list of vendors', 'ywdpd' );
					$new_rule[ $key ]['vendor_list_excluded'] = __( 'Exclude a list of vendors', 'ywdpd' );
				}
			}

			return $new_rule;
		}


		/**
		 * Add pricing rules options in settings panels
		 * @param $rules
		 *
		 * @return array
		 */
		public function add_cart_rule_option( $rules ) {

			$new_rule['rules_type'] = array();
			if( isset( $rules['rules_type'] ) ){
				foreach ( $rules['rules_type'] as $key =>$rule ) {
					$new_rule['rules_type'][ $key ] = $rule;

					if( $key == 'products'  ){
						$new_rule['rules_type'][ $key ]['options']['vendor_list'] = __( 'Include a list of vendors', 'ywdpd' );
						$new_rule['rules_type'][ $key ]['options']['vendor_list_excluded'] = __( 'Exclude a list of vendors', 'ywdpd' );
					}
				}
			}
			$new_rule['discount_type'] = $rules['discount_type'];
			return $new_rule;
		}


		/**
		 * Add localize params to javascript
		 *
		 * @param $params
		 *
		 * @return mixed
		 */
		public function add_localize_params( $params ) {
			$params['search_vendor_nonce'] =  wp_create_nonce( 'search-vendor' );

			return $params;
		}


		public function json_search_vendors(  ) {

			check_ajax_referer( 'search-vendor', 'security' );

			ob_start();

			$term = (string) wc_clean( stripslashes( $_GET['term'] ) );

			if ( empty( $term ) ) {
				die();
			}
			global $wpdb;
			$terms = $wpdb->get_results( 'SELECT name, slug, wpt.term_id FROM ' . $wpdb->prefix . 'terms wpt, ' . $wpdb->prefix . 'term_taxonomy wptt WHERE wpt.term_id = wptt.term_id AND wptt.taxonomy = "'.YITH_Vendors ()->get_taxonomy_name ().'" and wpt.name LIKE "%'.$term.'%" ORDER BY name ASC;' );

			$found_vendors = array();

			if ( $terms ) {
				foreach ( $terms as $cat ) {
					$found_vendors[$cat->term_id] = ( $cat->name ) ? $cat->name : 'ID: ' . $cat->slug;
				}
			}


			wp_send_json( $found_vendors );
		}

	}

}

/**
 * Unique access to instance of YWDPD_Multivendor class
 *
 * @return \YWDPD_Multivendor
 */
function YWDPD_Multivendor() {
	return YWDPD_Multivendor::get_instance();
}

YWDPD_Multivendor();