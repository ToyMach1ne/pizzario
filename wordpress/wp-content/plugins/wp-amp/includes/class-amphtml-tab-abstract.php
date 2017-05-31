<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

abstract class AMPHTML_Tab_Abstract {

	const ORDER_OPT = 'amphtml_template_blocks_order';

	protected $tab_name;

	protected $tab_fields;

	protected $is_current;

	protected $current_section;

	/**
	 * @var AMPHTML_Options
	 */
	protected $options;

	public function __construct( $name, $options, $is_current = false ) {
		$this->tab_name   = $name;
		$this->is_current = $is_current;
		$this->options    = $options;

		$this->current_section = $this->options->get_request_var(
			'section', current( array_keys( $this->get_sections() ) )
		);

		$this->tab_fields = $this->get_fields();

		AMPHTML_Options::add_fields( $this->tab_fields );
		add_action( 'admin_init', array ( $this, 'display_fields' ) );
		do_action( 'amphtml_init_tab', $this );

		// save options order
		if ( $this->is_ajax() ) {
			$this->save_order();
		}
	}

	abstract public function get_fields();

	public function get_name() {
		return $this->tab_name;
	}

	public function is_ajax() {
		return 'amphtml_options' == $this->options->get_request_var( 'action' );
	}

	public function set_current() {
		$this->is_current = true;

		return $this;
	}

	public function set_current_section( $section ) {
		$this->current_section = $section;

		return $this;
	}

	public function get_sections() {
		return array (
			'default' => ''
		);
	}

	public function get_section_name( $section_id ) {
		$sections = $this->get_sections();

		return isset( $sections[ $section_id ] ) ? $sections[ $section_id ] : '';
	}

	public function get_current_section() {
		return $this->current_section;
	}

	public function display_text_field( $args, $type = 'text' ) {
		$id       = current( $args );
		$required = ( isset( $args[1] ) ) ? $args[1] : false;
		?>
		<p>
		<input style="width: 28%" type="<?php echo $type; ?>" name="<?php echo $this->options->get( $id, 'name' ) ?>"
		       id="<?php echo $id ?>"
		       value="<?php echo esc_attr( $this->options->get( $id ) ); ?>"
			<?php echo ( $this->options->get( $id, 'placeholder' ) ) ? 'placeholder="' . $this->options->get( $id, 'placeholder' ) . '"' : '' ?>
			<?php if ( $required ): echo 'required'; endif; ?>
		/>
		</p>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
		<?php endif; ?>
		<?php
	}
	
	public function display_color_field( $args ) {
		extract( $args );
		if( !isset( $id ) ){
			return _e( 'ID is required settings option!', 'amphtml' );
		}
		?>
		<p>
			<input style="width: 28%" type="text" name="<?php echo $this->options->get( $id, 'name' ) ?>"
				   id="<?php echo $id; ?>"
				   class="amphtml-colorpicker"
				   value="<?php echo esc_attr( $this->options->get( $id ) ); ?>" />
		</p>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
		<?php endif; ?>
		<?php
	}

	public function display_checkbox_field( $args ) {
		$id       = current( $args );
		$disabled = ( array_search( 'disabled', $args ) ) ? 'onclick="return false;"' : '';
		$checked  = ( array_search( 'checked', $args ) ) ? 'checked' : checked( 1, $this->options->get( $id ), false );
		?>
		<p>
			<input type="checkbox" name="<?php echo $this->options->get( $id, 'name' ) ?>"
				   value="1" <?php echo $checked ?> <?php echo $disabled ?> />
			<?php if ( $this->options->get( $id, 'description' ) ): ?>
				<?php echo $this->options->get( $id, 'description' ) ?>
			<?php endif; ?>
		</p>
		<?php
	}

	public function display_multiple_select( $args ) {
		$id             = $args['id'];
		$name           = sprintf( "%s[]", $this->options->get( $id, 'name' ) );
		$current_values = ( $this->options->get( $id ) ) ? $this->options->get( $id ) : array ();
		?>
		<label for="<?php echo $id ?>">
			<select style="width: 30%" id="<?php echo $id ?>" name="<?php echo $name ?>" multiple size="5">
				<?php ?>
				<?php foreach ( $args['select_options'] as $option_value => $option_title ): ?>
					<option
						value="<?php echo $option_value ?>" <?php selected( in_array( $option_value, $current_values ) ) ?>>
						<?php echo $option_title ?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php echo $this->options->get( $id, 'description' ) ?></p>
		<?php endif; ?>
		<?php
	}

	public function display_select( $args ) {
		$id             = $args['id'];
		$name           = sprintf( "%s", $this->options->get( $id, 'name' ) );
		?>
		<label for="<?php echo $id ?>">
			<select style="width: 28%" id="<?php echo $id ?>" name="<?php echo $name ?>">
				<?php foreach( $args['select_options'] as $value => $title ): ?>
					<option value="<?php echo $value ?>" <?php selected( $this->options->get( $id ), $value ) ?>>
						<?php printf( __( '%s', 'amphtml' ), $title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
		<?php
	}


	public function get_section_fields( $id ) {
		$fields = array ();
		foreach ( $this->tab_fields as $field ) {
			if ( ! isset( $field['section'] ) ) {
				$field['section'] = 'default';

			}
			if ( $field['section'] !== $id || false === method_exists( $this, $field['display_callback'][1] ) ) {
				continue;
			}
			$fields[] = $field;
		}

		return $fields;
	}

	public function display_fields() {
		if ( false === $this->is_current() ) {
			return '';
		}
		$id    = $this->get_current_section();
		$title = $this->get_section_name( $id );

		add_settings_section( $id, $title, $this->get_section_callback( $id ), AMPHTML_Options::OPTIONS_PAGE );

		foreach ( $this->get_section_fields( $id ) as $field ) {
			$display_callback_args = isset( $field['display_callback_args'] ) ? $field['display_callback_args'] : array ();
			$sanitize_callback     = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';

			add_settings_field( $field['id'], $field['title'], $field['display_callback'],
				AMPHTML_Options::OPTIONS_PAGE, $id, $display_callback_args
			);
			$this->current_option_id = $field['id'];
			register_setting( AMPHTML_Options::OPTIONS_PAGE, $this->options->get( $field['id'], 'name' ), $sanitize_callback );
		}
	}

	public function is_current() {
		return $this->is_current;
	}

	function search_field_id( $id ) {
		foreach ( $this->tab_fields as $key => $val ) {
			if ( $val['id'] === $id ) {
				return $this->tab_fields[ $key ];
			}
		}

		return null;
	}

	public function save_order() {
		$blocks_order = array ();
		$section      = $_REQUEST['current_section'];
		if ( get_option( self::ORDER_OPT ) ) {
			$blocks_order = maybe_unserialize( get_option( self::ORDER_OPT ) );
		}
		$blocks_order[ $section ] = $_REQUEST['positions'];
		update_option( self::ORDER_OPT, maybe_serialize( $blocks_order ) );
		exit();
	}

	public function get_section_callback( $id ) {
		return null;
	}

	/**
	 * Update fieldset options
	 * 
	 * @param array $ids
	 */
	public function update_fieldset( $ids ) {
		foreach ( $ids as $id ) {
			$option = $this->options->get( $id, 'name' );
			$value  = isset( $_POST[ $option ] ) ? sanitize_text_field( $_POST[ $option ] ) : 0;
			update_option( $option, $value );
		}
	}

	public function get_submit() {
		?>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary"
			       value="<?php echo __( 'Save Changes', 'amphtml' ); ?>">
			<?php do_action( 'get_tab_submit_button', $this ); ?>
		</p>
		<?php
	}
	
	/**
	 * For compatibility with older versions
	 * @param string $option
	 * @return string
	 */
	public function get_img_url_by_option( $option ) {
		$logo = $this->options->get( $option );
		if( $img_obj = json_decode($logo) ) {
			$logo_url = $img_obj->url;
		} else {
			$logo_url = $logo;
		}
		return $logo_url;
	}

}