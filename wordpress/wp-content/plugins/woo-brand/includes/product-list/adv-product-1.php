<?php
		global $_chosen_attributes, $woocommerce, $_attributes_array;

	$include_category=$pw_adv1_category;
	$include_brand=$pw_brand;
	
	if($pw_adv1_category =="null" || $pw_adv1_category=="all" || $pw_adv1_category=="")
		$include_category="";
		
	if($pw_brand =="null" || $pw_brand=="all" || $pw_brand=="")
		$include_brand="";	

	$ret='';
	$rand_id='_'.rand(0,9000);
	$form= '<form id="pw_brand_form'.$rand_id.'">';

	foreach($atts as $key=>$value)
	{
		$form.='<input type="hidden" name="other_settings['.$key.']" value="'.$value.'" />';
	}


	$dropdown_category=wp_dropdown_categories(
		array(
			'show_option_all'    => __('All (Select Categorie)','woocommerce-brand'),
			'taxonomy' => 'product_cat',
			'class'=> 'pw_brand_category_filter_product',
			'name'=> 'pw_woob_category[]',
			'orderby'            => 'title', 
			'order'              => 'ASC',
			'id'=>'pw_brand_category_filter_product',
			'include'=> explode(',',$include_category),
			'echo'               => 0,
			)
		);
	
	$dropdown_category=str_replace("class='pw_brand_category_filter_product'","class='pw_brand_category_filter_product' data-id='".$rand_id."'",$dropdown_category);
	
	$dropdown_brand=wp_dropdown_categories(
		array(
			'show_option_all'    => __('All (Select ','woocommerce-brand').get_option( 'pw_woocommerce_brands_base' ).')',
			'taxonomy' => 'product_brand',
			'class'=> 'pw_brand_category_filter_product',
			'name'=> 'pw_woob_brand[]',
			'orderby'            => 'title', 
			'order'              => 'ASC',
			'id'=>'pw_brand_category_filter_product',
			'include'=> explode(',',$include_brand),
			'echo'               => 0,
			)
		);
		
	$dropdown_brand=str_replace("class='pw_brand_category_filter_product'","class='pw_brand_category_filter_product' data-id='".$rand_id."'",$dropdown_brand);
	
	
	$form.=$dropdown_category.$dropdown_brand.'</form>';	
	

	$taxonomy 		= 'product_brand';
	$get_terms="product_brand";
	$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );
	
	
	$includes=array();
	$query_meta_query=array('relation' => 'AND');
	
	if($include_category!='' && !in_array('0',explode(',',$include_category)))
	{
		$query_meta_query[] = array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => explode(',',$include_category)
					)
		);	
	}
	
	if($include_brand!='' && !in_array('0',explode(',',$include_brand)))
	{
		$query_meta_query[] = array(
					array(
						'taxonomy' => 'product_brand',
						'field' => 'id',
						'terms' => explode(',',$include_brand)
					)
		);	
	}


	$matched_products = get_posts(
		array(
			'post_type' 	=> 'product',
			'numberposts' 	=> -1,
			'post_status' 	=> 'publish',
			'fields' 		=> 'ids',
			'no_found_rows' => true,
			'tax_query' => $query_meta_query,
		)
	);
	
	/*print_r(array(
			'post_type' 	=> 'product',
			'numberposts' 	=> -1,
			'post_status' 	=> 'publish',
			'fields' 		=> 'ids',
			'no_found_rows' => true,
			'tax_query' => $query_meta_query,
		));*/
	
	
	$output_html="";
	$did=rand(0,1000);
	
	$output_html='<div class="wb-allview  wb-allview-'.$did.' '.$pw_style.'">';
	
	$flag="";
	$numeric_flag=false;	
	$item_counter =0;			
	
	foreach($matched_products as $id)
	{
		$product = get_product($id);
		$title = $product->get_title();
		$price = $product->get_price_html();
		//$img=$product->get_image(); //Featured Image 
		$size = 'shop_catalog';
		$img = get_the_post_thumbnail( $id, $size );
 		$permalink=$product->get_permalink();
		$add_to_cart_url = $product->add_to_cart_url();
		$cat =$product->get_categories();				
		$tag =$product->get_tags();
		$sku =$product->get_sku();
		$featured =$product->is_featured();
		$on_sale =$product->is_on_sale(); // 1:0
		$stock_status =$product->is_in_stock(); //1 : in ,0 :out
		$stock_quantity =$product->get_stock_quantity();
		$brands_list =  wp_get_object_terms($id, 'product_brand');
		$list="";
		$i=0;
		$brand_list=get_the_term_list( $id, 'product_brand', '', ', ', '' );
		//echo $brand_list.' , ';
		
		$tool_tip='<div class="wb-brandpro-list-meta"><span>'.__('Categories: ','woocommerce-brands').'</span>'.$cat.'</div>
						<div class="wb-brandpro-list-meta"><span>'.__('Brands: ','woocommerce-brands').'</span>'.$brand_list.'</div>';
		
		$output_html.='
			<div class="'.$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns.'">
				<div class="wb-brandpro-list-cnt ">';
				if (isset($pw_show_image) && ($pw_show_image=='yes'))	
					$output_html.=$img;
				$output_html.='
					<div class="wb-brandpro-list-detail">
						<div class="wb-brandpro-list-title">
							<h3>
								<a href="'.$permalink.'" >'.$title.'</a>
								<div class="tooltip-cnt">'.$tool_tip.'</div>
							</h3>
							
						</div>';
					if (isset($pw_show_price) && ($pw_show_price=='yes'))
						$output_html.='<div class="wb-brandpro-list-price">'.$price.'</div>';
				$output_html.='	
					</div>
				</div>
			</div>';
	}
	
	$output_html.="
			</div><!-- wb-allview-cat-cnt-->";
	
	



$ret.= '<div class="wb-allview-advcnt"><div class="wb-allview-formcnt">'.$form. '</div><div class="pw_brand_loading_cnt pw_brand_loadd" id="pw_brand_loadd'.$rand_id.'"><div class="pw_brand_le_loading" ><img src="'.plugin_dir_url_pw_woo_brand.'/img/loading.gif" width="32" height="32" /></div></div><div id="pw_brand_result'.$rand_id.'">'.$output_html.'</div></div>';
?>