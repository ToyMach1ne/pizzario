<?php
/*
Plugin Name: Woocomerce Brands Pro
Plugin URI: http://proword.net/Woocommerce_Brands_pro/
Description: Woocommerce Brands Plugin. After Install and active this plugin you'll have some shortcode and some widget for display your brands in fornt-end website.
Author: Proword
Version: 4.3.0
Author URI: http://proword.net/
Text Domain: woocommerce-brands
Domain Path: /languages/ 
/*
V 4.3.0
		Fixed : woocommerce 3.0.x is ready
		Fixed : register taxonomy for brand
		Added : you can change position brand in single and archive
		Added : show sticky button only product/shop pages
V 4.2.0
		added : add link in image advanced layout 2
		Fixed : Fix shortcode thumbnail
		Fixed : carosel slide speed in Visul composer
		Fixed : fix show empty brands in advanced show brands
V 4.1
	12/07/2016
		Fixed: fix Layered Nav widget in WC 2.6
		Fixed: WC 2.6 compatibility issues.
		Added : convert Description brand to editor
V 4.0
	02/04/2016
		Added :	compatible with woocommerce brand coupon add-ons
		Fixed : remove font body in front-style.css file
		fixed : fix for text brand in product page if dont set any brand's
V 3.7
	29/10/2015
		Added : Image Sie for brand logo In settings
		Added : New styles in shortcode
		Fixed : add default speed in carousel shortcode
		Fixed : compatible woocommerce 2.4.X
		Fixed : Fix thumbnail in mobile/tablet
		Fixed : Fix css in bootstrap
		Fixed : fix filter base url in widget
		Fixed : fix Show/Hide Empty Brands in shortcode "pw_brand_thumbnails"
		Fixed : fix with VC 4.8.x
		Fixed : fix a-z shortcode scroll and fillter
V 3.4 
	15/09/2015
		Fixed : fix issue in dorpdown in filter by brand
		Fixed : update sticky get_footer to wp_footer=
		Added : Term counts for hide products from the catalog in settings 
		Fixed : update sticky css in mobile view
V 3.3 
	18/07/2015
		Added : Add Multilanguge A-Z Key fillter Views
		Fixed : Fix Hide empty brand in a-z view
		Fixed : Check Compatible in wc 2.3.x and wordpress 4.2.2 = check
V 3.2.0
	15/04/2015
		Added : Display Brand logo In Product Listing(category page)
V 3.1.0
	7/3/2015
		Fixed : Remove Font Body from front-style.css
		Fixed :  url in wpml

*/

define('plugin_dir_url_pw_woo_brand', plugin_dir_url( __FILE__ ));
define ('plugin_dirname_pw_woo_brand',dirname(__FILE__));
if ( ! class_exists( 'pw_woocommerc_brans_active_plugin' ) )
	require_once 'classes/active-plugins-check.php';

/**
 * WC Detection
 */

 
/**
 * Check if WooCommerce is active
 **/
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		return pw_woocommerc_brans_active_plugin::woocommerce_active_check();
	}
} 

if ( is_woocommerce_active() ) {
	
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	/**
	 * Localisation
	 **/
	load_plugin_textdomain( 'woocommerce-brands', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

final class woo_brands{

	 public function __construct() {
		$this->includes();
		add_action( 'widgets_init', array( $this, 'include_widgets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );		
		add_action( 'wp_enqueue_scripts' , array( $this, 'eb_add_scripts' ) );
		register_activation_hook( __FILE__ , array( $this,'woo_brands_install' ));
		
		//Ui Shortcode
		add_filter('init', array( $this,'brand_shortcodes_add_scripts'));
		add_action('admin_head', array( $this,'brand_shortcodes_addbuttons'));	
	 }

	function brand_shortcodes_add_scripts() {
		if(is_admin()) {
			wp_enqueue_style('fontawesome-style', plugin_dir_url_pw_woo_brand.'css/fonts/font-awesome.css');			
		    /////////////////////////CSS CHOSEN///////////////////////
			wp_enqueue_style('pw-brand-chosen-style',plugin_dir_url_pw_woo_brand.'css/chosen/chosen.css', array() , null);
			wp_enqueue_style('pw-brand-backend-style',plugin_dir_url_pw_woo_brand.'css/backend-style.css', array() , null);
			wp_enqueue_script( 'pw-brand-chosen-script', plugin_dir_url_pw_woo_brand.'js/chosen/chosen.jquery.min.js', array( 'jquery' ));	
			//Dependency
			wp_enqueue_script( 'pw-brand-depds', plugin_dir_url_pw_woo_brand.'js/dependsOn-1.0.1.min.js', array( 'jquery' ));	
			//Colour Picker
			//wp_enqueue_style( 'wp-color-picker' );
			//wp_enqueue_script( 'wp-color-picker' );
		}		
	}
	function brand_shortcodes_addbuttons() {
		global $typenow;
		// check user permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
		}
		// check if WYSIWYG is enabled
		if ( get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", array( $this, "add_woo_brand_shortcodes_tinymce_plugin"));
			add_filter('mce_buttons', array( $this, 'register_woo_brand_shortcodes_button'));
		}
	}
	function add_woo_brand_shortcodes_tinymce_plugin($plugin_array) {
		$plugin_array['woo_brand_shortcodes_button'] = plugins_url( '/includes/tinymce_button.js', __FILE__ );
		return $plugin_array;
	}
	function register_woo_brand_shortcodes_button($buttons) {
	   array_push($buttons, "woo_brand_shortcodes_button");
	   return $buttons;
	}	

	public function woo_brands_install() {
		update_option( 'pw_woocommerce_brands_show_categories', 'no' );	
		//update_option( 'pw_woocommerce_brands_default_image', plugin_dir_url_pw_woo_brand.'img/default.png' );	
		update_option( 'pw_woocommerce_brands_display_extra', 'yes' );	
		update_option( 'pw_woocommerce_brands_position_extra', 'right' );	
		update_option( 'pw_woocommerce_brands_text','Brands');
		update_option( 'pw_woocommerce_brands_text_single','yes');
		update_option( 'pw_woocommerce_brands_image_single','yes');
		update_option( 'pw_woocommerce_brands_image_list','yes');
		update_option( 'pw_woocommerce_brands_desc_single','no');
		update_option( 'pw_woocommerce_brands_desc_list','no');		
		update_option( 'pw_wooccommerce_display_brand_in_product_shop','yes');
		update_option( 'pw_woocommerce_image_brand_shop_page','no');
		update_option( 'pw_position_brand_shop','above_price');
		update_option( 'pw_woocommerce_brands_shop_page','yes');
		update_option( 'pw_woocommerce_brands_base','brand');
		update_option( 'pw_woocommerce_brands_style_extra','wb-filter-style1');
		update_option( 'pw_woocommerce_brands_image_list_image_size','150:150');
		update_option( 'pw_woocommerce_brands_image_single_image_size','150:150');
		update_option( 'pw_woocommerce_image_brand_shop_page_image_size','150:150');
	}

	private function includes() {
		if(get_option('pw_woocommerce_brands_display_extra'))
			include_once( 'classes/side-button.php' );
		include_once( 'classes/taxonomies.php' );
		include_once( 'includes/shortcode.php' );
		include_once( 'includes/all_shortcode.php' );
		include_once( 'classes/setting-tabs.php' );
		include_once( 'classes/class-wc-brands.php' );
		include_once( 'vc_composer/main.php' );
		/////ACTION FILE///////
		include_once( 'includes/actions.php' );
		//include_once( 'includes/test.php' );
	}
	public function include_widgets() {
		
		if ( version_compare( WC_VERSION, '2.6.0', '>=' ) ) {
			require_once( 'classes/class-wc-widget-brand-nav.php' );
		} else {
			require_once( 'classes/class-wc-widget-brand-nav-deprecated.php' );
		}
		
		include_once( 'classes/widget.php' );
	}


	public function action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=pw_woocommerce_brands' ) . '">' . __( 'Settings', 'woocommerce-brands' ) . '</a>',
			'<a href="' . esc_url( apply_filters( 'woocommerce_docs_url', 'http://proword.net/Woocommerce_Brands/documentation/', 'woocommerce' ) ) . '">' . __( 'Docs', 'woocommerce-brands' ) . '</a>',

		), $links );
	}
	
	public function eb_add_scripts(){
		/* Bootstrap  */
		wp_register_style('woob-bootstrap-style', plugin_dir_url_pw_woo_brand.'css/framework/bootstrap.css');
		wp_enqueue_style('woob-bootstrap-style');	

		/*Front-End*/
		wp_register_style('woob-front-end-style', plugin_dir_url_pw_woo_brand.'css/front-style.css');
		wp_enqueue_style('woob-front-end-style');
		/* Dropdown css */
		wp_register_style('woob-dropdown-style',  plugin_dir_url_pw_woo_brand.'css/msdropdown/dd.css');
		wp_enqueue_style('woob-dropdown-style');
		/* carosel Css */
		wp_register_style('woob-carousel-style',  plugin_dir_url_pw_woo_brand.'css/carousel/slick.css');		
		wp_enqueue_style("woob-carousel-style");

		/* scroll Css  */
		wp_register_style('woob-scroller-style', plugin_dir_url_pw_woo_brand.'css/scroll/tinyscroller.css');
		wp_enqueue_style('woob-scroller-style');
		
		/* BX Slider  */
		wp_register_style('woob-bxslider-style', plugin_dir_url_pw_woo_brand.'css/bx-slider/jquery.bxslider.css');

		
		/* Tooltip  */
		wp_register_style('woob-tooltip-style', plugin_dir_url_pw_woo_brand.'css/tooltip/tipsy.css');
		wp_enqueue_style('woob-tooltip-style');	
		
		//////////PRETTY MULTI SELECT/////////////
		//wp_register_style('woob-multiselect-css', plugin_dir_url_pw_woo_brand.'css/multiselect/bootstrap-multiselect.css', array() , null);
		
		
		if(get_option('pw_woocommerce_brands_display_extra')=="yes")
		{
			wp_register_style('woob-extra-button-style', plugin_dir_url_pw_woo_brand.'css/extra-button/extra-style.css');
			wp_enqueue_style('woob-extra-button-style');
			
			wp_register_script('woob-extra-button-script',plugin_dir_url_pw_woo_brand.'js/extra-button/extra-button.js',array( 'jquery' ));
			wp_enqueue_script('woob-extra-button-script');	   
		}


		/* Drop Down Js */
		wp_register_script('woob-dropdown-script', plugin_dir_url_pw_woo_brand.'js/msdropdown/jquery.dd.min.js',array( 'jquery' ));
		/* carosel Js */
		wp_register_script('woob-carousel-script', plugin_dir_url_pw_woo_brand.'js/carousel/slick.js',array( 'jquery' ));
		
		/* Scroll Js */
		wp_register_script('woob-scrollbar-script',plugin_dir_url_pw_woo_brand.'js/scroll/tinyscroller.js',array( 'jquery' ));
		wp_enqueue_script('woob-scrollbar-script');
		
		/* BX Slider */
		wp_register_script('woob-bxslider-script',plugin_dir_url_pw_woo_brand.'js/bx-slider/jquery.bxslider.js',array( 'jquery' ));
		
		
		/* Tooltip */
		wp_register_script('woob-tooltip-script',plugin_dir_url_pw_woo_brand.'js/tooltip/jquery.tipsy.js',array( 'jquery' ));
		wp_enqueue_script('woob-tooltip-script');
		
		
		//////////MULTI SELECT///////////
		//wp_register_script( 'woob-bootstrap-js', plugin_dir_url_pw_woo_brand.'js/framework/bootstrap.js', array( 'jquery' ),true );
		//wp_register_script( 'woob-multiselect-js', plugin_dir_url_pw_woo_brand.'js/multiselect/bootstrap-multiselect.js', array( 'jquery' ),true );
		
		////////CUSTOM JS FRONT END////////
		wp_register_script('woob-front-end-custom-script',plugin_dir_url_pw_woo_brand.'js/custom-js.js',array( 'jquery' ));
		
		//wp_enqueue_style('woob-multiselect-css');

	//wp_enqueue_script( 'woob-bootstrap-js');
	//wp_enqueue_script( 'woob-multiselect-js');
	
		wp_enqueue_script('woob-front-end-custom-script');
		wp_localize_script( 'woob-front-end-custom-script', 'parameters', array(
			'ajaxurl' => admin_url( 'admin-ajax.php'),
			'template_url' => ''		
			)
		);
		
	}
}
new woo_brands();
add_filter('widget_text', 'do_shortcode');


add_action('wp_ajax_pw_recount_brand', 'pw_recount_brand');
add_action('wp_ajax_nopriv_pw_recount_brand', 'pw_recount_brand');
function pw_recount_brand()
{
	$product_brand = get_terms( 'product_brand', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );
	_wc_term_recount( $product_brand, get_taxonomy( 'product_brand' ), true, false );		
}

add_action('wp_ajax_pw_fetch_woocommerce_brand', 'pw_fetch_woocommerce_brand');
add_action('wp_ajax_nopriv_pw_fetch_woocommerce_brand', 'pw_fetch_woocommerce_brand');
function pw_fetch_woocommerce_brand() {
					$args = array(
						'taxonomy'       		   =>  'product_brand',
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 0,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'child_of'          		 => 0,
						'number'                   => '',
						'pad_counts'               => false 
					
					);
					$categories = get_categories($args); 
					if(!isset($_POST['single']))
						echo '<option value="all">All</option>';
					foreach ($categories as $category) {
						$option = '<option value="'.$category->cat_ID.'">';
						$option .= $category->cat_name;
						$option .= ' ('.$category->category_count.')';
						$option .= '</option>';
						$param_line .= $option;
					}
					echo $param_line;	
}


add_action('wp_ajax_pw_fetch_woocommerce_brand_category', 'pw_fetch_woocommerce_brand_category');
add_action('wp_ajax_nopriv_pw_fetch_woocommerce_brand_category', 'pw_fetch_woocommerce_brand_category');
function pw_fetch_woocommerce_brand_category() {
					$args = array(
						'taxonomy'       		   =>  'product_cat',
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 0,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'child_of'          		 => 0,
						'number'                   => '',
						'pad_counts'               => false 
					
					);
					$categories = get_categories($args); 
					foreach ($categories as $category) {
						$option = '<option value="'.$category->cat_ID.'">';
						$option .= $category->cat_name;
						$option .= ' ('.$category->category_count.')';
						$option .= '</option>';
						$param_line .= $option;
					}
					echo $param_line;	
}
}


?>
