<?php
/**
 * Plugin Name: WP AMP
 * Description: Accelerated Mobile Pages for your WordPress and WooCommerce websites.
 * Version:     7.4
 * Author:      TeamDev Ltd
 * Author URI:  https://www.teamdev.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AMPHTML' ) ) :
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	class AMPHTML {

		const AMP_QUERY    = 'amp';
		const E_LOG_FILE   = 'errors.log';
		const INCLUDES_DIR = 'includes';
		const TAB_DIR      = 'includes/tabs';
		const MODULES_DIR  = 'modules';

		/**
		 * @var AMPHTML_Template
		 */
		public $template;
		/**
		 * @var AMPHTML_Options
		 */
		public $options;

		private static $instance = null;

		public $version = '7.2';

		/**
		 * Creates or returns an instance of this class.
		 */
		public static function instance() {
			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
		 */
		public function __construct() {
			// helpers
			include_once( 'includes/amphtml-helper-functions.php' );
			// translations
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
			// image size
			$amp_width         = get_option( 'amphtml_content_width' );
			$amp_content_width = $amp_width ? $amp_width : '600';
			add_image_size( 'amphtml-image', $amp_content_width );
			// rel=amphtml
            add_action( 'wp_head', array( $this, 'add_rel_info' ) );

			$this->check_debug_mode();

			add_action( 'init', array ( $this, 'init' ), 99 );

			if( is_admin() ) {
				$this->admin_init();
			}
		}

		public function get_amphtml_url() {
			return plugin_dir_url( __FILE__ );
		}

		public function get_basename() {
			return plugin_basename( __FILE__ );
		}

		public function get_plugin_folder_name() {
			$names = explode( '/', self::get_basename() );

			return $names[0];
		}

		public function get_amphtml_path() {
			return plugin_dir_path( __FILE__ );
		}

		public function check_debug_mode() {
			if ( 'show_on_page' == get_option( 'amphtml_debug_mode' ) ) {
				error_reporting( E_ALL );
				ini_set( 'display_errors', 1 );
			} else {
				error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
				ini_set( 'display_errors', 0 );
			}
		}

        public function admin_init() {
            add_filter( 'plugin_action_links_' . $this->get_basename(), array( $this, 'action_links' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_styles' ) );
        }

		public function init() {
			if ( $this->is_amp() && 'save_to_log' == get_option( 'amphtml_debug_mode' ) ) {
				add_action( 'shutdown', array( $this, 'fatal_error_handler' ), 1 );
				set_error_handler( array( $this, 'error_handler' ) );
			}
			
			$this->includes();
			$this->options = new AMPHTML_Options();
			new AMPHTML_No_Conflict();

			do_action( 'amphtml_init' );

			add_action( 'template_redirect', array( $this, 'load_amphtml' ) );

			add_rewrite_endpoint( $this->get_endpoint(), EP_ALL );

			//exclude disabled pages ( like product cart ) from search
			add_action( 'pre_get_posts', array( $this, 'search_filter' ) );
			//pagination, custom post types and taxonomies support
			add_filter( 'do_parse_request', array( $this, 'parse_request' ), 10, 3 );
			//view original button redirect
			add_filter( 'amphtml_is_mobile_get_redirect_url', array( $this, 'view_original_redirect' ) );

			$this->check_version();
		}

		public function includes() {
			// template, options, sanitizer etc.
			include_once( 'includes/class-amphtml-template-abstract.php' );
			include_once( 'includes/class-amphtml-template.php' );
			include_once( 'includes/class-amphtml-options.php' );
			include_once( 'includes/class-amphtml-update.php' );
			include_once( 'includes/class-amphtml-no-conflict.php' );
			include_once( 'includes/class-amphtml-shortcode.php' );

			// tabs - TODO: needs refactoring
			include_once( 'includes/class-amphtml-tab-abstract.php' );
			include_once( 'includes/class-amphtml-tab.php' );

			// load tabs
			$this->load_components( self::TAB_DIR );

			// load add-ons
			$this->load_components( self::MODULES_DIR );
		}

		public function load_components( $dir ) {
			foreach ( glob( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . 'class-*.php' ) as $component ) {
				require_once $component;
			}
		}

		public function install() {
			new AMPHTML_Update();
			add_rewrite_endpoint( AMPHTML()->get_endpoint(), EP_ALL );
			flush_rewrite_rules();
		}

		public function check_version() {
			if ( get_option( 'amphtml_version' ) !== $this->version ) {
				$this->install();
				update_option( 'amphtml_version', $this->version );
			}
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'amphtml', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Enqueue and register JavaScript files here.
		 */
		public function register_scripts() {
			wp_enqueue_media();
			wp_enqueue_script( 'amphtml_mask', $this->get_amphtml_url() . 'js/jquery.mask.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'amphtml', $this->get_amphtml_url() . 'js/amphtml.js', array(
				'jquery',
				'jquery-ui-dialog',
				'wp-color-picker',
				'amphtml_mask'
			), '', true );

			wp_localize_script( 'amphtml', 'amphtml',
				array(
					'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
					'action'          => 'amphtml_options',
					'current_tab'     => $this->options->get_tabs()->get_current(),
					'current_section' => $this->options->get_tabs()->get_current_section(),
				)
			);
		}

		/**
		 * Enqueue and register CSS files here.
		 */
		public function register_styles() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_register_style( 'amphtml-admin-style', $this->get_amphtml_url() . 'css/admin-style.css' );
			wp_register_style( 'amphtml-admin-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
			wp_enqueue_style( 'amphtml-admin-style' );
			wp_enqueue_style( 'amphtml-admin-ui-css' );
		}

		public function get_rel_info() {
			global $wp, $wp_query;

			if ( $this->is_excluded( $wp_query->get_queried_object_id() ) ) {
				return '';
			}

            if ( get_option( 'permalink_structure' ) ) {
                $url = user_trailingslashit( home_url( $wp->request . '/' . AMPHTML()->get_endpoint() ) );
            } else {
                $url = home_url( add_query_arg( AMPHTML()->get_endpoint(), '1' ) );
            }

            if( is_search() AND isset( $_GET['s'] ) ){
                $url = user_trailingslashit( trailingslashit( get_search_link() ) . AMPHTML()->get_endpoint() );
            }

			return apply_filters( 'amphtml_rel_link_url', $url, $this );
		}

		public function add_rel_info() {
			$url = $this->get_rel_info();
			if ( !empty( $url ) ) {
				echo "<link rel='amphtml' href='$url' />";
			}
		}

		public function action_links( $links ) {
			$links[] = '<a href="' . $this->options->get_tab_url( 'general' ) . '">Settings</a>';

			return $links;
		}

		public function is_home_posts_page() {
			return ( is_home() && 'posts' == get_option( 'show_on_front' ) );
		}

		public function is_home_static_page() {
			return ( 'page' == get_option( 'show_on_front') && get_option( 'page_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
		}

		public function is_posts_page() {
			return ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}

		public function get_queried_object_id() {
			global $wp_query, $wp;

			$queried_object_id = $wp_query->get_queried_object_id();

			if ( $wp->request === $this->get_endpoint() ) {
				$queried_object_id = get_option( 'page_on_front' );
			}

			if ( $wp_query->is_archive() ) {
				$queried_object_id = '';
			}

			return $queried_object_id;
		}

		public function is_excluded( $id ) {
			global $wp_query;
			$is_excluded   = false;
			$allowed_pages = is_array( $this->options->get( 'archives' ) ) ? $this->options->get( 'archives' ) : array();
			if ( is_archive() && false == $this->is_allowed_page( $allowed_pages )
			     || ! is_archive() && $id && $this->is_excluded_post( $id )
				 || $this->is_excluded_posts_page() && $wp_query->is_home()
				 || is_search() && false == $this->is_allowed_page( $allowed_pages )
			) {
				$is_excluded = true;
			}

			return $is_excluded;
		}

		public function is_excluded_posts_page() {
			$allowed_pages = is_array( $this->options->get( 'archives' ) ) ? $this->options->get( 'archives' ) : array();
			return 'posts' == get_option( 'show_on_front') && ! array_search( 'show_on_front', $allowed_pages );
		}

		public function is_excluded_post( $id ) {
			$allowed_post_types = is_array( $this->options->get( 'post_types' ) ) ? $this->options->get( 'post_types' ) : array();
			$is_excluded = ( "true" === get_post_meta( $id, 'amphtml-exclude', true ) 
				|| false == in_array( get_post_type( $id ), $allowed_post_types ) );
			return apply_filters( 'amphtml_is_excluded_post', $is_excluded, $id );
		}

		public function load_amphtml() {
			global $wp;

			$queried_object_id = $this->get_queried_object_id();

			do_action( 'before_load_amphtml', $queried_object_id );

			$redirect_url = $this->get_redirect_url( $wp, $queried_object_id );
			if ( $redirect_url ) {
				wp_redirect( $redirect_url );
				exit();
			}

			include_once( 'includes/class-amphtml-template.php' );

			$this->template = new AMPHTML_Template( $this->options );
			new AMPHTML_Shortcode( $this->template );
			
			if ( $this->is_amp() ) {
				$this->template->load();
                $this->template = apply_filters( 'amphtml_template_load_after', $this->template );
				do_action( 'amphtml_before_render', $this->template );
                echo $this->template->render();
				exit();
			}
		}

		public function get_redirect_url( $wp, $queried_object_id ) {
			$url       = '';
			$post      = get_post( $queried_object_id );
			$is_mobile = $this->is_mobile() && false == $this->is_excluded( $queried_object_id );
			$post_id   = is_object( $post ) ? $post->ID : '';

			if ( $this->is_amp() ) {
				if ( isset( $_GET['is_amp'] ) && sanitize_text_field( $_GET['is_amp'] ) && isset( $_GET['s'] ) ) {
					$url = $this->get_search_redirect_url();
				} elseif ( $this->is_excluded( $queried_object_id ) && ! is_404() ) {
					$url = $this->get_excluded_redirect_url( $wp, $queried_object_id );
				} elseif ( is_singular( array( 'post', 'page' ) ) && post_password_required( $post_id )	) {
					// redirect to original password form from amp url
					$url = get_permalink( $post_id );
				}
			} else if ( apply_filters( 'amphtml_is_mobile_get_redirect_url', $is_mobile ) && ! post_password_required( $post_id ) ) {
				if ( '' != get_option( 'permalink_structure' ) ) {
					$url = home_url( $wp->request ) . '/' . $this->get_endpoint() . '/';
				} else {
					$args = array();
					parse_str( $_SERVER['QUERY_STRING'], $args );
					$args[ $this->get_endpoint() ] = 1;
					$url                           = add_query_arg( $args );
				}
			}

			return $url;
		}

		public function view_original_redirect( $is_mobile ) {
			return $is_mobile && false == isset( $_GET['view-original-redirect'] );
		}

		public function get_search_redirect_url() {
			if ( get_query_var( 's' ) ) {
				$url = '/' . 'search' . '/' . get_query_var( 's' ) . '/' . $this->get_endpoint() . '/';
			} else {
				$url = '/' . '?s' . '&' . $this->get_endpoint() . '=1';
			}

			return $url;
		}

		public function get_excluded_redirect_url( $wp, $queried_object_id ) {
			$endpoint = $this->get_endpoint();
			if ( $queried_object_id ) {
				$url = get_permalink( $queried_object_id );
			} else if ( '' != get_option( 'permalink_structure' ) ) {
				$url = home_url( rtrim( $wp->request, $endpoint ) );
			} else {
				$url = remove_query_arg( $endpoint );
			}

			return $url;
		}

		public function get_endpoint() {
			$endpoint_opt = get_option( 'amphtml_endpoint' );
			$endpoint     = ( $endpoint_opt ) ? $endpoint_opt : self::AMP_QUERY;

			return $endpoint;
		}

		public function get_options() {
			return $this->options->get_options();
		}

		public function is_mobile() {
			return wp_is_mobile() && $this->options->get( 'mobile_amp' );
		}

		public function is_allowed_page( $allowed_pages ) {
			global $wp_query;
			$is_allowed = false;
			$type       = '';
			switch ( true ) {
				case is_date():
					$type = 'date';
					break;
				case is_author():
					$type = 'author';
					break;
				case is_category():
					$type = 'category';
					break;
				case is_tag():
					$type = 'tag';
					break;
				case is_tax():
					$type = 'tax';
					break;
				case is_post_type_archive():
					$type = 'post_type_archive';
					break;
				case is_search():
					$type = 'search';
			}

			if ( 'tax' == $type && isset( $wp_query->query_vars['taxonomy'] ) ) {
				$type = $wp_query->query_vars['taxonomy'];
			}

			if ( 'post_type_archive' == $type ) {
				$type = get_post_type();
			}

			if ( in_array( $type, $allowed_pages ) ) {
				$is_allowed = true;
			}

			return $is_allowed;
		}

		public function get_class_filename( $class ) {
			$class = strtolower( $class );

			return 'class-' . str_replace( '_', '-', $class ) . '.php';
		}

		public function is_amp() {
			$endpoint = $this->get_endpoint();
			
			if ( '' == get_option( 'permalink_structure' ) ) {
				parse_str( $_SERVER['QUERY_STRING'], $url );
				return isset( $url[ $endpoint ] );
			}
			
			$url_parts   = explode( '?', $_SERVER["REQUEST_URI"] );
			$query_parts = explode( '/', $url_parts[0] );
			
			$is_amp = ( in_array( $endpoint, $query_parts ) );
			do_action( 'amphtml_is_amp', $is_amp );

			return $is_amp;
		}

		public function parse_request( $is_parse, $wp, $extra_query_vars ) {
			if ( $this->is_amp() ) {
				$is_parse = false;
				$this->_parse_request( $wp, $extra_query_vars );
			}

			return $is_parse;
		}

		protected function _parse_request( $wp, $extra_query_vars ) {
			global $wp_rewrite;

			$wp->query_vars       = array();
			$post_type_query_vars = array();

			$amp_endpoint = $this->get_endpoint();
			$wp->query_vars[ $amp_endpoint ] = '';

			if ( is_array( $extra_query_vars ) ) {
				$wp->extra_query_vars = &$extra_query_vars;
			} elseif ( ! empty( $extra_query_vars ) ) {
				parse_str( $extra_query_vars, $wp->extra_query_vars );
			}
			// Process PATH_INFO, REQUEST_URI, and 404 for permalinks.

			// Fetch the rewrite rules.
			$rewrite = $wp_rewrite->wp_rewrite_rules();

			if ( ! empty( $rewrite ) ) {
				// If we match a rewrite rule, this will be cleared.
				$error             = '404';
				$wp->did_permalink = true;

				$pathinfo = isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '';
				list( $pathinfo ) = explode( '?', $pathinfo );
				$pathinfo = str_replace( "%", "%25", $pathinfo );

				list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
				$self            = $_SERVER['PHP_SELF'];
				$home_path       = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
				$home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

				// Trim path info from the end and the leading home path from the
				// front. For path info requests, this leaves us with the requesting
				// filename, if any. For 404 requests, this leaves us with the
				// requested permalink.
				$req_uri  = str_replace( $pathinfo, '', $req_uri );
				$req_uri  = trim( $req_uri, '/' );
				$req_uri  = preg_replace( $home_path_regex, '', $req_uri );
				$req_uri  = trim( $req_uri, '/' );
				$pathinfo = trim( $pathinfo, '/' );
				$pathinfo = preg_replace( $home_path_regex, '', $pathinfo );
				$pathinfo = trim( $pathinfo, '/' );
				$self     = trim( $self, '/' );
				$self     = preg_replace( $home_path_regex, '', $self );
				$self     = trim( $self, '/' );

				// The requested permalink is in $pathinfo for path info requests and
				//  $req_uri for other requests.
				if ( ! empty( $pathinfo ) && ! preg_match( '|^.*' . $wp_rewrite->index . '$|', $pathinfo ) ) {
					$requested_path = $pathinfo;
				} else {
					// If the request uri is the index, blank it out so that we don't try to match it against a rule.
					if ( $req_uri == $wp_rewrite->index ) {
						$req_uri = '';
					}
					$requested_path = $req_uri;
				}
				$requested_file = $req_uri;

				$wp->request = $requested_path;

				// Look for matches.
				$endpoint      = sprintf( '/\/%s(\/)?$/', $amp_endpoint );
				$request_match = ( $requested_path == $amp_endpoint )
					? $requested_path : preg_replace( $endpoint, '', $requested_path );

				if ( empty( $request_match ) ) {
					// An empty request could only match against ^$ regex
					if ( isset( $rewrite['$'] ) ) {
						$wp->matched_rule = '$';
						$query            = $rewrite['$'];
						$matches          = array( '' );
					}
				} else {
					foreach ( (array) $rewrite as $match => $query ) {
						// If the requested file is the anchor of the match, prepend it to the path info.
						if ( ! empty( $requested_file ) && strpos( $match, $requested_file ) === 0 && $requested_file != $requested_path ) {
							$request_match = $requested_file . '/' . $requested_path;
						}

						if ( preg_match( "#^$match#", $request_match, $matches ) ||
						     preg_match( "#^$match#", urldecode( $request_match ), $matches )
						) {

							if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
								// This is a verbose page match, let's check to be sure about it.
								$page = get_page_by_path( $matches[ $varmatch[1] ] );
								if ( ! $page ) {
									continue;
								}

								$post_status_obj = get_post_status_object( $page->post_status );
								if ( ! $post_status_obj->public && ! $post_status_obj->protected
								     && ! $post_status_obj->private && $post_status_obj->exclude_from_search
								) {
									continue;
								}
							}

							// Got a match.
							$wp->matched_rule = $match;
							break;
						}
					}
				}

				if ( isset( $wp->matched_rule ) ) {
					// Trim the query of everything up to the '?'.
					$query = preg_replace( "!^.+\?!", '', $query );

					// Substitute the substring matches into the query.
					$query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );

					$wp->matched_query = $query;

					// Parse the query.
					parse_str( $query, $perma_query_vars );

					// If we're processing a 404 request, clear the error var since we found something.
					if ( '404' == $error ) {
						unset( $error, $_GET['error'] );
					}
				}

				// If req_uri is empty or if it is a request for ourself, unset error.
				if ( empty( $requested_path ) || $requested_file == $self || strpos( $_SERVER['PHP_SELF'], 'wp-admin/' ) !== false ) {
					unset( $error, $_GET['error'] );

					if ( isset( $perma_query_vars ) && strpos( $_SERVER['PHP_SELF'], 'wp-admin/' ) !== false ) {
						unset( $perma_query_vars );
					}

					$wp->did_permalink = false;
				}
			}

			/**
			 * Filters the query variables whitelist before processing.
			 *
			 * Allows (publicly allowed) query vars to be added, removed, or changed prior
			 * to executing the query. Needed to allow custom rewrite rules using your own arguments
			 * to work, or any other custom query variables you want to be publicly available.
			 *
			 * @since 1.5.0
			 *
			 * @param array $public_query_vars The array of whitelisted query variables.
			 */
			$wp->public_query_vars = apply_filters( 'query_vars', $wp->public_query_vars );

			foreach ( get_post_types( array(), 'objects' ) as $post_type => $t ) {
				if ( is_post_type_viewable( $t ) && $t->query_var ) {
					$post_type_query_vars[ $t->query_var ] = $post_type;
				}
			}

			foreach ( $wp->public_query_vars as $wpvar ) {
				if ( isset( $wp->extra_query_vars[ $wpvar ] ) ) {
					$wp->query_vars[ $wpvar ] = $wp->extra_query_vars[ $wpvar ];
				} elseif ( isset( $_POST[ $wpvar ] ) ) {
					$wp->query_vars[ $wpvar ] = $_POST[ $wpvar ];
				} elseif ( isset( $_GET[ $wpvar ] ) ) {
					$wp->query_vars[ $wpvar ] = $_GET[ $wpvar ];
				} elseif ( isset( $perma_query_vars[ $wpvar ] ) ) {
					$wp->query_vars[ $wpvar ] = $perma_query_vars[ $wpvar ];
				}

				if ( ! empty( $wp->query_vars[ $wpvar ] ) ) {
					if ( ! is_array( $wp->query_vars[ $wpvar ] ) ) {
						$wp->query_vars[ $wpvar ] = (string) $wp->query_vars[ $wpvar ];
					} else {
						foreach ( $wp->query_vars[ $wpvar ] as $vkey => $v ) {
							if ( ! is_object( $v ) ) {
								$wp->query_vars[ $wpvar ][ $vkey ] = (string) $v;
							}
						}
					}

					if ( isset( $post_type_query_vars[ $wpvar ] ) ) {
						$wp->query_vars['post_type'] = $post_type_query_vars[ $wpvar ];
						$wp->query_vars['name']      = $wp->query_vars[ $wpvar ];
					}
				}
			}

			// Convert urldecoded spaces back into +
			foreach ( get_taxonomies( array(), 'objects' ) as $taxonomy => $t ) {
				if ( $t->query_var && isset( $wp->query_vars[ $t->query_var ] ) ) {
					$wp->query_vars[ $t->query_var ] = str_replace( ' ', '+', $wp->query_vars[ $t->query_var ] );
				}
			}

			// Don't allow non-publicly queryable taxonomies to be queried from the front end.
			if ( ! is_admin() ) {
				foreach ( get_taxonomies( array( 'publicly_queryable' => false ), 'objects' ) as $taxonomy => $t ) {
					/*
					 * Disallow when set to the 'taxonomy' query var.
					 * Non-publicly queryable taxonomies cannot register custom query vars. See register_taxonomy().
					 */
					if ( isset( $wp->query_vars['taxonomy'] ) && $taxonomy === $wp->query_vars['taxonomy'] ) {
						unset( $wp->query_vars['taxonomy'], $wp->query_vars['term'] );
					}
				}
			}

			// Limit publicly queried post_types to those that are publicly_queryable
			if ( isset( $wp->query_vars['post_type'] ) ) {
				$queryable_post_types = get_post_types( array( 'publicly_queryable' => true ) );
				if ( ! is_array( $wp->query_vars['post_type'] ) ) {
					if ( ! in_array( $wp->query_vars['post_type'], $queryable_post_types ) ) {
						unset( $wp->query_vars['post_type'] );
					}
				} else {
					$wp->query_vars['post_type'] = array_intersect( $wp->query_vars['post_type'], $queryable_post_types );
				}
			}

			// Resolve conflicts between posts with numeric slugs and date archive queries.
			$wp->query_vars = wp_resolve_numeric_slug_conflicts( $wp->query_vars );

			foreach ( (array) $wp->private_query_vars as $var ) {
				if ( isset( $wp->extra_query_vars[ $var ] ) ) {
					$wp->query_vars[ $var ] = $wp->extra_query_vars[ $var ];
				}
			}

			if ( isset( $error ) ) {
				$wp->query_vars['error'] = $error;
			}

			/**
			 * Filters the array of parsed query variables.
			 *
			 * @since 2.1.0
			 *
			 * @param array $query_vars The array of requested query variables.
			 */
			$wp->query_vars = apply_filters( 'request', $wp->query_vars );

			/**
			 * Fires once all query variables for the current request have been parsed.
			 *
			 * @since 2.1.0
			 *
			 * @param WP &$wp Current WordPress environment instance (passed by reference).
			 */
			unset( $wp->query_vars[ $amp_endpoint ] );
			do_action_ref_array( 'parse_request', array( &$wp ) );
		}

		public function search_filter( $query ) {
			if ( ! is_admin() && $query->is_main_query() ) {
				if ( $query->is_search ) {
					$query->set( 'meta_query',
						array(
							'relation' => 'OR',
							array(
								'key'     => 'amphtml-exclude',
								'value'   => '',
								'compare' => 'NOT EXISTS'
							),
							array(
								'key'     => 'amphtml-exclude',
								'value'   => 'true',
								'compare' => '!='
							),
						)
					);
				}
			}
		}

		public function get_canonical_url() {
			global $wp;

			if ( get_option( 'permalink_structure' ) ) {
				$url = rtrim( home_url( add_query_arg( array(), $wp->request ) ), $this->get_endpoint() );
			} else {
				$url = home_url( remove_query_arg( 'amp' ) );
			}

			return user_trailingslashit( $url );
		}

		public function fatal_error_handler() {
			$last_error = error_get_last();

			if ( $last_error ) {
				$error = implode( ' ', $last_error );
				error_log( $error, 3, $this->get_amphtml_path(). DIRECTORY_SEPARATOR. self::E_LOG_FILE );
				wp_redirect( $this->get_canonical_url() );
			}
		}

		public function error_handler( $errno, $errstr, $errfile, $errline ) {
			$time  = date( 'Y-m-d H:i:s', time() );
			$ver   = PHP_VERSION_ID;
			$error = "$time $errno $errstr $errfile $errline $ver" . PHP_EOL;
			error_log( $error, 3, $this->get_amphtml_path(). DIRECTORY_SEPARATOR. self::E_LOG_FILE );
		}

		public function get_template(){
			return $this->template;
		}

	}

endif;

function AMPHTML() {
	return AMPHTML::instance();
}

AMPHTML();

register_activation_hook( __FILE__, 'amphtml_activate' );
register_deactivation_hook( __FILE__, 'amphtml_deactivate' );

function amphtml_activate() {
	add_rewrite_endpoint( AMPHTML()->get_endpoint(), EP_ALL );
	flush_rewrite_rules();
}

function amphtml_deactivate() {
    flush_rewrite_rules();
}