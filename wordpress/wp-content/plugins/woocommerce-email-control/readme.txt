=== Plugin Name ===
Contributors: cxThemes
Tags: woocommerce, email, customize, customise, edit, colors, text, preview, template, communication, send, test
Stable tag: 3.09
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce Email Customizer plugin allows you to fully customize the styling, colors, logo and text in the emails sent from your WooCommerce store.

== Description ==

Create Beautiful Customized WooCommerce Emails:
Email Customizer enables full customization of your WooCommerce emails. Customize colors, header & footer format, add custom links, link to your social networks, and now even customize what the email says. You no longer need to be a developer to do this.

Currently customization is something that only a developer can do by going into the code and editing the template files, which isn't really an option for a non-programmer. We wanted to give you an environment that is simple to use, gives you a live preview of your customizations, and can send a test email when you are done. That's what Email Customizer for WooCommerce does.

The plugin also adds functionality to your WooCommerce Orders page so you are able to open a preview of any of the email templates (New Order, Invoice, Processing Order, etc), and send/resend that email to your customer or yourself.

Email Customizer for WooCommerce has made managing the email communications sent from our store much simpler and more beautiful - making our whole operation look and sounds as solid as it is. We think it can do the same for you.

Great For
* Customizing of the  the styling, colors, header & footer format, add custom links, link to your social networks, and now even customize what the email says.
* Tailor what your customer reads and sees, before you send it - helping your operation to look and sound as solid as it is.
* Developers who can now also easily preview changes as they develop, modify or enhance their email template files.
* Shop Managers who want to preview & send/resend emails (New Order, Invoice, etc) right from the WooCommerce Order page.

Happy Conversions!


== Documentation ==

Please see the included PDF for full instructions on how to use this plugin.
 
 
== Changelog ==

= 3.09 =
* Remove the `height="80"` attribute from the product thumbnail images in the emails. We found that in cases where the thumbnail images were not proportionately cropped the images would appear squashed. FYI the product images in the emails use the WordPress 'thumbnail' image crop, which you can set in WordPress > Settings > Media.
* Update the way we get and display the Date after the release of WC 3.0

= 3.08 =
* Updated all email template files (WC3.0 compatibility).
* Use WC Order object methods instead of accessing properties directly (WC3.0 compatibility).
* Add helper functions for WC backwards compat.
* Email Customizer now requires WooCommerce version 2.5 or above.

= 3.07 =
* Fixed issue when using WPML String Translation where each new email sent would add new unique strings to the database causing unwanted bloat.

= 3.06 =
* Added new Template Information (click 'Header & Template Info' in the Email Customizer) shows which templates are used in the email you're previewing, where the templates come from and whether you are overriding the template (via your theme or child-theme). This means that you can more confidently customize our email templates without losing the changes you've made when you update our plugin. For more information see our Customize Emails by Overriding Templates via your Theme documentation here https://www.cxthemes.com/documentation/email-customizer/customize-emails-by-overriding-templates-via-your-theme/.

= 3.05 =
* Added new shortcode `[ec_coupon_code]`.
* Fix issue where shortcodes like `[ec_order]` would not display when using the show or hide settings.
* Replace deprecated function `get_currentuserinfo()` with `wp_get_current_user()`.

= 3.04 =
* We have chosen not to include a .pot (template) with our plugin as it can lead to our .pot being out of date and missing strings that you require, so you can create your own .pot (template). Or you can skip that and go straight to creating your language file. Please save your language files to this location that Loco Translate suggests: /wp-content/languages/plugins/email-control-en_US.po. Please see the /languages/guide.txt for a step-by-step guide.
* Small changes to some of the text, tip text & layout in the Email Customizer.
* Fix issue in very small browser windows where the customizer sections would not be high enough to display the fields inside them.

= 3.03 =
* Added new shortcodes `[ec_delivery_note]` `[ec_shipping_method]` `[ec_payment_method]` `[ec_custom_field]`.
* Allow for customizing of the text on the `[ec_pay_link text="..."]` and `[ec_reset_password_link text="..."]`.
* Read more about these, and all our shortcodes, in our new "Shortcodes" documentation https://www.cxthemes.com/documentation/email-customizer/shortcodes-email-customizer/.

= 3.02 =
* Fixed unique characters being stripped from the urls when saving custom Links.

= 3.01 =
* Fixed curl notice in the email preview when using Email Customizer with 3rd party email sending plugins.
* Fixed notice about `get_image_id()` when using product images in the email templates.
* Fixed issue when sending test emails in the customizer where email would erroneously send to the order email address and not the test email address.

= 3.00 =
* We're excited to announce a new template called Vanilla. It's modern, clean and bold and brings with it responsive layouts so it looks great on mobile email clients that support it. It also brings with it Product Images so your customers can see and get excited about what they're buying. And we didn't just add these features to Vanilla, we've added them to all our templates. We hope you like our new look.
* Added Responsive layouts to all the email templates so they look great on mobile email clients that support it.
* Added Product Images to all the email templates with an option to toggle them on or off.
* Replace tip-tip js plugin with simpler and more conventional 'title' attributes which leads to quicker loading & simpler interface.
* Fixed persistent bug showing a notice about `plain_text` in some versions of WC.

= 2.42 =
* Fix issue where template would show undefined variable `plain_text` when using WC2.5.4 and lower.
* Fixed the Links panel not being able to scroll to the lower links with smaller windows sizes.
* Fixed double slash in the preview URL that would result in the customizer not loading for some people.
* Changed to a new method to render the emails in the preview that uses the email trigger method to capture the rendered email.

= 2.41 =
* Start using WooCommerce Emogrifier class, exclusively, to merge the CSS into the Email HTML. Previously we needed our own version of this class as it was not yet available in older versions of WooCommerce. This means that the emails will be more consistently rendered. It also means you'll need WC version 2.3 or higher.
* Change the minimum required version of WooCommerce to version 2.3

= 2.40 =
* We have unfortunately removed the Email Customizer action box from the WooCommerce Order page. We felt the feature can lead to confusion as it's email-send functionality is a duplication of the WooCommerce Order Actions, and the emails may not send in the case of a note-email, or third-part customer emails. We also felt that previewing of all the order emails is done in the Email Customizer interface so it's not needed that the functionality is duplicated here. We apologise if this anyone is unhappy with what we felt like was a necessary decision.
* Changed the way we render our customizer - using more css and less reliant on JS (which can fail).
* Replaced Fontawesome icon fonts with Fontello to prevent collisions with other plugins and reduce load time by only loading the icons we need.

= 2.39 =
* Fix custom navigation links not working in Deluxe email template.

= 2.38 =
* Added the new Customer Order On-Hold template introduced by WooCommerce.
* Bring all our templates up to date with the WooCommerce email templates.
* Added the template helper functions `ec_special_title()` and `ec_nav_bar()` back into the templates.

= 2.37 =
* We've changed the way we do plugin auto-updates so we can better manage the demand for our plugins and updates. You will now be notified - as usual - about new available plugin updates. Then we'll require you to save your CodeCanyon purchase-code for our plugin - first time only - which will enable this and any future auto-updates. If you're not sure where to get your purchase code - don't worry, it will be explained in the plugin.

= 2.36 =
* Changed the order in which we load our localization translation which should result in previously inactive text being translatable.
* Display a notice if attempting to use our plugin alongside other email template plugins that will conflict with ours.

= 2.35 =
* We've added the new WooCommerce Editable Theme - this empowers the familiar WooCoomerce email template with full text customizations, and the expected color customizations, inside our Email Customizer.
* Added a friendly warning and the simplified email preview when there's not at least one order to preview.
* Updated all the templates with new functions, filters, etc so they are up to date with the latest version of WooCommerce.
* Remove styling of the low_stock, no_stock, backorder emails - they are internal so we're following WooCoomerce lead and not interfering with them.

= 2.34 =
* Allow for previewing of plain text emails in the Email Customizer.
* Make sure our templates don't interfere with the plain text emails.

= 2.33 =
* Bug fix - remove destructive `_log()` function - apologies to everyone.

= 2.32 =
* Fixed issue where on order that was not yet paid would not receive the request-to-pay in the Invoice email.
* Clean up styling of the Customer Details in the email templates.
* Added specific css class names to the Totals rows in the order-item-table.

= 2.31 =
* Fixed so is_woocommerce_active() check also works for multisite installations.

= 2.30 =
* Open all a-href links in the email preview in a new target window, rather than in the preview iframe.
* Change colorpick to ec-colorpick to guard against multiple initializations on some platforms.
* New system that will notify you with a compatibility warning when about to preview unknown email types, and provide options before proceeding.

= 2.29 =
* Refactor the plugin class so plugin is initialized as early as possible. Please let us know if any problems.
* Change how we check WooCommerce version number.

= 2.28 =
* Fixed so translations are back for all strings. Please let us know if we missed any.

= 2.27 =
* Make sure email shortcodes like [ec_order] are applied early as possible - to make sure they work with all Form Based payment gateways e.g. Payment Express.

= 2.26 =
* Apply styles to the WooCommerce emails - backorder, low_stock, no_stock.

= 2.25 =
* Enable support and editing of the newer emails refunded_order and cancelled_order.
* Template spring clean to bring inline with the latest woocommerce template updates.
* Moved the CSS inlining to a helper function rather than in the template.

= 2.24 =
* Change the registering of the templates up the load order from init to plugins_loaded for a consistent template load.

= 2.23 =
* Further improvements to shortcodes so [ec_firstname] and [ec_lastname] can be used in all the emails.

= 2.22 =
* Make sure [ec_shortcodes] are not mixed up across certain bulk email operations.
* Notification in preview when using ec_customer_note outside of Customer Note email where its intended.

= 2.21 =
* Improve shortcodes so Firstname and Lastname can be used in more of the emails, like New Account.
* Display a notification in the email preview when using the order shortcode in the New Account email. WooCommerce has not yet created the order at this point.

= 2.20 =
* Moved css inline-ing into the footer template so it's not reliant on the woocoomerce_email_footer action to be applied. Fixes blank emails in wc smart coupons.

= 2.19 =
* Added Internationalization how-to to the the docs.
* Updated the language files.
* UI Text changes.
* Changes to the order and priority of the loaded language files. Will not effect anyone who is already using internationalization.
* Changed where in the code the WooCommerce and version number checking is done.
* Made more strings translatable.
* Escaped all add_query_args and remove_query_args for security.
* Updated PluginUpdateChecker class.

= 2.18 =
* Changed the way clean CSS is passed to Emogrifier
* Force utf8 format in the email header if for some unique reason it has not been set or has been stripped.
* Change is_woocommerce_active method so it is not interfered with by another plugin to avoid Non-Static notices.
* Avoid writing empty address blocks and headings in the email if they are not set.
* Changed to singleton class initialisation
* Show in the dropdown if there are no Orders to preview.
* Changed name of Emogrifier class to EmogrifierCXEC to not interfere with WC.
* Fix no rendering of the default WC email in the preview.

= 2.17 =
* Rewrite shortcode logic and the way that email args are shared with them.
* Changed default email texts to use the multipurpose ec_order shortcode that works on the front and back end.

= 2.16 =
* Fixed notice with wc_get_template filter.

= 2.15 =
* Changed our WooCommerce version support - you can read all about it here https://helpcx.zendesk.com/hc/en-us/articles/202241041/
* Changed all deprecated woocommerce_ to wc_ functions.
* Changed order queries to use the new order status from WC2.2
* Updated to the newer wc_get_template filter available in WC2.2
* Added an 800 recent order limit showing in the orders dropdown to avoid memory overload on massive queries.

= 2.14 =
* Added [ec_order] shortcode that can be used in admin or user emails and uses the correct order link automatically. It accepts arguments that control it's display e.g. [ec_order show="#, number, date, link, container" hide="date, link"]. (more documentation coming soon)
* Added classes to the shortcodes so they can be individually targeted and styled.
* Changed order number on order dropdown to use get_order_number.
* Updated templates to display download links in only the correct places.
* Changed default method on shortcodes to use parse_args.

= 2.13 =
* Fixed payment details appearing in admin emails.
* Added preprocessing of emails subject during test send so the smart tags are converted and don't stay in the subject of the test email.

= 2.12 =
* Fixed bug notice in email when customer is creating account on first order.

= 2.11 =
* Emogrifier class changes - convert anonymous functions used for preg_replace_callback to be methods to extend support for older versions of php without anonymous functions support.

= 2.10 =
* Added a backup mb_convert_encoding function for cases where older hosting servers do not have have php_mbstring module turned on - please ask that your hosts enable this as you could run into encoding issues when using certain special characters - for this or other plugins that deal with character encoding.

= 2.09 =
* Added Custom CSS customization option so you can have full control over the style of your emails.
* Change css compiler to only pull classes from local <style> block in template to avoid style clashes and compiling errors.
* Added sanitization of css before it is compiled to avoid errors.
* Removed a few of the unwanted notices in WP_DEBUG mode.

= 2.08 =
* Email templates css tweaks.
* Name change from Email Control to Email Customizer.

= 2.07 =
* Improved language translation functionality. Create a folder called email-control in the WordPress language folder and put the appropriately named .mo file, this will override ours and will not be overwritten on plugin update.
* e.g. wp-content/languages/email-control/email-control-en_US.mo

= 2.06 =
* Small css tweaks
* Fixed get_settings function

= 2.05 =
* *** NEW *** Supreme template. loads more customizations - email width, header logo position, custom nav links (eg facebook, twitter, etc), footer logo and layout, and many more.
* Added all the same customizations improvements to the Deluxe template too.
* Added all the untranslated strings so they can now be loclalized (please let us know if we missed any).
* Moved hook 'woocommerce_email_before_order_table' to better position in templates.
* Added shortcode [ec_user_order_link] for user to see their order in their account on your site.

= 2.04 =
* Fixed terminate php block in emogrifier.
* Changed all require includes to relative file paths.

= 2.03 =
* Fixed css causing headings to rendered too small.
* Fixed bug with emogrifier require not finding path.

= 2.02 =
* Fixed bug stopping edit changes saving.

= 2.01 =
* Fixed compatibility bugs with older WooCommerce versions.
* CSS various tweaks.

= 2.0 =
* You can now customize the the styling, colors, logo and text in your emails.

= 1.0 =
* Initial release.
