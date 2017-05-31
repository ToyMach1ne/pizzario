<?php

class WooCommerce_Product_Options_Rest_API_Product_Groups extends WC_API_Resource {

    protected $base = '/product_option_groups';

    public function register_routes( $routes ) {

        # GET/POST /product_option_groups
        $routes[ $this->base ] = array(
            array( array( $this, 'get_product_option_groups' ), WC_API_Server::READABLE ),
            array( array( $this, 'create_product_option_group' ), WC_API_SERVER::CREATABLE | WC_API_Server::ACCEPT_DATA ),
        );

        # GET /product_option_groups/count
        $routes[ $this->base . '/count' ] = array(
            array( array( $this, 'get_product_option_groups_count' ), WC_API_Server::READABLE ),
        );

        # GET/PUT/DELETE /product_option_groups/<id>
        $routes[ $this->base . '/(?P<id>\d+)' ] = array(
            array( array( $this, 'get_product_option_group' ), WC_API_Server::READABLE ),
            array( array( $this, 'edit_product_option_group' ), WC_API_Server::EDITABLE | WC_API_Server::ACCEPT_DATA ),
            array( array( $this, 'delete_product_option_group' ), WC_API_Server::DELETABLE ),
        );

        return $routes;
    }

    public function get_product_option_groups( $fields = null, $filter = array(), $page = 1 ) {

        $filter[ 'page' ] = $page;

        $query = $this->query_product_option_groups( $filter );

        $product_option_groups = array();

        foreach ( $query->posts as $product_option_group_id ) {

            if ( !$this->is_readable( $product_option_group_id ) ) {
                continue;
            }

            $product_option_groups[] = current( $this->get_product_option_group( $product_option_group_id, $fields ) );
        }

        $this->server->add_pagination_headers( $query );

        return array( 'product_option_groups' => $product_option_groups );
    }

    public function get_product_option_group( $id, $fields = null ) {

        $id = $this->validate_request( $id, 'product_option_group', 'read' );
        if ( is_wp_error( $id ) ) {
            return $id;
        }

        $product_option_group = get_post( $id );

        $product_option_group_data = $this->get_product_option_group_data( $product_option_group );

        return array( 'product_option_group' => apply_filters( 'woocommerce_api_product_option_group_response', $product_option_group_data, $product_option_group, $fields, $this->server ) );
    }

    public function get_product_option_groups_count( $filter = array() ) {
        try {
            if ( !current_user_can( 'manage_woocommerce' ) ) {
                throw new WC_API_Exception( 'woocommerce_api_user_cannot_manage_woocommerce', __( 'You do not have permission to read the product option groups count', 'woocommerce-product-options' ), 401 );
            }

            $query = $this->query_product_option_groups( $filter );

            return array( 'count' => ( int ) $query->found_posts );
        } catch ( WC_API_Exception $e ) {
            return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
        }
    }

    public function create_product_option_group( $data ) {
        $id = 0;

        try {
            if ( !isset( $data[ 'product_option_group' ] ) ) {
                throw new WC_API_Exception( 'woocommerce_api_missing_product_option_group_data', sprintf( __( 'No %1$s data specified to create %1$s', 'woocommerce-product-options' ), 'product_option_group' ), 400 );
            }

            $data = $data[ 'product_option_group' ];

            // Check permissions.
            if ( !current_user_can( 'manage_woocommerce' ) ) {
                throw new WC_API_Exception( 'woocommerce_api_user_cannot_manage_woocommerce', __( 'You do not have permission to create product option groups', 'woocommerce-product-options' ), 401 );
            }

            $data = apply_filters( 'woocommerce_api_create_product_option_group_data', $data, $this );

            // Check if product option group title is specified.
            if ( !isset( $data[ 'title' ] ) ) {
                throw new WC_API_Exception( 'woocommerce_api_missing_product_option_group_title', sprintf( __( 'Missing parameter %s', 'woocommerce' ), 'title' ), 400 );
            }

            $new_product_option_group = array(
                'post_title' => wc_clean( $data[ 'title' ] ),
                'post_status' => isset( $data[ 'status' ] ) ? wc_clean( $data[ 'status' ] ) : 'publish',
                'post_type' => 'product_option_group',
                'menu_order' => isset( $data[ 'menu_order' ] ) ? intval( $data[ 'menu_order' ] ) : 0,
            );
            $id = wp_insert_post( $new_product_option_group, true );
            if ( is_wp_error( $id ) ) {
                throw new WC_API_Exception( 'woocommerce_api_cannot_create_product_option_group', $id->get_error_message(), 400 );
            }

            $this->save_meta( $id, $data );
            do_action( 'woocommerce_api_create_product_option_group', $id, $data );

            $this->server->send_status( 201 );

            return $this->get_product_option_group( $id );
        } catch ( WC_API_Exception $e ) {
            $this->clear_product_option_group( $id );
            return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
        }
    }

    function save_meta( $id, $data ) {
        if ( isset( $data[ 'group_options' ] ) ) {
            $group_options = $data[ 'group_options' ];
            delete_post_meta( $id, 'group_options' );
            add_post_meta( $id, 'group_options', $group_options, true );
        }
        global $woocommerce_product_options_rest_api;
        $woocommerce_product_options_rest_api->woocommerce_api_edit_product( $id, $data );
    }

    public function edit_product_option_group( $id, $data ) {
        try {
            if ( !isset( $data[ 'product_option_group' ] ) ) {
                throw new WC_API_Exception( 'woocommerce_api_missing_product_option_group_data', sprintf( __( 'No %1$s data specified to edit %1$s', 'woocommerce' ), 'product_option_group' ), 400 );
            }

            $data = $data[ 'product_option_group' ];

            $id = $this->validate_request( $id, 'product_option_group', 'edit' );

            if ( is_wp_error( $id ) ) {
                return $id;
            }

            $data = apply_filters( 'woocommerce_api_edit_product_option_group_data', $data, $this );

            if ( isset( $data[ 'title' ] ) ) {
                wp_update_post( array( 'ID' => $id, 'post_title' => wc_clean( $data[ 'title' ] ) ) );
            }
            if ( isset( $data[ 'name' ] ) ) {
                wp_update_post( array( 'ID' => $id, 'post_name' => sanitize_title( $data[ 'name' ] ) ) );
            }
            if ( isset( $data[ 'status' ] ) ) {
                wp_update_post( array( 'ID' => $id, 'post_status' => wc_clean( $data[ 'status' ] ) ) );
            }
            if ( isset( $data[ 'menu_order' ] ) ) {
                wp_update_post( array( 'ID' => $id, 'menu_order' => intval( $data[ 'menu_order' ] ) ) );
            }
            $this->save_meta( $id, $data );
            do_action( 'woocommerce_api_edit_product_option_group', $id, $data );

            return $this->get_product_option_group( $id );
        } catch ( WC_API_Exception $e ) {
            return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
        }
    }

    public function delete_product_option_group( $id, $force = false ) {

        $id = $this->validate_request( $id, 'product_option_group', 'delete' );

        if ( is_wp_error( $id ) ) {
            return $id;
        }

        do_action( 'woocommerce_api_delete_product_option_group', $id, $this );

        $result = ( $force ) ? wp_delete_post( $id, true ) : wp_trash_post( $id );

        if ( !$result ) {
            return new WP_Error( 'woocommerce_api_cannot_delete_product_option_group', sprintf( __( 'This %s cannot be deleted', 'woocommerce' ), 'product_option_group' ), array( 'status' => 500 ) );
        }

        if ( $force ) {
            return array( 'message' => sprintf( __( 'Permanently deleted %s', 'woocommerce' ), 'product_option_group' ) );
        } else {
            $this->server->send_status( '202' );

            return array( 'message' => sprintf( __( 'Deleted %s', 'woocommerce' ), 'product_option_group' ) );
        }
    }

    function clear_product_option_group( $product_option_group_id ) {
        if ( !is_numeric( $product_option_group_id ) || 0 >= $product_option_group_id ) {
            return;
        }
        wp_delete_post( $product_option_group_id, true );
    }

    function get_product_option_group_data( $product_option_group ) {
        $product_option_group_data = array(
            'title' => $product_option_group->post_title,
        );
        $meta = get_post_meta( $product_option_group->ID, 'group_options', true );
        if ( !empty( $meta ) ) {
            $product_option_group_data[ 'group_options' ] = $meta;
        } else {
            $product_option_group_data[ 'group_options' ] = array();
        }
        $product_options = get_post_meta( $product_option_group->ID, 'backend-product-options', true );
        if ( !empty( $product_options ) ) {
            $product_option_group_data[ 'product-options' ] = $product_options;
        } else {
            $product_option_group_data[ 'product-options' ] = array();
        }
        $product_options_settings = get_post_meta( $product_option_group->ID, 'product-options-settings', true );
        if ( !empty( $product_options_settings ) ) {
            $product_option_group_data[ 'product-options-settings' ] = $product_options_settings;
        } else {
            $product_option_group_data[ 'product-options-settings' ] = array();
        }
        return $product_option_group_data;
    }

    private function query_product_option_groups( $args ) {

        $query_args = array(
            'fields' => 'ids',
            'post_type' => 'product_option_group',
            'post_status' => 'publish',
            'meta_query' => array(),
        );

        $query_args = $this->merge_query_args( $query_args, $args );
        return new WP_Query( $query_args );
    }

}
