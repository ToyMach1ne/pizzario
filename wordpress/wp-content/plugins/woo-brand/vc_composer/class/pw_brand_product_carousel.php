<?php

if(!class_exists('pw_brand_VC_prodcut_carousel'))
{
	class pw_brand_VC_prodcut_carousel
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_prodcut_carousel_init'));
			add_shortcode('pw_brand_vc_prodcut_carousel',array($this,'pw_prodcut_carousel_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{
		}
		function pw_prodcut_carousel_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("Products Carousel By Brands",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_brand_vc_prodcut_carousel",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/product-car.png',
					"category" => __('Woo Brand', 'woocommerce-brands' ),
					"params" => array(
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Select Brand",  'woocommerce-brands' ),
										"param_name" => "pw_brand",
										"description" => ""
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Display Brands In Title', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_title',
										 'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Brands Style Title",  'woocommerce-brands' ),
										"param_name" => "pw_title_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-brandpro-car-header-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-brandpro-car-header-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-brandpro-car-header-style3",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Item Style",  'woocommerce-brands' ),
										"param_name" => "pw_item_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-brandpro-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-brandpro-style2",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Controller/Pagination Style",  'woocommerce-brands' ),
										"param_name" => "pw_carousel_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-carousel-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-carousel-style1",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-carousel-style3",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Carousel Skin",  'woocommerce-brands' ),
										"param_name" => "pw_carousel_skin_style",
										"value" => array(
											__("Liight" ,  'woocommerce-brands' )=>"wb-carousel-skin1",
											__("Dark" ,  'woocommerce-brands' )=>"wb-carousel-skin2",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Slide direction",  'woocommerce-brands' ),
										"param_name" => "pw_slide_direction",
										"value" => array(
											__("Vertical" ,  'woocommerce-brands' )=>"vertical",
											__("Horizontal" ,  'woocommerce-brands' )=>"horizontal",
											),
										"description" => "",
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Item Width",  'woocommerce-brands' ),
										"param_name" => "pw_item_width",
										"value" => '',
										"description" => "",
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Item Margin",  'woocommerce-brands' ),
										"param_name" => "pw_item_marrgin",
										"value" => '',
										"description" => "",
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Show Pagination', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_pagination',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'true' ),
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Show Control', 'woocommerce-brands'  ),
										'param_name' => 'pw_show_control',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'true' ),
									),
									array(
										'type' => 'pw_number',
										"class" => "",
										'heading' => __( 'Item Per View', 'woocommerce-brands'  ),
										'param_name' => 'pw_item_per_view',
										'value' => '',
									),
									array(
										'type' => 'pw_number',
										"class" => "",
										'heading' => __( 'Item Per Slide', 'woocommerce-brands'  ),
										'param_name' => 'pw_item_per_slide',
										'value' => '',
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Slide Speed",  'woocommerce-brands' ),
										"param_name" => "pw_slide_speed",
										"value" => array(
											__("1 Sec" ,  'woocommerce-brands' )=>"1000",
											__("2 Sec" ,  'woocommerce-brands' )=>"2000",
											__("3 Sec" ,  'woocommerce-brands' )=>"3000",
											__("4 Sec" ,  'woocommerce-brands' )=>"4000",
											__("5 Sec" ,  'woocommerce-brands' )=>"5000",
											__("6 Sec" ,  'woocommerce-brands' )=>"6000",
											__("7 Sec" ,  'woocommerce-brands' )=>"7000",
											),
										"description" => "",										
									),
									array(
										'type' => 'checkbox',
										'heading' => __( 'Auto Play', 'woocommerce-brands'  ),
										'param_name' => 'pw_auto_play',
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'true' ),
									),
								)
					) );
			}
		}
		// Shortcode handler function for  icon block
		function pw_prodcut_carousel_shortcode($atts)
		{
			$pw_brand=$pw_show_title=$pw_title_style=$pw_item_style=$pw_carousel_style=$pw_carousel_skin_style=$pw_item_width=$pw_item_marrgin=
			$pw_slide_direction=$pw_show_pagination=$pw_show_control=$pw_item_per_view=$pw_item_per_slide=
			$pw_slide_speed=$auto_play="";
			extract(shortcode_atts( array(
				'pw_brand' => '',
				'pw_show_title' => 'no',
				'pw_title_style' => '',
				'pw_item_style' => '',
				'pw_carousel_style' => '',
				'pw_carousel_skin_style' => '',
				'pw_item_width' => '300',
				'pw_item_marrgin' => '10',
				'pw_slide_direction' => '',
				'pw_show_pagination' => 'false',
				'pw_show_control' => 'false',
				'pw_item_per_view' => '3',
				'pw_item_per_slide' => '1',
				'pw_slide_speed' => '',
				'pw_auto_play' => 'false',
			),$atts));
			return do_shortcode('[pw_brand_product_carousel
				pw_brand="'.( $pw_brand !="" ? $pw_brand : "all" ).'" 
				pw_show_title="'.$pw_show_title.'" 
				pw_title_style="'.$pw_title_style.'" 
				pw_item_style="'.$pw_item_style.'" 
				pw_carousel_style="'.$pw_carousel_style.'" 
				pw_carousel_skin_style="'.$pw_carousel_skin_style.'" 
				pw_item_width="'.$pw_item_width.'" 
				pw_item_marrgin="'.$pw_item_marrgin.'" 
				pw_slide_direction="'.$pw_slide_direction.'" 
				pw_show_pagination="'.$pw_show_pagination.'" 
				pw_show_control="'.$pw_show_control.'" 
				pw_item_per_view="'.$pw_item_per_view.'" 
				pw_item_per_slide="'.$pw_item_per_slide.'" 
				pw_slide_speed="'.$pw_slide_speed.'" 
				pw_auto_play="'.($pw_auto_play!="" ? $pw_auto_play:"false").'"]'
			);

		}
	}
	//instantiate the class
	new pw_brand_VC_prodcut_carousel;
}
?>