<?php

if ( ! function_exists( 'pizzaro_events_element' ) ) :

	function pizzaro_events_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'section_title'		=> '',
			'pre_title'			=> '',
			'el_class'			=> ''
		), $atts));

		$args = array(
			'section_title'		=> $section_title,
			'pre_title'			=> $pre_title,
			'section_class'		=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_events' ) ) {
			ob_start();
			pizzaro_events( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_events' , 'pizzaro_events_element' );

endif;