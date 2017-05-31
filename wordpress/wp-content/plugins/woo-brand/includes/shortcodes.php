<?php
add_shortcode( 'pw_brands', 'pw_brands_shortcode' );
function pw_brands_shortcode( $atts, $content = null ) {

		$brands_attr = shortcode_atts( array(
			'type' => 'ver-carousel',
			'carousel_count_items'=>'5',
			'carousel_item_per_view'=>'1',
			'display_image'=>'yes',
			'featured'=>'no',
			'show_title'=>'yes',
			'show_count'=>'yes',
			'carousel_align'=>'center',
			'scroll_height'=>'150',
			'show_image'=>'no'			
		), $atts );
		
		if(get_option('pw_woocommerce_brands_show_categories')=="yes")
			$get_terms="product_cat";
		else
			$get_terms="product_brand";
			
		if(esc_attr($brands_attr['type']=="a-z-view") || esc_attr($brands_attr['type']=="all-views") )
			$categories = get_terms( $get_terms, 'orderby=name&hide_empty=0');
		else
			$categories = get_terms( $get_terms, 'orderby=name&hide_empty=0' );
			
		if(esc_attr($brands_attr['type']=="ver-carousel")  || esc_attr($brands_attr['type']=="hor-carousel"))
		{
				
				//wp_enqueue_script('modernizr.custom');
				wp_enqueue_script('woob-carousel-script');
		}
		$ret="";
		$did=rand(0,1000);
		switch (esc_attr($brands_attr['type']))
		{
			case "ver-carousel" :
			?>
<script type='text/javascript'>
            /* <![CDATA[  */                  
                jQuery(document).ready(function() {
					jQuery('#carouselver_<?php echo $did;?>').slick({ 
						  slidesToShow: parseInt(<?php echo $brands_attr['carousel_item_per_view'];?>),
						  slidesToScroll: 1,
						  autoplay: true,
						  speed: 1000,
						  vertical : true,
						});
                }); 
                /* ]]> */
             </script>               
            <?php
		/*		$ret.='<div class="scroll-img-ver-cnt scroll-img-ver-cnt-align-'.$brands_attr['carousel_align'].'"><div class="scroll-img-ver" id="carouselver_'.$did.'">';
				$cunter=0;
				foreach( (array) $categories as $term ) {
					if($cunter>=$brands_attr['carousel_count_items'])
						break;				
					$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
					$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
					$image 			= '';
					$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
					if ( $thumbnail_id && $brands_attr['display_image']=="yes" )
						$image = wp_get_attachment_thumb_url( $thumbnail_id );
					else
					{
						if(get_option('pw_woocommerce_brands_default_image'))
							$image=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
						else
							$image = WP_PLUGIN_URL.'/woo-brands/img/default.png';							
					}
					$count="";
					if($brands_attr['show_count']=='yes')
						$count='<span class="brand-count" > ( '.esc_html( $term->count ).' )</span>';
					$title="";
					if($brands_attr['show_title']=="yes")
						$title=esc_html( $term->name );	
												
					if($brands_attr['featured']=="yes" && $display_type==1)
					{
						$cunter++;	
						$ret.= '<div><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '" ><img src="'. $image .'"   alt="'.esc_html($term->name).'" /></a><div class="car-brand-title"><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '" >'.$title.' '.$count.'</a></div></div>';
					}
					elseif($brands_attr['featured']=="no")
					{
						$cunter++;
						$ret.= '<div><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '" ><img src="'. $image .'"   alt="'.esc_html($term->name).'" /></a><div class="car-brand-title"><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">'.$title.' '.$count.'</a></div></div>';						
					}
				}
				$ret.= '</div></div>';		
*/				
				break;
				
			case "hor-carousel":
			?>
			<script type='text/javascript'>
            /* <![CDATA[ */                    
                jQuery(document).ready(function() {
						jQuery('#carouselhor_<?php echo $did;?>').slick({ 
						  slidesToShow: parseInt(<?php echo $brands_attr['carousel_item_per_view'];?>),
						  slidesToScroll: 1,
						  autoplay: true,
						  speed: 1000,
						  dots: true,
						  responsive: [
							{
							  breakpoint: 768,
							  settings: {
  							    dots: true,
								slidesToShow: 3
							  }
							},
							{
							  breakpoint: 480,
							  settings: {
								arrows: false,
								dots: true,
								slidesToShow: 1
							  }
							}
						  ]
						});
                });
                /* ]]> */
             </script>               
            <?php
				$ret.= '<div class="scroll-img-hor" id="carouselhor_'.$did.'">';
				//echo '<ul id="carouselhor" class="elastislide-list" title="'. $brands_attr['carousel_item_per_view'] .'">';
				$cunter=0;
				foreach( (array) $categories as $term ) {
					if($cunter==$brands_attr['carousel_count_items'])
						break;				
					$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
					$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
					$image 			= '';
					$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
					if ( $thumbnail_id && $brands_attr['display_image']=="yes")
						$image = wp_get_attachment_thumb_url( $thumbnail_id );
					else
					{
						if(get_option('pw_woocommerce_brands_default_image'))
							$image=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
						else
							$image = WP_PLUGIN_URL.'/woo-brands/img/default.png';
					}
					$count="";
					if($brands_attr['show_count']=="yes")
						$count='<span class="brand-count" > ( '.esc_html( $term->count ).' )</span>';
						
					$title="";
					if($brands_attr['show_title']=="yes")
						$title=esc_html( $term->name );						
												
					if($brands_attr['featured']=="yes" && $display_type==1)
					{
						$cunter++;
						$ret.= '<div><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '"><img src="'. $image .'"  alt="'.esc_html($term->name).'" ></a><div class="car-brand-title"><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">'.$title.' '.$count.'</a></div></div>';
					}
					elseif($brands_attr['featured']=="no")
					{	
						$cunter++;
						$ret.= '<div><a href="' .($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '"><img src="'. $image .'"  alt="'.esc_html($term->name).'" /></a><div class="car-brand-title"><a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">'.$title.' '.$count.'</a></div></div>';
					}
				}
				//echo '</ul>';
				$ret.= '</div>';
				break;
				
			case "a-z-view":
			?>
					<script type='text/javascript'>
                    /* <![CDATA[ */                    
						jQuery(document).ready(function() {
							function init_scroll_shortcode(){
								var $scrollbar_short = jQuery('.shortcode-scroll');
								$scrollbar_short.tinyscrollbar();
								var scrollbar_short = $scrollbar_short.data("plugin_tinyscrollbar")
								scrollbar_short.update();
								setTimeout(function(){
									scrollbar_short.update();
								 },100); 
								return false;
							}
	
							function filterResults(letter){
								init_scroll_shortcode();
								if(letter=="ALL"){
									jQuery('.brand-item-short').removeClass('hidden').addClass('visible');
									return false;
								}
						
								jQuery('.brand-item-short').removeClass('visible').addClass('hidden');
								if(letter=="123"){
									var arr_0_9=["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
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
							jQuery('.alphabet-item-short').on('click',function(){
								var letter = jQuery(this).text();      
								jQuery('.alphabet-item-short').removeClass('active-letter');
								jQuery(this).addClass('active-letter');     
								filterResults(letter);        
							});
						
						
							jQuery( ".alphabet-item-short" ).each(function() {
								var letter=jQuery(this);
								
								
								if(jQuery(this).text().toUpperCase()=='ALL')
								{
									
								}else if (jQuery(this).text()=='123')
								{
									var arr_0_9=["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
									var flag=false;
									jQuery(".brand-item-short" ).each(function() {
										if(jQuery.inArray(jQuery(this).text().charAt(0).toUpperCase(),arr_0_9)!= -1)
										{
											flag=true;
											return false;
											//confirm(letter.text());
										}
									});
									if(flag==false)
									{
										letter.addClass('grey');
									}
									
								}else
								{
									var flag=false;
									jQuery(".brand-item-short" ).each(function() {
										if(jQuery(this).text().charAt(0).toUpperCase()==letter.text().charAt(0).toUpperCase())
										{
											flag=true;
											return false;
											//confirm(letter.text());
										}
									});
									if(flag==false)
									{
										letter.addClass('grey');
									}
								}
							});
						});
						/* ]]> */
                     </script>   
                     <?php
	            $ret= '<div class="brand-alphabet-table">
						<div class="all-alphabet"><a class="alphabet-item-short active-letter" href="#!">ALL</a></div>
                       	<div class="other-brands">
                            <a class="alphabet-item-short" href="#!">A</a>
                            <a class="alphabet-item-short" href="#!">B</a>
                            <a class="alphabet-item-short" href="#!">C</a>
                            <a class="alphabet-item-short" href="#!">D</a>
                            <a class="alphabet-item-short" href="#!">E</a>
                            <a class="alphabet-item-short" href="#!">F</a>
                            <a class="alphabet-item-short" href="#!">G</a>
                            <a class="alphabet-item-short" href="#!">H</a>
                            <a class="alphabet-item-short" href="#!">I</a>
                            <a class="alphabet-item-short" href="#!">J</a>
                            <a class="alphabet-item-short" href="#!">K</a>
                            <a class="alphabet-item-short" href="#!">L</a>
                            <a class="alphabet-item-short" href="#!">M</a>
                            <a class="alphabet-item-short" href="#!">N</a>
                            <a class="alphabet-item-short" href="#!">O</a>
                            <a class="alphabet-item-short" href="#!">P</a>
                            <a class="alphabet-item-short" href="#!">Q</a>
                            <a class="alphabet-item-short" href="#!">R</a>
                            <a class="alphabet-item-short" href="#!">S</a>
                            <a class="alphabet-item-short" href="#!">T</a>
                            <a class="alphabet-item-short" href="#!">U</a>
                            <a class="alphabet-item-short" href="#!">V</a>
                            <a class="alphabet-item-short" href="#!">W</a>
                            <a class="alphabet-item-short" href="#!">X</a>
                            <a class="alphabet-item-short" href="#!">Y</a>
                            <a class="alphabet-item-short" href="#!">Z</a>
                            <a class="alphabet-item-short number-order" href="#!">123</a>
                        </div>
                   </div>';
				$ret.= '<div class="eb-scrollbarcnt shortcode-scroll">
								<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
								<div class="viewport" style="height:200px">
								<div class="overview">';
					foreach( (array) $categories as $term ) {
						$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
						$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
						$count="";
						if($brands_attr['show_count']=="yes")
							$count='<span class="brand-count" > ( '.esc_html( $term->count ).' )</span>';
							
						if($brands_attr['featured']=="yes" && $display_type==1)
						{
							$ret.= '<a class="brand-item-short brand-item" href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">' . esc_html( $term->name ) .$count. '</a>';
						}
						elseif($brands_attr['featured']=="no")
						{
							$ret.= '<a class="brand-item-short brand-item" href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '">' . esc_html( $term->name ) .$count. '</a>';							
						}
					}
					$ret.= "</div></div></div> 
					";
				break;			
			case "all-views":
				
				$ret='<div class="all-brand-alphabet">';
				if($brands_attr['show_image']=="yes")
					$ret='<div class="all-brand-alphabet-img">';
				
				
				$flag="";
				$numeric_flag=false;					
				foreach( (array) $categories as $term ) {
					$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
					$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
					$first_letter=strtoupper(substr(esc_html( $term->name ),0,1));
					if(!is_numeric($first_letter))
						$numeric_flag=false;

					//if show image in shortcode
					if($brands_attr['show_image']=="yes")
					{
						//get image from tbl woocommerce term_meta
						$image 			= '';
						$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
						if ( $thumbnail_id && $brands_attr['display_image']=="yes" )
							$image = wp_get_attachment_thumb_url( $thumbnail_id );
						else
						{
							if(get_option('pw_woocommerce_brands_default_image'))
								$image=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
							else
								$image = WP_PLUGIN_URL.'/woo-brand/img/default.png';
						}
						
						$content='<img src="'.$image.'"  alt="'.esc_html( $term->name).'" />';
						$content=$term->name;
					}
					else
						$content=esc_html( $term->name );//if show n't image  in shortcode
						
					//if show count in shortcode
					if($brands_attr['show_image']=="yes" && $brands_attr['show_count']=="yes")
						$content .='<span class="brand-item-count brand-count-img"> '.esc_html( $term->count).' </span>';
					elseif($brands_attr['show_count']=="yes")
						$content .='<span class="brand-item-count"> ('.esc_html( $term->count).') </span>';
					if($get_terms=="product_cat"){
							if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
							{
								//end alphabet-cnt
								if($flag!="") $ret.="</div>";
								
								if($brands_attr['show_image']=="yes")
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
					}
					else
					{
						//if  show only featured in shortcode
						if($brands_attr['featured']=="yes" && $display_type==1){
							if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
							{
								//end alphabet-cnt
								if($flag!="") $ret.="</div>";
								
								if($brands_attr['show_image']=="yes")
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
							$ret.='<a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '"><div class="brand-item-cnt">'.$content.'</div></a>';
						}
						elseif($brands_attr['featured']=="no"){
							if(($flag!=$first_letter || is_numeric($first_letter)) && $numeric_flag==false)
							{
								//end alphabet-cnt
								if($flag!="") $ret.="</div>";
								if($brands_attr['show_image']=="yes")
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
							$ret.='<a href="' . ($url=="" ? home_url() .'/?'.$get_terms.'=' . esc_html( $term->slug ) : $url) . '"><div class="brand-item-cnt">'.$content.'</div></a>';
						}
					}
				}
				//end last alphabet-cnt
				$ret.="</div>";
				//end all-brand-alphabet
				$ret.="</div>";
			break;
		}

		return $ret;
//		return true;		
	}	


add_shortcode( 'pw_product_brands', 'pw_product_brands_shortcode' );
function pw_product_brands_shortcode( $atts ) {

	extract( shortcode_atts( array(
		  'show_empty' 		=> true,
		  'column'			=> 4,
		  'hide_empty'		=> 0,
		  'orderby'			=> 'name',
		  'exclude'			=> '',
		  'count_items'			=> ''
	 ), $atts ) );

	$exclude = array_map( 'intval', explode(',', $exclude) );
	$order = $orderby == 'name' ? 'asc' : 'desc';

	$brands = get_terms( 'product_brand', array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'exclude' => $exclude, 'number' => $count_items, 'order' => $order ) );

	if ( ! $brands )
		return;

	ob_start();
	
		if(version_compare(WC()->version, '3.0.0', '>=')){
			$wc_get_3='wc_get_template';
		}
		else
		{
			$wc_get_3='woocommerce_get_template';
		}
	$wc_get_3( 'brand-thumbnails.php', array(
		'brands'	=> $brands,
		'columns'	=> $column
	), 'woo-brand', WP_PLUGIN_URL . '/woo-brand/templates/' );

	return ob_get_clean();
}
?>