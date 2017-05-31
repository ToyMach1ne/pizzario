<?php

if(!class_exists('pw_brand_VC_all_view'))
{
	class pw_brand_VC_all_view
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_all_view_init'));
			add_shortcode('pw_all_vc_view',array($this,'pw_all_view_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{	
		}
		function pw_all_view_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("Brands List",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_all_vc_view",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/list.png',
					"category" => __('Woo Brand', 'woocommerce-brands' ),
					"params" => array(
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Layout",  'woocommerce-brands' ),
										"param_name" => "type",
										"value" => array(
											__("Simple Layout" ,  'woocommerce-brands' )=>"simple",
											__("Advanced Layout 1" ,  'woocommerce-brands' )=>"adv1",
											__("Advanced Layout 2(MultiSelect)" ,  'woocommerce-brands' )=>"adv2",
											),
										"description" => "",
									),
									array(
										"type" => "pw_category",
										"class" => "",
										"heading" => __("Category For Fillter",  'woocommerce-brands'  ),
										"param_name" => "pw_adv1_category",
										"description" => "",
										'dependency' => array(
											'element' => 'type',
											'value' => array( 'adv1' ))
									),
									array(
										"type" => "pw_category",
										"class" => "",
										"heading" => __("Category For Fillter",  'woocommerce-brands'  ),
										"param_name" => "pw_adv2_category",
										"description" => "",
										'dependency' => array(
											'element' => 'type',
											'value' => array( 'adv2' ))
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Filter style",  'woocommerce-brands' ),
										"param_name" => "pw_filter_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-multi-filter-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-multi-filter-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-multi-filter-style3",
											),
										"description" => "",
										'dependency' => array(
											'element' => 'type',
											'value' => array( 'adv2' ))										
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Only Featured Brands', 'woocommerce-brands'  ),
										'param_name' => 'pw_featured',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Count of Products', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_count',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Hide Empty Brands', 'woocommerce-brands'  ),
										'param_name' => 'pw_hide_empty_brands',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => '1' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Brand`s Image', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_image',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Brand`s Title', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_title',
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
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Style",  'woocommerce-brands' ),
										"param_name" => "pw_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-allview-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-allview-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-allview-style3",
											),
										"description" => "",
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Show Tooltip', 'woocommerce-brands'  ),
										'param_name' => 'pw_tooltip',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
								)
					) );
			}
		}
		// Shortcode handler function for  icon block
		function pw_all_view_shortcode($atts)
		{
			$type=$pw_show_count=$pw_featured=$pw_hide_empty_brands=
			$pw_show_image=$pw_show_title=$pw_columns=$pw_tablet_columns=$pw_mobile_columns=$pw_style=$pw_tooltip=$pw_adv1_category=$pw_adv2_category=$pw_filter_style="";
			extract(shortcode_atts( array(
				'type' => 'simple',
				'pw_show_count' => 'no',
				'pw_featured' => 'no',
				'pw_hide_empty_brands' => '0',
				'pw_show_image' => 'no',
				'pw_show_title' => 'no',
				'pw_columns'=>'wb-col-md-3',
				'pw_tablet_columns'=>'wb-col-sm-6',
				'pw_mobile_columns'=>'wb-col-xs-12',
				'pw_style' => '',
				'pw_tooltip' => 'no',
				'pw_adv1_category' => 'all',
				'pw_filter_style' => 'all',
				'pw_adv2_category' => 'all',
			),$atts));
			return do_shortcode('[pw_brand_all_views 
				type="'.$type.'" 
				pw_show_count="'.$pw_show_count.'" 
				pw_featured="'.($pw_featured!="" ? $pw_featured : "no").'" 
				pw_hide_empty_brands="'.$pw_hide_empty_brands.'" 
				pw_show_image="'.$pw_show_image.'" 
				pw_show_title="'.$pw_show_title.'" 
				pw_columns="'.$pw_columns.'"
				pw_tablet_columns="'.$pw_tablet_columns.'"
				pw_mobile_columns="'.$pw_mobile_columns.'"
				pw_style="'.$pw_style.'" 
				pw_tooltip="'.($pw_tooltip!= "" ? $pw_tooltip : "no").'"
				pw_adv1_category="'.$pw_adv1_category.'"
				pw_filter_style="'.$pw_filter_style.'"
				pw_adv2_category="'.$pw_adv2_category.'"
				]
			');
		}
	}
	//instantiate the class
	new pw_brand_VC_all_view;
}



?>