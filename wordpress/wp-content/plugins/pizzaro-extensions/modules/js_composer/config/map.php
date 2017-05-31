<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package pizzaro
 *
 */

if ( function_exists( 'vc_map' ) ) :

	#-----------------------------------------------------------------
	# Banner
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Banner', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_banner',
			'description'	=> esc_html__( 'Add Banner to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Image', 'pizzaro-extensions' )			=> 'image',
						esc_html__( 'Color', 'pizzaro-extensions' ) 		=> 'color'
					),
					'param_name'	=> 'bg_choice',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
					'param_name'	=> 'bg_color',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Height', 'pizzaro-extensions' ),
					'param_name'	=> 'height',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Sub Title', 'pizzaro-extensions' ),
					'param_name'	=> 'sub_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Description', 'pizzaro-extensions' ),
					'param_name'	=> 'description',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Text', 'pizzaro-extensions' ),
					'param_name'	=> 'action_text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Link', 'pizzaro-extensions' ),
					'param_name'	=> 'action_link',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Condition', 'pizzaro-extensions' ),
					'param_name'	=> 'condition',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Coupon
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Coupon', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_coupon',
			'description'	=> esc_html__( 'Add Coupon to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Coupon Code', 'pizzaro-extensions' ),
					'param_name'	=> 'coupon_code',
				),
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Image', 'pizzaro-extensions' )			=> 'image',
						esc_html__( 'Color', 'pizzaro-extensions' ) 		=> 'color'
					),
					'param_name'	=> 'bg_choice',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
					'param_name'	=> 'bg_color',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Height', 'pizzaro-extensions' ),
					'param_name'	=> 'height',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Sub Title', 'pizzaro-extensions' ),
					'param_name'	=> 'sub_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Description', 'pizzaro-extensions' ),
					'param_name'	=> 'description',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Text', 'pizzaro-extensions' ),
					'param_name'	=> 'action_text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Link', 'pizzaro-extensions' ),
					'param_name'	=> 'action_link',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Events
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Events', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_events',
			'description'	=> esc_html__( 'Add Events to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'section_title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Feature List
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Features List', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_features_list',
			'description'	=> esc_html__( 'Add Feature List to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type' => 'param_group',
					'value' => '',
					'param_name' => 'features',
					'params' => array(
						array(
							'type'			=> 'textfield',
							'heading'		=> esc_html__('Icon', 'pizzaro-extensions' ),
							'param_name'	=> 'icon',
						),
						array(
							'type'			=> 'textarea',
							'heading'		=> esc_html__('Label', 'pizzaro-extensions' ),
							'param_name'	=> 'label',
						)
					)
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Menu Card
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Menu Card', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_menu_card',
			'description'	=> esc_html__( 'Add Menu Card to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type' => 'param_group',
					'value' => '',
					'param_name' => 'menus',
					'params' => array(
						array(
							'type'			=> 'textfield',
							'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
							'param_name'	=> 'title',
						),
						array(
							'type'			=> 'textfield',
							'heading'		=> esc_html__('Price', 'pizzaro-extensions' ),
							'param_name'	=> 'price',
						),
						array(
							'type'			=> 'textarea',
							'heading'		=> esc_html__('Description', 'pizzaro-extensions' ),
							'param_name'	=> 'description',
						)
					)
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Newsletter
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Newsletter', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_newsletter',
			'description'	=> esc_html__( 'Add Newsletter to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Marketing Text', 'pizzaro-extensions' ),
					'param_name'	=> 'marketing_text',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Product Categories
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Product Categories', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_product_categories',
			'description'	=> esc_html__( 'Add Product Categories to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'section_title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Orderby', 'pizzaro-extensions' ),
					'param_name'	=> 'orderby',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Order', 'pizzaro-extensions' ),
					'param_name'	=> 'order',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Limit', 'pizzaro-extensions' ),
					'param_name'	=> 'limit',
				),
				array(
					'type'			=> 'checkbox',
					'param_name'	=> 'hide_empty',
					'heading'		=> esc_html__( 'Hide Empty', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Show only if brand have products', 'pizzaro-extensions' ),
					'value'			=> array(
						esc_html__( 'Allow', 'pizzaro-extensions' ) => 'true'
					)
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Slugs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Leave blank for default brands', 'pizzaro-extensions' ),
					'param_name'	=> 'slugs',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Product
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Product', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_product',
			'description'	=> esc_html__( 'Add Product to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Product ID', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Products 4-1
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name'			=> esc_html__( 'Products 4-1', 'pizzaro-extensions' ),
			'base'			=> 'pizzaro_products_4_1',
			'description'	=> esc_html__( 'Add Products 4-1 to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon'			=> '',
			'params'		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'pizzaro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'pizzaro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'pizzaro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'pizzaro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'pizzaro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'pizzaro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'pizzaro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'pizzaro-extensions' )		=> 'product_category' ,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order by', 'pizzaro-extensions' ),
					'param_name'	=> 'orderby',
					'description'	=> esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'pizzaro-extensions' ),
					'value'			=> 'date',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order', 'pizzaro-extensions' ),
					'param_name'	=> 'order',
					'description'	=> esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'pizzaro-extensions' ),
					'value'			=> 'DESC',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Product IDs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Products Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Category Slug', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Product Category Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'category',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			)
		)
	);

	#-----------------------------------------------------------------
	# Products Card
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name'			=> esc_html__( 'Products Card', 'pizzaro-extensions' ),
			'base'			=> 'pizzaro_products_card',
			'description'	=> esc_html__( 'Add Products Card to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon'			=> '',
			'params'		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'media_align',
					'value'			=> array(
						esc_html__( 'Select', 'pizzaro-extensions' ) 			=> '',
						esc_html__( 'Media Left', 'pizzaro-extensions' )		=> 'media-left',
						esc_html__( 'Media Right', 'pizzaro-extensions' )		=> 'media-right',
					),
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'image',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'pizzaro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'pizzaro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'pizzaro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'pizzaro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'pizzaro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'pizzaro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'pizzaro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'pizzaro-extensions' )		=> 'product_category' ,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of products to display', 'pizzaro-extensions' ),
					'param_name'	=> 'limit',
					'description'	=> esc_html__( ' Enter limit either 1,2 or 4.', 'pizzaro-extensions' ),
					'value'			=> '2',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order by', 'pizzaro-extensions' ),
					'param_name'	=> 'orderby',
					'description'	=> esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'pizzaro-extensions' ),
					'value'			=> 'date',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order', 'pizzaro-extensions' ),
					'param_name'	=> 'order',
					'description'	=> esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'pizzaro-extensions' ),
					'value'			=> 'DESC',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Product IDs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Products Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Category Slug', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Product Category Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'category',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			)
		)
	);

	#-----------------------------------------------------------------
	# Products Carousel with Image
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name'			=> esc_html__( 'Products Carousel with Image', 'pizzaro-extensions' ),
			'base'			=> 'pizzaro_products_carousel_with_image',
			'description'	=> esc_html__( 'Add Products Carousel with Image to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon'			=> '',
			'params'		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Sub Title', 'pizzaro-extensions' ),
					'param_name'	=> 'sub_title',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'pizzaro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'pizzaro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'pizzaro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'pizzaro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'pizzaro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'pizzaro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'pizzaro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'pizzaro-extensions' )		=> 'product_category' ,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of products to display', 'pizzaro-extensions' ),
					'param_name'	=> 'limit',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of columns to display', 'pizzaro-extensions' ),
					'param_name'	=> 'columns',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order by', 'pizzaro-extensions' ),
					'param_name'	=> 'orderby',
					'description'	=> esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'pizzaro-extensions' ),
					'value'			=> 'date',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order', 'pizzaro-extensions' ),
					'param_name'	=> 'order',
					'description'	=> esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'pizzaro-extensions' ),
					'value'			=> 'DESC',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Product IDs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Products Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Category Slug', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Product Category Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'category',
					'holder'		=> 'div'
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'image',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Category Orderby', 'pizzaro-extensions' ),
					'param_name'	=> 'cat_orderby',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Category Order', 'pizzaro-extensions' ),
					'param_name'	=> 'cat_order',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Category Limit', 'pizzaro-extensions' ),
					'param_name'	=> 'cat_limit',
				),
				array(
					'type'			=> 'checkbox',
					'param_name'	=> 'cat_hide_empty',
					'heading'		=> esc_html__( 'Category Hide Empty', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Show only if brand have products', 'pizzaro-extensions' ),
					'value'			=> array(
						esc_html__( 'Allow', 'pizzaro-extensions' ) => 'true'
					)
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Category Slugs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Leave blank for default brands', 'pizzaro-extensions' ),
					'param_name'	=> 'cat_slugs',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			)
		)
	);

	#-----------------------------------------------------------------
	# Products Sale Event
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Products Sale Event', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_products_sale_event',
			'description'	=> esc_html__( 'Add Products Sale Event to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Image', 'pizzaro-extensions' )			=> 'image',
						esc_html__( 'Color', 'pizzaro-extensions' ) 		=> 'color'
					),
					'param_name'	=> 'bg_choice',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
					'param_name'	=> 'bg_color',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Height', 'pizzaro-extensions' ),
					'param_name'	=> 'height',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Price', 'pizzaro-extensions' ),
					'param_name'	=> 'price',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Price Info', 'pizzaro-extensions' ),
					'param_name'	=> 'price_info',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Product IDs', 'pizzaro-extensions' ),
					'param_name'	=> 'product_ids',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Text', 'pizzaro-extensions' ),
					'param_name'	=> 'action_text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Link', 'pizzaro-extensions' ),
					'param_name'	=> 'action_link',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Products with Gallery
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name'			=> esc_html__( 'Products with Gallery', 'pizzaro-extensions' ),
			'base'			=> 'pizzaro_products_with_gallery',
			'description'	=> esc_html__( 'Add Products with Gallery to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon'			=> '',
			'params'		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'pizzaro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'pizzaro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'pizzaro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'pizzaro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'pizzaro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'pizzaro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'pizzaro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'pizzaro-extensions' )		=> 'product_category' ,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of products to display', 'pizzaro-extensions' ),
					'param_name'	=> 'limit',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of columns to display', 'pizzaro-extensions' ),
					'param_name'	=> 'columns',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order by', 'pizzaro-extensions' ),
					'param_name'	=> 'orderby',
					'description'	=> esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'pizzaro-extensions' ),
					'value'			=> 'date',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order', 'pizzaro-extensions' ),
					'param_name'	=> 'order',
					'description'	=> esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'pizzaro-extensions' ),
					'value'			=> 'DESC',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Product IDs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Products Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Category Slug', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Product Category Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'category',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			)
		)
	);

	#-----------------------------------------------------------------
	# Products
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name'			=> esc_html__( 'Products', 'pizzaro-extensions' ),
			'base'			=> 'pizzaro_products',
			'description'	=> esc_html__( 'Add Products to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon'			=> '',
			'params'		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'pizzaro-extensions' ) 					=> '',
						esc_html__( 'Featured Products', 'pizzaro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'pizzaro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'pizzaro-extensions' )		=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'pizzaro-extensions' )			=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'pizzaro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'pizzaro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'pizzaro-extensions' )		=> 'product_category' ,
					),
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of products to display', 'pizzaro-extensions' ),
					'param_name'	=> 'limit',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Number of columns to display', 'pizzaro-extensions' ),
					'param_name'	=> 'columns',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order by', 'pizzaro-extensions' ),
					'param_name'	=> 'orderby',
					'description'	=> esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'pizzaro-extensions' ),
					'value'			=> 'date',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Order', 'pizzaro-extensions' ),
					'param_name'	=> 'order',
					'description'	=> esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'pizzaro-extensions' ),
					'value'			=> 'DESC',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Product IDs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Products Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Enter Category Slug', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'This will only for Product Category Shortcode', 'pizzaro-extensions' ),
					'param_name'	=> 'category',
					'holder'		=> 'div'
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			)
		)
	);

	#-----------------------------------------------------------------
	# Recent Post
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Recent Post', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_recent_post',
			'description'	=> esc_html__( 'Add Recent Post to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'section_title',
				),
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Recent', 'pizzaro-extensions' )		=> 'recent',
						esc_html__( 'Random', 'pizzaro-extensions' ) 		=> 'random',
						esc_html__( 'Specific', 'pizzaro-extensions' ) 	=> 'specific'
					),
					'param_name'	=> 'post_choice',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('ID', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Only works with Specific on post choice', 'pizzaro-extensions' ),
					'param_name'	=> 'ids',
				),
				array(
					'type'			=> 'checkbox',
					'param_name'	=> 'show_read_more',
					'heading'		=> esc_html__( 'Show Read More', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Check to show Read More.', 'pizzaro-extensions' ),
					'value'			=> array(
						esc_html__( 'Enable', 'pizzaro-extensions' ) => 'true'
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Image', 'pizzaro-extensions' )			=> 'image',
						esc_html__( 'Color', 'pizzaro-extensions' ) 		=> 'color'
					),
					'param_name'	=> 'bg_choice',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
					'param_name'	=> 'bg_color',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Height', 'pizzaro-extensions' ),
					'param_name'	=> 'height',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				),
			),
		)
	);

	#-----------------------------------------------------------------
	# Recent Posts
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Recent Posts', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_recent_posts',
			'description'	=> esc_html__( 'Add Recent Posts to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'param_name'	=> 'section_title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__( 'Pre Title', 'pizzaro-extensions' ),
					'param_name'	=> 'pre_title',
				),
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Recent', 'pizzaro-extensions' )		=> 'recent',
						esc_html__( 'Random', 'pizzaro-extensions' ) 		=> 'random',
						esc_html__( 'Specific', 'pizzaro-extensions' ) 	=> 'specific'
					),
					'param_name'	=> 'post_choice',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('IDs', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Only works with Specific on post choice', 'pizzaro-extensions' ),
					'param_name'	=> 'ids',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Number of posts to show', 'pizzaro-extensions' ),
					'param_name'	=> 'limit',
				),
				array(
					'type'			=> 'checkbox',
					'param_name'	=> 'show_read_more',
					'heading'		=> esc_html__( 'Show Read More', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Check to show Read More.', 'pizzaro-extensions' ),
					'value'			=> array(
						esc_html__( 'Enable', 'pizzaro-extensions' ) => 'true'
					)
				),
				array(
					'type'			=> 'checkbox',
					'param_name'	=> 'show_comment_link',
					'heading'		=> esc_html__( 'Show Comment Link', 'pizzaro-extensions' ),
					'description'	=> esc_html__( 'Check to show comment link.', 'pizzaro-extensions' ),
					'value'			=> array(
						esc_html__( 'Enable', 'pizzaro-extensions' ) => 'true'
					)
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				),
			),
		)
	);

	#-----------------------------------------------------------------
	# Sale Product
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Sale Product', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_sale_product',
			'description'	=> esc_html__( 'Add Sale Product to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Button Text', 'pizzaro-extensions' ),
					'param_name'	=> 'button_text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Product ID', 'pizzaro-extensions' ),
					'param_name'	=> 'product_id',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Store Location
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Store Location', 'pizzaro-extensions' ),
			'base'  		=> 'pizzaro_store_search',
			'description'	=> esc_html__( 'Add Store Location to your page.', 'pizzaro-extensions' ),
			'category'		=> esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Choose', 'pizzaro-extensions' )		=> '',
						esc_html__( 'Image', 'pizzaro-extensions' )			=> 'image',
						esc_html__( 'Color', 'pizzaro-extensions' ) 		=> 'color'
					),
					'param_name'	=> 'bg_choice',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
					'param_name' 	=> 'bg_image',
				),
				array(
					'type'			=> 'colorpicker',
					'heading'		=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
					'param_name'	=> 'bg_color',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Height', 'pizzaro-extensions' ),
					'param_name'	=> 'height',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Title', 'pizzaro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Sub Title', 'pizzaro-extensions' ),
					'param_name'	=> 'sub_title',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Icon Class', 'pizzaro-extensions' ),
					'param_name'	=> 'icon_class',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Button Text', 'pizzaro-extensions' ),
					'param_name'	=> 'button_text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Page ID', 'pizzaro-extensions' ),
					'param_name'	=> 'page_id',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Extra Class', 'pizzaro-extensions' ),
					'param_name'	=> 'el_class',
				)
			),
		)
	);

	#-----------------------------------------------------------------
	# Terms
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'        => esc_html__( 'Terms', 'pizzaro-extensions' ),
			'base'        => 'pizzaro_terms',
			'description' => esc_html__( 'Adds a shortcode for get_terms. Used to get terms including categories, product categories, etc.', 'pizzaro-extensions' ),
			'class'		  => '',
			'controls'    => 'full',
			'icon'        => '',
			'category'    => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
			'params'      => array(
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Taxonomy', 'pizzaro-extensions' ),
					'param_name'   => 'taxonomy',
					'description'  => esc_html( 'Taxonomy name, or comma-separated taxonomies, to which results should be limited.', 'pizzaro-extensions' ),
					'value'        => 'category',
					'holder'       => 'div'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Order By', 'pizzaro-extensions' ),
					'param_name'   => 'orderby',
					'description'  => esc_html( 'Field(s) to order terms by. Accepts term fields (\'name\', \'slug\', \'term_group\', \'term_id\', \'id\', \'description\'). Defaults to \'name\'.', 'pizzaro-extensions' ),
					'value'        => 'name'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Order', 'pizzaro-extensions' ),
					'param_name'   => 'order',
					'description'  => esc_html( 'Whether to order terms in ascending or descending order. Accepts \'ASC\' (ascending) or \'DESC\' (descending). Default \'ASC\'.', 'pizzaro-extensions' ),
					'value'        => 'ASC'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Hide Empty ?', 'pizzaro-extensions' ),
					'param_name'   => 'hide_empty',
					'description'  => esc_html( 'Whether to hide terms not assigned to any posts. Accepts 1 or 0. Default 0.', 'pizzaro-extensions' ),
					'value'        => '0'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Include IDs', 'pizzaro-extensions' ),
					'param_name'   => 'include',
					'description'  => esc_html( 'Comma-separated string of term ids to include.', 'pizzaro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Exclude IDs', 'pizzaro-extensions' ),
					'param_name'   => 'exclude',
					'description'  => esc_html( 'Comma-separated string of term ids to exclude. If Include is non-empty, Exclude is ignored.', 'pizzaro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Number', 'pizzaro-extensions' ),
					'param_name'   => 'number',
					'description'  => esc_html( 'Maximum number of terms to return. Accepts 0 (all) or any positive number. Default 0 (all).', 'pizzaro-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Offset', 'pizzaro-extensions' ),
					'param_name'   => 'offset',
					'description'  => esc_html( 'The number by which to offset the terms query.', 'pizzaro-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Name', 'pizzaro-extensions' ),
					'param_name'   => 'name',
					'description'  => esc_html( 'Name or comma-separated string of names to return term(s) for.', 'pizzaro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Slug', 'pizzaro-extensions' ),
					'param_name'   => 'slug',
					'description'  => esc_html( 'Slug or comma-separated string of slugs to return term(s) for.', 'pizzaro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Hierarchical', 'pizzaro-extensions' ),
					'param_name'   => 'hierarchical',
					'description'  => esc_html( 'Whether to include terms that have non-empty descendants. Accepts 1 (true) or 0 (false). Default 1 (true)', 'pizzaro-extensions' ),
					'value'        => '1',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Child Of', 'pizzaro-extensions' ),
					'param_name'   => 'child_of',
					'description'  => esc_html( 'Term ID to retrieve child terms of. If multiple taxonomies are passed, child_of is ignored. Default 0.', 'pizzaro-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Parent', 'pizzaro-extensions' ),
					'param_name'   => 'parent',
					'description'  => esc_html( 'Parent term ID to retrieve direct-child terms of.', 'pizzaro-extensions' ),
					'value'        => '',
				)	
			)
		)
	);

endif;