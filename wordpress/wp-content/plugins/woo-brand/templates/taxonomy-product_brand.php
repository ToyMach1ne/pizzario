<?php
/**
 * Use the WooCommerce archive template for brand taxonomy pages
 */
		if(version_compare(WC()->version, '3.0.0', '>=')){
			$wc_get_3='wc_get_template';
		}
		else
		{
			$wc_get_3='woocommerce_get_template';
		}	
$wc_get_3( 'archive-product.php' );