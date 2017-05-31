<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Wc extends AMPHTML_Tab_Abstract {
	
	public function get_sections() {
		return array(
			'product'     => __( 'Product Page', 'amphtml' ),
			'shop'        => __( 'Shop Page', 'amphtml' ),
			'wc_archives' => __(  'Product Archives', 'amphtml' ),
			'add_to_cart' => __( 'Add to Cart', 'amphtml' ),
		);
	}

	public function get_fields() {
		return array_merge(
			$this->get_add_to_cart_fields( 'add_to_cart' ),
			$this->get_product_fields( 'product' ),
			$this->get_shop_fields( 'shop' ),
			$this->get_archives_fields( 'wc_archives' )
		);
	}

	public function get_product_fields( $section ) {
		$fields = array(
			array(
				'id'                    => 'product_breadcrumbs',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_breadcrumbs' ),
				'template_name'         => 'breadcrumb',
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			// Block original button
			array(
				'id'                    => 'product_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_product_original_btn_block' ),
				'display_callback_args' => array( 'product_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_product_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the product', 'amphtml' )
			),
			array(
				'id'                    => 'product_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			array(
				'id'                    => 'product_image',
				'title'                 => __( 'Image', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_image' ),
				'section'               => $section,
				'description'           => __( 'Show product image', 'amphtml' ),
			),
			array(
				'id'                    => 'product_title',
				'title'                 => __( 'Title', 'amphtml'),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_title' ),
				'section'               => $section,
				'description'           => 'Show product title',
			),
			array(
				'id'                    => 'product_sku',
				'title'                 => __( 'SKU', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_sku' ),
				'section'               => $section,
				'description'           => __( 'Show product SKU', 'amphtml' ),
			),
			array(
				'id'                    => 'product_rating',
				'title'                 => __( 'Rating', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_rating' ),
				'section'               => $section,
				'description'           => __( 'Show product rating', 'amphtml' ),
			),
			array(
				'id'                    => 'product_add_to_cart_block',
				'title'                 => __( 'Add To Cart Block', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_add_to_cart_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_add_to_cart_block' ),
				'section'               => $section,
				'description'           => '',
			),
			array(
				'id'                    => 'product_price',
				'title'                 => __( 'Price', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_price', 'disabled', 'checked' ),
				'section'               => $section,
				'description'           => __( 'Show product price', 'amphtml' ),
			),
			array(
				'id'                    => 'product_stock_status',
				'title'                 => __( 'Stock Status', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_stock_status' ),
				'section'               => $section,
				'description'           => __( 'Show product stock status', 'amphtml' ),
			),
			array(
				'id'                    => 'product_qty',
				'title'                 => __( 'Quantity', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_qty' ),
				'section'               => $section,
				'description'           => __( 'Show quantity option. Needs SSL certificate for AMP validation.', 'amphtml' ),
			),
			array(
				'id'                    => 'product_options',
				'title'                 => __( 'Options', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_options' ),
				'section'               => $section,
				'description'           => __( 'Show variable options. Needs SSL certificate for AMP validation.', 'amphtml' ),
			),
			array(
				'id'                    => 'product_add_to',
				'title'                 => __( 'Add To Cart', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_add_to', 'disabled', 'checked' ),
				'section'               => $section,
				'description'           => __( 'Show add to cart button', 'amphtml' ),
			),
			array(
				'id'                    => 'product_categories',
				'title'                 => __( 'Categories', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_categories' ),
				'section'               => $section,
				'description'           => __( 'Show product categories', 'amphtml' ),
			),
			array(
				'id'                    => 'product_tags',
				'title'                 => __( 'Tags', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_tags' ),
				'section'               => $section,
				'description'           => __( 'Show product tags', 'amphtml' ),
			),
			array(
				'id'                    => 'product_short_desc',
				'title'                 => __( 'Short Description', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_short_desc' ),
				'section'               => $section,
				'description'           => __( 'Show product short description', 'amphtml' ),
			),
			array(
				'id'                    => 'product_social_share',
				'title'                 => __( 'Social Share Buttons', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'product_social_share' ),
				'section'               => $section,
				'description'           => __( 'Show social share buttons', 'amphtml'),
			),
			array(
				'id'                    => 'product_description_block',
				'title'                 => __( 'Content Block', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_description_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_description_block' ),
				'section'               => $section,
				'description'           => '',
			),
			array(
				'id'                    => 'product_desc',
				'title'                 => __( 'Description', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_desc' ),
				'section'               => $section,
				'description'           => __( 'Show product description', 'amphtml' ),
			),
			array(
				'id'                    => 'product_attributes',
				'title'                 => __( 'Attributes', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_attributes' ),
				'section'               => $section,
				'description'           => __( 'Show product attributes', 'amphtml'),
			),
			array(
				'id'                    => 'product_reviews',
				'title'                 => __( 'Reviews', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_reviews' ),
				'section'               => $section,
				'description'           => __( 'Show product reviews', 'amphtml' ),
			),
			array(
				'id'                    => 'product_related_products_block',
				'title'                 => __( 'Related Products', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_related_products_block' ),
				'display_callback_args' => array( 'product_related_products_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_related_products_block' ),
				'section'               => $section,
				'description'           => __( 'Show related products', 'amphtml' )
			),
			array(
				'id'                    => 'product_related_rating',
				'title'                 => __( 'Rating', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_related_rating' ),
				'section'               => $section,
				'description'           => __( 'Show product rating', 'amphtml' ),
			),
			array(
				'id'                    => 'product_related_price',
				'title'                 => __( 'Price', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'product_related_price' ),
				'section'               => $section,
				'description'           => __( 'Show product price', 'amphtml' ),
			),
		);
		
		$top_ad_block[] = array(
			'id'                    => 'product_ad_top',
			'title'                 => __( 'Ad Block #1', 'amphtml' ),
			'default'               => 0,
			'section'               => $section,
			'display_callback'      => array( $this, 'display_checkbox_field' ),
			'display_callback_args' => array( 'product_ad_top' ),
			'description'           => __( 'Show ad block #1', 'amphtml' ),
		);

		$bottom_ad_block[] = array(
			'id'                    => 'product_ad_bottom',
			'title'                 => __( 'Ad Block #2', 'amphtml' ),
			'default'               => 0,
			'section'               => $section,
			'display_callback'      => array( $this, 'display_checkbox_field' ),
			'display_callback_args' => array( 'product_ad_bottom' ),
			'description'           => __( 'Show ad block #2', 'amphtml' ),
		);

		$fields = array_merge( $top_ad_block, $fields, $bottom_ad_block );
		return apply_filters( 'amphtml_template_products_fields', $fields, $section, $this );
	}

	public function get_shop_fields( $section ) {
		return array(
			array(
				'id'                    => 'shop_view',
				'title'                 => __( 'View', 'amphtml' ),
				'default'               => 'list',
				'display_callback'      => array( $this, 'display_select' ),
				'display_callback_args' => array(
					'id' => 'shop_view',
					'class' => 'unsortable',
					'select_options' => array(
						'list' => 'List',
						'grid' => 'Grid'
					)
				),
				'section'               => $section
			),
			// Block original button
			array(
				'id'                    => 'shop_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_shop_original_btn_block' ),
				'display_callback_args' => array( 'shop_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_shop_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the page', 'amphtml' )
			),
			array(
				'id'                    => 'shop_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'shop_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			array(
				'id'                    => 'shop_image',
				'title'                 => __( 'Image', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'shop_image' ),
				'section'               => $section,
				'description'           => __( 'Show product images', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_shop_rating',
				'title'                 => __( 'Rating', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'wc_shop_rating' ),
				'section'               => $section,
				'description'           => __( 'Show product rating', 'amphtml' ),
			),
			array(
				'id'                    => 'shop_short_desc',
				'title'                 => __( 'Short Description', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'shop_short_desc' ),
				'section'               => $section,
				'template_name'         => 'wc_archives_short_desc',
				'description'           => __( 'Show product short descriptions', 'amphtml' ),
			),
			array(
				'id'                    => 'shop_add_to_cart_block',
				'title'                 => __( 'Add To Cart Block', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_shop_add_to_cart_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_shop_add_to_cart_block' ),
				'section'               => $section,
				'description'           => '',
			),
			array(
				'id'                    => 'shop_price',
				'title'                 => __( 'Price', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'shop_price' ),
				'section'               => $section,
				'description'           => __( 'Show product prices', 'amphtml' ),
			),
			array(
				'id'                    => 'shop_add_to_cart',
				'title'                 => __( 'Add to Cart', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'shop_add_to_cart' ),
				'section'               => $section,
				'description'           => __( 'Show "Add to Cart" button', 'amphtml' ),
			)
		);
	}

	public function get_archives_fields( $section ) {
		return array(
			array(
				'id'                    => 'wc_archive_breadcrumbs',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array(
					'id'    => 'wc_archive_breadcrumbs',
					'class' => 'unsortable'
				),
				'template_name'         => 'breadcrumb',
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			array(
				'id'                    => 'wc_archives_view',
				'title'                 => __( 'View', 'amphtml' ),
				'default'               => 'list',
				'display_callback'      => array( $this, 'display_select' ),
				'class'                 => 'unsortable',
				'display_callback_args' => array(
					'id'             => 'wc_archives_view',
					'class'          => 'unsortable',
					'select_options' => array(
						'list' => 'List',
						'grid' => 'Grid'
					)
				),
				'section'               => $section
			),
			// Block original button
			array(
				'id'                    => 'wc_archive_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_wcarchive_original_btn_block' ),
				'display_callback_args' => array( 'wc_archive_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_wcarchive_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the page', 'amphtml' )
			),
			array(
				'id'                    => 'wc_archive_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'wc_archive_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_archives_desc',
				'title'                 => __( 'Description', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array(
					'id'    => 'wc_archives_desc',
					'class' => 'unsortable'
				),
				'section'               => $section,
				'description'           => __( 'Show description of archive page', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_archives_image',
				'title'                 => __( 'Image', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'wc_archives_image' ),
				'section'               => $section,
				'description'           => __( 'Show product images', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_archives_rating',
				'title'                 => __( 'Rating', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'wc_archives_rating' ),
				'section'               => $section,
				'description'           => __( 'Show product rating', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_archives_short_desc',
				'title'                 => __( 'Short Description', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'wc_archives_short_desc' ),
				'section'               => $section,
				'description'           => __( 'Show product short descriptions', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_archives_add_to_cart_block',
				'title'                 => __( 'Add To Cart Block', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_wc_archives_add_to_cart_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_wc_archives_add_to_cart_block' ),
				'section'               => $section,
				'description'           => '',
			),
			array(
				'id'                    => 'wc_archives_price',
				'title'                 => __( 'Price', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'wc_archives_price' ),
				'section'               => $section,
				'description'           => __( 'Show product prices', 'amphtml' ),
			),
			array(
				'id'                    => 'wc_archives_add_to_cart',
				'title'                 => __( 'Add to Cart', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'wc_archives_add_to_cart' ),
				'section'               => $section,
				'description'           => __( 'Show "Add to Cart" button', 'amphtml' ),
			)
		);
	}

	public function get_add_to_cart_fields( $section ) {
		return array(
			array(
				'id'                    => 'add_to_cart_text',
				'title'                 => __( 'Add to Cart Text', 'amphtml' ),
				'default'               => __( 'Add To Cart', 'amphtml' ),
				'section'               => $section,
				'display_callback'      => array( $this, 'display_text_field' ),
				'display_callback_args' => array( 'add_to_cart_text' ),
				'description'           => __( '"Add to Cart" button text', 'amphtml' )
			),
			array(
				'id'               => 'add_to_cart_behav',
				'title'            => __( 'Add to Cart Behavior', 'amphtml' ),
				'default'          => 'add_to_cart',
				'section'          => $section,
				'display_callback' => array( $this, 'display_add_to_cart_behav' ),
				'description'      => __( '"Add to Cart" button click action', 'amphtml' ),
			),
		);
	}

	/*
	 * Add To Cart Section
	 */
	public function display_add_to_cart_behav() {
		?>
		<select style="width: 28%" id="add_to_cart_behav"
		        name="<?php echo $this->options->get( 'add_to_cart_behav', 'name' ) ?>">
			<option value="add_to_cart" <?php selected( $this->options->get( 'add_to_cart_behav' ), 'add_to_cart' ) ?>>
				<?php _e( 'Add to cart and redirect to product page', 'amphtml' ); ?>
			</option>
			<option value="add_to_cart_cart" <?php selected( $this->options->get( 'add_to_cart_behav' ), 'add_to_cart_cart' ) ?>>
				<?php _e( 'Add to cart and redirect to cart page', 'amphtml' ); ?>
			</option>
			<option value="redirect" <?php selected( $this->options->get( 'add_to_cart_behav' ), 'redirect' ) ?>>
				<?php _e( 'Redirect to product page', 'amphtml' ); ?>
			</option>
		</select>
		<p class="description"><?php esc_html_e( $this->options->get( 'add_to_cart_behav', 'description' ), 'amphtml' ) ?></p>
		<?php
	}

	public function display_add_to_cart_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'product_price' ) ); ?>
			<?php $this->display_checkbox_field( array( 'product_stock_status' ) ); ?>
			<?php $this->display_checkbox_field( array( 'product_qty' ) ); ?>
			<?php $this->display_checkbox_field( array( 'product_options' ) ); ?>
			<?php $this->display_checkbox_field( array( 'product_add_to' ) ); ?>
		</fieldset>
		<?php
	}

	public function display_description_block() {
	?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'product_desc' ) ); ?>
			<?php $this->display_checkbox_field( array( 'product_attributes' ) ); ?>
			<?php $this->display_checkbox_field( array( 'product_reviews' ) ); ?>

		</fieldset>
	<?php
	}

	public function display_related_products_block() {
		?>
		<fieldset>
			<?php
				$this->display_checkbox_field( array( 'product_related_products_block' ) );
				if ( $this->options->get('product_related_products_block') ) {
					$this->display_checkbox_field( array( 'product_related_rating' ) );
					$this->display_checkbox_field( array( 'product_related_price' ) );
				}
			?>
		</fieldset>
		<?php
	}

	public function sanitize_add_to_cart_block() {
		$this->update_fieldset( array(
			'product_price',
			'product_stock_status',
			'product_qty',
			'product_options',
			'product_add_to'
			) );
		return 1;
	}

	public function sanitize_description_block() {
		$this->update_fieldset( array(
			'product_desc',
			'product_attributes',
			'product_reviews'
			) );
		return 1;
	}

	public function sanitize_related_products_block() {
		$this->update_fieldset( array(
			'product_related_rating',
			'product_related_price',
			) );
		$block_name = $this->options->get( 'product_related_products_block', 'name' );
		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}

	public function display_wc_archives_add_to_cart_block() {
	?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'wc_archives_price' ) ); ?>
			<?php $this->display_checkbox_field( array( 'wc_archives_add_to_cart' ) ); ?>
		</fieldset>
	<?php
	}

	public function sanitize_wc_archives_add_to_cart_block() {
		$this->update_fieldset( array(
			'wc_archives_price',
			'wc_archives_add_to_cart'
		) );
		return 1;
	}

	public function display_shop_add_to_cart_block() {
	?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'shop_price' ) ); ?>
			<?php $this->display_checkbox_field( array( 'shop_add_to_cart' ) ); ?>
		</fieldset>
	<?php
	}

	public function sanitize_shop_add_to_cart_block() {
		$this->update_fieldset( array(
			'shop_price',
			'shop_add_to_cart'
		) );
		return 1;
	}

	public function get_section_fields( $id ) {
		$fields_order = get_option( self::ORDER_OPT );
		$fields_order = maybe_unserialize( $fields_order );
		$fields_order = isset( $fields_order[ $id ] )
			? maybe_unserialize( $fields_order[ $id ] ) : array();
		if ( ! count( $fields_order ) ) {
			return parent::get_section_fields( $id );
		}
		$fields = array();
		foreach ( $fields_order as $field_name ) {
			$fields[] = $this->search_field_id( $field_name );
		}

		$fields = array_merge( parent::get_section_fields( $id ), $fields );

		// Move view option to top of list
		foreach ( $fields as $inx => $field ) {
			if ( isset( $field['display_callback_args']['class'] )
			     && isset( $field['display_callback_args']['class'] ) == 'unsortable' ) {
				unset( $fields[ $inx ] );
				array_unshift( $fields, $field );
			}
		}

		return $fields;
	}

	public function get_section_callback( $id ) {
		switch ( $id ) {
			case 'product':
			case 'shop':
			case 'wc_archives':
		        return array( $this, 'product_section_callback' );
			default:
                return parent::get_section_callback($id);
		}
	}
	
	public function display_product_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'product_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'product_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function sanitize_product_original_btn_block() {
		$this->update_fieldset( array(
			'product_original_btn_text',
		) );

		$block_name = $this->options->get( 'product_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	public function display_shop_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'shop_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'shop_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function sanitize_shop_original_btn_block() {
		$this->update_fieldset( array(
			'shop_original_btn_text',
		) );

		$block_name = $this->options->get( 'shop_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	public function display_wcarchive_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'wc_archive_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'wc_archive_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function sanitize_wcarchive_original_btn_block() {
		$this->update_fieldset( array(
			'wc_archive_original_btn_text',
		) );

		$block_name = $this->options->get( 'wc_archive_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}

	public function product_section_callback( $page, $section ) {
		global $wp_settings_fields;

		$row_id = 0;

		foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
			$class = '';

			if ( ! method_exists( $field['callback'][0], $field['callback'][1] ) ) {
				continue;
			}

			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			}
			echo "<tr data-name='{$field['id']}' id='pos_{$row_id}' {$class}>";
			echo '<th class="drag"></th>';
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
