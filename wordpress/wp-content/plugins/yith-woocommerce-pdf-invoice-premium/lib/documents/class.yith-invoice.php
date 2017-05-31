<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YITH_Invoice' ) ) {
	
	/**
	 * Implements features related to a PDF document
	 *
	 * @class   YITH_Invoice
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_Invoice extends YITH_Document {
		
		/**
		 * @var string date of creation for the current invoice
		 */
		public $date;
		
		/**
		 * @var string the document number
		 */
		public $number;
		
		/**
		 * @var string the document prefix
		 */
		public $prefix;
		
		/**
		 * @var string the document suffix
		 */
		public $suffix;
		
		/**
		 * @var string the document formatted number
		 */
		public $formatted_number = '';
		
		/**
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @param int $order_id int the order for which an invoice is creating
		 *
		 * @since  1.0
		 * @author Lorenzo giuffrida
		 * @access public
		 */
		public function __construct( $order_id = 0 ) {
			
			/**
			 * Call base class constructor
			 */
			parent::__construct ( $order_id );
			
			/**
			 *  Fill invoice information from a previous invoice is exists or from general plugin options plus order related data
			 * */
			$this->init_document ();
		}
		
		/**
		 * Check if the document is associated to a valid order
		 *
		 * @return bool
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function is_valid() {
			
			return $this->order && $this->order instanceof WC_Order;
		}
		
		/**
		 * Check if this document has been generated
		 *
		 * @return bool
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function generated() {
			return $this->is_valid () && yit_get_prop ( $this->order, '_ywpi_invoiced', true );
		}
		
		/*
		 * Check if an invoice exist for current order and load related data
		 */
		private function init_document() {
			
			if ( ! $this->is_valid () ) {
				return;
			}
			
			if ( $this->generated () ) {
				$this->number           = yit_get_prop ( $this->order, '_ywpi_invoice_number', true );
				$this->prefix           = yit_get_prop ( $this->order, '_ywpi_invoice_prefix', true );
				$this->suffix           = yit_get_prop ( $this->order, '_ywpi_invoice_suffix', true );
				$this->formatted_number = yit_get_prop ( $this->order, '_ywpi_invoice_formatted_number', true );
				$this->date             = yit_get_prop ( $this->order, '_ywpi_invoice_date', true );
				$this->save_path        = yit_get_prop ( $this->order, '_ywpi_invoice_path', true );
				$this->save_folder      = yit_get_prop ( $this->order, '_ywpi_invoice_folder', true );
			}
		}
		
		/**
		 * Cancel current document
		 */
		public function reset() {
			
			yit_delete_prop ( $this->order, '_ywpi_invoiced' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_number' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_prefix' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_suffix' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_formatted_number' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_path' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_folder' );
			yit_delete_prop ( $this->order, '_ywpi_invoice_date' );
		}
		
		/**
		 * Retrieve the formatted order date
		 *
		 */
		public function get_formatted_document_date() {
			$date = '';
			if ( $this->order ) {
				$format = ywpi_get_option ( 'ywpi_invoice_date_format' );
				$date   = date ( $format, strtotime ( $this->date ) );
			}
			
			return $date;
		}
		
		/**
		 * Retrieve the formatted document number
		 *
		 * @return mixed|string|void
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_formatted_document_number() {
			
			return $this->formatted_number;
			
		}
		
		/**
		 * Save invoice data
		 */
		public function save() {
			yit_save_prop ( $this->order,
				array(
					'_ywpi_invoiced'                 => true,
					'_ywpi_invoice_prefix'           => $this->prefix,
					'_ywpi_invoice_suffix'           => $this->suffix,
					'_ywpi_invoice_number'           => $this->number,
					'_ywpi_invoice_formatted_number' => $this->formatted_number,
					'_ywpi_invoice_date'             => $this->date,
					'_ywpi_invoice_path'             => $this->save_path,
					'_ywpi_invoice_folder'           => $this->save_folder,
				) );
			                 		
		}
	}
}