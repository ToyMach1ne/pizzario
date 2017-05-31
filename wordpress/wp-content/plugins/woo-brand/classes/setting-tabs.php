<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class pw_woocommerc_brans_WC_Admin_Tabs {

	public $tab; 
	public $options; 
	
	/**
	 * Constructor
	 */
	public function __construct() {
/*	*/
		$this->options = $this->pw_woocommerce_brands_plugin_options();
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'pw_woocommerce_brands_add_tab_woocommerce' ) );
		
		add_filter( 'woocommerce_page_settings', array( $this, 'pw_woocommerce_brands_add_page_setting_woocommerce' ) );
		add_action( 'woocommerce_update_options_pw_woocommerce_brands', array( $this, 'pw_woocommerce_brands_update_options' ) );
		add_action( 'woocommerce_admin_field_upload', array( $this, 'admin_fields_upload' ) );
		add_action( 'woocommerce_admin_field_addons', array( $this, 'admin_fields_addons' ) );
		add_action( 'woocommerce_admin_field_size', array( $this, 'admin_fields_size' ) );
		
		// save custom field types
		add_action( 'init', array( $this, 'save_custom_field_types' ) );		
		
		//recunt
		add_action( 'woocommerce_admin_field_recount', array( $this, 'admin_fields_recount' ) );
		//
		add_action( 'woocommerce_settings_tabs_pw_woocommerce_brands', array( $this, 'pw_woocommerce_brands_print_plugin_options' ) );
		
		add_action( 'admin_init', array( $this, 'settings_init_brand' ) );
		add_action( 'admin_init', array( $this, 'settings_save_brand' ) );		
		
		// Add brands filtering to the coupon creation screens.
		add_action( 'woocommerce_coupon_options_usage_restriction', array( $this, 'add_coupon_brands_fields' ) );
	}

	public function add_coupon_brands_fields () {
		global $post;
		// Brands
		if(!defined('plugin_dir_url_pw_woo_brand_coupon'))
		{
			?>
			<p class="form-field"><label for="product_brands"><?php _e( 'Product brands', 'woocommerce-brands' ); ?></label>
			<?php _e('Please BUY/activated woocommerce brand coupon Add-ons', 'woocommerce-brands'); ?> <a href="http://proword.net/product/brand-coupon-add-on/" >Click for Buy</a> <img class="help_tip" data-tip='<?php _e( 'A product must be associated with this brand for the coupon to remain valid or, for "Product Discounts", products with these brands will be discounted.', 'woocommerce-brands' ); ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></p>
			<?php

			// Exclude Brands
			?>
			<p class="form-field"><label for="exclude_product_brands"><?php _e( 'Exclude brands', 'woocommerce-brands' ); ?></label>
			<?php _e('Please BUY/activated woocommerce brand coupon Add-ons', 'woocommerce-brands'); ?> <a href="http://proword.net/product/brand-coupon-add-on/" >Click for Buy</a>
			<img class="help_tip" data-tip='<?php _e( 'Product must not be associated with these brands for the coupon to remain valid or, for "Product Discounts", products associated with these brands will not be discounted.', 'woocommerce-brands' ) ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></p>
			<?php
		}
	} // End add_coupon_brands_fields()
		
	public function save_custom_field_types() {
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.4.0', '>=' ) ) {
			add_filter( 'woocommerce_admin_settings_sanitize_option_pw_woocommerce_image_brand_shop_page_image_size', array( $this, 'save_size_field' ), 10, 3 );			
			add_filter( 'woocommerce_admin_settings_sanitize_option_pw_woocommerce_brands_image_single_image_size', array( $this, 'save_size_field' ), 10, 3 );			
			add_filter( 'woocommerce_admin_settings_sanitize_option_pw_woocommerce_brands_image_list_image_size', array( $this, 'save_size_field' ), 10, 3 );			
		} else {
			add_action( 'woocommerce_update_option_size', array( $this, '_deprecated_save_size_field' ) );			
		}
	}

	public function _deprecated_save_size_field( $option ) {
		$value = $this->save_size_field( null, $option, null );
		update_option( $option['id'], $value );
	}
	
	public function save_size_field( $value, $option, $raw_value ) {
			
		if ( isset( $_POST[ $option['id'] . '_width' ] ) && ! empty( $_POST[ $option['id'] . '_height' ] ) )
			return wc_clean( $_POST[ $option['id'] . '_width' ] ) . ':' . wc_clean( $_POST[ $option['id'] . '_height' ] );
	}
	
	function pw_woocommerce_brands_add_tab_woocommerce($tabs){
		$tabs['pw_woocommerce_brands'] = __('Brands','woocommerce-brands'); // or whatever you fancy
		return $tabs;
	}
	
	public function settings_init_brand() {
		add_settings_field(
			'woocommerce_product_brand_slug',      	// id
			__( 'Product Brand base', 'woocommerce' ), 	// setting title
			array( $this, 'product_brand_slug_input' ),  // display callback
			'permalink',                 				// settings page
			'optional'                  				// settings section
		);
	}
	public function product_brand_slug_input() {
		$perm = get_option( 'pw_woocommerce_brands_base' );
		//echo $perm;
		?>
		<input name="woocommerce_product_brand_slug" type="text" class="regular-text code" value="<?php if ( isset( $perm ) ) echo esc_attr( $perm ); ?>" placeholder="<?php echo _x('product-brand', 'slug', 'woocommerce') ?>" />
		<?php
	}	

	public function settings_save_brand() {
		if ( ! is_admin() )
			return;
					// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST['woocommerce_product_brand_slug'] )) {
			$perm 	= untrailingslashit( $_POST['woocommerce_product_brand_slug'] );	
			update_option( 'pw_woocommerce_brands_base', $perm );	
		}	
	}
	
	
	/**
	 * Update plugin options.
	 * 
	 * @return void
	 * @since 1.0.0
	 */
	public function pw_woocommerce_brands_update_options() {
	global $wp_rewrite;
		foreach( $this->options as $option ) {
			woocommerce_update_options( $option );   
		}
		
	   	$wp_rewrite->flush_rules();		
	}
	
	/**
	 * Add the select for the Woocommerce Brands page in WooCommerce > Settings > Pages
	 * 
	 * @param array $settings
	 * @return array
	 * @since 1.0.0
	 */
	public function pw_woocommerce_brands_add_page_setting_woocommerce( $settings ) {
		unset( $settings[count( $settings ) - 1] );
		
		$settings[] = array(
			'name' => __( 'Wishlist Page', 'woocommerce-brands' ),
			'desc' 		=> __( 'Page contents: [pw_woocommerce_brands]', 'woocommerce-brands' ),
			'id' 		=> 'pw_woocommerce_brands_page_id',
			'type' 		=> 'single_select_page',
			'std' 		=> '',         // for woocommerce < 2.0
			'default' 	=> '',         // for woocommerce >= 2.0
			'class'		=> 'chosen_select_nostd',
			'css' 		=> 'min-width:300px;',
			'desc_tip'	=>  false,
		);
		
		$settings[] = array( 'type' => 'sectionend', 'id' => 'page_options');
		
		return $settings;
	}

	
	
	
	public function pw_woocommerce_brands_print_plugin_options() {

		?>
		<div class="subsubsub_section">
			<br class="clear" />
			<?php foreach( $this->options as $id => $tab ) : ?>
			<div class="section" id="pw_woocommerce_brands_<?php echo $id ?>">
				<?php woocommerce_admin_fields( $this->options[$id] ) ;?>

			</div>
			<?php endforeach;?>

		</div>
		<?php
	}
	
	private function pw_woocommerce_brands_plugin_options() {
		$options['general_settings'] = array(
			array( 'name' => __( 'General Settings', 'woocommerce-brands' ), 'type' => 'title', 'desc' => '', 'id' => 'pw_woocommerce_brands_general_settings' ),
			array(
				'title' => __( 'Customize "Brand"', 'woocommerce-brands' ),
				'desc' 		=> __( 'Change "Brand" Text', 'woocommerce-brands' ),
				'id' 		=> 'pw_woocommerce_brands_text',
				'css' 		=> 'width:150px;',
				'default'	=> 'Brands',
				'type' 		=> 'text',
				'desc_tip'	=>  true,
			),		
			array(
				'name'      => __( 'Single product brand position', 'woocommerce-brands' , 'woocommerce-brands'),
				'desc'      => __( 'Position for brand list', 'woocommerce-brands' ),
				'id'        => 'pw_woocommerce_brands_position_single_brand',
				'type'      => 'select',
				'class'		=> 'chosen_select',
				'css' 		=> 'min-width:300px;',
				'options'   => array(
					'default' => __( 'Default', 'woocommerce-brands' ),
					'1' => __( 'Above Product Title', 'woocommerce-brands' ),
					'2' => __( 'Below Product Title', 'woocommerce-brands' ),
					'3' => __( 'After product price', 'woocommerce-brands' ),
					'4' => __( 'After product excerpt', 'woocommerce-brands' ),
					'5' => __( 'After single Add to Cart', 'woocommerce-brands' ),
					'6' => __( 'After product meta', 'woocommerce-brands' ),
					'7' => __( 'After product share', 'woocommerce-brands' ),
				),
				'desc_tip'	=>  true
			),
			array(
				'name'      => __( 'Product Listing brand position', 'woocommerce-brands' , 'woocommerce-brands'),
				'desc'      => __( 'Position for brand in Product Listing', 'woocommerce-brands' ),
				'id'        => 'pw_woocommerce_brands_position_product_list',
				'type'      => 'select',
				'class'		=> 'chosen_select',
				'css' 		=> 'min-width:300px;',
				'options'   => array(
					'default' => __( 'Default', 'woocommerce-brands' ),
					'before_price' => __( 'Before price', 'woocommerce-brands' ),
					'before_title' => __( 'Before Title', 'woocommerce-brands' ),
					'after_title' => __( 'After Title', 'woocommerce-brands' ),
					'before_addcart' => __( 'Before Add to Cart', 'woocommerce-brands' ),
					'after_addcart' => __( 'After Add to Cart', 'woocommerce-brands' ),
				),
				'desc_tip'	=>  true
			),			
			/*array(
				'title' => __( 'Display From', 'woocommerce-brands' ),
				'id' 		=> 'pw_woocommerce_brands_show_categories',
				'default'	=> 'no',
				'type' 		=> 'radio',
				'desc_tip'	=>  __( 'This option is show Categories or Brands.', 'woocommerce-brands' ),
				'options'	=> array(
					'no' => __( 'Brands', 'woocommerce-brands' ),
					'yes' => __( 'Categories', 'woocommerce-brands' )
				),
			),*/
			array( 'type' => 'sectionend', 'id' => 'pw_woocommerce_brands_general_settings' )
		);
		$options['sticky_settings'] = array(
			array( 'name' => __( 'Extra Button Settings', 'woocommerce-brands' ), 'type' => 'title', 'desc' => '', 'id' => 'pw_woocommerce_brands_sticky_settings' ),
/*			array(
				'name'      => __( 'Display Brands Extra Button', 'woocommerce-brands' ),
				'desc'      => __( 'Display Brands Extra Button.', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_display_extra',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'yes',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			*/
			array(
				'name'      => __( 'Position Extra Button', 'woocommerce-brands' , 'woocommerce-brands'),
				'desc'      => __( 'You Can Choose Extra Button Position.(Left/Right)', 'woocommerce-brands' ),
				'id'        => 'pw_woocommerce_brands_position_extra',
				'type'      => 'select',
				'class'		=> 'chosen_select',
				'css' 		=> 'min-width:300px;',
				'options'   => array(
					'left' => __( 'Left', 'woocommerce-brands' ),
					'right' => __( 'Right', 'woocommerce-brands' ),
				),
				'desc_tip'	=>  true
			),
			array(
				'name'      => __( 'Style Extra Button', 'woocommerce-brands' , 'woocommerce-brands'),
				'desc'      => __( 'You Can Choose Extra Button Style', 'woocommerce-brands' ),
				'id'        => 'pw_woocommerce_brands_style_extra',
				'type'      => 'select',
				'class'		=> 'chosen_select',
				'css' 		=> 'min-width:300px;',
				'options'   => array(
					'wb-filter-style1' => __( 'Style 1', 'woocommerce-brands' ),
					'wb-filter-style2' => __( 'Style 2', 'woocommerce-brands' ),
					'wb-filter-style3' => __( 'Style 3', 'woocommerce-brands' ),
				),
				'desc_tip'	=>  true
			),
			array(
				'name'      => __( 'show Extra Button in', 'woocommerce-brands' , 'woocommerce-brands'),
				'desc'      => __( 'only show Extra Button in These pages', 'woocommerce-brands' ),
				'id'        => 'pw_woocommerce_brands_show_pages_extra',
				'type'      => 'multiselect',
				'class'		=> 'chosen_select',
				'css' 		=> 'min-width:300px;',
				'options'   => array(
					'none' => __( 'None Pages', 'woocommerce-brands' ),
					'all' => __( 'All pages', 'woocommerce-brands' ),
					'shop' => __( 'Product/shop Pages', 'woocommerce-brands' ),
					//'single' => __( 'Single Product', 'woocommerce-brands' ),
					//'brand' => __( 'Brand List', 'woocommerce-brands' ),
				),
				'desc_tip'	=>  true
			),			
			array( 'type' => 'sectionend', 'id' => 'pw_woocommerce_brands_sticky_settings' )
		);
	
	$options['brands_settings'] = array(
			array( 'name' => __( 'Brand`s Image&Text Setting', 'woocommerce-brands' ), 'type' => 'title', 'desc' => '', 'id' => 'pw_woocommerce_brands_image_settings' ),

		/*	array(
				'name'      =>  '' ,
				'desc'      => __( 'Display Brand`s In Producut`s Page(shop page)', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_shop_page',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'no',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
		*/
			array(
				'name'      => '',
				'desc'      => __( 'Display Brand Title In Product Listing', 'woocommerce-brands'), 
				'id'        => 'pw_wooccommerce_display_brand_in_product_shop',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'yes',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			array(
				'name'      =>  '' ,
				'desc'      => __( 'Display Brand`s Description In Single Producut', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_desc_single',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'no',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			array(
				'name'      => '',
				'desc'      => __( 'Display Brand`s Description In Producut List', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_desc_list',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'no',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			array(
				'name'      => '',
				'desc'      => __( 'Display Brand`s Text In Single Producut', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_text_single',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'yes',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			array(
				'name'      => '',
				'desc'      => __( 'Display Brand logo In Product Listing', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_image_brand_shop_page',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'no',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			array(
				'name'      => __( 'Image Size', 'woocommerce-brands' ),
				'desc'      => __( 'This size is usually used in product listings', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_image_brand_shop_page_image_size',
				'std' 		=> '',         // for woocommerce < 2.0
				'default' 	=> '150:150',         // for woocommerce >= 2.0
				'type'      => 'size'
			),
			array(
				'name'      => '',
				'desc'      => __( 'Display Brand`s Image In Single Producut', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_image_single',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'yes',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),
			array(
				'name'      => __( 'Image Size', 'woocommerce-brands' ),
				'desc'      => __( 'This size is usually used in product listings', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_image_single_image_size',
				'std' 		=> '',         // for woocommerce < 2.0
				'default' 	=> '1:1',         // for woocommerce >= 2.0
				'type'      => 'size'
			),			
			array(
				'name'      => '',
				'desc'      => __( 'Display Brand`s Image In Brand Archive Page', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_image_list',
				'std' 		=> 'yes',         // for woocommerce < 2.0
				'default' 	=> 'yes',         // for woocommerce >= 2.0
				'type'      => 'checkbox'
			),	
			
			array(
				'name'      => __( 'Image Size', 'woocommerce-brands' ),
				'desc'      => __( 'This size is usually used in product listings', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_image_list_image_size',
				'std' 		=> '',         // for woocommerce < 2.0
				'default' 	=> '1:1',         // for woocommerce >= 2.0
				'type'      => 'size'
			),
			
			array(
				'name'      => __( 'Default Image', 'woocommerce-brands' ),
				'desc'      => __( 'Add Default Image', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_default_image',
				'std' 		=> '',         // for woocommerce < 2.0
				'default' 	=> '',         // for woocommerce >= 2.0
				'type'      => 'upload'
			),
			array(
				'name'      => __( 'Term counts', 'woocommerce-brands' ),
				'desc'      => __( '	This tool will recount product terms - useful when changing your settings in a way which hides products from the catalog.', 'woocommerce-brands'), 
				'id'        => 'pw_woocommerce_brands_recount',
				'std' 		=> '',         // for woocommerce < 2.0
				'default' 	=> '',         // for woocommerce >= 2.0
				'type'      => 'recount'
			),
			array(
				'name'      => __( 'Brand`s Add-ons', 'woocommerce-brands' ),
				'desc'      => '', 
				'id'        => 'pw_woocommerce_brands_addons',
				'std' 		=> '',         // for woocommerce < 2.0
				'default' 	=> '',         // for woocommerce >= 2.0
				'type'      => 'addons'
			),
			array( 'type' => 'sectionend', 'id' => 'pw_woocommerce_brands_image_settings' ),

			array( 'type' => 'sectionend', 'id' => 'pw_woocommerce_brands_position_settings' ),				
			array( 'type' => 'sectionend', 'id' => 'pw_woocommerce_brands_position_settings' ),				
		);
		
		return apply_filters( 'pw_woocommerce_brands_tab_options', $options );
	}
	



	/**
	 * Create new Woocommerce admin field: slider
	 * 
	 * @access public
	 * @param array $value
	 * @return void 
	 * @since 1.0.0
	 */
	public function admin_fields_size( $value ) {
		//if ( isset( $field['name'] ) && isset( $value['id'] ) ) :
			$width='150';
			$height='150';
			$ratio = get_option( $value['id'], $value['default'] );
			if($ratio!="")
				list( $width, $height ) = explode( ':', $ratio );								
			
			?><tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $value['id'] ); ?>"></label>
				</th>

				<td class="forminp image_width_settings no-pdding-top" ><?php echo $value['name']; ?>:
					<input  name="<?php echo esc_attr( $value['id'] . '_width' ); ?>" id="<?php echo esc_attr( $value['id'] . '_width' ); ?>" value="<?php echo esc_attr( $width ); ?>" type="text" style="max-width: 75px;" size="3" > × <input name="<?php echo esc_attr( $value['id'] . '_height' ); ?>" id="<?php echo esc_attr( $value['id'] . '_height' ); ?>" size="3" value="<?php echo esc_attr( $height ); ?>" type="text" style="max-width: 75px;" >px
				</td>


			</tr>
			<?php 
		//endif;			
	}
	
	/**
	 * Create new Woocommerce admin field: slider
	 * 
	 * @access public
	 * @param array $value
	 * @return void 
	 * @since 1.0.0
	 */
	public function admin_fields_upload( $value ) {
			$upload_value = ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) ? 
								esc_attr( stripslashes( get_option($value['id'] ) ) ) :
								esc_attr( $value['std'] );
								
			?><tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
				</th>
				<td class="forminp">
					<div class="form-field">
                        <div id="brands_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo ( $upload_value!='' ? wp_get_attachment_thumb_url( $upload_value ) : wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
                        <div style="line-height:60px;">
                            <input type="hidden" id="<?php echo esc_attr( $value['id'] ); ?>" name="<?php echo esc_attr( $value['id'] ); ?>" value="<?php echo $upload_value; ?>" />
                            <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce-brands' ); ?></button>
                            <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce-brands' ); ?></button>
                        </div>
                        
                        <div class="clear"></div>
                    </div>	
					<?php echo $value['desc']; ?>
                </td>
			</tr>
			

			
			<script type="text/javascript">
				 // Only show the "remove image" button when needed
				 if ( ! jQuery('#pw_woocommerce_brands_default_image').val() )
					 jQuery('.remove_image_button').hide();
	
				// Uploading files
				var file_frame;
	
				jQuery(document).on( 'click', '.upload_image_button', function( event ){
					
	
					event.preventDefault();
	
					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}
	
					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'woocommerce-brands' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'woocommerce-brands' ); ?>',
						},
						multiple: false
					});
	
					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();
	
						jQuery('#pw_woocommerce_brands_default_image').val( attachment.id );
						jQuery('#brands_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});
	
					// Finally, open the modal.
					file_frame.open();
				});
	
				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#brands_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
					jQuery('#pw_woocommerce_brands_default_image').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});
	
			</script>
			
			<?php
	}

	public function admin_fields_addons( $value ) {
		if(!defined('plugin_dir_url_pw_woo_brand_coupon'))
		{
			?>
			<tr>
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
				</th>
				<td class="forminp">
					<div class="form-field">
                        <div >
							<a href="#" id="<?php echo esc_attr( $value['id'] ); ?>"><?php _e('Show/Hide Add-ons') ?> </a>

                        </div>
						<?php echo $value['desc'];?>
                        <div class="clear"></div>
						<div id="pw_brand_addons" style="display:none">
							<?php
								include("addons.php");
							?>
						</div>
                    </div>	
					
                </td>
			</tr>
			<script type="text/javascript">
				jQuery(document).ready(function(e) {
					jQuery('#<?php echo esc_attr( $value['id'] ); ?>').click(function(e){
						e.preventDefault();
						jQuery("#pw_brand_addons").toggle();
					});
				});
			</script>		
			<?php
		}
	}	
	
	public function admin_fields_recount( $value ) {
			?>
			<tr>
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
				</th>
				<td class="forminp">
					<div class="form-field">
                        <div >
                            <button type="button" id="recount_btn" class="recount_btn button"><?php _e( 'Recount terms', 'woocommerce-brands' ); ?></button>
							<label id="msg_recount"></label>
                        </div>
						<?php echo $value['desc'];?>
                        <div class="clear"></div>
                    </div>	
					
                </td>
			</tr>
			<script type="text/javascript">
				jQuery(document).ready(function(e) {
					jQuery('#recount_btn').click(function(){
						//confirm('d');
						jQuery("#msg_recount").html('.......');
						//jQuery('#recount_btn').val('<?php _e('Loading...','pw_wc_flash_sale');?>');
						jQuery.ajax ({
							type: "POST",
							url: ajaxurl,
							data:   jQuery('#recount_btn').serialize()+ "&action=pw_recount_brand",
							
							success: function(data) {
								jQuery("#msg_recount").html('Terms successfully recounted');
								//confirm('Terms successfully recounted');
							}
						});	
					});
				});
			</script>		

			<?php
	}	
	
	
	/**
	* Save the admin field: slider
	*
	* @access public
	* @param mixed $value
	* @return void
	* @since 1.0.0
	*/
	public function admin_update_option($value) {
		update_option( $value['id'], woocommerce_clean($_POST[$value['id']]) );		
	}

	
}
new pw_woocommerc_brans_WC_Admin_Tabs();
?>