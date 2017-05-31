<?php

if(!class_exists('pw_brand_VC_a_z_view'))
{
	class pw_brand_VC_a_z_view
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_a_z_view_init'));
			add_shortcode('pw_brand_vc_az_view',array($this,'pw_a_z_view_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{	
		}
		function pw_a_z_view_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("A-Z View",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_brand_vc_az_view",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/azfilter.png',
					"category" => __('Woo Brand', 'woocommerce-brands'),
					"params" => array(
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Style",  'woocommerce-brands' ),
										"param_name" => "pw_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-filter-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-filter-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-filter-style3",
											__("Style 4" ,  'woocommerce-brands' )=>"wb-filter-style4",
											__("Style 5" ,  'woocommerce-brands' )=>"wb-filter-style5",
											__("Style 6" ,  'woocommerce-brands' )=>"wb-filter-style6",
											__("Style 7" ,  'woocommerce-brands' )=>"wb-filter-style7",
											__("Style 8" ,  'woocommerce-brands' )=>"wb-filter-style8",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Brand List Style",  'woocommerce-brands' ),
										"param_name" => "pw_brand_list_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-brandlist-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-brandlist-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-brandlist-style3",
											),
										"description" => "",
									),
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Except Brand(s)",  'woocommerce-brands'  ),
										"param_name" => "pw_except_brand",
										"description" => ""
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Display Only Featured Brands','woocommerce-brands'),
										'param_name' => 'pw_featured',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Display Count Of Brand','woocommerce-brands'),
										'param_name' => 'pw_show_count',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Hide Empty Brands','woocommerce-brands'),
										'param_name' => 'pw_hide_empty_brands',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => '1' ),
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Scroll Height", 'woocommerce-brands' ),
										"param_name" => "pw_scroll_height",
										"value" => '',
										"description" => "",
									),
								)
					) );
			}
		}
		// Shortcode handler function for  icon block
		function pw_a_z_view_shortcode($atts)
		{
			$pw_except_brand=$pw_style=$pw_brand_list_style=$pw_hide_empty_brands=$pw_show_count=$pw_featured=$pw_scroll_height="";
			extract(shortcode_atts( array(
				'pw_style' => 'wb-filter-style1',
				'pw_brand_list_style' => 'wb-brandlist-style1',
				'pw_except_brand' => '',
				'pw_show_count'=>'',
				'pw_featured'=>'no',
				'pw_hide_empty_brands'=>'0',
				'pw_scroll_height'=>'200',
			),$atts));
			return do_shortcode('[pw_brand_a-z-view 
				pw_style="'.$pw_style.'"
				pw_brand_list_style="'.$pw_brand_list_style.'"
				pw_except_brand="'.$pw_except_brand.'" 
				pw_show_count="'.$pw_show_count.'" 
				pw_featured="'.($pw_featured!="" ? $pw_featured : "no").'" 
				pw_hide_empty_brands="'.$pw_hide_empty_brands.'" 
				pw_scroll_height="'.$pw_scroll_height.'"]'
			);
		}
	}
	//instantiate the class
	new pw_brand_VC_a_z_view;
}
?>