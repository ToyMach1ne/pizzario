<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class pw_brans_WC_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_filter( 'get_terms', array( $this,'wc_change_term_countsa') , 10, 2 );
		add_action( 'init', array( $this, 'create_taxonomies' ) );
		add_action( "delete_term", array( $this, 'delete_term' ), 5 );

		/* Add form */
		add_action( 'product_brand_add_form_fields', array( $this, 'add_brands_fields' ) );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_brands_fields' ), 10, 2 );
		add_action( 'created_term', array( $this, 'save_brands_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_brands_fields' ), 10, 3 );

		/* Add columns */
		add_filter( 'manage_edit-product_brand_columns', array( $this, 'brands_columns' ) );
		add_filter( 'manage_product_brand_custom_column', array( $this, 'brands_column' ), 10, 3 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		/* create radiobox */
	//	add_action( 'admin_menu',array( $this,  'pw_woocommerc_brands_remove_meta_box'));
	//	add_action( 'add_meta_boxes',array( $this,  'pw_woocommerc_brands_add_meta_box'));
		
	//	add_filter( 'manage_edit-product_columns', array( $this, 'edit_columns_brands' ),15 );
	//	add_action( 'manage_product_posts_custom_column', array( $this, 'custom_columns_brands' ), 15 );
	//	add_filter('manage_edit-product_columns', array( $this, 'riv_news_type_columns'), 5);
	


	}

	
public function wc_change_term_countsa( $terms, $taxonomies ) {
	if ( is_admin() || is_ajax() ) {
		return $terms;
	}

	if ( ! isset( $taxonomies[0] ) || ! in_array( $taxonomies[0], apply_filters( 'woocommerce_change_term_counts', array( 'product_brand') ) ) ) {
		return $terms;
	}

	$term_counts = $o_term_counts = get_transient( 'wc_term_counts' );

	foreach ( $terms as &$term ) {
		if ( is_object( $term ) ) {
			$term_counts[ $term->term_id ] = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : get_woocommerce_term_meta( $term->term_id, 'product_count_' . $taxonomies[0] , true );

			if ( $term_counts[ $term->term_id ] !== '' ) {
				$term->count = absint( $term_counts[ $term->term_id ] );
			}
		}
	}

	// Update transient
	if ( $term_counts != $o_term_counts ) {
		set_transient( 'wc_term_counts', $term_counts, DAY_IN_SECONDS * 30 );
	}

	return $terms;
}
	
	public function edit_columns_brands($defaults) {
		$defaults['product_brand']  = __( 'Brands', 'woocommerce-brands' );	
		return $defaults;
	}

	public function custom_columns_brands($column)
	{
		global $post, $woocommerce, $the_product;

		if ( empty( $the_product ) || $the_product->id != $post->ID ) {
			$the_product = get_product( $post );
		}
	
		switch ( $column ) {
			case 'product_brand' :
				if ( ! $terms = get_the_terms( $post->ID, $column ) ) {
					echo '<span class="na">&ndash;</span>';
				} else {
					foreach ( $terms as $term ) {
						$termlist[] = '<a href="' . admin_url( 'edit.php?' . $column . '=' . $term->slug . '&post_type=product' ) . ' ">' . $term->name . '</a>';
					}
					echo implode( ', ', $termlist );
				}
				break;	
			default :
				break;				
		}
	}
	
	public function pw_woocommerc_brands_remove_meta_box(){
	//   remove_meta_box('product_branddiv', 'product', 'normal');
	}


	 public function pw_woocommerc_brands_add_meta_box() {
	//	 add_meta_box( 'mytaxonomy_id', 'Brands',array( $this,'pw_woocommerc_brands_metabox'),'product' ,'side','core');
	 }

	//Callback to set up the metabox
	public function pw_woocommerc_brands_metabox( $post ) {
		//Get taxonomy and terms
		$taxonomy = 'product_brand';
	 
		//Set up the taxonomy object and get terms
		$tax = get_taxonomy($taxonomy);
		$terms = get_terms($taxonomy,array('hide_empty' => 0));
	 
		//Name of the form
		$name = 'tax_input[' . $taxonomy . ']';
	 
		//Get current and popular terms
		$popular = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );
		$postterms = get_the_terms( $post->ID,$taxonomy );
		$current = ($postterms ? array_pop($postterms) : false);
		$current = ($current ? $current->term_id : 0);
		?>
	 
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
	 
			<!-- Display tabs-->
			<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
				<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
				<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used','woocommerce-brands' ); ?></a></li>
			</ul>
	 
			<!-- Display taxonomy terms -->
			<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
				<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
					<?php   foreach($terms as $term){
						$id = $taxonomy.'-'.$term->term_id;
						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='radio' id='in-$id' name='{$name}'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";
					   echo "</label></li>";
					}?>
			   </ul>
			</div>
	 
			<!-- Display popular taxonomy terms -->
			<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
				<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
					<?php   foreach($popular as $term){
						$id = 'popular-'.$taxonomy.'-'.$term->term_id;
						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='radio' id='in-$id'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";
						echo "</label></li>";
					}?>
			   </ul>
		   </div>
	 
		</div>
		<?php
	}

// create two taxonomies, genres and writers for the post type "product"
	public function create_taxonomies() {
		$labels = array(
			'name' => __( 'Brands', 'woocommerce-brands' ),
			'singular_name' => __( 'Brand', 'woocommerce-brands' ),
			'search_items' =>  __( 'Search Brands' ,'woocommerce-brands'),
			'all_items' => __( 'All Brands' ,'woocommerce-brands'),
			'parent_item' => __( 'Parent Brand' ,'woocommerce-brands'),
			'parent_item_colon' => __( 'Parent Brands:' ,'woocommerce-brands'),
			'edit_item' => __( 'Edit Brands' ,'woocommerce-brands'),
			'update_item' => __( 'Update Brands' ,'woocommerce-brands'),
			'add_new_item' => __( 'Add New Brand' ,'woocommerce-brands'),
			'new_item_name' => __( 'New Brand Name' ,'woocommerce-brands'),
			'menu_name' => __( 'Brands' ,'woocommerce-brands'),
		);    

	    register_taxonomy("product_brand",
	     array("product"),
	     array(
		     'hierarchical' => true,
		     'labels' => $labels,
		   	 'show_ui' => true,
    		 'query_var' => true,
		     'rewrite' => array( 'slug' => (get_option('pw_woocommerce_brands_base')=="" ? __( 'brand', 'woocommerce-brands' ) : get_option('pw_woocommerce_brands_base')), 'with_front' => true ),
		     'show_admin_column' => true
	     ));
	}  

	public function delete_term( $term_id ) {

		$term_id = (int) $term_id;

		if ( ! $term_id )
			return;

		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->woocommerce_termmeta} WHERE `woocommerce_term_id` = " . $term_id );
	}

	public function admin_scripts() {
			wp_enqueue_media();
	}
	
	public function add_brands_fields() {
		if(get_option('pw_woocommerce_brands_default_image'))
			$image=wp_get_attachment_thumb_url(get_option('pw_woocommerce_brands_default_image'));
		else
			$image = WP_PLUGIN_URL.'/woo-brand/img/default.png';
		?>
		<div class="">
			<label for="display_type"><?php _e( 'Url', 'woocommerce-brands' ); ?></label>
            <input type="text" name="url" style=" width: 95%" />
			<p><?php _e('Set Brand External Url (if you set the url, when visitor click on a brand name, this url will be displayed instead of brand page )','woocommerce-brands'); ?>.</p>
		</div>
		<br/>			
		<div class="">
			<label for="display_type"><?php _e( 'Featured', 'woocommerce-brands' ); ?></label>
            <input type="checkbox" name="featured" />
		</div>
		<br/>
		<div class="form-field">
			<label><?php _e( 'Thumbnail', 'woocommerce-brands' ); ?></label>
			<div id="brands_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
			<div style="line-height:60px;">
				<input type="hidden" id="brands_thumbnail_id" name="brands_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce-brands' ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce-brands' ); ?></button>
			</div>
			<script type="text/javascript">
				 // Only show the "remove image" button when needed
				 if ( ! jQuery('#brands_thumbnail_id').val() )
					 jQuery('.remove_image_button').hide();

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){
					

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'woocommerce-brands' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'woocommerce-brands' ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#brands_thumbnail_id').val( attachment.id );
						jQuery('#brands_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#brands_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
					jQuery('#brands_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		<?php
	}

	public function edit_brands_fields( $term, $taxonomy ) {
		$display_type	= get_woocommerce_term_meta( $term->term_id, 'featured', true );
		$url	= get_woocommerce_term_meta( $term->term_id, 'url', true );
		$image 			= '';
		$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
		if ( $thumbnail_id )
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		else
		{
			$image = wc_placeholder_img_src();	
		}
		?>
		
		<tr class="">
			<th scope="row" valign="top"><label><?php _e( 'URL', 'woocommerce-brands' ); ?></label></th>
			<td>
	  			 <input type="text" name="url" value="<?php echo $url;?>" style= "width: 95%;"/><br/>
				 <span class="description"><?php _e('Set Brand External Url (if you set the url, when visitor click on a brand name, this url will be displayed instead of brand page )','woocommerce-brands'); ?>.</span>
			</td>

		</tr>
		
		<tr class="">
			<th scope="row" valign="top"><label><?php _e( 'Featured', 'woocommerce-brands' ); ?></label></th>
			<td>
	  			 <input type="checkbox" name="featured" <?php checked( $display_type, 1 ); ?>/>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'woocommerce-brands' ); ?></label></th>
			<td>
				<div id="brands_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
				<div style="line-height:60px;">
					<input type="hidden" id="brands_thumbnail_id" name="brands_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
					<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce-brands' ); ?></button>
					<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce-brands' ); ?></button>
				</div>
				<script type="text/javascript">

					// Uploading files
					var file_frame;

					jQuery(document).on( 'click', '.upload_image_button', function( event ){

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( 'Choose an image', 'woocommerce-brands' ); ?>',
							button: {
								text: '<?php _e( 'Use image', 'woocommerce-brands' ); ?>',
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							attachment = file_frame.state().get('selection').first().toJSON();

							jQuery('#brands_thumbnail_id').val( attachment.id );
							jQuery('#brands_thumbnail img').attr('src', attachment.url );
							jQuery('.remove_image_button').show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery(document).on( 'click', '.remove_image_button', function( event ){
						jQuery('#brands_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
						jQuery('#brands_thumbnail_id').val('');
						jQuery('.remove_image_button').hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}


	public function save_brands_fields( $term_id, $tt_id, $taxonomy ) {
		if ( isset( $_POST['featured'] ) ){

			update_woocommerce_term_meta( $term_id, 'featured', 1);
		}	
		else{	
			update_woocommerce_term_meta( $term_id, 'featured', 0);
		}
		if ( isset( $_POST['brands_thumbnail_id'] ) )
			update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['brands_thumbnail_id'] ) );
			
		if ( isset( $_POST['url'] ) ){

			update_woocommerce_term_meta( $term_id, 'url', $_POST['url']);
		}	
		delete_transient( 'wc_term_counts' );
	}

	public function brands_columns( $columns ) {
			
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'woocommerce-brands' );		
		$new_columns['name'] =__('Name','woocommerce-brands');
		$new_columns['featured'] = __( 'featured', 'woocommerce-brands' );
		$new_columns['url'] = __( 'url', 'woocommerce-brands' );		
		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
		
	}

	public function brands_column( $columns, $column, $id ) {

		if ( $column == 'thumb' ) {

			$image 			= '';
			$thumbnail_id 	= get_woocommerce_term_meta( $id, 'thumbnail_id', true );

			if ($thumbnail_id)
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			else
				$image = wc_placeholder_img_src();
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="Thumbnail" class="wp-post-image" height="48" width="48" />';

		}
		
		if($column=="featured"){
			$display_type	= get_woocommerce_term_meta( $id, 'featured', true );
			if($display_type=="1")
				$columns.= 'yes';
			else		
				$columns.= 'no';
			}
		if($column=="url")
			$columns.= get_woocommerce_term_meta( $id, 'url', true );			

		return $columns;
	}
}
new pw_brans_WC_Admin_Taxonomies();


class PW_TAX_EDITOR_TINYMC {

	/**
	 * The taxonomies which should use the visual editor
	 *
	 * @since 1.0
	 * @var   array
	 */
	public $taxonomies;

	/**
	 * The constructor function for the class
	 *
	 * @since  1.0
	 * @access public
	 * @param  array $taxonomies The taxonomies which should use a visual editor
	 * @return void
	 */
	public function __construct( $taxonomies ) {

		/* Setup the class variables */
		$this->taxonomies = (array) $taxonomies;

		/* Only users with the "publish_posts" capability can use this feature */
		if ( current_user_can( 'publish_posts' ) ) {

			/* Remove the filters which disallow HTML in term descriptions */
			remove_filter( 'pre_term_description', 'wp_filter_kses' );
			remove_filter( 'term_description', 'wp_kses_data' );

			/* Add filters to disallow unsafe HTML tags */
			if ( ! current_user_can( 'unfiltered_html ' ) ) {
				add_filter( 'pre_term_description', 'wp_kses_post' );
				add_filter( 'term_description', 'wp_kses_post' );
			}
		}

		/* Evaluate shortcodes */
		add_filter( 'term_description', 'do_shortcode' );

		/* Convert smilies */
		add_filter( 'term_description', 'convert_smilies' );

		/* Loop through the taxonomies, adding actions */
		foreach ( $this->taxonomies as $taxonomy ) {
			if($taxonomy!='product_brand')
				continue;
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'render_field_edit' ), 1, 2 );
			add_action( $taxonomy . '_add_form_fields', array( $this, 'render_field_add' ), 1, 1 );
		}
	}

	/**
	 * Add the visual editor to the edit tag screen
	 *
	 * @since  1.0
	 * @access public
	 * @param  object $tag      The tag currently being edited
	 * @param  string $taxonomy The taxonomy that the tag belongs to
	 * @return void
	 */
	public function render_field_edit( $tag, $taxonomy ) {

		$settings = array(
			'textarea_name' => 'description',
			'textarea_rows' => 10,
		);

		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="description"><?php _ex( 'Description', 'Taxonomy Description' ); ?></label></th>
			<td><?php wp_editor( htmlspecialchars_decode( $tag->description ), 'html-description', $settings ); ?>
			<span class="description"><?php _e( 'The description is not prominent by default, however some themes may show it.' ); ?></span></td>
			<script type="text/javascript">
				// Remove the non-html field
				jQuery( 'textarea#description' ).closest( '.form-field' ).remove();
			</script>
		</tr>
		<?php
	}

	/**
	 * Add the visual editor to the add new tag screen
	 *
	 * @since  1.0
	 * @access public
	 * @param  string $taxonomy The taxonomy that a new tag is being added to
	 * @return void
	 */
	public function render_field_add( $taxonomy ) {

		$settings = array(
			'textarea_name' => 'description',
			'textarea_rows' => 7,
		);

		?>
		<div>
			<label for="tag-description"><?php _ex( 'Description', 'Taxonomy Description' ); ?></label>
			<?php wp_editor( '', 'html-tag-description', $settings ); ?>
			<p class="description"><?php _e( 'The description is not prominent by default, however some themes may show it.' ); ?></p>
			<script type="text/javascript">
				// Remove the non-html field
				jQuery( 'textarea#tag-description' ).closest( '.form-field' ).remove();

				jQuery(function() {
					// Trigger save
					jQuery( '#addtag' ).on( 'mousedown', '#submit', function() {
							tinyMCE.triggerSave();
						});
					});

			</script>
		</div>
		<?php
	}

}

/**
 * Instantiates the class to work on all of the registered taxonomies
 *
 * @since  1.0
 * @access public
 * @return void
 */
function pw_tax_editor_tinymc() {

	/* Retrieve an array of registered taxonomies */
	$taxonomies = get_taxonomies( '', 'names' );

	/* Initialize the class */
	$GLOBALS['PW-TAX-EDITOR-TINYMC'] = new PW_TAX_EDITOR_TINYMC( $taxonomies );
}
add_action( 'wp_loaded', 'pw_tax_editor_tinymc', 999 );

/**
 * Fix the formatting buttons on the HTML section of
 * the visual editor from being full-width
 *
 * @since  1.1
 * @return void
 */
function fix_pw_tax_editor_tinymc_style() {
	echo '<style>.quicktags-toolbar input { width: auto; }</style>';
}

add_action( 'admin_head-edit-tags.php', 'fix_pw_tax_editor_tinymc_style' );
?>