<?php

if ( ! function_exists( 'pizzaro_store_search_element' ) ) :

	function pizzaro_store_search_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'bg_choice'		=> '',
			'bg_image'		=> '',
			'bg_color'		=> '',
			'height'		=> '',
			'title'			=> '',
			'sub_title'		=> '',
			'icon_class'	=> '',
			'button_text'	=> '',
			'page_id'		=> '',
			'el_class'		=> ''
		), $atts));

		$args = array(
			'bg_choice'		=> isset( $bg_choice ) ? $bg_choice : 'image',
			'bg_color'		=> $bg_color,
			'height'		=> $height,
			'bg_image'		=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : '',
			'title'			=> $title,
			'sub_title'		=> $sub_title,
			'icon_class'	=> $icon_class,
			'button_text'	=> $button_text,
			'page_id'		=> $page_id,
			'section_class'	=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_store_search' ) ) {
			ob_start();
			pizzaro_store_search( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_store_search' , 'pizzaro_store_search_element' );

endif;