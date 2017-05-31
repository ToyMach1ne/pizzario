<?php
if(!function_exists('dhvc_is_editor')){
	function dhvc_is_editor(){
		return  (isset($_GET['vc_action']) && $_GET['vc_action'] === 'vc_inline') ||
            (isset($_GET['vc_inline']) || isset($_POST['vc_inline']));
	}
}
if(!function_exists('dhvc_is_inline')){
	function dhvc_is_inline(){
		return isset($_GET['vceditor']) && $_GET['vceditor'] === 'true';
	}
}


function dhvc_woo_css_minify( $css ) {
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
	$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
	$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	return trim( $css );
}


function dhvc_woo_the_shop_page_content(){
	global $dhvc_woo_shop_page;
	if(!empty($dhvc_woo_shop_page)){
		$content = $dhvc_woo_shop_page->post_content;
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = apply_filters('dhvc_woo_the_shop_page_content',$content);
		echo $content;
	}
}


function dhvc_woo_the_category_page_content(){
	global $dhvc_woo_category_page;
	if(!empty($dhvc_woo_category_page)){
		$content = $dhvc_woo_category_page->post_content;
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = apply_filters('dhvc_woo_the_category_page_content',$content);
		echo $content;
	}
}

function dhvc_woo_the_tag_page_content(){
	global $dhvc_woo_tag_page;
	if(!empty($dhvc_woo_tag_page)){
		$content = $dhvc_woo_tag_page->post_content;
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = apply_filters('dhvc_woo_the_tag_page_content',$content);
		echo $content;
	}
}

function dhvc_woo_the_brand_page_content(){
	global $dhvc_woo_brand_page;
	if(!empty($dhvc_woo_brand_page)){
		$content = $dhvc_woo_brand_page->post_content;
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = apply_filters('dhvc_woo_the_brand_page_content',$content);
		echo $content;
	}
}

function dhvc_woo_get_id(){
	$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	$token = '';
	$id = session_id();
	for ($i = 0; $i < 32; ++$i)
	{
		$token .= $chars[(rand(0, $max))];
	}
	return 'dhvc_woo_'.substr(md5($token.$id),0,10);
}

function dhvc_woo_setting_field_id($settings, $value){
	if(empty($value)){
	 	$value = dhvc_woo_get_id();
	 }
	return '<input name="'.$settings['param_name'].'" class="wpb_vc_param_value dhvc-woo-param-value wpb-textinput" type="hidden" value="'.$value.'"/>';
}


function dhvc_woo_setting_field_heading($settings, $value,$dependency=''){
	return '<div style="background: none repeat scroll 0 0 #E1E1E1;font-size: 14px;font-weight: bold;padding: 5px;">'.$value.'</div>';
}

function dhvc_woo_setting_field_products_ajax($settings, $value,$dependency=''){
	$product_ids = array();
	
	if(!empty($value))
		$product_ids = array_map( 'absint', explode( ',', $value ) );
	
	$output = '<select id= "'.$settings['param_name'].'" '.$dependency.' multiple="multiple" class="dhvc-woo-select dhvc_ajax_products">';
	if(!empty($product_ids)){
		foreach ( $product_ids as $product_id ) {
			$product = get_product( $product_id );
			$output .= '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . wp_kses_post( dhvc_woo_get_product_formatted_name($product) ) . '</option>';
				
		}
	}
	$output .= '</select>';
	$output .='<input id= "'.$settings['param_name'].'" type="hidden" class="wpb_vc_param_value dhvc-woo-param-value wpb-textinput" name="'.$settings['param_name'].'" value="'.$value.'" />';
	return $output;
}

if (!function_exists( 'dhvc_woo_resize' )) {
	function dhvc_woo_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
		// this is an attachment, so we have the ID
		if ( $attach_id ) {
			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$actual_file_path = get_attached_file( $attach_id );
			// this is not an attachment, let's use the image url
		} else if ( $img_url ) {
			$file_path = parse_url( $img_url );
			$actual_file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
			$actual_file_path = ltrim( $file_path['path'], '/' );
			$actual_file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
			$orig_size = getimagesize( $actual_file_path );
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}
		$file_info = pathinfo( $actual_file_path );
		$extension = '.'. $file_info['extension'];

		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

		$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {

			// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
			if ( file_exists( $cropped_img_path ) ) {
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
				$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
				);
				return $vt_image;
			}

			// $crop = false
			if ( $crop == false ) {
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;

				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

					$vt_image = array (
							'url' => $resized_img_url,
							'width' => $proportional_size[0],
							'height' => $proportional_size[1]
					);
					return $vt_image;
				}
			}

			// no cache files - let's finally resize it
			$img_editor =  wp_get_image_editor($actual_file_path);

			if ( is_wp_error($img_editor) || is_wp_error( $img_editor->resize($width, $height, $crop)) ) {
				return array (
						'url' => '',
						'width' => '',
						'height' => ''
				);
			}

			$new_img_path = $img_editor->generate_filename();

			if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
				return array (
						'url' => '',
						'width' => '',
						'height' => ''
				);
			}

			if(!is_string($new_img_path)) {
				return array (
						'url' => '',
						'width' => '',
						'height' => ''
				);
			}

			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

			// resized output
			$vt_image = array (
					'url' => $new_img,
					'width' => $new_img_size[0],
					'height' => $new_img_size[1]
			);
			return $vt_image;
		}

		// default output - without resizing
		$vt_image = array (
				'url' => $image_src[0],
				'width' => $image_src[1],
				'height' => $image_src[2]
		);
		return $vt_image;
	}
}

function dhvc_woo_getImageBySize( $params = array( 'post_id' => NULL, 'attach_id' => NULL, 'thumb_size' => 'thumbnail', 'class' => '' )){
	//array( 'post_id' => $post_id, 'thumb_size' => $grid_thumb_size )
	if ( (!isset($params['attach_id']) || $params['attach_id'] == NULL) && (!isset($params['post_id']) || $params['post_id'] == NULL) ) return;
	$post_id = isset($params['post_id']) ? $params['post_id'] : 0;
	
	if ( $post_id ) $attach_id = get_post_thumbnail_id($post_id);
	else $attach_id = $params['attach_id'];
	
	$thumb_size = $params['thumb_size'];
	$thumb_class = (isset($params['class']) && $params['class']!='') ? $params['class'].' ' : '';
	
	global $_wp_additional_image_sizes;
	$thumbnail = '';
	
	if ( is_string($thumb_size) && ((!empty($_wp_additional_image_sizes[$thumb_size]) && is_array($_wp_additional_image_sizes[$thumb_size])) || in_array($thumb_size, array('thumbnail', 'thumb', 'medium', 'large', 'full') ) ) ) {
		//$thumbnail = get_the_post_thumbnail( $post_id, $thumb_size );
		$thumbnail = wp_get_attachment_image( $attach_id, $thumb_size, false, array('class' => $thumb_class.'attachment-'.$thumb_size) );
		//TODO APPLY FILTER
	} elseif( $attach_id ) {
		if ( is_string($thumb_size) ) {
			preg_match_all('/\d+/', $thumb_size, $thumb_matches);
			if(isset($thumb_matches[0])) {
				$thumb_size = array();
				if(count($thumb_matches[0]) > 1) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][1]; // height
				} elseif(count($thumb_matches[0]) > 0 && count($thumb_matches[0]) < 2) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][0]; // height
				} else {
					$thumb_size = false;
				}
			}
		}
		if (is_array($thumb_size)) {
			// Resize image to custom size
			$p_img = dhvc_woo_resize($attach_id, null, $thumb_size[0], $thumb_size[1], true);
			$alt = trim(strip_tags( get_post_meta($attach_id, '_wp_attachment_image_alt', true) ));
	
			if ( empty($alt) ) {
				$attachment = get_post($attach_id);
				$alt = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
			}
			if ( empty($alt) )
				$alt = trim(strip_tags( $attachment->post_title )); // Finally, use the title
			if ( $p_img ) {
				$img_class = '';
				//if ( $grid_layout == 'thumbnail' ) $img_class = ' no_bottom_margin'; class="'.$img_class.'"
				$thumbnail = '<img class="'.$thumb_class.'" src="'.$p_img['url'].'" width="'.$p_img['width'].'" height="'.$p_img['height'].'" alt="'.$alt.'" />';
				//TODO: APPLY FILTER
			}
		}
	}
	
	$p_img_large = wp_get_attachment_image_src($attach_id, 'large' );
	return array( 'thumbnail' => $thumbnail, 'p_img_large' => $p_img_large );
}

function dhvc_woo_json_headers() {
	header( 'Content-Type: application/json; charset=utf-8' );
}

function dhvc_woo_search_products (){
	dhvc_woo_json_headers();
	$term = (string) sanitize_text_field( stripslashes( $_GET['term'] ) );
	
	
	if (empty($term)) die();
	
	$post_types = array('product', 'product_variation');
	
	if ( is_numeric( $term ) ) {
	
		$args = array(
				'post_type'			=> $post_types ,
				'post_status'	 	=> 'publish',
				'posts_per_page' 	=> -1,
				'post__in' 			=> array(0, $term),
				'fields'			=> 'ids'
		);
	
		$args2 = array(
				'post_type'			=> $post_types,
				'post_status'	 	=> 'publish',
				'posts_per_page' 	=> -1,
				'post_parent' 		=> $term,
				'fields'			=> 'ids'
		);
	
		$args3 = array(
				'post_type'			=> $post_types,
				'post_status' 		=> 'publish',
				'posts_per_page' 	=> -1,
				'meta_query' 		=> array(
						array(
								'key' 	=> '_sku',
								'value' => $term,
								'compare' => 'LIKE'
						)
				),
				'fields'			=> 'ids'
		);
	
		$posts = array_unique(array_merge( get_posts( $args ), get_posts( $args2 ), get_posts( $args3 ) ));
	
	} else {
	
		$args = array(
				'post_type'			=> $post_types,
				'post_status' 		=> 'publish',
				'posts_per_page' 	=> -1,
				's' 				=> $term,
				'fields'			=> 'ids'
		);
	
		$args2 = array(
				'post_type'			=> $post_types,
				'post_status' 		=> 'publish',
				'posts_per_page' 	=> -1,
				'meta_query' 		=> array(
						array(
								'key' 	=> '_sku',
								'value' => $term,
								'compare' => 'LIKE'
						)
				),
				'fields'			=> 'ids'
		);
	
		$posts = array_unique(array_merge( get_posts( $args ), get_posts( $args2 ) ));
	
	}
	
	$found_products = array();
	
	if ( $posts ) foreach ( $posts as $post ) {
	
		$product = get_product( $post );
		
		$found_products[ $post ] = dhvc_woo_get_product_formatted_name($product);
	
	}
	
	echo json_encode( $found_products );
	
	die();
}

add_action('wp_ajax_dhvc_woo_search_products', 'dhvc_woo_search_products');

function dhvc_woo_get_product_formatted_name(WC_Product $product){
	if ( $product->get_sku() ) {
		$identifier = $product->get_sku() ;
	} else {
		$identifier = '#' . $product->id;
	}
	
	return sprintf( __( '%s &ndash; %s', DHVC_WOO ), $identifier, $product->get_title() );
}

function dhvc_woo_setting_field_categories($settings, $value,$dependency=''){
	$category_ids = explode(',',$value);
	$args = array(
			'orderby' => 'name',
			'hide_empty' => 0,
	);
	$categories = get_terms( 'product_cat', $args );
	$output = '<select '.$dependency.' id= "'.$settings['param_name'].'" multiple="multiple" class="dhvc-woo-select chosen_select_nostd">';
	$output .= '<option value="">' . esc_html(__('-- Select --',DHVC_WOO) ) . '</option>';
	if( ! empty($categories)){
		foreach ($categories as $cat):
		$output .= '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $category_ids ), true, false ) . '>' . esc_html( $cat->name ) . '</option>';
		endforeach;
	}
	$output .= '</select>';
	$output .='<input id= "'.$settings['param_name'].'" type="hidden" class="wpb_vc_param_value dhvc-woo-param-value wpb-textinput" name="'.$settings['param_name'].'" value="'.$value.'" />';
	return $output;
}
function dhvc_woo_setting_field_brands($settings, $value,$dependency=''){
	$brands_ids = explode(',',$value);
	$args = array(
			'orderby' => 'name',
			'hide_empty' => 0,
	);
	$brands = get_terms( 'product_brand', $args );
	$output = '<select '.$dependency.' id= "'.$settings['param_name'].'" multiple="multiple" class="dhvc-woo-select chosen_select_nostd">';
	if( ! empty($brands) && !is_wp_error($brands)){
		foreach ($brands as $brand):
		$output .= '<option value="' . esc_attr( $brand->term_id ) . '"' . selected( in_array( $brand->term_id, $brands_ids ), true, false ) . '>' . esc_html( $brand->name ) . '</option>';
		endforeach;
	}
	$output .= '</select>';
	$output .='<input id= "'.$settings['param_name'].'" type="hidden" class="wpb_vc_param_value dhvc-woo-param-value wpb-textinput" name="'.$settings['param_name'].'" value="'.$value.'" />';
	return $output;
}
function dhvc_woo_setting_field_tags($settings, $value,$dependency=''){
	$tags_ids = explode(',',$value);
	$args = array(
			'orderby' => 'name',
			'hide_empty' => 0,
	);
	$tags = get_terms( 'product_tag', $args );
	$output = '<select '.$dependency.' id= "'.$settings['param_name'].'" multiple="multiple" class="dhvc-woo-select chosen_select_nostd">';
	if( ! empty($tags)){
		foreach ($tags as $tag):
		$output .= '<option value="' . esc_attr( $tag->term_id ) . '"' . selected( in_array( $tag->term_id, $tags_ids ), true, false ) . '>' . esc_html( $tag->name ) . '</option>';
		endforeach;
	}
	$output .= '</select>';
	$output .='<input id= "'.$settings['param_name'].'" type="hidden" class="wpb_vc_param_value dhvc-woo-param-value wpb-textinput" name="'.$settings['param_name'].'" value="'.$value.'" />';
	return $output;
}

function dhvc_woo_setting_field_attributes($settings, $value,$dependency=''){
	$attributes_ids = explode(',', $value);
	$attributes = wc_get_attribute_taxonomies();
	$output = '<select '.$dependency.' id= "'.$settings['param_name'].'" multiple="multiple" class="dhvc-woo-select chosen_select_nostd">';
	if($attributes){
		foreach ($attributes as $tax):
			if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ){
				$output .= '<optgroup label="'.$tax->attribute_name.'">';
					$args = array(
							'orderby' => 'name'
					);
					if ( $name = wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
						$terms = get_terms( $name, $args );
						if(!empty($terms)){
							foreach ($terms as $term){
								$v =  $name.'|'.$term->slug;
								$output .= '<option value="' . esc_attr($v) . '"' . selected( in_array($v, $attributes_ids ), true, false ) . '>' . esc_html( $term->name ) . '</option>';
							}
						}
					}
				$output .='</optgroup>';
			}
		endforeach;
	}
	$output .= '</select>';
	$output .='<input id= "'.$settings['param_name'].'" type="hidden" class="wpb_vc_param_value dhvc-woo-param-value wpb-textinput" name="'.$settings['param_name'].'" value="'.$value.'" />';
	return $output;
}


function dhvc_woo_orderby($orderby,$r,$atts){
	global $woocommerce, $wp_query;
	ob_start();
	?>
	<form class="dhvc-woo-ordering" method="get">
		<select name="orderby" class="orderby">
			<?php
				$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
					'menu_order' => __( 'Default sorting', DHVC_WOO ),
					'popularity' => __( 'Sort by popularity', DHVC_WOO ),
					'rating'     => __( 'Sort by average rating', DHVC_WOO ),
					'date'       => __( 'Sort by newness', DHVC_WOO ),
					'price'      => __( 'Sort by price: low to high', DHVC_WOO ),
					'price-desc' => __( 'Sort by price: high to low', DHVC_WOO )
				) );
	
				if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' )
					unset( $catalog_orderby['rating'] );
	
				foreach ( $catalog_orderby as $id => $name )
					echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
			?>
		</select>
		<?php
			// Keep query string vars intact
			foreach ( $_GET as $key => $val ) {
				if ( 'orderby' === $key || 'submit' === $key )
					continue;
				
				if ( is_array( $val ) ) {
					foreach( $val as $innerVal ) {
						echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
					}
				
				} else {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
				}
			}
		?>
	</form>
	<?php
	echo apply_filters('dhvc_woo_orderby', ob_get_clean(),$orderby,$r,$atts);
}

function dhvc_result_count($r,$atts){
	global $woocommerce;
	ob_start();
	?>
	<p class="dhvc-woo-result-count">
		<?php
		$paged    = max( 1, $r->get( 'paged' ) );
		$per_page = $r->get( 'posts_per_page' );
		$total    = $r->found_posts;
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $r->get( 'posts_per_page' ) * $paged );
	
		if ( 1 == $total ) {
			_e( 'Showing the single result', DHVC_WOO );
		} elseif ( $total <= $per_page || -1 == $per_page ) {
			printf( __( 'Showing all %d results', DHVC_WOO ), $total );
		} else {
			printf( _x( 'Showing %1$d–%2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', DHVC_WOO ), $first, $last, $total );
		} 
		?>
	</p>
	<?php
	echo apply_filters('dhvc_result_count', ob_get_clean(),$r,$atts);
}


function dhvc_woo_pagination($args = array(),$query = null) {
	global $wp_rewrite, $wp_query;
	
	if (empty($query) ) {
		$query = $wp_query;
	}
	
	if ( 1 >= $query->max_num_pages )
		return;
	
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	
	$max_num_pages = intval( $query->max_num_pages );
	
	$defaults = array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => $max_num_pages,
			'current' => $current,
			'prev_next' => true,
			'prev_text' => __( '&larr;',DHVC_WOO ),
			'next_text' => __( '&rarr;',DHVC_WOO ), 
			'show_all' => false,
			'end_size' => 1,
			'mid_size' => 1,
			'add_fragment' => '',
			'type' => 'plain',
			'before' => '<div class="pagination dhvc-woo-pagination">', 
			'after' => '</div>',
			'echo' => true,
			'use_search_permastruct' => true
	);
	
	if( $wp_rewrite->using_permalinks() && ! is_search() )
		$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	
	if ( is_search() )
		$defaults['use_search_permastruct'] = false;
	
	if ( is_search() ) {
		$search_permastruct = $wp_rewrite->get_search_permastruct();
		if ( ! empty( $search_permastruct ) ) {
			$base = get_search_link();
			$base = add_query_arg( 'paged', '%#%', $base );
			$defaults['base'] = $base;
		}
	}
	
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters('dhvc_woo_pagination_args',$args);
	
	if ( 'array' == $args['type'] )
		$args['type'] = 'plain';
	$pattern = '/\?(.*?)\//i';
	
	preg_match( $pattern, $args['base'], $raw_querystring );
	if(!empty($raw_querystring)){
		if( $wp_rewrite->using_permalinks() && $raw_querystring )
			$raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
		@$args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
	}
	$page_links = paginate_links( $args );
	
	$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );
	
	$page_links = $args['before'] . $page_links . $args['after'];
	$page_links = apply_filters('dhvc_woo_pagination', $page_links);
	if ( $args['echo'] )
		echo $page_links;
	else
		return $page_links;
}

function dhvc_woo_params(){
	return apply_filters('dhvc_woo_params',array(
		array (
			"type" => "dhvc_woo_field_id",
			"param_name" => "id"
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Heading", DHVC_WOO ),
				'admin_label'=>true,
				"param_name" => "heading"
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Heading Color", DHVC_WOO ),
				"param_name" => "heading_color",
				"value" => "#47A3DA",
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Heading Font Size", DHVC_WOO ),
				"param_name" => "heading_font_size",
				"value" => '20px',
				"description" => __ ( "Enter your custom size. Example: 20px.", DHVC_WOO ),
		),
		array (
				"type" => "dhvc_woo_field_heading",
				"value" => "Query Options",
				"param_name" => "query_options"
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Query Type", DHVC_WOO ),
				"param_name" => "query_type",
				'admin_label'=>true,
				"value" => array (
						__ ( 'Build Query', DHVC_WOO ) => '1',
						__ ( 'Use Main Query', DHVC_WOO ) => '2',
						__ ( 'Up-Sells Product', DHVC_WOO ) => 'upsell',
						__ ( 'Cross-Sells Product', DHVC_WOO ) => 'crosssell',
						__ ( 'Related Product', DHVC_WOO ) => 'related',
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_products_ajax",
				"heading" => __("Products",DHVC_WOO),
				"param_name" => "products",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_exclude_products_ajax",
				"heading" => __("Exclude Products",DHVC_WOO),
				"param_name" => "exclude_products",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_categories",
				"class" => "",
				"heading" => __ ( "Categories", DHVC_WOO ),
				"param_name" => "category",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_exclude_categories",
				"class" => "",
				"heading" => __ ( "Exclude Categories", DHVC_WOO ),
				"param_name" => "exclude_category",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_brands",
				"class" => "",
				"heading" => __ ( "Brands", DHVC_WOO ),
				"param_name" => "brand",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_exclude_brands",
				"class" => "",
				"heading" => __ ( "Exclude Brands", DHVC_WOO ),
				"param_name" => "exclude_brand",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_tags",
				"class" => "",
				"heading" => __ ( "Tags", DHVC_WOO ),
				"param_name" => "tag",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_exclude_tags",
				"class" => "",
				"heading" => __ ( "Exclude Tags", DHVC_WOO ),
				"param_name" => "exclude_tag",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dhvc_woo_field_attributes",
				"class" => "",
				"heading" => __ ( "Attributes", DHVC_WOO ),
				"param_name" => "attribute",
				"dependency" => array (
						'element' => "query_type",
						'value' => array (
								'1',
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Products Per Page", DHVC_WOO ),
				"param_name" => "posts_per_page",
				'admin_label'=>true,
				"value" => 8,
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','upsell','crosssell','related'
					)
				),
				"description"=>__('number of post to show per page. If value -1 to show all posts',DHVC_WOO)
		)
		,
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Products Per Row", DHVC_WOO ),
				"param_name" => "post_per_row",
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','2','upsell','crosssell','related'
					)
				),
				'admin_label'=>true,
				"value" => array (
						__ ( '2', DHVC_WOO ) => '2',
						__ ( '3', DHVC_WOO ) => '3',
						__ ( '4', DHVC_WOO ) => '4',
						__ ( '5', DHVC_WOO ) => '5',
						__ ( '6', DHVC_WOO ) => '6'
				)
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Show", DHVC_WOO ),
				"param_name" => "show",
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','upsell','crosssell','related'
					)
				),
				"value" => array (
						__ ( 'All Products', DHVC_WOO ) => '',
						__ ( 'Featured Products', DHVC_WOO ) => 'featured',
						__ ( 'On-sale Products', DHVC_WOO ) => 'onsale'
				),
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Products Ordering", DHVC_WOO ),
				"param_name" => "orderby",
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','upsell','crosssell','related'
					)
				),
				'admin_label'=>true,
				"value" => array (
						__ ( 'Publish Date', DHVC_WOO ) => 'date',
						__ ( 'Modified Date', DHVC_WOO ) => 'modified',
						__ ( 'Random', DHVC_WOO ) => 'rand',
						__ ( 'Alphabetic', DHVC_WOO ) => 'title',
						__ ( 'Comment Count', DHVC_WOO ) => 'comment_count',
						__ ( 'Price', DHVC_WOO ) => 'price',
						__ ( 'Sales', DHVC_WOO ) => 'sales',
						__ ( 'Popularity',DHVC_WOO)=>'popularity',
						__ ( 'Rating', DHVC_WOO ) => 'rating',
				),
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				'admin_label'=>true,
				"heading" => __ ( "Ascending or Descending", DHVC_WOO ),
				"param_name" => "order",
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','upsell','crosssell','related'
					)
				),
				"description" => __ ( 'After the Sort Order is selected, the products will be sorted by this condition', DHVC_WOO ),
				"value" => array (
						__ ( 'Ascending', DHVC_WOO ) => 'ASC',
						__ ( 'Descending', DHVC_WOO ) => 'DESC'
				),
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','upsell','crosssell','related'
					)
				),
				"heading" => __ ( "Hide free products", DHVC_WOO ),
				"param_name" => "hide_free",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '1'
				)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Show hidden products", DHVC_WOO ),
				"param_name" => "show_hidden",
				"dependency" => array (
					'element' => "query_type",
					'value' => array (
						'1','upsell','crosssell','related'
					)
				),
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
		array (
				"type" => "dhvc_woo_field_heading",
				"value" => "Style Options",
				"param_name" => "style_options"
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Display type", DHVC_WOO ),
				"param_name" => "display",
				"value" => array (
						__ ( 'Grid', DHVC_WOO ) => 'grid',
						__ ( 'List', DHVC_WOO ) => 'list',
						// 								__ ( 'Table', DHVC_WOO ) => 'table',
						__ ( 'Masonry Grid', DHVC_WOO ) => 'masonry',
						__ ( 'Carousel', DHVC_WOO ) => 'carousel'
				)
		),
		array (
			'save_always'=>true,
			"type" => "dropdown",
			"class" => "",
			"heading" => __ ( "Use default style of Theme", DHVC_WOO ),
			"param_name" => "use_default_style_of_theme",
			"value" => array (
				__ ( 'No', DHVC_WOO ) => '0',
				__ ( 'Yes', DHVC_WOO ) => '1'
			),
			'description'=>__('User default style of Theme (only work with Grid layout)',DHVC_WOO),
			"dependency" => array (
				'element' => "display",
				'value' => array (
					'grid',
				)
			)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide result count", DHVC_WOO ),
				"param_name" => "hide_result_count",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '1'
				),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'grid',
								'list',
								'table'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Ordering List", DHVC_WOO ),
				"param_name" => "hide_ordering_list",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '1'
				),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'grid',
								'list',
								'table'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Show Pagination G^F^X^F^U^L^L^.^N^E^T", DHVC_WOO ),
				"param_name" => "show_grid_pagination",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '1'
				),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'grid',
								'list',
								'table'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Masonry Filters", DHVC_WOO ),
				"param_name" => "show_masonry_filter",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Masonry Filters Background", DHVC_WOO ),
				"param_name" => "masonry_filters_background",
				"value" => "#ffffff",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Masonry Filters Selected Background", DHVC_WOO ),
				"param_name" => "masonry_filters_selected_background",
				"value" => "#47A3DA",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Masonry Filters Color", DHVC_WOO ),
				"param_name" => "masonry_filters_color",
				"value" => "#666666",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Masonry Filters Selected Color", DHVC_WOO ),
				"param_name" => "masonry_filters_selected_color",
				"value" => "#ffffff",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Masonry Filters Border Color", DHVC_WOO ),
				"param_name" => "masonry_filters_border_color",
				"value" => "#ffffff",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Masonry Filters Selected Border Color", DHVC_WOO ),
				"param_name" => "masonry_filters_selected_border_color",
				"value" => "#47A3DA",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Masonry Gutter", DHVC_WOO ),
				"param_name" => "masonry_gutter",
				"value" => 10,
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'masonry'
						)
				)
		),
		array (
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Carousel Arrows", DHVC_WOO ),
				"param_name" => "hide_carousel_arrows",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Show Carousel Pagination", DHVC_WOO ),
				"param_name" => "show_carousel_pagination",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '1'
				),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Background", DHVC_WOO ),
				"param_name" => "carousel_arrow_background",
				"value" => "#CFCDCD",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Hover Background", DHVC_WOO ),
				"param_name" => "carousel_arrow_hover_background",
				"value" => "#47A3DA",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Color", DHVC_WOO ),
				"param_name" => "carousel_arrow_color",
				"value" => "#ffffff",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Hover Color", DHVC_WOO ),
				"param_name" => "carousel_arrow_hover_color",
				"value" => "#ffffff",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Size", DHVC_WOO ),
				"param_name" => "carousel_arrow_size",
				"value" => '24px',
				"description" => __ ( "Enter your custom size. Example: 24px.", DHVC_WOO ),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Front Size", DHVC_WOO ),
				"param_name" => "carousel_arrow_front_size",
				"value" => "12px",
				"description" => __ ( "Enter your custom size. Example: 12px.", DHVC_WOO ),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Carousel Arrows Border Radius", DHVC_WOO ),
				"param_name" => "carousel_arrow_border_radius",
				"value" => '3px',
				"description" => __ ( "Enter your custom border radius. Example: 3px.", DHVC_WOO ),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Carousel Pagination Background", DHVC_WOO ),
				"param_name" => "carousel_pagination_background",
				"value" => "#869791",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Carousel Pagination Active Background", DHVC_WOO ),
				"param_name" => "carousel_pagination_active_background",
				"value" => "#47A3DA",
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Carousel Pagination Size", DHVC_WOO ),
				"param_name" => "carousel_pagination_size",
				"value" => '12px',
				"description" => __ ( "Enter your custom size. Example: 12px.", DHVC_WOO ),
				"dependency" => array (
						'element' => "display",
						'value' => array (
								'carousel'
						)
				)
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Item Border Style", DHVC_WOO ),
				"param_name" => "item_border_style",
				"value" => array (
						__ ( 'Solid', DHVC_WOO ) => 'solid',
						__ ( 'Dashed', DHVC_WOO ) => 'dashed',
						__ ( 'Dotted', DHVC_WOO ) => 'dotted',
						__ ( 'None', DHVC_WOO ) => 'none'
				),

		)
		,
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Item Border Color", DHVC_WOO ),
				"param_name" => "item_border_color",
				"value" => "#e1e1e1",

		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Item Border Width", DHVC_WOO ),
				"param_name" => "item_border_width",
				"value" => "1px",
				"description" => __ ( "Enter your custom item border width . Example: 5px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Row Separator Color", DHVC_WOO ),
				"param_name" => "row_separator_color",
				"value" => "#e1e1e1",

		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Row Separator Height", DHVC_WOO ),
				"param_name" => "row_separator_height",
				"value" => "20px",
				"description" => __ ( "Enter your custom row separator height . Example: 20px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Row Separator Border Style", DHVC_WOO ),
				"param_name" => "row_separator_border_style",
				"value" => array (
						__ ( 'Solid', DHVC_WOO ) => 'solid',
						__ ( 'Dashed', DHVC_WOO ) => 'dashed',
						__ ( 'Dotted', DHVC_WOO ) => 'dotted',
						__ ( 'None', DHVC_WOO ) => 'none'
				)
		)
		,
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Thumbnail", DHVC_WOO ),
				"param_name" => "hide_thumbnail",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
		array (
			'save_always'=>true,
			"type" => "checkbox",
			"class" => "",
			"heading" => __ ( "Hover Thumbnail Effects", DHVC_WOO ),
			"param_name" => "hover_thumbnail",
			"value" => array (
				__ ( 'Yes, please', DHVC_WOO ) => '1'
			)
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Thumbnail Background Color", DHVC_WOO ),
				"param_name" => "thumbnail_background_color",
				"value" => "#ffffff"
		)
		,
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Thumbnail Border Style", DHVC_WOO ),
				"param_name" => "thumbnail_border_style",
				"value" => array (
						__ ( 'Solid', DHVC_WOO ) => 'solid',
						__ ( 'Dashed', DHVC_WOO ) => 'dashed',
						__ ( 'Dotted', DHVC_WOO ) => 'dotted',
						__ ( 'None', DHVC_WOO ) => 'none'
				)
		)
		,
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Thumbnail Border Color", DHVC_WOO ),
				"param_name" => "thumbnail_border_color",
				"value" => "#e1e1e1"
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Thumbnail Border Width", DHVC_WOO ),
				"param_name" => "thumbnail_border_width",
				"value" => "0px",
				"description" => __ ( "Enter your custom thumbnail border width . Example: 2px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Thumbnail Border Radius", DHVC_WOO ),
				"param_name" => "thumbnail_border_radius",
				"value" => "0px",
				"description" => __ ( "Enter your custom thumbnail border radius . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Thumbnail Padding", DHVC_WOO ),
				"param_name" => "thumbnail_padding",
				"value" => '0',
				"description" => __ ( "Enter your custom thumbnail padding . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Thumbnail Margin", DHVC_WOO ),
				"param_name" => "thumbnail_margin",
				"value" => '0',
				"description" => __ ( "Enter your custom thumbnail margin . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Thumbnail Width", DHVC_WOO ),
				"param_name" => "thumbnail_width",
				"description" => __( "Enter your custom thumbnail width . Example: 200.", DHVC_WOO ),
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Thumbnail Height", DHVC_WOO ),
				"param_name" => "thumbnail_height",
				"description" => __( "Enter your custom thumbnail height . Example: 200.", DHVC_WOO ),
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Title", DHVC_WOO ),
				"param_name" => "hide_title",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Title Align", DHVC_WOO ),
				"param_name" => "title_align",
				"value" => array (
						__ ( 'Center', DHVC_WOO ) => 'center',
						__ ( 'Left', DHVC_WOO ) => 'left',
						__ ( 'Right', DHVC_WOO ) => 'right'
				)
		)
		,
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Title Color", DHVC_WOO ),
				"param_name" => "title_color",
				"value" => "#47A3DA"
		)
		,
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Title Hover Color", DHVC_WOO ),
				"param_name" => "title_hover_color",
				"value" => "#98D2F7"
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Title Font Size", DHVC_WOO ),
				"param_name" => "title_font_size",
				"value" => '14px',
				"description" => __ ( "Enter your custom title font size . Example: 20px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Title Padding", DHVC_WOO ),
				"param_name" => "title_padding",
				"value" => '0',
				"description" => __ ( "Enter your custom title padding . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Title Margin", DHVC_WOO ),
				"param_name" => "title_margin",
				"value" => '0',
				"description" => __ ( "Enter your custom title margin . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Show Excerpt", DHVC_WOO ),
				"param_name" => "show_excerpt",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '1'
				)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Excerpt Words", DHVC_WOO ),
				"param_name" => "excerpt_length",
				"value" => '15',
				"description" => __ ( "Excerpt max words", DHVC_WOO )
		)
		,
		
		array (
				'save_always'=>true,
				"type" => "dropdown",
				"class" => "",
				"heading" => __ ( "Excerpt Align", DHVC_WOO ),
				"param_name" => "excerpt_align",
				"value" => array (
						__ ( 'Center', DHVC_WOO ) => 'center',
						__ ( 'Left', DHVC_WOO ) => 'left',
						__ ( 'Right', DHVC_WOO ) => 'right'
				)
		)
		,
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Excerpt Color", DHVC_WOO ),
				"param_name" => "excerpt_color",
				"value" => "#333333"
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Excerpt Font Size", DHVC_WOO ),
				"param_name" => "excerpt_font_size",
				"value" => '12px',
				"description" => __ ( "Enter your custom excerpt font size . Example: 14px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Excerpt Padding", DHVC_WOO ),
				"param_name" => "excerpt_padding",
				"value" => '0',
				"description" => __ ( "Enter your custom excerpt padding . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Excerpt Margin", DHVC_WOO ),
				"param_name" => "excerpt_margin",
				"value" => '0',
				"description" => __ ( "Enter your custom excerpt margin . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Price", DHVC_WOO ),
				"param_name" => "hide_price",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
		array (
				'save_always'=>true,	
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Price Color", DHVC_WOO ),
				"param_name" => "price_color",
				"value" => "#47A3DA"
		),
		array (
				'save_always'=>true,
				"type" => "colorpicker",
				"class" => "",
				"heading" => __ ( "Non-discount Price Color", DHVC_WOO ),
				"param_name" => "no_discount_price_color",
				"value" => "#333333"
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Price Font Size", DHVC_WOO ),
				"param_name" => "price_font_size",
				"value" => '14px',
				"description" => __ ( "Enter your custom price font size . Example: 20px.", DHVC_WOO )
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Non-discount Price Font Size", DHVC_WOO ),
				"param_name" => "no_discount_price_font_size",
				"value" => '12px',
				"description" => __ ( "Enter your custom price font size . Example: 14px.", DHVC_WOO )
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Price Padding", DHVC_WOO ),
				"param_name" => "price_padding",
				"value" => '0',
				"description" => __ ( "Enter your custom price padding . Example: 10px 10px 10px 10px.", DHVC_WOO )
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Price Margin", DHVC_WOO ),
				"param_name" => "price_margin",
				"value" => '0',
				"description" => __ ( "Enter your custom price margin . Example: 10px 10px 10px 10px.", DHVC_WOO )
		)
		,
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Add to cart", DHVC_WOO ),
				"param_name" => "hide_addtocart",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
// 		array (
// 				"type" => "colorpicker",
// 				"class" => "",
// 				"heading" => __ ( "Add To Cart Color", DHVC_WOO ),
// 				"param_name" => "addtocart_color",
// 				"value" => "#47A3DA"
// 		),
// 		array (
// 			"type" => "colorpicker",
// 			"class" => "",
// 			"heading" => __ ( "Add To Cart Hover Color", DHVC_WOO ),
// 			"param_name" => "addtocart_hover_color",
// 			"value" => "#fff"
// 		),
// 		array (
// 				"type" => "textfield",
// 				"class" => "",
// 				"heading" => __ ( "Add To Cart Font Size", DHVC_WOO ),
// 				"param_name" => "addtocart_font_size",
// 				"value" => '14px',
// 				"description" => __ ( "Enter your custom add to cart font size . Example: 14px.", DHVC_WOO )
// 		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Add To Cart Padding", DHVC_WOO ),
				"param_name" => "addtocart_padding",
				"value" => '0',
				"description" => __ ( "Enter your custom add to cart padding . Example: 10px 10px 10px 10px.", DHVC_WOO )
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"class" => "",
				"heading" => __ ( "Add To Cart Margin", DHVC_WOO ),
				"param_name" => "addtocart_margin",
				"value" => '0',
				"description" => __ ( "Enter your custom add to cart margin . Example: 10px 10px 10px 10px.", DHVC_WOO )
		),
		array (
			'save_always'=>true,
			"type" => "dropdown",
			"class" => "",
			"heading" => __ ( "Add Cart & Price Style", DHVC_WOO ),
			"param_name" => "add_cart_price_style",
			"dependency" => array (
				'element' => "display",
				'value' => array (
					'grid',
					'masonry',
					'carousel',
				)
			),
			"value" => array (
				__ ( '2 Columns', DHVC_WOO ) => '2column',
				__ ( '1 Colum Center', DHVC_WOO ) => '1column',
			)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Rating", DHVC_WOO ),
				"param_name" => "show_rating",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
		array (
				'save_always'=>true,
				"type" => "checkbox",
				"class" => "",
				"heading" => __ ( "Hide Sale flash", DHVC_WOO ),
				"param_name" => "show_sale_flash",
				"value" => array (
						__ ( 'Yes, please', DHVC_WOO ) => '0'
				)
		),
		array (
			'save_always'=>true,
			"type" => "checkbox",
			"class" => "",
			"heading" => __ ( "Show Quick view", DHVC_WOO ),
			"param_name" => "show_quickview",
			"value" => array (
				__ ( 'Yes, please', DHVC_WOO ) => '1'
			)
		),
		array (
				'save_always'=>true,
				"type" => "textfield",
				"heading" => __ ( "Extra class name", DHVC_WOO ),
				"param_name" => "el_class",
				"description" => __ ( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", DHVC_WOO )
		)
	));
}





