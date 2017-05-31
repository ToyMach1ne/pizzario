<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Appearance extends AMPHTML_Tab_Abstract {

	public function __construct($name, $options, $is_current = false) {
		parent::__construct($name, $options, $is_current);
		add_action( 'amphtml_proceed_settings_form', array( $this, 'remove_outdated_min_css' ) );

	}


	public function get_fields() {
		return array_merge(
			$this->get_color_fields( 'colors' ),
			$this->get_font_fields( 'fonts' ),
			$this->get_header_fields( 'header' ),
			$this->get_footer_fields( 'footer' ),
			$this->get_post_meta_data_fields( 'post_meta_data' ),
			$this->get_social_share_buttons_fields( 'social_share_buttons' ),
			$this->get_extra_css_fields( 'extra_css' )
		);
	}

	public function get_sections() {
		return array(
			'colors'                => __( 'Colors', 'amphtml' ),
			'fonts'                 => __( 'Fonts', 'amphtml' ),
			'header'                => __( 'Header', 'amphtml' ),
			'footer'                => __( 'Footer', 'amphtml' ),
			'post_meta_data'        => __( 'Post Meta Data', 'amphtml' ),
			'social_share_buttons'  => __( 'Social Share Buttons', 'amphtml' ),
			'extra_css'             => __( 'Extra CSS', 'amphtml' )
		);
	}

	public function get_font_fields( $section ) {
		return array(
			array(
				'id'                    => 'logo_font',
				'title'                 => __( 'Logo', 'amphtml' ),
				'default'               => 'sans-serif',
				'description'           => '',
				'display_callback'      => array( $this, 'display_font_select' ),
				'display_callback_args' => array( 'logo_font' ),
				'section'               => $section
			),
			array(
				'id'                    => 'menu_font',
				'title'                 => __( 'Menu', 'amphtml' ),
				'default'               => 'sans-serif',
				'description'           => '',
				'display_callback'      => array( $this, 'display_font_select' ),
				'display_callback_args' => array( 'menu_font' ),
				'section'               => $section
			),
			array(
				'id'                    => 'title_font',
				'title'                 => __( 'Title', 'amphtml' ),
				'default'               => 'sans-serif',
				'description'           => '',
				'display_callback'      => array( $this, 'display_font_select' ),
				'display_callback_args' => array( 'title_font' ),
				'section'               => $section
			),
			array(
				'id'                    => 'post_meta_font',
				'title'                 => __( 'Post Meta', 'amphtml' ),
				'default'               => 'sans-serif',
				'description'           => '',
				'display_callback'      => array( $this, 'display_font_select' ),
				'display_callback_args' => array( 'post_meta_font' ),
				'section'               => $section
			),
			array(
				'id'                    => 'content_font',
				'title'                 => __( 'Content', 'amphtml' ),
				'default'               => 'sans-serif',
				'description'           => '',
				'display_callback'      => array( $this, 'display_font_select' ),
				'display_callback_args' => array( 'content_font' ),
				'section'               => $section
			),
			array(
				'id'                    => 'footer_font',
				'title'                 => __( 'Footer', 'amphtml' ),
				'default'               => 'sans-serif',
				'description'           => '',
				'display_callback'      => array( $this, 'display_font_select' ),
				'display_callback_args' => array( 'footer_font' ),
				'section'               => $section
			),
		);
	}

	public function get_color_fields( $section ) {
		$fields = array(
			array(
				'id'                    => 'header_color',
				'title'                 => __( 'Header Background', 'amphtml' ),
				'default'               => '#0087be',
				'display_callback_args' => array( 'id' => 'header_color' ),
			),
			array(
				'id'                    => 'footer_color',
				'title'                 => __( 'Footer Background', 'amphtml' ),
				'default'               => '#0087be',
				'display_callback_args' => array( 'id' => 'footer_color' ),
			),
			array(
				'id'                    => 'background_color',
				'title'                 => __( 'Page Background', 'amphtml' ),
				'default'               => '#FFFFFF',
				'display_callback_args' => array( 'id' => 'background_color' ),
			),
			array(
				'id'                    => 'sidebar_menu_color',
				'title'                 => __( 'Sidebar Menu Background', 'amphtml' ),
				'default'               => '#2e4453',
				'display_callback_args' => array( 'id' => 'sidebar_menu_color' ),
			),
			array(
				'id'                    => 'main_title_color',
				'title'                 => __( 'Main Title', 'amphtml' ),
				'default'               => '#2e4453',
				'display_callback_args' => array( 'id' => 'main_title_color' ),
			),
			array(
				'id'                    => 'link_color',
				'title'                 => __( 'Link Text', 'amphtml' ),
				'default'               => '#0087be',
				'display_callback_args' => array( 'id' => 'link_color' ),
			),
			array(
				'id'                    => 'main_text_color',
				'title'                 => __( 'Main Text', 'amphtml'),
				'default'               => '#3d596d',
				'display_callback_args' => array( 'id' => 'main_text_color' ),
			),
			array(
				'id'                    => 'header_text_color',
				'title'                 => __( 'Header Text', 'amphtml' ),
				'default'               => '#FFFFFF',
				'display_callback_args' => array( 'id' => 'header_text_color' ),
			),
			array(
				'id'                    => 'footer_text_color',
				'title'                 => __( 'Footer Text', 'amphtml' ),
				'default'               => '#FFFFFF',
				'display_callback_args' => array( 'id' => 'footer_text_color' ),
			),
		);
		
		// set common options
		foreach ($fields as &$field) {
			$field['display_callback'] = array( $this, 'display_color_field' );
			$field['sanitize_callback'] = array( $this, 'sanitize_color' );
			$field['section'] = $section;
		}
		
		$fields = apply_filters( 'amphtml_color_fields', $fields, $this, $section );
		return $fields;
	}

	public function get_header_fields( $section ) {
		return array(
			array(
				'id'                    => 'favicon',
				'title'                 => __( 'Favicon', 'amphtml' ),
				'description'           => '',
				'display_callback'      => array( $this, 'display_favicon' ),
				'section'               => $section
			),
			array(
				'id'                    => 'header_menu',
				'title'                 => __( 'Header Menu', 'amphtml' ),
				'default'               => 1,
				'description'           => __('Show header menu (<a href="' . add_query_arg( array( 'action' => 'locations' ), admin_url( 'nav-menus.php' ) ) . '" target="_blank">set AMP menu</a>)', 'amphtml' ),
				'display_callback'      => array( $this, 'display_checkbox_field' ),
                'display_callback_args' => array( 'header_menu' ),
				'section'               => $section
			),
			array(
				'id'                    => 'header_menu_type',
				'title'                 => __( 'Header Menu Type', 'amphtml' ),
				'default'               => 'accordion',
				'description'           => '',
				'display_callback'      => array( $this, 'display_header_menu_type' ),
				'section'               => $section
			),
            array(
                'id'                    => 'header_menu_button',
                'title'                 => __( 'Header Menu Button', 'amphtml' ),
                'default'               => 'text',
                'description'           => '',
                'display_callback'      => array( $this, 'display_header_menu_button' ),
                'section'               => $section
            ),
			array(
				'id'                    => 'logo_opt',
				'title'                 => __( 'Logo Type', 'amphtml' ),
				'default'               => 'text_logo',
				'description'           => '',
				'display_callback'      => array( $this, 'display_logo_opt' ),
				'section'               => $section
			),
			array(
				'id'                    => 'logo_text',
				'title'                 => __( 'Logo Text', 'amphtml'),
				'default'               => get_bloginfo( 'name' ),
				'display_callback'      => array( $this, 'display_text_field' ),
				'display_callback_args' => array( 'logo_text' ),
				'description'           => '',
				'section'               => $section
			),
			array(
				'id'                    => 'logo',
				'title'                 => __( 'Logo Icon', 'amphtml' ),
				'description'           => '',
				'display_callback'      => array( $this, 'display_logo' ),
				'section'               => $section
			),
		);
	}

	public function get_footer_fields( $section ) {
		return array(
			array(
				'id'                => 'footer_content',
				'title'             => __( 'Footer Content', 'amphtml'),
				'default'           => '',
				'section'           => $section,
				'display_callback'  => array( $this, 'display_footer_content' ),
				'sanitize_callback' => array( $this, 'sanitize_footer_content' ),
				'description'       => __( 'This is the footer content block for all AMP pages. <br>'
				                 . 'Leave empty to hide footer at all. <br>'
				                 . 'Plain html without inline styles allowed. '
				                 . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' ),
			),
			array(
				'id'                => 'footer_scrolltop',
				'title'             => __( 'Scroll to Top Button', 'amphtml'),
				'default'           => __('Back to top', 'amphtml'),
				'section'           => $section,
				'display_callback'  => array( $this, 'display_text_field' ),
				'display_callback_args'  => array( 'footer_scrolltop' ),
				'description'       => __( 'Leave empty to hide this button.', 'amphtml' ),
			),
		);
	}

	public function get_post_meta_data_fields( $section ) {
		return array(
			array(
				'id'                    => 'post_meta_author',
				'title'                 => __( 'Author', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_meta_author' ),
				'section'               => $section,
				'description'           => __( 'Show post author', 'amphtml' ),
			),
			array(
				'id'                    => 'post_meta_categories',
				'title'                 => __( 'Categories', 'amphtml'),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_meta_categories' ),
				'section'               => $section,
				'description'           => __( 'Show post categories', 'amphtml' ),
			),
			array(
				'id'                    => 'post_meta_tags',
				'title'                 => __( 'Tags', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_meta_tags' ),
				'section'               => $section,
				'description'           => __( 'Show post tags', 'amphtml' ),
			),
			array(
				'id'                    => 'post_meta_date_format',
				'title'                 => __( 'Date Format', 'amphtml'),
				'default'               => 'default',
				'display_callback'      => array( $this, 'display_date_format' ),
				'section'               => $section,
				'description'           => '(<a href="https://codex.wordpress.org/Formatting_Date_and_Time#Examples">Examples of Date Format</a>)',
			),
			array(
				'id'                    => 'post_meta_date_format_custom',
				'title'                 => '',
				'display_callback'      => array( $this, 'display_date_format_custom' ),
				'section'               => $section,
				'description'           => '',
			),
		);
	}

	public function get_social_share_buttons_fields( $section ) {
		return array(
			array(
				'id'                    => 'social_share_buttons',
				'title'                 => __( 'Social Share Buttons', 'amphtml' ),
				'default'               => array( 'facebook', 'twitter', 'linkedin', 'gplus' ),
				'display_callback'      => array( $this, 'display_multiple_select' ),
				'display_callback_args' => array(
					'id' => 'social_share_buttons',
					'select_options' => array(
						'facebook'  => __( 'Facebook', 'amphtml' ),
						'twitter'   => __( 'Twitter', 'amphtml' ),
						'linkedin'  => __( 'LinkedIn', 'amphtml' ),
						'gplus'     => __( 'Google Plus', 'amphtml' ),
						'pinterest' => __( 'Pinterest', 'amphtml' ),
						'whatsapp'  => __( 'WhatsApp', 'amphtml' ),
						'email'     => __( 'Email', 'amphtml' ),
					)
				),
				'section'               => $section,
			),
		);
	}
	
	public function get_extra_css_fields( $section ) {
		return array (
			array (
				'id'                    => 'extra_css_amp',
				'placeholder'           => __( 'Enter Your CSS Code', 'amphtml' ),
				'title'                 => __( 'Extra CSS', 'amphtml' ),
				'display_callback'      => array ( $this, 'display_textarea_field' ),
				'display_callback_args' => array ( 'extra_css_amp' ),
				'description'           => '',
				'section'               => $section
			)
		);
	}

	public function display_date_format_custom() {
		return '';
	}

	/*
	 * Color Section
	 */
	public function sanitize_color( $color, $id = 'empty' ) {
		// Validate Color
		$background = trim( $color );
		$background = strip_tags( stripslashes( $background ) );

		// Check if is a valid hex color
		if ( false === $this->options->check_header_color( $background ) ) {
			add_settings_error( $this->options->get( $id, 'name' ), 'hc_error', __( 'Insert a valid color', 'amphtml' ), 'error' );
			$valid_field = $this->options->get( $id );
		} else {
			$valid_field = $background;
		}

		return $valid_field;
	}
	
	/*
	 *  Font Section
	 */
	public function get_fonts_list() {
		return array(
			'sans-serif',
			'Work Sans',
			'Alegreya',
			'Fira Sans',
			'Lora',
			'Merriweather',
			'Montserrat',
			'Open Sans',
			'Playfair Display',
			'Roboto',
			'Lato',
			'Cardo',
			'Arvo',
		);
	}

	public function display_font_select( $args ) {
		$id = current( $args );
		?>
		<label for="<?php echo $id ?>">
			<select style="width: 28%" id="<?php echo $id ?>" name="<?php echo $this->options->get( $id, 'name' ) ?>">
				<?php foreach( $this->get_fonts_list() as $title ): ?>
					<?php $name = str_replace(' ', '+', $title )  ?>
					<option value="<?php echo $name ?>" <?php selected( $this->options->get( $id ), $name ) ?>>
						<?php printf( __( '%s', 'amphtml' ), $title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
		<?php
	}

	/*
	 *  Header Section
	 */
	public function display_header_menu_type() {
		$select_args = array(
			'id' => 'header_menu_type',
			'select_options' => array(
				'accordion' => 'Accordion',
				'sidebar' => 'Sidebar',
			)
		);
		$this->display_select( $select_args );
	}

    public function display_header_menu_button() {
        $select_args = array(
            'id' => 'header_menu_button',
            'select_options' => array(
                'text' => 'Text',
                'icon' => 'Icon',
            )
        );
        $this->display_select( $select_args );
    }
	public function display_logo_opt() {
		?>
		<select style="width: 28%" id="logo_opt" name="<?php echo $this->options->get( 'logo_opt', 'name' ) ?>">
			<option value="icon_logo" <?php selected( $this->options->get( 'logo_opt' ), 'icon_logo' ) ?>>
				<?php _e( 'Icon Logo', 'amphtml' ); ?>
			</option>
			<option value="text_logo" <?php selected( $this->options->get( 'logo_opt' ), 'text_logo' ) ?>>
				<?php _e( 'Text Logo', 'amphtml' ); ?>
			</option>
			<option value="icon_an_text" <?php selected( $this->options->get('logo_opt'), 'icon_an_text' ) ?>>
				<?php _e( 'Icon and Text Logo', 'amphtml' ); ?>
			</option>
			<option value="image_logo" <?php selected( $this->options->get('logo_opt'), 'image_logo' ) ?>>
			    <?php _e( 'Image Logo', 'amphtml' ); ?>
			</option>
		</select>
		<?php
	}

	public function display_logo() {
		$logo_url = $this->get_img_url_by_option( 'logo' );
		?>
		<label for="upload_image">
			<p class="logo_preview hide_preview" <?php if ( ! $logo_url ): echo 'style="display:none"'; endif; ?>>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php _e( 'Site Logo', 'amphtml' ) ?>"
				     style="width: auto; height: 100px">
			</p>
			<input class="upload_image" type="hidden" name="<?php echo $this->options->get( 'logo', 'name' ) ?>"
			       value="<?php echo esc_url( $logo_url ); ?>"/>
			<input class="upload_image_button button" type="button" value="<?php _e( 'Upload Image', 'amphtml' ) ?>"/>
			<input <?php if ( ! $logo_url ): echo 'style="display:none"'; endif; ?>
				class="reset_image_button button" type="button" value="<?php _e( 'Reset Image', 'amphtml' ) ?>"/>
			<p class="img_text_size_full" style="display:none" ><?php _e( 'The image will have full size.', 'amphtml' ) ?></p>
			<p class="img_text_size img_text_size_logo" ><?php _e( 'The image will be resized to fit in a 32x32 box (but not cropped)', 'amphtml' ) ?></p>
		</label>
		<?php
	}
	
	public function display_favicon() {
		$logo_url = $this->get_img_url_by_option( 'favicon' );
		?>
		<label for="upload_image">
			<p class="logo_preview" <?php if ( ! $logo_url ): echo 'style="display:none"'; endif; ?>>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php _e( 'Site Favicon', 'amphtml' ) ?>"
				     style="width: auto; height: 100px">
			</p>
			<input class="upload_image" type="hidden" name="<?php echo $this->options->get( 'favicon', 'name' ) ?>"
			       value="<?php echo esc_url( $logo_url ); ?>"/>
			<input class="upload_image_button button" type="button" value="<?php _e( 'Upload Image', 'amphtml' ) ?>"/>
			<input <?php if ( ! $logo_url ): echo 'style="display:none"'; endif; ?>
				class="reset_image_button button" type="button" value="<?php _e( 'Reset Image', 'amphtml' ) ?>"/>
			<p class="img_text_size_full_favicon"><?php _e( 'The image will have full size.', 'amphtml' ) ?></p>
		</label>
		<?php
	}
	
	/*
	 * Footer Section
	 */
	public function sanitize_footer_content( $footer_content ) {
		$tags = wp_kses_allowed_html( 'post' );
		$tags['form']['action-xhr'] = true;

		$not_allowed = array(
			'font',
			'form',
			'menu',
			'nav'
		);

		foreach ( $tags as $key => $attr ) {
			if ( in_array( $key, $not_allowed ) ) {
				unset( $tags[ $key ] );
			}
		}
		
		$tags = apply_filters('wpamp_content_tags', $tags );
		return wp_kses( $footer_content, $tags );
	}

	public function display_footer_content() {
		?>
		<textarea name="<?php echo $this->options->get( 'footer_content', 'name' ) ?>" rows="6" cols="60"><?php echo trim( $this->options->get( 'footer_content' ) ); ?></textarea>
		<?php if ( $this->options->get( 'footer_content', 'description' ) ): ?>
			<p class="description"><?php _e( $this->options->get( 'footer_content', 'description' ), 'amphtml' ) ?></p>
		<?php endif;
	}

	public function display_date_format() {
		?>
		<fieldset>
		<?php

		$custom = true;

		echo "\t<label><input type='radio' name='". $this->options->get( 'post_meta_date_format', 'name' ). "' value='none'";
		if ( 'none' === $this->options->get( 'post_meta_date_format' ) ) {
			echo " checked='checked'";
			$custom = false;
		}
		echo ' /></span> ' . __('None', 'amphtml' ) . "</label><br />\n";


		echo "\t<label><input type='radio' name='". $this->options->get( 'post_meta_date_format', 'name' ). "' value='relative'";
		if ( 'relative' === $this->options->get( 'post_meta_date_format' ) ) {
			echo " checked='checked'";
			$custom = false;
		}

		echo ' /> <span class="date-time-text format-i18n">'
		. esc_html( sprintf( _x( '%s ago', '%s = human-readable time difference', 'amphtml' ), human_time_diff( get_the_date( ) )) )
		. '</span> (' . __('Relative', 'amphtml') . ")</label><br />\n";


		echo "\t<label><input type='radio' name='". $this->options->get( 'post_meta_date_format', 'name' ). "' value='default'";
		if ( 'default' === $this->options->get( 'post_meta_date_format' ) ) {
			echo " checked='checked'";
			$custom = false;
		}
		echo ' /> <span class="date-time-text format-i18n">' . date_i18n( get_option('date_format') ) . '</span> (' . __('Default system format', 'amphtml' ) . ")</label><br />\n";

		$custom_value = strlen( get_option( 'amphtml_post_meta_date_format_custom' ) ) ? get_option( 'amphtml_post_meta_date_format_custom' ) : __( 'F j, Y', 'amphtml' );

		echo '<label><input type="radio" name="'. $this->options->get( 'post_meta_date_format', 'name' ) .'" id="date_format_custom_radio" value="custom"';
		checked( $custom );
		echo '/> <span class="date-time-text date-time-custom-text">' . __( 'Custom:', 'amphtml' ) . '</label>' .
			'<input type="text" name="amphtml_post_meta_date_format_custom" id="amphtml_date_format_custom" value="' . esc_attr( $custom_value ) . '" style="width:60px" /></span>' .
			'<span class="example">' . date_i18n( $custom_value ) . '</span>' .
			"<span class='spinner'></span>\n";
		?>
		<span class="description"><?php _e( $this->options->get( 'post_meta_date_format', 'description' ), 'amphtml' ) ?></span>
		</fieldset>
		<?php
	}

	public function get_submit() { //todo replace with action
		?>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __( 'Save Changes', 'amphtml' ); ?>">
			<?php if ( 'colors' == $this->get_current_section() ): ?>
					<input type="submit" name="reset" id="reset" class="button"
					value="<?php echo __( 'Default theme settings', 'amphtml' ); ?>" style="margin-left: 10px;" >
			<?php endif; ?>
		</p>
		<?php
	}
	
	public function display_textarea_field( $args ) {
		$id = current( $args );
		?>
		<textarea name="<?php echo $this->options->get( 'extra_css_amp', 'name' ) ?>" id="amp_css_entry"
		          style="width:100%;height:300px;"
			<?php echo ( $this->options->get( $id, 'placeholder' ) ) ? 'placeholder="' . $this->options->get( $id, 'placeholder' ) . '"' : '' ?>><?php
			echo esc_attr( $this->options->get( 'extra_css_amp' ) ); ?></textarea>
			<p class="description">
				<strong><?php _e( 'Important', 'amphtml' ); ?>: </strong><span><?php _e( 'Do not use disallowed styles for avoiding AMP validation errors.', 'amphtml' ); ?>
					<?php _e( 'Please see', 'amphtml' ); ?>: <a href="https://www.ampproject.org/docs/guides/responsive/style_pages" target="_blank">
						<?php _e( 'Supported CSS', 'amphtml' ); ?></a>.</span>
			</p>
		<?php
	}

	public function remove_outdated_min_css( $options ) {
		if ( isset( $_REQUEST['settings-updated'] ) && 'true'  == $_REQUEST['settings-updated']  && $this->is_current() ) {
			$styles = array( 'style', 'rtl-style' );
			$template = new AMPHTML_Template( $options );
			foreach ( $styles as $filename ) {
				if ( $path = $template->get_minify_style_path( $filename ) ) {
					unlink( $path );
				}
				$template->generate_minified_css_file( $filename );
			}
		}
	}

	public function get_section_callback( $id ) {
		return array( $this, 'section_callback' );
	}

	public function section_callback( $page, $section ) {
		global $wp_settings_fields;

		$custom_fields = array(
			'logo_text',
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
				echo "<tr{$class} style='display: none'>";
			} else {
				echo "<tr data-name='{$field['id']}' id='pos_{$row_id}' {$class}>";
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

}