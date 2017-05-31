<?php

if( ! defined('KC_FILE' ) ) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$kc = KingComposer::globe();

$shortcode_params = array();

$shortcode_params['pizzaro_banner'] = array(
	'name' => esc_html__( 'Banner', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Banner', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Banner Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'bg_choice',
			'label'			=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'image'			=> esc_html__('Image', 'pizzaro-extensions'),
				'color'			=> esc_html__('Color', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for background.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_color',
			'label'			=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
			'type'			=> 'color_picker',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'height',
			'label'			=> esc_html__('Height', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'pre_title',
			'label'			=> esc_html__('Pre Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'sub_title',
			'label'			=> esc_html__('Sub Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'description',
			'label'			=> esc_html__('Description', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'action_text',
			'label'			=> esc_html__('Action Text', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'action_link',
			'label'			=> esc_html__('Action Link', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'condition',
			'label'			=> esc_html__('Condition', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_coupon'] = array(
	'name' => esc_html__( 'Shop Coupon', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Shop Coupon', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Shop Coupon Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'coupon_code',
			'label'			=> esc_html__('Coupon Code', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_choice',
			'label'			=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'image'			=> esc_html__('Image', 'pizzaro-extensions'),
				'color'			=> esc_html__('Color', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for background.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_color',
			'label'			=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
			'type'			=> 'color_picker',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'height',
			'label'			=> esc_html__('Height', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'pre_title',
			'label'			=> esc_html__('Pre Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'sub_title',
			'label'			=> esc_html__('Sub Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'description',
			'label'			=> esc_html__('Description', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'action_text',
			'label'			=> esc_html__('Action Text', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'action_link',
			'label'			=> esc_html__('Action Link', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_events'] = array(
	'name' => esc_html__( 'Events', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Upcoming Events', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Events Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'section_title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'pre_title',
			'label'			=> esc_html__('Pre Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_features_list'] = array(
	'name' => esc_html__( 'Features List', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Features List', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Features List Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'type'			=> 'group',
			'label'			=> esc_html__( 'Features', 'pizzaro-extensions' ),
			'name'			=> 'features',
			'description'	=> '',
			'options'		=> array(
				'add_text'			=> esc_html__( 'Add new feature', 'pizzaro-extensions' )
			),
			'params' => array(
				array(
					'name'			=> 'icon',
					'label'			=> esc_html__('Icon', 'pizzaro-extensions'),
					'type'			=> 'icon_picker',
					'admin_label'	=> true
				),
				array(
					'name'			=> 'label',
					'label'			=> esc_html__('Label', 'pizzaro-extensions'),
					'type'			=> 'textarea',
					'admin_label'	=> true
				)
			)
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_menu_card'] = array(
	'name' => esc_html__( 'Menu Card', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Menu Card', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Menu Card Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'type'			=> 'text',
			'label'			=> esc_html__( 'Title', 'pizzaro-extensions' ),
			'name'			=> 'title',
			'admin_label'	=> true,
		),
		array(
			'type'			=> 'text',
			'label'			=> esc_html__( 'Pre Title', 'pizzaro-extensions' ),
			'name'			=> 'pre_title',
			'admin_label'	=> true,
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'type'			=> 'group',
			'label'			=> esc_html__( 'Menus', 'pizzaro-extensions' ),
			'name'			=> 'menus',
			'description'	=> '',
			'options'		=> array(
				'add_text'			=> esc_html__( 'Add new menu', 'pizzaro-extensions' )
			),
			'params' => array(
				array(
					'type'			=> 'text',
					'label'			=> esc_html__( 'Title', 'pizzaro-extensions' ),
					'name'			=> 'title',
					'admin_label'	=> true,
				),
				array(
					'type'			=> 'text',
					'label'			=> esc_html__( 'Price', 'pizzaro-extensions' ),
					'name'			=> 'price',
					'admin_label'	=> true,
				),
				array(
					'type'			=> 'textarea',
					'label'			=> esc_html__( 'Description', 'pizzaro-extensions' ),
					'name'			=> 'description',
					'admin_label'	=> true,
				)
			)
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_newsletter'] = array(
	'name' => esc_html__( 'Newsletter', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Newsletter', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Newsletter Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'marketing_text',
			'label'			=> esc_html__('Marketing Text', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
	),
);

$shortcode_params['pizzaro_product_categories'] = array(
	'name' => esc_html__( 'Product Categories', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Product Categories', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Product Categories Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'pre_title',
			'label'			=> esc_html__('Pre Title', 'pizzaro-extensions' ),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'section_title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions' ),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'orderby',
			'label'			=> esc_html__('Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'title',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'order',
			'label'			=> esc_html__('Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'ASC',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'limit',
			'label'			=> esc_html__('Limit', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of categories to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Hide empty?', 'pizzaro-extensions' ),
			'name'			=> 'hide_empty',
			'description'	=> esc_html__( 'Check to hide empty brands.', 'pizzaro-extensions' ),
			'options'		=> array( 'true' => esc_html__( 'Yes', 'pizzaro-extensions' ) ),
		),
		array(
			'name'			=> 'slugs',
			'label'			=> esc_html__('Slugs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter slug spearate by comma(,).', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_product'] = array(
	'name' => esc_html__( 'Product', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Product', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Product Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product ID', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter sale product id.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_products_4_1'] = array(
	'name' => esc_html__( 'Products 4-1', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Products 4-1', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Products 4-1 Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'shortcode_tag',
			'label'			=> esc_html__( 'Shortcode', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'featured_products'		=> esc_html__( 'Featured Products','pizzaro-extensions'),
				'sale_products'			=> esc_html__( 'On Sale Products','pizzaro-extensions'),
				'top_rated_products'	=> esc_html__( 'Top Rated Products','pizzaro-extensions'),
				'recent_products'		=> esc_html__( 'Recent Products','pizzaro-extensions'),
				'best_selling_products'	=> esc_html__( 'Best Selling Products','pizzaro-extensions'),
				'products'				=> esc_html__( 'Products','pizzaro-extensions'),
				'product_category'		=> esc_html__( 'Product Category','pizzaro-extensions')
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'orderby',
			'label'			=> esc_html__('Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'date',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'order',
			'label'			=> esc_html__('Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'desc',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Products Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'category',
			'label'			=> esc_html__('Category', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Product Category Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_products_card'] = array(
	'name' => esc_html__( 'Products Card', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Products Card', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Products Card Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'media_align',
			'label'			=> esc_html__( 'Media Align', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'media-left'		=> esc_html__( 'Media Left','pizzaro-extensions'),
				'media-right'		=> esc_html__( 'Media Right','pizzaro-extensions')
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'shortcode_tag',
			'label'			=> esc_html__( 'Shortcode', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'featured_products'		=> esc_html__( 'Featured Products','pizzaro-extensions'),
				'sale_products'			=> esc_html__( 'On Sale Products','pizzaro-extensions'),
				'top_rated_products'	=> esc_html__( 'Top Rated Products','pizzaro-extensions'),
				'recent_products'		=> esc_html__( 'Recent Products','pizzaro-extensions'),
				'best_selling_products'	=> esc_html__( 'Best Selling Products','pizzaro-extensions'),
				'products'				=> esc_html__( 'Products','pizzaro-extensions'),
				'product_category'		=> esc_html__( 'Product Category','pizzaro-extensions')
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'limit',
			'label'			=> esc_html__( 'Limit', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'1'		=> '1',
				'2'		=> '2',
				'4'		=> '4'
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'orderby',
			'label'			=> esc_html__('Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'date',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'order',
			'label'			=> esc_html__('Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'desc',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Products Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'category',
			'label'			=> esc_html__('Category', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Product Category Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_products_carousel_with_image'] = array(
	'name' => esc_html__( 'Products Carousel with Image', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Products Carousel with Image', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Products Carousel with Image Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'sub_title',
			'label'			=> esc_html__('Sub Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter sub title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'shortcode_tag',
			'label'			=> esc_html__( 'Shortcode', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'featured_products'		=> esc_html__( 'Featured Products','pizzaro-extensions'),
				'sale_products'			=> esc_html__( 'On Sale Products','pizzaro-extensions'),
				'top_rated_products'	=> esc_html__( 'Top Rated Products','pizzaro-extensions'),
				'recent_products'		=> esc_html__( 'Recent Products','pizzaro-extensions'),
				'best_selling_products'	=> esc_html__( 'Best Selling Products','pizzaro-extensions'),
				'products'				=> esc_html__( 'Products','pizzaro-extensions'),
				'product_category'		=> esc_html__( 'Product Category','pizzaro-extensions')
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'limit',
			'label'			=> esc_html__('Limit', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of products to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'columns',
			'label'			=> esc_html__('Columns', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of cloumns to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'orderby',
			'label'			=> esc_html__('Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'date',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'order',
			'label'			=> esc_html__('Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'desc',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Products Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'category',
			'label'			=> esc_html__('Category', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Product Category Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'cat_orderby',
			'label'			=> esc_html__('Category Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'title',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'cat_order',
			'label'			=> esc_html__('Category Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'ASC',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'cat_limit',
			'label'			=> esc_html__('Category Limit', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of categories to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Category Hide empty?', 'pizzaro-extensions' ),
			'name'			=> 'cat_hide_empty',
			'description'	=> esc_html__( 'Check to hide empty brands.', 'pizzaro-extensions' ),
			'options'		=> array( 'true' => esc_html__( 'Yes', 'pizzaro-extensions' ) ),
		),
		array(
			'name'			=> 'cat_slugs',
			'label'			=> esc_html__('Category Slugs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter slug spearate by comma(,).', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_products_sale_event'] = array(
	'name' => esc_html__( 'Products Sale Event', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Products Sale Event', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Products Sale Event Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'bg_choice',
			'label'			=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'image'			=> esc_html__('Image', 'pizzaro-extensions'),
				'color'			=> esc_html__('Color', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for background.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_color',
			'label'			=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
			'type'			=> 'color_picker',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'height',
			'label'			=> esc_html__('Height', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'pre_title',
			'label'			=> esc_html__('Pre Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'price',
			'label'			=> esc_html__('Price', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'price_info',
			'label'			=> esc_html__('Price Info', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_ids',
			'label'			=> esc_html__('Product IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'action_text',
			'label'			=> esc_html__('Action Text', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'action_link',
			'label'			=> esc_html__('Action Link', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_products_with_gallery'] = array(
	'name' => esc_html__( 'Products with Gallery', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Products with Gallery', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Products with Gallery Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'shortcode_tag',
			'label'			=> esc_html__( 'Shortcode', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'featured_products'		=> esc_html__( 'Featured Products','pizzaro-extensions'),
				'sale_products'			=> esc_html__( 'On Sale Products','pizzaro-extensions'),
				'top_rated_products'	=> esc_html__( 'Top Rated Products','pizzaro-extensions'),
				'recent_products'		=> esc_html__( 'Recent Products','pizzaro-extensions'),
				'best_selling_products'	=> esc_html__( 'Best Selling Products','pizzaro-extensions'),
				'products'				=> esc_html__( 'Products','pizzaro-extensions'),
				'product_category'		=> esc_html__( 'Product Category','pizzaro-extensions')
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'limit',
			'label'			=> esc_html__('Limit', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of products to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'columns',
			'label'			=> esc_html__('Columns', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of cloumns to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'orderby',
			'label'			=> esc_html__('Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'date',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'order',
			'label'			=> esc_html__('Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'desc',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Products Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'category',
			'label'			=> esc_html__('Category', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Product Category Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_products'] = array(
	'name' => esc_html__( 'Products', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Products', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Products Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'shortcode_tag',
			'label'			=> esc_html__( 'Shortcode', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'featured_products'		=> esc_html__( 'Featured Products','pizzaro-extensions'),
				'sale_products'			=> esc_html__( 'On Sale Products','pizzaro-extensions'),
				'top_rated_products'	=> esc_html__( 'Top Rated Products','pizzaro-extensions'),
				'recent_products'		=> esc_html__( 'Recent Products','pizzaro-extensions'),
				'best_selling_products'	=> esc_html__( 'Best Selling Products','pizzaro-extensions'),
				'products'				=> esc_html__( 'Products','pizzaro-extensions'),
				'product_category'		=> esc_html__( 'Product Category','pizzaro-extensions')
			),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'limit',
			'label'			=> esc_html__('Limit', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of products to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'columns',
			'label'			=> esc_html__('Columns', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of cloumns to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'orderby',
			'label'			=> esc_html__('Orderby', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter orderby.', 'pizzaro-extensions'),
			'value'			=> 'date',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'order',
			'label'			=> esc_html__('Order', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter order.', 'pizzaro-extensions'),
			'value'			=> 'desc',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Products Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'category',
			'label'			=> esc_html__('Category', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with Product Category Shortcode.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_recent_post'] = array(
	'name' => esc_html__( 'Recent Post', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Recent Post', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Recent Post Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'section_title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'post_choice',
			'label'			=> esc_html__( 'Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'recent'		=> esc_html__('Recent', 'pizzaro-extensions'),
				'random'		=> esc_html__('Random', 'pizzaro-extensions'),
				'specific'		=> esc_html__('Specific', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for posts.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'ids',
			'label'			=> esc_html__('IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with specific choice.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_choice',
			'label'			=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'image'			=> esc_html__('Image', 'pizzaro-extensions'),
				'color'			=> esc_html__('Color', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for background.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_color',
			'label'			=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
			'type'			=> 'color_picker',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'height',
			'label'			=> esc_html__('Height', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Show Read More', 'pizzaro-extensions' ),
			'name'			=> 'show_read_more',
			'description'	=> esc_html__( 'Check to show Read More.', 'pizzaro-extensions' ),
			'options'		=> array( 'true' => esc_html__( 'Enable', 'pizzaro-extensions' ) ),
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_recent_posts'] = array(
	'name' => esc_html__( 'Recent Posts', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Recent Posts', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Recent Posts Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'section_title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'pre_title',
			'label'			=> esc_html__('Pre Title', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'post_choice',
			'label'			=> esc_html__( 'Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'recent'		=> esc_html__('Recent', 'pizzaro-extensions'),
				'random'		=> esc_html__('Random', 'pizzaro-extensions'),
				'specific'		=> esc_html__('Specific', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for posts.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'ids',
			'label'			=> esc_html__('IDs', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter id spearate by comma(,) Note: Only works with specific choice.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'limit',
			'label'			=> esc_html__('Limit', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter the number of posts to display.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Show Read More', 'pizzaro-extensions' ),
			'name'			=> 'show_read_more',
			'description'	=> esc_html__( 'Check to show Read More.', 'pizzaro-extensions' ),
			'options'		=> array( 'true' => esc_html__( 'Enable', 'pizzaro-extensions' ) ),
		),
		array(
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Show Comment Link', 'pizzaro-extensions' ),
			'name'			=> 'show_comment_link',
			'description'	=> esc_html__( 'Check to show comment link.', 'pizzaro-extensions' ),
			'options'		=> array( 'true' => esc_html__( 'Enable', 'pizzaro-extensions' ) ),
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_sale_product'] = array(
	'name' => esc_html__( 'Sale Product', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Sale Product', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Sale Product Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'description'	=> esc_html__('Enter title.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'button_text',
			'label'			=> esc_html__('Button Text', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter text to appear on button.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'product_id',
			'label'			=> esc_html__('Product ID', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('Enter sale product id.', 'pizzaro-extensions'),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> esc_html__('Extra class name', 'pizzaro-extensions'),
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

$shortcode_params['pizzaro_store_search'] = array(
	'name' => esc_html__( 'Store Location', 'pizzaro-extensions' ),
	'description' => esc_html__( 'Store Location', 'pizzaro-extensions' ),
	'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
	'title' => esc_html__( 'Store Location Settings', 'pizzaro-extensions' ),
	'is_container' => true,
	'params' => array(
		array(
			'name'			=> 'bg_choice',
			'label'			=> esc_html__( 'Background Choice', 'pizzaro-extensions' ),
			'type'			=> 'select',
			'options'		=> array(
				'image'			=> esc_html__('Image', 'pizzaro-extensions'),
				'color'			=> esc_html__('Color', 'pizzaro-extensions')
			),
			'description'	=> esc_html__( 'Select choice for background.', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_image',
			'type'			=> 'attach_image',
			'label'			=> esc_html__( 'Background Image', 'pizzaro-extensions' ),
			'admin_label'	=> true
		),
		array(
			'name'			=> 'bg_color',
			'label'			=> esc_html__( 'Background Color', 'pizzaro-extensions' ),
			'type'			=> 'color_picker',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'height',
			'label'			=> esc_html__('Height', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'title',
			'label'			=> esc_html__('Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'sub_title',
			'label'			=> esc_html__('Sub Title', 'pizzaro-extensions'),
			'type'			=> 'textarea',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'icon_class',
			'label'			=> esc_html__('Icon Class', 'pizzaro-extensions'),
			'type'			=> 'icon_picker',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'button_text',
			'label'			=> esc_html__('Button Text', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'page_id',
			'label'			=> esc_html__('Page ID', 'pizzaro-extensions'),
			'type'			=> 'text',
			'admin_label'	=> true
		),
		array(
			'name'			=> 'el_class',
			'label'			=> 'Extra class name',
			'type'			=> 'text',
			'description'	=> esc_html__('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'pizzaro-extensions')
		)
	),
);

if ( class_exists( 'RevSlider' ) ) {
	$revsliders = array();
	
	$slider = new RevSlider();
	$arrSliders = $slider->getArrSliders();

	if ( $arrSliders ) {
		foreach ( $arrSliders as $slider ) {
			$revsliders[ $slider->getAlias() ] = $slider->getTitle();
		}
	} else {
		$revsliders[0] = esc_html__( 'No sliders found', 'pizzaro-extensions' );
	}

	$shortcode_params['rev_slider'] = array(
		'name' => esc_html__( 'Revolution Slider', 'pizzaro-extensions' ),
		'description' => esc_html__( 'Select your Revolution Slider.', 'pizzaro-extensions' ),
		'category' => esc_html__( 'Pizzaro Elements', 'pizzaro-extensions' ),
		'title' => esc_html__( 'Revolution Slider Settings', 'pizzaro-extensions' ),
		'is_container' => true,
		'params' => array(
			array(
				'name'			=> 'alias',
				'label'			=> esc_html__('Revolution Slider', 'pizzaro-extensions' ),
				'type'			=> 'select',
				'options'		=> $revsliders,
				'admin_label'	=> true
			)
		),
	);
}

$kc->add_map( $shortcode_params );