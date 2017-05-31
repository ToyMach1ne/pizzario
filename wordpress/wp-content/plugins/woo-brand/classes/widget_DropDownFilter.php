<?php 
/*
	WidGet For Woo Brand V3.0
*/
//Add Carousel Pro Brand`s
class pw_widget_carousel extends WP_Widget {
	function __construct() {
		parent::__construct(
			'pw_widget_carousel', // Base ID
			__('Woo Brand`s Carousel', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Display a Carousel of your Brands on widget.', 'woocommerce-brands' ), ) // Args
		);
	}
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		echo do_shortcode('[pw_brand_carousel
				pw_brand="'.$instance['pw_brand'].'" 
				pw_except_brand="'.$instance['pw_except_brand'].'" 
				pw_style="'.$instance['pw_style'].'" 
				pw_tooltip="'.$instance['pw_tooltip'].'" 
				pw_round_corner="'.$instance['pw_round_corner'].'" 
				pw_show_image="'.$instance['pw_show_image'].'" 
				pw_featured="'.$instance['pw_featured'].'" 
				pw_show_title="'.$instance['pw_show_title'].'" 
				pw_show_count="'.$instance['pw_show_count'].'" 
				pw_item_width="'.$instance['pw_item_width'].'" 
				pw_item_marrgin="'.$instance['pw_item_marrgin'].'" 
				pw_slide_direction="'.$instance['pw_slide_direction'].'" 
				pw_show_pagination="'.$instance['pw_show_pagination'].'" 
				pw_show_control="'.$instance['pw_show_control'].'" 
				pw_item_per_view="'.$instance['pw_item_per_view'].'" 
				pw_item_per_slide="'.$instance['pw_item_per_slide'].'" 
				pw_slide_speed="'.$instance['pw_slide_speed'].'" 
				pw_auto_play="'.$instance['pw_auto_play'].'"]');			
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('title:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset ( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_brand' ); ?>"><?php _e('Brand`s:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_brand' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_brand' ) ); ?>" value="<?php if ( isset ( $instance['pw_brand'] ) ) echo esc_attr( $instance['pw_brand'] ); ?>" placeholder="<?php _e('All', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_except_brand' ); ?>"><?php _e('Except Brand`s:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_except_brand' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_except_brand' ) ); ?>" value="<?php if ( isset ( $instance['pw_except_brand'] ) ) echo esc_attr( $instance['pw_except_brand'] ); ?>" placeholder="<?php _e('None', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_featured' ); ?>"><?php _e('Display Only Featured Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_featured'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_featured'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Only Featured Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_count' ); ?>"><?php _e('Display Count Of Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_count'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_show_count'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Count Of Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_image' ); ?>"><?php _e('Display Brand`s Image:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_image'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_show_image'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Brand`s Image','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_title' ); ?>"><?php _e('Display Brand`s Title:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_title'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_show_title'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Brand`s Title','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_tooltip' ); ?>"><?php _e('Show Tooltip:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_tooltip'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_tooltip'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Show Tooltip','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_style' ); ?>"><?php _e('Style:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_style' ) ); ?>">
				<option value="wb-car-style1" <?php selected( @$instance['pw_style'], 'wb-car-style1' ,1) ?>><?php _e('Style 1', 'woocommerce-brands') ?></option>
				<option value="wb-car-style2" <?php selected( @$instance['pw_style'], 'wb-car-style2',1 ) ?>><?php _e('Style 2', 'woocommerce-brands') ?></option>
				<option value="wb-car-style3" <?php selected( @$instance['pw_style'], 'wb-car-style3',1 ) ?>><?php _e('Style 3', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_round_corner' ); ?>"><?php _e('Round Corner:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_round_corner'); ?>" type="checkbox" value="wb-car-round" <?php checked( @$instance['pw_round_corner'], 'wb-car-round' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Round Corner','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_item_width' ); ?>"><?php _e('Item Width:', 'woocommerce-brands') ?></label>
			<input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_item_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_item_width' ) ); ?>" value="<?php if ( isset ( $instance['pw_item_width'] ) ) echo esc_attr( $instance['pw_item_width'] ); ?>" placeholder="<?php _e('300', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_item_marrgin' ); ?>"><?php _e('Item Marrgin:', 'woocommerce-brands') ?></label>
			<input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_item_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_item_marrgin' ) ); ?>" value="<?php if ( isset ( $instance['pw_item_marrgin'] ) ) echo esc_attr( $instance['pw_item_marrgin'] ); ?>" placeholder="<?php _e('300', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_slide_direction' ); ?>"><?php _e('Slide direction:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_slide_direction' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_slide_direction' ) ); ?>">
				<option value="vertical" <?php selected( @$instance['pw_slide_direction'], 'vertical',1) ;?>><?php _e('Vertical', 'woocommerce-brands') ?></option>
				<option value="horizontal" <?php selected( @$instance['pw_slide_direction'], 'horizontal',1); ?>><?php _e('Horizontal', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_pagination' ); ?>"><?php _e('Show Pagination:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_pagination'); ?>" type="checkbox" value="true" <?php checked( @$instance['pw_show_pagination'], 'true' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Show Pagination','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_control' ); ?>"><?php _e('Show Control:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_control'); ?>" type="checkbox" value="true" <?php checked( @$instance['pw_show_control'], 'true' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Show Control','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_item_per_view' ); ?>"><?php _e('Item Per View:', 'woocommerce-brands') ?></label>
			<input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_item_per_view' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_item_per_view' ) ); ?>" value="<?php if ( isset ( $instance['pw_item_per_view'] ) ) echo esc_attr( $instance['pw_item_per_view'] ); ?>" placeholder="<?php _e('3', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_slide_speed' ); ?>"><?php _e('Slide Speed:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_slide_speed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_slide_speed' ) ); ?>">
				<option value="1000" <?php selected( @$instance['pw_slide_speed'], '1000',1) ?>><?php _e('1 Sec', 'woocommerce-brands') ?></option>
				<option value="2000" <?php selected( @$instance['pw_slide_speed'], '2000',1 ) ?>><?php _e('2 Sec', 'woocommerce-brands') ?></option>
				<option value="3000" <?php selected( @$instance['pw_slide_speed'], '3000',1 ) ?>><?php _e('3 Sec', 'woocommerce-brands') ?></option>
				<option value="4000" <?php selected( @$instance['pw_slide_speed'], '4000',1 ) ?>><?php _e('4 Sec', 'woocommerce-brands') ?></option>
				<option value="5000" <?php selected( @$instance['pw_slide_speed'], '5000',1 ) ?>><?php _e('5 Sec', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_auto_play' ); ?>"><?php _e('Auto play:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_auto_play'); ?>" type="checkbox" value="true" <?php checked( @$instance['pw_auto_play'], 'true' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Auto play','woocommerce-brands'); ?></label></p>
		</p>
		
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) :'';
		$instance['pw_brand']     = ( ! empty($new_instance['pw_brand'] )) ? $new_instance['pw_brand'] : '';
		$instance['pw_except_brand']     = isset($new_instance['pw_except_brand'] ) ? $new_instance['pw_except_brand'] : 'null';
		$instance['pw_style']     = isset($new_instance['pw_style'] ) ? $new_instance['pw_style'] : 'wb-car-style1';
		$instance['pw_tooltip']     = isset($new_instance['pw_tooltip'] ) ? $new_instance['pw_tooltip'] : 'no';
		$instance['pw_round_corner']     = isset($new_instance['pw_round_corner'] ) ? $new_instance['pw_round_corner'] : 'wb-car-no';
		$instance['pw_show_image']     = isset($new_instance['pw_show_image'] ) ? $new_instance['pw_show_image'] : 'no';
		$instance['pw_featured']     = isset($new_instance['pw_featured'] ) ? $new_instance['pw_featured'] : 'no';
		$instance['pw_show_title']     = isset($new_instance['pw_show_title'] ) ? $new_instance['pw_show_title'] : 'no';
		$instance['pw_show_count']     = isset($new_instance['pw_show_count'] ) ? $new_instance['pw_show_count'] : 'no';
		$instance['pw_item_width']     = isset($new_instance['pw_item_width'] ) ? $new_instance['pw_item_width'] : '300';
		$instance['pw_item_marrgin']     = isset($new_instance['pw_item_marrgin'] ) ? $new_instance['pw_item_marrgin'] : '10';
		$instance['pw_slide_direction']     = isset($new_instance['pw_slide_direction'] ) ? $new_instance['pw_slide_direction'] : 'vertical';
		$instance['pw_show_pagination']     = isset($new_instance['pw_show_pagination'] ) ? $new_instance['pw_show_pagination'] : 'false';
		$instance['pw_show_control']     = isset($new_instance['pw_show_control'] ) ? $new_instance['pw_show_control'] : 'false';
		$instance['pw_item_per_view']     = isset($new_instance['pw_item_per_view'] ) ? $new_instance['pw_item_per_view'] : '3';
		$instance['pw_item_per_slide']     = isset($new_instance['pw_item_per_slide'] ) ? $new_instance['pw_item_per_slide'] : '1';
		$instance['pw_slide_speed']     = isset($new_instance['pw_slide_speed'] ) ? $new_instance['pw_slide_speed'] : 'false';
		$instance['pw_auto_play']     = isset($new_instance['pw_auto_play'] ) ? $new_instance['pw_auto_play'] : 'false';
		return $instance;
	}
}
register_widget( 'pw_widget_carousel' );	

//Add Thumbnails
class pw_widget_thumbnails extends WP_Widget {
	function __construct() {
		parent::__construct(
			'pw_widget_thumbnails', // Base ID
			__('Woo Brand`s Thumbnails', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Display a Thumbnails of your Brands on widget.', 'woocommerce-brands' ), ) // Args
		);
	}
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo do_shortcode('
				[pw_brand_thumbnails 
					pw_brand="'.$instance['pw_brand'].'"
					pw_except_brand="'.$instance['pw_except_brand'].'"
					pw_style="'.$instance['pw_style'].'"
					pw_round_corner="'.$instance['pw_round_corner'].'"
					pw_tooltip="'.$instance['pw_tooltip'].'"
					pw_featured="'.$instance['pw_featured'].'"
					pw_count_of_number="'.$instance['pw_count_of_number'].'"
					pw_hide_empty_brands="'.$instance['pw_hide_empty_brands'].'"
					pw_show_title="'.$instance['pw_show_title'].'"
					pw_show_count="'.$instance['pw_show_count'].'"
					pw_order_by="'.$instance['pw_order_by'].'"
					pw_columns="'.$instance['pw_columns'].'"
				]
			');
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('title:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset ( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_brand' ); ?>"><?php _e('Brand`s:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_brand' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_brand' ) ); ?>" value="<?php if ( isset ( $instance['pw_brand'] ) ) echo esc_attr( $instance['pw_brand'] ); ?>" placeholder="<?php _e('All', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_except_brand' ); ?>"><?php _e('Except Brand`s:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_except_brand' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_except_brand' ) ); ?>" value="<?php if ( isset ( $instance['pw_except_brand'] ) ) echo esc_attr( $instance['pw_except_brand'] ); ?>" placeholder="<?php _e('None', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_count_of_number' ); ?>"><?php _e('Count Of Item:', 'woocommerce-brands') ?></label>
			<input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_count_of_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_count_of_number' ) ); ?>" value="<?php if ( isset ( $instance['pw_count_of_number'] ) ) echo esc_attr( $instance['pw_count_of_number'] ); ?>" placeholder="<?php _e('All', 'woocommerce-brands'); ?>" />
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_order_by' ); ?>"><?php _e('Order By:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_order_by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_order_by' ) ); ?>">
				<option value="name" <?php selected( @$instance['pw_order_by'], 'name' ,1) ?>><?php _e('Name', 'woocommerce-brands') ?></option>
				<option value="count" <?php selected( @$instance['pw_order_by'], 'count',1 ) ?>><?php _e('Count', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_featured' ); ?>"><?php _e('Display Only Featured Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_featured'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_featured'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Only Featured Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_hide_empty_brands' ); ?>"><?php _e('Hide Empty Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_hide_empty_brands'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_hide_empty_brands'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Hide Empty Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_title' ); ?>"><?php _e('Display Brand`s Title:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_title'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_show_title'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Brand`s Title','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_count' ); ?>"><?php _e('Display Count Of Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_count'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_show_count'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Count Of Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_tooltip' ); ?>"><?php _e('Show Tooltip:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_tooltip'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_tooltip'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Show Tooltip','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_style' ); ?>"><?php _e('Style:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_style' ) ); ?>">
				<option value="wb-thumb-style1" <?php selected( @$instance['pw_style'], 'wb-thumb-style1',1) ;?>><?php _e('Style 1', 'woocommerce-brands') ?></option>
				<option value="wb-thumb-style2" <?php selected( @$instance['pw_style'], 'wb-thumb-style2',1) ;?>><?php _e('Style 2', 'woocommerce-brands') ?></option>
				<option value="wb-thumb-style3" <?php selected( @$instance['pw_style'], 'wb-thumb-style3',1) ;?>><?php _e('Style 3', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_columns' ); ?>"><?php _e('Columns:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_columns' ) ); ?>">
				<option value="wb-col-md-12" <?php selected( @$instance['pw_columns'], 'wb-col-md-12',1) ;?>><?php _e('1', 'woocommerce-brands') ?></option>
				<option value="wb-col-md-6" <?php selected( @$instance['pw_columns'], 'wb-col-md-6',1) ;?>><?php _e('2', 'woocommerce-brands') ?></option>
				<option value="wb-col-md-4" <?php selected( @$instance['pw_columns'], 'wb-col-md-4',1) ;?>><?php _e('3', 'woocommerce-brands') ?></option>
				<option value="wb-col-md-3" <?php selected( @$instance['pw_columns'], 'wb-col-md-3',1) ;?>><?php _e('4', 'woocommerce-brands') ?></option>
				<option value="wb-col-md-2" <?php selected( @$instance['pw_columns'], 'wb-col-md-2',1) ;?>><?php _e('6', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_round_corner' ); ?>"><?php _e('Round Corner:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_round_corner'); ?>" type="checkbox" value="wb-thumb-round" <?php checked( @$instance['pw_round_corner'], 'wb-thumb-round' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Round Corner','woocommerce-brands'); ?></label></p>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) :'';
		$instance['pw_brand'] = isset( $new_instance['pw_brand'] )  ? $new_instance['pw_brand']  :'null';
		$instance['pw_except_brand'] =  isset( $new_instance['pw_except_brand'] ) ? $new_instance['pw_except_brand']  :'null';
		$instance['pw_style'] = isset( $new_instance['pw_style'] )  ? $new_instance['pw_style']  :'';
		$instance['pw_round_corner'] =  isset( $new_instance['pw_round_corner'] )  ? $new_instance['pw_round_corner'] :'wb-thumb-no';
		$instance['pw_tooltip'] = isset( $new_instance['pw_tooltip'] )  ? $new_instance['pw_tooltip'] :'no';
		$instance['pw_featured'] =  isset( $new_instance['pw_featured'] ) ? $new_instance['pw_featured'] :'no';
		$instance['pw_count_of_number'] =  isset( $new_instance['pw_count_of_number'] )  ? $new_instance['pw_count_of_number']:'3';
		$instance['pw_hide_empty_brands'] =  isset( $new_instance['pw_hide_empty_brands'] )  ? $new_instance['pw_hide_empty_brands'] :'no';
		$instance['pw_show_title'] =  isset( $new_instance['pw_show_title'] )  ? $new_instance['pw_show_title']  :'no';
		$instance['pw_show_count'] = isset( $new_instance['pw_show_count'] )  ? $new_instance['pw_show_count'] :'no';
		$instance['pw_order_by'] = isset( $new_instance['pw_order_by'] )  ? $new_instance['pw_order_by']  :'name';
		$instance['pw_columns'] = isset( $new_instance['pw_columns'] )  ? $new_instance['pw_columns']  :'4';
		return $instance;
	}
}
register_widget( 'pw_widget_thumbnails' );	

//Add A-z View
class pw_widget_a_z_views extends WP_Widget {
	function __construct() {
		parent::__construct(
			'pw_widget_a_z_views', // Base ID
			__('Woo Brand`s A-Z View', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Display a A-z views of your Brands on widget.', 'woocommerce-brands' ), ) // Args
		);
	}
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo do_shortcode('[pw_brand_a-z-view 
			pw_style="'.$instance['pw_style'].'"
			pw_except_brand="'.$instance['pw_except_brand'].'" 
			pw_show_count="'.$instance['pw_show_count'].'" 
			pw_featured="'.$instance['pw_featured'].'" 
			pw_hide_empty_brands="'.$instance['pw_hide_empty_brands'].'" 
			pw_scroll_height="'.$instance['pw_scroll_height'].'"]')
		;
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('title:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset ( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_style' ); ?>"><?php _e('Style:', 'woocommerce-brands') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'pw_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_style' ) ); ?>">
				<option value="wb-filter-style1" <?php selected( @$instance['pw_style'], 'wb-filter-style1' ,1) ?>><?php _e('Style 1', 'woocommerce-brands') ?></option>
				<option value="wb-filter-style2" <?php selected( @$instance['pw_style'], 'wb-filter-style2',1 ) ?>><?php _e('Style 2', 'woocommerce-brands') ?></option>
				<option value="wb-filter-style3" <?php selected( @$instance['pw_style'], 'wb-filter-style3',1 ) ?>><?php _e('Style 3', 'woocommerce-brands') ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_except_brand' ); ?>"><?php _e('Except Brand`s:', 'woocommerce-brands') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_except_brand' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_except_brand' ) ); ?>" value="<?php if ( isset ( $instance['pw_except_brand'] ) ) echo esc_attr( $instance['pw_except_brand'] ); ?>" placeholder="<?php _e('None', 'woocommerce-brands'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_featured' ); ?>"><?php _e('Display Only Featured Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_featured'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_featured'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Only Featured Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_show_count' ); ?>"><?php _e('Display Count Of Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_show_count'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_show_count'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Display Count Of Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_hide_empty_brands' ); ?>"><?php _e('Hide Empty Brands:', 'woocommerce-brands') ?></label>
			<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('pw_hide_empty_brands'); ?>" type="checkbox" value="yes" <?php checked( @$instance['pw_hide_empty_brands'], 'yes' ); ?> />				
			<label for="rss-show-summary"><?php echo _e('Hide Empty Brands','woocommerce-brands'); ?></label></p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pw_scroll_height' ); ?>"><?php _e('Scroll Height:', 'woocommerce-brands') ?></label>
			<input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pw_scroll_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pw_scroll_height' ) ); ?>" value="<?php if ( isset ( $instance['pw_scroll_height'] ) ) echo esc_attr( $instance['pw_scroll_height'] ); ?>" placeholder="<?php _e('300', 'woocommerce-brands'); ?>" />
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) :'';
		$instance['pw_style'] = isset( $new_instance['pw_style'] )  ? $new_instance['pw_style']  :'null';
		$instance['pw_except_brand'] = isset( $new_instance['pw_except_brand'] )  ? $new_instance['pw_except_brand']  :'null';
		$instance['pw_show_count'] = isset( $new_instance['pw_show_count'] )  ? $new_instance['pw_show_count']  :'null';
		$instance['pw_featured'] = isset( $new_instance['pw_featured'] )  ? $new_instance['pw_featured']  :'no';
		$instance['pw_hide_empty_brands'] = isset( $new_instance['pw_hide_empty_brands'] )  ? $new_instance['pw_hide_empty_brands']  :'no';
		$instance['pw_scroll_height'] = isset( $new_instance['pw_scroll_height'] )  ? $new_instance['pw_scroll_height']  :'null';
		return $instance;
	}
}
register_widget( 'pw_widget_a_z_views' );	






/*
	WidGet Brand V2 
*/
class pw_brands_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'pw_brands_Widget', // Base ID
			__('WooCommerce Brands', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Display a list of your Brands on your site.', 'woocommerce-brands' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
		if(get_option('pw_woocommerce_brands_show_categories')=="yes")
			$get_terms="product_cat";
		else
			$get_terms="product_brand";
					
		$categories = get_terms( $get_terms, 'orderby=name&hide_empty=0' );
			
			if ( ! empty( $categories ) ) {
				
				if($instance['show']=="dropdown")
				{
					
					wp_enqueue_script('woob-dropdown-script');

					?>
					<script type='text/javascript'>
                    /* <![CDATA[ */                    
						function onbrandsChange(value) {
							if(value=="")
								return false;
							var val=Array();
							val=value.split('@');
							//confirm(val[1]);
							if(val[0]=='slug')
								window.location= "<?php echo home_url(); ?>/?<?php echo $get_terms;?>="+val[1];
							else
								window.location= val[1];
						}
						
						jQuery(document).ready(function() {
//							jQuery("#payments").msDropdown({visibleRows:4});
							jQuery(".tech").msDropdown();	
			//				jQuery( '#carouselhor' ).elastislide(
			//					{
			//					 minItems : parseInt(jQuery( '#carouselhor' ).attr('title')),
			//					}
			//				);
						});
						/* ]]> */
                     </script>                    
					<?php
					echo '<select name="tech" class="tech" onchange="onbrandsChange(this.value)" >';
					//if(get_option('pw_woocommerce_brands_show_categories')=="yes")
						 echo '<option value="">'. __('Please Select','woocommerce-brands').'</option>';
					//else
					//	 echo '<option value="">'.__('Select One','woocommerce-brands') .'</option>';
						 
					 foreach( (array) $categories as $term ) { 
					  $url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
					  $display_type = get_woocommerce_term_meta( $term->term_id, 'featured', true );
					  $count="";
					  if($instance['post_counts']==1)
					   $count='( '. esc_html( $term->count ) .' )  ';
							 
					  if($instance['featured']==1 && $display_type==1)
					  {
						if($url!="")
							echo'<option value="url@'.esc_html( $url ).'">'.esc_html( $term->name ).$count.'</option>';
						else
							echo'<option value="slug@'.esc_html( $term->slug ).'" '.selected( esc_html ( get_query_var( 'product_brand' ) ) , esc_html( $term->slug ) , 1 ).'>'.esc_html( $term->name ).$count.'</option>';
						
					  }
					  elseif($instance['featured']==0)
					  {
						if($url!="")
							echo'<option value="url@'.esc_html( $url ).'">'.esc_html( $term->name ).$count.'</option>';
						else
							echo'<option value="slug@'.esc_html( $term->slug ).'" '.selected( esc_html ( get_query_var( 'product_brand' ) ) , esc_html( $term->slug ) , 1 ).'>'.esc_html( $term->name ).$count.'</option>';
					  }
					 }
					 echo '</select>';	
				}
				//list
				else
				{
					$pw_except_brand='no';$featured='no';
					if($instance['post_counts']==1)
						$pw_except_brand='yes';
					if($instance['featured']==1)
						$featured='yes';
					echo do_shortcode('[pw_brand_a-z-view
							pw_show_count=>"'.$pw_except_brand.'"
							pw_featured="'.$featured.'"]');
				}
			}
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','woocommerce-brands'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
            
		<p><label for="<?php echo $this->get_field_id('show'); ?>"><?php _e('Display Show:','woocommerce-brands'); ?></label>
            <select class='widefat' id="<?php echo $this->get_field_id('show'); ?>"
                    name="<?php echo $this->get_field_name('show'); ?>" type="text">
              <option value='dropdown' <?php selected( @$instance['show'] , "dropdown",1); ?>>
                Display DropDown
              </option>
              <option value='a-z' <?php selected( @$instance['show'] , "a-z",1); ?>>
                Display A-Z
              </option>
            </select>
        </p>

		<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('featured'); ?>" type="checkbox" value="1" <?php checked( @$instance['featured'], 1 ); ?> />
		<label for="rss-show-summary"><?php echo _e('Display Only featured?','woocommerce-brands'); ?></label></p>		
		<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('post_counts'); ?>" type="checkbox" value="1" <?php checked( @$instance['post_counts'], 1 ); ?> />
		<label for="rss-show-summary"><?php echo _e('Show post counts','woocommerce-brands'); ?></label></p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['show'] = $new_instance['show'];		
		$instance['featured']     = isset($new_instance['featured'] ) ? (int) $new_instance['featured'] : 0;
		$instance['post_counts']     = isset($new_instance['post_counts'] ) ? (int) $new_instance['post_counts'] : 0;				
		return $instance;
	}
}
register_widget( 'pw_brands_Widget' );

class pw_brands_carousel_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'pw_brands_carousel_Widget', // Base ID
			__('WooCommerce Brands Carousel', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Display a list of your Brands on your site.', 'woocommerce-brands' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['carousel_title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		if(get_option('pw_woocommerce_brands_show_categories')=="yes")
			$get_terms="product_cat";
		else
			$get_terms="product_brand";
					
		$categories = get_terms( $get_terms, 'orderby=name&hide_empty=0' );
		$carousel_type=$instance['carousel_type'];
		$carousel_align=$instance['carousel_align'];
		$count_item=$instance['carousel_count_item'];
		$item_per_view=$instance['carousel_per_view'];
		$show_title=$instance['carousel_show_title'];
		$featured=$instance['carousel_featured'];
		$show_count=$instance['carousel_show_count'];
		if($count_item=="")$count_item=5;
		if($item_per_view=="")$item_per_view=1;		
		echo do_shortcode('[pw_brands type="'.$carousel_type.'" carousel_align="'.$carousel_align.'" carousel_count_items="'.$count_item.'" carousel_item_per_view="'.$item_per_view.'" featured="'.$featured.'" show_title="'.$show_title.'" show_count="'.$show_count.'"]');

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'carousel_title' => '' ) );
		?>
		<p><label for="<?php echo $this->get_field_id('carousel_title'); ?>"><?php _e('Title:','woocommerce-brands'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('carousel_title'); ?>" name="<?php echo $this->get_field_name('carousel_title'); ?>" value="<?php if (isset ( $instance['carousel_title'])) {echo esc_attr( $instance['carousel_title'] );} ?>" /></p>
            
		<p><label for="<?php echo $this->get_field_id('carousel_type'); ?>"><?php _e('Carousel Type:','woocommerce-brands'); ?></label>
            <select class='widefat' id="<?php echo $this->get_field_id('carousel_type'); ?>"
                    name="<?php echo $this->get_field_name('carousel_type'); ?>" >
              <option value='hor-carousel' <?php selected( @$instance['carousel_type'] , "hor-carousel",1); ?>>
                Horizontal Carousel
              </option>
              <option value='ver-carousel' <?php selected( @$instance['carousel_type'] , "ver-carousel",1); ?>>
                Vertical Carousel
              </option>
            </select>
        </p>
		
        <p><label for="<?php echo $this->get_field_id('carousel_align'); ?>"><?php _e('Carousel Align:','woocommerce-brands'); ?></label>
            <select class='widefat' id="<?php echo $this->get_field_id('carousel_align'); ?>"
                    name="<?php echo $this->get_field_name('carousel_align'); ?>" >
              <option value='left' <?php selected( @$instance['carousel_align'] , "left",1); ?>>
                Left
              </option>
              <option value='center' <?php selected( @$instance['carousel_align'] , "center",1); ?>>
                Center
              </option>
              <option value='right' <?php selected( @$instance['carousel_align'] , "right",1); ?>>
                Right
              </option>
            </select>
        </p>
        
		<p><label for="rss-show-summary"><?php echo _e('Count of Items','woocommerce-brands'); ?></label>
        <input id="rss-show-summary" name="<?php echo $this->get_field_name('carousel_count_item'); ?>" type="number" value="<?php echo @$instance['carousel_count_item']; ?>" />
		</p>
        
        
        <p><label for="rss-show-summary"><?php echo _e('Item Per View','woocommerce-brands'); ?></label>
        <input id="rss-show-summary" name="<?php echo $this->get_field_name('carousel_per_view'); ?>" type="number" value="<?php echo @$instance['carousel_per_view']; ?>"/>
		</p>
       
        <p><input id="rss-show-summary" name="<?php echo $this->get_field_name('carousel_show_title'); ?>" type="checkbox" value="yes" <?php checked( @$instance['carousel_show_title'], "yes" ); ?> />
		<label for="rss-show-summary"><?php echo _e('Show Title?','woocommerce-brands'); ?></label></p>

		<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('carousel_featured'); ?>" type="checkbox" value="yes" <?php checked( @$instance['carousel_featured'], "yes" ); ?> />
		<label for="rss-show-summary"><?php echo _e('Display Only featured?','woocommerce-brands'); ?></label></p>
             
		<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('carousel_show_count'); ?>" type="checkbox" value="yes" <?php checked( @$instance['carousel_show_count'], "yes" ); ?> />
		<label for="rss-show-summary"><?php echo _e('Show post counts','woocommerce-brands'); ?></label></p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['carousel_title'] = ( ! empty( $new_instance['carousel_title'] ) ) ? strip_tags( $new_instance['carousel_title'] ) : '';
		$instance['carousel_type'] = $new_instance['carousel_type'];	
		$instance['carousel_align'] = $new_instance['carousel_align'];
		
		$instance['carousel_show_title']     = isset( $new_instance['carousel_show_title'] ) ? $new_instance['carousel_show_title'] : "no";	
		$instance['carousel_featured']     = isset( $new_instance['carousel_featured'] ) ? $new_instance['carousel_featured'] : "no";
		$instance['carousel_show_count']     = isset( $new_instance['carousel_show_count'] ) ? $new_instance['carousel_show_count'] : "no";				
		
		$instance['carousel_count_item']     =( @isset( $new_instance['carousel_count_item'] ) ? $new_instance['carousel_count_item']:"");
		
		$instance['carousel_per_view']     = @isset( $new_instance['carousel_per_view'] ) ? $new_instance['carousel_per_view']:"";

		return $instance;
	}
}
register_widget( 'pw_brands_carousel_Widget' );

function woo_brands_layered_nav_init( $filtered_posts ) {
	global $woocommerce, $_chosen_attributes;

	if ( is_active_widget( false, false, 'woocommerce_brand_nav', true ) && ! is_admin() ) {

		if ( ! empty( $_GET[ 'filter_product_brand' ] ) ) {

			$terms 	= array_map( 'intval', explode( ',', $_GET[ 'filter_product_brand' ] ) );

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

				$woocommerce->query->layered_nav_post__in = array_merge( $woocommerce->query->layered_nav_post__in, $matched_products );
				$woocommerce->query->layered_nav_post__in[] = 0;

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
add_action( 'loop_shop_post_in', 'woo_brands_layered_nav_init', 11 );

class wc_brands_brand_thumbnails extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wc_brands_brand_thumbnails', // Base ID
			__('WooCommerce Brand Thumbnails', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Show a grid of brand thumbnails.', 'woocommerce-brands' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		
		$exclude = array_map( 'intval', explode( ',', $instance['exclude'] ) );
		$order = $instance['orderby'] == 'name' ? 'asc' : 'desc';

		$brands = get_terms( 'product_brand', array( 'hide_empty' => $instance['hide_empty'], 'orderby' => $instance['orderby'], 'exclude' => $exclude, 'number' => $instance['number'], 'order' => $order ) );

		if ( ! $brands ) 
			return;

		echo $before_widget;
		
		if ( ! empty( $instance['title'] ) )
			echo $before_title . $instance['title'] . $after_title;
		$pw_woocommerc_brans_Wc_Brands = untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
		if(version_compare(WC()->version, '3.0.0', '>=')){
			$wc_get_3='wc_get_template';
		}
		else
		{
			$wc_get_3='woocommerce_get_template';
		}			
		$wc_get_3( 'brand-thumbnails.php', array(
			'brands'	=> $brands,
			'columns'	=> $instance['columns']
		), 'woocommerce-brands', $pw_woocommerc_brans_Wc_Brands. '/templates/' );
		
		echo $after_widget;	
	}

	public function form( $instance ) {
		if ( ! isset( $instance['hide_empty'] ) ) 
			$instance['hide_empty'] = 0;
		if ( ! isset( $instance['orderby'] ) ) 
			$instance['orderby'] = 'name';
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'woocommerce-brands') ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset ( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e('Columns:', 'woocommerce-brands') ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" value="<?php if ( isset ( $instance['columns'] ) ) echo esc_attr( $instance['columns'] ); else echo '1'; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Count Of Number:', 'woocommerce-brands') ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php if ( isset ( $instance['number'] ) ) echo esc_attr( $instance['number'] ); ?>" placeholder="<?php _e('All', 'woocommerce-brands'); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude:', 'woocommerce-brands') ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'exclude' ) ); ?>" value="<?php if ( isset ( $instance['exclude'] ) ) echo esc_attr( $instance['exclude'] ); ?>" placeholder="<?php _e('None', 'woocommerce-brands'); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>"><?php _e('Hide empty brands:', 'woocommerce-brands') ?></label>
				<p><input id="rss-show-summary" name="<?php echo $this->get_field_name('hide_empty'); ?>" type="checkbox" value="1" <?php checked( @$instance['hide_empty'], 1 ); ?> />				
				<label for="rss-show-summary"><?php echo _e('Hide empty brands','woocommerce-brands'); ?></label></p>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order by:', 'woocommerce-brands') ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					<option value="name" <?php selected( $instance['orderby'], 'name' ) ?>><?php _e('Name', 'woocommerce-brands') ?></option>
					<option value="count" <?php selected( $instance['orderby'], 'count' ) ?>><?php _e('Count', 'woocommerce-brands') ?></option>
				</select>
			</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['columns'] = strip_tags( stripslashes( $new_instance['columns'] ) );
		$instance['orderby'] = strip_tags( stripslashes( $new_instance['orderby'] ) );
		$instance['exclude'] = strip_tags( stripslashes( $new_instance['exclude'] ) );
		$instance['hide_empty'] = strip_tags( stripslashes( $new_instance['hide_empty'] ) );
		$instance['number'] = strip_tags( stripslashes( $new_instance['number'] ) );
		
		if ( ! $instance['columns'] )
			$instance['columns'] = 1;
			
		if ( ! $instance['orderby'] )
			$instance['orderby'] = 'name';
			
		if ( ! $instance['exclude'] )
			$instance['exclude'] = '';
			
		if ( ! $instance['hide_empty'] )
			$instance['hide_empty'] = 0;
			
		if ( ! $instance['number'] )
			$instance['number'] = '';
		
		return $instance;
	}
}
register_widget( 'wc_brands_brand_thumbnails' );

class woocommerce_brand_nav extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'woocommerce_brand_nav', // Base ID
			__('WooCommerce Brand Layered Nav', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Shows brands in a widget which lets you narrow down the list of products when viewing products.', 'woocommerce-brands' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $_chosen_attributes, $woocommerce, $_attributes_array;

		extract( $args );

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( is_array( $_attributes_array ) ? $_attributes_array : array(), array( 'product_cat', 'product_tag' ) ) ) )
			return;

		$current_term 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->term_id : '';
		$current_tax 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->taxonomy : '';

		$title 			= apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$taxonomy 		= 'product_brand';
		$display_type 	= isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

		if ( ! taxonomy_exists( $taxonomy ) )
			return;

		$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );

		if ( count( $terms ) > 0 ) {

			ob_start();

			$found = false;

			echo $before_widget . $before_title . $title . $after_title;

			if ( ! $_attributes_array || ! is_tax( $_attributes_array ) )
				if ( is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) )
					$found = true;

			if ( $display_type == 'dropdown' ) {

				if ( $current_tax && $taxonomy == $current_tax ) {

					$found = false;

				} else {

					$taxonomy_filter = $taxonomy;

					$found = true;

					echo '<select id="dropdown_layered_nav_' . $taxonomy_filter . '" name="tech" class="tech">';

					echo '<option value="">' . __( 'Any brand', 'woocommerce-brands' ) .'</option>';

					foreach ( $terms as $term ) {

						if ( $term->term_id == $current_term )
							continue;

						$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );

						if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

							$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

							set_transient( $transient_name, $_products_in_term );
						}

						$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

						$count = sizeof( array_intersect( $_products_in_term, $woocommerce->query->filtered_product_ids ) );

						if ( $count > 0 )
							$found = true;
						$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));							
						if($url!="")
							echo '<option value="url@' . esc_html( $url ). '">' . $term->name . '</option>';
						else
							echo '<option value="'. $term->term_id . '" '.selected( isset( $_GET[ 'filter_product_brand' ] ) ? $_GET[ 'filter_product_brand' ] : '' , $term->term_id, false ) . '>' . $term->name . '</option>';							
					}

					echo '</select>';

					$js = "
						jQuery(document).ready(function() {
							jQuery('.tech').msDropdown();
						});					
						jQuery('#dropdown_layered_nav_$taxonomy_filter').change(function(){
							value=jQuery('#dropdown_layered_nav_$taxonomy_filter').val();
							var val=Array();
							val=value.split('@');
							if(val[0]=='url')
								window.location= val[1];							
							else
								location.href = '" . add_query_arg('filtering', '1', remove_query_arg( 'filter_product_brand' ) ) . "&filter_product_brand=' + jQuery('#dropdown_layered_nav_$taxonomy_filter').val();								
						});
					";

					if ( function_exists( 'wc_enqueue_js' ) ) {
						wc_enqueue_js( $js );
					} else {
						$woocommerce->add_inline_js( $js );
					}

				}

			}
			else {

				echo "<ul>";

				foreach ( $terms as $term ) {

					$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );
					//print_r(get_transient( $transient_name ));
					if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

						$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

						set_transient( $transient_name, $_products_in_term );
					}

					$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

					$count = sizeof( array_intersect( $_products_in_term, $woocommerce->query->filtered_product_ids ) );

					if ( $current_term == $term->term_id )
						continue;

					if ( $count > 0 && $current_term !== $term->term_id )
						$found = true;

					if ( $count == 0 && ! $option_is_set )
						continue;

					$current_filter = ( isset( $_GET[ 'filter_product_brand' ] ) ) ? explode( ',', $_GET[ 'filter_product_brand' ] ) : array();

					if ( ! is_array( $current_filter ) )
						$current_filter = array();

					if ( ! in_array( $term->term_id, $current_filter ) )
						$current_filter[] = $term->term_id;

					if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
						$link = home_url();
					} elseif ( is_post_type_archive( 'product' ) || is_page( woocommerce_get_page_id('shop') ) ) {
						$link = get_post_type_archive_link( 'product' );
					} else {
						$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
					}

					if ( $_chosen_attributes ) {
						foreach ( $_chosen_attributes as $name => $data ) {
							if ( $name !== 'product_brand' ) {

								while ( in_array( $current_term, $data['terms'] ) ) {
									$key = array_search( $current_term, $data );
									unset( $data['terms'][$key] );
								}

								if ( ! empty( $data['terms'] ) )
									$link = add_query_arg( sanitize_title( str_replace( 'pa_', 'filter_', $name ) ), implode(',', $data['terms']), $link );
							}
						}
					}

					if ( isset( $_GET['min_price'] ) )
						$link = add_query_arg( 'min_price', $_GET['min_price'], $link );

					if ( isset( $_GET['max_price'] ) )
						$link = add_query_arg( 'max_price', $_GET['max_price'], $link );
						
					if ( isset( $_chosen_attributes['product_brand'] ) && is_array( $_chosen_attributes['product_brand']['terms'] ) && in_array( $term->term_id, $_chosen_attributes['product_brand']['terms'] ) ) {

						$class = 'class="chosen"';

						// Remove this term is $current_filter has more than 1 term filtered
						if ( sizeof( $current_filter ) > 1 ) {
							$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
							$link = add_query_arg( 'filter_product_brand', implode( ',', $current_filter_without_this ), $link );
						}

					} else {
						$class = '';
						$link = add_query_arg( 'filter_product_brand', implode( ',', $current_filter ), $link );
					}

					// Search Arg
					if ( get_search_query() )
						$link = add_query_arg( 's', get_search_query(), $link );

					// Post Type Arg
					if ( isset( $_GET['post_type'] ) )
						$link = add_query_arg( 'post_type', $_GET['post_type'], $link );

					echo '<li ' . $class . '>';


					$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));							
					
					echo ( $count > 0 || $option_is_set ) ? '<a href="' .($url=="" ? $link : $url). '">' : '<span>';

					echo $term->name;

					echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';

					echo ' <small class="count">' . $count . '</small></li>';
				}

				echo "</ul>";

			} // End display type conditional

			echo $after_widget;

			if ( ! $found )
				ob_end_clean();
			else
				echo ob_get_clean();
		}
	}

	public function form( $instance ) {
		global $woocommerce;

		if ( ! isset( $instance['display_type'] ) )
			$instance['display_type'] = 'list';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woocommerce-brands' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php _e( 'Display Type:', 'woocommerce-brands' ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
			<option value="list" <?php selected( $instance['display_type'], 'list' ); ?>><?php _e( 'List', 'woocommerce-brands' ); ?></option>
			<option value="dropdown" <?php selected( $instance['display_type'], 'dropdown' ); ?>><?php _e( 'Dropdown', 'woocommerce-brands' ); ?></option>
		</select></p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		global $woocommerce;

		if ( empty( $new_instance['title'] ) )
			$new_instance['title'] = __( 'Brands', 'woocommerce-brands' );

		$instance['title'] 			= strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['display_type'] 	= stripslashes( $new_instance['display_type'] );

		return $instance;
	}
}
register_widget( 'woocommerce_brand_nav' );
