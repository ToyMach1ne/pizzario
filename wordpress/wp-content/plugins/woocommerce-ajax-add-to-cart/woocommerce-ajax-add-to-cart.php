<?php
/*
Plugin Name: WooCommerce Ajax Add To Cart (oraksoft)
Description: A plugin that will ajaxify the "Add to cart" button even in the single product pages.
Version: 1.0.0
Author: Karapet Abrahamyan
Author URI: http://oraksoft.com/
*/

if (!defined('ABSPATH'))
    die();

class OrakAddToCart
{
	
	public static function Init()
	{
		add_action('wp_enqueue_scripts', 'OrakAddToCart::loadScripts');		
	}
	
	public static function loadScripts()
	{	
		add_action('wp_head', 'OrakAddToCart::enqueueScripts');
	}
	
	public static function enqueueScripts() 
	{
		global $woocommerce;
		
		wp_register_script(
			'oraksoft-ajax-add-to-cart',
			plugins_url( 'orak-ajax-add-to-cart.js' , __FILE__ ),
			array('jquery')
		);
		wp_enqueue_script('oraksoft-ajax-add-to-cart');
		wp_localize_script('oraksoft-ajax-add-to-cart', 'oraksoft_js_data_watc', array('cart_url' => $woocommerce->cart->get_cart_url()));
	}
}

if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option( 'active_plugins' ))))
    OrakAddToCart::Init();
?>