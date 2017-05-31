<?php

if ( ! function_exists( 'pizzaro_products_sale_event_element' ) ) :

	function pizzaro_products_sale_event_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'pre_title'		=> '',
			'title'			=> '',
			'price'			=> '',
			'price_info'	=> '',
			'product_ids'	=> '',
			'action_text'	=> '',
			'action_link'	=> '#',
			'bg_choice'		=> '',
			'bg_image'		=> '',
			'bg_color'		=> '',
			'height'		=> '',
			'el_class'		=> ''
		), $atts));

		$args = array(
			'pre_title'		=> $pre_title,
			'section_title'	=> $title,
			'pre_title'		=> $pre_title,
			'price'			=> $price,
			'price_info'	=> $price_info,
			'product_ids'	=> $product_ids,
			'action_text'	=> $action_text,
			'action_link'	=> $action_link,
			'bg_choice'		=> isset( $bg_choice ) ? $bg_choice : 'image',
			'bg_color'		=> $bg_color,
			'height'		=> $height,
			'bg_image'		=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : '',
			'section_class'	=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_products_sale_event' ) ) {
			ob_start();
			pizzaro_products_sale_event( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_products_sale_event' , 'pizzaro_products_sale_event_element' );

endif;