<?php

if(!class_exists('pw_brand_VC_thumbnails'))
{
	class pw_brand_VC_thumbnails
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_thumbnails_init'));
			add_shortcode('pw_brand_vc_thumbnails',array($this,'pw_thumbnails_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{	
		}
		function pw_thumbnails_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("Brands thumbnail",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_brand_vc_thumbnails",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/thumbnail.png',
					"category" => __('Woo Brand', 'woocommerce-brands'),
					"params" => array(
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Brands",  'woocommerce-brands'  ),
										"param_name" => "pw_brand",
										"description" => ""
									),
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Except Brand(s)",  'woocommerce-brands'  ),
										"param_name" => "pw_except_brand",
										"description" => ""
									),
									array(
										'type' => 'pw_number',
										"class" => "",
										'heading' => __( 'Count Of Item', 'woocommerce-brands'  ),
										'param_name' => 'pw_count_of_number',
										'value' => '',
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Order By",  'woocommerce-brands' ),
										"param_name" => "pw_order_by",
										"value" => array(
											__("ASC" ,  'woocommerce-brands' )=>"Name",
											__("DESC" ,  'woocommerce-brands' )=>"Count",
											),
										"description" => "",
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Display Only Featured Brands','woocommerce-brands'),
										'param_name' => 'pw_featured',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Hide Empty Brands','woocommerce-brands'),
										'param_name' => 'pw_hide_empty_brands',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => '1' ),
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Display Brand`s Image Size",  'woocommerce-brands' ),
										"param_name" => "pw_show_image_size",
										"value" => array(
											__("Thumbnail" ,  'woocommerce-brands' )=>"thumb",
											__("Full" ,  'woocommerce-brands' )=>"full",
											),
										"description" => "",
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Display Brand`s Title','woocommerce-brands'),
										'param_name' => 'pw_show_title',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Display Count Of Brands','woocommerce-brands'),
										'param_name' => 'pw_show_count',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __('Show Tooltip','woocommerce-brands'),
										'param_name' => 'pw_tooltip',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Style",  'woocommerce-brands' ),
										"param_name" => "pw_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-thumb-style1",
											__("style 2" ,  'woocommerce-brands' )=>"wb-thumb-style2",
											__("style 3" ,  'woocommerce-brands' )=>"wb-thumb-style3",
											__("style 4" ,  'woocommerce-brands' )=>"wb-thumb-style4",
											),
										"description" => "",
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
										'heading' => __('Round Corner','woocommerce-brands'),
										'param_name' => 'pw_round_corner',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'wb-thumb-round' ),
									),
								)
					) );
			}
		}
		// Shortcode handler function for  icon block
		function pw_thumbnails_shortcode($atts)
		{
			$pw_except_brand=$pw_style=$pw_round_corner=$pw_brand=
			$pw_columns=$pw_tablet_columns=$pw_mobile_columns=$pw_count_of_number=$pw_hide_empty_brands==$pw_show_image_size=
			$order_by=$pw_show_title=$pw_show_count=$pw_tooltip=$pw_featured="";
			extract(shortcode_atts( array(
				'pw_brand' => 'null',
				'pw_except_brand' => 'null',
				'pw_style' => 'wb-thumb-style1',
				'pw_round_corner' => 'wb-car-no',
				'pw_tooltip' => 'no',
				'pw_featured' => 'no',
				'pw_count_of_number' => '',
				'pw_hide_empty_brands' => '0',
				'pw_show_image_size' => '0',
				'pw_show_title' => 'no',
				'pw_show_count' => 'no',
				'pw_order_by' => 'name',
				'pw_columns'=>'wb-col-md-3',
				'pw_tablet_columns'=>'wb-col-sm-6',
				'pw_mobile_columns'=>'wb-col-xs-12',
			),$atts));
			return do_shortcode('
				[pw_brand_thumbnails 
					pw_brand="'.( $pw_brand !="" ? $pw_brand : "all" ).'"
					pw_except_brand="'.$pw_except_brand.'"
					pw_style="'.$pw_style.'"
					pw_round_corner="'.$pw_round_corner.'"
					pw_tooltip="'.$pw_tooltip.'"
					pw_featured="'.($pw_featured!="" ? $pw_featured : "no").'"
					pw_count_of_number="'.$pw_count_of_number.'"
					pw_hide_empty_brands="'.$pw_hide_empty_brands.'"
					pw_show_image_size="'.$pw_show_image_size.'"
					pw_show_title="'.$pw_show_title.'"
					pw_show_count="'.$pw_show_count.'"
					pw_order_by="'.$pw_order_by.'"
					pw_columns="'.$pw_columns.'"
					pw_tablet_columns="'.$pw_tablet_columns.'"
					pw_mobile_columns="'.$pw_mobile_columns.'"
				]
			');
		}
	}
	//instantiate the class
	new pw_brand_VC_thumbnails;
}
?>