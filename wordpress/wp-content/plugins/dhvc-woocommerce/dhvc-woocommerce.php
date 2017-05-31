<?php
/*
* Plugin Name: DHVC Woocommerce Products Layouts
* Plugin URI: http://sitesao.com/item/woocommerce-products-layouts/
* Description: DHVC Woocommerce Products Layouts Shortcodes - Manage Woocommerce Products Layouts with Visual Composer
* Version: 2.2.31
* Author: Sitesao
* Author URI:http://sitesao.com/
* License: License GNU General Public License version 2 or later;
* Copyright 2014  Sitesao
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!defined('DHVC_WOO'))
	define('DHVC_WOO','dhvc-woocommerce');

if(!defined('DHVC_WOO_VERSION'))
	define('DHVC_WOO_VERSION','2.2.31');

if(!defined('DHVC_WOO_URL'))
	define('DHVC_WOO_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));

if(!defined('DHVC_WOO_DIR'))
	define('DHVC_WOO_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));

if (!function_exists('dhwc_is_active')){
	/**
	 * Check woocommerce plugin is active
	 *
	 * @return boolean .TRUE is active
	 */
	function dhwc_is_active(){
		$active_plugins = (array) get_option( 'active_plugins', array() );
		
		if ( is_multisite() )
			$active_plugins = array_merge($active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

global $dhvc_woo_category_page,$dhvc_woo_tag_page,$dhvc_woo_brand_page,$dhvc_woo_shop_page;
$dhvc_woo_category_page = $dhvc_woo_tag_page = $dhvc_woo_brand_page = $dhvc_woo_shop_page = 0;

if(!class_exists('DHVCWoo',false)){

	require_once DHVC_WOO_DIR.'/includes/functions.php';
	
	class DHVCWoo{

		public function __construct(){
			add_action('init',array(&$this,'init'));
			if(dhwc_is_active())
				require_once DHVC_WOO_DIR.'/includes/dhwc-brand/dhwc-brand.php';
		}
		
		public function init(){
			load_plugin_textdomain( DHVC_WOO, false, basename(DHVC_WOO_DIR) . '/languages' );
				
				
			add_action( 'wp_ajax_dhvc_woo_product_quickview', array( &$this, 'quickview' ) );
			add_action( 'wp_ajax_nopriv_dhvc_woo_product_quickview', array( &$this, 'quickview' ) );
			
			
			wp_register_style('dhvc-woo-chosen', DHVC_WOO_URL.'/assets/css/chosen.min.css');
			wp_register_style('dhvc-woo-font-awesome', DHVC_WOO_URL.'/assets/fonts/awesome/css/font-awesome.min.css',array(),'4.0.3');
			wp_register_style('dhvc-woo', DHVC_WOO_URL.'/assets/css/style.css');
			
			wp_register_script('dhvc-woo-isotope', DHVC_WOO_URL.'/assets/js/isotope.pkgd.min.js',array('jquery'),DHVC_WOO_VERSION,true);
			wp_register_script('dhvc-woo-owlcarousel', DHVC_WOO_URL.'/assets/js/owl.carousel/owl.carousel.min.js',array('jquery'),DHVC_WOO_VERSION,true);
			wp_register_style('dhvc-woo-owlcarousel', DHVC_WOO_URL.'/assets/js/owl.carousel/assets/owl.carousel.css');
			wp_register_script('dhvc-woo-boostrap-modal', DHVC_WOO_URL.'/assets/js/boostrap-modal.min.js',array('jquery'),DHVC_WOO_VERSION,true);
			wp_register_style('dhvc-woo-boostrap-modal', DHVC_WOO_URL.'/assets/css/boostrap-modal.css');
			if(is_admin()){
				add_action('admin_enqueue_scripts',array(&$this,'admin_enqueue_styles'));
				add_action('admin_enqueue_scripts',array(&$this,'enqueue_scripts'));
			}else{
				add_action('wp_print_scripts',array(&$this,'enqueue_scripts'));
				add_action('wp_print_scripts',array(&$this,'print_scripts'));
				//add_filter( 'term_link', array($this,'term_link'), 100,10);
				//add_action( 'template_redirect', array($this,'template_redirect'),1000);
				add_filter( 'template_include', array( &$this, 'template_loader' ) ,1000);
				add_action(	'wp_head', array(&$this,'add_shortcodes_custom_css'),1000 );
			}
			
			if(!dhwc_is_active()){
				add_action('admin_notices', array(&$this,'woocommerce_notice'));
				return;
			}
			
			
			
			if(is_admin()){
				require_once DHVC_WOO_DIR.'/includes/admin.php';
			}
			
			require_once DHVC_WOO_DIR.'/includes/class.php';
				
			$shortcode = new DHVCWooCommerce();
			$shortcode->init();
				
		}
		

		public function notice(){
			$plugin = get_plugin_data(__FILE__);
			echo '
			  <div class="updated">
			    <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/1gKaeh5" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', DHVC_WOO), $plugin['Name']) . '</p>
			  </div>';
		}
		
		public function woocommerce_notice(){
			$plugin = get_plugin_data(__FILE__);
			echo '
			  <div class="updated">
			    <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a></strong> plugin to be installed and activated on your site.', DHVC_WOO), $plugin['Name']) . '</p>
			  </div>';
		}
		
		public function print_scripts(){
			global $post;
			wp_enqueue_style('dhvc-woo-font-awesome');
			//wp_enqueue_style('dhvc-woo-chosen');
			wp_enqueue_style('dhvc-woo');
		}
		public function quickview(){
			global $woocommerce, $post, $product;
			$product_id = $_POST['product_id'];
			$product = get_product( $product_id );
			$post = get_post( $product_id );
			$output = '';
		
			ob_start();
			?>
			<?php
			global $woocommerce,$product,$post;
			?>
			<div class="modal fade woocommerce dhvc-woo-product-quickview">
				<div class="modal-dialog modal-lg">
			    	<div class="modal-content">
			    		<div class="modal-body">
			    			<div id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>
								<div class="dhvc-woo-row-fluid">
									<div class="dhvc-woo-span6">
										<div class="dhvc-woo-modal-image">
											<?php
												/**
												 * woocommerce_before_single_product_summary hook
												 *
												 * @hooked woocommerce_show_product_sale_flash - 10
												 * @hooked woocommerce_show_product_images - 20
												 */
												do_action( 'woocommerce_before_single_product_summary' );
											?>
										</div>
									</div>
									<div class="dhvc-woo-span6">
										<div class="summary entry-summary">
										<?php
											/**
											 * woocommerce_single_product_summary hook
											 *
											 * @hooked woocommerce_template_single_title - 5
											 * @hooked woocommerce_template_single_rating - 10
											 * @hooked woocommerce_template_single_price - 10
											 * @hooked woocommerce_template_single_excerpt - 20
											 * @hooked woocommerce_template_single_add_to_cart - 30
											 * @hooked woocommerce_template_single_meta - 40
											 * @hooked woocommerce_template_single_sharing - 50
											 */
											do_action( 'woocommerce_single_product_summary' );
										?>
										</div><!-- .summary -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			$output = apply_filters('dhvc_woo_product_quickview_html', $output);
			echo trim($output);
			die();
		}
		public function admin_bar_menu(){
			global $wp_admin_bar,$dhvc_woo_category_page,$dhvc_woo_tag_page,$dhvc_woo_brand_page;
			
			if(!empty($dhvc_woo_category_page)){
				if($this->show_button($dhvc_woo_category_page->ID)){
					$wp_admin_bar->add_menu( array(
							'id' 	=>'dhvc_woo_category_page',
							'title' => __('Edit Category Page Template', DHVC_WOO),
							'href' => admin_url().'post.php?post='.$dhvc_woo_category_page->ID.'&action=edit',
					) );
					
				}	
			}elseif (!empty($dhvc_woo_brand_page)){
				if($this->show_button($dhvc_woo_brand_page->ID)){
					$wp_admin_bar->add_menu( array(
							'id' 	=>'dhvc_woo_brand_page',
							'title' => __('Edit Brand Page Template', DHVC_WOO),
							'href' => admin_url().'post.php?post='.$dhvc_woo_brand_page->ID.'&action=edit',
					) );
				}
			}elseif (!empty($dhvc_woo_tag_page)){
				if($this->show_button($dhvc_woo_tag_page->ID)){
					$wp_admin_bar->add_menu( array(
							'id' 	=>'dhvc_woo_tag_page',
							'title' => __('Edit Tag Page Template', DHVC_WOO),
							'href' => admin_url().'post.php?post='.$dhvc_woo_tag_page->ID.'&action=edit',
					) );
				}
			}
		}
		
		public function show_button($post_id){
			global $current_user;
			get_currentuserinfo();
			if(!current_user_can('edit_post', $post_id)) return false;
			return true;
		}
		
		public function admin_enqueue_styles(){
			wp_enqueue_style('dhvc-woo-chosen');
		}

		public function enqueue_scripts(){
			// JavaScript
			if(defined('WOOCOMMERCE_VERSION'))
				wp_enqueue_script( 'wc-add-to-cart-variation' );
			
			wp_register_script('dhvc-woo',DHVC_WOO_URL.'/assets/js/script.js',array('jquery'),DHVC_WOO_VERSION,true);
			$dhvcWooL10n = array(
				'ajax_url'=>admin_url( 'admin-ajax.php', 'relative' ),
			);
			wp_localize_script('dhvc-woo', 'dhvcWooL10n', $dhvcWooL10n);
			wp_enqueue_script('dhvc-woo');
		}
		
		public function template_redirect(){
			if ( is_tax( 'product_cat' )) {
				$category_slug = get_query_var('product_cat');
				$category = get_term_by('slug', $category_slug, 'product_cat');
				$category_page_id = get_woocommerce_term_meta($category->term_id,'dhvc_woo_category_page_id',true);
				if($category_page_id){
					wp_redirect(get_permalink($category_page_id));
				}
			
			}elseif (is_tax('product_brand')){
				$brand_slug = get_query_var('product_brand');
				$brand = get_term_by('slug', $brand_slug, 'product_brand');
				$brand_page_id = get_woocommerce_term_meta($brand->term_id,'dhvc_woo_brand_page_id',true);
				if($brand_page_id){
					wp_redirect(get_permalink($brand_page_id));
				}
			}elseif ( (is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ))) && (get_option('dhvc_woo_product_archives') == 'yes') ) {
				
			}
		}
		
		public function term_link( $link, $term, $taxonomy ){
			if ( $term->taxonomy === 'product_cat' ) {
				$category_page_id = get_woocommerce_term_meta($term->term_id,'dhvc_woo_category_page_id',true);
				if($category_page_id){
					return get_permalink($category_page_id);
				}
			}
			if ( $term->taxonomy=== 'product_brand' ) {
				$brand_page_id = get_woocommerce_term_meta($term->term_id,'dhvc_woo_brand_page_id',true);
				if($brand_page_id){
					return get_permalink($brand_page_id);
				}
			}
			return $link;
		}
		
		public function add_shortcodes_custom_css(){
			global $dhvc_woo_category_page,$dhvc_woo_tag_page,$dhvc_woo_brand_page,$dhvc_woo_shop_page;
			$custom_template_id = 0;
			if(is_tax( 'product_cat' ) && !empty($dhvc_woo_category_page)):
				$custom_template_id = $dhvc_woo_category_page->ID;
			elseif (is_tax('product_tag') && !empty($dhvc_woo_tag_page)):
				$custom_template_id = $dhvc_woo_tag_page->ID;
			elseif (is_tax('product_brand') && !empty($dhvc_woo_brand_page)):
				$custom_template_id = $dhvc_woo_brand_page->ID;
			elseif ((is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ))) && (get_option('dhvc_woo_product_archives') == 'yes') && !empty($dhvc_woo_shop_page)):
				$custom_template_id = $dhvc_woo_shop_page->ID;
			endif;
			if($wpb_custom_css = get_post_meta( $custom_template_id, '_wpb_post_custom_css', true )){
				echo '<style type="text/css" data-type="dhvc_woocommerce_shortcode_custom_css">'.$wpb_custom_css.'</style>';
			}
			if($wpb_shortcodes_custom_css = get_post_meta( $custom_template_id, '_wpb_shortcodes_custom_css', true )){
				echo '<style type="text/css" data-type="dhvc_woocommerce_shortcode_custom_css">'.$wpb_shortcodes_custom_css.'</style>';
			}	
		}
		
		public function template_loader($template){
			global $post,$dhvc_woo_category_page,$dhvc_woo_tag_page,$dhvc_woo_brand_page,$dhvc_woo_shop_page;
			if ( is_tax( 'product_cat' )) {
				$category_slug = get_query_var('product_cat');
				$category = get_term_by('slug', $category_slug, 'product_cat');
				$category_page_id = get_woocommerce_term_meta($category->term_id,'dhvc_woo_category_page_id',true);
				if($category_page_id){
					$dhvc_woo_category_page = get_post($category_page_id);
					if(class_exists('Ultimate_VC_Addons')){
						$backup_post = $post;
						$post  = $dhvc_woo_category_page;
						$Ultimate_VC_Addons = new Ultimate_VC_Addons;
						$Ultimate_VC_Addons->aio_front_scripts();
						$post = $backup_post;
					}
					$file = 'archive-product.php';
					$find[] = 'dhvc-woocommerce/' . $file;
					$template       = locate_template( $find );
					$status_options = get_option( 'woocommerce_status_options', array() );
					if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
						$template = DHVC_WOO_DIR . '/templates/' . $file;
						
					return $template;
				}
				
			}elseif (is_tax('product_tag')){
				$tag_slug = get_query_var('product_tag');
				$tag = get_term_by('slug', $tag_slug, 'product_tag');
				$tag_page_id = get_woocommerce_term_meta($tag->term_id,'dhvc_woo_tag_page_id',true);
				if($tag_page_id){
					$dhvc_woo_tag_page = get_post($tag_page_id);
					if(class_exists('Ultimate_VC_Addons')){
						$backup_post = $post;
						$post  = $dhvc_woo_tag_page;
						$Ultimate_VC_Addons = new Ultimate_VC_Addons;
						$Ultimate_VC_Addons->aio_front_scripts();
						$post = $backup_post;
					}
					$file = 'archive-product.php';
					$find[] = 'dhvc-woocommerce/' . $file;
					$template       = locate_template( $find );
					$status_options = get_option( 'woocommerce_status_options', array() );
					if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
						$template = DHVC_WOO_DIR . '/templates/' . $file;
				
					return $template;
				}
				
				
			}elseif (is_tax('product_brand')){
				$brand_slug = get_query_var('product_brand');
				$brand = get_term_by('slug', $brand_slug, 'product_brand');
				$brand_page_id = get_woocommerce_term_meta($brand->term_id,'dhvc_woo_brand_page_id',true);
				if($brand_page_id){
					$dhvc_woo_brand_page = get_post($brand_page_id);
					if(class_exists('Ultimate_VC_Addons')){
						$backup_post = $post;
						$post  = $dhvc_woo_brand_page;
						$Ultimate_VC_Addons = new Ultimate_VC_Addons;
						$Ultimate_VC_Addons->aio_front_scripts();
						$post = $backup_post;
					}
					$file = 'archive-product.php';
					$find[] = 'dhvc-woocommerce/' . $file;
					$template       = locate_template( $find );
					$status_options = get_option( 'woocommerce_status_options', array() );
					if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
						$template = DHVC_WOO_DIR . '/templates/' . $file;
				
					return $template;
				}
			}elseif ( (is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ))) && (get_option('dhvc_woo_product_archives') == 'yes') ) {
				$dhvc_woo_shop_page = get_post(wc_get_page_id('shop'));
				if(class_exists('Ultimate_VC_Addons')){
					$backup_post = $post;
					$post  = $dhvc_woo_shop_page;
					$Ultimate_VC_Addons = new Ultimate_VC_Addons;
					$Ultimate_VC_Addons->aio_front_scripts();
					$post = $backup_post;
				}
				$file = 'archive-product.php';
				$find[] = 'dhvc-woocommerce/' . $file;
				$template       = locate_template( $find );
				$status_options = get_option( 'woocommerce_status_options', array() );
				if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) )
					$template = DHVC_WOO_DIR . '/templates/' . $file;
				
				return $template;
			}
			return $template;
		}
		
		
	}

	new DHVCWoo();
}

