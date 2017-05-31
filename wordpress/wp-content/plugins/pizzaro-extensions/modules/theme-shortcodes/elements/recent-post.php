<?php

if ( ! function_exists( 'pizzaro_recent_post_element' ) ) :

	function pizzaro_recent_post_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'section_title'		=> '',
			'post_choice'		=> 'recent',
			'ids'				=> '',
			'bg_choice'			=> '',
			'bg_image'			=> '',
			'bg_color'			=> '',
			'height'			=> '',
			'show_read_more'	=> false,
			'el_class'			=> '',
		), $atts));

		$args = array(
			'section_title'			=> $section_title,
			'post_choice'			=> $post_choice,
			'post_id'				=> $ids,
			'bg_choice'				=> isset( $bg_choice ) ? $bg_choice : 'image',
			'bg_color'				=> $bg_color,
			'height'				=> $height,
			'bg_image'				=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : '',
			'show_read_more'		=> $show_read_more,
			'section_class'			=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_recent_post' ) ) {
			ob_start();
			pizzaro_recent_post( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_recent_post' , 'pizzaro_recent_post_element' );

endif;