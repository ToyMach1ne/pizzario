<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (dhwc_is_active() && !class_exists('DHWC_Brand')){
	class DHWC_Brand {
		
		public function __construct(){
			if(taxonomy_exists('product_brand'))
				return;
			
			include_once dirname(__FILE__).'/includes/dhwc-brand-functions.php';
			
			add_action( 'woocommerce_register_taxonomy',array($this,'dhwc_init_taxonomy'));
			add_action( 'loop_shop_post_in', array($this,'dhwc_loop_shop_post_in_brand'), 11 );
			add_action('widgets_init',array($this,'dhwc_widget_init'));
			
			if (is_admin()){
				include_once dirname(__FILE__).'/includes/dhwc-brand-admin.php';
			}else{
				add_shortcode('dhwc_product_brands', array(&$this,'dhwc_product_brands_shortcode'));
			}

			add_filter( 'template_include',array($this,'dhwc_template_include'));
			
			//add_filter('body_class',array($this,'dhwc_add_body_class'));
			
			if(get_option('dhwc_product_brand_show_desc') =='yes'){
				add_action( 'woocommerce_archive_description',array($this,'dhwc_show_product_brand_descs'));
			}

			add_action( 'woocommerce_product_meta_end',array($this,'dhwc_add_product_brand_meta'));
		}
		
		function dhwc_init_taxonomy(){
			if(taxonomy_exists('product_brand'))
				return;
			global $woocommerce;
			
			$permalinks 	= get_option( 'woocommerce_permalinks' );
			$shop_page_id 	= wc_get_page_id('shop');
			$base_slug 		= $shop_page_id > 0 && get_post( $shop_page_id ) ? get_page_uri( $shop_page_id ) : 'shop';
		
			$product_brand_slug = empty( $permalinks['brand_base'] ) ? _x( 'product-brand', 'slug', DHVC_WOO ) : $permalinks['brand_base'];
		
			register_taxonomy ( 'product_brand', 
				apply_filters ( 'dhwc_taxonomy_objects_product_brand', array ('product' ) ), 
				apply_filters ( 'dhwc_taxonomy_args_product_brand', array (
					'hierarchical' => true,
					'update_count_callback' => '_update_post_term_count',
					'label' => __ ( 'Product Brands', DHVC_WOO ),
					'labels' => array (
							'name' => __ ( 'Product Brands', DHVC_WOO ),
							'singular_name' => __ ( 'Product Brand', DHVC_WOO ),
							'menu_name' => _x ( 'Brands', 'Admin menu name', DHVC_WOO ),
							'search_items' => __ ( 'Search Product Brands', DHVC_WOO ),
							'all_items' => __ ( 'All Product Brands', DHVC_WOO ),
							'parent_item' => __ ( 'Parent Product Brand', DHVC_WOO ),
							'parent_item_colon' => __ ( 'Parent Product Brand:', DHVC_WOO ),
							'edit_item' => __ ( 'Edit Product Brand', DHVC_WOO ),
							'update_item' => __ ( 'Update Product Brand', DHVC_WOO ),
							'add_new_item' => __ ( 'Add New Product Brand', DHVC_WOO ),
							'new_item_name' => __ ( 'New Product Brand Name', DHVC_WOO ) 
					),
					'show_ui' => true,
					'show_in_nav_menus' => true,
					'query_var' => true,
					'capabilities' => array (
							'manage_terms' => 'manage_product_terms',
							'edit_terms' => 'edit_product_terms',
							'delete_terms' => 'delete_product_terms',
							'assign_terms' => 'assign_product_terms' 
					),
					'rewrite' => array (
							'slug' => $product_brand_slug,
							'with_front' => false,
							'hierarchical' => true 
					) 
			) ) );
		}
		
		
		/**
		 * Filter product by brand
		 *
		 * @param $filtered_posts
		*/
		function dhwc_loop_shop_post_in_brand($filtered_posts){
			global $woocommerce, $_chosen_attributes;
			if ( is_active_widget( false, false, 'dhwc_widget_layered_nav', true ) && ! is_admin() ) {
				if ( ! empty( $_GET[ 'filter_product_brand' ] ) ) {
					$terms 	= explode( ',', $_GET[ 'filter_product_brand' ] );
		
					if ( sizeof( $terms ) > 0 ) {
		
						$_chosen_attributes['product_brand']['terms'] = $terms;
						$_chosen_attributes['product_brand']['query_type'] = 'and';
		
						$matched_products = get_posts(
								array(
										'post_type' 	=> 'product',
										'numberposts' 	=> -1,
										'post_status' 	=> 'publish',
										'fields' 		=> 'ids',
										'no_found_rows' => true,
										'tax_query' => array(
												'relation' => 'AND',
												array(
														'taxonomy' 	=> 'product_brand',
														'terms' 	=> $terms,
														'field' 	=> 'id'
												)
										)
								)
						);
		
						if(isset($woocommerce->query->layered_nav_post__in)){
							$woocommerce->query->layered_nav_post__in = array_merge( $woocommerce->query->layered_nav_post__in, $matched_products );
							$woocommerce->query->layered_nav_post__in[] = 0;
						}
		
						if ( sizeof( $filtered_posts ) == 0 ) {
							$filtered_posts = $matched_products;
							$filtered_posts[] = 0;
						} else {
							$filtered_posts = array_intersect( $filtered_posts, $matched_products );
							$filtered_posts[] = 0;
						}
					}
				}
			}
			return (array) $filtered_posts;
		}
		
	
		
		/**
		 * register widget
		*/
		function dhwc_widget_init(){
			// Includes
			include_once dirname(__FILE__).'/includes/dhwc-widget-brands.php';
			include_once dirname(__FILE__).'/includes/dhwc-widget-layered-nav.php';
			include_once dirname(__FILE__).'/includes/dhwc-widget-slider-brands.php';
		
			// Register widgets
			register_widget('DHWC_Widget_Layered_Nav');
			register_widget('DHWC_Widget_Brands');
			register_widget('DHWC_Widget_Brand_Slider');
		}
		
		
		/**
		 * Load a template.
		 *
		 * Handles template usage so that we can use our own templates instead of the themes.
		 *
		 * Templates are in the 'templates' folder. woocommerce looks for theme
		 * overrides in /theme/woocommerce/ by default
		 *
		 * For beginners, it also looks for a woocommerce.php template first. If the user adds
		 * this to the theme (containing a woocommerce() inside) this will be used for all
		 * woocommerce templates.
		 *
		 * @access public
		 * @param mixed $template
		 * @return string
		 */
		function dhwc_template_include($template){
			$template_url = apply_filters( 'woocommerce_template_url', 'woocommerce/' );
			$find = array( 'woocommerce.php' );
			$file = '';
			if ( is_tax( 'product_brand' ) ) {
				$term = get_queried_object();
	
				$file 		= 'taxonomy-' . $term->taxonomy . '.php';
				$find[] 	= 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] 	= $template_url . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] 	= $file;
				$find[] 	= $template_url . $file;
	
			}
			if ( $file ) {
				$template = locate_template( $find );
				if ( ! $template ) $template = untrailingslashit(dirname( __FILE__ )) . '/templates/' . $file;
			}
			return $template;
		}
		
		
		/**
		 * add class to body tag
		*/
		function dhwc_add_body_class($classes) {
			return $classes[] = 'woocommerce woocommerce-page';
		}
		/**
		 * show brand description
		*/
		function dhwc_show_product_brand_descs(){
			if (!is_tax('product_brand'))
				return;
			if (!get_query_var('term'))
				return;
				
			$thumbnail = '';
			$term = get_term_by( 'slug', get_query_var( 'term' ),'product_brand');
			$thumbnail = dhwc_get_product_brand_thumbnail_url( $term->term_id, 'shop_catalog' );
		
			?>
			<div class="term-description product-brand-desc">
					<?php echo wc_format_content( term_description() );?>
			</div>
		<?php
		 }
		    
		    
	    /**
	     * dhwc_add_product_brand_meta function
	     */
	    function dhwc_add_product_brand_meta(){
	    	global $post;
			if ( is_singular( 'product' ) ) {
				echo apply_filters('dhwc_product_brand_meta',dhwc_get_brands( $post->ID, ', ', ' <span class="posted_in">' . __('Brand:', DHVC_WOO).' ', '.</span>' ));
			}
	    }
	    
	    public function dhwc_product_brands_shortcode($atts,$content=null){
	    	global $woocommerce_loop;
	    	
	    	$atts = shortcode_atts( array(
	    		'number'     => null,
	    		'orderby'    => 'name',
	    		'order'      => 'ASC',
	    		'columns'    => '4',
	    		'hide_empty' => 1,
	    		'parent'     => '',
	    		'ids'        => ''
	    	), $atts );
	    	
	    	if ( isset( $atts['ids'] ) ) {
	    		$ids = explode( ',', $atts['ids'] );
	    		$ids = array_map( 'trim', $ids );
	    	} else {
	    		$ids = array();
	    	}
	    	
	    	$hide_empty = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;
	    	
	    	// get terms and workaround WP bug with parents/pad counts
	    	$args = array(
	    		'orderby'    => $atts['orderby'],
	    		'order'      => $atts['order'],
	    		'hide_empty' => $hide_empty,
	    		'include'    => $ids,
	    		'pad_counts' => true,
	    		'child_of'   => $atts['parent']
	    	);
	    	
	    	$product_brands = get_terms( 'product_brand', $args );
	    	
	    	if ( '' !== $atts['parent'] ) {
	    		$product_brands = wp_list_filter( $product_brands, array( 'parent' => $atts['parent'] ) );
	    	}
	    	
	    	if ( $hide_empty ) {
	    		foreach ( $product_brands as $key => $brand ) {
	    			if ( $brand->count == 0 ) {
	    				unset( $product_brands[ $key ] );
	    			}
	    		}
	    	}
	    	
	    	if ( $atts['number'] ) {
	    		$product_brands = array_slice( $product_brands, 0, $atts['number'] );
	    	}
	    	
	    	$woocommerce_loop['columns'] = $atts['columns'];
	    	
	    	ob_start();
	    	
	    	// Reset loop/columns globals when starting a new loop
	    	$woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';
	    	
	    	if ( $product_brands ) {
	    	
	    		woocommerce_product_loop_start();
	    	
	    		foreach ( $product_brands as $brand ) {
	    	
	    			wc_get_template( 'content-product_brand.php', array(
	    			'brand' => $brand
	    			) );
	    	
	    		}
	    	
	    		woocommerce_product_loop_end();
	    	
	    	}
	    	
	    	woocommerce_reset_loop();
	    	
	    	return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
	    }
	}
	new DHWC_Brand();
}