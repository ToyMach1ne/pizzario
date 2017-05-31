<?php

return apply_filters( 'yit_ywdpd_cart_rules_options', array(

    'rules_type' => array(

        'customers'          => array(
            'label' => __('Customers','ywdpd'),
            'options' => array(
                'customers_list'      => __( 'Users in list', 'ywdpd' ),
                'customers_list_excluded'  => __( 'Exclude users in list', 'ywdpd' ),
                'role_list'     => __( 'Roles in list', 'ywdpd' ),
                'role_list_excluded' => __( 'Roles not in list', 'ywdpd' ),
                'num_of_orders'     => __( 'Minimum number of orders required', 'ywdpd' ),
                'amount_spent'      => __( 'Minimum past expense required', 'ywdpd' )
            )
        ),

        'products'          => array(
            'label' => __('Products','ywdpd'),
            'options' => array(
                'products_list'            => __( 'At least one selected product', 'ywdpd' ),
                'products_list_and'        => __( 'All selected products in cart', 'ywdpd' ),
                'products_list_excluded'   => __( 'Products not selected', 'ywdpd' ),
                'categories_list'          => __( 'At least a selected category', 'ywdpd' ),
                'categories_list_and'      => __( 'All selected categories in cart', 'ywdpd' ),
                'categories_list_excluded' => __( 'Categories not selected', 'ywdpd' ),
                'tags_list'                => __( 'At least a selected tag', 'ywdpd' ),
                'tags_list_and'            => __( 'All selected tags in cart', 'ywdpd' ),
                'tags_list_excluded'       => __( 'Tags not selected', 'ywdpd' )

            )
        ),

        'cart_items'          => array(
            'label' => __('Cart Items','ywdpd'),
            'options' => array(
                'sum_item_quantity'         => __( 'Minimum quantity of product items', 'ywdpd' ),
                'sum_item_quantity_less'    => __( 'Maximum quantity of product items', 'ywdpd' ),
                'count_cart_items_at_least' => __( 'Minimum quantity of cart items', 'ywdpd' ),
                'count_cart_items_less'     => __( 'Maximum quantity of cart items', 'ywdpd' )
            )
        ),

        'cart_subtotal'          => array(
            'label' => __('Cart Subtotals','ywdpd'),
            'options' => array(
                'subtotal_at_least' => __( 'Minimum subtotal', 'ywdpd' ),
                'subtotal_less'     => __( 'Maximum subtotal', 'ywdpd' )
            )
        ),

    ),

    'discount_type' => array(
        'percentage'  => __( 'Percentage Discount', 'ywdpd' ),
        'price'       => __( 'Price Discount', 'ywdpd' ),
        'fixed-price' => __( 'Fixed Price', 'ywdpd' )
    )


));
