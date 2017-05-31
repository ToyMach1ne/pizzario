<?php

class AMPHTML_Ad {

	const DEFAULT_NUM_OF_SECTIONS = '2';

	const OPTION = 'amphtml_ad_sections';

	private $ad_sections;

	function __construct() {
		add_action( 'amphtml_init_tab', array( $this, 'init' ) );
		add_filter( 'amphtml_admin_tab_list', array( $this, 'add_tab' ), 10, 1 );
		add_filter( 'amphtml_ad_tab_sections', array( $this, 'update_ad_section' ), 10, 2 );
		$this->ad_sections = get_option( self::OPTION )
			? explode( ',', get_option( self::OPTION ) ) : array();
		$this->add_blocks_to_template();

	}

	public function init( $tab ) {
		if ( 'ad' == $tab->get_name() && $tab->is_current() ) {
			add_action( 'get_tab_submit_button', array( $this, 'update_submit_button' ) );
			add_action( 'amphtml_proceed_settings_form', array( $this, 'add_new_ad_block' ) );
			add_action( 'amphtml_proceed_settings_form', array( $this, 'delete_ad_block' ) );
		}
	}

	public function add_tab( $list ) {
		$list['ad'] = __( 'Ads', 'amphtml' );

		return $list;
	}

	public function update_ad_section( $sections, $tab ) {
		foreach ( $this->ad_sections as $section ) {
			$sections[ $section ] = __( "Ad Block #$section", 'amphtml' );
		}

		return $sections;
	}

	public function update_ad_fields( $fields, $tab ) {
		foreach ( $this->ad_sections as $section ) {
			$section_fields = $this->get_section_fields( $section, $tab );
			$fields         = array_merge( $fields, $section_fields );
		}

		return $fields;
	}

	public function update_submit_button( $tab ) {
		?>
		<input type="submit"
		       name="add_new_ad" id="add-new-ad"
		       class="button"
		       value="<?php echo __( 'Add New', 'amphtml' ); ?>"
		       style="margin-left: 10px;">
		<?php if ( ! in_array( $tab->get_current_section(), array( 'top', 'bottom' ) ) ): ?>
			<input type="submit"
			       name="delete_ad" id="delete-ad"
			       class="button"
			       value="<?php echo __( 'Delete Current', 'amphtml' ); ?>"
			       style="margin-left: 10px;">
		<?php endif;
	}

	public function add_new_ad_block( AMPHTML_Options $opt ) {
		if ( $opt->get_request_var( 'action' ) == 'add_new_ad_block' ) {
			$new_section_id      = end( $this->ad_sections )
				? end( $this->ad_sections ) : self::DEFAULT_NUM_OF_SECTIONS;
			$this->ad_sections[] = ++ $new_section_id;
			update_option( self::OPTION, implode( ',', $this->ad_sections ) );

			$new_section_url = add_query_arg( array(
				'page'    => 'amphtml-options',
				'tab'     => 'ad',
				'section' => $new_section_id
			), get_admin_url( null, 'options-general.php' ) );

			wp_redirect( $new_section_url );
		}
	}

	public function delete_ad_block( AMPHTML_Options $opt ) {
		$current_section = isset( $_POST['section'] ) ? $_POST['section'] : '';
		if ( $opt->get_request_var( 'action' ) == 'delete_ad_block' && $current_section ) {
			$block_opts = array(
				"ad_layout_$current_section",
				"ad_height_$current_section",
				"ad_width_$current_section",
				"ad_type_$current_section",
				"ad_doubleclick_data_slot_$current_section",
				"ad_data_id_client_$current_section",
				"ad_adsense_data_slot_$current_section",
				"ad_content_code_$current_section"
			);
			if ( ( $key = array_search( $current_section, $this->ad_sections ) ) !== false ) {
				unset( $this->ad_sections[ $key ] );
				update_option( self::OPTION, implode( ',', $this->ad_sections ) );
				foreach ( $block_opts as $opt ) {
					delete_option( $opt );
				}
			}

			$new_section_url = add_query_arg( array(
				'page'    => 'amphtml-options',
				'tab'     => 'ad',
				'section' => 'top'
			), get_admin_url( null, 'options-general.php' ) );

			wp_redirect( $new_section_url );
		}
	}

	public function add_blocks_to_template() {
		$template_filters = array(
			'posts'    => 'amphtml_template_posts_fields',
			'products' => 'amphtml_template_products_fields',
			'pages'    => 'amphtml_template_page_fields',
			'search'   => 'amphtml_template_search_fields',
			'blog'     => 'amphtml_template_blog_fields',
			'archives' => 'amphtml_template_archive_fields'
		);

		$template_filters = apply_filters( 'amphtml_fields_tabs_for_ad', $template_filters );

		foreach ( $template_filters as $section => $filter ) {
			add_filter( $filter, array( $this, 'get_blocks_for_templates' ), 10, 3 );
		}
	}

	public function get_blocks_for_templates( $fields, $section, $tab ) {
		$blocks           = array();
		$section_field_id = explode( '_', $fields[0]['id'] );
		$prefix           = $section_field_id[0];

		$top_ad_block[] = array(
			'id'                    => "{$prefix}_ad_top",
			'title'                 => __( 'Ad Block #1', 'amphtml' ),
			'default'               => 0,
			'section'               => $section,
			'display_callback'      => array( $tab, 'display_checkbox_field' ),
			'display_callback_args' => array( "{$prefix}_ad_top" ),
			'description'           => __( 'Show ad block #1', 'amphtml' ),
		);

		$bottom_ad_block[] = array(
			'id'                    => "{$prefix}_ad_bottom",
			'title'                 => __( 'Ad Block #2', 'amphtml' ),
			'default'               => 0,
			'section'               => $section,
			'display_callback'      => array( $tab, 'display_checkbox_field' ),
			'display_callback_args' => array( "{$prefix}_ad_bottom" ),
			'description'           => __( 'Show ad block #2', 'amphtml' ),
		);

		foreach ( $this->ad_sections as $block ) {
			$blocks[] = array(
				'id'                    => "post_ad_$block",
				'title'                 => __( "Ad Block #$block", 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $tab, 'display_checkbox_field' ),
				'display_callback_args' => array( "post_ad_$block" ),
				'description'           => __( "Show ad block #$block", 'amphtml' ),
			);
		}

		return array_merge( $top_ad_block, $fields, $bottom_ad_block, $blocks );
	}
}

new AMPHTML_Ad();