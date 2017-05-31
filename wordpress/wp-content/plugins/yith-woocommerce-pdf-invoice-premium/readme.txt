=== YITH WooCommerce PDF Invoice and Shipping List Premium ===

Contributors: yithemes
Tags: woocommerce, orders, woocommerce order, pdf, invoice, pdf invoice, delivery note, pdf invoices, automatic invoice, download, download invoice, bill order, billing, automatic billing, order invoice, billing invoice, new order, processing order, shipping list, shipping document, delivery, packing slip, transport document,  delivery, shipping, order, shop, shop invoice, customer, sell, invoices, email invoice, packing slips
Requires at least: 4.0
Tested up to: 4.7.4
Stable tag: 1.4.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

= Version 1.4.12 - Released: May 03, 2017 =

* New: show delivery data in invoices when used with YITH WooCommerce Delivery Date plugin
* Fix: when tax column is enabled, an error is thrown if free shipping method is used
* Tweak; invoice layout for note field
* Dev: added action 'yith_ywpi_after_document_notes' in notes field

= Version 1.4.11 - Released: Apr 27, 2017 =

* Fix: cannot delete invoice once is created with WooCommerce 3.
* Fix: invoice not attached to outgoing emails with WooCommerce 3.
* Fix: wrong link for parent order when using YITH Multi Vendor and WooCommerce 3.0+.

= Version 1.4.10 - Released: Apr 08, 2017 =

* Fix: on WC 3.0, checkout error if an invoice is created on new order containing variable products.

= Version 1.4.9 - Released: Mar 28, 2017 =

* Fix: fatal error if mPDF class exists
* Fix: YITH Plugin Framework initialization.

= Version 1.4.8 - Released: Mar 15, 2017 =

* Fix: packing slip not rendered if the option for showing product size is enabled.

= Version 1.4.7 - Released: Mar 07, 2017 =

* New:  Support to WooCommerce 2.7.0-RC1
* Update: YITH Plugin Framework
* Fix: taxes amount calculation for shipping fee.
* Fix: Thai characters not shown correctly on invoice.
* Fix: pro-forma documents did not use the same layout of invoices

= Version 1.4.6 - Released: Feb 20, 2017 =

* Tweak: shipping costs and additional fees are shown on packing slip by default.
* Tweak: show a notice for pro-forma invoices not available when used with YITH Multi Vendor.

= Version 1.4.5 - Released: Feb 01, 2017 =

* New: integration with YITH Multi Vendor, show main order number in sub order invoices.
* Tweak: integration with YITH Multi Vendor, hide the metabox in admin's order pages if the invoicing for vendor orders is disabled.
* Fix: integration with YITH Multi Vendor, vendors cannot delete invoices.
* Dev: filter 'yith_ywpi_can_create_document' lets third party plugin to set if specific document could be created
* Dev: filter 'yith_ywpi_delete_document_capabilities' lets third party plugin to set the user capability that enable document deletion.

= Version 1.4.4 - Released: Jan 16, 2017 =

* New: invoice number can be set dynamically using the additional placeholders [year], [month] and [day]
* Fix: invoice generation failed if a product was deleted
* Dev: new filter 'yith_ywpi_set_document_date' for overriding the document date to be set in invoices and credit notes
* Dev: new filter 'yith_ywpi_image_path' for overriding the images shown in documents

= Version 1.4.3 - Released: Dec 07, 2016 =

* Added: ready for WordPress 4.7
* Fixed: proforma documents not attached to emails automatically

= Version 1.4.2 - Released: Nov 23, 2016 =

* Added: new option for showing weight and dimension of products in packing slip documents

= Version 1.4.1 - Released: Oct 31, 2016 =

* Fixed: DropBox sync fails on new document generation

= Version 1.4.0 - Released: Oct 11, 2016 =

* Added: manage refunds with credit notes
* Added: new templates hierarchy
* Added: all templates are customizable
* Added: compatibility with a wide range of character set
* Updated: changed the PDF library used from DOMPDF to MPDF

= Version 1.3.16 - Released: Aug 12, 2016 =

* Added: new PDF module

= Version 1.3.16 - Released: Aug 12, 2016 =

* Fixed: next invoice number updated when the invoice creation failed
* Fixed: conflict issue with DropBox library already instantiated

= Version 1.3.15 - Released: Jul 27, 2016 =

* Added: option for mandatory SSN number on checkout
* Added: option for mandatory VAT number on checkout

= Version 1.3.14 - Released: Jul 04, 2016 =

* Updated: the company logo is retrieved from the server path instead of the public path
* Updated: catalog file
* Updated: italian translation file

= Version 1.3.13 - Released: Jun 20, 2016 =

* Fixed: image not shown and other issue with DOMPDF library

= Version 1.3.12 - Released: Jun 14, 2016 =

* Added: WooCommerce 2.6 ready
* Fixed: in the YITH Multi Vendor plugin, the vendor was unable to set its own company logo

= Version 1.3.11 - Released: Apr 29, 2016 =

* Added: 100% compatibility with YITH WooCommerce Account Funds
* Added: filter yith_ywpi_print_document_notes for notes on invoices
* Added: filter that could hide buttons on plugin metabox
* Added: filter that could hide buttons on orders back-end page
* Added: filter that could hide pro-forma button on myaccount page

= Version 1.3.10 - Released: Apr 14, 2016 =

* Added: support for invoices made for the YITH WooCommerce Funds plugin
* Added: option for generating and attaching the pro-forma invoice on new order

= Version 1.3.9 - Released: Apr 06, 2016 =

* Fixed: the percentage discount column in the invoice shows the discounted percentage instead of the discount percentage

= Version 1.3.8 - Released: Apr 05, 2016 =

* Added: option that let you show a "discount percentage" column on invoice
* Added: option that let you show the order subotal inclusive or exclusive of the order discount
* Added: option that let you choose if the discount amount should be shown on the order summary

= Version 1.3.7 - Released: Mar 16, 2016 =

* Added: optionally show a column on invoice with total taxed
* Tweaked: huge improvement on resulting file size, reduced to few KB
* Added: option for enabling Unicode charset support(need to be disabled in order to have smaller image size)
* Updated: plugin catalog file

= Version 1.3.6 - Released: Mar 14, 2016 =

* Fixed: sanitize document file name
* Fixed: invoice number not incremented on automatic invoice
* Updated: yith-woocommerce-pdf-invoice.pot model file

= Version 1.3.5 - Released: Mar 04, 2016 =

* Fixed: download of documents from order page

= Version 1.3.4 - Released: Mar 03, 2016 =

* Fixed: unable to download the invoice on my-account page
* Fixed: missing button for invoice creation on orders page
* Updated: file yith-woocommerce-pdf-invoice.pot

= Version 1.3.3 - Released: Mar 01, 2016 =

* Fixed: missing $product on the invoice template when "show SKU" is enabled
* Fixed: show only valid taxonomy when the variation information should be displayed in the invoice
* Fixed: no file downloaded if "Document generation mode" was set to "Download"

= Version 1.3.2 - Released: Feb 18, 2016 =

* Updated: removed unused plugin options
* Fixed: warning on pro-forma document generation

= Version 1.3.1 - Released: Feb 17, 2016 =

* Fixed: wrong discount applied to the order totals

= Version 1.3.0 - Released: Feb 16, 2016 =

* Updated: plugin ready for WooCommerce 2.5
* Updated: invoice template can be override
* Added: YITH Multi Vendor compatibility: vendors can create their own invoices.
* Added: template system rewritten for improved performance and customization
* Added: customizable Customer billing details with third party postmeta

= Version 1.2.3 - Released: Dec 29, 2015 =

* Fixed: wrong discount calculation when price are entered inclusive of taxes

= Version 1.2.2 - Released: Dec 15, 2015 =

* Fixed: YITH Plugin Framework breaks updates on WordPress multisite
* Fixed: Missing localization for a string in invoice template

= Version 1.2.1 - Released: Dec 11, 2015 =

* Fixed: company logo not shown on invoice for DOMPDF issue

= Version 1.2.0 - Released: Dec 04, 2015 =

* Fixed: VAT number and SSN number not shown on invoice
* Updated: languages file

= Version 1.1.8 - Released: Nov 04, 2015 =

* Fixed: invoice generated and attached to emails not related to orders
* Updated : text-domain changed from ywpi to yith-woocommerce-pdf-invoice

= Version 1.1.7 - Released: Sep 30, 2015 =

* Fix: typo on invoice template
* Fix: wrong invoice number shown.

= Version 1.1.6 - Released: Sep 01, 2015 =

* Fix: removed deprecated WooCommerce_update_option_X hook.

= Version 1.1.5 - Released: Aug 27, 2015 =

* Tweak: update YITH Plugin framework.

= Version 1.1.4 - Released: Jul 28, 2015 =

* Added : new original product price column for invoices.

= Version 1.1.3 - Released: Jun 19, 2015 =

* Added : some placeholders for invoice prefix and suffix.

= Version 1.1.2 - Released: May 22, 2015 =

* Added : improved unicode support.

= Version 1.1.1 - Released: Apr 24, 2015 ==

* Tweak : invoice and pro-forma invoice template updated.

= Version 1.1.0 - Released: Apr 22, 2015 ==

* Fix : security issue (https://make.wordpress.org/plugins/2015/04/20/fixing-add_query_arg-and-remove_query_arg-usage/)
* Tweak : support up to Wordpress 4.2

= Version 1.0.5 - Released: Apr 20, 2015 ==

* Added : optionally display short description column.

= Version 1.0.4 - Released: Apr 15, 2015 ==

* Added : compatibility with WooThemes EU VAT Number plugin.

= Version 1.0.3 - Released: Apr 07, 2015 ==

* Fix : documents with greek text could not be rendered correctly.

= Version 1.0.2 - Released: Mar 05, 2015 ==

* Initial release