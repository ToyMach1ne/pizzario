<?php

if(!class_exists('pw_VC_carousel'))
{
	class pw_VC_carousel
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_carousel_init'));
			add_shortcode('pw_brand_vc_carousel',array($this,'pw_carousel_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{
		}
		function pw_carousel_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("Brands Carousel",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_brand_vc_carousel",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/brand-car.png',
					"category" => __('Woo Brand', 'woocommerce-brands' ),
					"params" => array(
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Select Brand",  'woocommerce-brands' ),
										"param_name" => "pw_brand",
										"description" => "Leave Blank To Select All"
									),
									array(
										"type" => "brand",
										"class" => "",
										"heading" => __("Except Brand(s)",  'woocommerce-brands' ),
										"param_name" => "pw_except_brand",
										"description" => ""
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Display Only Featured Brands", 'woocommerce-brands' ),
										"param_name" => "pw_featured",
										"description" => "",
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Display Count Of Brands", 'woocommerce-brands' ),
										"param_name" => "pw_show_count",
										"description" => "",
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Display Brand`s Image", 'woocommerce-brands' ),
										"param_name" => "pw_show_image",
										"description" => "",
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
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
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Display Brand`s Title", 'woocommerce-brands' ),
										"param_name" => "pw_show_title",
										"description" => "",
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show Tooltip", 'woocommerce-brands' ),
										"param_name" => "pw_tooltip",
										"description" => "",
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'yes' ),
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Style",  'woocommerce-brands' ),
										"param_name" => "pw_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-car-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-car-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-car-style3",
											__("Style 4" ,  'woocommerce-brands' )=>"wb-car-style4",
											__("Style 5" ,  'woocommerce-brands' )=>"wb-car-style5",
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
											__("Style 2" ,  'woocommerce-brands' )=>"wb-carousel-style2",
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
											__("Light" ,  'woocommerce-brands' )=>"wb-carousel-skin1",
											__("Dark" ,  'woocommerce-brands' )=>"wb-carousel-skin2",
											),
										"description" => "",
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Round Corner", 'woocommerce-brands' ),
										"param_name" => "pw_round_corner",
										"description" => "",
										'value' => array( __( 'Yes, please', 'woocommerce-brands'  ) => 'wb-car-round' ),
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
										"heading" => __("Item Marrgin",  'woocommerce-brands' ),
										"param_name" => "pw_item_marrgin",
										"value" => '',
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
		function pw_carousel_shortcode($atts)
		{
			$pw_brand=$pw_show_image=$pw_show_image_size=$pw_tooltip=$pw_except_brand=$pw_featured=$pw_show_title=$pw_show_count=$pw_style=$pw_carousel_style=$pw_carousel_skin_style=$pw_round_corner=
			$pw_item_width=$pw_item_marrgin=$pw_slide_direction=$pw_show_pagination=$pw_show_control
			=$pw_item_per_view=$pw_item_per_slide=$pw_slide_speed=$pw_auto_play="";
			extract(shortcode_atts( array(
				'pw_brand' => 'null',
				'pw_except_brand' => 'null',
				'pw_style' => 'wb-car-style1',
				'pw_carousel_style' => 'wb-carousel-style1',
				'pw_carousel_skin_style' => 'wb-carousel-skin1',
				'pw_tooltip' => 'no',
				'pw_round_corner' => 'wb-car-no',
				'pw_show_image' => 'no',
				'pw_show_image_size' => 'thumb',
				'pw_featured' => 'no',
				'pw_show_title' => 'no',
				'pw_show_count' => 'no',
				'pw_item_width' => '300',
				'pw_item_marrgin' => '10',
				'pw_slide_direction' => 'vertical',
				'pw_show_pagination' => 'false',
				'pw_show_control' => 'false',
				'pw_item_per_view' => '3',
				'pw_item_per_slide' => '1',
				'pw_slide_speed' => '',
				'pw_auto_play' => 'false',
			),$atts));	
			return do_shortcode('[pw_brand_carousel 
				pw_brand="'.( $pw_brand !="" ? $pw_brand : "all" ).'" 
				pw_except_brand="'.$pw_except_brand.'" 
				pw_style="'.$pw_style.'" 
				pw_carousel_style="'.$pw_carousel_style.'" 
				pw_carousel_skin_style="'.$pw_carousel_skin_style.'" 
				pw_tooltip="'.$pw_tooltip.'" 
				pw_round_corner="'.$pw_round_corner.'" 
				pw_show_image="'.$pw_show_image.'" 
				pw_show_image_size="'.$pw_show_image_size.'" 
				pw_featured="'.($pw_featured!="" ? $pw_featured : "no").'" 
				pw_show_title="'.$pw_show_title.'" 
				pw_show_count="'.$pw_show_count.'" 
				pw_item_width="'.$pw_item_width.'" 
				pw_item_marrgin="'.$pw_item_marrgin.'" 
				pw_slide_direction="'.$pw_slide_direction.'" 
				pw_show_pagination="'.$pw_show_pagination.'" 
				pw_show_control="'.$pw_show_control.'" 
				pw_item_per_view="'.$pw_item_per_view.'" 
				pw_item_per_slide="'.$pw_item_per_slide.'" 
				pw_slide_speed="'.$pw_slide_speed.'" 
				pw_auto_play="'.$pw_auto_play.'" ]'
			);
		}
	}
	//instantiate the class
	new pw_VC_carousel;
}
?>