<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Ad extends AMPHTML_Tab_Abstract {

	public function __construct($name, $options, $is_current = false) {
		parent::__construct($name, $options, $is_current);
		add_filter('wpamp_content_tags', array( $this, 'register_ad_tag') );
	}
	
	public function register_ad_tag($tags) {
		$tags['amp-ad'] = array( 
			'type'			 => true,
			'width'			 => true,
			'height'		 => true,
			'layout'         => true,
			'data-ad-client' => true,
			'data-ad-slot'   => true
		);

		return $tags;
	}

	public function get_sections() {
		$sections = array(
			'top'    => __( 'Ad Block #1', 'amphtml' ),
			'bottom' => __( 'Ad Block #2', 'amphtml' )
		);

		return apply_filters( 'amphtml_ad_tab_sections', $sections, $this );
	}

	public function get_fields() {
		$fields = array();
		foreach ( $this->get_sections() as  $section => $title ) {
			$section = (string) $section;
			$fields = array_merge( $fields, array(
				array(
					'id'                    => "ad_type_$section",
					'title'                 => __( 'Type', 'amphtml' ),
					'default'               => 'adsense',
					'section'               => $section,
					'display_callback'      => array( $this, 'display_ad_type' ),
					'display_callback_args' => array( "ad_type_$section" ),
					'description'           => __( 'Ad network', 'amphtml' ),
				),
				array(
					'id'                    => "ad_layout_$section",
					'title'                 => __( 'Layout', 'amphtml' ),
					'section'               => $section,
					'default'               => 'fixed',
					'display_callback'      => array( $this, 'display_select' ),
					'display_callback_args' => array(
						'id'             => "ad_layout_$section",
						'select_options' => array(
							'fixed'      => 'fixed',
							'responsive' => 'responsive'
						)
					),
				),
				array(
					'id'                    => "ad_height_$section",
					'title'                 => __( 'Height', 'amphtml' ),
					'section'               => $section,
					'default'               => '50',
					'display_callback'      => array( $this, 'display_text_field' ),
					'display_callback_args' => array( "ad_height_$section", true ),
					'sanitize_callback'     => array( $this, 'sanitize_ad_height' ),
					'description'           => __( 'Ad block height (in pixels)', 'amphtml' ),
				),
				array(
					'id'                    => "ad_width_$section",
					'title'                 => __( 'Width', 'amphtml' ),
					'section'               => $section,
					'default'               => '200',
					'display_callback'      => array( $this, 'display_text_field' ),
					'display_callback_args' => array( "ad_width_$section", true ),
					'sanitize_callback'     => array( $this, 'sanitize_ad_width' ),
					'description'           => __( 'Ad block width (in pixels)', 'amphtml' ),
				),
				array(
					'id'                    => "ad_doubleclick_data_slot_$section",
					'title'                 => __( 'Data Slot', 'amphtml' ),
					'section'               => $section,
					'default'               => '',
					'display_callback'      => array( $this, 'display_text_field' ),
					'display_callback_args' => array( "ad_doubleclick_data_slot_$section", true ),
					'sanitize_callback'     => array( $this, 'sanitize_ad_doubleclick_data_slot' ),
					'description'           => __( 'data-slot', 'amphtml' ),
				),
				array(
					'id'                    => "ad_data_id_client_$section",
					'title'                 => __( 'AdSense Client', 'amphtml' ),
					'section'               => $section,
					'default'               => '',
					'display_callback'      => array( $this, 'display_text_field' ),
					'display_callback_args' => array( "ad_data_id_client_$section", true ),
					'description'           => __( 'data-ad-client', 'amphtml' ),
				),
				array(
					'id'                    => "ad_adsense_data_slot_$section",
					'title'                 => __( 'Data Slot', 'amphtml' ),
					'section'               => $section,
					'default'               => '',
					'display_callback'      => array( $this, 'display_text_field' ),
					'display_callback_args' => array( "ad_adsense_data_slot_$section", true ),
					'sanitize_callback'     => array( $this, 'sanitize_ad_adsense_data_slot' ),
					'description'           => __( 'data-ad-slot', 'amphtml' ),
				),
				array(
					'id'                    => "ad_content_code_$section",
					'title'                 => __( 'Custom Ad', 'amphtml' ),
					'section'               => $section,
					'default'               => '',
					'display_callback'      => array( $this, 'display_textarea_field' ),
					'display_callback_args' => array( "ad_content_code_$section", true ),
					'sanitize_callback'     => array( $this, 'sanitize_custom_ad' ),
					'description'           => __( "Put Ad code in accordance with the " . '<a href="https://www.ampproject.org/docs/reference/components/amp-ad">Reference</a>', 'amphtml' ),
				),
			) );
		}

		return apply_filters( 'amphtml_ad_tab_fields', $fields, $this );
	}

	public function sanitize_digits( $key, $val, $message ) {
		$val = sanitize_text_field( $val );
		if ( strlen( $val ) === 0 ) {
			return '';
		}
		if ( 0 === preg_match( '/^[1-9][0-9]*$/', $val ) ) {
			add_settings_error( $this->options->get( $key, 'name' ), 'cw_error', $message, 'error' );
			$valid_val = $this->options->get( $key );
		} else {
			$valid_val = $val;
		}

		return $valid_val;
	}

	public function sanitize_ad_width( $width ) {
		$key = $this->get_attr_key( 'ad_width' );

		return $this->sanitize_digits( $key, $width, __( 'Insert a valid ad block width', 'amphtml' ) );
	}

	public function sanitize_ad_height( $height ) {
		$key = $this->get_attr_key( 'ad_height' );

		return $this->sanitize_digits( $key, $height, __( 'Insert a valid ad block height', 'amphtml' ) );
	}

	public function sanitize_ad_doubleclick_data_slot( $data_slot ) {
		return sanitize_text_field( $data_slot );
	}

	public function sanitize_ad_adsense_data_slot( $data_slot ) {
		$key = $this->get_attr_key( 'ad_adsense_data_slot' );

		return $this->sanitize_digits( $key, $data_slot, __( 'Insert a valid ad adsense data slot', 'amphtml' ) );
	}

	public function display_ad_type( $args ) {
		$id = current( $args );
		?>
		<label for="ad_type">
			<select style="width: 28%" id="<?php echo $id ?>" name="<?php echo $this->options->get( $id, 'name' ) ?>">
				<option value="adsense" <?php selected( $this->options->get( $id ), 'adsense' ) ?>>
					<?php _e( 'AdSense', 'amphtml' ); ?>
				</option>
				<option value="doubleclick" <?php selected( $this->options->get( $id ), 'doubleclick' ) ?>>
					<?php _e( 'Doubleclick', 'amphtml' ); ?>
				</option>
				<option value="other" <?php selected( $this->options->get( $id ), 'other' ) ?>>
					<?php _e( 'Other', 'amphtml' ); ?>
				</option>
			</select>
			<p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
		</label>
		<?php
	}

	public function display_textarea_field( $args ) {  // TODO: move to abstract class
		$id   = current( $args );
		$name = sprintf( "%s", $this->options->get( $id, 'name' ) );
		?>
		<textarea id="<?php echo $id ?>" name="<?php echo $name ?>" rows="6" cols="46"><?php echo trim( $this->options->get( $id ) ); ?></textarea>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php echo $this->options->get( $id, 'description' ) ?></p>
		<?php endif;
	}

	public function get_section_callback( $id ) {
		return array( $this, 'section_callback' );
	}

	public function section_callback( $page, $section ) {
		global $wp_settings_fields;

		$custom_fields = array(
			"ad_type_$section",
		);

		if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
			return;
		}
		$row_id = 0;
		foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
			$class = '';

			if ( ! method_exists( $field['callback'][0], $field['callback'][1] ) ) {
				continue;
			}

			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			}

			if ( in_array( $field['id'], $custom_fields ) ) {
				echo "<tr data-name='{$field['id']}' id='pos_{$row_id}' {$class}>";
			} else {
				echo "<tr{$class} style='display: none'>";
			}

			if ( ! empty( $field['args']['label_for'] ) ) {
				echo '<th scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></th>';
			} else {
				echo '<th scope="row">' . $field['title'] . '</th>';
			}

			echo '<td>';
			call_user_func( $field['callback'], $field['args'] );
			echo '</td>';
			echo '</tr>';
			$row_id ++;
		}
	}

	public function sanitize_custom_ad( $ad_content ) {
		$type_key = 'amphtml_ad_type_'. $_POST['section'];

		if ( 'other' !== $_POST[ $type_key ] ) {
			return '';
		}

		return trim( $ad_content );
	}

	public function get_attr_key( $slug ) {
		global $option;

		return str_replace( 'amphtml_', '', $option );
	}

}