<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class pw_brands_admin_extra_button {

	/**
	 * Constructor
	 */
	public function __construct() {
			add_action( 'wp_footer', array( $this, 'pw_brands_eb_front_end' ) ); 
	}


	/* create two taxonomies, genres and writers for the post type "book" */
	public function pw_brands_eb_front_end()
	{
			        global $post;
/**/	
		$show=false;
		$show_pages=get_option('pw_woocommerce_brands_show_pages_extra','none');
		if(is_array($show_pages))
		{
			foreach($show_pages as $page ){
				if($page=='none'){
					return '';
				}
				if($page=='all'){
					$show=true;
					break ;
				}				
				elseif ( $page=='shop' && ($post->post_type == 'product' || is_singular( 'product' ) || is_tax( 'product_brand' ) ))
				{
					$show=true;
					break ;
				}
/*				elseif ($page=='single' && is_singular( 'product' ) )
				{
					$show=true;
					break ;
				}
				elseif ( $page=='brand' && is_tax( 'product_brand' ) )
				{
					$show=true;
					break ;
				}
*/
			}
		}
/*		if ( $post->post_type !== 'product' )
			return '';
		if ( is_singular( 'product' ) )
			return '';
		
		if ( ! is_tax( 'product_brand' ) )
			return '';			
	*/	
			if($show)
			{
				
				$pw_stick_dir = (get_option('pw_woocommerce_brands_position_extra')=='left')?'pw-left-stick':'pw-right-stick';
				$pw_content_dir = (get_option('pw_woocommerce_brands_position_extra')=='left')?'pw-content-left':'pw-content-right';
				
				if(get_option('pw_woocommerce_brands_show_categories')=="yes")
					$get_terms="product_cat";
				else
					$get_terms="product_brand";				
				
				$sorted_cart = array();
				$ret=$retal=$retcu="";
				
				$tax=(get_option('pw_woocommerce_brands_text')=="" ? "Brand":get_option('pw_woocommerce_brands_text'));
				$categories = get_terms( $get_terms, 'orderby=name&hide_empty=0' );
				$ret.= '<div id="pw_stick_brands" class="pw-stick  pw-stick-light '.$pw_stick_dir.' "><span class="pw-title">'.$tax;
				$ret.= '</span></div>';
				$ret.= '<div class="pw-content pw-content-4 pw_stick_brands pw-content-light '.$pw_content_dir.' ">
						<div class="pw-content-close"></div>';
				$ret.='
                <div  class="'.get_option('pw_woocommerce_brands_style_extra').'">
                    <div class="wb-alphabet-table">
                        <div class="wb-all-alphabet">
                            <a class="wb-alphabet-item wb-alphabet-item-eb active-letter-eb" href="#!">ALL</a>
                        </div>
                        <div class="wb-other-brands">';
						

                        foreach( (array) $categories as $term ) {
							$char=mb_substr($term->name,0,1);
							$char=strtoupper($char);
							if (in_array($char, $sorted_cart))
							{
							}
							else
								$sorted_cart[]=$char;

                            $count="";
							$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));
							$count='<span class="brand-count" > ('.esc_html( $term->count ).')</span>';				
                            $retcu.='<div class="wb-filter-item-cnt brand-item-eb"><a class="wb-filter-item" href="' . ($url=="" ? get_term_link( $term->slug, $get_terms )  : $url) . '">' . esc_html( $term->name ).'</a>'.$count.'</div>';	
                        }
					sort($sorted_cart);
					foreach($sorted_cart as $ar)
					{
						$retal.='<a class="wb-alphabet-item wb-alphabet-item-eb" href="#!">'.$ar.'</a>';
					}						
                    $ret.=$retal.'</div>
							</div>';						
					$ret.='
					 <div class="eb-scrollbarcnt eb-scroll">
								<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>';
					$ret.='<div class="viewport" style="height:200px">
								<div class="overview">';
					$ret.=$retcu;
					
                        $ret.= "</div>";
                    $ret.= '</div>
						  </div>
						</div>
					</div>';
				echo $ret;
			}
	}	

}
new pw_brands_admin_extra_button();
?>