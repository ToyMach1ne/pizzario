<?php
/**
 * Plugin Name: YITH WooCommerce PDF Invoice and Shipping List Premium
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-pdf-invoice/
 * Description: Generate PDF invoices, credit notes, pro-forma invoice and packing slip for WooCommerce orders.
 * Set manual or automatic invoice generation, fully customizable document template and sync with your DropBox account.
 * Author: YITHEMES
 * Text Domain: yith-woocommerce-pdf-invoice
 * Version: 1.4.12
 * Author URI: http://yithemes.com/
 *
 * @author  Yithemes
 * @package YITH WooCommerce PDF Invoice and Packing slip Premium
 * @version 1.4.12
 */

/*  Copyright 2013-2015  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

//region    ****    Check if prerequisites are satisfied before enabling and using current plugin

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! function_exists( 'yith_ywpi_premium_install_woocommerce_admin_notice' ) ) {
	/**
	 * Show notice if WooCommerce is not active
	 *
	 * @author Lorenzo Giuffrida
	 * @since  1.0.0
	 */
	function yith_ywpi_premium_install_woocommerce_admin_notice() {
		?>
		<div class="error">

			<p><?php _e( 'YITH WooCommerce PDF Invoice is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-pdf-invoice' ); ?></p>
		</div>
		<?php
	}
}
/**
 * Check if a free version is currently active and try disabling before activating this one
 */
if ( ! function_exists( 'yit_deactive_free_version' ) ) {
	require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YITH_YWPI_FREE_INIT', plugin_basename( __FILE__ ) );


if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

//endregion

//region    ****    Define constants  ****
defined( 'YITH_YWPI_INIT' ) || define( 'YITH_YWPI_INIT', plugin_basename( __FILE__ ) );
defined( 'YITH_YWPI_PREMIUM' ) || define( 'YITH_YWPI_PREMIUM', '1' );
defined( 'YITH_YWPI_SLUG' ) || define( 'YITH_YWPI_SLUG', 'yith-woocommerce-pdf-invoice' );
defined( 'YITH_YWPI_SECRET_KEY' ) || define( 'YITH_YWPI_SECRET_KEY', 'gpToFMpxJ2ZT7gRSeyG8' );
defined( 'YITH_YWPI_VERSION' ) || define( 'YITH_YWPI_VERSION', '1.4.12' );
defined( 'YITH_YWPI_FILE' ) || define( 'YITH_YWPI_FILE', __FILE__ );
defined( 'YITH_YWPI_DIR' ) || define( 'YITH_YWPI_DIR', plugin_dir_path( __FILE__ ) );
defined( 'YITH_YWPI_URL' ) || define( 'YITH_YWPI_URL', plugins_url( '/', __FILE__ ) );
defined( 'YITH_YWPI_ASSETS_URL' ) || define( 'YITH_YWPI_ASSETS_URL', YITH_YWPI_URL . 'assets' );
defined( 'YITH_YWPI_ASSETS_DIR' ) || define( 'YITH_YWPI_ASSETS_DIR', YITH_YWPI_DIR . 'assets' );
defined( 'YITH_YWPI_TEMPLATE_DIR' ) || define( 'YITH_YWPI_TEMPLATE_DIR', YITH_YWPI_DIR . 'templates/' );
defined( 'YITH_YWPI_INVOICE_TEMPLATE_URL' ) || define( 'YITH_YWPI_INVOICE_TEMPLATE_URL', YITH_YWPI_URL . 'templates/invoice/' );
defined( 'YITH_YWPI_INVOICE_TEMPLATE_DIR' ) || define( 'YITH_YWPI_INVOICE_TEMPLATE_DIR', YITH_YWPI_DIR . 'templates/invoice/' );
defined( 'YITH_YWPI_ASSETS_IMAGES_URL' ) || define( 'YITH_YWPI_ASSETS_IMAGES_URL', YITH_YWPI_ASSETS_URL . '/images/' );
defined( 'YITH_YWPI_ASSETS_IMAGES_DIR' ) || define( 'YITH_YWPI_ASSETS_IMAGES_DIR', YITH_YWPI_ASSETS_DIR . '/images/' );
defined( 'YITH_YWPI_LIB_DIR' ) || define( 'YITH_YWPI_LIB_DIR', YITH_YWPI_DIR . 'lib/' );

$wp_upload_dir = wp_upload_dir();

defined( 'YITH_YWPI_DOCUMENT_SAVE_DIR' ) || define( 'YITH_YWPI_DOCUMENT_SAVE_DIR', $wp_upload_dir['basedir'] . '/ywpi-pdf-invoice/' );
defined( 'YITH_YWPI_SAVE_INVOICE_URL' ) || define( 'YITH_YWPI_SAVE_INVOICE_URL', $wp_upload_dir['baseurl'] . '/ywpi-pdf-invoice/' );
defined( 'YITH_YWPI_INVOICE_LOGO_PATH' ) || define( 'YITH_YWPI_INVOICE_LOGO_PATH', YITH_YWPI_DOCUMENT_SAVE_DIR . 'invoice-logo.jpg' );

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_YWPI_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YITH_YWPI_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_YWPI_DIR );

//endregion
if ( ! function_exists( 'yith_ywpi_premium_init' ) ) {
	/**
	 * Init the plugin
	 *
	 * @author Lorenzo Giuffrida
	 * @since  1.0.0
	 */
	function yith_ywpi_premium_init() {

		/* Load YWPI text domain */
		load_plugin_textdomain( 'yith-woocommerce-pdf-invoice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load required classes and functions
		require_once( YITH_YWPI_LIB_DIR . 'class.yith-ywpi-plugin-fw-loader.php' );
		require_once( YITH_YWPI_LIB_DIR . 'class.yith-checkout-addon.php' );
		require_once( YITH_YWPI_LIB_DIR . 'class.yith-woocommerce-pdf-invoice.php' );
		require_once( YITH_YWPI_LIB_DIR . 'class.yith-ywpi-backend.php' );
		require_once( YITH_YWPI_LIB_DIR . 'class.yith-woocommerce-pdf-invoice-premium.php' );
		require_once( YITH_YWPI_LIB_DIR . 'class.yith-pdf-invoice-dropbox.php' );

		require_once( YITH_YWPI_LIB_DIR . 'documents/class.yith-document.php' );
		require_once( YITH_YWPI_LIB_DIR . 'documents/class.yith-invoice.php' );
		require_once( YITH_YWPI_LIB_DIR . 'documents/class.yith-pro-forma.php' );
		require_once( YITH_YWPI_LIB_DIR . 'documents/class.yith-credit-note.php' );
		require_once( YITH_YWPI_LIB_DIR . 'documents/class.yith-shipping.php' );

		require_once( YITH_YWPI_LIB_DIR . 'class.yith-invoice-details.php' );
		require_once( YITH_YWPI_DIR . 'functions.php' );

		YITH_YWPI_Plugin_FW_Loader::get_instance();
		YITH_PDF_Invoice();

		ywpi_start_plugin_compatibility();
	}
}
add_action( 'yith_ywpi_premium_init', 'yith_ywpi_premium_init' );

if ( ! function_exists( 'YITH_PDF_Invoice' ) ) {
	/**
	 * Retrieve the instance of the plugin main class
	 *
	 * @return YITH_WooCommerce_Pdf_Invoice_Premium
	 * @author Lorenzo Giuffrida
	 * @since  1.0.0
	 */
	function YITH_PDF_Invoice() {
		return YITH_WooCommerce_Pdf_Invoice_Premium::get_instance();
	}
}

if ( ! function_exists( 'yith_ywpi_premium_install' ) ) {
	/**
	 * Install the plugin
	 *
	 * @author Lorenzo Giuffrida
	 * @since  1.0.0
	 */
	function yith_ywpi_premium_install() {

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywpi_premium_install_woocommerce_admin_notice' );
		} else {
			do_action( 'yith_ywpi_premium_init' );
		}
	}
}
add_action( 'plugins_loaded', 'yith_ywpi_premium_install', 11 );