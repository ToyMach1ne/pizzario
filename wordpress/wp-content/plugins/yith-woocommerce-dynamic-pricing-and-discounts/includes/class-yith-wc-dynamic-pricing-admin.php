<?php

if ( !defined( 'ABSPATH' ) || !defined( 'YITH_YWDPD_VERSION' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements admin features of YITH WooCommerce Dynamic Pricing and Discounts
 *
 * @class   YITH_WC_Dynamic_Pricing_Admin
 * @package YITH WooCommerce Dynamic Pricing and Discounts
 * @since   1.0.0
 * @author  Yithemes
 */
if ( !class_exists( 'YITH_WC_Dynamic_Pricing_Admin' ) ) {

	/**
	 * Class YITH_WC_Dynamic_Pricing_Admin
	 */
	class YITH_WC_Dynamic_Pricing_Admin {

        /**
         * Single instance of the class
         *
         * @var \YITH_WC_Dynamic_Pricing_Admin
         */
        protected static $instance;

        /**
         * @var $_panel Panel Object
         */
        protected $_panel;

        /**
         * @var $_premium string Premium tab template file name
         */
        protected $_premium = 'premium.php';

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-dynamic-pricing-and-discounts/';

        /**
         * @var string Panel page
         */
        protected $_panel_page = 'yith_woocommerce_dynamic_pricing_and_discounts';

        /**
         * @var string Doc Url
         */
        public $doc_url = 'https://yithemes.com/docs-plugins/yith-woocommerce-dynamic-pricing-and-discounts/';

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WC_Dynamic_Pricing_Admin
         * @since 1.0.0
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since  1.0.0
         * @author Emanuela Castorina
         */
        public function __construct() {

            $this->create_menu_items();

            // register plugin to licence/update system
            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
            
            //custom tab
            add_action( 'ywdpd_price_rules_tab', array( $this, 'rules_tab' ), 10, 2 );
            add_action( 'ywdpd_cart_rules_tab', array( $this, 'rules_tab' ), 10, 2 );

            add_action( 'ywdpd_print_rules', array( $this, 'load_rules' ), 10 );

            // save options
            add_action('admin_init', array($this, 'save_options') );

            // panel type ajax action active
	        /* ajax action */
	        add_action( 'wp_ajax_ywdpd_admin_action', array( $this, 'ajax' ) );
	        add_action( 'wp_ajax_nopriv_ywdpd_admin_action', array( $this, 'ajax' ) );


            //Add action links
            add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWDPD_DIR . '/' . basename( YITH_YWDPD_FILE ) ), array( $this, 'action_links' ) );
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

            //custom styles and javascripts
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 11);


        }

		/**
		 * Switch a ajax call
		 */
		public function ajax() {
			if ( isset( $_REQUEST['ywdpd_action'] ) ) {
				if ( method_exists( $this, 'ajax_' . $_REQUEST['ywdpd_action'] ) ) {
					$s = 'ajax_' . $_REQUEST['ywdpd_action'];
					$this->$s();
				}
			}

		}

		public function ajax_section_clone(){
			$global_option = get_option(YITH_WC_Dynamic_Pricing()->plugin_options);

			$key     = uniqid();
			$cloned_from = $_REQUEST['cloned_from'];
			if( isset( $global_option['pricing-rules'][$cloned_from]) ){
				$global_option['pricing-rules'][$key] = $global_option['pricing-rules'][$cloned_from];
				update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
			}elseif( isset( $global_option['cart-rules'][$cloned_from]) ){
				$global_option['cart-rules'][$key] = $global_option['cart-rules'][$cloned_from];
				update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
			}

			wp_send_json(array('key' => $key));

		}

		/**
		 * Modify the capability
		 *
		 * @param $capability
		 *
		 * @return string
		 */
		function change_capability( $capability ) {
            return 'manage_woocommerce';
        }

        /**
         * Enqueue styles and scripts
         *
         * @access public
         * @return void
         * @since 1.0.0
         */
        public function enqueue_styles_scripts() {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style( 'yith_ywdpd_backend', YITH_YWDPD_ASSETS_URL . '/css/backend.css', YITH_YWDPD_VERSION );
	        wp_enqueue_script( 'ywdpd_timepicker', YITH_YWDPD_ASSETS_URL . '/js/jquery-ui-timepicker-addon.min.js', array( 'jquery' ), YITH_YWDPD_VERSION, true );
            wp_enqueue_script( 'yith_ywdpd_admin', YITH_YWDPD_ASSETS_URL . '/js/ywdpd-admin' . YITH_YWDPD_SUFFIX . '.js', array( 'jquery','jquery-ui-sortable' ), YITH_YWDPD_VERSION, true );
            wp_enqueue_script( 'jquery-blockui', YITH_YWDPD_ASSETS_URL . '/js/jquery.blockUI.min.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'ajax-chosen', YITH_YWDPD_URL.'plugin-fw/assets/js/chosen/ajax-chosen.jquery'. YITH_YWDPD_SUFFIX . '.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'ajax-chosen');
            wp_enqueue_script( 'wc-enhanced-select' );

            wp_localize_script( 'yith_ywdpd_admin', 'yith_ywdpd_admin', apply_filters( 'yith_ywdpd_admin_localize',array(
                'ajaxurl'                 => WC()->ajax_url(),
                'search_categories_nonce' => wp_create_nonce( 'search-categories' ),
                'search_tags_nonce'       => wp_create_nonce( 'search-tags' ),
                'search_products_nonce'   => wp_create_nonce( 'search-products' ),
                'search_customers_nonce'  => wp_create_nonce( 'search-customers' ),
                'block_loader'            => apply_filters( 'yith_ywdpd_block_loader_admin', YITH_YWDPD_ASSETS_URL . '/images/block-loader.gif' ),
                'error_msg'               => apply_filters( 'yith_ywdpd_error_msg_admin', __( 'Please, add a description for the rule', 'ywdpd' ) ),
                'del_msg'                 => apply_filters( 'yith_ywdpd_delete_msg_admin', __( 'Do you really want to delete this rule?', 'ywdpd' ) )
            )));

        }

        /**
         * Create Menu Items
         *
         * Print admin menu items
         *
         * @since  1.0
         * @author Emanuela Castorina
         */
        private function create_menu_items() {
            // Add a panel under YITH Plugins tab
            add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
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

            if ( !empty( $this->_panel ) ) {
                return;
            }

            $admin_tabs = array(
                'general' => __( 'Settings', 'ywdpd' ),
            );

            if ( defined( 'YITH_YWDPD_FREE_INIT' ) ) {
                $admin_tabs['premium'] = __( 'Premium Version', 'ywdpd' );
            }
            else {
                $admin_tabs['pricing'] = __( 'Price Rules', 'ywdpd' );
                $admin_tabs['cart']    = __( 'Cart Discounts', 'ywdpd' );
            }

            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => __( 'Dynamic Pricing', 'ywdpd' ),
                'menu_title'       => __( 'Dynamic Pricing', 'ywdpd' ),
                'capability'       => 'manage_options',
                'parent'           => 'ywdpd',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YITH_YWDPD_DIR . '/plugin-options'
            );

            //enable shop manager to set Dynamic Pricing Options
            if(  YITH_WC_Dynamic_Pricing()->get_option('enable_shop_manager') == 'yes' ){
                add_filter( 'option_page_capability_yit_' . $args['parent'] . '_options', array($this,'change_capability') );
                $args['capability'] = 'manage_woocommerce';
            }


            /* === Fixed: not updated theme  === */
            if ( !class_exists( 'YIT_Plugin_Panel' ) ) {
                require_once( YITH_YWDPD_DIR.'/plugin-fw/lib/yit-plugin-panel.php' );
            }

            $this->_panel = new YIT_Plugin_Panel( $args );

	        $this->save_default_options();
            
        }

		/**
		 * Save default options when the plugin is installed
		 *
		 * @since   1.0.0
		 * @author  Emanuela Castorina
		 * @return  void
		 */
		public function save_default_options() {

			$options                = maybe_unserialize( get_option( 'yit_ywdpd_options', array() ) );
			$current_option_version = get_option( 'yit_ywdpd_option_version', '0' );
			$forced                 = isset( $_GET['update_ywdpd_options'] ) && $_GET['update_ywdpd_options'] == 'forced';

			if ( version_compare( $current_option_version, YITH_YWDPD_VERSION, '>=' ) && ! $forced ) {
				return;
			}

			$new_option = array_merge( $this->_panel->get_default_options(), ( array ) $options );
			update_option( 'yit_ywdpd_options', $new_option );
			update_option( 'yit_ywdpd_option_version', YITH_YWDPD_VERSION );
		}

        /**
         * Print fields table
         *
         * @access public
         * @param array $options
         * @return void
         * @since 1.0.0
         */
        public function rules_tab( $options ) {

            if( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page
                && isset( $_GET['tab'] ) && !empty($_GET['tab'])
                && file_exists( YITH_YWDPD_TEMPLATE_PATH . '/admin/rules-panel.php' ) ) {
                $type = $_GET['tab'];
                include_once( YITH_YWDPD_TEMPLATE_PATH . '/admin/rules-panel.php' );
            }
        }

        /**
         * Add new pricing rules options section
         *
         * @since 1.0.0
         * @access public
         * @author Emanuela Castorina
         */
        public function ajax_add_section() {

            if ( ! isset( $_REQUEST['section'] ) ) {
                die();
            }

            $description = strip_tags( $_REQUEST['section'] );
            $key     = uniqid();
            $id      = $_REQUEST['id'];
            $type    = $_REQUEST['type'];
            $name    = $_REQUEST['name'];

            include( YITH_YWDPD_TEMPLATE_PATH . 'admin/'.$type.'-rules-panel.php' );

            die();
        }

        /**
         * Action Links
         *
         * add the action links to plugin admin page
         *
         * @param $links | links plugin array
         *
         * @return   mixed Array
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @return mixed
         * @use      plugin_action_links_{$plugin_file_name}
         */
        public function action_links( $links ) {
            $links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'ywdpd' ) . '</a>';
            return $links;
        }

		/**
		 * @param $type
		 */
		public function load_rules( $type ) {
            if ( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page
                 && file_exists( YITH_YWDPD_TEMPLATE_PATH . '/admin/'.$type.'-rules-option.php' ) ) {
                $db_value = YITH_WC_Dynamic_Pricing()->get_option($type.'-rules');

                include_once( YITH_YWDPD_TEMPLATE_PATH . '/admin/'.$type.'-rules-option.php' );
            }
        }



		public function save_options(){

			if( ! isset( $_GET['page'] ) || $_GET['page'] != $this->_panel_page
			    || ! isset( $_GET['tab'] ) || empty($_GET['tab'])
			    || ! isset( $_POST['ywdpd-action'] ) || $_POST['ywdpd-action'] != 'save-options' ) {
				return;
			}
			$global_option = get_option(YITH_WC_Dynamic_Pricing()->plugin_options);
			$type = $_GET['tab'];
			$section_key = $_REQUEST['section-key'];
			if( isset( $_REQUEST[YITH_WC_Dynamic_Pricing()->plugin_options][$type.'-rules'][$section_key]) ){
				if( isset( $global_option[$type.'-rules'] ) && is_array( $global_option[$type.'-rules'] ) ){
					$global_option[$type.'-rules'][$section_key] = $_REQUEST[YITH_WC_Dynamic_Pricing()->plugin_options][$type.'-rules'][$section_key];
				}
				else {
					$global_option[$type.'-rules'] = array( $section_key => $_REQUEST[YITH_WC_Dynamic_Pricing()->plugin_options][$type.'-rules'][$section_key] );
				}
				update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
			}

		}

		public function ajax_order_section() {
			$keys          = $_POST['order_keys'];
			$type          = $_POST['tab'];
			$global_option = get_option( YITH_WC_Dynamic_Pricing()->plugin_options );
			$new_array     = array();

			foreach ( $keys as $key ) {
				if ( isset( $global_option[ $type . '-rules' ][ $key ] ) ) {
					$new_array[ $key ] = $global_option[ $type . '-rules' ][ $key ];
				}
			}

			if ( $new_array ) {
				$global_option[ $type . '-rules' ] = $new_array;
				update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option );
			}

			die();

		}

		public function ajax_section_remove() {
            $section_key = $_REQUEST['section'];

            $global_option = get_option(YITH_WC_Dynamic_Pricing()->plugin_options);

            if( isset( $global_option['pricing-rules'][$section_key]) ){
                unset( $global_option['pricing-rules'][$section_key] );
                update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
            } elseif( isset( $global_option['cart-rules'][$section_key]) ){
		        unset( $global_option['cart-rules'][$section_key] );
		        update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
	        }

	        die();
        }

        public function ajax_section_active(  ) {
            $section_key = $_REQUEST['section'];
            $active = $_REQUEST['active'];
            $global_option = get_option(YITH_WC_Dynamic_Pricing()->plugin_options);

            if( isset( $global_option['pricing-rules'][$section_key]) ){
                $global_option['pricing-rules'][$section_key]['active'] = $active;
                update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
            } elseif( isset( $global_option['cart-rules'][$section_key]) ){
		        $global_option['cart-rules'][$section_key]['active'] = $active;
		        update_option( YITH_WC_Dynamic_Pricing()->plugin_options, $global_option);
	        }

            wp_send_json( $active );
        }

		/**
		 * Json Search Category to load product categories in chosen selects
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Emanuela Castorina
		 */
		public function ajax_category_search( ) {
			check_ajax_referer( 'search-categories', 'security' );

			ob_start();

			$term = (string) wc_clean( stripslashes( $_GET['term'] ) );

			if ( empty( $term ) ) {
				die();
			}
			global $wpdb;
			$terms = $wpdb->get_results( 'SELECT name, slug, wpt.term_id FROM ' . $wpdb->prefix . 'terms wpt, ' . $wpdb->prefix . 'term_taxonomy wptt WHERE wpt.term_id = wptt.term_id AND wptt.taxonomy = "product_cat" and wpt.name LIKE "%'.$term.'%" ORDER BY name ASC;' );

			$found_categories = array();

			if ( $terms ) {
				foreach ( $terms as $cat ) {
					$found_categories[$cat->term_id] = ( $cat->name ) ? $cat->name : 'ID: ' . $cat->slug;
				}
			}

			$found_categories = apply_filters( 'ywdpd_json_search_categories', $found_categories );
			wp_send_json( $found_categories );

		}

		/**
		 * Json Search Tag to load product tags in chosen selects
		 *
		 * @since 1.1.0
		 * @access public
		 * @author Emanuela Castorina
		 */
		public function ajax_tag_search( ) {
			check_ajax_referer( 'search-tags', 'security' );

			ob_start();
			$term = (string) wc_clean( stripslashes( $_GET['term'] ) );
			if ( empty( $term ) ) {
				die();
			}
			global $wpdb;
			$terms = $wpdb->get_results( 'SELECT name, slug, wpt.term_id FROM ' . $wpdb->prefix . 'terms wpt, ' . $wpdb->prefix . 'term_taxonomy wptt WHERE wpt.term_id = wptt.term_id AND wptt.taxonomy = "product_tag" and wpt.name LIKE "%'.$term.'%" ORDER BY name ASC;' );

			$found_tags = array();

			if ( $terms ) {
				foreach ( $terms as $tag ) {
					$found_tags[$tag->term_id] = ( $tag->name ) ? $tag->name : 'ID: ' . $tag->slug;
				}
			}

			$found_tags = apply_filters( 'ywdpd_json_search_tags', $found_tags );
			wp_send_json( $found_tags );

		}

		/**
		 * Json Search Customer to load customers in chosen selects
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Emanuela Castorina
		 */
		public function ajax_customers_search( ) {

			check_ajax_referer( 'search-customers', 'security' );

			ob_start();

			$term = (string) wc_clean( stripslashes( $_GET['term'] ) );

			if ( empty( $term ) ) {
				die();
			}
			$user_query = new WP_User_Query( array( 'search' => '*'.$term.'*', 'search_columns' => array( 'user_login', 'user_email','user_nicename', 'user_email') ) );

			$users = $user_query->get_results();

			$found_user = array();
			if( !empty($users)){
				foreach ( $users as $user ) {
					$found_user[$user->ID] = $user->data->user_email;
				}
			}

			$found_user = apply_filters( 'ywdpd_json_search_customers', $found_user );
			wp_send_json( $found_user );

		}

		/**
		 * plugin_row_meta
		 *
		 * add the action links to plugin admin page
		 *
		 * @param $plugin_meta
		 * @param $plugin_file
		 * @param $plugin_data
		 * @param $status
		 *
		 * @return   Array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      plugin_row_meta
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( defined( 'YITH_YWDPD_INIT' ) && YITH_YWDPD_INIT == $plugin_file ) {
				$plugin_meta[] = '<a href="' . $this->doc_url . '" target="_blank">' . __( 'Plugin Documentation', 'ywdpd' ) . '</a>';
			}
			return $plugin_meta;
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once ( YITH_YWDPD_DIR . 'plugin-fw/licence/lib/yit-licence.php' );
				require_once ( YITH_YWDPD_DIR . 'plugin-fw/licence/lib/yit-plugin-licence.php' );
			}
			YIT_Plugin_Licence()->register( YITH_YWDPD_INIT, YITH_YWDPD_SECRET_KEY, YITH_YWDPD_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_updates() {
			if( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once YITH_YWDPD_DIR.'plugin-fw/lib/yit-upgrade.php';
			}
			YIT_Upgrade()->register( YITH_YWDPD_SLUG, YITH_YWDPD_INIT );
		}

    }
}

/**
 * Unique access to instance of YITH_WC_Dynamic_Pricing_Admin class
 *
 * @return \YITH_WC_Dynamic_Pricing_Admin
 */
function YITH_WC_Dynamic_Pricing_Admin() {
    return YITH_WC_Dynamic_Pricing_Admin::get_instance();
}