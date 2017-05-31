<?php

class DHVCWooAdmin {
	public function __construct(){
		add_action('admin_init', array($this, 'init'));
		
		//product category form
		add_action( 'product_cat_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'product_cat_edit_form_fields', array( $this, 'edit_category_fields' ) ,10,2);
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10,3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10,3);
		
// 		//product tag form
// 		add_action( 'product_tag_add_form_fields', array( $this, 'add_tag_fields' ) );
// 		add_action( 'product_tag_edit_form_fields', array( $this, 'edit_tag_fields' ) ,10,2);
// 		add_action( 'created_term', array( $this, 'save_tag_fields' ) , 10,3);
// 		add_action( 'edit_term', array( $this, 'save_tag_fields' ), 10,3);
		
		//product brand form
		add_action( 'product_brand_add_form_fields', array( $this, 'add_brand_fields' ) );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_brand_fields' ) ,10,2);
		add_action( 'created_term', array( $this, 'save_brand_fields' ), 10,3);
		add_action( 'edit_term', array( $this, 'save_brand_fields' ), 10,3);
		

		add_action( 'woocommerce_settings_catalog_options_after',array(&$this,'settings_catalog_options_after'));
		add_action('woocommerce_update_options_products',array(&$this,'update_options_products'));
		
		
	}
	
	public function init(){
	
		wp_register_script( 'dhvc-woo-admin',DHVC_WOO_URL. '/assets/js/admin.js', array('jquery'),DHVC_WOO_VERSION,false);
		wp_enqueue_script( 'dhvc-woo-admin' );
		
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
			return;
		
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array($this, 'mce_external_plugins'));
			add_filter('mce_buttons', array($this, 'mce_buttons'));
		}
		$dhvcwoocommerce = array('plugin_url'=>DHVC_WOO_URL,'form'=>$this->shortcode_params());
		wp_localize_script('jquery', 'dhvcwoocommerce', $dhvcwoocommerce);
		wp_enqueue_style('dhvc-woo-chosen');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style('dhvc-woo-admin',DHVC_WOO_URL.'/assets/css/admin.css');
		
	}
	

	/**--------------woocommerce_admin_settings--------------*/
	protected function _get_admin_settings(){
		return  apply_filters( 'dhvc_woo_settings_fields', array(
			array(
				'name' => __( 'DHVC Woo Options', DHVC_WOO ),
				'type' => 'title',
				'id' => 'dhvc_woo' ),
			array(
				'name' 		=> __( 'Product Archive / Shop Page', DHVC_WOO ),
				'desc' 		=> __( 'Use DHVC WOO Override Woocommerce Product Archive / Shop Page Options.', DHVC_WOO ),
				'id' 		=> 'dhvc_woo_product_archives',
				'type' 		=> 'checkbox',
			),
			array(
				'type' => 'sectionend',
				'id' => 'dhvc_woo_override_product_archives'
			),
		) );
	}
	
	
	public function settings_catalog_options_after(){
		woocommerce_admin_fields($this->_get_admin_settings());
	}
	
	public function update_options_products(){
		woocommerce_update_options($this->_get_admin_settings());
	}
	
	public function mce_external_plugins($plugins){
		
		$plugins['dhvcwoocommerce'] = DHVC_WOO_URL. '/assets/js/admin_plugin.js';
		return $plugins;
	}
	public function mce_buttons($buttons){
		$buttons[] = 'dhvcwoocommerce_button';
		return $buttons;
	}
	
	public function add_category_fields(){
	?>
	<div class="form-field">
		<label for="dhvc_woo_category_page_id"><?php _e( 'Page Template', DHVC_WOO ); ?></label>
		<?php 
		$args = array(
				'name'=>'dhvc_woo_category_page_id',
				'show_option_none'=>' ',
				'echo'=>false,
		);
		echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;',DHVC_WOO) .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
		
		?>
	</div>
	
	<?php
	}
	
	public function edit_category_fields( $term, $taxonomy ) {
		$dhvc_woo_category_page_id = get_woocommerce_term_meta( $term->term_id, 'dhvc_woo_category_page_id', true );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Page Template', DHVC_WOO ); ?></label></th>
		<td>
			<?php 
			$args = array(
					'name'=>'dhvc_woo_category_page_id',
					'show_option_none'=>' ',
					'post_status' => 'publish,private',
					'echo'=>false,
					'selected'=>absint($dhvc_woo_category_page_id)
			);
			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;',DHVC_WOO) .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
			
			?>
		</td>
	</tr>
	<?php
	}
	
	public function save_category_fields( $term_id, $tt_id, $taxonomy ) {
		if(!empty($_POST['dhvc_woo_category_page_id'])){
			update_woocommerce_term_meta($term_id, 'dhvc_woo_category_page_id', absint( $_POST['dhvc_woo_category_page_id'] ) );
		}else{
			delete_woocommerce_term_meta($term_id,  'dhvc_woo_category_page_id');
		}
	}
	
	public function add_tag_fields(){
		?>
	<div class="form-field">
		<label for="dhvc_woo_tag_page_id"><?php _e( 'Page Template', DHVC_WOO ); ?></label>
		<?php 
		$args = array(
				'name'=>'dhvc_woo_tag_page_id',
				'show_option_none'=>' ',
				'post_status' => 'publish,private',
				'echo'=>false,
		);
		echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;',DHVC_WOO) .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
		
		?>
	</div>
	
	<?php
	}
	
	public function edit_tag_fields( $term, $taxonomy ) {
		$dhvc_woo_tag_page_id = get_woocommerce_term_meta( $term->term_id, 'dhvc_woo_tag_page_id', true );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Page Template', DHVC_WOO ); ?></label></th>
		<td>
			<?php 
			$args = array(
					'name'=>'dhvc_woo_tag_page_id',
					'show_option_none'=>' ',
					'echo'=>false,
					'post_status' => 'publish,private',
					'selected'=>absint($dhvc_woo_tag_page_id)
			);
			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;',DHVC_WOO) .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
			
			?>
		</td>
	</tr>
	<?php
	}
	
	public function save_tag_fields( $term_id, $tt_id, $taxonomy ) {
		if(!empty($_POST['dhvc_woo_tag_page_id'])){
			update_woocommerce_term_meta($term_id, 'dhvc_woo_tag_page_id', absint( $_POST['dhvc_woo_tag_page_id'] ) );
		}else{
			delete_woocommerce_term_meta($term_id,  'dhvc_woo_tag_page_id');
		}
	}
	

	public function add_brand_fields(){
		?>
	<div class="form-field">
		<label for="dhvc_woo_brand_page_id"><?php _e( 'Page Template', DHVC_WOO ); ?></label>
		<?php 
		$args = array(
				'name'=>'dhvc_woo_brand_page_id',
				'show_option_none'=>' ',
				'post_status' => 'publish,private',
				'echo'=>false,
		);
		echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;',DHVC_WOO) .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
		
		?>
	</div>
	
	<?php
	}
	
	public function edit_brand_fields( $term, $taxonomy ) {
		$dhvc_woo_brand_page_id = get_woocommerce_term_meta( $term->term_id, 'dhvc_woo_brand_page_id', true );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Page Template', DHVC_WOO ); ?></label></th>
		<td>
			<?php 
			$args = array(
					'name'=>'dhvc_woo_brand_page_id',
					'show_option_none'=>' ',
					'echo'=>false,
					'post_status' => 'publish,private',
					'selected'=>absint($dhvc_woo_brand_page_id)
			);
			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;',DHVC_WOO) .  "' class='enhanced chosen_select_nostd' id=", wp_dropdown_pages( $args ) );
			
			?>
		</td>
	</tr>
	<?php
	}
	
	public function save_brand_fields( $term_id, $tt_id, $taxonomy ) {
		if(!empty($_POST['dhvc_woo_brand_page_id'])){
			update_woocommerce_term_meta($term_id, 'dhvc_woo_brand_page_id', absint( $_POST['dhvc_woo_brand_page_id'] ) );
		}else{
			delete_woocommerce_term_meta($term_id,  'dhvc_woo_brand_page_id');
		}
	}
	
	public function shortcode_params(){
		$params = dhvc_woo_params();
		$params_fields = array (
				'dhvc_woo_field_id'						=> 'dhvc_woo_setting_field_id',
				'dhvc_woo_field_products_ajax' 			=> 'dhvc_woo_setting_field_products_ajax',
				'dhvc_woo_field_exclude_products_ajax'	=> 'dhvc_woo_setting_field_products_ajax',
				'dhvc_woo_field_id' 					=> 'dhvc_woo_setting_field_id',
				'dhvc_woo_field_categories' 			=> 'dhvc_woo_setting_field_categories',
				'dhvc_woo_field_exclude_categories' 	=> 'dhvc_woo_setting_field_categories',
				'dhvc_woo_field_tags' 					=> 'dhvc_woo_setting_field_tags',
				'dhvc_woo_field_brands' 				=> 'dhvc_woo_setting_field_brands',
				'dhvc_woo_field_exclude_brands'			=> 'dhvc_woo_setting_field_brands',
				'dhvc_woo_field_exclude_tags' 			=> 'dhvc_woo_setting_field_tags',
				'dhvc_woo_field_attributes' 			=> 'dhvc_woo_setting_field_attributes',
				'dhvc_woo_field_heading' 				=> 'dhvc_woo_setting_field_heading' 
		);
		ob_start();
		?>
		<div class="dhvc-woo-shortcode-params">
		<?php
		foreach ($params as $param){
		?>
		<div class="dhvc-woo-shortcode-param">
			<?php if (isset($param['heading'])){?>
			<div class="dhvc-woo-element-label"><?php echo $param['heading'] ?></div>
			<?php } ?>
			<div class="dhvc-woo-element-input">
				<?php 

				$dependency = '';
				if(isset($param['dependency'])){
					$dependency_value = isset($param['dependency']['value']) ? $param['dependency']['value'] : '';
					if(is_array($dependency_value)){
						$dependency_value = implode(',', $dependency_value);	
					}
					$dependency = 'data-dependency="toggle" data-dependency-element="'.$param['dependency']['element'].'" data-dependency-value = "'.$dependency_value.'"';
				}
				
				if(array_key_exists($param['type'], $params_fields)){
					$func = $params_fields[$param['type']];
					$value = '';
					if(isset($param['value']))
						$value = $param['value'];
					echo call_user_func($func,$param,$value,$dependency);
				}else{
					switch ($param['type']){
						case 'textfield':
							echo '<input '.$dependency.' id="'.$param['param_name'].'" class="dhvc-woo-param-value" name="'.$param['param_name'].'" value="'.(isset($param['value']) ? $param['value'] : '').'" type="text"/>';
							break;
						case 'dropdown':
							$param_line = '<select '.$dependency.' name="'.$param['param_name'].'" class="dhvc-woo-param-value" id="'.$param['param_name'].'">';
							$param_value = '';
							$flag = false;
							foreach ( $param['value'] as $text_val => $val ) {
								if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
									$text_val = $val;
								}
								$text_val = __($text_val, DHVC_WOO);
								$selected = '';
								if(!$flag){
									$param_value = $val;
									$flag = true;
								}
								if ((string)$val === (string)$param_value) {
									$selected = ' selected="selected"';
								}
								
								$param_line .= '<option value="'.$val.'"'.$selected.'>'.htmlspecialchars($text_val).'</option>';
							}
							$param_line .= '</select>';
							echo $param_line;
							break;
						case 'checkbox' :
							$values = is_array($param['value']) ? $param['value'] : array();
							$param_line = '';
							foreach ( $values as $label => $v ) {
								$checked ='';
								$param_line .= ' <input '.$dependency.' id="'. $param['param_name'] . '-' . $v .'" value="' . $v . '" class="dhvc-woo-param-value '.$param['param_name'].' '.$param['type'].'" type="checkbox" name="'.$param['param_name'].'"'.$checked.'> ' . __($label, DHVC_WOO);
							}
							echo $param_line;
							break;
						case 'colorpicker':
							echo '<div class="color-group">'
									.'<input '.$dependency.' name="'.$param['param_name'].'" class="dhvc-woo-param-value '.$param['type'].'_field dhvc-woo-color-control" value="'.$param['value'].'" type="text"/>'
								 .'</div>';
							break;
						default:
							break;
					}
				}
				?>
				<?php if(isset($param['description'])):?>
				<span class="clear" style="font-size: 11px;color:#999999"><?php echo $param['description'] ?></span>
				<?php endif;?>
			</div>
		</div>
		<?php
		}
		?>
		<p class="submit">
			<input id="dhvcwoocommerce-shortcode-submit" class="button-primary" type="button" value="Insert Shortcode">
		</p>
		</div>
		<?php
		return ob_get_clean();
	}
	
}

new DHVCWooAdmin();