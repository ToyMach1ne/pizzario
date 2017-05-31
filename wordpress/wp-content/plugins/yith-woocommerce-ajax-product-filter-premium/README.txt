=== YITH WooCommerce Ajax Navigation Premium ===

Contributors: yithemes
Tags: woocommerce, widget, ajax, ajax filtered nav, ajax navigation, ajax filtered navigation
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 3.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog =

= 3.1.3 =

* Tweak: Support for YITH WooCommerce Brands add-on FREE
* Fix: undefined variable $_get_current_filter in premium widget
* Fix: Product image disappears after filter with lazy load option enabled
* Fix: Empty li tag with query type OR in categories filter
* Fix: Reset widget doesn't works with categories
* Fix: product_cat arg empy in query string if remove current category filter
* Fix: Order by doesn't works with filter type list, label and dropdown
* Fix: Order by doesn't works with filter type categories
* Dev: yith_wcan_skip_no_products_in_category hook
* Dev: yith_wcan_force_show_count_in_category hook
* Dev: yith_wcan_brands_enabled hook

= 3.1.2 =

* Added: yit_get_terms_args hook
* Added: yith_wcan_skip_no_products_color hook
* Added: yith_wcan_show_no_products_attributes hook
* Added: yith_wcan_after_reset_widget hook
* Added: yith_wcan_before_reset_widget hook
* Fixed: On Sale widget works only for current products in WordPress 4.7
* Removed: yith_wcan_hide_no_products_attributes hook

= 3.1.1 =

* Fixed: Dropdown option doesn't works with Avada theme
* Fixed: Unable to update to version 3.1.0

= 3.1.0 =

* Added: Support to WordPress 4.7
* Added: yith_wcan_hide_no_products_attributes hook
* Added: Don't show "On Sale filter" if no on sale products are available

= 3.0.13 =

* Fixed: Warning on current category check in filter
* Fixed: Url management with query type set to OR
* Fixed: $instance not defined warning on Categories filter
* Fixed: Close dropdown widget before open another dropdown
* Fixed: Layout issue with color style and round style

= 3.0.12 =

* Added: yith_wcan_dropdown_type hook
* Fixed: Plugin doesn't hide the Filter by price, filter by stock/on-sale, filter sort if no products was found

= 3.0.11 =

* Tweak: Removed deprecated taxonomy count WooCommerce transient
* Fixed: Wrong reset url if filter start from product category page

= 3.0.10 =

* Added: ScrollTop features in Desktop and Mobile
* Fixed: Filter by categories with "Only Parent" display option show all categories
* Fixed: Warning: in_array() expects at least 2 parameters, 1 given with query type set to OR
* Fixed: Widget dropdown doesn't works on Flatsome Theme
* Fixed: Filter by BiColor not show all attributes

= 3.0.9 =

* Added: Support to Ultimate Member plugin
* Fixed: Error on activation "the plugin required WooCommerce in order to works"
* Fixed: Get term issue with old WordPress version

= 3.0.8 =

* Added: Italian and Spanish language files available
* Added: Support to WordPress 4.6RC2
* Tweak: Removed deprecated arg to get_terms function
* Fixed: Empty filter doesn't hide after ajax call
* Fixed: Categories widget doesn't show all categories in archive page
* Fixed: Max execution time issue and 500 internal server error issue

= 3.0.7 =

* Added: yith_wcan_get_list_html_terms hook
* Added: yith_wcan_exclude_category_terms hook
* Fixed: Category widget doesn't show main parent category if this is empty
* Fixed: Wrong products count if "Hide out of stock items from the catalog" are enabled

= 3.0.6 =

* Fixed: Reset button doesn't show after click on a filter

= 3.0.5 =

* Fixed: Unable to override list price filter template by theme
* Fixed: style="displya:none"> text appears if the filters are empty
* Fixed: Argument #1 is not an array in frontend class
* Fixed: WP_Post object cannot convert to string in frontend class
* Fixed: Problem with cirillic charachter
* Fixed: Wrong count in filter widgets
* Fixed: Warning in error_log/debug.log file with latest version

= 3.0.4 = 

* Fixed: Filters show all attributes in shop and product taxonomy pages

= 3.0.3 =

* Added: Support to Porto Theme
* Fixed: Wrong query object in layered nav query with WooCommerce 2.6.2

= 3.0.2 =

* Fixed: Filters disappears in sort count select on Avada theme
* Fixed: Filter by attributes doesn't works with WooCommerce 2.5 
* Fixed: rtrim waring in untrailingslashit
* Fixed: Wrong filter on category page with WooCommerce 2.6.2

= 3.0.1 =

* Fixed: print empty li tag after update to version 3.0.0

= 3.0.0 =

* Added: Support to WooCommece 2.6
* Tweak: Layered navigation management
* Tweak: English Typo

= 2.9.2 =

* Fixed: Wrong reset button link in product category page

= 2.9.1 =

* Fixed: $class variable are not defined
* Fixed: Filter by list-attribute doesn't works in versio 2.9.0

= 2.9.0 =

* Added: Change browsers url option (in SEO tab)
* Added: Show current category in product category page (in general tab)
* Added: Support to Ultimate WooCommerce Brands PRO
* Added: Hierarchical tags management
* Added: See all tags link in tags widget id a filter was applied
* Added: See all categories link in categories widget if a filter was applied
* Tweak: Change checkboxes with radio button in Sort By Filter
* Fixed: Filtering issue with YITH WooCommerce Brands Add-on Premium
* Fixed: HTML5 Validation (attribute name not allowed in ul element)
* Fixed: The page doesn't scroll up in mobile
* Fixed: z-index not set to -1 when user close dropdown filters with click in page area

= 2.8.1 =

* Added: WooCommerce shop navigation in ajax
* Fixed: Dropdown issue with Remy Theme
* Fixed: $.fn.slider is not a function after click on reset filter
* Fixed: Reset filter in product category page doesn't works
* Fixed: Plugin panel option style issue

= 2.8.0 =

* Added: Hierarchical Product Category management

= 2.7.9 =

* Fixed: Reset filter doesn't show with Brands and Categories
* Fixed: Unable to unset Brands filter

= 2.7.8 =

* Added: New option to set the scroll top anchor html element for mobile
* Added: Trigger window scroll event after ajax call
* Fixed: The page scroll down after filter has been applied in mobile
* Fixed: Duplicated query in Filter by categories
* Fixed: generated 404 link with in stock/on sale filter
* Fixed: YITH WooCommerce Product Slider Carousel doesn't work after a filter was applied
* Fixed: Widget doesn't work with multiple hierarchical terms

= 2.7.7 =

* Added: Suppoort to quantity input in loop
* Added: yith-wcan-pro body class
* Fixed: SEO option doesn't works with category filter
* Fixed: SEO option doesn't works with in stock/on sale filter

= 2.7.6 = 

* Fixed: Error on activation

= 2.7.5 =

* Added: New event yith-wcan-wrapped was triggered after container wrap
* Added: Support to WooCommerce 2.5
* Fixed: Stop activation free version if premium is enabled

= 2.7.4 =

* Updated: Plugin core framework

= 2.7.3 =

* Added: Support to WooCommerce 2.5-RC1
* Added: Checkboxes style for filter
* Added: Sort by number of products contained or alphabetically
* Fixed: Reset Filter in category page
* Fixed: Filter doesn't work correctly in sub-categories
* Fixed: Filter by tag and Filter by categories doesn't show in sidebar
* Fixed: Add specific class in hieralchical categories

= 2.7.2 =

* Fixed: Unable to filter by categories and brands at same time
* Fixed: Filter by categories widget issue
* Fixed: select/unselect all don't works with wordpress 4.4
* Fixed: The plugin shows empty filters in product category page
* Fixed: Reset filter doesn't works in product category page
* Fixed: WooCommerce price slider doesn't set to default value after filter reset

= 2.7.1 =

* Fixed: Customer can't reset brands filter

= 2.7.0 =

* Fixed: Sort By dropdown lost style in shop page
* Fixed: Hierarchical categories filter doesn't works
* Fixed: Wrong link on brands filter
* Fixed: Loader image don't change
* Fixed: Reset doesn't show with filter by categories
* Fixed: Click on row to filter in dropdown style
* Removed: var_dump() in product filter widget

= 2.6.1 =

* Added: Instant WooCommerce price filter with slider

= 2.6.0 =

* Added: Filter by categories
* Added: yith_wcan_show_widget hook to manage the widgets display condition
* Added: yith_wcan_is_search hook to disable the widgets in search page
* Fixed: SEO option issue with tag filter
* Fixed: Disable widgets in search page
* Fixed: Hierarchical option in Filter By Brand type

= 2.5.0 =

* Added: SEO Tab to add follow and index option for filtered page
* Added: yith_wcan_dropdown_class hook for dropdown classes
* Added: yith_wcan_untrailingslashit hook for disable untrailingslashit function in filter link
* Tweak: Performance improved with new plugin core 2.0
* Tweak: Plugin don't apply filter in category page
* Fixed: Issuet with YITH Infinite Scrolling plugin
* Fixed: Filter widget don't show in product attribute page
* Fixed: Filter by price doesn't work without page reload
* Fixed: Dropdown icon doesn't display
* Fixed: Issue with WPML and Visual Composer plugins in admin

= 2.4.0 =

* Added: Language files called yith-woocommerce-ajax-navigation
* Removed: All language files called yith_wc_ajxnav
* Tweak: New wordpress translation text domain added
* Fixed: Dropdown issue with sort by and price filter widget
* Fixed: Widget title option doesn't work
* Fixed: Issue with price filter widgets if no price range was set

= 2.3.1 =

* Added: Support to YITH Infinite Scrolling plugin
* Fixed: No pagination container issue after filter applied
* Fixed: js error yit_wcan not defined
* Fixed: issue with blank label

= 2.3.0 =

* Added: New frontend options for script configuration
* Added: Custom Style Section
* Updated: Plugin Core Framework
* Updated: Languages file
* Fixed: Warning in list price filter without price

= 2.2.0 =

* Added: Support to WordPress 4.3
* Updated: Language files
* Fixed: Color lost after change widget style with WordPress 4.3
* Fixed: Tag list show after save widget option in other style
* Fixed: Tag list disappear after save option in tags style
* Fixed: Warning when switch from color to label style

= 2.1.2 =

* Added: Support to WooCommerce 2.4
* Updated: Plugin Framework
* Fixed: Tag list and child term support 
* Fixed: Dropdown options doesn't work in WordPress 4.2.4

= 2.1.1 =

* Tweak: Support to PAAMAYIM NEKUDOTAYIM in PHP Version < 5.3

= 2.1.0 =

* Added: Frontend classes option panel
* Added: yith_wcan_ajax_frontend_classes filter
* Added: plugin works in product category page
* Added: Select tags to use in filter
* Added: WPML and String translation support
* Updated: language pot file
* Updated: Italian translation
* Tweak: Shop uri management
* Fixed: in stock/on sale works only with all option enable
* Fixed: wrong filter link in product category page
* Fixed: Widget filter by tag does not combine properly filters
* Fixed: Widget doesn't work fine in Shop Category Page
* Fixed: Remove trailing slash in widget shop uri
* Fixed: Prevent double instance in singleton class
* Fixed: The widget doesn't work with WPML with Label, Color and BiColor style

= 2.0.4 =

* Added: Filter 'yith_wcan_product_taxonomy_type' to widget product tax type
* Tweak: YITH WooCommerce Brands Add-on support in taxonomy page

= 2.0.3 =

* Added: Support to Sortable attribute
* Tweak: Yithemes Themes support
* Fixed: Color lost after change widget style

= 2.0.0 =

Initial Release
