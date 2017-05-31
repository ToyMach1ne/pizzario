<?php
/**
 * WooCommerce - WooCommerce Email Theme
 * Themes allow enhanced customization and editing of WooCommerce store emails.
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Instantiate plugin.
 */
$GLOBALS['WC_Email_Theme_WooCommerce'] = new WC_Email_Theme_WooCommerce();

/**
 *
 * Main Class.
 */
class WC_Email_Theme_WooCommerce {
	
	/*
	*  Constructor
	*
	*  Construct all the all the neccessary actions, filters and functions for the plugin
	*
	*  @date	20-08-2014
	*  @since	1.0
	*
	*/
	public function __construct() {
		
		/* Register Email Theme */
		add_action( 'register_email_theme',	array( $this, 'register_email_theme' ) );
	}
	
	/**
	 * Register Email Theme
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function register_email_theme() {
		
		ec_register_email_theme(
			'woocommerce',
			array(
				'name'                         => 'WooCommerce (copy, editable)',
				'description'                  => '',
				'template_folder'              => WC_EMAIL_CONTROL_DIR . '/templates',
				'sections'                     => $this->get_sections(),
				'settings'                     => $this->get_settings(),
				'woocoomerce_required_version' => '2.5',
			)
		);
	}
	
	public function get_sections() {
		
		$sections = array(
			array(
				"name" => __( "Text", "email-control" ),
				"id"   => "text_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Appearance", "email-control" ),
				"id"   => "appearance_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Header", "email-control" ),
				"id"   => "header_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Footer", "email-control" ),
				"id"   => "footer_section",
				"desc" => "",
				"tip"  => "",
			),
		);
		
		return $sections;
	}
	
	/**
	 * Get Settings
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function get_settings() {
		
		// Types
		// title, sectionend, text, email, number, color, password,
		// textarea, select, multiselect, radio, checkbox, image_width,
		// single_select_page, single_select_country, multi_select_countries
		
		$settings = array(
			
			
			
			// New Order (new_order, admin-new-order.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "New customer order", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "new_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "You have received an order from [ec_firstname] [ec_lastname]. The order is as follows:", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "new_order",
				"section" => "text_section",
			),
			
			
			
			
			// Cancelled Order (cancelled_order, admin-cancelled-order.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Cancelled order", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "cancelled_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "The order [ec_order] for [ec_firstname] [ec_lastname] has been cancelled.", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "cancelled_order",
				"section" => "text_section",
			),
			
			
			
			
			// Failed Order (failed_order, admin-failed-order.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Failed order", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "failed_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Payment for order [ec_order] from [ec_firstname] [ec_lastname] has failed.", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "failed_order",
				"section" => "text_section",
			),
			
			
			
			
			// On-hold Order (customer_on_hold_order, customer-on-hold-order.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Thank you for your order", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_on_hold_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Your order is on-hold until we confirm payment has been received. Your order details are shown below for your reference:", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_on_hold_order",
				"section" => "text_section",
			),
			
			
			
			
			// Processing Order (customer_processing_order, customer-processing-order.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Thank you for your order", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_processing_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Your order has been received and is now being processed. Your order details are shown below for your reference:", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_processing_order",
				"section" => "text_section",
			),
			
			
			
			
			// Completed Order (customer_completed_order, customer-completed-order.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Your order is complete", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_completed_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Hi there. Your recent order on [ec_site_name] has been completed. Your order details are shown below for your reference:", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_completed_order",
				"section" => "text_section",
			),
			
			
			
			
			// Refunded Order - full (customer_refunded_order, customer-refunded-order.php)
			array(
				"name"    => __( "Heading (full)", "email-control" ),
				"id"      => "heading_full",
				"type"    => "text",
				"default" => __( "Your order has been fully refunded", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text_full",
				"type"    => "textarea",
				"default" => __( "Hi there. Your order on [ec_site_name] has been refunded.", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"section" => "text_section",
			),
			
			// Refunded Order - partial (customer_refunded_order, customer-refunded-order.php)
			array(
				"name"    => __( "Heading (partial)", "email-control" ),
				"id"      => "heading_partial",
				"type"    => "text",
				"default" => __( "You have been partially refunded", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text_partial",
				"type"    => "textarea",
				"default" => __( "Hi there. Your order on [ec_site_name] has been partially refunded.", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"section" => "text_section",
			),
			
			
			
			
			// Customer Invoice - payment pending (customer_invoice, customer-invoice.php)
			array(
				"name"    => __( "Heading (payment pending)", "email-control" ),
				"id"      => "heading_pending",
				"type"    => "text",
				"default" => __( "Order [ec_order show='#,number' hide='container'] details", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text_pending",
				"type"    => "textarea",
				"default" => __( "An order has been created for you on [ec_site_link]. To pay for this order please use the following link: [ec_pay_link]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"section" => "text_section",
			),
			
			// Customer Invoice - payment complete (customer_invoice, customer-invoice.php)
			array(
				"name"    => __( "Heading (payment complete)", "email-control" ),
				"id"      => "heading_complete",
				"type"    => "text",
				"default" => __( "Order [ec_order show='#,number' hide='container'] details", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text_complete",
				"type"    => "textarea",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"section" => "text_section",
			),
			
			
			
			
			// Customer Note (customer_note, customer-note.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => "A note has been added to your order",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_note",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Hello, a note has just been added to your order:\n\n[ec_customer_note]\n\nFor your reference, your order details are shown below.\n\n", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_note",
				"section" => "text_section",
			),
			
			
			
			
			// Reset Password (customer_reset_password, customer-reset-password.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Password Reset Instructions", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_reset_password",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Someone requested that the password be reset for the following account:\n\nUsername: [ec_user_login]\n\nIf this was a mistake, just ignore this email and nothing will happen.\n\nTo reset your password, visit the following address:\n[ec_reset_password_link]", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_reset_password",
				"section" => "text_section",
			),
			
			
			
			
			// New Account (customer_new_account, customer-new-account.php)
			array(
				"name"    => __( "Heading", "email-control" ),
				"id"      => "heading",
				"type"    => "text",
				"default" => __( "Welcome to [ec_site_name hide='container']", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_new_account",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", "email-control" ),
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Thanks for creating an account on [ec_site_name]. Your username is [ec_user_login].\n\nYou can access your account area to view your orders and change your password here: [ec_account_link].", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_new_account",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Password Regenerated Text", "email-control" ),
				"id"      => "main_text_generate_pass",
				"type"    => "textarea",
				"default" => __( "Your password has been automatically generated: [ec_user_password]", "email-control" ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_new_account",
				"section" => "text_section",
			),
			
			
			
			
			// all
			
			array(
				"name"     => __( "Base Color", "email-control" ),
				"id"       => "base_color",
				"type"     => "color",
				"default"  => "#557da1",
				"desc"     => "",
				"tip"      => "",
				"email-type"  => "all",
				// "class" => "ec-half",
				"section"  => "appearance_section",
			),
			
			array(
				"name"     => __( "Background Colour", "email-control" ),
				"id"       => "background_color",
				"type"     => "color",
				"default"  => "#f5f5f5",
				"desc"     => "",
				"tip"      => "",
				"email-type"  => "all",
				// "class" => "ec-half",
				"section"  => "appearance_section",
			),
			
			array(
				"name"     => __( "Body Background Colour", "email-control" ),
				"id"       => "body_background_color",
				"type"     => "color",
				"default"  => "#fdfdfd",
				"desc"     => "",
				"tip"      => "",
				"email-type"  => "all",
				// "class" => "ec-half",
				"section"  => "appearance_section",
			),
			
			array(
				"name"     => __( "Body Text Colour", "email-control" ),
				"id"       => "body_text_color",
				"type"     => "color",
				"default"  => "#505050",
				"desc"     => "",
				"tip"      => "",
				"email-type"  => "all",
				// "class" => "ec-half",
				"section"  => "appearance_section",
			),
			
			array(
				"name"    => __( "Product Images", "email-control" ),
				"id"      => "product_thumbnail",
				"type"    => 'checkbox',
				"default" => 'no',
				"desc"    => '',
				"tip"     => '',
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			
			
			
			
			
			array(
				"name"    => __( "Logo", "email-control" ),
				"id"      => "header_image",
				"type"    => "image_upload",
				"default" => get_option( 'woocommerce_email_header_image' ),
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"section" => "header_section",
			),
			
			array(
				"name"    => __( "Footer Text", "email-control" ),
				"id"      => "footer_text",
				"type"    => "textarea",
				"default" => __( "[ec_site_name] – Powered by WooCommerce", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"css"     => "height:47px;",
				"section" => "footer_section",
			),
		
		
		
		);
		
		return $settings;
	}
}
