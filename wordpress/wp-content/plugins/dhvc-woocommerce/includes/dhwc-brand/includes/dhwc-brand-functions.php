<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!function_exists('dhwc_is_active')){
	/**
	 * Check woocommerce plugin is active
	 * 
	 * @return boolean .TRUE is active
	 */
	function dhwc_is_active(){
		$plugin = 'woocommerce/woocommerce.php';
		
		if (in_array( $plugin, (array) get_option( 'active_plugins', array() ) ))
			return true;
			
		if ( !is_multisite() )
		return false;
	
		$plugins = get_site_option( 'active_sitewide_plugins');
		if ( isset($plugins[$plugin]) )
			return true;
	
		return false;
	}
}

function dhwc_recount_product_brand_after_stock_change($product_id){
	if ( get_option( 'woocommerce_hide_out_of_stock_items' ) != 'yes' )
		return;

	$product_terms = get_the_terms( $product_id, 'product_brand' );
	if ( $product_terms ) {
		$product_brands = array();

		foreach ( $product_terms as $term ) {
			$product_brands[ $term->term_id ] = $term->parent;
		}

		_wc_term_recount( $product_brands, get_taxonomy( 'product_brand' ), false, false );
	}
}
add_action( 'woocommerce_product_set_stock_status', 'dhwc_recount_product_brand_after_stock_change' );

function dhwc_product_brand_change_term_counts($taxonomies){
	$taxonomies[] = 'product_brand';
	return $taxonomies;
}
add_filter('woocommerce_change_term_counts', 'dhwc_product_brand_change_term_counts');

function dhwc_subbrand_thumbnail( $brand ) {
	$small_thumbnail_size  	= apply_filters( 'single_product_small_thumbnail_size', 'dh-full' );
	$dimensions    			= wc_get_image_size( $small_thumbnail_size );
	$thumbnail_id  			= get_woocommerce_term_meta( $brand->term_id, 'thumbnail_id', true  );

	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
		$image = $image[0];
	} else {
		$image = wc_placeholder_img_src();
	}

	if ( $image ) {
		// Prevent esc_url from breaking spaces in urls for image embeds
		// Ref: http://core.trac.wordpress.org/ticket/23605
		$image = str_replace( ' ', '%20', $image );

		echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $brand->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
	}
}

/**
 * dhwc_product_dropdown_brands function
 * @return string
 */
function dhwc_product_dropdown_brands($args=array()){
	global $wp_query, $woocommerce;

	if ( ! class_exists( 'WC_Product_Cat_Dropdown_Walker' ) )
		include_once( $woocommerce->plugin_path() . '/includes/walkers/class-product-cat-dropdown-walker.php' );

	$current_product_brand = isset( $wp_query->query_vars['product_brand'] ) ? $wp_query->query_vars['product_brand'] : '';
	
	$defaults            = array(
		'pad_counts'         => 1,
		'show_count'         => 1,
		'hierarchical'       => 1,
		'hide_empty'         => 1,
		'show_uncategorized' => 1,
		'orderby'            => 'name',
		'selected'           => $current_product_brand,
		'menu_order'         => false
	);
	$args = wp_parse_args( $args, $defaults );

	if ( $args['orderby'] == 'order' ) {
		$args['menu_order'] = 'asc';
		$args['orderby']    = 'name';
	}

	$terms = get_terms( 'product_brand', apply_filters( 'dhwc_product_dropdown_brands_get_terms_args', $args ) );

	if ( ! $terms ) {
		return;
	}
	$output  = "<select name='product_brand' class='dropdown_product_brand'>";
	$output .= '<option value="" ' .  selected( $current_product_brand, '', false ) . '>' . __( 'Select a brand', DHVC_WOO ) . '</option>';
	$output .= woocommerce_walk_category_dropdown_tree( $terms, 0, $args );
	if ( $args['show_uncategorized'] ) {
		$output .= '<option value="0" ' . selected( $current_product_brand, '0', false ) . '>' . __( 'Uncategorized', DHVC_WOO ) . '</option>';
	}
	$output .= "</select>";

	echo $output;
}
/**
 * get brand by post
 *
 */
function dhwc_get_brands($post_id = 0, $sep = ', ', $before = '', $after = ''){
	global $post;
	if ( $post_id )
		$post_id = $post->ID;
		
	return get_the_term_list( $post_id, 'product_brand', $before, $sep, $after );
}

/**
 * get thumbnail product brand by id
 * @param int $brand_id
 * @param string $size
 */
function dhwc_get_product_brand_thumbnail_url($brand_id,$size = 'full'){
	$thumbnail_id = get_woocommerce_term_meta($brand_id, 'thumbnail_id', true);
	if ($thumbnail_id)
		return current(wp_get_attachment_image_src($thumbnail_id, $size));
		
}

/**
 * Display or retrieve the HTML list of brands.
 * 
 * @return string HTML content only if 'echo' argument is 0.
 */
function dhwc_list_brands( $args = '' ){
	$defaults = array(
		'show_option_all' => '', 'show_option_none' => __('No brands',DHVC_WOO),
		'orderby' => 'name', 'order' => 'ASC',
		'style' => 'list',
		'show_count' => 0, 'hide_empty' => 1,
		'use_desc_for_title' => 1, 'child_of' => 0,
		'feed' => '', 'feed_type' => '',
		'feed_image' => '', 'exclude' => '',
		'exclude_tree' => '', 'current_brand' => 0,
		'hierarchical' => true, 'title_li' => __( 'Brands',DHVC_WOO),
		'echo' => 1, 'depth' => 0,
		'taxonomy' => 'product_brand'
	);

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] )
		$r['pad_counts'] = true;

	if ( true == $r['hierarchical'] ) {
		$r['exclude_tree'] = $r['exclude'];
		$r['exclude'] = '';
	}

	if ( !isset( $r['class'] ) )
		$r['class'] = ( 'brand' == $r['taxonomy'] ) ? 'brands' : $r['taxonomy'];

	extract( $r );

	if ( !taxonomy_exists($taxonomy) )
		return false;

		
	$brans = (array) get_terms( $taxonomy, $args );

	foreach ( array_keys( $brans ) as $k )
		_make_cat_compat( $brans[$k] );
	
	$output = '';
	if ( $title_li && 'list' == $style )
			$output = '<li class="' . esc_attr( $class ) . '">' . $title_li . '<ul>';

	if ( empty( $brans ) ) {
		if ( ! empty( $show_option_none ) ) {
			if ( 'list' == $style )
				$output .= '<li>' . $show_option_none . '</li>';
			else
				$output .= $show_option_none;
		}
	} else {
		if ( ! empty( $show_option_all ) ) {
			$posts_page = ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
			$posts_page = esc_url( $posts_page );
			if ( 'list' == $style )
				$output .= "<li><a href='$posts_page'>$show_option_all</a></li>";
			else
				$output .= "<a href='$posts_page'>$show_option_all</a>";
		}

		if ( empty( $r['current_brand'] ) && ( is_tax())) {
			$current_term_object = get_queried_object();
			if ( $current_term_object && $r['taxonomy'] === $current_term_object->taxonomy )
				$r['current_brand'] = get_queried_object_id();
		}

		if ( $hierarchical )
			$depth = $r['depth'];
		else
			$depth = -1; // Flat.

		$output .= walk_category_tree( $brans, $depth, $r );
	}

	if ( $title_li && 'list' == $style )
		$output .= '</ul></li>';

	$output = apply_filters( 'dhwc_list_brands', $output, $args );

	if ( $echo )
		echo ($output);
	else
		return $output;
}













