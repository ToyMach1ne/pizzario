<?php
	//GET FROM XML
	/*$api_url='http://proword.net/xmls/Woo_Reporting/add-ons.php';
	
	$response = wp_remote_get(  $api_url );
				
	 Check for errors, if there are some errors return false 
	if ( is_wp_error( $response ) or ( wp_remote_retrieve_response_code( $response ) != 200 ) ) {
		return false;
	}*/
	$add_ons_status=array(
		array(
			"id" => "variation",
			"label" => 'Coupon Add-on',
			"desc" => 
			"
				<p>This Add-On helps you create coupons based on brands.</p>
			",
			"icon" => "<div class='awr-descicon'></div>",
			"link" => "http://proword.net/product/brand-coupon-add-on/",
			"folder_name" => "PW-Advanced-Woocommerce-Reporting-System-Variaion-addon",
			"define_variable" => "__PW_VARIATION_ADD_ON__",
		),
	);
	
	echo '
	<div class="wrap">
		<div class="row">
			<div class="col-xs-12">
				
				
			';
	foreach($add_ons_status as $plugin){
		//IS ACTIVE
		$active=defined($plugin['define_variable']);
		
		//IS EXIST
		$my_plugin = WP_PLUGIN_DIR . '/' .$plugin['folder_name'];
		$exist=is_dir( $my_plugin );
		
		$label=$plugin['label'];
		$desc =$plugin['desc'];
		$icon = $plugin['icon'];
		$active_status='';
		$btn='';
		
		if($exist){
			if($active)
			{
				$active_status="awr-addones-active";
				$btn='<a class="awr-addons-btn" href="#" ><i class="fa fa-check"></i>'.__('Activated','woocommerce-brands').'</a>';
			}else
			{
				$active_status="awr-addones-deactive";
				$btn='<a class="awr-addons-btn" href="'.admin_url()."plugins.php".'" target="_blank"><i class="fa fa-plug"></i>'.__('Activate Here','woocommerce-brands').'</a>';
			
			}
		}else
		{
			$active_status="awr-addones-disable";
			$btn='<a class="awr-addons-btn" href="'.$plugin['link'].'" target="_blank"><i class="fa fa-shopping-cart"> </i>'.__('Buy Now','woocommerce-brands').'</a>';
			
			
			
		}
		
		//echo '<div style="background:'.$color.'"><div><h4>'.$label.'</h4></div>'.$text.'</div>';
		echo '
			  <div class="awr-addons-cnt '.$active_status.'">
				'.$icon.'
				<div class="awr-desc-content">	
					<h3 class="awr-addones-title">'.$label.'</h3>
					<div class="awr-addnoes-desc">'.$desc.'</div><br>
					'.$btn.'
				</div>
				<div class="awr-clearboth"></div>
			  </div>';
	}
	echo '
			</div><!--col-xs-12 -->
		</div><!--row -->
	</div><!--wrap -->
	';
?>