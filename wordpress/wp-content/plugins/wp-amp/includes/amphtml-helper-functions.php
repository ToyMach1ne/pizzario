<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public function for sanitizing content
 */
if ( function_exists( 'AMPHTML' ) ) {
	function esc_amphtml( $content ) {
		$amphtml = AMPHTML()->get_template()
		                    ->get_sanitize_obj()
		                    ->sanitize_content( $content );

		return apply_filters( 'esc_amphtml', $amphtml );
	}
}

/**
 * Shortcode for hiding content from AMP
 */
add_shortcode( 'no-amp', 'amphtml_no_amp' );

function amphtml_no_amp( $atts, $content ) {
	if ( is_wp_amp() ) {
		$content  = '';
	} else {
        $content = apply_filters('the_content', $content);
    }
	return $content;
}

/**
 * is_post_type_viewable() for older WordPress versions
 */

if ( ! function_exists( 'is_post_type_viewable' ) ) {

    function is_post_type_viewable( $post_type ) {
        if ( is_scalar( $post_type ) ) {
            $post_type = get_post_type_object( $post_type );
            if ( ! $post_type ) {
                return false;
            }
        }

        return $post_type->publicly_queryable || ( $post_type->_builtin && $post_type->public );
    }

}

/**
 * Check if AMP page loaded
 * @return bool
 */
function is_wp_amp() {
    $endpoint_opt = get_option( 'amphtml_endpoint' );
    $endpoint     = ( $endpoint_opt ) ? $endpoint_opt : AMPHTML::AMP_QUERY;

    if ( '' == get_option( 'permalink_structure' ) ) {
        parse_str( $_SERVER['QUERY_STRING'], $url );
        return isset( $url[ $endpoint ] );
    }

    $url_parts   = explode( '?', $_SERVER["REQUEST_URI"] );
    $query_parts = explode( '/', $url_parts[0] );

    $is_amp = ( in_array( $endpoint, $query_parts ) );

    return $is_amp;
}