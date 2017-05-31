<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YITH_YWPI_Multivendor_Loader' ) ) {
	
	/**
	 * Implements features related to an invoice document
	 *
	 * @class   YITH_YWPI_Multivendor_Loader
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_YWPI_Multivendor_Loader {
		
		/**
		 * @var object $_panel Panel Object
		 */
		protected $_panel;
		
		/**
		 * @var string Yith WooCommerce Pdf invoice panel page
		 */
		protected $_panel_page = 'yith-plugins_page_pdf_invoice_for_multivendor';
		
		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 */
		protected static $instance;
		
		/**
		 * @var bool if vendors can manage invoices for their orders
		 */
		public $vendor_invoice_enabled = false;
		
		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null ( self::$instance ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		public function __construct() {
			
			if ( ! defined ( 'YITH_WPV_PREMIUM' ) ) {
				return;
			}
			
			$this->vendor_invoice_enabled = "yes" == get_option ( 'yith_wpv_vendors_enable_pdf_invoice', false );
			
			//  If vendors can't create invoices for their own orders, remove all buttons
			if ( ! $this->vendor_invoice_enabled ) {
				
				add_filter ( 'yith_ywpi_show_invoice_button_order_list', array(
					$this,
					'remove_invoice_button_on_order_list'
				), 10, 2 );
				
				add_filter ( 'yith_ywpi_show_invoice_button_order_page', array(
					$this,
					'remove_invoice_button_on_order_page'
				), 10, 2 );
				
				add_filter ( 'yith_ywpi_show_metabox_for_order', array(
					$this,
					'show_metabox_on_suborders'
				), 10, 2 );
				
			} else {
				//  Vendors can create and manage invoices
				
				/**
				 * Create a plugin option menu for vendors
				 */
				add_action ( 'admin_menu', array( $this, 'register_panel' ), 5 );
				
				/**
				 * Modify the option name for YITH MultiVendor compatibility to fit the single vendor option name
				 */
				add_filter ( 'ywpi_option_name', array( $this, 'vendor_option_name' ), 10, 2 );
				
				/**
				 * Set the storing folder for the vendor
				 */
				add_filter ( 'ywpi_storing_folder', array( $this, 'get_vendor_storing_folder' ), 10, 2 );
				
				/**
				 * Filter the order items by vendor
				 */
				add_filter ( 'yith_ywpi_get_order_items_for_invoice', array(
					$this,
					'get_order_items_for_invoice'
				), 10, 2 );
				
				/**
				 * Filter the order fee by vendor
				 */
				add_filter ( 'yith_ywpi_get_order_fee_for_invoice', array(
					$this,
					'get_order_fee_and_shipping_for_invoice'
				), 10, 2 );
				
				/**
				 * Filter the order shipping by vendor
				 */
				add_filter ( 'yith_ywpi_get_order_shipping_for_invoice', array(
					$this,
					'get_order_fee_and_shipping_for_invoice'
				), 10, 2 );
				
				/**
				 * Remove the default buttons related to  YITH PDF Invoice on "my-account" page
				 */
				add_filter ( 'yith_ywpi_my_order_actions', array( $this, 'show_multi_invoice_button' ), 10, 2 );
				
				/**
				 * Show a table with invoice information in case of orders with suborders
				 */
				add_action ( 'woocommerce_order_details_after_order_table', array( $this, 'show_suborder_invoice' ) );
				
				/**
				 * Disable the automatically generated invoices
				 */
				add_filter ( 'yith_ywpi_create_automatic_invoices', array( $this, 'stop_automatic_invoices' ), 10, 2 );
				
				/**
				 * Get the corrected value for an invoice subtotal
				 */
				add_filter ( 'yith_ywpi_invoice_subtotal', array( $this, 'get_parent_order_subtotal' ), 10, 4 );
				
				/**
				 * Get the corrected value for an invoice subtotal
				 */
				add_filter ( 'yith_ywpi_invoice_subtotal', array( $this, 'get_parent_order_subtotal' ), 10, 4 );
				
				/**
				 * Get the corrected value for order total taxes
				 */
				add_filter ( 'yith_ywpi_invoice_tax_totals', array( $this, 'get_parent_order_taxes' ), 10, 2 );
				
				add_action ( 'yith_ywpi_template_order_number', array(
					$this,
					'show_suborder_number_in_document'
				) );
				
				add_filter ( 'yith_ywpi_delete_document_capabilities', array(
					$this,
					'enable_document_deletion'
				), 10, 3 );
			}
			
			add_action ( 'yith_wcmv_after_suborder_details', array( $this, 'show_order_invoiced_status' ) );
			
			add_filter ( 'yith_ywpi_show_packing_slip_button_order_page', array(
				$this,
				'show_packing_slip_buttons_in_order'
			), 10, 2 );
			
			add_filter ( 'ywpi_general_options', array(
				$this,
				'notify_pro_forma_option_disabled'
			) );
		}
		
		public function notify_pro_forma_option_disabled( $options ) {
			$options['general']['pro-forma']['desc'] .= '<br><b>' . __ ( 'This feature is not available when used with YITH Multi Vendor plugin.', 'yith-woocommerce-pdf-invoice' ) . '</b>';
			
			return $options;
		}
		
		/**
		 * Enable document deletion for vendors, adding their own capabilities to allowed capabilities
		 *
		 * @param array  $capabilities
		 * @param int    $order_id
		 * @param string $document_type
		 *
		 * @return array
		 */
		public function enable_document_deletion( $capabilities, $order_id, $document_type ) {
			$capabilities[] = YITH_Vendors ()->admin->get_special_cap ();
			
			return $capabilities;
			
		}
		
		/**
		 * Set the visibility for packing slip buttons in sub orders
		 *
		 * @param bool     $visible
		 * @param WC_Order $order
		 *
		 * @return bool
		 */
		public function show_packing_slip_buttons_in_order( $visible, $order ) {
			$current_order_id = yit_get_prop ( $order, 'id' );
			$parent_order_id  = get_post_field ( 'post_parent', $current_order_id );
			
			if ( $parent_order_id ) {
				$visible = false;
			}
			
			return $visible;
		}
		
		/**
		 * Set if the metabox with the invoicing buttons should be shown for a specific
		 * order
		 *
		 * @param bool    $visible
		 * @param WP_Post $post
		 *
		 * @return bool
		 */
		public function show_metabox_on_suborders( $visible, $post ) {
			
			if ( $post->post_parent ) {
				$visible = false;
			}
			
			return $visible;
		}
		
		/**
		 * @param YITH_Document $document
		 */
		public function show_suborder_number_in_document( $document ) {
			
			/**  If there are parent order for the current document order being generated,
			 * show the parent order number in invoice
			 * */
			$current_order_id = yit_get_prop ( $document->order, 'id' );
			$parent_order_id  = get_post_field ( 'post_parent', $current_order_id );
			
			if ( $parent_order_id && apply_filters ( 'yith_ywpi_show_parent_order_number', true, $document ) ) {
				$parent_order = wc_get_order ( $parent_order_id );
				echo '<br><span style="font-size: small">' . sprintf ( __ ( '(Main order #%s)', 'yith-woocommerce-pdf-invoice' ), $parent_order->get_order_number () ) . '</span>';
			}
		}
		
		/**
		 * Check if a vendor is related to a super user
		 *
		 * @param YITH_Vendor $vendor
		 *
		 * @return mixed
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function is_vendor_super_admin( $vendor ) {
			return $vendor->is_super_user ( $vendor->get_owner () );
		}
		
		
		/**
		 * Retrieve the amount of taxes for the parent order
		 *
		 * @param float    $tax_totals
		 * @param WC_Order $order
		 *
		 * @return mixed
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 *
		 */
		public function get_parent_order_taxes( $tax_totals, $order ) {
			$sub_orders = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
			
			if ( ! $sub_orders ) {
				return $tax_totals;
			}
			
			$order_taxes = array();
			
			$parent_order_items = $order->get_items ( array( 'line_item', 'fee' ) );
			
			foreach ( $parent_order_items as $item_id => $item ) {
				
				if ( isset( $item["product_id"] ) ) {
					$product_id = $item["product_id"];
					$vendor     = yith_get_vendor ( $product_id, 'product' );
					
					if ( $vendor->is_valid () && ! $this->is_vendor_super_admin ( $vendor ) ) {
						continue;
					}
				}
				
				//  for the product there aren't vendor or the vendor is the admin, so the tax amount should be used
				$line_tax_data = maybe_unserialize ( $item['line_tax_data'] );
				
				if ( isset( $line_tax_data['total'] ) ) {
					
					foreach ( $line_tax_data['total'] as $tax_rate_id => $tax ) {
						
						if ( ! isset( $order_taxes[ $tax_rate_id ] ) ) {
							$order_taxes[ $tax_rate_id ] = 0;
						}
						
						$order_taxes[ $tax_rate_id ] += $tax;
					}
				}
			}
			
			foreach ( $order->get_items ( array( 'shipping' ) ) as $item_id => $item ) {
				
				$line_tax_data = maybe_unserialize ( $item['taxes'] );
				
				if ( isset( $line_tax_data ) ) {
					foreach ( $line_tax_data as $tax_rate_id => $tax ) {
						
						if ( ! isset( $order_taxes[ $tax_rate_id ] ) ) {
							$order_taxes[ $tax_rate_id ] = 0;
						}
						
						$order_taxes[ $tax_rate_id ] += $tax;
					}
				}
			}
			
			$results = array();
			foreach ( ( $order_taxes ) as $tax_rate_id => $tax_amount ) {
				if ( ! isset( $results[ $tax_rate_id ] ) ) {
					$obj = new stdClass;
					
					$obj->label              = WC_Tax::get_rate_label ( $tax_rate_id );
					$obj->amount             = 0;
					$results[ $tax_rate_id ] = $obj;
				}
				
				$results[ $tax_rate_id ]->amount += $tax_amount;
			}
			
			return $results;
		}
		
		/**
		 * Get the correct value for subtotal in parent order invoice, removing all the items that do not belong to the parent order
		 *
		 * @param float    $subtotal
		 * @param WC_order $order
		 * @param float    $product_discount
		 * @param float    $order_fee_amount
		 *
		 * @return float
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_parent_order_subtotal( $subtotal, $order, $product_discount, $order_fee_amount ) {
			
			$sub_orders = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
			
			if ( ! $sub_orders ) {
				return $subtotal;
			}
			
			return $this->calculate_parent_order_subtotal ( $order, $product_discount, $order_fee_amount );
		}
		
		/**
		 * Calculate the parent order subtotal
		 *
		 * @param WC_order $order
		 * @param float    $product_discount
		 * @param float    $order_fee_amount
		 *
		 * @return int
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function calculate_parent_order_subtotal( $order, $product_discount, $order_fee_amount ) {
			
			$order_items = $this->get_parent_order_items ( $order );
			
			$subtotal = 0;
			
			foreach ( $order_items as $item ) {
				$subtotal += ( isset( $item['line_subtotal'] ) ) ? $item['line_subtotal'] : 0;
			}
			
			$total_discount = $this->get_parent_order_total_discount ( $order->get_total_discount (), $order, $product_discount );
			
			return $subtotal + $total_discount + $order_fee_amount + $order->get_total_shipping ();
		}
		
		/**
		 * Calculate the total discount in parent order invoice
		 *
		 * @param WC_order $order
		 * @param float    $product_discount
		 *
		 * @return float
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function calculate_parent_order_total_discount( $order, $product_discount ) {
			
			$sub_orders = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
			
			$total_discount = $order->get_total_discount ();
			foreach ( $sub_orders as $order_id ) {
				$_order = wc_get_order ( $order_id );
				$total_discount -= $_order->get_total_discount ();
			}
			
			return $total_discount + $product_discount;
		}
		
		/**
		 * Get the correct value for total discount in parent order invoice
		 *
		 * @param float    $total_discount
		 * @param WC_order $order
		 * @param float    $product_discount
		 *
		 * @return float
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_parent_order_total_discount( $total_discount, $order, $product_discount ) {
			
			$sub_orders = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
			
			if ( ! $sub_orders ) {
				return $total_discount;
			}
			
			return $this->calculate_parent_order_total_discount ( $order, $product_discount );
		}
		
		/**
		 * show the invoice information for a suborder
		 *
		 * @param WC_Order $order
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function show_order_invoiced_status( $order ) {
			
			YITH_PDF_Invoice ()->show_invoice_information_link ( $order );
		}
		
		/**
		 * Disable the automatically generated invoices
		 */
		public function stop_automatic_invoices( $enable, $order_id ) {
			return false;
		}
		
		/**
		 * Remove the invoice button on orders list page for the vendors
		 *
		 * @param WC_Order $order
		 * @param string   $html
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function remove_invoice_button_on_order_list( $html, $order ) {
			
			$post_author = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
			$vendor      = yith_get_vendor ( $post_author, 'user' );
			
			if ( ! $this->is_vendor_super_admin ( $vendor ) ) {
				return '';
			}
			
			return $html;
		}
		
		/**
		 * Remove the invoice button on order page for the vendors
		 *
		 * @param bool     $enabled
		 * @param WC_Order $order
		 *
		 * @return bool
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function remove_invoice_button_on_order_page( $enabled, $order ) {
			
			$post_author = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
			$vendor      = yith_get_vendor ( $post_author, 'user' );
			
			if ( ! $this->is_vendor_super_admin ( $vendor ) ) {
				return false;
			}
			
			return $enabled;
		}
		
		/**
		 * Show a single row for invoice related infomation
		 *
		 * @param WC_Order $order
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		private function show_multivendor_invoice_row( $order ) {
			$is_admin_order = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
			
			?>
			<tr>
				<td>
					<?php
					$post_author = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
					$vendor      = yith_get_vendor ( $post_author, 'user' );
					if ( $vendor->is_valid () ) {
						echo $vendor->name;
					} elseif ( $is_admin_order ) {
						echo apply_filters ( 'yith_ywpi_my_account_admin_as_vendor_title', __ ( '<b>Current shop</b>', 'yith-woocommerce-pdf-invoice' ) );
					}
					?>
				</td>
				<td>
					<?php if ( $this->order_has_invoice ( $order, false ) ): ?>
						<a target="_blank"
						   href="<?php echo YITH_PDF_Invoice ()->get_action_url ( 'view', 'invoice', yit_get_prop ( $order, 'id' ) ); ?>">
							<?php _e ( "Download", 'yith-woocommerce-pdf-invoice' ); ?></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
		
		/**
		 * register action to show tracking information on email for completed orders
		 *
		 * @param WC_Order $order the order whose details should be shown
		 */
		public function show_suborder_invoice( $order ) {
			
			$result = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
			
			if ( ! $result ) {
				return;
			}
			?>
			<h2><?php _e ( "Invoice status", 'yith-woocommerce-pdf-invoice' ); ?></h2>
			<table class="shop_table shop_table_responsive multi_vendor_invoice" id="multi_vendor_invoice">
				<thead>
				<tr class="multi_vendor_invoice_header">
					<th><?php _e ( "Vendor", 'yith-woocommerce-pdf-invoice' ); ?></th>
					<th><?php _e ( "Invoice", 'yith-woocommerce-pdf-invoice' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php $this->show_multivendor_invoice_row ( $order );
				
				foreach ( $result as $suborder_id ) {
					$suborder = wc_get_order ( $suborder_id );
					$this->show_multivendor_invoice_row ( $suborder );
				}
				?>
				
				</tbody>
			</table>
			<?php
			
		}
		
		/**
		 * Show invoice buttons according to the order type and status
		 *
		 * @param array    $actions
		 * @param WC_Order $order
		 *
		 * @return array
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 *
		 */
		public function show_multi_invoice_button( $actions, $order ) {
			
			//  if there are suborders, remove standard buttons
			if ( YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) ) ) {
				unset( $actions['print-invoice'] );
				unset( $actions['print-pro-forma-invoice'] );
				
				if ( $this->order_has_invoice ( $order ) ) {
					$actions['view-mv-invoice'] = array(
						'url'  => $order->get_view_order_url () . '#ywpi-invoice-details',
						'name' => __ ( 'View Invoice', 'yith-woocommerce-pdf-invoice' ),
					);
				}
			}
			
			return $actions;
		}
		
		/**
		 * Check if an order or (optionally) one of its suborders has an invoiced attached
		 *
		 * @param WC_Order  $order
		 * @param bool|true $recursive
		 *
		 * @return bool
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function order_has_invoice( $order, $recursive = true ) {
			
			$invoice = new YITH_Invoice( yit_get_prop ( $order, 'id' ) );
			if ( $invoice->generated () ) {
				return true;
			}
			
			if ( $recursive ) {
				$sub_orders = YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) );
				foreach ( $sub_orders as $sub_order_id ) {
					$invoice = new YITH_Invoice( $sub_order_id );
					if ( $invoice->generated () ) {
						
						return true;
					}
				}
			}
			
			return false;
		}
		
		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {
			
			if ( ! empty( $this->_panel ) ) {
				return;
			}
			
			$admin_tabs['vendor'] = __ ( 'Vendor settings', 'yith-woocommerce-pdf-invoice' );
			
			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => __ ( 'PDF Invoice', 'yith-woocommerce-pdf-invoice' ),
				'menu_title'       => __ ( 'PDF Invoice', 'yith-woocommerce-pdf-invoice' ),
				'capability'       => YITH_Vendors ()->admin->get_special_cap (),
				'parent'           => '',
				'parent_page'      => '',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'icon_url'         => 'dashicons-admin-settings',
				'options-path'     => YITH_YWPI_DIR . '/plugin-options',
			);
			
			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}
		
		/**
		 * Modify the option name for YITH MultiVendor compatibility to fit the single vendor option name
		 *
		 * @param string $option_name the name of the option
		 * @param mixed  $obj
		 *
		 * @return string
		 */
		public function vendor_option_name( $option_name, $obj = null ) {
			
			$vendor = null;
			
			$vendor_option_names = array(
				'ywpi_invoice_number',
				'ywpi_invoice_year_billing',
				'ywpi_invoice_prefix',
				'ywpi_invoice_suffix',
				'ywpi_invoice_number_format',
				'ywpi_invoice_reset',
				'ywpi_company_name',
				'ywpi_company_logo',
				'ywpi_company_details',
				'ywpi_invoice_notes',
				'ywpi_invoice_footer',
				'ywpi_pro_forma_notes',
				'ywpi_pro_forma_footer',
				'ywpi_packing_slip_notes',
				'ywpi_packing_slip_footer',
			);
			
			if ( ! in_array ( $option_name, $vendor_option_names ) ) {
				
				return $option_name;
			}
			
			/**
			 * If $obj_id == 0, retrieve the vendor option name based on current user
			 */
			if ( $obj instanceof WC_Order ) {
				
				$order = wc_get_order ( $obj );
				
				if ( $order ) {
					$post_author = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
					$vendor      = yith_get_vendor ( $post_author, 'user' );
				}
			} else if ( $obj instanceof YITH_Document ) {
				
				$order = wc_get_order ( $obj->order );
				
				if ( $order ) {
					$post_author = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
					$vendor      = yith_get_vendor ( $post_author, 'user' );
				}
			} else if ( $obj == 0 ) {
				$vendor = yith_get_vendor ( get_current_user_id (), 'user' );
			}
			
			if ( $vendor && $vendor->is_valid () ) {
				
				$option_name = $option_name . '_' . $vendor->id;
			}
			
			return $option_name;
		}
		
		/**
		 * Set the storing folder for the vendor
		 *
		 * @param string        $folder_path current folder path
		 * @param YITH_Document $document    the document to save
		 *
		 * @return string the vendor storing folder
		 */
		public function get_vendor_storing_folder( $folder_path, $document ) {
			if ( $document->is_valid () ) {
				$post_author = get_post_field ( 'post_author', yit_get_prop ( $document->order, 'id' ) );
				$vendor      = yith_get_vendor ( $post_author, 'user' );
				
				if ( $vendor->is_valid () ) {
					$folder_path = sprintf ( "%s/%s", $vendor->id, $folder_path );
				}
				
			}
			
			return $folder_path;
		}
		
		/**
		 * Retrieve the fee to use for the invoice
		 *
		 * @param array    $items
		 * @param WC_Order $order
		 *
		 * @return array
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_order_fee_and_shipping_for_invoice( $items, $order ) {
			//  The order fee are always associated to the main order
			
			//  If there are suborders, then it's a main order and the fees are used
			if ( YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) ) ) {
				return $items;
			}
			
			$post_author = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
			$vendor      = yith_get_vendor ( $post_author, 'user' );
			if ( $this->is_vendor_super_admin ( $vendor ) ) {
				return $items;
			}
			
			return array();
		}
		
		/**
		 * Retrieve the items to use for the invoice
		 *
		 * @param array    $items
		 * @param WC_Order $order
		 *
		 * @return array
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_order_items_for_invoice( $items, $order ) {
			//  For vendor's orders or admin order's without suborders, return all the items
			if ( ! YITH_Orders_Premium::get_suborder ( yit_get_prop ( $order, 'id' ) ) ) {
				return $items;
			}
			
			return $this->get_parent_order_items ( $order );
		}
		
		/**
		 * Retrieve the parent order items, without the order items that belong to other vendors
		 *
		 * @param WC_order $order
		 *
		 * @return array
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_parent_order_items( $order ) {
			
			//  for orders with multiple vendors, filter the resulting items by main order vendor or admin alternativly
			$post_author      = get_post_field ( 'post_author', yit_get_prop ( $order, 'id' ) );
			$vendor           = yith_get_vendor ( $post_author, 'user' );
			$items_by_vendors = YITH_Orders_Premium::get_order_items_by_vendor ( yit_get_prop ( $order, 'id' ) );
			
			if ( $vendor->is_valid () ) {
				
				if ( isset( $items_by_vendors[ $vendor->id ] ) ) {
					
					return $items_by_vendors[ $vendor->id ];
				}
			} else {
				
				//  returns items that are not related to any vendor(so the admin will manage it)
				if ( isset( $items_by_vendors[0] ) ) {
					return $items_by_vendors[0];
				}
			}
			
			return array();
		}
	}
}

YITH_YWPI_Multivendor_Loader::get_instance ();