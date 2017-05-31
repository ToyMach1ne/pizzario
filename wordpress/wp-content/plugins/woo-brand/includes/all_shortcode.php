<?php
//For Other Languge
/*
*/

add_shortcode( 'pw_brand_a-z-view', 'pw_brand_a_z_view_func' );
function pw_brand_a_z_view_func( $atts, $content = null ) {
	$pw_except_brand=$pw_style=$pw_brand_list_style=$pw_hide_empty_brands=$pw_show_count=$pw_featured=$pw_scroll_height="";
	extract(shortcode_atts( array(
		'pw_style' => 'wb-filter-style1',
		'pw_brand_list_style' => 'wb-brandlist-style1',
		'pw_except_brand' => '',
		'pw_show_count'=>'5',
		'pw_featured'=>'no',
		'pw_hide_empty_brands'=>'5',
		'pw_scroll_height'=>'200',
	),$atts));
	if(get_option('pw_woocommerce_brands_show_categories')=="yes")
		$get_terms="product_cat";
	else
		$get_terms="product_brand";

	$exclude=$pw_except_brand;
	if($pw_except_brand =="null" || $pw_except_brand=="all")
		$exclude="";

	$empty_brand=$pw_hide_empty_brands;
	if($pw_hide_empty_brands =="null" || $pw_hide_empty_brands=="")
		$empty_brand=0;
	$args = array(
		'orderby'           => 'name', 
		'order'             => 'ASC',
		'hide_empty'        => $empty_brand,
		'exclude'           => $exclude, 
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
	$pw_scroll_height = ($pw_scroll_height=='')?200:$pw_scroll_height;
	$categories = get_terms( $get_terms, $args);
	$ret="";
	$did=rand(0,1000);
$sorted_cart = array();
$ret=$retal=$retcu="";
	$ret= '
	<div  class="'.$pw_style.' '.$pw_brand_list_style.'">
		<div class="wb-alphabet-table">
			<div class="wb-all-alphabet">
				<a class="wb-alphabet-item wb-alphabet-item-short_'.$did.' active-letter_'.$did.'" href="#!">ALL</a>
			</div>
			<div class="wb-other-brands">
			';
	foreach( (array) $categories as $term ) {
		//echo $term->name;
		$char=mb_substr($term->name,0,1);
		//$char=substr($term->name,0,1);
		//echo $char;
		$char=strtoupper($char);
		if (in_array($char, $sorted_cart)){
		}
		else
		{
		//	$ret.='<a class="wb-alphabet-item wb-alphabet-item-short" href="#!">'.$char.'</a>';
		//echo 'a';
			$sorted_cart[]=$char;			
		}
		
			$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
			$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
			$count="";
			if($pw_show_count=="yes")
				$count='<span class="brand-count" > ('.esc_html( $term->count ).')</span>';
				
			if($pw_featured=="yes" && $display_type==1)
			{
				$retcu.= '<div class="wb-filter-item-cnt brand-item-short"><a class="wb-filter-item" href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">' . esc_html( $term->name ) . '</a>'.$count.'</div>';
			}
			elseif($pw_featured=="no")
			{
				$retcu.= '<div class="wb-filter-item-cnt brand-item-short"><a class="wb-filter-item" href="' . ($url=="" ? get_term_link( $term->slug, $get_terms ) : $url) . '">' . esc_html( $term->name ) . '</a>'.$count.'</div>';							
			}
			
	}
	sort($sorted_cart);
	//print_r($sorted_cart);
	foreach($sorted_cart as $ar)
	{
		$retal.='<a class="wb-alphabet-item wb-alphabet-item-short_'.$did.'" href="#!">'.$ar.'</a>';
	}
	
	$ret.=$retal.'</div>
			</div>';
			
	$ret.= '<div class="eb-scrollbarcnt shortcode-scroll_'.$did.'">
					<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
					<div class="viewport" style="height:'. $pw_scroll_height .'px">
					<div class="overview">';
	$ret.=$retcu;
	
	$ret.= "</div></div></div>
		</div>";
	$ret.="<script type='text/javascript'>
	/* <![CDATA[ */                    
		jQuery(document).ready(function() {
			function init_scroll_shortcode(){
				var scrollbar_short = jQuery('.shortcode-scroll_".$did."');
				scrollbar_short.tinyscrollbar();
				var scrollbar_short = scrollbar_short.data('plugin_tinyscrollbar')
				scrollbar_short.update();
				setTimeout(function(){
					scrollbar_short.update();
				 },100); 
				return false;
			}

			function filterResults(letter){
				init_scroll_shortcode();
				if(letter=='ALL'){
					jQuery('.brand-item-short').removeClass('hidden').addClass('visible');
					return false;
				}
		
				jQuery('.brand-item-short').removeClass('visible').addClass('hidden');
				if(letter=='123'){
					var arr_0_9=['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
					jQuery('.brand-item-short').filter(function() {
						//return arr_0_9.indexOf(jQuery(this).text().charAt(0).toUpperCase()) != -1;
						return jQuery.inArray(jQuery(this).text().charAt(0).toUpperCase(),arr_0_9)!= -1;
					}).removeClass('hidden').addClass('visible');
				}else
				{
					jQuery('.brand-item-short').filter(function() {
						return jQuery(this).text().charAt(0).toUpperCase() === letter;
					}).removeClass('hidden').addClass('visible');
				}
			};
			filterResults('ALL');
			jQuery('.wb-alphabet-item-short_".$did."').on('click',function(){
				var letter = jQuery(this).text();      
				jQuery('.wb-alphabet-item-short_".$did."').removeClass('active-letter_".$did."');
				jQuery(this).addClass('active-letter_".$did."');     
				filterResults(letter);        
			});
		
		
			jQuery( '.wb-alphabet-item-short_".$did."' ).each(function() {
				var letter=jQuery(this);
				
				
				if(jQuery(this).text().toUpperCase()=='ALL')
				{
					
				}else if (jQuery(this).text()=='123')
				{
					var arr_0_9=['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
					var flag=false;
					jQuery('.brand-item-short' ).each(function() {
						if(jQuery.inArray(jQuery(this).text().charAt(0).toUpperCase(),arr_0_9)!= -1)
						{
							flag=true;
							return false;
							//confirm(letter.text());
						}
					});
					if(flag==false)
					{
						letter.addClass('wb-invis-item');
					}
					
				}else
				{
					var flag=false;
					jQuery('.brand-item-short' ).each(function() {
						if(jQuery(this).text().charAt(0).toUpperCase()==letter.text().charAt(0).toUpperCase())
						{
							flag=true;
							return false;
							//confirm(letter.text());
						}
					});
					if(flag==false)
					{
						letter.addClass('wb-invis-item');
					}
				}
			});
		});
		/* ]]>*/
	 </script>";
	 
	return $ret;
}

add_shortcode( 'pw_brand_all_views', 'pw_brand_all_views_func' );
function pw_brand_all_views_func( $atts, $content = null ) {
	$pw_list_view_layout=$pw_show_count=$pw_featured=$pw_hide_empty_brands=$pw_show_image=$pw_show_title=$pw_columns=$pw_tablet_columns=$pw_mobile_columns=$pw_style=$pw_tooltip=$pw_adv1_category=$pw_adv2_category=$pw_filter_style=$ret="";


	extract(shortcode_atts( array(
		'type' => 'simple',
		'pw_show_count' => '',
		'pw_featured' => '',
		'pw_hide_empty_brands' => '',
		'pw_show_image' => '',
		'pw_show_title' => '',
		'pw_columns'=>'wb-col-md-3',
		'pw_tablet_columns'=>'wb-col-sm-6',
		'pw_mobile_columns'=>'wb-col-xs-12',
		'pw_style' => '',
		'pw_tooltip' => '',
		'pw_adv1_category'=> '',
		'pw_filter_style'=> '',
		'pw_adv2_category'=> '',
	),$atts));
	//print_r($atts);
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
	
	return $ret;	
}

add_shortcode( 'pw_brand_carousel', 'pw_brand_carousel_func' );
function pw_brand_carousel_func( $atts, $content = null ) {
	//Add BxSlider
	wp_enqueue_style('woob-bxslider-style');	
	wp_enqueue_script('woob-bxslider-script');
	$pw_brand=$pw_show_image=$pw_tooltip=$pw_except_brand=$pw_featured=$pw_show_title=$pw_show_count=$pw_style=$pw_carousel_style=$pw_carousel_skin_style=$pw_round_corner=
	$pw_item_width=$pw_item_marrgin=$pw_slide_direction=$pw_show_pagination=$pw_show_control
	=$pw_item_per_view=$pw_item_per_slide=$pw_slide_speed=$pw_auto_play="";
	extract(shortcode_atts( array(
		'pw_brand' => '',
		'pw_except_brand' => '',
		'pw_style' => '',
		'pw_carousel_style' => '',
		'pw_carousel_skin_style' => '',
		'pw_tooltip' => '',
		'pw_round_corner' => '',
		'pw_show_image' => '',
		'pw_show_image_size' => '',
		'pw_featured' => '',
		'pw_show_title' => '',
		'pw_show_count' => '',
		'pw_item_width' => '300',
		'pw_item_marrgin' => '10',
		'pw_slide_direction' => '',
		'pw_show_pagination' => '',
		'pw_show_control' => '',
		'pw_item_per_view' => '3',
		'pw_item_per_slide' => '1',
		'pw_slide_speed' => '1',
		'pw_auto_play' => '',
	),$atts));
	//print_r($atts);
	if(get_option('pw_woocommerce_brands_show_categories')=="yes")
		$get_terms="product_cat";
	else
		$get_terms="product_brand";
	
	$exclude = array_map( 'intval', explode( ',', $pw_except_brand ) );
	//$exclude=$pw_except_brand;
	if($pw_except_brand =="null" || $pw_except_brand=="all"|| $pw_except_brand=="")
		$exclude="";
	
	$include = array_map( 'intval', explode( ',', $pw_brand ) );
	//$include=$pw_brand;
	if($pw_brand =="null" || $pw_brand=="all" || $pw_brand=="")
		$include="";
	$args = array(
		'orderby'           => 'name', 
		'order'             => 'ASC',
		'hide_empty'        => false,
		'exclude'           => $exclude, 
		'exclude_tree'      => array(), 
		'include'           => $include,
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
	$count='';
	$pw_slide_speed = (trim($pw_slide_speed)=='')?1:$pw_slide_speed;
	$pw_item_width = (trim($pw_item_width)=='')?300:$pw_item_width;
	$pw_item_marrgin = (trim($pw_item_marrgin)=='')?10:$pw_item_marrgin;
	$pw_item_per_view = (trim($pw_item_per_view)=='')?3:$pw_item_per_view;
	$pw_item_per_slide = (trim($pw_item_per_slide)=='')?1:$pw_item_per_slide;
	$pw_auto_play = ((trim($pw_auto_play)=='') || (!isset($pw_auto_play)))?'false':$pw_auto_play;
	
	$ret .= '<ul class="wb-bxslider wb-car-car  wb-carousel-layout wb-car-cnt " id="slider_'.$did.'"  style="visibility:hidden" >';
	foreach( (array) $categories as $term ) {
		$display_featured	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
		
		$url= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
		if($url=="")
			$url= get_term_link( $term->slug, $get_terms );

		$image= '';
		if($pw_show_count=="yes") $count=' ('.esc_html( $term->count).')';
		if($pw_show_image=="yes")
		{
			
			$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );			

			if ( $thumbnail_id )
			{
				if($pw_show_image_size=="full")
				{
					$image = current( wp_get_attachment_image_src( $thumbnail_id, 'full' ) );
				}
				else
				{
					$image = wp_get_attachment_thumb_url( $thumbnail_id );
				}
			}
			else
			{
				if(get_option('pw_woocommerce_brands_default_image'))
					$image=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
				else
					$image = WP_PLUGIN_URL.'/woo-brand/img/default.png';
			}
		}
			if($pw_featured=="yes" && $display_featured==1)
			{
				$ret .='<li>
					<div class="wb-car-item-cnt" rel="tipsy" title="'.$term->name.$count.'">';
				if ($image!='')	{  
					$ret .='<a href="'.$url.'">'.'<img src="'.$image.'" >'.'</a>';
				}
				if ($pw_show_title=='yes'){
					$ret.='<div class="wb-car-title"><a  href="'.$url.'" title="'.$term->name.'" >'.$term->name.'</a>'.$count.'</div>';
				}
				$ret .='</div>
				</li>';
			}
			elseif($pw_featured=="no")
			{

				$ret .='<li>
					<div class="wb-car-item-cnt" rel="tipsy" title="'.$term->name.$count.'">';
				if ($image!='')	{  
					$ret .='<a href="'.$url.'" >'.'<img src="'.$image.'" >'.'</a>';
				}
				if ($pw_show_title=='yes'){
					$ret.='<div class="wb-car-title"><a  href="'.$url.'" title="'.$term->name.'" >'.$term->name.'</a>'.$count.'</div>';
				}
				$ret .='</div>
				</li>';
			}
		
		
	}
	$ret .='</ul>';
	if ( $pw_tooltip=='yes' ){
		$ret .="
		<script type='text/javascript'>
			jQuery(function() {
			   jQuery('#slider_" . $did ." div[rel=tipsy]').tipsy({ gravity: 's',live: true,fade:true});
			   jQuery('#slider_" . $did ."').css('visibility','visible');
			});
		</script>";
	}
	$ret .="<script type='text/javascript'>
                jQuery(document).ready(function() {
					jQuery('#slider_" . $did ."').css('visibility','visible');
                    slider" . $did ." =
					 jQuery('#slider_" . $did ."').bxSlider({ 
						  mode : '".($pw_slide_direction=='vertical' ? 'vertical' : 'horizontal' )."' ,
						  touchEnabled : true ,
						  adaptiveHeight : true ,
						  slideMargin : ".$pw_item_marrgin." , 
						  wrapperClass : 'wb-bx-wrapper wb-car-car wb-car-cnt ".$pw_style." ".$pw_round_corner." ".$pw_carousel_style." ".$pw_carousel_skin_style."',
						  infiniteLoop:true,
						  pager:".($pw_show_pagination =='true'?'true':'false').",
						  controls:".($pw_show_control=='true'?'true':'false').",".
						  ($pw_slide_direction=='horizontal' ? 'slideWidth:'.$pw_item_width.',' : 'slideWidth:5000,' )."
						  minSlides: ". $pw_item_per_view.",
						  maxSlides: ". $pw_item_per_view.",
						  moveSlides: ".$pw_item_per_slide.",
						  auto: ". $pw_auto_play.",
						  pause : ". $pw_slide_speed .",
						  autoHover  : true , 
 						  autoStart: true,
						  responsive:false,
					 });";
					 if ($pw_auto_play=='true'){
					 $ret.="
						 jQuery('.wb-bx-wrapper .wb-bx-controls-direction a').click(function(){
							  slider" . $did .".startAuto();
						 });
						 jQuery('.wb-bx-pager a').click(function(){
							 var i = jQuery(this).data('slide-index');
							 slider" . $did .".goToSlide(i);
							 slider" . $did .".stopAuto();
							 restart=setTimeout(function(){
								slider" . $did .".startAuto();
								},1000);
							 return false;
						 });";
					 }
               $ret.=" });	
            </script>";
	return $ret;
}

add_shortcode( 'pw_brand_product_carousel', 'pw_brand_product_carousel_func' );
function pw_brand_product_carousel_func( $atts, $content = null ) {
	wp_enqueue_style('woob-bxslider-style');	
	wp_enqueue_script('woob-bxslider-script');
	$pw_brand=$pw_show_title=$pw_item_style=$pw_carousel_style=$pw_carousel_skin_style=$pw_title_style=$pw_item_width=$pw_item_marrgin=
	$pw_slide_direction=$pw_show_pagination=$pw_show_control=$pw_item_per_view=$pw_item_per_slide=
	$pw_slide_speed=$auto_play="";
	extract(shortcode_atts( array(
		'pw_brand' => '',
		'pw_show_title' => '',
		'pw_title_style' => '',
		'pw_item_width' => '',
		'pw_item_marrgin' => '',
		'pw_slide_direction' => '',
		'pw_item_style' => '',
		'pw_carousel_style' => '',
		'pw_carousel_skin_style' => '',
		'pw_show_pagination' => '',
		'pw_show_control' => '',
		'pw_item_per_view' => '',
		'pw_item_per_slide' => '',
		'pw_slide_speed' => '1',
		'pw_auto_play' => '',
	),$atts));
	$ret="";
	$did=rand(0,1000);
	
	if(get_option('pw_woocommerce_brands_show_categories')=="yes")
		$get_terms="product_cat";
	else
		$get_terms="product_brand";
		
	$query_args = array(
		'post_status'    => 'publish', 
		'post_type'      => 'product', 
		'tax_query' =>array(
				array(
						'taxonomy' => $get_terms,
						'field'    => 'id',
						'terms'    =>  explode(',',$pw_brand),
				),
			),
	);
	
	$pw_item_width = (trim($pw_item_width)=='')?300:$pw_item_width;
	$pw_item_marrgin = (trim($pw_item_marrgin)=='')?10:$pw_item_marrgin;
	$pw_item_per_view = (trim($pw_item_per_view)=='')?3:$pw_item_per_view;
	$pw_item_per_slide = (trim($pw_item_per_slide)=='')?1:$pw_item_per_slide;
	    ob_start();
	$products = new WP_Query($query_args);

    if ( $products->have_posts() ) : ?>
			<?php 
				$did=rand(0,1000);
				
				
				if ( $pw_show_title=='yes' ){
					$brands = array_map( 'intval', explode( ',', $pw_brand ) );
					$show_brand="";
					foreach($brands as $brand)
					{
						//$brand='<a href="">Themeforst</a>,<a href="">Codecanyon</a>';
						$r= get_term($brand, $get_terms);
						$url = get_woocommerce_term_meta( $brand, 'url', true );
						$show_brand.='<a href="'.($url=="" ? get_term_link( $r->slug, $get_terms ): $url).'">'.$r->name.'</a>,';
						//$show_brand.=$r->name;
					}
					$ret .='<div class="wb-brandpro-car-header '.$pw_title_style.'"><span>'.__('Products In','woocommerce-brands').get_option( 'pw_woocommerce_brands_base' ).'</span>'.$show_brand.'</div>';
				}
				$ret .= '<ul class="wb-bxslider wb-car-car  wb-carousel-layout wb-car-cnt  " id="slider_'.$did.'" style="visibility:hidden">';
				while ($products->have_posts())
					{
						$products->the_post();
						$product = get_product(get_the_ID());
						$title = $product->get_title();
						//$terms=get_the_terms( get_the_ID(), 'product_brand' );
						
					//	print_r($terms);
					//	echo  get_the_ID();
					//	echo '<br/>';
						$price = $product->get_price_html();
						$img=$product->get_image(); //Featured Image 
						//$img=$product->get_image(); //Featured Image 
						$size = 'shop_catalog';
						$img = get_the_post_thumbnail(get_the_ID(), $size );						
						$permalink=$product->get_permalink();
						$add_to_cart_url = $product->add_to_cart_url();
						$cat =$product->get_categories();				
						$tag =$product->get_tags();
						$sku =$product->get_sku();
						$featured =$product->is_featured();
						$on_sale =$product->is_on_sale(); // 1:0
						$stock_status =$product->is_in_stock(); //1 : in ,0 :out
						$stock_quantity =$product->get_stock_quantity();
						$show_brand=get_the_term_list( get_the_ID(), 'product_brand');
						$ret.= '<li>
									<div class="wb-brandpro-car-cnt">
										'.$img.'
										<div class="wb-brandpro-car-detail">
											<div class="wb-brandpro-car-title">
												<h3><a href="'.$permalink.'"  title="'.$title.'" >'.$title.'</a></h3>
											</div>
											<div class="wb-brandpro-car-price">'.$price.'</div>
											<div class="wb-brandpro-car-meta"><span>'.__('Categories: ','woocommerce-brands').'</span>'.$cat.'</div>
											<div class="wb-brandpro-car-meta"><span>'.__('Brands: ','woocommerce-brands').'</span>'.$show_brand.'</div>
										</div>
									</div>
								</li>';
					}
				$ret .= '</ul>';
			?>
	<?php endif;
	$ret .="
		<script type='text/javascript'>
			jQuery(document).ready(function() {
				jQuery('#slider_" . $did ."').css('visibility','visible');
				slider" . $did ." =
				 jQuery('#slider_" . $did ."').bxSlider({ 
					  mode : '".($pw_slide_direction=='vertical' ? 'vertical' : 'horizontal' )."' ,
					  touchEnabled : true ,
					  adaptiveHeight : true ,
					  slideMargin : ".$pw_item_marrgin." , 
					  wrapperClass : 'wb-bx-wrapper wb-car-cnt products ".$pw_item_style." ".$pw_carousel_style." ".$pw_carousel_skin_style."',
					  infiniteLoop:true,
					  pager:".($pw_show_pagination =='true'?'true':'false').",
					  controls:".($pw_show_control=='true'?'true':'false').",".
					  ($pw_slide_direction=='horizontal' ? 'slideWidth:'.$pw_item_width.',' : 'slideWidth:5000,' )."
					  minSlides: ". $pw_item_per_view.",
					  maxSlides: ". $pw_item_per_view.",
					  moveSlides: ".$pw_item_per_slide.",
					  auto: ". $pw_auto_play.",
					  pause : ". $pw_slide_speed .",
					  autoHover  : true , 
					  autoStart: true
				 });";
				 if ($pw_auto_play=='true'){
					 $ret.="
					 jQuery('.wb-bx-wrapper .wb-bx-controls-direction a').click(function(){
						  slider" . $did .".startAuto();
					 });
					 jQuery('.wb-bx-pager a').click(function(){
						 var i = jQuery(this).data('slide-index');
						 slider" . $did .".goToSlide(i);
						 slider" . $did .".stopAuto();
						 restart=setTimeout(function(){
							slider" . $did .".startAuto();
							},1000);
						 return false;
					 });";
				 }
				 
			$ret.="});	
		</script>";
    wp_reset_postdata();
	return  $ret ;
}



add_shortcode( 'pw_brand_product_grid', 'pw_brand_product_grid_func' );
function pw_brand_product_grid_func( $atts, $content = null ) {
global $woocommerce_loop;
	$pw_brand=$pw_show_title=$pw_title_style=$pw_columns=$pw_posts_per_page=$pw_orderby=$pw_orderby=$pw_order="";
	extract(shortcode_atts( array(
		'pw_brand' => '',
		'pw_show_title' => '',
		'pw_title_style' => '',
		'pw_columns' => '',
		'pw_posts_per_page' => '',
		'pw_orderby' => '',
		'pw_order' => '',
	),$atts));
	$ret="";
	if($pw_columns=='')
		$pw_columns=4;

	if(get_option('pw_woocommerce_brands_show_categories')=="yes")
		$get_terms="product_cat";
	else
		$get_terms="product_brand";
		
	$query_args = array(
		'post_status'    => 'publish', 
		'post_type'      => 'product', 
		'posts_per_page'      => $pw_posts_per_page, 
		'orderby' => $pw_orderby,
		'order'   => $pw_order,		
		'tax_query' =>array(
				array(
						'taxonomy' => $get_terms,
						'field'    => 'id',
						'terms'    => explode(',',$pw_brand),
				),
			),
	);
	    ob_start();
	$products = new WP_Query($query_args);
    $woocommerce_loop['columns'] = $pw_columns;
	if ( $pw_show_title=='yes' ){
		$brands = array_map( 'intval', explode( ',', $pw_brand ) );
		$show_brand="";
		foreach($brands as $brand)
		{
			//$brand='<a href="">Themeforst</a>,<a href="">Codecanyon</a>';
			$r= get_term($brand, $get_terms);
			$url = get_woocommerce_term_meta( $brand, 'url', true );
			$show_brand.='<a href="'.($url=="" ? get_term_link( $r->slug, $get_terms ) : $url).'">'.$r->name.'</a>'.' /'.' ';
			//$show_brand.=$r->name;
		}
		echo '<div class="wb-brandpro-grid-header '.$pw_title_style.'"><span>'.__('Products In ','woocommerce-brands').get_option( 'pw_woocommerce_brands_base' ).'</span>'.$show_brand.'</div>';		
	}
    if ( $products->have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		<?php endif;

    wp_reset_postdata();

    return '<div class="woocommerce columns-' . $pw_columns . '">' . ob_get_clean() . '</div>';
}

add_shortcode( 'pw_brand_filter_brand', 'pw_brand_filter_brand_func' );
function pw_brand_filter_brand_func( $atts, $content = null ) {
	$pw_adv1_category=$pw_brand=$pw_show_image=$pw_columns=$pw_tablet_columns=$pw_mobile_columns=$pw_style="";
	extract(shortcode_atts( array(
		'pw_adv1_category' => '',
		'pw_brand' => '',
		'pw_show_image' => '',
		'pw_columns'=>'wb-col-md-3',
		'pw_tablet_columns'=>'wb-col-sm-6',
		'pw_mobile_columns'=>'wb-col-xs-12',
		'pw_show_price' => '',
	),$atts));
	require plugin_dirname_pw_woo_brand.'/includes/product-list/adv-product-1.php';
	/*
	if($pw_category=="" || $pw_category=="0" || $pw_category=="null")
		return;
	$pw_category = array_map( 'intval', explode( ',', $pw_category ) );
	$cat="<ul>";
	$brand='';
	foreach ($pw_category as $catf)
	{
		$display_cat=get_term_by('id', $catf, 'product_cat');
		$cat.="<li>".$display_cat->name;
		$pw_brand	= get_woocommerce_term_meta( $catf, '_wc_brand_category');
		if(is_array($pw_brand))
		{
			$brand.='<ul class="">';
			foreach ($pw_brand as $brandf)
			{
				$display_brand=get_term_by('id', $brandf, 'product_brand');
				$brand.='<li>'.$display_brand->name.'</li>';
			}
			$brand.='</ul>';
		}
		$cat.='</li>';
	}
	$cat.="</ul>";
	
	echo $cat.'<br>'.$brand;
	
	
	//For style 3
	$pw_brand=$pw_category;
	$matched_products = get_posts(
		array(
			'post_type' 	=> 'product',
			'numberposts' 	=> -1,
			'post_status' 	=> 'publish',
			'fields' 		=> 'ids',
			'no_found_rows' => true,
			'tax_query' => 
						array(
						'relation' => 'AND',
							array(
									'taxonomy' => 'product_brand',
									'field'    => 'id',
									'terms'    => array($pw_brand),
							),
							array(
									'taxonomy' => 'product_cat',
									'field'    => 'id',
									'terms'    => array($pw_category),
							),
						),
		)
	);
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
		echo $title.'<br/>'.$price.'<br>'.$permalink.'<br>'.$add_to_cart_url.'<br>'.$cat.'<br>------------<br>';
	}
	*/
	
	return $ret;
}
add_shortcode( 'pw_brand_thumbnails', 'pw_brand_thumbnails_func' );
function pw_brand_thumbnails_func( $atts, $content = null ) {
		$pw_except_brand=$pw_style=$pw_round_corner=$pw_brand=
		$pw_columns=$pw_tablet_columns=$pw_mobile_columns=$pw_count_of_number=$pw_hide_empty_brands=$pw_show_image_size=
		$order_by=$pw_show_title=$pw_show_count=$pw_tooltip=$pw_featured="";
		extract(shortcode_atts( array(
			'pw_brand' => '1',
			'pw_except_brand' => '1',
			'pw_style' => '',
			'pw_round_corner' => '',
			'pw_tooltip' => '',
			'pw_featured' => '',
			'pw_count_of_number' => '',
			'pw_hide_empty_brands' => '',
			'pw_show_image_size' => '',
			'pw_show_title' => '',
			'pw_show_count' => '',
			'pw_order_by' => 'name',
			'pw_columns'=>'wb-col-md-3',
			'pw_tablet_columns'=>'wb-col-sm-6',
			'pw_mobile_columns'=>'wb-col-xs-12',
		),$atts));
		$exclude = array_map( 'intval', explode( ',', $pw_except_brand ) );
		//print_r($exclude);
		$order = $pw_order_by == 'name' ? 'asc' : 'desc';
		$include = array_map( 'intval', explode( ',', $pw_brand ) );
		if($pw_brand =="null" || $pw_brand=="all" || $pw_brand=="")
			$include="";
			
		if(get_option('pw_woocommerce_brands_show_categories')=="yes")
			$get_terms="product_cat";
		else
			$get_terms="product_brand";
		($pw_hide_empty_brands=="0" || $pw_hide_empty_brands==""  ? $pw_hide_empty_brands=0 : $pw_hide_empty_brands=1);

		$brands = get_terms( $get_terms, array( 'hide_empty' => $pw_hide_empty_brands,'include' =>$include, 'orderby' => $pw_order_by, 'exclude' => $exclude, 'number' => $pw_count_of_number, 'order' => $order ) );

		if ( ! $brands ) 
			return;
		
		$did=rand(0,1000);	
		$ret='<div class="wb-row wb-thumb-wrapper wb-thumb-'. $did .' ' .$pw_style.' ' .$pw_round_corner.'">';
		foreach ( $brands as $index => $brand ) : 
			$count='';
			if($pw_show_count=="yes") $count=' ('.esc_html( $brand->count).')';
			
			$thumbnail_id = get_woocommerce_term_meta( $brand->term_id, 'thumbnail_id', true );			

			$url = get_woocommerce_term_meta( $brand->term_id, 'url', true );
			$thumbnail='';
			if ( $thumbnail_id )
			{
				if($pw_show_image_size=="full")
				{
					$thumbnail = current( wp_get_attachment_image_src( $thumbnail_id, 'full' ) );
				}
				else
				{
					$thumbnail = wp_get_attachment_thumb_url( $thumbnail_id );
				}
			}
				
			//$thumbnail = pw_woocommerc_brans_Wc_Brands::get_brand_thumbnail_url( $brand->term_id, apply_filters( 'woocommerce_brand_thumbnail_size', 'brand-thumb' ) );
			
			if ( ! $thumbnail ){
				if(get_option('pw_woocommerce_brands_default_image'))
					$thumbnail=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
				else
					$thumbnail = WP_PLUGIN_URL.'/woo-brand/img/default.png';
			}
			$display_type	= get_woocommerce_term_meta( $brand->term_id, 'featured', true );
			if($pw_featured=="yes" && $display_type==1)
			{
				$ret.= '
					<div class="'.$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns.'">
						<div class="wb-thumb-cnt" rel="tipsy" title="'.$brand->name.$count.'">
							<a href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'">
								<img src="'.$thumbnail.'" alt="'.$brand->name.'" />
							</a>';
						if ($pw_show_title=='yes'){
							$ret.= '<div class="wb-thumb-title"><a  href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'" title="'.$brand->name.'" >'.$brand->name.'</a>'.$count.'</div>';
						}
				$ret.= '		
					</div>
				</div>';
			}
			elseif($pw_featured=="no")
			{
				$ret.= '
					<div class="'.$pw_mobile_columns.' '.$pw_tablet_columns.' '.$pw_columns.'">
						<div class="wb-thumb-cnt" rel="tipsy" title="'.$brand->name.$count.'">
							<a href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'" >
								<img src="'.$thumbnail.'" alt="'.$brand->name.'" />
							</a>';
						if ($pw_show_title=='yes'){
							$ret.= '<div class="wb-thumb-title"><a  href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'" title="'.$brand->name.'" >'.$brand->name.'</a>'.$count.'</div>';
						}
				$ret.= '		
					</div>
				</div>';
			}		
		endforeach;
		$ret.='</div>';
		if ( $pw_tooltip=='yes' ){
			$ret.= "
			<script type='text/javascript'>
				jQuery(function() {
				   jQuery('.wb-thumb-".$did." div[rel=tipsy]').tipsy({ gravity: 's',live: true,fade:true});
				});
			</script>";
		} 			
	/*	$pw_woocommerc_brans_Wc_Brands = untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
		woocommerce_get_template( 'brand-thumbnails.php', array(
			'brands'	=> $brands,
			'columns'	=> $pw_columns,
			'pw_show_title'	=> $pw_show_title,
			'pw_featured'	=> $pw_featured,
			'pw_show_count'	=> $pw_show_count,
			'pw_style'=>$pw_style,
			'pw_round_corner'=>$pw_round_corner,
			'pw_tooltip'=>$pw_tooltip,
		), 'woocommerce-brands', $pw_woocommerc_brans_Wc_Brands . '/templates/' );
		*/
		return $ret;
}

/*
add_shortcode( 'pw_brand_thumbnails_auto', 'auto_pw_brand_thumbnails_func' );
function auto_pw_brand_thumbnails_func( $atts, $content = null ) 
{	
		global $post;
		$id=$post->ID;
		$brandsp = wp_get_post_terms( $post->ID, "product_brand", array( "fields" => "ids" ) );	
	
		$pw_except_brand=$pw_style=$pw_round_corner=$pw_brand=
		$pw_columns=$pw_count_of_number=$pw_hide_empty_brands=
		$order_by=$pw_show_title=$pw_show_count=$pw_tooltip=$pw_featured="";
		extract(shortcode_atts( array(
			'pw_style' => '',
			'pw_round_corner' => '',
			'pw_tooltip' => '',
			'pw_featured' => '',
			'pw_count_of_number' => '',
			'pw_hide_empty_brands' => '',
			'pw_show_title' => '',
			'pw_show_count' => '',
			'pw_order_by' => '',
			'pw_columns'=>'',
		),$atts));	

		$order = $pw_order_by == 'name' ? 'asc' : 'desc';
		$include = array_map( 'intval', explode( ',', $pw_brand ) );
		if($pw_brand =="null" || $pw_brand=="all" || $pw_brand=="")
			$include="";

		$get_terms="product_brand";
		($pw_hide_empty_brands=="no" || $pw_hide_empty_brands==""  ? $pw_hide_empty_brands=0 : $pw_hide_empty_brands=1);

		$brands = get_terms( $get_terms, array( 'hide_empty' => $pw_hide_empty_brands,'include' =>$brandsp, 'orderby' => $pw_order_by, 'number' => $pw_count_of_number, 'order' => $order ) );
	
	

		$did=rand(0,1000);	
		$ret='<div class="wb-row wb-thumb-wrapper wb-thumb-'. $did .' ' .$pw_style.' ' .$pw_round_corner.'">';
		foreach ( $brands as $index => $brand ) : 
			$count='';
			if($pw_show_count=="yes") $count=' ('.esc_html( $brand->count).')';
			
			$thumbnail_id = get_woocommerce_term_meta( $brand->term_id, 'thumbnail_id', true );
			$url = get_woocommerce_term_meta( $brand->term_id, 'url', true );
			$thumbnail='';
			if ( $thumbnail_id )
				$thumbnail = current( wp_get_attachment_image_src( $thumbnail_id, 'full' ) );				
				
			
			if ( ! $thumbnail ){
				if(get_option('pw_woocommerce_brands_default_image'))
					$thumbnail=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
				else
					$thumbnail = WP_PLUGIN_URL.'/woo-brands/img/default.png';
			}
			$display_type	= get_woocommerce_term_meta( $brand->term_id, 'featured', true );
			if($pw_featured=="yes" && $display_type==1)
			{
				$ret.= '
					<div class="'.$pw_columns.' wb-col-xs-12">
						<div class="wb-thumb-cnt" rel="tipsy" title="'.$brand->name.$count.'">
							<a href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'">
								<img src="'.$thumbnail.'" alt="'.$brand->name.'" />
							</a>';
						if ($pw_show_title=='yes'){
							$ret.= '<div class="wb-thumb-title"><a  href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'" title="'.$brand->name.'" >'.$brand->name.'</a>'.$count.'</div>';
						}
				$ret.= '		
					</div>
				</div>';
			}
			elseif($pw_featured=="no")
			{
				$ret.= '
					<div class="'.$pw_columns.' wb-col-xs-12">
						<div class="wb-thumb-cnt" rel="tipsy" title="'.$brand->name.$count.'">
							<a href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'" >
								<img src="'.$thumbnail.'" alt="'.$brand->name.'" />
							</a>';
						if ($pw_show_title=='yes'){
							$ret.= '<div class="wb-thumb-title"><a  href="'.($url=="" ? get_term_link( $brand->slug, $get_terms ) : $url).'" title="'.$brand->name.'" >'.$brand->name.'</a>'.$count.'</div>';
						}
				$ret.= '		
					</div>
				</div>';
			}		
		endforeach;
		$ret.='</div>';
		if ( $pw_tooltip=='yes' ){
			$ret.= "
			<script type='text/javascript'>
				jQuery(function() {
				   jQuery('.wb-thumb-".$did." div[rel=tipsy]').tipsy({ gravity: 's',live: true,fade:true});
				});
			</script>";
		} 			
		return $ret;
}
*/

?>