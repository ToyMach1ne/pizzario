<?php
/**
 * Customer Completed Order
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

do_action( 'woocommerce_email_header', $email_heading );
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top" class="top_content_container">
			
			<div class="top_heading">
				<?php echo get_option( 'ec_deluxe_customer_completed_order_heading' ); ?>
			</div>
			<div class="top_paragraph">
				<?php echo get_option( 'ec_deluxe_customer_completed_order_main_text' ); ?>
			</div>
			
			<div class="top_paragraph">
				<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>
			</div>
			
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="top_content_container">
			
			<?php echo ec_special_title( __( "Order Details", 'email-control'), array("border_position" => "center", "text_position" => "center", "space_after" => "3", "space_before" => "3" ) ); ?>
			
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td class="order-table-heading" style="text-align:left; padding: 12px 0 6px;">
						<span class="highlight">
							<?php _e( 'Order Number:', 'email-control' ) ?>
						</span> 
						<?php echo $order->get_order_number(); ?>
					</td>
					<td class="order-table-heading" style="text-align:right; padding: 12px 0 6px;">
						<span class="highlight">
							<?php _e( 'Order Date:', 'email-control' ) ?>
						</span> 
						<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>
					</td>
				</tr>
			</table>

			<table cellspacing="0" cellpadding="0" class="order_items_table" border="0" >
				<thead>
					<tr>
						<th scope="col" class="order_items_table_th_style order_items_table_td order_items_table_td_left order_items_table_td_top"><?php _e( 'Product', 'email-control' ); ?></th>
						<th scope="col" class="order_items_table_th_style order_items_table_td order_items_table_td_top"><?php _e( 'Quantity', 'email-control' ); ?></th>
						<th scope="col" class="order_items_table_th_style order_items_table_td order_items_table_td_right order_items_table_td_top" style="text-align:right"><?php _e( 'Price', 'email-control' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php echo $order->email_order_items_table( true, false, true ); ?>
				</tbody>
				<tfoot>
					<?php
					if ( $totals = $order->get_order_item_totals() ) {
						$i = 0;
						foreach ( $totals as $total ) {
							$i++;
							?>
							<tr class="order_items_table_total_row order_items_table_total_row_<?php echo esc_attr( sanitize_title( $total['label'] ) ) ?>">
								<th scope="row" colspan="2" class="order_items_table_totals_style order_items_table_td order_items_table_td_left">
									<?php echo $total['label']; ?>
								</th>
								<td class="order_items_table_totals_style order_items_table_td order_items_table_td_right" style="text-align:right;" >
									<?php echo $total['value']; ?>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</tfoot>
			</table>
			
			<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>
			
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="top_content_container">

			<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

			<?php echo ec_special_title( __( "Customer details", 'email-control'), array("border_position" => "center", "text_position" => "center") ); ?>

			<?php if ( $order->billing_email ) : ?>
				<p><strong><?php _e( 'Email:', 'email-control' ); ?></strong> <?php echo $order->billing_email; ?></p>
			<?php endif; ?>
			<?php if ( $order->billing_phone ) : ?>
				<p><strong><?php _e( 'Tel:', 'email-control' ); ?></strong> <?php echo $order->billing_phone; ?></p>
			<?php endif; ?>

			<?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>
						
		</td>
	</tr>
</table>

<?php do_action( 'woocommerce_email_footer' ); ?>
