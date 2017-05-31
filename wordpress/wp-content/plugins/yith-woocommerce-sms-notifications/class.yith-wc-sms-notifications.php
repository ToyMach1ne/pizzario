<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_WC_SMS_Notifications
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( ! class_exists( 'YITH_WC_SMS_Notifications' ) ) {

	class YITH_WC_SMS_Notifications {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WC_SMS_Notifications
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Panel object
		 *
		 * @var     /Yit_Plugin_Panel object
		 * @since   1.0.0
		 * @see     plugin-fw/lib/yit-plugin-panel.php
		 */
		protected $_panel = null;

		/**
		 * @var string Premium version landing link
		 */
		protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-sms-notifications/';

		/**
		 * @var string Plugin official documentation
		 */
		protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-sms-notifications/';

		/**
		 * @var string YITH WooCommerce SMS Notifications panel page
		 */
		protected $_panel_page = 'yith-wc-sms-notifications';

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WC_SMS_Notifications
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self;

			}

			return self::$instance;

		}

		/**
		 * Constructor
		 *
		 * @since   1.0.0
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function __construct() {

			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			//Load plugin framework
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 12 );
			add_filter( 'plugin_action_links_' . plugin_basename( YWSN_DIR . '/' . basename( YWSN_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 5 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_filter( 'http_request_args', array( $this, 'enable_unsafe_urls' ) );


			$this->includes();

			if ( 'yes' == get_option( 'ywsn_enable_plugin' ) ) {

				add_action( 'init', array( $this, 'init_multivendor_integration' ), 20 );


				if ( 'none' == get_option( 'ywsn_sms_gateway' ) ) {

					add_action( 'admin_notices', array( $this, 'add_admin_notices' ) );

				}

				if ( 'requested' == get_option( 'ywsn_customer_notification' ) ) {

					add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'show_sms_request_option' ) );
					add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_sms_request_option' ) );

				}

				foreach ( array_keys( wc_get_order_statuses() ) as $status ) {

					$slug = ( 'wc-' === substr( $status, 0, 3 ) ) ? substr( $status, 3 ) : $status;

					add_action( 'woocommerce_order_status_' . $slug, array( $this, 'order_status_changed' ), 99 );

				}

			}

		}

		/**
		 * Files inclusion
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		private function includes() {

			include_once( 'includes/class-ywsn-messages.php' );
			include_once( 'includes/class-ywsn-sms-gateway.php' );
			include_once( 'includes/class-ywsn-url-shortener.php' );

			if ( is_admin() ) {

				include_once( 'includes/class-ywsn-ajax.php' );
				include_once( 'includes/class-ywsn-metabox.php' );

				include_once( 'templates/admin/class-yith-wc-custom-checklist.php' );
				include_once( 'templates/admin/class-yith-wc-custom-textarea.php' );
				include_once( 'templates/admin/class-yith-wc-custom-label.php' );
				include_once( 'templates/admin/class-yith-wc-check-matrix-table.php' );
				include_once( 'templates/admin/class-ywsn-sms-send.php' );

			}

		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 * @use     /Yit_Plugin_Panel class
		 * @see     plugin-fw/lib/yit-plugin-panel.php
		 */
		public function add_menu_page() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general'  => _x( 'General Settings', 'general settings tab name', 'yith-woocommerce-sms-notifications' ),
				'messages' => _x( 'SMS Settings', 'sms settings tab name', 'yith-woocommerce-sms-notifications' ),
				'howto'    => _x( 'How-to', 'how-to tab name', 'yith-woocommerce-sms-notifications' ),
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => _x( 'SMS Notifications', 'plugin name in admin page title', 'yith-woocommerce-sms-notifications' ),
				'menu_title'       => _x( 'SMS Notifications', 'plugin name in admin WP menu', 'yith-woocommerce-sms-notifications' ),
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YWSN_DIR . 'plugin-options'
			);

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

		}

		/**
		 * Add scipts and styles
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function admin_scripts() {

			global $post;

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'ywsn-admin', YWSN_ASSETS_URL . '/css/ywsn-admin' . $suffix . '.css' );

			wp_enqueue_script( 'ywsn-admin', YWSN_ASSETS_URL . '/js/ywsn-admin' . $suffix . '.js', array( 'jquery' ) );

			$params = array(
				'ajax_url'                  => admin_url( 'admin-ajax.php' ),
				'order_id'                  => isset( $post->ID ) ? $post->ID : '',
				'sms_customer_notification' => get_option( 'ywsn_customer_notification' ),
				'sms_after_send'            => __( 'Message sent successfully!', 'yith-woocommerce-sms-notifications' ),
				'sms_no_message'            => __( 'Please select the type of message you want to send.', 'yith-woocommerce-sms-notifications' ),
				'sms_empty_message'         => __( 'Your message is blank!', 'yith-woocommerce-sms-notifications' ),
				'sms_wrong'                 => __( 'Please enter a valid phone number.', 'yith-woocommerce-sms-notifications' ),
				'sms_before_send'           => __( 'Sending...', 'yith-woocommerce-sms-notifications' ),
				'sms_manual_send_advice'    => __( 'The client did not requested sms notifications. Do you really want to send it?', 'yith-woocommerce-sms-notifications' ),
			);

			wp_localize_script( 'ywsn-admin', 'ywsn_admin', $params );

		}

		/**
		 * Advise if the plugin cannot be performed
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_admin_notices() {

			?>
			<div class="error">
				<p>
					<?php _e( 'To use this plugin, you should first select and configure an SMS service provider', 'yith-woocommerce-sms-notifications' ); ?>
				</p>
			</div>
			<?php

		}

		/**
		 * On change order status send SMS
		 *
		 * @since   1.0.0
		 *
		 * @param   $order_id
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function order_status_changed( $order_id ) {

			$order             = wc_get_order( $order_id );
			$current_action    = str_replace( 'woocommerce_order_status_', '', current_action() );
			$order_status      = apply_filters( 'ywsn_order_status', $order->post_status, $order_id );
			$order_status_slug = ( 'wc-' === substr( $order_status, 0, 3 ) ) ? substr( $order_status, 3 ) : $order_status;

			if ( 'none' == get_option( 'ywsn_sms_gateway' ) || $current_action == false || $current_action != $order_status_slug || ! apply_filters( 'ywsn_allow_sms_sending', true, $order_id, $order ) ) {

				return;

			}

			$active_sms = $this->get_active_sms( $order );

			if ( isset( $active_sms[ $order_status ]['customer'] ) && 1 == $active_sms[ $order_status ]['customer'] && $this->user_receives_sms( $order_id ) && wp_get_post_parent_id( $order_id ) == 0 ) {

				$customer_sms = new YWSN_Messages( $order, true );

				$customer_sms->single_sms();

			}

			if ( isset( $active_sms[ $order_status ]['admin'] ) && 1 == $active_sms[ $order_status ]['admin'] ) {

				$admin_sms = new YWSN_Messages( $order, false );

				$admin_sms->admins_sms();

			}

		}

		/**
		 * Get active SMS list with special behavior for sub-orders
		 *
		 * @since   1.0.3
		 *
		 * @param   $order
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function get_active_sms( $order ) {

			$active_sms = array();

			if ( wp_get_post_parent_id( $order->id ) != 0 ) {

				$active_sms = apply_filters( 'ywsn_active_sms', array(), $order );

			} else {

				$active_sms = get_option( 'ywsn_sms_active_send' );

			}

			return $active_sms;

		}

		/**
		 * Check if customer wants to receive SMS
		 *
		 * @since   1.0.0
		 *
		 * @param   $order_id
		 *
		 * @return  bool
		 * @author  Alberto Ruggiero
		 */
		public function user_receives_sms( $order_id ) {

			if ( 'requested' == get_option( 'ywsn_customer_notification' ) ) {

				return ( get_post_meta( $order_id, '_ywsn_receive_sms', true ) == 'yes' );

			} else {

				return true;

			}

		}

		/**
		 * Show SMS request checkbox in checkout page
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function show_sms_request_option() {

			if ( ! empty( $_POST['ywsn_receive_sms'] ) ) {

				$value = wc_clean( $_POST['ywsn_receive_sms'] );

			} else {

				$value = get_option( 'ywsn_checkout_checkbox_value' ) == 'yes';

			}

			$label = apply_filters( 'ywsn_checkout_option_label', get_option( 'ywsn_checkout_checkbox_text' ) );

			if ( ! empty( $label ) ) {

				woocommerce_form_field( 'ywsn_receive_sms', array(
					'type'  => 'checkbox',
					'class' => array( 'form-row-wide' ),
					'label' => $label,
				), $value );

			}

		}

		/**
		 * Save SMS request checkbox in checkout page
		 *
		 * @since   1.0.0
		 *
		 * @param   $order_id
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function save_sms_request_option( $order_id ) {

			if ( ! empty( $_POST['ywsn_receive_sms'] ) ) {

				update_post_meta( $order_id, '_ywsn_receive_sms', 'yes' );

			}

		}

		/**
		 * Enable unsafe URLs for some SMS operator
		 *
		 * @since   1.0.8
		 *
		 * @param   $args
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function enable_unsafe_urls( $args ) {

			$active_gateway   = get_option( 'ywsn_sms_gateway' );
			$enabled_gateways = array( 'YWSN_Jazz' );

			if ( in_array( $active_gateway, $enabled_gateways ) ) {

				$args['reject_unsafe_urls'] = false;

			}

			return $args;

		}

		/**
		 * Add YITH WooCommerce Multi Vendor integration
		 *
		 * @since   1.0.3
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function init_multivendor_integration() {

			if ( $this->is_multivendor_active() ) {

				include_once( 'includes/class-ywsn-multivendor.php' );

			}

		}

		/**
		 * Check if YITH WooCommerce Multi Vendor is active
		 *
		 * @since   1.0.3
		 * @return  bool
		 * @author  Alberto Ruggiero
		 */
		public function is_multivendor_active() {

			return defined( 'YITH_WPV_PREMIUM' ) && YITH_WPV_PREMIUM;

		}

		/**
		 * Get Placeholders reference
		 *
		 * @since   1.0.8
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function placeholder_reference() {

			$placeholders = array(
				'{site_title}'       => __( 'Website name', 'yith-woocommerce-sms-notifications' ),
				'{order_id}'         => __( 'Order number', 'yith-woocommerce-sms-notifications' ),
				'{order_total}'      => __( 'Order total', 'yith-woocommerce-sms-notifications' ),
				'{order_status}'     => __( 'Order status', 'yith-woocommerce-sms-notifications' ),
				'{billing_name}'     => __( 'Billing name', 'yith-woocommerce-sms-notifications' ),
				'{shipping_name}'    => __( 'Shipping name', 'yith-woocommerce-sms-notifications' ),
				'{shipping_method}'  => __( 'Shipping method', 'yith-woocommerce-sms-notifications' ),
				'{additional_notes}' => __( 'Additional Notes', 'yith-woocommerce-sms-notifications' ),
				'{order_date}'       => __( 'Order Date', 'yith-woocommerce-sms-notifications' ),
			);

			if ( function_exists( 'YITH_YWOT' ) ) {

				$placeholders['{tracking_number}'] = __( 'Tracking Number', 'yith-woocommerce-sms-notifications' );
				$placeholders['{carrier_name}']    = __( 'Carrier name', 'yith-woocommerce-sms-notifications' );
				$placeholders['{shipping_date}']   = __( 'Shipping date', 'yith-woocommerce-sms-notifications' );

			}

			return $placeholders;

		}

		/**
		 * YITH FRAMEWORK
		 */

		/**
		 * Load plugin framework
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Andrea Grillo
		 * <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {

				global $plugin_fw_data;

				if ( ! empty( $plugin_fw_data ) ) {

					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once( $plugin_fw_file );

				}

			}

		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @return  string The premium landing link
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function get_premium_landing_uri() {

			return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing;

		}

		/**
		 * Action Links
		 *
		 * add the action links to plugin admin page
		 * @since   1.0.0
		 *
		 * @param   $links | links plugin array
		 *
		 * @return  mixed
		 * @author  Andrea Grillo  <andrea.grillo@yithemes.com>
		 * @use     plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {

			$links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-sms-notifications' ) . '</a>';

			return $links;

		}

		/**
		 * Plugin row meta
		 *
		 * add the action links to plugin admin page
		 *
		 * @since   1.0.0
		 *
		 * @param   $plugin_meta
		 * @param   $plugin_file
		 * @param   $plugin_data
		 * @param   $status
		 *
		 * @return  array
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     plugin_row_meta
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( ( defined( 'YWSN_INIT' ) && ( YWSN_INIT == $plugin_file ) ) ) {

				$plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin documentation', 'yith-woocommerce-sms-notifications' ) . '</a>';

			}

			return $plugin_meta;

		}

		/**
		 * Register plugins for activation tab
		 *
		 * @since   2.0.0
		 * @return  void
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once 'plugin-fw/licence/lib/yit-licence.php';
				require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YWSN_INIT, YWSN_SECRET_KEY, YWSN_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @since   2.0.0
		 * @return  void
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once( 'plugin-fw/lib/yit-upgrade.php' );
			}
			YIT_Upgrade()->register( YWSN_SLUG, YWSN_INIT );
		}

	}

}