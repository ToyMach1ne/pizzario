<?php

if ( ! function_exists( 'pizzaro_menu_card_element' ) ) :

	function pizzaro_menu_card_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'pre_title'			=> '',
			'bg_image'			=> '',
			'menus'				=> array(),
			'el_class'			=> '',
		), $atts));

		if( is_object( $menus ) || is_array( $menus ) ) {
			$menus = json_decode( json_encode( $menus ), true );
		} else {
			$menus = json_decode( urldecode( $menus ), true );
		}

		$args = array(
			'section_title'		=> $title,
			'pre_title'			=> $pre_title,
			'bg_image'			=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : array( '//placehold.it/1920x950', '1920', '950' ),
			'menus'				=> $menus,
			'section_class'		=> $el_class
		);

		$html = '';
		if( function_exists( 'pizzaro_menu_card' ) ) {
			ob_start();
			pizzaro_menu_card( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_menu_card' , 'pizzaro_menu_card_element' );

endif;