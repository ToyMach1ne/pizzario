<?php
/**
 * Email Order Items
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

foreach ( $items as $item ) :
	$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
	$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
	?>
	<tr>
		<td class="order_items_table_td_style order_items_table_td order_items_table_td_left order_items_table_td_product">
			
			<table class="order_items_table_product_details_inner" cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<?php
					// Show image.
					$show_image = ( 'yes' == get_option( 'ec_supreme_all_order_item_table_thumbnail' ) );
					$image_size = ( isset( $image_size ) ) ? $image_size : array( 70, 70 );
					if ( $show_image && is_object( $_product ) && $_product->get_image_id() ) {
						?>
						<td class="order_items_table_product_details_inner_td order_items_table_product_details_inner_td_image">
							<?php echo apply_filters( 'woocommerce_order_item_thumbnail', '<span style="margin-bottom: 5px"><img src="' . ( $_product->get_image_id() ? current( wp_get_attachment_image_src( $_product->get_image_id(), 'thumbnail') ) : wc_placeholder_img_src() ) .'" alt="' . __( 'Product Image', 'email-control' ) . '" height="' . esc_attr( $image_size[1] ) . '" width="' . esc_attr( $image_size[0] ) . '" style="vertical-align:middle; margin-right: 10px;" /></span>', $item ); ?>
						</td>
						<?php
					}
					?>
					<td class="order_items_table_product_details_inner_td order_items_table_product_details_inner_td_text" width="100%">
						
						<span class="order_items_table_product_details_inner_title">
							<?php
							// Product name
							echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
							
							// SKU
							if ( $show_sku && is_object( $_product ) && $_product->get_sku() ) {
								echo ' (#' . $_product->get_sku() . ')';
							}
							?>
						</span>
						
						<?php
						// File URLs
						if ( $show_download_links && is_object( $_product ) && $_product->exists() && $_product->is_downloadable() ) {
						
							$download_files = $order->get_item_downloads( $item );
							$i              = 0;
						
							foreach ( $download_files as $download_id => $file ) {
								$i++;
						
								if ( count( $download_files ) > 1 ) {
									$prefix = sprintf( __( 'Download %d', 'email-control' ), $i );
								} elseif ( $i == 1 ) {
									$prefix = __( 'Download', 'email-control' );
								}
						
								echo '<br/><small class="order_item_download">' . $prefix . ': <a href="' . esc_url_raw( $file['download_url'] ) . '" target="_blank">' . esc_html( $file['name'] ) . '</a></small>';
							}
						}
						
						// Variation
						if ( $item_meta->meta ) {
							echo '<br/><small>' . nl2br( $item_meta->display( true, true ) ) . '</small>';
						}
						?>
						
					</td>
				</tr>
			</table>
			
		</td>
		<td class="order_items_table_td_style order_items_table_td order_items_table_td_product">
			<?php echo $item['qty']; ?>
		</td>
		<td class="order_items_table_td_style order_items_table_td order_items_table_td_right order_items_table_td_product" style="text-align:right">
			<?php echo $order->get_formatted_line_subtotal( $item ); ?>
		</td>
	</tr>

	<?php if ( $show_purchase_note && is_object( $_product ) && $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) : ?>
		<tr>
			<td colspan="3" class="order_items_table_td_style order_items_table_td order_items_table_td_both order_items_table_td_product">
				<?php echo apply_filters( 'the_content', $purchase_note ); ?>
			</td>
		</tr>
	<?php endif; ?>

	<?php
endforeach;
?>