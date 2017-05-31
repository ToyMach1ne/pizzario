<?php
		global $_chosen_attributes, $woocommerce, $_attributes_array;
	

	$include=$pw_adv2_category;
	
	if($pw_adv2_category =="null" || $pw_adv2_category=="all" || $pw_adv2_category=="")
		$include="";
	$ret ='';
	$rand_id='_'.rand(0,9000);
	$form= '<form id="pw_brand_form'.$rand_id.'">';

	foreach($atts as $key=>$value)
	{
		$form.='<input type="hidden" name="other_settings['.$key.']" value="'.$value.'" />';
	}

	$dropdown='<label for="pw_brand_category_filter" class="pw_brand_category_filter_checkbox pw-active-filter">'.__('All','woocommerce-brand').'<input name="pw_woob_category[]" id="pw_brand_category_filter_all"  type="checkbox" value="0" data-id="'.$rand_id.'" checked /></label>';
	
	$categories=get_categories(
		array(
			'taxonomy' => 'product_cat',
			'include'=> explode(',',$include),
			'orderby'            => 'title', 
			'order'              => 'ASC',
			)
		);

	foreach ($categories as $category) {
		$dropdown.='<label for="pw_brand_category_filter" class="pw_brand_category_filter_checkbox">'.$category->cat_name.' <input name="pw_woob_category[]" id="pw_brand_category_filter"  type="checkbox" value="'.$category->cat_ID.'" data-id="'.$rand_id.'"/></label>';
	}
	
	//$dropdown=str_replace("class='pw_brand_category_filter'","class='pw_brand_category_filter' data-id='".$rand_id."'",$dropdown);
	
	//$dropdown=str_replace("class='pw_brand_category_filter'","class='pw_brand_category_filter' data-id='".$rand_id."' multiple='multiple'",$dropdown);
	
	$form.=$dropdown.'</form>';	


	$taxonomy 		= 'product_brand';
	$get_terms="product_brand";
	$empty_brand='1';
	if($pw_hide_empty_brands =="null" || $pw_hide_empty_brands==""|| $pw_hide_empty_brands=="0")
	{
		$empty_brand='0';	
	}
	$terms = get_terms( $taxonomy, array( 'hide_empty' => $empty_brand ) );
	
	
	$includes=array();
	$query_meta_query=array();
	if($include!='' && !in_array('0',explode(',',$include)))
	{
		$query_meta_query[] = array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => explode(',',$include)
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
	
		
	$output_html="";
	$letters='';
	$did=rand(0,1000);
	
	$output_html='<div class="wb-allview  wb-allview-'.$did.' '.$pw_style.'">';
	
	$flag="";
	$numeric_flag=false;	
	$item_counter =0;			
	foreach ( $terms as $term ) {
		$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );
		if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {
               // $_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

              //  set_transient( $transient_name, $_products_in_term );			
		}
		$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );
		$count = sizeof( array_intersect( $_products_in_term, $matched_products ) );

		if ( $count == 0 && ! $option_is_set )
			continue;	
	/*	$output_html.=  ( $count > 0 || $option_is_set ) ? '<a href="">' : '<span>';
		$output_html.=  $term->name;
		$output_html.=  ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';
		$output_html.= ' <small class="count">' . $count . '</small></li>';	
	*/
	//print_r($term);
	//$includes[]=$term->term_id;
		$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
		$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
		$first_letter=strtoupper(mb_substr(esc_html( $term->name ),0,1));
		if(!is_numeric($first_letter))
			$numeric_flag=false;
	
		$brand_img='';
		//if show image in shortcode
		if($pw_show_image=="yes")
		{
			//get image from tbl woocommerce term_meta
			$image 			= '';
			$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
			if ( $thumbnail_id && $pw_show_image=="yes" )
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			else
			{
				if(get_option('pw_woocommerce_brands_default_image'))
					$image=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
				else
					$image = WP_PLUGIN_URL.'/woo-brand/img/default.png';
			}
			
			$brand_img='<img src="'.$image.'"  alt="'.esc_html( $term->name).'" />';
		}
		//if show count in shortcode
		$brand_count='';
		if($pw_show_count=="yes")
			$brand_count .=' ('.$count.')';
	
		//if  show only featured in shortcode
		if($pw_featured=="yes" && $display_type==1){
			if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
			{
				//end alphabet-cnt
				if($flag!="") $output_html.="</div><!--row -->
										</div><!-- wb-allview-cat-cnt=-->";
				
				//if first_latter is numberic
				if(is_numeric($first_letter) && $numeric_flag==false)
				{
					$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12" id="123">123</div>'.$numeric_flag.
									'<div class="wb-row">';
					$numeric_flag=true;
					$letters .='<a class="wb-wb-allview-letters" href="#123">123</a>';
				}
				else if(!is_numeric($first_letter) ){
					$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12" id="'.$first_letter.'">'.$first_letter.'</div>'.$numeric_flag.
										'<div class="wb-row">';
					$letters .='<a class="wb-wb-allview-letters" href="#'.$first_letter.'">'.$first_letter.'</a>';
				}
				$flag=$first_letter;
			}						
				
			$output_html.='<div class="'.(($pw_style!='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'wb-col-md-12').'">
						<div class="wb-allview-item-cnt" rel="tipsy" title="'.$term->name.$brand_count.'"><a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$brand_img.'</a>';
							if ($pw_show_title=='yes'){
								$output_html.='<div class="wb-allview-item-title">
										  <a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$term->name.$brand_count.'</a>
									   </div>';
							}
						$output_html.='
						</div>
					</div>';
		}
		elseif($pw_featured=="no"){
		
			if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
			{
				//end alphabet-cnt
				if($flag!="") $output_html.="</div><!--row -->
										</div><!-- wb-allview-cat-cnt=-->";
				
				//if first_latter is numberic
				if(is_numeric($first_letter) && $numeric_flag==false)
				{
					$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12" id="123">123</div>'.$numeric_flag.
									'<div class="wb-row">';
					$numeric_flag=true;
					$letters .='<a class="wb-wb-allview-letters" href="#123">123</a>';
				}
				else if(!is_numeric($first_letter) ){
					$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12" id="'.$first_letter.'">'.$first_letter.'</div>'.$numeric_flag.
										'<div class="wb-row">';
					$letters .='<a class="wb-wb-allview-letters" href="#'.$first_letter.'">'.$first_letter.'</a>';
				}
				$flag=$first_letter;
			}						
				
			$output_html.='<div class="'.(($pw_style!='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'wb-col-md-12').'">
						<div class="wb-allview-item-cnt" rel="tipsy" title="'.$term->name.$brand_count.'">
							'.$brand_img;
							if ($pw_show_title=='yes'){
								$output_html.='<div class="wb-allview-item-title">
										  <a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$term->name.$brand_count.'</a>
									   </div>';
							}
						$output_html.='
						</div>
					</div>';
		}//end elseif $pw_featured=="no"
		$item_counter++;
	}
	$output_html.="</div><!--row -->
			</div><!-- wb-allview-cat-cnt=-->";
	//end all-brand-alphabet
	$output_html.="</div>";
	if ( $pw_tooltip=='yes' ){
		$output_html .="
		<script type='text/javascript'>
			jQuery(function() {
			   jQuery('.wb-allview-".$did." div[rel=tipsy]').tipsy({ gravity: 's',live: true,fade:true});
			});
		</script>";
	}



$ret.= '<div class="wb-allview-advcnt"><div class="wb-allview-formcnt '.$pw_filter_style.'">'.$form. '<div class="wb-allview-lettercnt">'.$letters.'</div></div>'.'<div class="pw_brand_loading_cnt pw_brand_loadd" id="pw_brand_loadd'.$rand_id.'"><div class="pw_brand_le_loading" ><img src="'.plugin_dir_url_pw_woo_brand.'/img/loading.gif" width="32" height="32" /></div></div><div id="pw_brand_result'.$rand_id.'">'.$output_html.'</div></div>';

//print_r($includes);


//$ret.= "</ul>";
/*
$empty_brand=$pw_hide_empty_brands;
if($pw_hide_empty_brands =="null" || $pw_hide_empty_brands=="")
	$empty_brand="0";
$args = array(
	'orderby'           => 'name', 
	'order'             => 'ASC',
	'hide_empty'        => 0,
	'exclude'           => array(), 
	'exclude_tree'      => array(), 
	'include'           => $includes,
	'number'            => '', 
	'fields'            => 'all', 
	'slug'              => '',
	'name'              => '',
	'parent'            => '',
	'hierarchical'      => true, 
	'child_of'          => 0, 
	'get'               => '', 
	'name__like'        => '',
	'description__like' => '',
	'pad_counts'        => false, 
	'offset'            => '', 
	'search'            => '', 
	'cache_domain'      => 'core'		
);	
$get_terms="product_brand";
$categories = get_terms( $get_terms, $args);
print_r($categories);
	
foreach( (array) $categories as $term ) {
	

}//end for each

*/
?>