<?php

if ( ! function_exists( 'pizzaro_banner_element' ) ) :

	function pizzaro_banner_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'bg_choice'		=> '',
			'bg_image'		=> '',
			'bg_color'		=> '',
			'height'		=> '',
			'pre_title'		=> '',
			'title'			=> '',
			'sub_title'		=> '',
			'description'	=> '',
			'action_text'	=> '',
			'action_link'	=> '#',
			'condition'		=> '',
			'el_class'		=> ''
		), $atts));

		$args = array(
			'bg_choice'		=> isset( $bg_choice ) ? $bg_choice : 'image',
			'bg_color'		=> $bg_color,
			'height'		=> $height,
			'bg_image'		=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : '',
			'pre_title'		=> $pre_title,
			'title'			=> $title,
			'sub_title'		=> $sub_title,
			'description'	=> $description,
			'action_text'	=> $action_text,
			'action_link'	=> $action_link,
			'condition'		=> $condition,
			'section_class'	=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_banner' ) ) {
			ob_start();
			pizzaro_banner( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_banner' , 'pizzaro_banner_element' );

endif;