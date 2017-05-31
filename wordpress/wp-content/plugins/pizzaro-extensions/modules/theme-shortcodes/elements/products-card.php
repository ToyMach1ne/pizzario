<?php

if ( ! function_exists( 'pizzaro_products_card_element' ) ) :

	function pizzaro_products_card_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'media_align'		=> 'media-right',
			'image'				=> '',
			'shortcode_tag'		=> 'recent_products',
			'limit' 			=> 2,
			'orderby' 			=> 'date',
			'order' 			=> 'desc',
			'product_id'		=> '',
			'category'			=> '',
			'el_class'			=> '',
		), $atts));

		$shortcode_atts = function_exists( 'pizzaro_get_atts_for_shortcode' ) ? pizzaro_get_atts_for_shortcode( array( 'shortcode' => $shortcode_tag, 'product_category_slug' => $category, 'products_choice' => 'ids', 'products_ids_skus' => $product_id ) ) : array();

		$args = array(
			'section_title'		=> $title,
			'media_align'		=> $media_align,
			'image'				=> isset( $image ) && intval( $image ) ? wp_get_attachment_image_src( $image, 'full' ) : array( '//placehold.it/810x813', '810', '813' ),
			'shortcode_tag'		=> $shortcode_tag,
			'shortcode_atts'	=> wp_parse_args( $shortcode_atts, array( 'order' => $order, 'orderby' => $orderby ) ),
			'limit'				=> $limit,
			'section_class'		=> $el_class
		);

		$html = '';
		if( function_exists( 'pizzaro_products_card' ) ) {
			ob_start();
			pizzaro_products_card( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_products_card' , 'pizzaro_products_card_element' );

endif;