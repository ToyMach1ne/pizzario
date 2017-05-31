<?php
/**
 * Plugin Name: WooCommerce Email Customizer
 * Plugin URI: https://codecanyon.net/item/email-customizer-for-woocommerce/8654473?ref=cxThemes&utm_source=email%20customizer&utm_campaign=commercial%20plugin%20upsell&utm_medium=plugins%20page%20view%20details
 * Description: WooCommerce Email Customizer plugin allows you to fully customize the styling, colors, logo and text in the emails sent from your WooCommerce store.
 * Author: cxThemes
 * Author URI: https://codecanyon.net/item/email-customizer-for-woocommerce/8654473?ref=cxThemes&utm_source=email%20customizer&utm_campaign=commercial%20plugin%20upsell&utm_medium=plugins%20page%20view%20details
 * Version: 3.09
 * Text Domain: email-control
 * Domain Path: /languages/
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    cxThemes
 * @category  WooCommerce, WordPress
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define Constants
 */
define( 'WC_EMAIL_CONTROL_VERSION', '3.09' );
define( 'WC_EMAIL_CONTROL_REQUIRED_WOOCOMMERCE_VERSION', 2.5 );
define( 'WC_EMAIL_CONTROL_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_EMAIL_CONTROL_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'WC_EMAIL_CONTROL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // woocommerce-email-control/ec-email-control.php

/**
 * Update Check
 */
require 'includes/updates/cxthemes-plugin-update-checker.php';
$wc_email_control_update = new CX_Email_Control_Plugin_Update_Checker( __FILE__, 'woocommerce-email-control' );

/**
 * Check if WooCommerce is active, and is required WooCommerce version.
 */
if ( ! WC_Email_Control::is_woocommerce_active() || version_compare( get_option( 'woocommerce_version' ), WC_EMAIL_CONTROL_REQUIRED_WOOCOMMERCE_VERSION, '<' ) ){
	add_action( 'admin_notices', array( 'WC_Email_Control', 'woocommerce_inactive_notice' ) );
	return;
}

/**
 * Check if any conflicting plugins are active, then deactivate ours.
 */
if ( WC_Email_Control::is_conflicting_plugins_active() ) {
	add_action( 'admin_notices', array( 'WC_Email_Control', 'is_conflicting_plugins_active_notice' ) );
	return;
}
	
/**
 * Includes
 */
include_once( 'includes/ec-woo-back-compat-functions.php' );
include_once( 'includes/ec-functions.php' );
include_once( 'includes/ec-shortcodes.php' );
include_once( 'includes/ec-settings.php' );
include_once( 'ec-theme-woocommerce.php' ); // Theme
include_once( 'ec-theme-vanilla.php' ); // Theme
include_once( 'ec-theme-deluxe.php' ); // Theme
include_once( 'ec-theme-supreme.php' ); // Theme

/**
 * Instantiate plugin.
 */
global $cxec_email_control;
$cxec_email_control = WC_Email_Control::get_instance();

/**
 * Main Class.
 */
class WC_Email_Control {
	
	private $id = 'woocommerce_email_control';
	
	private static $instance;
	
	/**
	* Get Instance creates a singleton class that's cached to stop duplicate instances
	*/
	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}
	
	/**
	* Construct empty on purpose
	*/
	private function __construct() {}
	
	/**
	* Init behaves like, and replaces, construct
	*/
	public function init() {
		
		// Translations
		add_action( 'init', array( $this, 'load_translation' ) );
		
		// Register email themes.
		add_action( 'init', array( $this, 'register_email_themes' ), 100 );
		
		// Enqueue Scripts/Styles - in head of admin page
		add_action( 'admin_enqueue_scripts', array( $this, 'ec_head_scripts' ) );
		
		// Enqueue Scripts/Styles - in head of email preview page
		add_action( 'ec_render_preview_head_scripts', array( $this, 'ec_head_scripts' ), 102 );
		
		// Add menu item
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		
		// Ajax saving of options
		add_action( 'wp_ajax_save_meta', array( $this, 'save_meta' ) );
		add_action( 'wp_ajax_nopriv_save_meta', array( $this, 'nopriv_save_meta' ) );
		
		// Ajax saving of options new
		add_action( 'wp_ajax_save_option',	 array( $this, 'save_option' ) );
		add_action( 'wp_ajax_nopriv_save_option',	 array( $this, 'nopriv_save_option' ) );
		
		// Ajax send email
		add_action( 'wp_ajax_ec_send_email',	 array( $this, 'send_email' ) );
		add_action( 'wp_ajax_nopriv_ec_send_email',	 array( $this, 'nopriv_send_email' ) );
		
		// Ajax saving of all edit settings
		add_action( 'wp_ajax_save_edit_email', array( $this, 'save_edit_email' ) );
		add_action( 'wp_ajax_nopriv_save_edit_email', array( $this, 'nopriv_save_edit_email' ) );
		
		// Check Templates
		add_filter( 'wc_get_template', array( $this, 'ec_get_template' ), 10, 5 );
		
		//Email Customizer - Admin and Preview pages only
		if ( isset( $_REQUEST["page"] ) && $_REQUEST["page"] == $this->id ) {
			
			// Remove all notifications
			remove_all_actions( 'admin_notices' );
			
			// Remove admin bar
			require_once( 'includes/toolbar-removal/wp-toolbar-removal.php');
						
			if ( ! isset( $_REQUEST["ec_render_email"] ) ) {
				
				// Email Customizer - Admin page only
				add_action( 'in_admin_header', array( $this, 'ec_render_admin_page' ) );
			}
			else {
				
				// Email Customizer - Preview page only
				add_filter( 'wp_print_scripts', array( $this, 'deregister_all_scripts' ), 101 );
				add_action( 'wp_print_scripts', array( $this, 'ec_head_scripts' ), 102 );
				add_action( 'admin_init', array( $this, 'ec_render_preview_page' ) );
			}
		}
		
		// Add Button in WooCommerce->Settings->Email
		add_action( 'woocommerce_settings_tabs_email', array( $this, 'woocommerce_settings_button' ) );
		
		// Setup global email args.
		add_action( 'woocommerce_before_template_part', array( $this, 'ec_before_template_prep_email_args_global' ) , 10, 4 );
		add_action( 'woocommerce_after_template_part', array( $this, 'ec_after_template_prep_email_args_global' ) , 10, 4 );
		
		// Setup options filtering.
		add_action( 'woocommerce_before_template_part', array( $this, 'ec_before_template_filter_options' ) , 10, 4 );
		
		// Modify email headers.
		add_action( 'woocommerce_email_headers', array( $this, 'ec_email_headers' ) );
		
		// Complicated method to re-aply inline styling after WooCommerce has,
		// due to WooCommerce having applied wordwrap 60 to the html before
		// our css is applied, whcih results in some of the CSS not applying.
		add_filter( 'wc_get_template', array( $this, 'ec_globalize_email_object' ), 10, 5 );
		add_action( 'woocommerce_mail_content', array( $this, 'ec_style_inline' ), 85 );
		
		// Other simpler WooCommerce emails - Content.
		// add_filter( 'woocommerce_email_content_low_stock', array( $this, 'woocommerce_simple_email_content' ), 10, 2 );
		// add_filter( 'woocommerce_email_content_no_stock', array( $this, 'woocommerce_simple_email_content' ), 10, 2 );
		// add_filter( 'woocommerce_email_content_backorder', array( $this, 'woocommerce_simple_email_content' ), 10, 2 );
		// Other simpler WooCommerce emails - Headers.
		// add_filter( 'woocommerce_email_headers', array( $this, 'woocommerce_simple_email_headers' ), 10, 2 );
	}
	
	/**
	 * Localization
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public static function load_translation() {
		
		// Domain ID - used in eg __( 'Text', 'pluginname' )
		$domain = 'email-control';
		
		// get the languages locale eg 'en_US'
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		
		// Look for languages here: wp-content/languages/pluginname/pluginname-en_US.mo
		load_textdomain( $domain, WP_LANG_DIR . "/{$domain}/{$domain}-{$locale}.mo" ); // Don't mention this location in the docs - but keep it for legacy.
		
		// Look for languages here: wp-content/languages/plugins/pluginname-en_US.mo
		load_textdomain( $domain, WP_LANG_DIR . "/plugins/{$domain}-{$locale}.mo" );
		
		// Look for languages here: wp-content/languages/pluginname-en_US.mo
		load_textdomain( $domain, WP_LANG_DIR . "/{$domain}-{$locale}.mo" );
		
		// Look for languages here: wp-content/plugins/pluginname/languages/pluginname-en_US.mo
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . "/languages/" );
	}
	
	/**
	 * Allows hooking by the themes wishing to be initialized
	 *
	 * @date	20-04-2016
	 * @since	2.36
	 */
	public static function register_email_themes() {
		
		// Register email themes.
		do_action( 'register_email_theme' );
	}
	
	/**
	 * Dergister all scripts & styles
	 *
	 * Deregister all scripts so the email preview is
	 * css clean and free of other plugins js bugs
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	function deregister_all_scripts() {
		
		global $wp_scripts,  $wp_styles;
		
		// Dequeue All Scripts
		if ( false != $wp_scripts->queue ) {
			foreach( $wp_scripts->queue as $script ) {
				$wp_scripts->dequeue( $script );
				
				// if ( isset( $wp_scripts->registered[$script] ) ) {
				// 	$wp_scripts->registered[$script]->deps = array();
				// }
			}
		}
		
		// Dequeue All Styles
		if ( false != $wp_styles->queue ) {
			foreach( $wp_styles->queue as $script ) {
				$wp_styles->dequeue( $script );
				
				// if ( isset( $wp_styles->registered[$script] ) ) {
				// 	$wp_styles->registered[$script]->deps = array();
				// }
			}
		}
	}
	
	/**
	 * Enqueue CSS and Scripts
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function ec_head_scripts() {
		
		global $woocommerce, $wp_scripts, $current_screen, $pagenow;
		
		// All Pages
		wp_register_style( 'woocommerce_admin', $woocommerce->plugin_url() . '/assets/css/admin.css' );
		wp_enqueue_style( 'woocommerce_admin' );
		
		wp_enqueue_script( 'woocommerce_admin' );
		
		wp_register_script( 'jquery-tiptip', $woocommerce->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array('jquery') );
		wp_enqueue_script( 'jquery-tiptip' );
		
		// Email Customizer - Admin page only
		if 	(
				( isset( $_REQUEST["page"] ) && $_REQUEST["page"] == $this->id )
				||
				( isset( $_REQUEST["page"] ) && $_REQUEST["page"] == "wc-settings" )
				||
				( isset( $_REQUEST["ec_render_email"] ) )
				||
				( isset( $current_screen->id ) && $current_screen->id == "shop_order" )
				||
				( 'plugins.php' == $pagenow )
			) {
			
			
			// For image uplaoder on settings page_link
			wp_enqueue_media();
			
			/**
			 * Fontello.
			 */
			wp_enqueue_style(
				'cxecrt-icon-font',
				WC_EMAIL_CONTROL_URI . '/assets/css/fontello/css/cxectrl-icon-font.css',
				array(),
				WC_EMAIL_CONTROL_VERSION
			);
			
			// Email Customizer Custom Scripts
			wp_register_style( 'email-control', WC_EMAIL_CONTROL_URI . '/assets/css/email-control-back-end.css', array(), WC_EMAIL_CONTROL_VERSION, 'screen' );
			wp_enqueue_style( 'email-control' );
			wp_register_script( 'email-control', WC_EMAIL_CONTROL_URI . '/assets/js/email-control-back-end.js', array( 'jquery', 'jquery-tiptip', 'iris' ), WC_EMAIL_CONTROL_VERSION );
			wp_enqueue_script( 'email-control' );
			wp_localize_script('email-control', 'woocommerce_email_control', array(
				'home_url' => get_home_url(),
				'admin_url' => admin_url(),
				'ajaxurl' => admin_url('admin-ajax.php')
			));
			
			// Open Sans - incase it has not yet
			wp_enqueue_style( 'open-sans' );
			
		}
		
		wp_enqueue_style( 'open-sans' );
		
		// Email Customizer - Preview page only
		if ( ( isset( $_REQUEST["page"] ) && $_REQUEST["page"] == $this->id ) && isset( $_REQUEST["ec_render_email"] ) ) {
			
			// Load jQuery
			wp_enqueue_script( 'jquery' );
			
			// Load Dashicons
			wp_enqueue_style( 'dashicons' );
			
			// Email Customizer Custom Scripts
			wp_register_style( 'email-control', WC_EMAIL_CONTROL_URI . '/assets/css/email-control-back-end.css', array(), WC_EMAIL_CONTROL_VERSION, 'screen' );
			wp_enqueue_style( 'email-control' );
			
		}
		
		
		if ( 'plugins.php' == $pagenow ) {
			
			// Plugin Update JS.
			wp_enqueue_script( 'email-control-update-js', WC_EMAIL_CONTROL_URI . '/assets/js/email-control-update.js', array( 'jquery' ), WC_EMAIL_CONTROL_VERSION );
			wp_localize_script('email-control-update-js', 'woocommerce_email_control', array(
				'home_url' => get_home_url(),
				'admin_url' => admin_url(),
				'ajaxurl' => admin_url('admin-ajax.php')
			));
		}
		
	}
	
	/*
	*  Save option
	*
	*  @date	20-08-2014
	*  @since	1.0
	*/
	function save_meta() {

		/*
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "save_meta_nonce")) {
		  exit("No naughty business please");
		}
		*/
		
		$current_user = wp_get_current_user();
		
		$field_name  = ( isset( $_REQUEST["field_name"] ) ) ? $_REQUEST["field_name"] : "" ;
		$field_value  = ( isset( $_REQUEST["field_value"] ) ) ? $_REQUEST["field_value"] : "" ;

		if ( strpos( $field_name, 'userspecifc' ) ) {
			
			//Save the option specific to the current user
			update_user_meta( $current_user->ID, $field_name, $field_value );
		}
		else {
			
			//Save the option to the global options
			update_option( $field_name, $field_value );
		}
		
		die();
	}
	
	function nopriv_save_meta() {
		_e('You must be logged in', 'email-control' );
		die();
	}
	
	/*
	*  Save option new
	*
	*  @date	20-08-2014
	*  @since	1.0
	*/
	function save_option() {
		/*
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "save_option_nonce")) {
		  exit("No naughty business please");
		}
		*/
		
		$field_name  = ( isset( $_REQUEST["field_name"] ) ) ? $_REQUEST["field_name"] : "" ;
		$field_value  = ( isset( $_REQUEST["field_value"] ) ) ? $_REQUEST["field_value"] : "" ;
		
		update_option( $field_name, $field_value );
		
		die();
	}
	
	function nopriv_save_option() {
		_e( 'You must be logged in', 'email-control' );
		die();
	}
	
	/*
	*  Ajax send email
	*
	*  @date	20-08-2014
	*  @since	1.0
	*/
	public function send_email() {
		global $order, $woocommerce, $cxec_email_control;
		
		$email_type = $_REQUEST['ec_email_type'];
		$order_id   = $_REQUEST['ec_email_order'];
		// $email_addresses = $_REQUEST['ec_email_addresses'];
		// $email_theme = $_REQUEST['ec_email_theme'];
		
		// Handle button actions
		if ( ! empty( $_REQUEST['ec_email_type'] ) ) {

			// Load mailer
			$mailer = $woocommerce->mailer();
			$mails = $mailer->get_emails();
			
			// Ensure gateways are loaded in case they need to insert data into the emails
			$woocommerce->payment_gateways();
			$woocommerce->shipping();
			
			$email_to_send = wc_clean( $_REQUEST['ec_email_type'] );

			if ( ! empty( $mails ) ) {
				foreach ( $mails as $mail ) {
					if ( $mail->id == $email_to_send ) {
						
						// Get the chosen order.
						$order = new WC_Order( $order_id );
						
						// Add the new custom recipient email address.
						// add_filter( 'woocommerce_email_recipient_' . $mail->id, array( $this, 'woocommerce_email_recipient' ) ); // Old method.
						$mail->recipient = ( isset( $_REQUEST['ec_email_addresses'] ) ? $_REQUEST['ec_email_addresses'] : NULL );
						
						// Init the mail.
						$cxec_email_control->populate_mail_object( $order, $mail );
						
						// Send the mail.
						$mail->send( $mail->get_recipient(), $mail->get_subject(), $mail->get_content(), $mail->get_headers(), $mail->get_attachments() );
					}
				}
			}
		}
		
		die();
	}
	
	function nopriv_send_email() {
		_e( 'You must be logged in', 'email-control' );
		die();
	}
	
	/*
	* Filter used to modify the receivers email address so tester can specify their own.
	 */
	function woocommerce_email_recipient () {
		if ( isset( $_REQUEST['ec_email_addresses'] ) ) {
			return $_REQUEST['ec_email_addresses'];
		}
	}
	
	/*
	*  Ajax send email
	*
	*  @date	20-08-2014
	*  @since	1.0
	*/
	function trigger_send_email( $order, $mail, $email_addresses) {
		
		$email_addresses = str_replace( " ", "", $email_addresses );
		$email_addresses = explode( ",", $email_addresses );
		
		$mail->object		= new WC_Order( $order );
		
		$mail->find[] = '{order_date}';
		$mail->replace[] = date_i18n( wc_date_format(), strtotime( ec_order_get_date_created( $mail->object ) ) );

		$mail->find[] = '{order_number}';
		$mail->replace[] = $mail->object->get_order_number();
		
		
		$mail->recipient	= $mail->object->billing_email;
		
		$email_subject		= $mail->get_subject();
		$email_content		= $mail->get_content();
		$email_headers		= $mail->get_headers();
		$email_attachments	= $mail->get_attachments();
		
		
		foreach ( $email_addresses as $email_address ) {
			echo $mail->send( $email_address, $email_subject, $email_content, $email_headers, $email_attachments );
		}
		
		die();
	}
	
	/*
	*  Save all edit options.
	*
	*  @date	20-08-2014
	*  @since	1.0
	*/
	public function save_edit_email() {
				
		$email_type		= ( $_REQUEST['ec_email_type'] ) ? $_REQUEST['ec_email_type'] : false ;
		$email_id		= ( $_REQUEST['ec_email_id'] ) ? $_REQUEST['ec_email_id'] : false ;
		
		$settings = ec_get_settings( $email_id );
		
		EC_Settings::save_fields( $settings );
		
		die();
	}
	
	function nopriv_save_edit_email() {
		_e( 'You must be logged in', 'email-control' );
		die();
	}
	
	/**
	 * Render admin page.
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function ec_render_admin_page() {
		
		require_once( 'pages/ec-admin-page.php');
	}
	
	/**
	 * Render preview page.
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function ec_render_preview_page() {
		
		require_once( 'pages/ec-preview-page.php');
	}
	
	/**
	 * Add a submenu item to the WooCommerce menu
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function admin_menu() {
		
		add_submenu_page(
			'woocommerce',
			__( 'Email Customizer', 'email-control' ),
			__( 'Email Customizer', 'email-control' ),
			'manage_woocommerce',
			$this->id,
			array( $this, 'ec_render_admin_page' )
		);
	}
	
	/**
	 * Add info and button to WooCommerce->settings->email page
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	function woocommerce_settings_button( $data ) {
		
		global $woocommerce, $wp_scripts, $current_screen;
		
		$ec_url = "";
		$ec_url .= admin_url();
		$ec_url .= "admin.php";
		$ec_url .= "?";
		$ec_url .= "page=woocommerce_email_control";
		
		if ( isset( $_REQUEST["section"] ) ) {
			
			if ( class_exists('WC') ) {
				$mailer = WC()->mailer();
				$mails = $mailer->get_emails();
			}
			else{
				$mailer = $woocommerce->mailer();
				$mails = $mailer->get_emails();
			}
			
			if ( ! empty( $mails ) ) {
				foreach ( $mails as $mail ) {
					$email_type = str_replace( "wc_email_", "", $_REQUEST["section"] );
					if ( $mail->id == $email_type ) {
						$ec_url .= "&ec_email_type=" . $email_type;
					}
				}
			}
		}
		
		?>
		<div class="pe-wc-settings-holder">
			
			<?php if ( isset( $_REQUEST["section"] ) && $_REQUEST["section"] != "" ) { ?>
				
				<!-- Inner Tabs -->
				<h4>Email Customizer</h4>
				<p>
					<a class="button ec" href="<?php echo $ec_url ?>" target="preview_email">Preview Email</a>
					<?php _e( "Preview and test emails as they will appear in mail clients when received.", 'email-control' ) ?>
				</p>
				
			<?php } else { ?>
			
				<!-- First Tab -->
				<h3>Email Customizer</h3>
				<p>
					<a class="button ec" href="<?php echo $ec_url ?>" target="preview_email">Preview Email</a>
					<?php _e( "Preview and test emails as they will appear in mail clients when received.", 'email-control' ) ?>
				</p>
				
			<?php } ?>
			
		</div>
		<?php
	}
	
	/**
	 * Check for and return our template.
	 *
	 * WC 2.2 and above - added this filter recently so can't use until more regular support
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 *
	 * @return string	Template file location
	 */
	function ec_get_template( $located, $template_name, $args, $template_path, $default_path ) {
		
		// error_log( $located );
		
		// return $located;
		
		// echo "<br>";
		// echo "get_template";
		// echo "<br>";
		// echo "<br>";
		// echo 'located--------------------: ' . $located;
		// echo "<br>";
		// echo 'template_name----------: ' . $template_name;
		// echo "<br>";
		// echo "args------------------------: ";
		// print_r( $args );
		// echo "<br>";
		// echo 'template_path-----------: ' . $template_path;
		// echo "<br>";
		// echo 'default_path-------------: ' . $default_path;
		// echo "<br><br><br><br>";
		
		if ( in_array( 'emails', explode( '/', $template_name ) ) && ! in_array( 'plain', explode( '/', $template_name ) ) ) {
			
			global $ec_email_themes, $woocommerce, $collect_email_template;
			
			// If not set yet then set it.
			if ( ! isset( $collect_email_template ) ) $collect_email_template = array();
			
			// Use the selected theme from database
			$ec_theme_selected = get_option( 'ec_template' );
			
			// Overide selected theme with that passed by preview
			if ( isset( $_REQUEST["ec_email_theme_preview"] ) ) $ec_theme_selected = $_REQUEST["ec_email_theme_preview"];
			
			// Used to record how the template was found - so we can display it on the debugging info.
			$status = 'third-party';
			
			if ( is_array( $ec_email_themes ) && isset( $ec_theme_selected ) && $ec_theme_selected !== false ) {
				if ( array_key_exists( $ec_theme_selected, $ec_email_themes ) ) {
					
					// It's one of our themes so do custom filters
					add_filter( 'woocommerce_email_custom_details_header', '__return_empty_string' ); // Remove the header of the customer details.
					
					$this_template_pathinfo  = pathinfo( $template_name );
					$ec_theme_selected; // supreme
					$this_folder_name        = $this_template_pathinfo['dirname']; // emails
					$this_original_file_name = $this_template_pathinfo['filename'] . '.php'; // email-footer.php
					$this_modified_file_name = $this_template_pathinfo['filename'] . '-' . $ec_theme_selected . '.php'; // email-footer-supreme.php
					
					$this_template_versions = '';
					// if ( ! version_compare( WC()->version, '2.4', '>=' ) ) $this_template_versions = '-below-wc2.4';
					if ( version_compare( WC()->version, '2.5', '>=' ) && version_compare( WC()->version, '3', '<' ) ) {
						$this_template_versions = '-wc2.5-wc2.9';
					}
					else if ( ! version_compare( WC()->version, '2.5', '>=' ) ) {
						$this_template_versions = '-below-wc2.5';
					}
					
					$this_template = $located;
					
					/**
					 * Check our plugin locations.
					 */
					// Check our plugin for `templates/emails/email-footer-supreme.php` (old file structure/name).
					if ( isset( $ec_email_themes[$ec_theme_selected]['template_folder'] ) ) {
						$new_template = trailingslashit( $ec_email_themes[$ec_theme_selected]['template_folder'] ) . 'emails' . '/' . $this_modified_file_name;
						if ( file_exists( $new_template ) ) {
							$this_template = $new_template;
							$status = 'default';
						}
					}
					// Check our plugin for `templates/emails/supreme/email-footer.php`.
					if ( isset( $ec_email_themes[$ec_theme_selected]['template_folder'] ) ) {
						$new_template = trailingslashit( $ec_email_themes[$ec_theme_selected]['template_folder'] ) . 'emails' . '/' . $ec_theme_selected . '/' . $this_original_file_name;
						if ( file_exists( $new_template ) ) {
							$this_template = $new_template;
							$status = 'default';
						}
					}
					
					/**
					 * Check theme-as-plugin locations.
					 */
					// Check theme-plugin for `templates/emails/email-footer-supreme.php` (old file structure/name).
					$new_template = WC_EMAIL_CONTROL_DIR . '/templates' . $this_template_versions . '/' . 'emails' . '/' . $this_modified_file_name;
					if ( file_exists( $new_template ) ) {
						$this_template = $new_template;
						$status = 'default';
					}
					// Check theme-plugin for `templates/emails/supreme/email-footer.php`.
					$new_template = WC_EMAIL_CONTROL_DIR . '/templates' . $this_template_versions . '/' . 'emails' . '/' . $ec_theme_selected . '/' . $this_original_file_name;
					if ( file_exists( $new_template ) ) {
						$this_template = $new_template;
						$status = 'default';
					}
					
					/**
					 * Check the WooCommerce template locations.
					 */
					// Check WooCommerce for `emails/email-footer-supreme.php` (old file structure/name).
					// Check WooCommerce for `emails/supreme/email-footer.php`.
					$new_template = locate_template( array(
						trailingslashit( $woocommerce->template_path() ) . $this_folder_name . '/' . $this_modified_file_name,
						trailingslashit( $woocommerce->template_path() ) . $this_folder_name . '/' . $ec_theme_selected . '/' . $this_original_file_name,
					));
					if ( file_exists( $new_template ) ) {
						$this_template = $new_template;
						$status = 'override';
					}
					
					// Set the located as $this_template.
					$located = $this_template;
				}
			}
			
			// Store the templates used, and whether it was: default | third-party | override
			if ( ! array_key_exists( $located, $collect_email_template ) ) { // Make sure it doens't already exist.
				$collect_email_template[ $located ] = $status;
			}
		}
		
		// echo "<br>";
		// echo "get_template";
		// echo "<br>";
		// echo "<br>";
		// echo 'located--------------------: ' . $located;
		// echo "<br>";
		// echo 'template_name----------: ' . $template_name;
		// echo "<br>";
		// echo "args------------------------: ";
		// print_r( $args );
		// echo "<br>";
		// echo 'template_path-----------: ' . $template_path;
		// echo "<br>";
		// echo 'default_path-------------: ' . $default_path;
		// echo "<br><br><br><br>";
		
		return $located;
	}
	
	/**
	 * Modify options before template
	 *
	 * Only if one of the email templates are run, so as not to waste processing time,
	 * and check if REQUEST fields are being posted and rather use those.
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	function ec_before_template_filter_options( $template_name, $template_path, $located, $args ) {
		
		if ( FALSE !== strrpos( $template_name, 'email' ) ) {
			
			// Get active themes.
			$ec_theme_selected = false;
			if ( get_option( "ec_template" ) ) {
				$ec_theme_selected = get_option( "ec_template" );
			}
			if ( isset( $_REQUEST['ec_email_theme'] ) ) {
				$ec_theme_selected = $_REQUEST['ec_email_theme'];
			}
			
			// Modify if theres preview fields.
			$settings = ec_get_settings( $ec_theme_selected );
			
			if ( $settings ) {
				foreach ( $settings as $setting_key => $setting_value ) {
					
					$field_id	= $setting_value["id"];
					$field_type	= $setting_value["type"];
					
					add_filter( "default_option_{$field_id}", array('EC_Settings', 'ec_default_option') );
					add_filter( "option_{$field_id}", create_function( '$field_value', 'return EC_Settings::ec_render_option("'.$field_id.'", $field_value ); ' ) );
				}
			}
			
			// Only do this once, the first time an email template is called.
			remove_filter( 'woocommerce_before_template_part', array( $this, 'ec_before_template_filter_options' ) );
		}
	}
	
	/**
	 * Push the args into a global.
	 *
	 * To be used in the shortcodes. Has to be done this way while we are
	 * not getting passed $args due to not being able to use WC new filter
	 * in wc_get_template as it was only released late - around 2.2
	 *
	 * @date	20-08-2014
	 * @since	2.12
	 */
	function ec_before_template_prep_email_args_global( $template_name, $template_path, $located, $args ) {
		
		// Only do this for email templates.
		if ( FALSE !== strrpos( $template_name, 'email' ) ) {
			
			global $ec_email_args;
			
			if ( NULL == $ec_email_args ) {
				
				$ec_email_args = array_merge( $args, array( 'ec_template_name' => $template_name ) );
				
				// Debugging
				//echo 'start:&nbsp;' . $template_name;
			}
		}
	}
	
	/**
	 * Remove the args from global on last call to the template function.
	 *
	 * @date	09-02-2015
	 * @since	2.17
	 */
	function ec_after_template_prep_email_args_global( $template_name, $template_path, $located, $args ) {
		
		// Only do this for email templates.
		if ( FALSE !== strrpos( $template_name, 'email' ) ) {
			
			global $ec_email_args;
			
			if ( isset( $ec_email_args['ec_template_name'] ) && $template_name == $ec_email_args['ec_template_name'] ) {
				
				$ec_email_args = NULL;
				
				// Debugging
				//echo 'end:&nbsp;' . $template_name;
			}
		}
	}
	
	/**
	 * Force UTF-8 to email headers
	 *
	 * @date	09-02-2015
	 * @since	2.17
	 */
	function ec_email_headers( $headers ) {
		$headers = str_replace( "\r\n", '; charset=UTF-8' . "\r\n" , $headers );
		return $headers;
	}
	
	/**
	 * Format the other simpler WooCommerce emails - Content.
	 */
	function woocommerce_simple_email_content( $message ) {
		
		ob_start();
		wc_get_template('emails/email-header.php' );
		echo $message;
		wc_get_template('emails/email-footer.php' );
		return ob_get_clean();
	}
	
	/**
	 * Format the other simpler WooCommerce emails - Headers.
	 */
	function woocommerce_simple_email_headers() {
		
		return "Content-Type: text/html; charset=UTF-8\r\n";
	}
	
	/**
	 * Store the email object in a global so we can access it in the next steps.
	 */
	function ec_globalize_email_object( $located, $template_name, $args, $template_path, $default_path ) {
		global $cxec_cache_email_object, $cxec_cache_email_formatting_type;
		
		// Only if this is an html & email request.
		if ( isset( $args['email_heading'] ) && isset( $args['plain_text'] ) && FALSE === $args['plain_text'] ) {
			
			add_filter( 'woocommerce_email_styles', array( $this, 'ec_temporarily_empty_css' ) );
			$cxec_cache_email_formatting_type = 'html';
		}
		
		// Disable this, as `$args['email']` is not passed by `wc_get_template_html()` until WC 2.5
		// Only if this is an html & email request.
		/*if ( isset( $args['email']->email_type ) ) {
			
			add_filter( 'woocommerce_email_styles', array( $this, 'ec_temporarily_empty_css' ) );
			$cxec_cache_email_object = $args['email'];
		}*/
		
		return $located;
	}
	
	/**
	 * Temporarily stop any CSS being applied inline, so that we can do it all in the next step.
	 */
	function ec_temporarily_empty_css( $css ) {
		return '';
	}
	
	/**
	 * Apply CSS inline ourselves.
	 */
	function ec_style_inline( $message ) {
		global $cxec_cache_email_object, $cxec_cache_email_formatting_type;
		
		if ( isset( $cxec_cache_email_formatting_type ) && 'html' == $cxec_cache_email_formatting_type ) {
			
			// Remove the filter added above that blocks CSS being added before this.
			remove_filter( 'woocommerce_email_styles', array( $this, 'ec_temporarily_empty_css' ) );
			
			// Remove the break-line's applied already by WC wordwrap, replace them with a space.
			$message = str_replace( "\n", " ", $message );
			
			// Apply the CSS - merge it inline.
			$message = $this->ec_style_inline_for_old_wc( $message );
			
			// Remove Tabs to neaten up.
			$message = trim( preg_replace( '/\t+/', ' ', $message ) );
			
			// Now apply wordwrap - it should not break the display any more now that it's done after the CSS is inline-ed.
			$message = wordwrap( $message, 60 );
			
			// Unset the cache, ready fior next email.
			unset( $cxec_cache_email_formatting_type );
		}
		
		// Disable this, as `$args['email']` is not passed by `wc_get_template_html()` until WC 2.5
		/*if ( isset( $cxec_cache_email_object ) ) {
			
			// Remove the filter added above that blocks CSS being added before this.
			remove_filter( 'woocommerce_email_styles', array( $this, 'ec_temporarily_empty_css' ) );
			
			// Remove the break-line's applied already by WC wordwrap, replace them with a space.
			$message = str_replace( "\n", " ", $message );
			
			// Apply the CSS - merge it inline.
			$message = $cxec_cache_email_object->style_inline( $message );
			
			// Remove Tabs to neaten up.
			$message = trim( preg_replace( '/\t+/', ' ', $message ) );
			
			// Now apply wordwrap - it should not break the display any more now that it's done after the CSS is inline-ed.
			$message = wordwrap( $message, 60 );
			
			// Unset the cache, ready fior next email.
			unset( $cxec_cache_email_object );
		}*/
		
		return $message;
	}
	
	/**
	 * Temporarily copied and modified from WC
	 * 'Apply inline styles to dynamic content'.
	 *
	 * @param string|null $content
	 * @return string
	 */
	public function ec_style_inline_for_old_wc( $content ) {
		
		// make sure we only inline CSS for html emails
		if ( class_exists( 'DOMDocument' ) ) {
			ob_start();
			wc_get_template( 'emails/email-styles.php' );
			$css = apply_filters( 'woocommerce_email_styles', ob_get_clean() );

			// apply CSS styles inline for picky email clients
			try {
				$emogrifier = new Emogrifier( $content, $css );
				$content    = $emogrifier->emogrify();
			} catch ( Exception $e ) {
				$logger = new WC_Logger();
				$logger->add( 'emogrifier', $e->getMessage() );
			}
		}
		return $content;
	}
	
	/**
	 *
	 */
	public function populate_mail_object( $order, &$mail ) {
		global $cxec_cache_email_message;
		
		// New method of gathering email HTML by pushing the data up into a global.
		add_action( 'woocommerce_mail_content', array( $this, 'cancel_email_send' ), 90 );
		
		// Force the email to seem enabled in-case it has been tuned off programmatically.
		$mail->enabled = 'yes';
		
		/**
		 * Get a User ID for the preview.
		 */
		
		// Get the Customer user from the order, or the current user ID if guest.
		if ( 0 === ( $user_id = (int) get_post_meta( ec_order_get_id( $order ), '_customer_user', TRUE ) ) ) {
			$user_id = get_current_user_id();
		}
		$user = get_user_by( 'id', $user_id );
		
		/**
		 * Get a Product ID for the preview.
		 */
		
		// Set backup product_id
		$product_id = -1;
		
		// Get a product from the order.
		$items = $order->get_items();
		foreach ( $items as $item ) {
			$product_id = $item['product_id'];
			if ( NULL !== get_post( $product_id ) ) break;
			// $product_variation_id = $item['variation_id'];
		}
		
		// If it doesnt exist anymore then get the latest product.
		if ( NULL === get_post( $product_id ) ){
			
			$products_array = get_posts( array(
				'posts_per_page'   => 1,
				'orderby'          => 'date',
				'post_type'        => 'product',
				'post_status'      => 'publish',
			) );
			
			if ( isset( $products_array[0]->ID ) ){
				$product_id = $products_array[0]->ID;
			}
		}
		
		/**
		 * Generate the required email for use with Sending or Previewing.
		 *
		 * All the email types in all the varying plugins require specific
		 * properties to be set before they generate the email for our
		 * preview, or send a test email.
		 */
		
		$compatabiltiy_warning = FALSE; // Default.
		
		switch ( $mail->id ) {
			
			/**
			 * WooCommerce (default transactional mails).
			 */
			
			case 'new_order':
			case 'cancelled_order':
			case 'customer_processing_order':
			case 'customer_completed_order':
			case 'customer_refunded_order':
			case 'customer_on_hold_order':
			case 'customer_invoice':
			case 'failed_order':
				
				$mail->object                  = $order;
				$mail->find['order-date']      = '{order_date}';
				$mail->find['order-number']    = '{order_number}';
				$mail->replace['order-date']   = date_i18n( wc_date_format(), strtotime( ec_order_get_date_created( $mail->object ) ) );
				$mail->replace['order-number'] = $mail->object->get_order_number();
				break;
			
			case 'customer_new_account':
				
				$mail->object             = $user;
				$mail->user_pass          = '{user_pass}';
				$mail->user_login         = stripslashes( $mail->object->user_login );
				$mail->user_email         = stripslashes( $mail->object->user_email );
				$mail->recipient          = $mail->user_email;
				$mail->password_generated = TRUE;
				break;
			
			case 'customer_note':
				
				$mail->object                  = $order;
				$mail->customer_note           = 'Hello';
				$mail->find['order-date']      = '{order_date}';
				$mail->find['order-number']    = '{order_number}';
				$mail->replace['order-date']   = date_i18n( wc_date_format(), strtotime( ec_order_get_date_created( $mail->object ) ) );
				$mail->replace['order-number'] = $mail->object->get_order_number();
				break;
			
			case 'customer_reset_password':
				
				$mail->object     = $user;
				$mail->user_login = $user->user_login;
				$mail->reset_key  = '{{reset-key}}';
				break;
			
			/**
			 * WooCommerce Wait-list Plugin (from WooCommerce).
			 */
			
			case 'woocommerce_waitlist_mailout':
				
				$mail->object    = get_product( $product_id );
				$mail->find[]    = '{product_title}';
				$mail->replace[] = $mail->object->get_title();
				break;
				
			/**
			 * WooCommerce Subscriptions Plugin (from WooCommerce).
			 */
			
			case 'new_renewal_order':
			case 'new_switch_order':
			case 'customer_processing_renewal_order':
			case 'customer_completed_renewal_order':
			case 'customer_completed_switch_order':
			case 'customer_renewal_invoice':
				
				$mail->object = $order;
				break;
				
			case 'cancelled_subscription':
				
				$mail->object = FALSE;
				$compatabiltiy_warning = TRUE;
				break;
			
			/**
			 * Everything else, including all default WC emails.
			 */
			
			default:
				
				$mail->object = $order;
				$compatabiltiy_warning = TRUE;
				break;
		}
		
		return $compatabiltiy_warning;
	}
	
	/**
	 * New method of previewing emails.
	 * Stores the email message up in a global, then return an
	 * empty string message which prevents the email sending.
	 */
	function cancel_email_send( $message ) {
		global $cxec_cache_email_message;
		$cxec_cache_email_message = $message;
		return $message;
	}
	
	/**
	 * Check if any conflicting plugins are active, then deactivate ours.
	 *
	 * @since	2.36
	 */
	public static function is_conflicting_plugins_active() {
		
		global $cxec_plugins_found;
		
		$active_plugins = (array) get_option( 'active_plugins', array() );
		
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		
		// Define the plugins to check for.
		$plugins_to_check = array(
			'woocommerce-email-customizer.php' => 'WooCoomerce Email Customizer by WooThemes',
			'yith-woocommerce-email-templates' => 'YITH WooCommerce Email Templates',
		);
		
		$cxec_plugins_found = array();
		foreach ( $active_plugins as $active_plugin_key => $active_plugin_value ) {
			foreach ( $plugins_to_check as $plugins_to_check_key => $plugins_to_check_value ) {
				if ( FALSE !== strpos( $active_plugin_value, $plugins_to_check_key ) || FALSE !== strpos( $active_plugin_key, $plugins_to_check_key ) ) {
					// Collect the found plugin.
					$cxec_plugins_found[] = $plugins_to_check[$plugins_to_check_key];
				}
			}
		}
		
		return ! empty( $cxec_plugins_found );
	}
	
	/**
	 * Display Notifications on conflicting plugins active.
	 *
	 * @since	2.36
	 */
	public static function is_conflicting_plugins_active_notice() {
		
		global $cxec_plugins_found;
		
		if ( ! empty( $cxec_plugins_found ) ) :
			?>
			<div id="message" class="error">
				<p>
					<?php
					printf(
						__( '%sEmail Customizer for WooCommerce is inactive due to conflicts%sOur plugin will conflict with the following plugins and cannot be used while they are active: %s', 'email-control' ),
						'<strong>',
						'</strong><br>',
						'<em>' . implode( ', ', $cxec_plugins_found ) . '</em>'
					);
					?>
				</p>
			</div>
			<?php
		endif;
	}
	
	/**
	 * Is WooCommerce active.
	 */
	public static function is_woocommerce_active() {
		
		$active_plugins = (array) get_option( 'active_plugins', array() );
		
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
	
	/**
	 * Display Notifications on specific criteria.
	 *
	 * @since	2.14
	 */
	public static function woocommerce_inactive_notice() {
		if ( current_user_can( 'activate_plugins' ) ) :
			if ( ! class_exists( 'WooCommerce' ) ) :
				?>
				<div id="message" class="error">
					<p>
						<?php
						printf(
							__( '%sEmail Customizer for WooCommerce needs WooCommerce%s %sWooCommerce%s must be active for Email Customizer to work. Please install & activate WooCommerce.', 'email-control' ),
							'<strong>',
							'</strong><br>',
							'<a href="http://wordpress.org/extend/plugins/woocommerce/" target="_blank" >',
							'</a>'
						);
						?>
					</p>
				</div>
				<?php
			elseif ( version_compare( get_option( 'woocommerce_db_version' ), WC_EMAIL_CONTROL_REQUIRED_WOOCOMMERCE_VERSION, '<' ) ) :
				?>
				<div id="message" class="error">
					<!--<p style="float: right; color: #9A9A9A; font-size: 13px; font-style: italic;">For more information <a href="http://cxthemes.com/plugins/update-notice.html" target="_blank" style="color: inheret;">click here</a></p>-->
					<p>
						<?php
						printf(
							__( '%sEmail Customizer for WooCommerce is inactive%s This version of Email Customizer requires WooCommerce %s or newer. For more information about our WooCommerce version support %sclick here%s.', 'email-control' ),
							'<strong>',
							'</strong><br>',
							WC_EMAIL_CONTROL_REQUIRED_WOOCOMMERCE_VERSION,
							'<a href="https://helpcx.zendesk.com/hc/en-us/articles/202241041/" target="_blank" style="color: inheret;" >',
							'</a>'
						);
						?>
					</p>
					<div style="clear:both;"></div>
				</div>
				<?php
			endif;
		endif;
	}

}
