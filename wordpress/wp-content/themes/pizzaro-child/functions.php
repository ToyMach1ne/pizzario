<?php
/**
 * Pizzaro Child
 *
 * @package pizzaro-child
 */
/**
 * Include all your custom code here
 */
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name'                       => 'Kitchen',
    'singular_name'              => 'Kitchen',
    'search_items'               => 'Search',
    'popular_items'              => 'Popular',
    'all_items'                  => 'All',
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => 'Edit',
    'update_item'                => 'Update',
    'add_new_item'               => 'Add',
    'new_item_name'              => 'New',
    'separate_items_with_commas' => 'Separate items with comas',
    'add_or_remove_items'        => 'Add or remove',
    'choose_from_most_used'      => 'Choose from the most used',
    'not_found'                  => 'No found.',
    'menu_name'                  => 'Кухни',
  );

  $args = array(
    'hierarchical'          => false,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'kitchens' ),
  );

  register_taxonomy( 'kitchens', 'product', $args );

