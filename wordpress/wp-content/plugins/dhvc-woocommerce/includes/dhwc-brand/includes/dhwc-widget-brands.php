<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class DHWC_Widget_Brands extends WP_Widget {
	
	/**
	 * Constructor
	 */
	public function __construct(){
		$widget_options = array( 'classname' => 'woocommerce dhwc_widget_brands', 'description' => __( 'A list or dropdown of product brands.',DHVC_WOO) );
		$control_options = array();
		parent::__construct('dhwc_widget_brands',__('DHWOO Product Brands',DHVC_WOO), $widget_options, $control_options);
	}
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {
		global $wp_query, $post, $woocommerce;
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Product brands', DHVC_WOO ) : $instance['title'], $instance, $this->id_base);
		$count = $instance['count'] ? '1' : '0';
		$hierarchical = $instance['hierarchical'] ? true : false;
		$show_children_only = (isset($instance['show_children_only']) && $instance['show_children_only']) ? '1' : '0';
		$dropdown = $instance['dropdown'] ? '1' : '0';
		$orderby  = isset($instance['orderby']) ? $instance['orderby'] : 'order';
		$hide_empty = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : 0;
		
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$dropdown_args      = array( 'hide_empty' => $hide_empty );
		$list_args          = array( 'show_count' => $count, 'hierarchical' => $hierarchical, 'taxonomy' => 'product_brand', 'hide_empty' => $hide_empty );
		
		$list_args['menu_order'] = false;

		if ( $orderby == 'order' ) {
			$list_args['menu_order'] = 'asc';
		} else {
			$list_args['orderby'] = 'title';

		}
		
		// Setup Current Category
		$this->current_brand   = false;
		$this->brand_ancestors = array();
		
		if ( is_tax( 'product_brand' ) ) {
		
			$this->current_brand   = $wp_query->queried_object;
			$this->brand_ancestors = get_ancestors( $this->current_brand->term_id, 'product_brand' );
		
		} elseif ( is_singular( 'product' ) ) {
		
			$product_brand = wc_get_product_terms( $post->ID, 'product_brand', array( 'orderby' => 'parent' ) );
		
			if ( $product_brand ) {
				$this->current_brand   = end( $product_brand );
				$this->brand_ancestors = get_ancestors( $this->current_brand->term_id, 'product_brand' );
			}
		
		}
		
		// Show Siblings and Children Only
		if ( $show_children_only && $this->current_brand ) {
		
			// Top level is needed
			$top_level = get_terms(
				'product_brand',
				array(
					'fields'       => 'ids',
					'parent'       => 0,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);
		
			// Direct children are wanted
			$direct_children = get_terms(
				'product_brand',
				array(
					'fields'       => 'ids',
					'parent'       => $this->current_brand->term_id,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);
		
			// Gather siblings of ancestors
			$siblings  = array();
			if ( $this->brand_ancestors ) {
				foreach ( $this->brand_ancestors as $ancestor ) {
					$ancestor_siblings = get_terms(
						'product_brand',
						array(
							'fields'       => 'ids',
							'parent'       => $ancestor,
							'hierarchical' => false,
							'hide_empty'   => false
						)
					);
					$siblings = array_merge( $siblings, $ancestor_siblings );
				}
			}
		
			if ( $hierarchical ) {
				$include = array_merge( $top_level, $this->brand_ancestors, $siblings, $direct_children, array( $this->current_brand->term_id ) );
			} else {
				$include = array_merge( $direct_children );
			}
		
			$dropdown_args['include'] = implode( ',', $include );
			$list_args['include']     = implode( ',', $include );
		
			if ( empty( $include ) ) {
				return;
			}
		
		} elseif ( $show_children_only ) {
			$dropdown_args['depth']        = 1;
			$dropdown_args['child_of']     = 0;
			$dropdown_args['hierarchical'] = 1;
			$list_args['depth']            = 1;
			$list_args['child_of']         = 0;
			$list_args['hierarchical']     = 1;
		}
		if ( $dropdown ) {
			$dropdown_defaults = array(
				'show_count'         => $count,
				'hierarchical'       => $hierarchical,
				'show_uncategorized' => 0,
				'orderby'            => $orderby,
				'selected'           => $this->current_brand ? $this->current_brand->slug : ''
			);
			$dropdown_args = wp_parse_args( $dropdown_args, $dropdown_defaults );
			dhwc_product_dropdown_brands( $dropdown_args );
			wc_enqueue_js( "
				jQuery( '.dropdown_product_brand' ).change( function() {
					if ( jQuery(this).val() != '' ) {
						var this_page = '';
						var home_url  = '" . esc_js( home_url( '/' ) ) . "';
						if ( home_url.indexOf( '?' ) > 0 ) {
							this_page = home_url + '&product_brand=' + jQuery(this).val();
						} else {
							this_page = home_url + '?product_brand=' + jQuery(this).val();
						}
						location.href = this_page;
					}
				});
			" );

		} else {

			include_once(dirname(__FILE__) . '/dhwc_product_brand_list_walker.php' );

			$list_args['walker'] 					= new DHWC_Product_Brand_List_Walker;
			$list_args['title_li'] 					= '';
			$list_args['show_children_only']		= ( isset( $instance['show_children_only'] ) && $instance['show_children_only'] ) ? 1 : 0;
			$list_args['pad_counts'] 				= 1;
			$list_args['show_option_none'] 			= __('No product brands exist.', DHVC_WOO );
			$list_args['current_brand']				= ( $this->current_brand ) ? $this->current_brand->term_id : '';
			$list_args['current_brand_ancestors']	= $this->brand_ancestors;

			echo '<ul class="product-brands">';

			dhwc_list_brands( apply_filters( 'dhwc_product_brands_widget_args', $list_args ) );

			echo '</ul>';

		}

		echo $after_widget;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::form()
	 */
	public function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'order';
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$show_children_only = isset( $instance['show_children_only'] ) ? (bool) $instance['show_children_only'] : false;
		$hide_empty = isset($instance['hide_empty']) ? (bool) $instance['hide_empty'] : false ;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', DHVC_WOO ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Order by:', DHVC_WOO ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>" name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>">
			<option value="order" <?php selected($orderby, 'order'); ?>><?php _e( 'Order', DHVC_WOO ); ?></option>
			<option value="name" <?php selected($orderby, 'name'); ?>><?php _e( 'Name', DHVC_WOO ); ?></option>
		</select></p>

		<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('dropdown') ); ?>" name="<?php echo esc_attr( $this->get_field_name('dropdown') ); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Show as dropdown', DHVC_WOO ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts', DHVC_WOO ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hierarchical') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hierarchical') ); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy', DHVC_WOO ); ?></label><br/>

		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_children_only') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_children_only') ); ?>"<?php checked( $show_children_only ); ?> />
		<label for="<?php echo $this->get_field_id('show_children_only'); ?>"><?php _e( 'Only show children for the current brand', DHVC_WOO ); ?></label></p>
		
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>"<?php checked( $hide_empty ); ?> />
		<label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide Empty Brands', DHVC_WOO ); ?></label></p>
		
		<?php 
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? true : false;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['show_children_only'] = !empty($new_instance['show_children_only']) ? 1 : 0;
		$instance['hide_empty'] = !empty($new_instance['hide_empty']) ? 1 : 0;

		return $instance;
	}
}