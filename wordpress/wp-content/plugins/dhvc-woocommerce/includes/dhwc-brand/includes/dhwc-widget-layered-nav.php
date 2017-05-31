<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class DHWC_Widget_Layered_Nav extends WP_Widget {
	/**
	 * Constructor
	 */
	public function __construct(){
		$widget_options = array( 'classname' => 'woocommerce widget_layered_nav dhwc_widget_layered_nav', 'description' => __( 'Shows product brands in a widget which lets you narrow down the list of products when viewing product categories',DHVC_WOO) );
		$control_options = array();
		parent::__construct('dhwc_widget_layered_nav',__('DHWOO Product Brand Layered Nav',DHVC_WOO), $widget_options, $control_options);
	}
	
	protected function _get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		global $wpdb;
	
		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();
	
		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}
	
		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
	
		$sql  = "
		SELECT COUNT( {$wpdb->posts}.ID ) as term_count, term_count_relationships.term_taxonomy_id as term_count_id FROM {$wpdb->posts}
		INNER JOIN {$wpdb->term_relationships} AS term_count_relationships ON ({$wpdb->posts}.ID = term_count_relationships.object_id)
		" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
		WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish'
		" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND term_count_relationships.term_taxonomy_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
			GROUP BY term_count_relationships.term_taxonomy_id;
		";
	
		$results = $wpdb->get_results( $sql );
	
		return wp_list_pluck( $results, 'term_count', 'term_count_id' );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::widget()
	 */
	function widget($args, $instance){
		global $_chosen_attributes, $woocommerce, $_attributes_array;

		extract( $args );
		
		
		$_attributes_array = is_array( $_attributes_array ) ? $_attributes_array : array();
		
		if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge($_attributes_array,array('product_tag', 'product_cat'))))
			return;

		$current_term 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->term_id : '';
		$current_tax 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->taxonomy : '';

		$title 			= apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$taxonomy 		= 'product_brand';
		$display_type 	= isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

		if ( ! taxonomy_exists( $taxonomy ) )
			return;

		$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );
		
		$wc_query_has_filtered_product_ids = true;
		if(isset($woocommerce->query->filtered_product_ids)){
			$filtered_product_ids = $woocommerce->query->filtered_product_ids;
		}else{
			$wc_query_has_filtered_product_ids = false;
			$filtered_product_ids = $this->_get_filtered_term_product_counts(wp_list_pluck( $terms, 'term_id' ), $taxonomy,'and');
		}
		
		
		if ( count( $terms ) > 0 ) {

			ob_start();

			$found = false;

			echo $before_widget . $before_title . $title . $after_title;

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! $_attributes_array || ! is_tax( $_attributes_array ) )
				if ( is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) )
					$found = true;

			if ( $display_type == 'dropdown' ) {

				// skip when viewing the taxonomy
				if ( $current_tax && $taxonomy == $current_tax ) {

					$found = false;

				} else {

					$taxonomy_filter = $taxonomy;

					$found = true;

					echo '<select id="dropdown_layered_nav_' . $taxonomy_filter . '">';

					echo '<option value="">' . __( 'Any brand', DHVC_WOO ) .'</option>';

					foreach ( $terms as $term ) {

						// If on a term page, skip that term in widget list
						if ( $term->term_id == $current_term )
							continue;

						// Get count based on current view - uses transients
						$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );

						if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

							$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

							set_transient( $transient_name, $_products_in_term );
						}

						$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

						$count = $wc_query_has_filtered_product_ids ? sizeof( array_intersect( $_products_in_term, $filtered_product_ids ) ) : (isset( $filtered_product_ids[ $term->term_id ] ) ? $filtered_product_ids[ $term->term_id ] : 0);

						if ( $count > 0 )
							$found = true;

						echo '<option value="' . $term->term_id . '" '.selected( isset( $_GET[ 'filter_product_brand' ] ) ? $_GET[ 'filter_product_brand' ] : '' , $term->term_id, false ) . '>' . $term->name .'&nbsp;(' . $count . ')'. '</option>';
					}

					echo '</select>';

					wc_enqueue_js("
						jQuery('#dropdown_layered_nav_$taxonomy_filter').change(function(){
							location.href = '" . esc_url_raw( preg_replace( '%\/page/[0-9]+%', '', esc_url(add_query_arg('filtering', '1', esc_url(remove_query_arg( array( 'page', 'filter_product_brand' )) ) )) ) ) . "&filter_product_brand=' + jQuery('#dropdown_layered_nav_$taxonomy_filter').val();
				
						});

					");

				}

			} else {

				// List display
				echo "<ul>";

				foreach ( $terms as $term ) {

					$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );

					if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

						$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

						set_transient( $transient_name, $_products_in_term );
					}

					$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

					// If this is an AND query, only show options with count > 0
					$count = $wc_query_has_filtered_product_ids ? sizeof( array_intersect( $_products_in_term, $filtered_product_ids ) ) : (isset( $filtered_product_ids[ $term->term_id ] ) ? $filtered_product_ids[ $term->term_id ] : 0);

					if ( $current_term == $term->term_id )
						continue;

					if ( $count > 0 && $current_term !== $term->term_id )
						$found = true;

					if ( $count == 0 && ! $option_is_set )
						continue;
					
					$current_filter = ( isset( $_GET[ 'filter_product_brand' ] ) ) ? explode( ',', $_GET[ 'filter_product_brand' ] ) : array();
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
								
								$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

								if ( ! empty( $data['terms'] ) )
									$link = esc_url(add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link ));
								
							}
						}
					}

					// Min/Max
					if ( isset( $_GET['min_price'] ) )
						$link = esc_url(add_query_arg( 'min_price', $_GET['min_price'], $link ));

					if ( isset( $_GET['max_price'] ) )
						$link = esc_url(add_query_arg( 'max_price', $_GET['max_price'], $link ));

					// Current Filter = this widget
					if ( isset( $_chosen_attributes['product_brand'] ) && is_array( $_chosen_attributes['product_brand']['terms'] ) && in_array( $term->term_id, $_chosen_attributes['product_brand']['terms'] ) ) {

						$class = 'class="chosen"';

						// Remove this term is $current_filter has more than 1 term filtered
						if ( sizeof( $current_filter ) > 1 ) {
							$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
							$link = esc_url(add_query_arg( 'filter_product_brand', implode( ',', $current_filter_without_this ), $link ));
						}

					} else {
						$class = '';
						$link = esc_url(add_query_arg( 'filter_product_brand', implode( ',', $current_filter ), $link ));
					}

					// Search Arg
					if ( get_search_query() )
						$link = esc_url(add_query_arg( 's', get_search_query(), $link ));

					// Post Type Arg
					if ( isset( $_GET['post_type'] ) )
						$link = esc_url(add_query_arg( 'post_type', $_GET['post_type'], $link ));

					echo '<li ' . $class . '>';

					echo ( $count > 0 || $option_is_set ) ? '<a href="' . esc_url( apply_filters( 'dhwc_layered_nav_link', $link ) ) . '">' : '<span>';

					echo $term->name;

					echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';

					echo ' <small class="count">' . $count . '</small></li>';
				}

				echo "</ul>";

			} 

			echo $after_widget;

			if ( ! $found )
				ob_clean();
			else
				echo ob_get_clean();
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::update()
	 */
	function update( $new_instance, $old_instance ) {
		global $woocommerce;

		if ( empty( $new_instance['title'] ) )
			$new_instance['title'] = __('Brands',DHVC_WOO);

		$instance['title'] 			= strip_tags( stripslashes($new_instance['title'] ) );
		$instance['display_type'] 	= stripslashes( $new_instance['display_type'] );

		return $instance;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/WP_Widget::form()
	 */
	function form( $instance ) {
		global $woocommerce;
		
		if ( ! isset( $instance['display_type'] ) )
			$instance['display_type'] = 'list';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', DHVC_WOO ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php _e( 'Display Type:', DHVC_WOO ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
			<option value="list" <?php selected( $instance['display_type'], 'list' ); ?>><?php _e( 'List', DHVC_WOO ); ?></option>
			<option value="dropdown" <?php selected( $instance['display_type'], 'dropdown' ); ?>><?php _e( 'Dropdown', DHVC_WOO ); ?></option>
		</select></p>

		<?php
	}
}