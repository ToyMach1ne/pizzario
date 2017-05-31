<?php

if ( ! function_exists( 'pizzaro_recent_posts_element' ) ) :

	function pizzaro_recent_posts_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'section_title'		=> '',
			'pre_title'			=> '',
			'post_choice'		=> 'recent',
			'limit'				=> 3,
			'ids'				=> '',
			'show_read_more'	=> false,
			'show_comment_link'	=> false,
			'el_class'			=> '',
		), $atts));

		$args = array(
			'section_title'			=> $section_title,
			'pre_title'				=> $pre_title,
			'post_choice'			=> $post_choice,
			'post_id'				=> $ids,
			'limit'					=> $limit,
			'show_read_more'		=> $show_read_more,
			'show_comment_link'		=> $show_comment_link,
			'section_class'			=> $el_class,
		);

		$html = '';
		if( function_exists( 'pizzaro_recent_posts' ) ) {
			ob_start();
			pizzaro_recent_posts( $args );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'pizzaro_recent_posts' , 'pizzaro_recent_posts_element' );

endif;