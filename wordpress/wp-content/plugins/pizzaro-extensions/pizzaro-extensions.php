<?php
/**
 * Plugin Name:    	Pizzaro Extensions
 * Plugin URI:     	https://demo2.chethemes.com/pizzaro/
 * Description:    	This selection of extensions compliment our lean and mean theme for WooCommerce, Pizzaro. Please note: they don’t work with any WordPress theme, just Pizzaro.
 * Author:         	Transvelo
 * Author URL:     	https://chethemes.com/
 * Version:        	1.1.3
 * Text Domain: 	pizzaro-extensions
 * Domain Path: 	/languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Pizzaro_Extensions' ) ) {
	/**
	 * Main Pizzaro_Extensions Class
	 *
	 * @class Pizzaro_Extensions
	 * @version	1.0.0
	 * @since 1.0.0
	 * @package	Kudos
	 * @author Ibrahim
	 */
	final class Pizzaro_Extensions {
		/**
		 * Pizzaro_Extensions The single instance of Pizzaro_Extensions.
		 * @var 	object
		 * @access  private
		 * @since 	1.0.0
		 */
		private static $_instance = null;

		/**
		 * The token.
		 * @var     string
		 * @access  public
		 * @since   1.0.0
		 */
		public $token;

		/**
		 * The version number.
		 * @var     string
		 * @access  public
		 * @since   1.0.0
		 */
		public $version;

		/**
		 * Constructor function.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function __construct () {
			
			$this->token 	= 'pizzaro-extensions';
			$this->version 	= '0.0.1';
			
			add_action( 'plugins_loaded', array( $this, 'setup_constants' ),		10 );
			add_action( 'plugins_loaded', array( $this, 'includes' ),				20 );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ),	30 );
		}

		/**
		 * Main Pizzaro_Extensions Instance
		 *
		 * Ensures only one instance of Pizzaro_Extensions is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see Pizzaro_Extensions()
		 * @return Main Kudos instance
		 */
		public static function instance () {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function setup_constants() {

			// Plugin Folder Path
			if ( ! defined( 'PIZZARO_EXTENSIONS_DIR' ) ) {
				define( 'PIZZARO_EXTENSIONS_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'PIZZARO_EXTENSIONS_URL' ) ) {
				define( 'PIZZARO_EXTENSIONS_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'PIZZARO_EXTENSIONS_FILE' ) ) {
				define( 'PIZZARO_EXTENSIONS_FILE', __FILE__ );
			}

			// Modules File
			if ( ! defined( 'PIZZARO_MODULES_DIR' ) ) {
				define( 'PIZZARO_MODULES_DIR', PIZZARO_EXTENSIONS_DIR . '/modules' );
			}
		}

		/**
		 * Include required files
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function includes() {

			#-----------------------------------------------------------------
			# Post Formats
			#-----------------------------------------------------------------
			require_once PIZZARO_MODULES_DIR . '/post-formats/post-formats.php';

			#-----------------------------------------------------------------
			# Static Block Post Type
			#-----------------------------------------------------------------
			require_once PIZZARO_MODULES_DIR . '/post-types/static-block/static-block.php';

			#-----------------------------------------------------------------
			# Theme Shortcodes
			#-----------------------------------------------------------------
			require_once PIZZARO_MODULES_DIR . '/theme-shortcodes/theme-shortcodes.php';

			#-----------------------------------------------------------------
			# King Composer Extensions
			#-----------------------------------------------------------------
			require_once PIZZARO_MODULES_DIR . '/kingcomposer/kingcomposer.php';

			#-----------------------------------------------------------------
			# Visual Composer Extensions
			#-----------------------------------------------------------------
			require_once PIZZARO_MODULES_DIR . '/js_composer/js_composer.php';

			#-----------------------------------------------------------------
			# Page Templates
			#-----------------------------------------------------------------
			// require PIZZARO_MODULES_DIR . '/page-templates/class-pizzaro-page-templates.php';
		}

		/**
		 * Load the localisation file.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'pizzaro-extensions', false, dirname( plugin_basename( PIZZARO_EXTENSIONS_FILE ) ) . '/languages/' );
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone () {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'pizzaro-extensions' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup () {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'pizzaro-extensions' ), '1.0.0' );
		}
	}
}

/**
 * Returns the main instance of Pizzaro_Extensions to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Pizzaro_Extensions
 */
function Pizzaro_Extensions() {
	return Pizzaro_Extensions::instance();
}

/**
 * Initialise the plugin
 */
Pizzaro_Extensions();