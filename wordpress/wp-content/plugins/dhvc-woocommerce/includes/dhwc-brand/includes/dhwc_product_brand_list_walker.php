<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class DHWC_Product_Brand_List_Walker extends  Walker {
	var $tree_type = 'product_brand';
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/Walker::start_lvl()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/Walker::end_lvl()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/Walker::start_el()
	 */
	function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {

		$output .= '<li class="brand-item brand-item-' . $object->term_id;

		if ( $args['current_brand'] == $object->term_id )
			$output .= ' current-brand';

		if ( $args['current_brand_ancestors'] && $args['current_brand'] && in_array( $object->term_id, $args['current_brand_ancestors'] ) )
			$output .= ' current-brand-parent';

		$output .=  '"><a href="' . get_term_link( (int) $object->term_id, 'product_brand' ) . '">' . $object->name . '</a>';

		if ( $args['show_count'] )
			$output .= ' <span class="count">(' . $object->count . ')</span>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/Walker::end_el()
	 */
	function end_el( &$output, $object, $depth = 0, $args = array() ) {

		$output .= "</li>\n";

	}
	
	/**
	 * (non-PHPdoc)
	 * @see wp-includes/Walker::display_element()
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		if ( !$element )
			return;

		if ( ! $args[0]['show_children_only'] || ( $args[0]['show_children_only'] && ( $element->parent == 0 || $args[0]['current_brand'] == $element->parent || in_array( $element->parent, $args[0]['current_brand_ancestors'] ) ) ) ) {

			$id_field = $this->db_fields['id'];

			//display this element
			if ( is_array( $args[0] ) )
				$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'start_el'), $cb_args);

			$id = $element->$id_field;

			// descend only when the depth is right and there are children for this element
			if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

				foreach( $children_elements[ $id ] as $child ){

					if ( !isset($newlevel) ) {
						$newlevel = true;
						//start the child delimiter
						$cb_args = array_merge( array(&$output, $depth), $args);
						call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
					}
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}
				unset( $children_elements[ $id ] );
			}

			if ( isset($newlevel) && $newlevel ){
				//end the child delimiter
				$cb_args = array_merge( array(&$output, $depth), $args);
				call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
			}

			//end this element
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'end_el'), $cb_args);

		}
	}
}