<?php

if ( ! function_exists( 'pizzaro_products_carousel_with_image_element' ) ) :

	function pizzaro_products_carousel_with_image_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'sub_title'			=> '',
			'shortcode_tag'		=> 'recent_products',
			'limit' 			=> 12,
			'columns'			=> 3,
			'orderby' 			=> 'date',
			'order' 			=> 'desc',
			'product_id'		=> '',
			'category'			=> '',
			'image'				=> '',
			'bg_image'			=> '',
			'el_class'			=> '',
			'cat_orderby' 		=> 'name',
			'cat_order' 		=> 'ASC',
			'cat_hide_empty'	=> false,
			'cat_limit'			=> 4,
			'cat_slugs'			=> ''
		), $atts));

		$shortcode_atts = function_exists( 'pizzaro_get_atts_for_shortcode' ) ? pizzaro_get_atts_for_shortcode( array( 'shortcode' => $shortcode_tag, 'product_category_slug' => $category, 'products_choice' => 'ids', 'products_ids_skus' => $product_id ) ) : array();

		$args = array(
			'section_title'		=> $title,
			'sub_title'			=> $sub_title,
			'shortcode_tag'		=> $shortcode_tag,
			'shortcode_atts'	=> wp_parse_args( $shortcode_atts, array( 'order' => $order, 'orderby' => $orderby ) ),
			'limit'				=> $limit,
			'columns'			=> $columns,
			'image'				=> isset( $image ) && intval( $image ) ? wp_get_attachment_image_src( $image, 'full' ) : array( '//placehold.it/570x480', '570', '480' ),
			'bg_image'			=> isset( $bg_image ) && intval( $bg_image ) ? wp_get_attachment_image_src( $bg_image, 'full' ) : array( '//placehold.it/1920x680', '1920', '680' ),
			'section_class'		=> $el_class,
			'category_args'			=> array(
				'orderby'				=> $cat_orderby,
				'order'					=> $cat_order,
				'hide_empty'			=> $cat_hide_empty,
				'number'				=> $cat_limit,
				'slug'					=> $cat_slugs,
				'hierarchical'			=> false
			),
			'carousel_args'	=> array(
				'items'				=> $columns,
				'nav'				=> true,
				'slideSpeed'		=> 300,
				'dots'				=> false,
				'rtl'				=> is_rtl() ? true : false,
				'paginationSpeed'	=> 400,
				'navText'			=> is_rtl() ? array( '<i class="po po-arrow-right-slider"></i>', '<i class="po po-arrow-left-slider"></i>' ) : array( '<i class="po po-arrow-left-slider"></i>', '<i class="po po-arrow-right-slider"></i>' ),
				'margin'			=> 0,
				'touchDrag'			=> true,
				'responsive'		=> array(
					'0'		=> array( 'items'	=> 1 ),
					'480'	=> array( 'items'	=> 3 ),
					'768'	=> array( 'items'	=> 2 ),
					'992'	=> array( 'items'	=> 3 ),
					'1200'	=> array( 'items'	=> $columns ),
				)
			)
		);

		$html = '';
		if( function_exists( 'pizzaro_products_carousel_with_image' ) ) {
			ob_start();
			pizzaro_products_carousel_with_image( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_products_carousel_with_image' , 'pizzaro_products_carousel_with_image_element' );

endif;