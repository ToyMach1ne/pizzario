<?php

define('__IT_PROJECTNAME_ROOT_URL_VC__', plugin_dir_url( __FILE__ ));
if(defined('WPB_VC_VERSION')){
	class pw_brand_vc_plugin {
		public function __construct() 
		{
			//Add And Remov Param
			add_action( 'init', array( $this, 'integrateWithVC' ) );

			$this->it_includes();
		}
		
		//Add And Remov Param
		public function integrateWithVC() {
			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				return;
			}
			if(function_exists('vc_add_shortcode_param')){
				vc_add_shortcode_param('brand', array($this,'pw_brand_field'));
				vc_add_shortcode_param('pw_category', array($this,'pw_category_field'));
				vc_add_shortcode_param('pw_number' , array($this, 'pw_number_settings_field' ) );
			}else if(function_exists('add_shortcode_param')){
				add_shortcode_param('brand', array($this,'pw_brand_field'));
				add_shortcode_param('pw_category', array($this,'pw_category_field'));
				add_shortcode_param('pw_number' , array($this, 'pw_number_settings_field' ) );
			}
		}
		
		function pw_number_settings_field($settings, $value){
			$dependency = '';
			//vc_generate_dependencies_attributes($settings);
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$min = isset($settings['min']) ? $settings['min'] : '';
			$max = isset($settings['max']) ? $settings['max'] : '';
			$suffix = isset($settings['suffix']) ? $settings['suffix'] : '';		   
			$output = '<input name="'.$settings['param_name']
					.'" class="wpb_vc_param_value wpb-textinput '
					.$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="'
					.$settings['param_name'].'" type="number" min="'.$min.'" max="'.$max.'" value="'.$value.'" ' . $dependency . 'style="max-width:100px; margin-right: 10px;" />'.$suffix;
					
			return $output;
		}

		function pw_brand_field($settings, $value){
			$args = array(
				'taxonomy'       		   =>  'product_brand',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'child_of'          		 => 0,
				'number'                   => '',
				'pad_counts'               => false 
			
			);
			$categories = get_categories($args); 		
			$param_line='';

			$param_line .= '
			<input name="'.$settings['param_name']
				 .'" class="wpb_vc_param_value wpb-textinput '
				 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].' pw_brand_items_'.$settings['param_name'].'" id="pw_brand_items" type="hidden" value="'.$value.'" data-id="'.$settings['param_name'].'"/>';
			
			$param_line.='<select name="'.$settings['param_name'].'-a" class="wpb_vc_param_value wpb-select '
				.$settings['param_name'].' '.$settings['type'].'_field chosen-select_brand" multiple="multiple"  data-class="pw_brand_items_'.$settings['param_name'].'">';

						foreach ($categories as $category) {
							
							$selected='';
							if(is_array(explode(',',$value)) && in_array($category->cat_ID,explode(',',$value)))
								$selected='selected';
								
							$option = '<option value="'.$category->cat_ID.'" '.$selected.'>';
							$option .= $category->cat_name;
							$option .= ' ('.$category->category_count.')';
							$option .= '</option>';
							$param_line .= $option;
						}
			$param_line.='</select>';
			$param_line.="
						<script type='text/javascript'>
							/* <![CDATA[ */
							jQuery(document).ready(function() {
								jQuery('.chosen-select_brand').chosen();	
								jQuery('.chosen-select_brand').change(function(){
									var element_target='.'+jQuery(this).attr('data-class');
									jQuery(element_target).val(jQuery(this).val());
								});	
									
							});
							/* ]]> */
						</script>";
			
			$dependency = '';
			//vc_generate_dependencies_attributes($settings);
			
			return $param_line;
		}

		function pw_category_field($settings, $value){
			$args = array(
				'taxonomy'       		   =>  'product_cat',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'child_of'          		 => 0,
				'number'                   => '',
				'pad_counts'               => false 
			
			);
			$categories = get_categories($args); 		
			$param_line='';

			$param_line .= '
			<input name="'.$settings['param_name']
				 .'" class="wpb_vc_param_value wpb-textinput '
				 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].' pw_brand_items_'.$settings['param_name'].'" id="pw_brand_items" type="hidden" value="'.$value.'" data-id="'.$settings['param_name'].'"/>';
			
			$param_line.='<select name="'.$settings['param_name'].'-a" class="wpb_vc_param_value wpb-select '
				.$settings['param_name'].' '.$settings['type'].'_field chosen-select_cat" multiple="multiple"  data-class="pw_brand_items_'.$settings['param_name'].'">';

						foreach ($categories as $category) {
							
							$selected='';
							if(is_array(explode(',',$value)) && in_array($category->cat_ID,explode(',',$value)))
								$selected='selected';
								
							$option = '<option value="'.$category->cat_ID.'" '.$selected.'>';
							$option .= $category->cat_name;
							$option .= ' ('.$category->category_count.')';
							$option .= '</option>';
							$param_line .= $option;
						}
			$param_line.='</select>';
			$param_line.="
						<script type='text/javascript'>
							/* <![CDATA[ */
							jQuery(document).ready(function() {
								jQuery('.chosen-select_cat').chosen();	
								jQuery('.chosen-select_cat').change(function(){
									var element_target='.'+jQuery(this).attr('data-class');
									jQuery(element_target).val(jQuery(this).val());
								});	
									
							});
							/* ]]> */
						</script>";
			
			$dependency = '';
			//vc_generate_dependencies_attributes($settings);
			
			return $param_line;
		}		

		
		
		public function it_includes()
		{
			require_once('class/pw_brand_a_z_view.php');
			require_once('class/pw_brand_vc_all_view.php');
			require_once('class/pw_brand_carousel.php');
			require_once('class/pw_brand_product_carousel.php');
			require_once('class/pw_brand_thumbnails.php');
			require_once('class/pw_brand_product_grid.php');
			require_once('class/pw_brand_product_list_filter.php');
		}
	}
	new pw_brand_vc_plugin();
}
?>