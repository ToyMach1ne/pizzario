<?php
/**
 * WooCommerce Extensions Integrations
 *
 * @package pizzaro
 */

if ( is_yith_wapo_activated() ) {

	if ( ! function_exists( 'pizzaro_wapo_check_required_addons' ) ) {
		/**
		 * pizzaro_wapo_check_required_addons function.
		 *
		 * @param mixed $product_id
		 * @return void
		 */
		function pizzaro_wapo_check_required_addons( $product_id ) {
			$types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

			if ( ! empty( $types_list ) ) {
				return true;
			}

			return false;
		}
	}

	/**
	 * Adds extra post classes for products.
	 *
	 * @since 2.1.0
	 * @param array $classes
	 * @param string|array $class
	 * @param int $post_id
	 * @return array
	 */
	function pizzaro_wapo_woocommerce_post_class( $classes, $class = '', $post_id = '' ) {
		if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
			return $classes;
		}

		$product = wc_get_product( $post_id );

		if ( $product && pizzaro_wapo_check_required_addons( $post_id ) ) {
			$classes[] = 'addon-product';
		}

		return $classes;
	}

	add_filter( 'post_class', 'pizzaro_wapo_woocommerce_post_class', 30, 3 );

	if ( ! function_exists( 'pizzaro_wapo_display_on_loop' ) ) {
		function pizzaro_wapo_display_on_loop() {
			global $product;

			$product_id = pizzaro_wc_get_product_id( $product );
			$product_type = pizzaro_wc_get_product_type( $product );
			if( is_object($product) && $product_id > 0 ) {

				$product_type_list = YITH_WAPO::getAllowedProductTypes();

				if( in_array( $product_type, $product_type_list ) ) {

					$types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

					if( ! empty( $types_list ) ) {
						$radio_groups_html = '';

						$yith_wapo_frontend = new YITH_WAPO_Frontend( YITH_WAPO_VERSION );
						
						ob_start();
						foreach( $types_list as $single_type ) {
							if( $single_type->required && $single_type->type == 'radio' ) {
								$yith_wapo_frontend->printSingleGroupType( $product , $single_type );
							}
						}
						$radio_groups_html = ob_get_clean();

						if( ! empty( $radio_groups_html ) ) {
							$search 		= array( '<h3>', '</h3>' );
							$replace 		= array( '<h3><span>', '</span></h3>' );
							$radio_groups_html 	= str_replace( $search, $replace, $radio_groups_html );
							echo '<div id="yith_wapo_groups_container" class="yith_wapo_groups_container">' . $radio_groups_html . '</div>';
						}
					}

				}
			}
		}
	}

	add_action( 'woocommerce_shop_loop_item_title', 'pizzaro_wapo_display_on_loop', 20 );
}

if ( is_yith_wcqv_activated() ) {
	$yith_wcqv = YITH_WCQV_Frontend();
	remove_action( 'woocommerce_after_shop_loop_item', array( $yith_wcqv, 'yith_add_quick_view_button' ), 15 );
	add_action( 'pizzaro_product_item_hover_area', array( $yith_wcqv, 'yith_add_quick_view_button' ), 30 );
}

if( class_exists( 'WC_Email_Cart' ) ) {
	$cxecrt = WC_Email_Cart::get_instance();
	if ( 'yes' == cxecrt_get_option( 'cxecrt_show_cart_page_button' ) ) {
		remove_action( 'woocommerce_cart_collaterals', array( $cxecrt, 'cart_page_call_to_action' ) );
		add_action( 'woocommerce_cart_collaterals', array( $cxecrt, 'cart_page_call_to_action' ) );
	}
}