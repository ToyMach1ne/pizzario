<?php

if ( ! function_exists( 'pizzaro_coupon_element' ) ) :

	function pizzaro_coupon_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'coupon_code'	=> '',
			'pre_title'		=> '',
			'title'			=> '',
			'sub_title'		=> '',
			'description'	=> '',
			'action_text'	=> '',
			'action_link'	=> '#',
			'bg_choice'		=> '',
			'bg_image'		=> '',
			'bg_color'		=> '',
			'height'		=> '',
			'el_class'		=> ''
		), $atts));

		$args = array(
			'coupon_code'	=> $coupon_code,
			'pre_title'		=> $pre_title,
			'title'			=> $title,
			'sub_title'		=> $sub_title,
			'description'	=> $description,
			'action_text'	=> $action_text,
			'action_link'	=> $action_link,
			'bg_choice'		=> isset( $bg_choice ) ? $bg_choice : 'image',
			'bg_color'		=> $bg_color,
			'height'		=> $height,
			'bg_image'		=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : '',
			'section_class'	=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_coupon' ) ) {
			ob_start();
			pizzaro_coupon( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_coupon' , 'pizzaro_coupon_element' );

endif;