<?php

if ( ! function_exists( 'pizzaro_terms_element' ) ) :

	function pizzaro_terms_element( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			'taxonomy'     => 'category',
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => 0,
			'include'      => '',
			'exclude'      => '',
			'number'       => 0,
			'offset'       => 0,
			'name'         => '',
			'slug'         => '',
			'hierarchical' => true,
			'child_of'     => 0,
			'parent'       => ''
		), $atts, 'pizzaro_terms' );

		// Unset empty optional args
		$optional_args = array( 'include', 'exclude', 'name', 'slug', 'parent' );

		foreach( $optional_args as $optional_arg ) {
			if ( empty ( $atts[ $optional_arg ] ) ) {
				unset( $atts[ $optional_arg ] );
			}
		}

		// Check for comma separated and convert into arrays
		$comma_separated_args = array( 'taxonomy', 'include', 'exclude', 'name', 'slug' );

		foreach ( $comma_separated_args as $comma_separated_arg ) {
			if ( !empty( $atts[ $comma_separated_arg ] ) ) {
				$atts[$comma_separated_arg] = explode( ',', $atts[$comma_separated_arg] );
			}
		}

		//Cast int or number
		$int_args = array( 'hide_empty', 'number', 'offset', 'hierarchical', 'child_of', 'parent' );

		foreach ( $int_args as $int_arg ) {
			if ( !empty( $atts[ $int_arg ] ) ) {
				$atts[ $int_arg ] = (int) $atts[ $int_arg ];
			}
		}

		$terms = get_terms( $atts );

		$html = '';

		foreach ( $terms as $term ) {
			$html .= '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
		}

		if ( ! empty( $html ) ) {
			$html = '<ul>' . $html . '</ul>';
		}

	    return $html;
	}

	add_shortcode( 'pizzaro_terms' , 'pizzaro_terms_element' );

endif;