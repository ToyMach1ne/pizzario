<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YITH_Checkout_Addon' ) ) {

	/**
	 * Add SSN and VAT to user information and on checkout process
	 *
	 * @class   YITH_Checkout_Addon
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_Checkout_Addon {

		private $ssn_number_text = 'billing_vat_ssn';
		private $ssn_number_suffix = '_vat_ssn';
		private $ssn_number_args = 'vat_ssn';
		private $ssn_number_args_replace = '{vat_ssn}';
		private $ssn_number_metakey = '_billing_vat_ssn';

		private $vat_number_text = 'billing_vat_number';
		private $vat_number_suffix = '_vat_number';
		private $vat_number_args = 'vat_number';
		private $vat_number_args_replace = '{vat_number}';
		private $vat_number_metakey = '_billing_vat_number';

		private $ask_ssn_number = false;
		private $ask_vat_number = false;

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			self::$instance->ask_ssn_number = ( 'yes' == ywpi_get_option( 'ywpi_ask_ssn_number' ) ) ? true : false;
			self::$instance->ask_vat_number = ( 'yes' == ywpi_get_option( 'ywpi_ask_vat_number' ) ) ? true : false;

			return self::$instance;
		}

		public function initialize() {
			//  If ssn number and vat number are not enabled, skip the following process
			if ( $this->ask_ssn_number || $this->ask_vat_number ) {
				// Add custom field and sort fields to billing
				add_filter( 'woocommerce_billing_fields', array( $this, 'add_sort_custom_fields' ) );

				// Format edit address form
				add_filter( 'woocommerce_address_to_edit', array( $this, 'customize_edit_address_form_fields' ) );

				// Register custom fields of checkout
				add_action( 'woocommerce_checkout_order_processed', array( $this, 'process_shop_order_meta' ), 10, 2 );
				//add_action( 'woocommerce_process_shop_order_meta', array( $this, 'process_shop_order_meta' ), 10, 2 );

				// Adds order meta to template email
				add_filter( 'woocommerce_email_order_meta_fields', array(
					$this,
					'add_custom_email_order_meta',
				), 10, 3 );

				// Update formatted billing address, for view order page
				add_filter( 'woocommerce_order_formatted_billing_address', array(
					$this,
					'update_formatted_billing_address_order',
				), 10, 2 );

				// Update formatted shipping address, for view order page
				add_filter( 'woocommerce_order_formatted_shipping_address', array(
					$this,
					'update_formatted_billing_address_order',
				), 10, 2 );


				// Update formatted billing address, for my-account page
				add_filter( 'woocommerce_my_account_my_address_formatted_address', array(
					$this,
					'update_formatted_billing_address_myaccount',
				), 10, 2 );

				// Update replacements for formatted address
				add_filter( 'woocommerce_formatted_address_replacements', array(
					$this,
					'update_address_replacement',
				), 10, 2 );

				// Adds custom detail to customer profile (public view)
				add_filter( 'woocommerce_admin_billing_fields', array( $this, 'add_custom_billing_field' ) );

				// Retrieve customer meta to customer profile
				add_filter( 'woocommerce_found_customer_details', array( $this, 'retrieve_customer_meta' ), 10, 3 );

				// Retrieve custom customer meta, for customer profile (admin view)
				add_filter( 'woocommerce_customer_meta_fields', array( $this, 'add_profile_details' ) );
			}
		}

		public function __construct() {

		}


		/**
		 * Customize "Edit address" form fields
		 *
		 * @access public
		 *
		 * @param $fields array Array of fields
		 *
		 * @return array Customized array of fields
		 * @since  1.0.0
		 */
		public function customize_edit_address_form_fields( $fields ) {
			if ( isset( $fields['billing_company'] ) ) {
				$fields['billing_company']['class'] = array( 'form-row-first' );
			}

			return $fields;
		}

		/**
		 * Add custom billing fields to all forms
		 *
		 * @access public
		 *
		 * @param $fields array Array of fields
		 *
		 * @return array Customized array of fields
		 * @since  1.0.0
		 */
		public function add_sort_custom_fields( $fields ) {

			if ( $this->ask_ssn_number ) {
				$is_required                      = 'yes' == get_option( 'ask_ssn_number_required', 'no' );
				$fields[ $this->ssn_number_text ] = array(
					'label'    => __( 'SSN', 'yith-woocommerce-pdf-invoice' ),
					'required' => $is_required,
					'class'    => array( 'form-row-wide' ),
					'clear'    => 1,
				);
			}

			if ( $this->ask_vat_number ) {
				$add_vat_number = true;

				//  Check for compatibility mode
				if ( ywpi_use_woo_eu_vat_number() ) {
					$add_vat_number = false;
				}

				if ( $add_vat_number ) {
					$is_required = 'yes' == get_option( 'ask_vat_number_required', 'no' );

					$fields[ $this->vat_number_text ] = array(
						'label'    => __( 'VAT', 'yith-woocommerce-pdf-invoice' ),
						'required' => $is_required,
						'class'    => array( 'form-row-wide' ),
						'clear'    => 1,
					);
				}

			}

			return $fields;
		}

		/**
		 * Saves VAT/SSN when processing order save (admin side)
		 *
		 * @access public
		 *
		 * @param $order_id int Order id
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function process_shop_order_meta( $order_id ) {

			if ( $this->ask_ssn_number ) {
				if ( isset( $_POST[ $this->ssn_number_metakey ] ) ) {
					yit_save_prop(wc_get_order($order_id), $this->ssn_number_metakey, stripslashes( $_POST[ $this->ssn_number_metakey ] ) );
				}
			}

			if ( $this->ask_vat_number ) {

				//  Save VAT number value but pay attention to compatibility mode

				if ( ywpi_use_woo_eu_vat_number() ) {

					if ( isset( $_POST["vat_number"] ) ) {

						yit_save_prop(wc_get_order($order_id), $this->vat_number_metakey, stripslashes( $_POST["vat_number"] ) );
					}

				} elseif ( isset( $_POST[ $this->vat_number_metakey ] ) ) {
					yit_save_prop(wc_get_order($order_id), $this->vat_number_metakey, stripslashes( $_POST[ $this->vat_number_metakey ] ) );
				}
			}
		}

		/**
		 * Sets custom fields to new order email
		 *
		 * @access public
		 *
		 * @param $fields        array Array of fields to be used in mail construction
		 * @param $sent_to_admin bool Flag set when mail will be carbon copied to admin
		 * @param $order         \WC_Order Order object
		 *
		 * @return array Filtered array of fields
		 * @since  1.0.0
		 */
		public function add_custom_email_order_meta( $fields, $sent_to_admin, $order ) {

			if ( $this->ask_ssn_number ) {
				$fields[] = array(
					'label' => __( 'SSN', 'yith-woocommerce-pdf-invoice' ),
					'value' => wptexturize( yit_get_prop( $order, $this->ssn_number_metakey, true ) ),
				);
			}

			if ( $this->ask_vat_number ) {
				$add_vat_number = true;

				//  Check for compatibility mode
				if ( ywpi_use_woo_eu_vat_number() ) {
					$add_vat_number = false;
				}

				if ( $add_vat_number ) {
					$fields[] = array(
						'label' => __( 'VAT', 'yith-woocommerce-pdf-invoice' ),
						'value' => wptexturize( yit_get_prop( $order, $this->vat_number_metakey, true ) ),
					);
				}
			}

			return $fields;
		}

		/**
		 * Adds VAT/SSN field to formatted address for order's admin view
		 *
		 * @access public
		 *
		 * @param           $billing_fields array Array of fields to be used in formatted address
		 * @param \WC_Order Order           object
		 *
		 * @return array Array of filtered fields
		 * @since  1.0.0
		 */
		public function update_formatted_billing_address_order( $billing_fields, $order ) {

			if ( $this->ask_ssn_number ) {
				$billing_fields[ $this->ssn_number_text ] = yit_get_prop( $order, $this->ssn_number_metakey, true );
			}

			if ( $this->ask_vat_number ) {
				$billing_fields[ $this->vat_number_text ] = yit_get_prop( $order, $this->vat_number_metakey, true );
			}

			return $billing_fields;
		}

		/**
		 * Adds VAT/SSN field to formatted address for my-account user view
		 *
		 * @access public
		 *
		 * @param           $billing_fields array Array of fields to be used in formatted address
		 * @param \WC_Order Order           object
		 *
		 * @return array Array of filtered fields
		 * @since  1.0.0
		 */
		public function update_formatted_billing_address_myaccount( $billing_fields, $customer_id ) {
			if ( $this->ask_ssn_number ) {
				$billing_fields[ $this->ssn_number_text ] = get_user_meta( $customer_id, $this->ssn_number_text, true );
			}

			if ( $this->ask_vat_number ) {
				$billing_fields[ $this->vat_number_text ] = get_user_meta( $customer_id, $this->vat_number_text, true );
			}

			return $billing_fields;
		}

		/**
		 * Update address replacement for all site address formats
		 *
		 * @access public
		 *
		 * @param $replacements array Array of available replacements
		 * @param $args         array Array of arguments to use in replacements
		 *
		 * @return array Filtered array of replacements
		 * @since  1.0.0
		 */
		public function update_address_replacement( $replacements, $args ) {

			$replacements[ $this->ssn_number_args_replace ] = '';
			$replacements[ $this->vat_number_args_replace ] = '';

			if ( $this->ask_ssn_number ) {
				if ( ! empty( $args[ $this->ssn_number_text ] ) ) {
					$replacements[ $this->ssn_number_args_replace ] = 'SSN: ' . $args[ $this->ssn_number_text ];
				}
			}

			if ( $this->ask_vat_number ) {
				if ( ! empty( $args[ $this->vat_number_text ] ) ) {
					$replacements[ $this->vat_number_args_replace ] = 'VAT: ' . $args[ $this->vat_number_text ];
				}
			}

			return $replacements;
		}

		/**
		 * Adds VAT/SSN fields to order admin view
		 *
		 * @access public
		 *
		 * @param $billing_data array Array of registered fields
		 *
		 * @return array Filtered array of registered fields
		 * @since  1.0.0
		 */
		public function add_custom_billing_field( $billing_data ) {

			if ( $this->ask_ssn_number ) {
				$billing_data[ $this->ssn_number_args ] = array(
					'label' => __( 'SSN', 'yith-woocommerce-pdf-invoice' ),
					'show'  => false,
				);
			}


			if ( $this->ask_vat_number ) {
				$billing_data[ $this->vat_number_args ] = array(
					'label' => __( 'VAT', 'yith-woocommerce-pdf-invoice' ),
					'show'  => false,
				);
			}

			return $billing_data;
		}

		/**
		 * Retrieve customer VAT/SSN from user meta, and adds it to customer data for WC processing
		 *
		 * @access public
		 *
		 * @param $customer_data array Array of available customer data
		 * @param $user_id       int User id
		 * @param $type_to_load  string Billing or Shipping (for VAT only billing is a valid option)
		 *
		 * @return array Filtered array of available customer data
		 */
		public function retrieve_customer_meta( $customer_data, $user_id, $type_to_load ) {

			if ( $this->ask_ssn_number ) {
				$customer_data[ $type_to_load . $this->ssn_number_suffix ] = get_user_meta( $user_id, $type_to_load . $this->ssn_number_suffix, true );
			}

			if ( $this->ask_vat_number ) {
				$customer_data[ $type_to_load . $this->vat_number_suffix ] = get_user_meta( $user_id, $type_to_load . $this->vat_number_suffix, true );
			}

			return $customer_data;
		}

		/**
		 * Add VAT/SSN to user profile details
		 *
		 * @access public
		 *
		 * @param $meta array Array of available meta
		 *
		 * @return array Filtered array of available meta
		 * @since  1.0.0
		 */
		public function add_profile_details( $meta ) {

			if ( $this->ask_ssn_number ) {
				$meta['billing']['fields'][ $this->ssn_number_text ] = array(
					'label'       => __( 'SSN', 'yith-woocommerce-pdf-invoice' ),
					'description' => '',
				);
			}

			if ( $this->ask_vat_number ) {
				$meta['billing']['fields'][ $this->vat_number_text ] = array(
					'label'       => __( 'VAT', 'yith-woocommerce-pdf-invoice' ),
					'description' => '',
				);
			}

			return $meta;
		}
	}
}