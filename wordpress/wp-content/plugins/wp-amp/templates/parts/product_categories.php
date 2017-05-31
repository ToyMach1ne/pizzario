<?php
/**
 * @var $this AMPHTML_Template
 * @var $product WC_Product
 */
$product = $this->product;
$cat_count  = sizeof( get_the_terms( $this->post->ID, 'product_cat' ) );
echo $product->get_categories( ', ',
	'<p class="amphtml-posted-in">' . _n( 'Category:', 'Categories:', $cat_count, 'amphtml' ) . ' ', '</p>' );
