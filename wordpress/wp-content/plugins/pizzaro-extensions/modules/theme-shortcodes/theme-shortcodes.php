<?php

/**
 * Module Name 			: Theme Shortcodes
 * Module Description 	: Provides additional shortcodes for the Pizzaro theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Pizzaro_Shortcodes' ) ) {
	class Pizzaro_Shortcodes {

		/**
		 * Constructor function.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'setup_constants' ),	10 );
			add_action( 'init', array( $this, 'includes' ),			10 );
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
			if ( ! defined( 'PIZZARO_EXTENSIONS_SHORTCODE_DIR' ) ) {
				define( 'PIZZARO_EXTENSIONS_SHORTCODE_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'PIZZARO_EXTENSIONS_SHORTCODE_URL' ) ) {
				define( 'PIZZARO_EXTENSIONS_SHORTCODE_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'PIZZARO_EXTENSIONS_SHORTCODE_FILE' ) ) {
				define( 'PIZZARO_EXTENSIONS_SHORTCODE_FILE', __FILE__ );
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
			# Shortcodes
			#-----------------------------------------------------------------
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/banner.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/coupon.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/events.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/features-list.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/menu-card.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/newsletter.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/product-categories.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/product.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/products-4-1.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/products-card.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/products-carousel-with-image.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/products-sale-event.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/products-with-gallery.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/products.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/recent-post.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/recent-posts.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/sale-product.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/store-search.php';
			require_once PIZZARO_EXTENSIONS_SHORTCODE_DIR . '/elements/terms.php';
		}
	}
}

// Finally initialize code
new Pizzaro_Shortcodes();