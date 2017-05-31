<?php
if(get_option('pw_woocommerce_brands_show_categories')=="yes")
	$get_terms="product_cat";
else
	$get_terms="product_brand";
$empty_brand=$pw_hide_empty_brands;
if($pw_hide_empty_brands =="null" || $pw_hide_empty_brands=="")
	$empty_brand=0;
$args = array(
	'orderby'           => 'name', 
	'order'             => 'ASC',
	'hide_empty'        => $empty_brand,
	'exclude'           => array(), 
	'exclude_tree'      => array(), 
	'include'           => array(),
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
$categories = get_terms( $get_terms, $args);
$ret="";
$did=rand(0,1000);

$ret='<div class="wb-allview  wb-allview-'.$did.' '.$pw_style.'">';

$flag="";
$numeric_flag=false;	
$item_counter =0;				
foreach( (array) $categories as $term ) {
	
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
		$brand_count .=' ('.esc_html( $term->count).')';
		
	
	
	if($get_terms=="product_cat"){
	/*		if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
			{
				//end alphabet-cnt
				if($flag!="") $ret.="</div>";
				
				if($pw_show_image=="yes")
					$ret.='<div class="alphabet-cnt-img">';
				else
					$ret.='<div class="alphabet-cnt">';	
				//if first_latter is numberic
				if(is_numeric($first_letter) && $numeric_flag==false)
				{
					$ret.='<h2 class="alphabet-h2">123</h2>'.$numeric_flag;
					$numeric_flag=true;
				}
				else if(!is_numeric($first_letter) )
					$ret.='<h2 class="alphabet-h2">'.$first_letter.'</h2>'.$numeric_flag;
				$flag=$first_letter;
			}											
			$ret.='<a href="' . home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) . '"><div class="brand-item-cnt">'.$content.'</div></a>';						
	*/
	}
	else
	{
		//if  show only featured in shortcode
		if($pw_featured=="yes" && $display_type==1){
			if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
			{
				//end alphabet-cnt
				if($flag!="") $ret.="</div><!--row -->
										</div><!-- wb-allview-cat-cnt=-->";
				
				//if first_latter is numberic
				if(is_numeric($first_letter) && $numeric_flag==false)
				{
					$ret.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12">123</div>'.$numeric_flag.
									'<div class="wb-row">';
					$numeric_flag=true;
				}
				else if(!is_numeric($first_letter) )
					$ret.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12">'.$first_letter.'</div>'.$numeric_flag.
									'<div class="wb-row">';
				$flag=$first_letter;
			}						
				
			$ret.='<div class="'.(($pw_style!='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'wb-col-md-12').'">
						<div class="wb-allview-item-cnt" rel="tipsy" title="'.$term->name.$brand_count.'">
							 <a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$brand_img.'</a>';
							if ($pw_show_title=='yes'){
								$ret.='<div class="wb-allview-item-title">
										  <a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$term->name.$brand_count.'</a>
									   </div>';
							}
						$ret.='
						</div>
					</div>';
		}
		elseif($pw_featured=="no"){
			if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
			{
				//end alphabet-cnt
				if($flag!="") $ret.="</div><!--row -->
										</div><!-- wb-allview-cat-cnt=-->";
				
				//if first_latter is numberic
				if(is_numeric($first_letter) && $numeric_flag==false)
				{
					$ret.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12">123</div>'.$numeric_flag.
									'<div class="wb-row">';
					$numeric_flag=true;
				}
				else if(!is_numeric($first_letter) )
					$ret.='<div class="wb-allview-cat-cnt '.(($pw_style=='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'').'">
								<div class="wb-allview-title wb-col-md-12">'.$first_letter.'</div>'.$numeric_flag.
									'<div class="wb-row">';
				$flag=$first_letter;
			}						
				
			$ret.='<div class="'.(($pw_style!='wb-allview-style3')?$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns:'wb-col-md-12').'">
						<div class="wb-allview-item-cnt" rel="tipsy" title="'.$term->name.$brand_count.'">
							<a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$brand_img.'</a>';
							if ($pw_show_title=='yes'){
								$ret.='<div class="wb-allview-item-title">
										  <a href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">'.$term->name.$brand_count.'</a>
									   </div>';
							}
						$ret.='
						</div>
					</div>';
		}//end elseif $pw_featured=="no"
	}//end else $get_terms=="product_cat"
	$item_counter++;
}//end for each

$ret.="</div><!--row -->
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
?>