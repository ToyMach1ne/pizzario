<?php
/**
 * The Template for displaying products in a product brand. Simply includes the archive template.
 *
 * Override this template by copying it to yourtheme/dhvc-woocommerce/taxonomy-product_brand.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

woocommerce_get_template( 'archive-product.php' );