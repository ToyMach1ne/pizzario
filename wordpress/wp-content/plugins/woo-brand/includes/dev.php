<?php
add_shortcode( 'pw_brand_all_viewsa', 'pw_brand_all_views_funca' );
function pw_brand_all_views_funca( $atts, $content = null ) {
	$type=$show_count=$featured=$pw_hide_empty_brands=$show_image=
	$pw_adv1_category=$pw_adv1_defalt_category=$pw_adv1_hide_empty_brands=$pw_adv1_order_by=$pw_adv1_tooltip=
	$pw_adv2_category=$pw_adv2_hide_empty_brands=$pw_adv2_order_by=$pw_adv2_tooltip="";

	extract(shortcode_atts( array(
		'type' => '',
		'show_count' => '',
		'featured' => '',
		'pw_hide_empty_brands' => '',
		'show_image' => '',
		'pw_adv1_category' => '',
		'pw_adv1_defalt_category' => '',
		'pw_adv1_hide_empty_brands' => '',
		'pw_adv1_order_by' => '',
		'pw_adv1_tooltip' => '',
		'pw_adv2_category' => '',
		'pw_adv2_hide_empty_brands' => '',
		'pw_adv2_order_by' => '',
		'pw_adv2_tooltip' => '',
	),$atts));
	if(get_option('pw_woocommerce_brands_show_categories')=="yes")
		$get_terms="product_cat";
	else
		$get_terms="product_brand";
	$ret="";
	switch ($type) {
		case 'simple':
			require plugin_dirname_pw_woo_brand.'/includes/brand_lists/simple.php';
		break;
		
		case 'adv1':
			require plugin_dirname_pw_woo_brand.'/includes/brand_lists/adv1.php';
		break;
		
		case 'adv2':
			require plugin_dirname_pw_woo_brand.'/includes/brand_lists/adv2.php';
		break;	
	}
	
	$ret.='<div class="pw_brand_loading_cnt pw_brand_loadd" style="background:#EEE"><div class="pw_brand_le_loading" ><img src="'.plugin_dir_url_pw_woo_brand.'/img/loading.gif" width="32" height="32" /></div></div>';		
	
	return $ret;
}



?>