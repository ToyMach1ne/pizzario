<?php
/**
 * Group container template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
$groups_list = array();
foreach( $types_list as $single_type ) {
	$groups_list[ $single_type->group_id ][] = $single_type;
}

foreach( $groups_list as $key => $types_list ) {
	$group = new YITH_WAPO_Group( $key );
	if( ! empty( $group->name ) ) {
		echo '<h2 class="group-name">' . esc_html( $group->name ) . '</h2>';
	}
	echo '<div class="yith_wapo_groups_container_wrap">';
		foreach( $types_list as $single_type ) {
			$yith_wapo_frontend->printSingleGroupType( $product , $single_type );
		}
	echo '</div>';
}

$display_price = function_exists('wc_get_price_to_display') ? wc_get_price_to_display( $product ) : $product->get_display_price();
$display_price = empty( $display_price ) ? 0 : $display_price;
?>

<div class="yith_wapo_group_total" data-product-price="<?php echo esc_attr( $display_price ); ?>">
	<div class="yith_wapo_group_option_total"><span class="price amount"></span></div>
	<div class="yith_wapo_group_final_total"><span class="price amount"></span></div>
</div>
