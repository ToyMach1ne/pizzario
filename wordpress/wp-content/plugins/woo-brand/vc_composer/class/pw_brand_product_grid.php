<?php

if(!class_exists('pw_brand_VC_product_grid'))
{
	class pw_brand_VC_product_grid
	{
		function __construct()
		{
			add_action('admin_init',array($this,'pw_product_grid_init'));
			add_shortcode('pw_brand_vc_product_grid',array($this,'pw_product_grid_shortcode'));
			//add_action('wp_enqueue_scripts',array($this,'alert_shortcode'));
		}	
		function front_scripts_alert()
		{	
		}
		function pw_product_grid_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("Products Grid By Brands",  'woocommerce-brands'),
					"description" => '',
					"base" => "pw_brand_vc_product_grid",
					"class" => "",
					"controls" => "full",
					"icon" => __IT_PROJECTNAME_ROOT_URL_VC__.'icons/product-grid.png',
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
										'type' => 'checkbox',
										'heading' => __('Display Brands In Title','woocommerce-brands'),
										'param_name' => 'pw_show_title',
										'value' => array( __( 'Yes, please','woocommerce-brands'  ) => 'yes' ),
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Brand`s Title Style",  'woocommerce-brands' ),
										"param_name" => "pw_title_style",
										"value" => array(
											__("Style 1" ,  'woocommerce-brands' )=>"wb-brandpro-car-header-style1",
											__("Style 2" ,  'woocommerce-brands' )=>"wb-brandpro-car-header-style2",
											__("Style 3" ,  'woocommerce-brands' )=>"wb-brandpro-car-header-style3",
											),
										"description" => "",
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Columns", 'woocommerce-brands' ),
										"param_name" => "pw_columns",
										"value" => '',
										"description" => "",
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Number Per Page", 'woocommerce-brands' ),
										"param_name" => "pw_posts_per_page",
										"value" => '',
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Order By",  'woocommerce-brands' ),
										"param_name" => "pw_orderby",
										"value" => array(
											__("name" ,  'woocommerce-brands' )=>"name",
											__("rand" ,  'woocommerce-brands' )=>"rand",
											__("author" ,  'woocommerce-brands' )=>"author",
											__("title" ,  'woocommerce-brands' )=>"title",
											__("date" ,  'woocommerce-brands' )=>"date",
											__("ID" ,  'woocommerce-brands' )=>"ID",
											__("modified" ,  'woocommerce-brands' )=>"modified",
											__("none" ,  'woocommerce-brands' )=>"none",
											),
										"description" => "",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Order",  'woocommerce-brands' ),
										"param_name" => "pw_order",
										"value" => array(
											__("ASC" ,  'woocommerce-brands' )=>"ASC",
											__("DESC" ,  'woocommerce-brands' )=>"DESC",
											),
										"description" => "",
									),
								)
					) );
			}
		}
		// Shortcode handler function for  icon block
		function pw_product_grid_shortcode($atts)
		{
			$pw_brand=$pw_show_title=$pw_title_style=$pw_columns=$pw_posts_per_page=$pw_orderby=$pw_orderby=$pw_order="";
			extract(shortcode_atts( array(
				'pw_brand' => '',
				'pw_show_title' => 'no',
				'pw_title_style' => '',
				'pw_columns' => '3',
				'pw_posts_per_page' => '',
				'pw_orderby' => '',
				'pw_order' => '',
			),$atts));
			return do_shortcode('[pw_brand_product_grid 
				pw_brand="'.( $pw_brand !="" ? $pw_brand : "all" ).'"
				pw_show_title="'.$pw_show_title.'" 
				pw_title_style="'.$pw_title_style.'" 
				pw_columns="'.$pw_columns.'" 
				pw_posts_per_page="'.$pw_posts_per_page.'" 
				pw_orderby="'.$pw_orderby.'"
				pw_order="'.$pw_order.'"]'
			);
		}
	}
	//instantiate the class
	new pw_brand_VC_product_grid;
}
?>