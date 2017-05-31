<?php

class AMPHTML_Update {

	public $current_version;

	public function __construct() {
		$this->current_version = get_option( 'amphtml_version' );
		$this->update_template_options();
		$this->update_ad_fields();
		$this->update_7_2();

	}

	public function update_template_options() {
		$update_ver        = '6.6';
		$disabled_elements = array(
			'archive_title',
			'post_title',
			'post_content',
			'page_title',
			'page_content',
			'search_page_title',
			'archive_content_block',
			'blog_content_block',
			'search_page_content_block',
		);

		if ( $this->current_version < $update_ver ) {
			do_action( 'update_6_6' );
			foreach ( $disabled_elements as $element ) {
				update_option( 'amphtml_' . $element, 1 );
			}
		}
	}

	public function update_ad_fields() {
		$update_ver = '7.0';

		if ( $this->current_version < $update_ver ) {

			// Update styles
			$styles = array( 'style', 'rtl-style' );
			foreach ( $styles as $filename ) {
				$path = ABSPATH. "wp-content/plugins/wp-amp/css/$filename.min.css";
				if ( is_file( $path ) ) {
					unlink( $path );
				}
			}

			// Remove unused options
			$wrong_fields = array(
				'amphtml_ad_bottom_layout' => 'amphtml_ad_layout_bottom',
				'amphtml_ad_top_layout'    => 'amphtml_ad_layout_top',
				'amphtml_ad_bottom_height' => 'amphtml_ad_height_bottom',
				'amphtml_ad_top_height'    => 'amphtml_ad_height_top',
				'amphtml_ad_top_width'     => 'amphtml_ad_width_top',
				'amphtml_ad_bottom_width'  => 'amphtml_ad_width_bottom'
			);
			foreach ( $wrong_fields as $wrong_field_name => $correct_field_name ) {
				if ( $value = get_option( $wrong_field_name ) ) {
					add_option( $correct_field_name, $value );
					delete_option( $wrong_field_name );
				}
			}
		}
	}

	public function update_7_2() {
		$update_ver = '7.2';

		if ( $this->current_version < $update_ver ) {

			// Update styles
			$styles = array( 'style', 'rtl-style' );
			foreach ( $styles as $filename ) {
				$path = ABSPATH . "wp-content/plugins/wp-amp/css/$filename.min.css";
				if ( is_file( $path ) ) {
					unlink( $path );
				}
			}

		}
	}
}