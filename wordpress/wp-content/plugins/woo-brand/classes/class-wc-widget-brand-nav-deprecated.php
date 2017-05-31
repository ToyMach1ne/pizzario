<?php 
function woo_brands_layered_nav_init( $filtered_posts ) {
	global $woocommerce, $_chosen_attributes;
	$perm ="";
	$perm = 'filter_product_'.get_option( 'pw_woocommerce_brands_base','brand' );
	if ( is_active_widget( false, false, 'woocommerce_brand_nav', true ) && ! is_admin() ) {

		if ( ! empty( $_GET[ $perm ] ) ) {

			$terms 	= array_map( 'intval', explode( ',', $_GET[ $perm ] ) );

			if ( sizeof( $terms ) > 0 ) {

				$_chosen_attributes['product_brand']['terms'] = $terms;
				$_chosen_attributes['product_brand']['query_type'] = 'and';

				$matched_products = get_posts(
					array(
						'post_type' 	=> 'product',
						'numberposts' 	=> -1,
						'post_status' 	=> 'publish',
						'fields' 		=> 'ids',
						'no_found_rows' => true,
						'tax_query' => array(
							'relation' => 'AND',
							array(
								'taxonomy' 	=> 'product_brand',
								'terms' 	=> $terms,
								'field' 	=> 'id'
							)
						)
					)
				);

				$woocommerce->query->layered_nav_post__in = array_merge( $woocommerce->query->layered_nav_post__in, $matched_products );
				$woocommerce->query->layered_nav_post__in[] = 0;

				if ( sizeof( $filtered_posts ) == 0 ) {
					$filtered_posts = $matched_products;
					$filtered_posts[] = 0;
				} else {
					$filtered_posts = array_intersect( $filtered_posts, $matched_products );
					$filtered_posts[] = 0;
				}

			}

		}

    }

    return (array) $filtered_posts;
}
add_action( 'loop_shop_post_in', 'woo_brands_layered_nav_init', 11 );

class woocommerce_brand_nav extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'woocommerce_brand_nav', // Base ID
			__('WooCommerce Brand Layered Nav', 'woocommerce-brands'), // Name
			array( 'description' => __( 'Shows brands in a widget which lets you narrow down the list of products when viewing products.', 'woocommerce-brands' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $_chosen_attributes, $woocommerce, $_attributes_array;
		$perm ="";
		$perm = 'filter_product_'.get_option( 'pw_woocommerce_brands_base','brand' );
		extract( $args );

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( is_array( $_attributes_array ) ? $_attributes_array : array(), array( 'product_cat', 'product_tag' ) ) ) )
			return;

		$current_term 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->term_id : '';
		$current_tax 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->taxonomy : '';

		$title 			= apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$taxonomy 		= 'product_brand';
		$display_type 	= isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

		if ( ! taxonomy_exists( $taxonomy ) )
			return;

		$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );

		if ( count( $terms ) > 0 ) {

			ob_start();

			$found = false;

			echo $before_widget . $before_title . $title . $after_title;

			if ( ! $_attributes_array || ! is_tax( $_attributes_array ) )
				if ( is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) )
					$found = true;

			if ( $display_type == 'dropdown' ) {

				if ( $current_tax && $taxonomy == $current_tax ) {

					$found = false;

				} else {

					$taxonomy_filter = $taxonomy;

					$found = true;

					echo '<select id="dropdown_layered_nav_' . $taxonomy_filter . '" name="tech" class="tech">';

					echo '<option value="">' . __( 'Any brand', 'woocommerce-brands' ) .'</option>';

				foreach ( $terms as $term ) {
					$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );
					if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {
						$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

						set_transient( $transient_name, $_products_in_term );
					}
					$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );
					$count = sizeof( array_intersect( $_products_in_term, $woocommerce->query->filtered_product_ids ) );
					if ( $current_term == $term->term_id )
						continue;
					if ( $count > 0 && $current_term !== $term->term_id )
						$found = true;

					if ( $count == 0 && ! $option_is_set )
						continue;
					$current_filter = ( isset( $_GET[ $perm ] ) ) ? explode( ',', $_GET[ $perm ] ) : array();

					if ( ! is_array( $current_filter ) )
						$current_filter = array();

					if ( ! in_array( $term->term_id, $current_filter ) )
						$current_filter[] = $term->term_id;

					if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
						$link = home_url();
					} elseif ( is_post_type_archive( 'product' ) || is_page( woocommerce_get_page_id('shop') ) ) {
						$link = get_post_type_archive_link( 'product' );
					} else {
						$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
					}
					if ( $_chosen_attributes ) {
						foreach ( $_chosen_attributes as $name => $data ) {
							if ( $name !== 'product_brand' ) {
								while ( in_array( $current_term, $data['terms'] ) ) {
									$key = array_search( $current_term, $data );
									unset( $data['terms'][$key] );
								}
								if ( ! empty( $data['terms'] ) )
									$link = add_query_arg( sanitize_title( str_replace( 'pa_', 'filter_', $name ) ), implode(',', $data['terms']), $link );
							}
						}
					}
					if ( isset( $_chosen_attributes['product_brand'] ) && is_array( $_chosen_attributes['product_brand']['terms'] ) && in_array( $term->term_id, $_chosen_attributes['product_brand']['terms'] ) ) {
						$class = 'class="chosen"';
						if ( sizeof( $current_filter ) > 1 ) {
							$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
							$link = add_query_arg( $perm, implode( ',', $current_filter_without_this ), $link );
						}

					} else {
						$class = '';
						$link = add_query_arg( $perm, implode( ',', $current_filter ), $link );
					}
					if ( get_search_query() )
						$link = add_query_arg( 's', get_search_query(), $link );
					if ( isset( $_GET['post_type'] ) )
						$link = add_query_arg( 'post_type', $_GET['post_type'], $link );

					echo '<option value="'. $term->term_id . '" '.selected( isset( $_GET[ $perm ] ) ? $_GET[ $perm ] : '' , $term->term_id, false ) . '>' . $term->name . '</option>';												

				}					


					echo '</select>';

					$js = "
						jQuery(document).ready(function() {
							jQuery('.tech').msDropdown();
						});					
						jQuery('#dropdown_layered_nav_$taxonomy_filter').change(function(){
							value=jQuery('#dropdown_layered_nav_$taxonomy_filter').val();
							var val=Array();
							val=value.split('@');
							if(val[0]=='url')
								window.location= val[1];							
							else
								location.href = '" . add_query_arg('filtering', '1', remove_query_arg( $perm ) ) . "&".$perm."=' + jQuery('#dropdown_layered_nav_$taxonomy_filter').val();								
						});
					";

					if ( function_exists( 'wc_enqueue_js' ) ) {
						wc_enqueue_js( $js );
					} else {
						$woocommerce->add_inline_js( $js );
					}

				}

			}
			else {

				echo "<ul>";

				foreach ( $terms as $term ) {

					$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );
					//print_r(get_transient( $transient_name ));
					if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

						$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

						set_transient( $transient_name, $_products_in_term );
					}

					$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

					$count = sizeof( array_intersect( $_products_in_term, $woocommerce->query->filtered_product_ids ) );

					if ( $current_term == $term->term_id )
						continue;

					if ( $count > 0 && $current_term !== $term->term_id )
						$found = true;

					if ( $count == 0 && ! $option_is_set )
						continue;

					$current_filter = ( isset( $_GET[ $perm ] ) ) ? explode( ',', $_GET[ $perm ] ) : array();

					if ( ! is_array( $current_filter ) )
						$current_filter = array();

					if ( ! in_array( $term->term_id, $current_filter ) )
						$current_filter[] = $term->term_id;

					if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
						$link = home_url();
					} elseif ( is_post_type_archive( 'product' ) || is_page( woocommerce_get_page_id('shop') ) ) {
						$link = get_post_type_archive_link( 'product' );
					} else {
						$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
					}

					if ( $_chosen_attributes ) {
						foreach ( $_chosen_attributes as $name => $data ) {
							if ( $name !== 'product_brand' ) {

								while ( in_array( $current_term, $data['terms'] ) ) {
									$key = array_search( $current_term, $data );
									unset( $data['terms'][$key] );
								}

								if ( ! empty( $data['terms'] ) )
									$link = add_query_arg( sanitize_title( str_replace( 'pa_', 'filter_', $name ) ), implode(',', $data['terms']), $link );
							}
						}
					}

					if ( isset( $_GET['min_price'] ) )
						$link = add_query_arg( 'min_price', $_GET['min_price'], $link );

					if ( isset( $_GET['max_price'] ) )
						$link = add_query_arg( 'max_price', $_GET['max_price'], $link );
						
					if ( isset( $_chosen_attributes['product_brand'] ) && is_array( $_chosen_attributes['product_brand']['terms'] ) && in_array( $term->term_id, $_chosen_attributes['product_brand']['terms'] ) ) {

						$class = 'class="chosen"';

						// Remove this term is $current_filter has more than 1 term filtered
						if ( sizeof( $current_filter ) > 1 ) {
							$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
							$link = add_query_arg( $perm, implode( ',', $current_filter_without_this ), $link );
						}

					} else {
						$class = '';
						$link = add_query_arg( $perm, implode( ',', $current_filter ), $link );
					}

					// Search Arg
					if ( get_search_query() )
						$link = add_query_arg( 's', get_search_query(), $link );

					// Post Type Arg
					if ( isset( $_GET['post_type'] ) )
						$link = add_query_arg( 'post_type', $_GET['post_type'], $link );

					echo '<li ' . $class . '>';


					$url	= esc_html(get_woocommerce_term_meta( $term->term_id, 'url', true ));							
					
					echo ( $count > 0 || $option_is_set ) ? '<a href="' .($url=="" ? $link : $url). '">' : '<span>';

					echo $term->name;

					echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';

					echo ' <small class="count">' . $count . '</small></li>';
				}

				echo "</ul>";

			} // End display type conditional

			echo $after_widget;

			if ( ! $found )
				ob_end_clean();
			else
				echo ob_get_clean();
		}
	}

	public function form( $instance ) {
		global $woocommerce;

		if ( ! isset( $instance['display_type'] ) )
			$instance['display_type'] = 'list';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woocommerce-brands' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php _e( 'Display Type:', 'woocommerce-brands' ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
			<option value="list" <?php selected( $instance['display_type'], 'list' ); ?>><?php _e( 'List', 'woocommerce-brands' ); ?></option>
			<option value="dropdown" <?php selected( $instance['display_type'], 'dropdown' ); ?>><?php _e( 'Dropdown', 'woocommerce-brands' ); ?></option>
		</select></p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		global $woocommerce;

		if ( empty( $new_instance['title'] ) )
			$new_instance['title'] = __( 'Brands', 'woocommerce-brands' );

		$instance['title'] 			= strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['display_type'] 	= stripslashes( $new_instance['display_type'] );

		return $instance;
	}
}
register_widget( 'woocommerce_brand_nav' );


?>