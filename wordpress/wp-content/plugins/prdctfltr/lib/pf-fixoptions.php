<?php

	if ( ! defined( 'ABSPATH' ) ) exit;

	class WC_Prdctfltr_Options {

		public static $options;
		public static $attribute;
		public static $preset;

		public static function init() {
			$class = __CLASS__;
			new $class;
		}

		function __construct() {

			include_once( 'pf-options-array.php' );
			self::$options = $options_std;
			self::$attribute = $attribute_std;

			foreach( self::$options as $opt => $val ) {
				add_filter( 'pre_option_' . $opt, __CLASS__ . '::option_switch', 10, 2 );
			}
		}

		public static function set_preset( $preset ) {

			$option = get_option( $preset, false );


			if ( $option !== false && is_string( $option ) && substr( $option, 0, 1 ) == '{' ) {
				$decoded = json_decode( stripslashes( $option ), true );
			}

			if ( empty( $decoded ) && $preset !== 'prdctfltr_wc_default' ) {
				$option = get_option( 'prdctfltr_wc_default', false );
				if ( $option !== false && is_string( $option ) && substr( $option, 0, 1 ) == '{' ) {
					$decoded = json_decode( stripslashes( $option ), true );
				}
			}

			if ( empty( $decoded ) ) {
				self::$preset = array();
			}
			else if ( is_array( $decoded ) ) {

				if ( isset( $decoded['wc_settings_prdctfltr_active_filters'] ) ) {
					$wc_settings_prdctfltr_attributes = array();
					if ( is_array( $decoded['wc_settings_prdctfltr_active_filters'] ) ) {
						foreach ( $decoded['wc_settings_prdctfltr_active_filters'] as $k ) {
							if ( substr( $k, 0, 3 ) == 'pa_' ) {
								foreach( self::$attribute as $k12 => $v76 ) {
									$key = str_replace( '%%%%', $k, $k12 );
									self::$options[$key] = $v76;
									add_filter( 'pre_option_' . $key, __CLASS__ . '::option_switch', 10, 2 );
								}
							}
						}
					}
				}

				$decoded = self::fix_nulls( $decoded );
				self::$preset = array_merge( self::$options, $decoded );
			}

		}

		public static function fix_nulls( $decoded ) {

			if ( is_array( $decoded ) ) {
				foreach( $decoded as $k => $v ) {
					if ( $v === null ) {
						unset( $decoded[$k] );
					}
				}
			}

 			return $decoded;

		}


		public static function option_switch( $replace, $option ) {

			if ( isset( self::$preset[$option] ) ) {
				return self::$preset[$option];
			}

			return false;

		}

	}

	add_action( 'woocommerce_init', array( 'WC_Prdctfltr_Options', 'init' ) );

?>