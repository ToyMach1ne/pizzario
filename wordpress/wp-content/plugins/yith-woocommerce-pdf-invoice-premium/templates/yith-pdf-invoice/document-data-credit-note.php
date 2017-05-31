<?php /* @var YITH_Invoice $document */ ?>

<div class="invoice-data-content">
	<table>
		<tr class="ywpi-invoice-number">
			<td class="ywpi-invoice-number-title" colspan="2">
				<?php _e ( "CREDIT NOTE NUMBER", 'yith-woocommerce-pdf-invoice' ); ?>
			</td>
		</tr>
		
		<tr class="ywpi-invoice-number">
			<td class="ywpi-invoice-number-title" colspan="2">
				<?php echo $document->get_formatted_document_number (); ?>
			</td>
		</tr>
		
		<tr class="ywpi-order-number">
			<td class="left-content">
				<?php _e ( "Invoice number", 'yith-woocommerce-pdf-invoice' ); ?>
			</td>
			<td class="right-content">
				<?php
				$current_order_id = yit_get_prop ( $document->order, 'id' );
				$parent_order_id  = get_post_field ( 'post_parent', $current_order_id );
				$parent_order     = wc_get_order ( $parent_order_id );
				
				if ( $parent_order ) {
					$invoice = new YITH_Invoice( yit_get_prop ( $parent_order, 'id' ) );
					if ( $invoice->generated () ) {
						echo $invoice->get_formatted_document_number ();
					}
				}
				?>
			</td>
		</tr>
		
		<tr class="ywpi-order-number">
			<td class="left-content">
				<?php _e ( "Order No.", 'yith-woocommerce-pdf-invoice' ); ?>
			</td>
			<td class="right-content">
				<?php echo $document->order->get_order_number (); ?>
				<?php do_action ( 'yith_ywpi_template_order_number', $document ); ?>
			</td>
		</tr>
		
		<tr class="ywpi-invoice-date">
			<td class="left-content">
				<?php _e ( "Date", 'yith-woocommerce-pdf-invoice' ); ?>
			</td>
			<td class="right-content">
				<?php echo apply_filters ( 'ywpi_template_invoice_data_table_invoice_date', $document->get_formatted_document_date (), $document ); ?>
			</td>
		</tr>
		
		<?php if ( apply_filters ( 'ywpi_template_invoice_data_table_order_amount_visible', true ) ) : ?>
			<tr class="invoice-amount">
				<td class="left-content">
					<?php _e ( "Amount", 'yith-woocommerce-pdf-invoice' ); ?>
				</td>
				<td class="right-content">
					<?php echo wc_price ( $document->order->get_total () ); ?>
				</td>
			</tr>
		
		<?php endif; ?>
	</table>
</div>