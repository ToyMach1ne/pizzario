<?php

class Woocommerce_Product_Options_Product_Option_Group {

    function __construct() {
        //Add fields to store in admin
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
        //Save fields
        add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
        //Register post type
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    function group_option_price( $product_post_id ) {
        $price = 0;
        global $wpdb;
        global $woocommerce_product_options_ajax;
        $sql = "SELECT ID, menu_order FROM {$wpdb->prefix}posts WHERE post_type='product_option_group' AND post_status='publish' ORDER BY menu_order ASC;";
        $results = $wpdb->get_results( $sql );
        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $post_id = $result->ID;
                $meta = get_post_meta( $post_id, 'group_options', true );
                $product_group_post_id = 0;
                $print_options = false;
                if ( !empty( $meta[ 'any_product' ] ) ) {
                    $print_options = true;
                    $product_group_post_id = $post_id;
                } else {
                    if ( !empty( $meta[ 'categories' ] ) ) {
                        $categories = $meta[ 'categories' ];
                        $product_categories = get_the_terms( $product_post_id, 'product_cat' );
                        if ( !empty( $product_categories ) ) {
                            foreach ( $product_categories as $product_category ) {
                                if ( in_array( $product_category->name, $categories ) ) {
                                    $print_options = true;
                                    $product_group_post_id = $post_id;
                                    break;
                                }
                            }
                        }
                    }
                    if ( !$print_options && !empty( $meta[ 'query' ] ) ) {
                        $product_group_post_id = get_the_id();
                        $product_query = new WP_Query( $meta[ 'query' ] );
                        if ( $product_query->have_posts() ) {
                            while ( $product_query->have_posts() ) {
                                $product_query->the_post();
                                $test_id = get_the_id();
                                if ( $test_id === $product_post_id ) {
                                    $print_options = true;
                                    break;
                                }
                            }
                        }
                        wp_reset_postdata();
                    }
                }
                if ( $print_options ) {
                    $product_options = get_post_meta( $product_group_post_id, 'backend-product-options', true );
                    if ( !empty( $product_options ) ) {
                        foreach ( $product_options as $product_option_id => $product_option ) {
                            global $woocommerce_product_options_ajax;
                            $option_price = $woocommerce_product_options_ajax->get_option_price( $product_post_id, $product_group_post_id, $product_option_id, $product_option );
                            $price = $price + floatval( $option_price );
                        }
                    }
                    $price = $price + $woocommerce_product_options_ajax->get_rules_price( $product_group_post_id );
                }
            }
        }
        return $price;
    }

    function get_group_ids( $product_post_id ) {
        $group_ids = array();
        global $wpdb;
        $sql = "SELECT ID, menu_order FROM {$wpdb->prefix}posts WHERE post_type='product_option_group' AND post_status='publish' ORDER BY menu_order ASC;";
        $results = $wpdb->get_results( $sql );
        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $post_id = $result->ID;
                $meta = get_post_meta( $post_id, 'group_options', true );
                $print_options = false;
                if ( !empty( $meta[ 'any_product' ] ) ) {
                    $print_options = true;
                    $product_group_post_id = $post_id;
                    $group_ids[] = $post_id;
                } else {
                    if ( !empty( $meta[ 'categories' ] ) ) {
                        $categories = $meta[ 'categories' ];
                        $product_categories = get_the_terms( $product_post_id, 'product_cat' );
                        if ( !empty( $product_categories ) ) {
                            foreach ( $product_categories as $product_category ) {
                                if ( in_array( $product_category->name, $categories ) ) {
                                    $group_ids[] = $post_id;
                                    break;
                                }
                            }
                        }
                    }
                    if ( !$print_options && !empty( $meta[ 'query' ] ) ) {
                        $product_group_post_id = get_the_id();
                        $product_query = new WP_Query( $meta[ 'query' ] );
                        if ( $product_query->have_posts() ) {
                            while ( $product_query->have_posts() ) {
                                $product_query->the_post();
                                $test_id = get_the_id();
                                if ( $test_id === $product_post_id ) {
                                    $group_ids[] = $post_id;
                                    break;
                                }
                            }
                        }
                        wp_reset_postdata();
                    }
                }
            }
            wp_reset_postdata();
        }
        return $group_ids;
    }

    function print_options( $position ) {
        global $wpdb;
        $sql = "SELECT ID, menu_order FROM {$wpdb->prefix}posts WHERE post_type='product_option_group' AND post_status='publish' ORDER BY menu_order ASC;";
        $results = $wpdb->get_results( $sql );
        global $post;
        $product_post_id = $post->ID;
        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $product_option_group_id = $result->ID;
                $meta = get_post_meta( $product_option_group_id, 'group_options', true );
                $print_options = false;
                if ( !empty( $meta[ 'any_product' ] ) ) {
                    $print_options = true;
                    $product_group_post_id = $product_option_group_id;
                } else {
                    if ( !empty( $meta[ 'categories' ] ) ) {
                        $categories = $meta[ 'categories' ];
                        $product_categories = get_the_terms( $product_post_id, 'product_cat' );
                        if ( !empty( $product_categories ) ) {
                            foreach ( $product_categories as $product_category ) {
                                if ( in_array( $product_category->name, $categories ) ) {
                                    $print_options = true;
                                    $product_group_post_id = $product_option_group_id;
                                    break;
                                }
                            }
                        }
                    }
                    if ( !$print_options && !empty( $meta[ 'query' ] ) ) {
                        $product_group_post_id = $product_option_group_id;
                        $product_query = new WP_Query( $meta[ 'query' ] );
                        if ( $product_query->have_posts() ) {
                            while ( $product_query->have_posts() ) {
                                $product_query->the_post();
                                $test_id = get_the_id();
                                if ( $test_id === $product_post_id ) {
                                    $print_options = true;
                                    break;
                                }
                            }
                        }
                        wp_reset_postdata();
                    }
                }
                if ( $print_options ) {
                    $settings = get_post_meta( $product_group_post_id, 'product-options-settings', true );
                    $above_add_to_cart = value( $settings, 'above_add_to_cart', '' );
                    $in_variations_add_to_cart = value( $settings, 'in_variations_add_to_cart', '' );
                    if ( ( $position == 'show_in_list' && !empty( $settings[ 'show_in_list' ] ) ) || empty( $position ) || ( $position == value( $settings, 'location_of_options' ) ) || ( $position == 'above_add_to_cart' && !empty( $above_add_to_cart ) ) || ( $position == 'below_add_to_cart' && empty( $above_add_to_cart ) && empty( $in_variations_add_to_cart ) && empty( $settings[ 'location_of_options' ] ) ) || ( $position == 'in_variations_add_to_cart' && !empty( $in_variations_add_to_cart ) ) ) {
                        $options = get_post_meta( $product_group_post_id, 'backend-product-options' );
                        if ( isset( $options[ 0 ] ) && !isset( $options[ 0 ][ 'type' ] ) ) {
                            $options = $options[ 0 ];
                        }
                        print '<div class="product-option-groups">';
                        $this->_print_options( $options, $product_group_post_id, $product_post_id );
                        print '</div>';
                    }
                }
            }
        }
    }

    function _print_options( $options, $product_option_post_id, $post_id ) {
        //Add the legend
        $meta = get_post_meta( $product_option_post_id, 'group_options', true );
        $legend = value( $meta, 'legend' );
        //Add the accordion
        $accordion = value( $meta, 'accordion' );
        $accordion_html = '';
        $accordion_title_class = '';
        $accordion_content_class = '';
        if ( !empty( $accordion ) ) {
            $accordion_html = ' <span class="accordion-group-expand">+</span> ';
            $accordion_content_class = 'product-option-accordion-group-content';
            $accordion_title_class = 'accordion-group';
        }
        print '<div class="product-option-group">';
        if ( !empty( $legend ) ) {
            print '<fieldset><legend class="' . $accordion_title_class . '">' . get_the_title( $product_option_post_id ) . $accordion_html . '</legend>';
        } else {
            print '<h2 class="' . $accordion_title_class . ' product-option-group-title">' . get_the_title( $product_option_post_id ) . $accordion_html . '</h2>';
        }
        print '<div class="' . $accordion_content_class . '">';

        //Add the options
        global $woocommerce_product_options_product_frontend;
        $woocommerce_product_options_product_frontend->_print_options( $options, $product_option_post_id, $post_id );

        if ( !empty( $legend ) ) {
            print '</fieldset>';
        }
        print '</div>';
        print '</div>';
    }

    function save_meta_boxes( $post_id, $post ) {
        global $woocommerce_product_options_product_admin;
        $woocommerce_product_options_product_admin->save_meta_boxes( $post_id, $post );
//Save group options
        if ( $post->post_type != 'product_option_group' ) {
            return;
        }
        $value = array();
        if ( isset( $_POST[ 'group_options' ] ) ) {
            $value = $_POST[ 'group_options' ];
        }
        if ( !add_post_meta( $post_id, 'group_options', $value, true ) ) {
            update_post_meta( $post_id, 'group_options', $value );
        }
    }

    function register_post_type() {
        register_post_type( 'product_option_group', array( 'labels' => array(
                'name' => __( 'Product Option Groups', 'woocommerce-product-options' ),
                'singular_name' => __( 'Product Option Group', 'woocommerce-product-options' ),
                'menu_name' => __( 'Product Option Groups', 'woocommerce-product-options' ),
                'add_new' => __( 'Add Product Option Group', 'woocommerce-product-options' ),
                'add_new_item' => __( 'Add New Product Option Group', 'woocommerce-product-options' ),
                'edit' => __( 'Edit', 'woocommerce-product-options' ),
                'edit_item' => __( 'Edit Product Option Group', 'woocommerce-product-options' ),
                'new_item' => __( 'New Product Option Group', 'woocommerce-product-options' ),
                'view' => __( 'View Product Option Group', 'woocommerce-product-options' ),
                'view_item' => __( 'View Product Option Group', 'woocommerce-product-options' ),
                'search_items' => __( 'Search Product Option Groups', 'woocommerce-product-options' ),
                'not_found' => __( 'No Product Option Groups found', 'woocommerce-product-options' ),
                'not_found_in_trash' => __( 'No Product Option Groups found in trash', 'woocommerce-product-options' ),
                'parent' => __( 'Parent Product Option Group', 'woocommerce-product-options' )
            ),
            'description' => __( 'This is where you can add/edit your Product Option Groups.', 'woocommerce-product-options' ),
            'public' => false,
            'show_ui' => true,
            'capability_type' => 'shop_order',
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_menu' => current_user_can( 'manage_woocommerce' ) ? 'edit.php?post_type=product' : true,
            'hierarchical' => false,
            'show_in_nav_menus' => false,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array( 'title', 'page-attributes' ),
            'has_archive' => false,
                )
        );
    }

    function add_meta_boxes() {
        global $woocommerce_product_options_product_admin;
        add_meta_box(
                'woocommerce-product-group-options', __( 'Group Options' ), array( $this, 'add_meta_box' ), 'product_option_group', 'normal', 'default'
        );
        add_meta_box(
                'woocommerce-product-options', __( 'Product Options' ), array( $woocommerce_product_options_product_admin, 'add_meta_box' ), 'product_option_group', 'normal', 'default'
        );
        $this->enqueue_scripts();
    }

    function add_meta_box() {
        global $post;
        $post_id = $post->ID;
        $meta = get_post_meta( $post_id, 'group_options', true );
        //Option to add Product Option Group to:
        //Any product
        $any_product = value( $meta, 'any_product' );
        _e( 'Add these product options to:', 'woocommerce-product-options' );
        print '<br>';
        print '<label><input type="checkbox" class="any-product" name="group_options[any_product]" value="yes" ';
        if ( !empty( $any_product ) ) {
            print 'checked="checked" ';
        }
        print '/>';
        _e( 'Every product', 'woocommerce-product-options' );
        print '</label>';
        print '<div class="not-any-product">';
        //Any product in category
        $categories = value( $meta, 'categories' );
        $possible_categories = get_terms( 'product_cat', array( 'hide_empty' => false, 'fields' => 'names' ) );
        if ( !empty( $possible_categories ) ) {
            _e( 'Apply options to these categories:', 'woocommerce-product-options' );
            print '<br><select multiple name="group_options[categories][]" class="product-option-group-categories">';
            foreach ( $possible_categories as $possible_category ) {
                print '<option value="' . $possible_category . '"';
                if ( !empty( $categories ) && in_array( $possible_category, $categories ) ) {
                    print ' selected ';
                }
                print '>' . $possible_category . '</option>';
            }
            print '</select>';
        }
        //Any product in query
        print '<div class="product-option-group-query">';
        print '<br>';
        _e( 'Or products satisfying this query:', 'woocommerce-product-options' );
        $query = value( $meta, 'query' );
        print '<br><input type="text" value="' . $query . '" name="group_options[query]" placeholder="post_type=product&s=keyword"/>';
        print '</div>';
        print '</div>';
        //Option to accordion or legend group
        $legend = value( $meta, 'legend' );
        print '<br>';
        print '<label><input type="checkbox" class="legend" name="group_options[legend]" value="yes" ';
        if ( !empty( $legend ) ) {
            print 'checked="checked" ';
        }
        print '/>';
        _e( 'Display a box around this group?', 'woocommerce-product-options' );
        print '</label>';

        $accordion = value( $meta, 'accordion' );
        print '<br>';
        print '<label><input type="checkbox" class="accordion" name="group_options[accordion]" value="yes" ';
        if ( !empty( $accordion ) ) {
            print 'checked="checked" ';
        }
        print '/>';
        _e( 'Accordion this group?', 'woocommerce-product-options' );
        print '</label>';
        $this->enqueue_scripts();
    }

    function enqueue_scripts() {
        wp_enqueue_script( 'woocommerce-product-options-product-group', plugins_url() . '/woocommerce-product-options/assets/js/product-group.js', array( 'jquery' ), false, true );
        $localize_array = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
        wp_localize_script( 'woocommerce-product-group', 'woocommerce_product_option_group_settings', $localize_array );
        wp_register_style( 'woocommerce-product-group-css', plugins_url() . '/woocommerce-product-options/assets/css/product-group.css' );
        wp_enqueue_style( 'woocommerce-product-group-css' );
    }

}

$woocommerce_product_options_product_option_group = new Woocommerce_Product_Options_Product_Option_Group();
?>