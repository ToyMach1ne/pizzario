<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class DHWC_Widget_Brand_Slider extends WP_Widget {
	/**
	 * Constructor
	 */
	public function __construct(){
		$widget_options = array( 'classname' => 'dhwc_widget_brand_slider', 'description' => __( 'Shows product brans with slider',DHVC_WOO) );
		$control_options = array();
		parent::__construct('dhwc_widget_brand_slider',__('DHWOO Product Brand Slider',DHVC_WOO), $widget_options, $control_options);
		
		if (!is_admin()){
			add_action("wp_print_styles", array(&$this, 'enqueue_scripts'));
			wp_enqueue_script('dhwc-widget-brand-slider',DHVC_WOO_URL.'/assets/js/jquery.bxslider.min.js',array('jquery'),'',true);
		}
		
	}
	
	function enqueue_scripts(){
		wp_enqueue_style('dhwc-widget-brand-slider',DHVC_WOO_URL.'/assets/css/jquery.bxslider.css');
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::widget()
	 */
	public function widget($args, $instance){
		extract( $args );
		
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Brands', DHVC_WOO ) : $instance['title'], $instance, $this->id_base);
		$o = isset($instance['orderby']) ? $instance['orderby'] : 'order';
		$h = $instance['hide_empty'] ? true : false;
		if ($o == 'name'){
			$order = 'asc';
		}else{
			$order = 'desc';
		}
		$brands = get_terms(
			'product_brand', 
			array(
				'hide_empty' => $h,
				'orderby' =>$o,
				'order' => $order )
		);
		if (!$brands)
			return;
		
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		?>
		<div class="product-brand-slide">
			<ul id="pb-<?php echo $args['widget_id']?>" class="list-brand">
				<?php foreach ($brands as $brand){?>
				<?php 
				$thumbnail = dhwc_get_product_brand_thumbnail_url( $brand->term_id);
				if ( ! $thumbnail )
					$thumbnail = woocommerce_placeholder_img_src();
				?>
				<li class="item">
					<a title="<?php echo $brand->name; ?>" href="<?php echo get_term_link($brand->slug,'product_brand'); ?>">
						<img alt="<?php echo $brand->name; ?>" src="<?php echo $thumbnail?>">
					</a>
					<h2><a title="<?php echo $brand->name; ?>" href="<?php echo get_term_link($brand->slug,'product_brand'); ?>"><?php echo $brand->name; ?></a></h2>
				</li>
				<?php } ?>
			</ul>
		</div>
		<script type="text/javascript">
		<!--
		jQuery(document).ready(function($){
			$('#pb-<?php echo $args['widget_id']?>').bxSlider();
		});
		//-->
		</script>
		<?php
		
		echo $after_widget;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::update()
	 */
	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['hide_empty'] = !empty($new_instance['hide_empty']) ? true : false;
		return $instance;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::form()
	 */
	public function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'order';
		$hide_empty = isset( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : false;
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', DHVC_WOO) ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset ( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>" />
			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Order by:', DHVC_WOO ) ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>" name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>">
				<option value="order" <?php selected($orderby, 'order'); ?>><?php _e( 'Order', DHVC_WOO ); ?></option>
				<option value="name" <?php selected($orderby, 'name'); ?>><?php _e( 'Name', DHVC_WOO ); ?></option>
			</select>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>"<?php checked( $hide_empty ); ?> />
			<label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide empty brands', DHVC_WOO ); ?></label>
		</p>
		<?php
	}
}