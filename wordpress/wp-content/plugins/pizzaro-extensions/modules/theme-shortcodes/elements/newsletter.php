<?php

if ( ! function_exists( 'pizzaro_newsletter_element' ) ) :

	function pizzaro_newsletter_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'marketing_text'	=> '',
			'bg_image'			=> ''
		), $atts));

		$args = array(
			'title'				=> $title,
			'marketing_text'	=> $marketing_text,
			'bg_image'			=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, array( '1920', '470' ) ) : array( '//placehold.it/1920x470', '1920', '470' ),
		);

		$html = '';
		if( function_exists( 'pizzaro_newsletter' ) ) {
			ob_start();
			pizzaro_newsletter( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_newsletter' , 'pizzaro_newsletter_element' );

endif;