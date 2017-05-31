<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_General extends AMPHTML_Tab_Abstract {

	public function get_fields() {
		return array(
			array(
				'id'               => 'endpoint',
				'title'            => __( 'AMP Endpoint', 'amphtml' ),
				'default'          => 'amp',
				'display_callback' => array( $this, '' ),
				'description'      => __( 'This string will be added to the end of URL for all pages with AMP content', 'amphtml' )
			),
			array(
				'id'                    => 'mobile_amp',
				'title'                 => __( 'Redirect Mobile Users', 'amphtml' ),
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'mobile_amp' ),
				'description'           => __( 'Redirect all mobile users to AMP version by default', 'amphtml' )
			),
			array(
				'id'                => 'content_width',
				'title'             => __( 'AMP Content Max-Width', 'amphtml' ),
				'default'           => 600,
				'display_callback'  => array( $this, 'display_content_width' ),
				'sanitize_callback' => array( $this, 'sanitize_content_width' ),
				'description'       => __( 'Setup maximum width for AMP content (in pixels)', 'amphtml' )
			),
			array(
				'id'                => 'element_height',
				'title'             => __( 'AMP Element Default Height', 'amphtml' ),
				'default'           => 400,
				'display_callback'  => array( $this, 'display_element_height' ),
				'sanitize_callback' => array( $this, 'sanitize_element_height' ),
				'description'       => __( 'If element (image, iframe, etc) height value does not exist, will be applied this one', 'amphtml' )
			),
			array(
				'id'                    => 'post_types',
				'title'                 => __( 'Post Types', 'amphtml' ),
				'default'               => array( 'post', 'page' ),
				'display_callback'      => array( $this, 'display_multiple_select' ),
				'display_callback_args' => array(
					'id'             => 'post_types',
					'select_options' => $this->get_post_types()
				),
				'description'           => __( 'Enable AMP for selected post types', 'amphtml' )
			),
			array(
				'id'                    => 'archives',
				'title'                 => __( 'Archives', 'amphtml' ),
				'default'               => array( 'date', 'author', 'category', 'tag' ),
				'display_callback'      => array( $this, 'display_multiple_select' ),
				'display_callback_args' => array(
					'id'             => 'archives',
					'select_options' => $this->get_archives()
				),
				'description'           => __( 'Enable AMP for selected archive pages', 'amphtml' )
			),
			array(
				'id'                    => 'default_the_content',
				'title'                 => __( 'Compatibility Mode', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'default_the_content' ),
				'description'           => __( 'Remove third-party the_content hooks for better compatibility', 'amphtml' )
			),
			array(
				'id'                    => 'is_hidden_forms',
				'title'                 => __( 'Hide Forms', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'is_hidden_forms' ),
				'description'           => __( 'Hide forms in post content for avoiding validation errors', 'amphtml' )
			),
			array(
				'id'                    => 'rtl_enable',
				'title'                 => __( 'Enable RTL', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'rtl_enable' ),
				'description'           => __( 'Enable Right-to-Left language support', 'amphtml' )
			),
			array(
				'id'                    => 'debug_mode',
				'title'                 => __( 'Debug Mode', 'amphtml' ),
				'display_callback'      => array( $this, 'display_select' ),
				'default'               => 'off',
				'display_callback_args' => array(
					'id'             => 'debug_mode',
					'select_options' => array(
						'off'          => 'Off',
						'show_on_page' => 'Show on page',
						'save_to_log'  => 'Save to log'
					)
				),
				'description'           => __( 'Show errors, warnings and notices', 'amphtml' )
			)
		);
	}

	public function sanitize_content_width( $content_width ) {
		$content_width = sanitize_text_field( $content_width );
		if ( 0 === preg_match( '/^[1-9][0-9]*$/', $content_width ) ) {
			add_settings_error( $this->options->get( 'content_width', 'name' ), 'cw_error', __( 'Insert a valid content width', 'amphtml' ), 'error' );
			$valid_field = $this->options->get( 'content_width' );
		} else {
			$valid_field = $content_width;
		}

		return $valid_field;
	}

	public function sanitize_element_height( $value ) {
		$id = 'element_height';
		$value = sanitize_text_field( $value );
		if ( 0 === preg_match( '/^[1-9][0-9]*$/', $value ) ) {
			add_settings_error( $this->options->get( $id, 'name' ), 'cw_error', __( 'Insert a valid element height', 'amphtml' ), 'error' );
			$valid_field = $this->options->get( $id );
		} else {
			$valid_field = $value;
		}

		return $valid_field;
	}

	public function sanitize_number( $val ) {
		$valid_field = $this->options->get( 'facebook_pixel' );
		$val = sanitize_text_field( $val );
		if ( 0 === preg_match( '/^[0-9]+$|^$/', $val ) ) {
			add_settings_error( $this->options->get( 'facebook_pixel', 'name' ), 'cw_error', __( 'Insert a valid pixel ID', 'amphtml' ), 'error' );
		} else {
			$valid_field = $val;
		}

		return $valid_field;
	}

	public function sanitize_endpoint( $endpoint ) {
		$endpoint = sanitize_title( $endpoint );
		if ( ! $endpoint ) {
			add_settings_error( $this->options->get( 'endpoint', 'name' ), 'endpoint_error', __( 'Insert a valid endpoint', 'amphtml' ), 'error' );
			$valid_field = $this->options->get( 'endpoint' );
		} else {
			$valid_field = $endpoint;
		}

		return $valid_field;
	}

	public function display_content_width() {
		$id = 'content_width';
		?>
		<input style="width: 28%" type="text"
		       name="<?php echo $this->options->get( $id, 'name' ) ?>"
		       id="custom_content_width" value="<?php echo esc_attr( $this->options->get( $id ) ) ?>"
		       required
		/>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
		<?php endif; ?>
		<?php
	}

	public function display_element_height() {
		$id = 'element_height';
		?>
		<input style="width: 28%" type="text"
		       name="<?php echo $this->options->get( $id, 'name' ) ?>"
		       id="custom_element_height" value="<?php echo esc_attr( $this->options->get( $id ) ) ?>"
		       required
		/>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
		<?php endif; ?>
		<?php
	}

	public function get_archives() {

		$archives = array(
			'date'     => 'Dates',
			'author'   => 'Authors',
			'category' => 'Categories',
			'tag'      => 'Tags',
			'search'   => 'Search Results'
		);
		$taxonomies = get_taxonomies(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'object'
		);
		foreach ( $taxonomies as $taxonomy ) {
			if( $taxonomy->show_ui ) {
				$archives[ $taxonomy->name ] = $taxonomy->label;
			}
		}

		$archives = array_merge( $archives, $this->get_post_types( array( 'public' => true, 'has_archive' => true ) ) );

		if ( get_option( 'show_on_front' ) ) {
			$archives['show_on_front'] = 'Your latest posts';
		}

		return $archives;
	}

	public function get_post_types( $args = '' ) {
		$types        = array();
		$default_args = array(
			'public' => true
		);
		$args         = is_array( $args ) ? $args : $default_args;
		$post_types   = get_post_types( $args, 'object' );

		foreach ( $post_types as $type ) {
			$types[ $type->name ] = $type->label;
		}

		return $types;
	}

	public function display_number_field( $args ) {
		$this->display_text_field( $args, 'number' );
	}

}