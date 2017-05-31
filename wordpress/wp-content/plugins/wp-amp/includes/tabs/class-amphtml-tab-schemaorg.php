<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Schemaorg extends AMPHTML_Tab_Abstract {

	public function get_fields() {
		$fields = array(
			array(
				'id'               => 'default_logo',
				'title'            => __( 'Publisher Logo', 'amphtml' ),
				'display_callback' => array( $this, 'display_default_logo' ),
				'description'      => '',
			),
			array(
				'id'               => 'default_image',
				'title'            => __( 'Default Image', 'amphtml' ),
				'display_callback' => array( $this, 'display_default_image' ),
				'description'      => '',
			),
			array(
				'id'               => 'schema_type',
				'title'            => __( 'Content Type', 'amphtml' ),
				'display_callback' => array( $this, 'display_select' ),
				'default'          => 'NewsArticle',
				'display_callback_args' => array(
					'id'             => 'schema_type',
					'select_options' => array(
						'NewsArticle'   => 'NewsArticle',
						'BlogPosting'   => 'BlogPosting'
					)
				),
				'description'      => '',
			),
		);

		return apply_filters( 'amphtml_schemaorg_tab_fields', $fields, $this );
	}

	public function display_default_logo() {
		$img_url = $this->get_img_url_by_option( 'default_logo' );
		?>
		<label for="upload_image">
			<p class="logo_preview" <?php if ( ! $img_url ): echo 'style="display:none"'; endif; ?>>
				<img src="<?php echo esc_url( $img_url ); ?>"
				     alt="<?php _e( 'Default Logo', 'amphtml' ) ?>"
				     style="width: auto; height: 100px">
			</p>
			<input class="upload_image" type="hidden" name="<?php echo $this->options->get( 'default_logo', 'name' ) ?>"
			       value="<?php echo esc_url( $img_url ); ?>"/>
			<input class="button upload_image_button" type="button" value="<?php echo __( 'Upload Image', 'amphtml' ); ?>"/>
			<input <?php if ( ! $this->options->get( 'default_logo' ) ): echo 'style="display:none"'; endif; ?>
				class="button reset_image_button" type="button" value="<?php echo __( 'Reset Image', 'amphtml' ); ?>"/>
			<p><?php _e( 'This image is required for Schema.org markup. The logo should fit in a 60x600px rectangle, and either be exactly 60px high or exactly 600px wide.', 'amphtml' ) ?> <a href="https://developers.google.com/search/docs/data-types/articles#logo-guidelines" target="_blank"><?php _e( 'See full requirements.', 'amphtml' ) ?></a></p>
		</label>
		<?php
	}

	public function display_default_image() {
		$img_url = $this->get_img_url_by_option( 'default_image' );
		?>
		<label for="upload_image">
			<p class="logo_preview" <?php if ( ! $img_url ): echo 'style="display:none"'; endif; ?>>
				<img src="<?php echo esc_url( $img_url ); ?>"
				     alt="<?php _e( 'Default Image', 'amphtml' ) ?>"
				     style="width: auto; height: 100px">
			</p>
			<input class="upload_image" type="hidden"
			       name="<?php echo $this->options->get( 'default_image', 'name' ) ?>"
			       value="<?php echo esc_url( $img_url ); ?>"/>
			<input class="button upload_image_button" type="button" value="<?php echo __( 'Upload Image', 'amphtml' ); ?>"/>
			<input <?php if ( ! $this->options->get( 'default_image' ) ): echo 'style="display:none"'; endif; ?>
				class="button reset_image_button" type="button" value="<?php echo __( 'Reset Image', 'amphtml' ); ?>"/>
			<p><?php _e( 'This image is required for Schema.org markup and will be used if you have not set up featured image for post/page. Image should be at least 696 pixels wide.', 'amphtml' ) ?> <a href="https://developers.google.com/search/docs/data-types/articles#article_types" target="_blank"><?php _e( 'See requirements.', 'amphtml' ) ?></a></p>
		</label>
		<?php
	}

}