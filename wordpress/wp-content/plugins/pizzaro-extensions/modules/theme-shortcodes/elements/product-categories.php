<?php

if ( ! function_exists( 'pizzaro_product_categories_element' ) ) :

	function pizzaro_product_categories_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'section_title'	=> '',
			'pre_title'		=> '',
			'orderby'		=> 'title',
			'order'			=> 'ASC',
			'limit'			=> 4,
			'hide_empty'	=> false,
			'slugs'			=> '',
			'el_class'		=> ''
		), $atts));

		$args = array(
			'section_title'	=> $section_title,
			'pre_title'		=> $pre_title,
			'section_class'	=> $el_class,
		);

		$taxonomy_args = array(
			'orderby'		=> $orderby,
			'order'			=> $order,
			'number'		=> $limit,
			'hide_empty'	=> $hide_empty,
			'slugs'			=> $slugs
		);

		$args['category_args'] = $taxonomy_args;

		$html = '';
		if( function_exists( 'pizzaro_product_categories' ) ) {
			ob_start();
			pizzaro_product_categories( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_product_categories' , 'pizzaro_product_categories_element' );

endif;