<?php
/**
 * Creates Static Block Post Type
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( ! class_exists( 'StaticBlockContent' ) ) :

/**
 * StaticBlockContent Class
 */
class StaticBlockContent {
	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init',								array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'after_switch_theme',				'flush_rewrite_rules' );

		add_action( 'add_meta_boxes',					array( __CLASS__, 'register_meta_box' ) );	
		add_action( 'save_post',						array( __CLASS__, 'save_meta_box' ) );
	}

	/**
	 * Register post types.
	 */
	public static function register_post_types() {

		/* Set up the arguments for the post type. */
		$args = array(
			'labels' => array(
				'name' 					=>	esc_html_x('Static Content Blocks', 'post type general name', 'pizzaro-extensions'),
				'singular_name' 		=>	esc_html_x('Static Block', 'post type singular name', 'pizzaro-extensions'),
				'add_new' 				=>	esc_html_x('Add New', 'block', 'pizzaro-extensions'),
				'add_new_item' 			=>	esc_html__('Add New Block', 'pizzaro-extensions'),
				'edit_item' 			=>	esc_html__('Edit Block', 'pizzaro-extensions'),
				'new_item' 				=>	esc_html__('New Block', 'pizzaro-extensions'),
				'all_items' 			=>	esc_html__('Static Blocks', 'pizzaro-extensions'),
				'view_item' 			=>	esc_html__('View Block', 'pizzaro-extensions'),
				'search_items' 			=>	esc_html__('Search', 'pizzaro-extensions'),
				'not_found' 			=>	esc_html__('No blocks found', 'pizzaro-extensions'),
				'not_found_in_trash' 	=>	esc_html__('No blocks found in Trash', 'pizzaro-extensions'), 
				'parent_item_colon' 	=>	'',
				'menu_name' 			=>	esc_html__('Static Content', 'pizzaro-extensions'), 
			),
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'public'              => true,
			'show_ui'             => true,
			'query_var'           => 'static_block',
			'rewrite'             => array('slug' => 'static_block'),
			'supports'            => array(
				'title',
				'editor',
				'revisions',
			),
		);

		if ( ! post_type_exists('static_block') ) {
			/* Register the post type. */
			register_post_type(
				'static_block', // Post type name. Max of 20 characters. Uppercase and spaces not allowed.
				apply_filters( 'pizzaro_static_block_post_type_args', $args )      // Arguments for post type.
			);
		}
	}

	/**
	 * Registering static_block meta box for configuring static_block
	 */
	public static function register_meta_box(){
		add_meta_box('static-block-meta-box-filters', esc_html__( 'Content Options', 'pizzaro-extensions' ), array( __CLASS__, 'display_meta_box' ), 'static_block', 'side', 'default' );
	}

	/**
	 * Displaying static_block meta box
	 */
	public static function display_meta_box(){
		global $post;
		
		/**
		 * Get currently saved values
		 */
		$content_filters = get_post_meta( $post->ID, '_pizzaro_static_block_content_filters', true );
		$wpautop = get_post_meta( $post->ID, '_pizzaro_static_block_wpautop', true );
		?>
		<div class="metaField_field_wrapper metaField_field_content_filters">
			<p><label for="content_filters"><strong><?php echo esc_html__('Content Filters', 'pizzaro-extensions'); ?></strong></label></p>
			<label class="metaField_radio" style="display: block; padding: 2px 0;">
				<input class="metaField_radio" type="radio" name="content_filters" value="default" <?php echo ( ( $content_filters == 'default' || !$content_filters ) ? 'checked="checked"' : '' ); ?>> <?php echo esc_html__('Defaults (recommended)', 'pizzaro-extensions'); ?>
			</label>
			<label class="metaField_radio" style="display: block; padding: 2px 0;">
				<input class="metaField_radio" type="radio" name="content_filters" value="all" <?php echo ( $content_filters == 'all' ? 'checked="checked"' : '' ); ?>> <?php echo esc_html__('All Content Filters', 'pizzaro-extensions'); ?>
			</label>
			<p class="metaField_caption" style="color:#999"><?php echo esc_html__('Apply all WP content filters? This will include plugin added filters.', 'pizzaro-extensions'); ?></p>
		</div>
		<div class="metaField_field_wrapper metaField_field_wpautop" style="border-top: 1px solid #dfdfdf;">
			<p><label for="wpautop"><strong><?php echo esc_html__('Auto Paragraphs', 'pizzaro-extensions'); ?></strong></label></p>
			<label class="metaField_radio" style="display: block; padding: 2px 0;">
				<input class="metaField_radio" type="radio" name="wpautop" value="on" <?php echo ( ( $wpautop == 'on' || !$wpautop ) ? 'checked="checked"' : '' ); ?>> <?php echo esc_html__('On', 'pizzaro-extensions'); ?>
			</label>
			<label class="metaField_radio" style="display: block; padding: 2px 0;">
				<input class="metaField_radio" type="radio" name="wpautop" value="off" <?php echo ( $wpautop == 'off' ? 'checked="checked"' : '' ); ?>> <?php echo esc_html__('Off', 'pizzaro-extensions'); ?>
			</label>
			<p class="metaField_caption" style="color:#999"><?php echo esc_html__('Add &lt;p&gt; and &lt;br&gt; tags automatically. (disabling may fix layout issues)', 'pizzaro-extensions'); ?></p>
		</div>
		<?php
		wp_nonce_field( "_pizzaro_static_block_meta_box_filters", "_pizzaro_static_block_meta_box_filters" );
	}

	/**
	 * Saving meta box data
	 */
	public static function save_meta_box( $post_id ){
		/**
		 * If save_post is triggered from front end, there will be no get_current_screen() loaded. Stop the process.
		 */
		if( ! function_exists( 'get_current_screen' ) ){
			return;
		}

		$screen = get_current_screen();
		// Only run this on static_block editor screen
		if ( $screen != null && $screen->post_type != 'static_block' ) {
			return;
		}

		// Cancel if this is an autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify nonce
		if( ! isset( $_POST["_pizzaro_static_block_meta_box_filters"] ) || ! wp_verify_nonce( $_POST["_pizzaro_static_block_meta_box_filters"], "_pizzaro_static_block_meta_box_filters" ) ) {
			return;
		}

		// if our current user can't edit this post, bail
		if( ! current_user_can( 'edit_posts' ) ) {
			return;		
		}

		// Save static_block data
		if( ! empty( $_POST['content_filters'] ) ) {
			update_post_meta( $post_id, "_pizzaro_static_block_content_filters", $_POST['content_filters'] ); 			
		}

		if( ! empty( $_POST['wpautop'] ) ) {
			update_post_meta( $post_id, "_pizzaro_static_block_wpautop", $_POST['wpautop'] );
		}
	}
}

endif;

/**
 * Initialize
 */
StaticBlockContent::init();