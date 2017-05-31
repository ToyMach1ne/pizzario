<?php
	//FETCH BRANDS 
	add_action('wp_ajax_pw_brand_fetch_brands', 'pw_brand_fetch_brands');
	add_action('wp_ajax_nopriv_pw_brand_fetch_brands', 'pw_brand_fetch_brands');
	function pw_brand_fetch_brands() {
		extract($_POST);
		global $_chosen_attributes, $woocommerce, $_attributes_array;

		extract(shortcode_atts( array(
			'type' => '',
			'pw_show_count' => '',
			'pw_featured' => '',
			'pw_hide_empty_brands' => '',
			'pw_show_image' => '',
			'pw_show_title' => '',
			'pw_columns' => '',
			'pw_style' => '',
			'pw_tooltip' => '',
			'pw_adv1_category'=> '',
			'pw_adv2_category'=> '',
		),$other_settings));
		$letters='';
		$include="";		
		if($type=='adv1')
		{
			$include=$pw_adv1_category;
			if($pw_adv1_category =="null" || $pw_adv1_category=="all" || $pw_adv1_category=="")
				$include="";
		}else if($type=='adv2')
		{
			$include=$pw_adv2_category;
			if($pw_adv2_category =="null" || $pw_adv2_category=="all" || $pw_adv2_category=="")
				$include="";
		}
				
		$current_term 	= $pw_woob_category;
		$taxonomy 		= 'product_brand';
		$get_terms="product_brand";
		$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );
		
		
		$includes=array();
		
		$query_meta_query=array();
		if(!in_array('0',$current_term))
		{
			$query_meta_query[] = array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'id',
							'terms' => $current_term
						)
			);	
		}else if($include!='')
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
		
		/*print_r(array(
				'post_type' 	=> 'product',
				'numberposts' 	=> -1,
				'post_status' 	=> 'publish',
				'fields' 		=> 'ids',
				'no_found_rows' => true,
				'tax_query' => $query_meta_query,
			));
		*/
		
		$ret="";
		$did=rand(0,1000);
		
		$ret='<div class="wb-allview  wb-allview-'.$did.' '.$pw_style.'">';
		
		$flag="";
		$numeric_flag=false;	
		$item_counter =0;	
		
		$output_html='';
				
		foreach ( $terms as $term ) {
			$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );
			if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {}
			$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );
			$count = sizeof( array_intersect( $_products_in_term, $matched_products ) );
			if ( $current_term == $term->term_id )
				continue;
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
						$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_columns:'').'">
									<div class="wb-allview-title wb-col-md-12"  id="123">123</div>'.$numeric_flag.
										'<div class="wb-row">';
						$letters .='<a class="wb-wb-allview-letters" href="#123">'.$first_letter.'</a>';
						$numeric_flag=true;
					}
					else if(!is_numeric($first_letter) ){
						$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_columns:'').'">
									<div class="wb-allview-title wb-col-md-12"  id="'.$first_letter.'">'.$first_letter.'</div>'.$numeric_flag.
										'<div class="wb-row">';
						$letters .='<a class="wb-wb-allview-letters" href="#'.$first_letter.'">'.$first_letter.'</a>';
					}
					$flag=$first_letter;
				}						
					
				$output_html.='<div class="'.(($pw_style!='wb-allview-style3')?$pw_columns:'wb-col-md-12').'">
							<div class="wb-allview-item-cnt" rel="tipsy" title="'.$term->name.$brand_count.'">
								'.$brand_img;
								if ($pw_show_title=='yes'){
									$output_html.='<div class="wb-allview-item-title">
											  <a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">'.$term->name.$brand_count.'</a>
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
						$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_columns:'').'">
									<div class="wb-allview-title wb-col-md-12" id="123">123</div>'.$numeric_flag.
										'<div class="wb-row">';
						$letters .='<a class="wb-wb-allview-letters" href="#123">'.$first_letter.'</a>';
						$numeric_flag=true;
					}
					else if(!is_numeric($first_letter) ){
						$output_html.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_columns:'').'">
									<div class="wb-allview-title wb-col-md-12" id="'.$first_letter.'">'.$first_letter.'</div>'.$numeric_flag.
										'<div class="wb-row">';
						$letters .='<a class="wb-wb-allview-letters" href="#'.$first_letter.'">'.$first_letter.'</a>';
					}
					$flag=$first_letter;
				}						
					
				$output_html.='<div class="'.(($pw_style!='wb-allview-style3')?$pw_columns:'wb-col-md-12').'">
							<div class="wb-allview-item-cnt" rel="tipsy" title="'.$term->name.$brand_count.'"><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">'.$brand_img.'</a>';
								if ($pw_show_title=='yes'){
									$output_html.='<div class="wb-allview-item-title">
											  <a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">'.$term->name.$brand_count.'</a>
										   </div>';
								}
							$output_html.='
							</div>
						</div>';
			}//end elseif $pw_featured=="no"
			$item_counter++;
		}
		
		if(trim($output_html)=='')
		{
			$output_html= __('There are no items !','woocommerce-brand');
			$ret.=$output_html. "</div><!--row -->
					</div><!-- wb-allview-cat-cnt=-->";
			//end all-brand-alphabet
			$ret.="</div>";
			echo $ret;
			exit(0);
		}

		
		
		$ret.=$output_html. "</div><!--row -->
				</div><!-- wb-allview-cat-cnt=-->";
		//end all-brand-alphabet
		$ret.="</div>";
		if ( $pw_tooltip=='yes' ){
			$ret .="
			<script type='text/javascript'>
				jQuery(function() {
				   jQuery('.wb-allview-".$did." div[rel=tipsy]').tipsy({ gravity: 's',live: true,fade:true});
				});
			</script>";
		}
		
		
		echo $ret;
		
		exit();
	}
	
	//FETCH PRODUCTS 
	add_action('wp_ajax_pw_brand_fetch_products', 'pw_brand_fetch_products');
	add_action('wp_ajax_nopriv_pw_brand_fetch_products', 'pw_brand_fetch_products');
	function pw_brand_fetch_products() {
		extract($_POST);
		
		//print_r($other_settings);
		
		extract(shortcode_atts( array(
			'pw_adv1_category' => '',
			'pw_brand' => '',
			'pw_show_image' => '',
			'pw_columns' => '',
			'pw_style' => '',
		),$other_settings));
		
		$letters='';
		$include_category=$pw_adv1_category;
		$include_brand=$pw_brand;
		$include="";		
		
		
		
		if($pw_adv1_category =="null" || $pw_adv1_category=="all" || $pw_adv1_category=="")
			$include_category="";
		
		if($pw_brand =="null" || $pw_brand=="all" || $pw_brand=="")
			$include_brand="";	
		
		$current_term_category 	= $pw_woob_category;
		$current_term_brand 	= $pw_woob_brand;
		$taxonomy 		= 'product_brand';
		$get_terms="product_brand";
		$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );
		
		
		$includes=array();

		$query_meta_query=array('relation' => 'AND');
		if(!in_array('0',$current_term_category))
		{
			$query_meta_query[] = array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'id',
							'terms' => $current_term_category
						)
			);	
		}else if($include_category!='')
		{
			$query_meta_query[] = array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'id',
							'terms' => explode(',',$include_category)
						)
			);	
		}
		
		if(!in_array('0',$current_term_brand))
		{
			$query_meta_query[] = array(
						array(
							'taxonomy' => 'product_brand',
							'field' => 'id',
							'terms' => $current_term_brand
						)
			);	
		}else if($include_brand!='')
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
		
		$ret="";
		$did=rand(0,1000);
		
		$ret='<div class="wb-allview  wb-allview-'.$did.' '.$pw_style.'">';
		
		$flag="";
		$numeric_flag=false;	
		$item_counter =0;	
		
		$output_html='';
				
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
			<div class="'.$pw_columns.'">
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
		
		if(trim($output_html)=='')
		{
			$output_html= __('There are no items !','woocommerce-brand');
			$ret.=$output_html. "
					</div><!-- wb-allview-cat-cnt=-->";
			//end all-brand-alphabet
			echo $ret;
			exit(0);
		}

		$ret.=$output_html. "
				</div><!-- wb-allview-cat-cnt=-->";
		//end all-brand-alphabet
		echo $ret;
		
		exit();
	}
	
?>