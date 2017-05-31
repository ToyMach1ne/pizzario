<?php

if(!class_exists('pw_brand_product_list_filter'))
{
	class pw_brand_product_list_filter
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_product_list_filter_init'));
			add_shortcode('pw_product_list_filter_view',array($this,'pw_product_list_filter_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{	
		}
		function pw_product_list_filter_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("Product Grid By Brands With Filter",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_product_list_filter_view",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/product-grid-filter.png',
					"category" => __('Woo Brand', 'woocommerce-brands' ),
					"params" => array(
									
									array(
										"type" => "pw_category",
										"class" => "",
										"heading" => __("Category For Fillter",  'woocommerce-brands'  ),
										"param_name" => "pw_adv1_category",
										"description" => "",
									),
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Brands",  'woocommerce-brands'  ),
										"param_name" => "pw_brand",
										"description" => ""
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Products Image', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_image',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Desktop Columns",  'woocommerce-brands' ),
										"param_name" => "pw_columns",
										"value" => array(
											__("1 Columns" ,  'woocommerce-brands' )=>"wb-col-md-12",
											__("2 Columns" ,  'woocommerce-brands' )=>"wb-col-md-6",
											__("3 Columns" ,  'woocommerce-brands' )=>"wb-col-md-4",
											__("4 Columns" ,  'woocommerce-brands' )=>"wb-col-md-3",
											__("6 Columns" ,  'woocommerce-brands' )=>"wb-col-md-2",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Tablet Columns",  'woocommerce-brands' ),
										"param_name" => "pw_tablet_columns",
										"value" => array(
											__("1 Columns" ,  'woocommerce-brands' )=>"wb-col-sm-12",
											__("2 Columns" ,  'woocommerce-brands' )=>"wb-col-sm-6",
											__("3 Columns" ,  'woocommerce-brands' )=>"wb-col-sm-4",
											__("4 Columns" ,  'woocommerce-brands' )=>"wb-col-sm-3",
											__("6 Columns" ,  'woocommerce-brands' )=>"wb-col-sm-2",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Columns",  'woocommerce-brands' ),
										"param_name" => "pw_mobile_columns",
										"value" => array(
											__("1 Columns" ,  'woocommerce-brands' )=>"wb-col-xs-12",
											__("2 Columns" ,  'woocommerce-brands' )=>"wb-col-xs-6",
											__("3 Columns" ,  'woocommerce-brands' )=>"wb-col-xs-4",
											__("4 Columns" ,  'woocommerce-brands' )=>"wb-col-xs-3",
											__("6 Columns" ,  'woocommerce-brands' )=>"wb-col-xs-2",
											),
										"description" => "",
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Products Price', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_price',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),

								)
					) );
			}
		}
		// Shortcode handler function for  icon block
		function pw_product_list_filter_shortcode($atts)
		{
			$pw_show_image=$pw_show_price=$pw_columns=$pw_tablet_columns=$pw_mobile_columns=$pw_adv1_category=$pw_brand="";
			extract(shortcode_atts( array(
				'pw_adv1_category' => 'all',
				'pw_brand' => 'all',
				'pw_show_image' => 'no',
				'pw_columns'=>'wb-col-md-3',
				'pw_tablet_columns'=>'wb-col-sm-6',
				'pw_mobile_columns'=>'wb-col-xs-12',
				'pw_show_price' => 'no',
			),$atts));
			return do_shortcode('[pw_brand_filter_brand 
				pw_adv1_category="'.$pw_adv1_category.'"
				pw_brand="'.$pw_brand.'"
				pw_show_image="'.$pw_show_image.'" 
				pw_columns="'.$pw_columns.'"
				pw_tablet_columns="'.$pw_tablet_columns.'"
				pw_mobile_columns="'.$pw_mobile_columns.'"
				pw_show_price="'.$pw_show_price.'" 
				]
			');
		}
	}
	//instantiate the class
	new pw_brand_product_list_filter;
}



?>