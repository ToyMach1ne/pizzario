<?php
if ( !defined( 'ABSPATH' ) || ! defined( 'YITH_YWDPD_VERSION' ) ) {
    exit; // Exit if accessed directly
}



if( ! function_exists( 'ywdpd_have_dynamic_coupon' ) ){
	/**
	 * @return bool
	 */
	function ywdpd_have_dynamic_coupon() {

        $coupons = WC()->cart->get_coupons();

        if( empty( $coupons ) ){
            return false;
        }

        $dynamic_coupon = YITH_WC_Dynamic_Pricing()->get_option( 'coupon_label' );

        foreach( $coupons as $code => $value ){
            if( strtolower( $code ) == strtolower( $dynamic_coupon ) ){
                return true;
            }
        }

        return false;
    }
}

if( ! function_exists( 'ywdpd_get_discounted_price_table' ) ) {
	/**
	 * @param $price
	 * @param $rule
	 *
	 * @return int
	 */
	function ywdpd_get_discounted_price_table( $price, $rule ) {

        $discount = 0;

        if ( $rule['type_discount'] == 'percentage' ) {
            $discount = $price * $rule['discount_amount'];
        }
        elseif ( $rule['type_discount'] == 'price' ) {
            $discount = $rule['discount_amount'];
        }
        elseif ( $rule['type_discount'] == 'fixed-price' ) {
            $discount = $price - $rule['discount_amount'];
        }

        return ( ( $price - $discount ) < 0 ) ? 0 : ( $price - $discount );
    }
}

if( ! function_exists( 'ywdpd_check_cart_coupon' ) ) {
	/**
	 * Check if cart have already coupon applied
	 *
	 * @since 1.1.4
	 * @author Emanuela Castorina
	 */
	function ywdpd_check_cart_coupon() {

		if( ! WC()->cart ){
			return false;
		}

		$cart_coupons = WC()->cart->applied_coupons;

		if ( ywdpd_have_dynamic_coupon() ) {
			return false;
		}

		return ! empty( $cart_coupons );
	}
}