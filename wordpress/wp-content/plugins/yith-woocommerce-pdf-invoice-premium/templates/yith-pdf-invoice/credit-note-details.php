<?php
/**
 * The Template for invoice details
 *
 * Override this template by copying it to [your theme]/woocommerce/invoice/ywpi-invoice-details.php
 *
 * @author        Yithemes
 * @package       yith-woocommerce-pdf-invoice-premium/Templates
 * @version       1.0.0
 */

/** @var YITH_Document $document */

$invoice_details = new YITH_Invoice_Details( $document );
/** @var WC_Order_Refund $order */
$order          = $document->order;
?>
<table class="credit-note-details">
	<thead>
	<tr>
		<th class="column-refund-text"><?php _e( 'Description', 'yith-woocommerce-pdf-invoice' ); ?></th>

		<?php if ( ywpi_is_enabled_credit_note_subtotal_column( $document ) ) : ?>
			<th class="column-amount"><?php _e( 'Subtotal', 'yith-woocommerce-pdf-invoice' ); ?></th>
		<?php endif; ?>

		<?php if ( ywpi_is_enabled_credit_note_total_tax_column( $document ) ) : ?>
			<th class="column-amount"><?php _e( 'Total tax', 'yith-woocommerce-pdf-invoice' ); ?></th>
		<?php endif; ?>

		<?php if ( ywpi_is_enabled_credit_note_total_column( $document ) ) : ?>
			<th class="column-amount"><?php _e( 'Total', 'yith-woocommerce-pdf-invoice' ); ?></th>
		<?php endif; ?>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="column-refund-text">

			<?php
			$refund_text = get_option( 'ywpi_credit_note_refund_text', '' );
			echo $refund_text ? $refund_text : __( 'Your refund', 'yith-woocommerce-pdf-invoice' );

			if ( ywpi_is_enabled_credit_note_reason_column( $document ) ) : ?>
				<br>
				<i><?php echo $order->get_refund_reason(); ?></i>
			<?php endif; ?>
		</td>

		<?php if ( ywpi_is_enabled_credit_note_subtotal_column( $document ) ) : ?>
			<td class="column-amount">
				<?php echo wc_price( $order->get_subtotal() ); ?>
			</td>
		<?php endif; ?>

		<?php if ( ywpi_is_enabled_credit_note_total_tax_column( $document ) ) : ?>
			<td class="column-amount">
				<?php echo wc_price( $order->get_total_tax() ); ?>
			</td>
		<?php endif; ?>

		<?php if ( ywpi_is_enabled_credit_note_total_column( $document ) ) : ?>
			<td class="column-amount">
				<?php echo wc_price( $order->get_total() ); ?>
			</td>
		<?php endif; ?>

	</tr>
	</tbody>
</table>

