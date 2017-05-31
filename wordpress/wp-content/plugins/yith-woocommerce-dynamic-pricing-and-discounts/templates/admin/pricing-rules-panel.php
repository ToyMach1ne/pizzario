<?php
if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWDPD_VERSION' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template For Pricing Rules Free
 *
 * @package YITH WooCommerce Dynamic Pricing and Discounts
 * @since   1.0.0
 * @author  Yithemes
 */

$pricing_rules_options = YITH_WC_Dynamic_Pricing()->pricing_rules_options;
$suffix_id             = $id . '[' . $key . ']';
$suffix_name           = $name . '[' . $key . ']';
$rules                 = YITH_WC_Dynamic_Pricing_Helper()->get_roles();

?>
<div class="ywdpd-section-handle" data-key="<?php echo $key ?>">
	<form class="form-pricing-rules" id="form-<?php echo $key ?>" method="post">
	<input type="hidden" name="section-key" value="<?php echo $key ?>" />
	<input type="hidden" name="ywdpd-action" value="save-options" />
	<div class="ywdpd-section ywdpd-select-wrapper section-<?php echo $key ?>">

		<div class="ywdpd-section-head">
			<span class="ywdpd-active activated" data-section="<?php echo $key ?>"></span>
			<?php echo $description ?>
			<input type="hidden" name="<?php echo $suffix_name . '[description]' ?>" id="<?php echo $suffix_id . '[description]' ?>" value="<?php echo $description ?>">
			<input type="hidden" name="<?php echo $suffix_name . '[active]' ?>" id="<?php echo $suffix_id . '[active]' ?>" class="active-hidden-field" value="yes">
            <span class="ywdpd-clone" data-section="<?php echo $key ?>"><img src="data:image/svg+xml;base64,CjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDEwMDAgMTAwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwMCAxMDAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPG1ldGFkYXRhPiBTdmcgVmVjdG9yIEljb25zIDogaHR0cDovL3d3dy5vbmxpbmV3ZWJmb250cy5jb20vaWNvbiA8L21ldGFkYXRhPgogIDxnPjxwYXRoIGQ9Ik0zNzIuMiw0NzguN2gtMjEzdjQyLjZoMjEzVjQ3OC43eiBNMTU5LjEsNzc3aDQ2OC43di00Mi42SDE1OS4xVjc3N3ogTTM3Mi4yLDM1MC45aC0yMTN2NDIuNmgyMTNWMzUwLjl6IE0xNTkuMSw2NDkuMWg0NjguN3YtNDIuNkgxNTkuMVY2NDkuMXogTTc3MC43LDQyMWwtMjYzLTI4My4yaC05Mi45Yy0xNTUuOSwwLTMxOS42LDAtMzE5LjYsMEM0OC4yLDEzNy44LDEwLDE3NiwxMCwyMjN2NjgxLjdjMCw0Ny4xLDM4LjIsODUuMiw4NS4yLDg1LjJoNTk2LjVjNDcuMSwwLDg1LjItMzguMiw4NS4yLTg1LjJWNDM2LjFsLTcuNS03LjVMNzcwLjcsNDIxeiBNNTIxLjMsMjE1LjhsMTg2LjUsMTk5SDU2My45Yy0yMy41LDAtNDIuNi0xOS4xLTQyLjYtNDIuNlYyMTUuOHogTTczNC4zLDkwNC44YzAsMjMuNS0xOS4xLDQyLjYtNDIuNiw0Mi42SDk1LjJjLTIzLjUsMC00Mi42LTE5LjEtNDIuNi00Mi42VjIyM2MwLTIzLjUsMTkuMS00Mi42LDQyLjYtNDIuNmgzODMuNXYxOTEuN2MwLDQ3LjEsMzguMiw4NS4yLDg1LjIsODUuMmgxNzAuNFY5MDQuOHogTTk4Mi41LDMwMC43bDEuMy03LjVMNzIwLjgsMTBoLTkzYy0xNTUuOSwwLTMxOS42LDAtMzE5LjYsMEMyNjEuMiwxMCwyMjMsNDguMiwyMjMsOTUuMmg0Mi42YzAtMjMuNSwxOS4xLTQyLjYsNDIuNi00Mi42aDM4My41djE5MS43YzAsNDcuMSwzOC4yLDg1LjIsODUuMiw4NS4yaDE3MC40Vjc3N2MwLDIzLjUtMTkuMSw0Mi42LTQyLjYsNDIuNmgtODUuMnY0Mi42aDg1LjJjNDcuMSwwLDg1LjItMzguMiw4NS4yLTg1LjJWMzA4LjNMOTgyLjUsMzAwLjd6IE03NzcsMjg3Yy0yMy41LDAtNDIuNi0xOS4xLTQyLjYtNDIuNlY4OGwxODYuNSwxOTlINzc3eiIgc3R5bGU9ImZpbGw6IzU2NTY1NiI+PC9wYXRoPjwvZz48L3N2Zz4KICA=" width="16" height="16" alt="<?php _e('Duplicate', 'ywdpd') ?>"></span>
			<span class="ywdpd-remove" data-section="<?php echo $key ?>"></span>
		</div>

		<div class="section-body">
			<table>
				<!-- Rule name -->
				<tr>
					<th>
						<?php _e( 'Description', 'ywdpd' ); ?>
					</th>
					<td>
						<input type="text" name="<?php echo $suffix_name . '[description]' ?>" id="<?php echo $suffix_id . '[description]' ?>" value="<?php echo $description ?>">
					</td>
				</tr>
				<!-- DISCOUNT MODE -->
				<tr>
					<th>
						<?php _e( 'Discount mode', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[discount_mode]" id="<?php echo $suffix_id . '[discount_mode]' ?>" class="yith-ywdpd-eventype-select" data-field="discount_mode">
							<?php foreach ( $pricing_rules_options['discount_mode'] as $key_type => $type ): ?>
								<option value="<?php echo $key_type ?>"><?php echo $type ?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>

				<!-- SHOW Table Price -->
				<tr class="deps-discount_mode" data-type="bulk">
					<th>
						<?php _e( 'Show Price Table', 'ywdpd' ); ?>
					</th>
					<td>
						<input type="checkbox" name="<?php echo $suffix_name . "[show_table_price]" ?>" id="<?php echo $suffix_id . "[show_table_price]" ?>" value="1" />
					</td>
				</tr>

				<!-- SHOW IN LOOP -->
				<tr class="deps-discount_mode" data-type="bulk">
					<th>
						<?php _e( 'Show in loop', 'ywdpd' ); ?>
					</th>
					<td>
						<input type="checkbox" name="<?php echo $suffix_name . "[show_in_loop]" ?>" id="<?php echo $suffix_id . "[show_in_loop]" ?>" value="1" />
					</td>
				</tr>
				<!-- QUANTITY BASED -->
				<tr class="deps-discount_mode" data-type="bulk;special_offer">
					<th>
						<?php _e( 'Quantity Based', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[quantity_based]" id="<?php echo $suffix_id . '[quantity_based]' ?>">
							<?php foreach ( $pricing_rules_options['quantity_based'] as $key_type => $type ): ?>
								<option value="<?php echo $key_type ?>"><?php echo $type ?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>

				<!-- DISCOUNT SCHEDULE -->
				<tr>
					<th>
						<?php _e( 'Discount Schedule', 'ywdpd' ); ?>
					</th>
					<td>
						<table style="width: auto">
							<tr>
								<td><?php _e( 'From', 'ywdpd' ) ?></td>
								<td>
									<input type="" class="datepicker" name="<?php echo $suffix_name ?>[schedule_from]" id="<?php echo $suffix_id . '[schedule_from]' ?>" value="" />
								</td>
								<td><?php _e( 'To', 'ywdpd' ) ?></td>
								<td>
									<input type="" class="datepicker" name="<?php echo $suffix_name ?>[schedule_to]" id="<?php echo $suffix_id . '[schedule_to]' ?>" value="" />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- USER CONDITIONS -->
				<tr>
					<th>
						<?php _e( 'User Status', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[user_rules]" id="<?php echo $suffix_id . '[user_rules]' ?>" class="yith-ywdpd-eventype-select" data-field="user_rules">
							<?php foreach ( $pricing_rules_options['user_rules'] as $key_type => $type ): ?>
								<option value="<?php echo $key_type ?>"><?php echo $type ?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>
				<tr class="deps-user_rules" data-type="role_list">
					<th>
						<?php _e( 'Select Roles', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[user_rules_role_list][]" multiple="multiple" id="<?php echo $suffix_id . '[user_rules_role_list]' ?>" data-placeholder="<?php _e( 'Select a role', 'ywdpd' ) ?>">
							<?php foreach ( $rules as $key_rule => $rule ): ?>
								<option value="<?php echo $key_rule ?>"><?php echo $rule ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr class="deps-user_rules" data-type="role_list_excluded">
					<th>
						<?php _e( 'Select roles to exclude', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[user_rules_role_list_excluded][]" multiple="multiple" id="<?php echo $suffix_id . '[user_rules_role_list_excluded]' ?>" data-placeholder="<?php _e( 'Select a role', 'ywdpd' ) ?>">
							<?php foreach ( $rules as $key_rule => $rule ): ?>
								<option value="<?php echo $key_rule ?>"><?php echo $rule ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr class="deps-user_rules" data-type="customers_list">
					<th>
						<?php _e( 'Select Customers', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[user_rules_customers_list][]" class="chosen ajax_chosen_select_customers" multiple="multiple" id="<?php echo $suffix_id . '[user_rules_customers_list]' ?>" data-placeholder="<?php _e( 'Search for a customer', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-user_rules" data-type="customers_list_excluded">
					<th>
						<?php _e( 'Select customers to exclude', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[user_rules_customers_list_excluded][]" class="chosen ajax_chosen_select_customers" multiple="multiple" id="<?php echo $suffix_id . '[user_rules_customers_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a customer', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>

				<?php
				do_action( 'yit_ywdpd_pricing_rules_panel_after_user_status', $suffix_name, $suffix_id );
				?>
				<!-- APPLY TO -->
				<tr>
					<th>
						<?php _e( 'Apply to', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to]" id="<?php echo $suffix_id . '[apply_to]' ?>" class="yith-ywdpd-eventype-select" data-field="apply_to">
							<?php foreach ( $pricing_rules_options['apply_to'] as $key_type => $type ): ?>
								<option value="<?php echo $key_type ?>"><?php echo $type ?></option>
							<?php endforeach ?>
						</select>
						<span class="desc-inline"><?php _e( 'Select the products to which applying the rule', 'ywdpd') ?></span>
					</td>
				</tr>
				<tr class="deps-apply_to" data-type="products_list">
					<th>
						<?php _e( 'Select Products', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to_products_list][]" class="chosen ajax_chosen_select_products" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_products_list]' ?>" data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_to" data-type="products_list_excluded">
					<th>
						<?php _e( 'Select Products', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to_products_list_excluded][]" class="chosen ajax_chosen_select_products" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_products_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_to" data-type="categories_list">
					<th>
						<?php _e( 'Categories', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to_categories_list][]" class="chosen ajax_chosen_select_categories" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_categories_list]' ?>" data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_to" data-type="categories_list_excluded">
					<th>
						<?php _e( 'Categories', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to_categories_list_excluded][]" class="chosen ajax_chosen_select_categories" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_categories_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_to" data-type="tags_list">
					<th>
						<?php _e( 'Tags', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to_tags_list][]" class="chosen ajax_chosen_select_tags" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_tags_list]' ?>" data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_to" data-type="tags_list_excluded">
					<th>
						<?php _e( 'Tags', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_to_tags_list_excluded][]" class="chosen ajax_chosen_select_tags" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_tags_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<?php if ( defined( 'YITH_WPV_PREMIUM' ) ): ?>

					<tr class="deps-apply_to" data-type="vendor_list">
						<th>
							<?php _e( 'Vendors', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_to_vendors_list][]" class="chosen ajax_chosen_select_vendors" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_vendors_list]' ?>" data-placeholder="<?php _e( 'Search for a vendor', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

					<tr class="deps-apply_to" data-type="vendor_list_excluded">
						<th>
							<?php _e( 'Vendors', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_to_vendors_list_excluded][]" class="chosen ajax_chosen_select_vendors" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_vendors_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a vendor', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

				<?php endif ?>

				<?php if ( defined( 'YITH_WCBR_PREMIUM_INIT' ) ): ?>

					<tr class="deps-apply_to" data-type="brand_list">
						<th>
							<?php _e( 'Brands', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_to_brands_list][]" class="chosen ajax_chosen_select_brands" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_brands_list]' ?>" data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

					<tr class="deps-apply_to" data-type="brand_list_excluded">
						<th>
							<?php _e( 'Brands', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_to_brands_list_excluded][]" class="chosen ajax_chosen_select_brands" multiple="multiple" id="<?php echo $suffix_id . '[apply_to_brands_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

				<?php endif ?>
				<!-- APPLY ADJUSTMENT -->
				<tr class="deps-discount_mode" data-type="bulk;special_offer" data-rel="apply_adjustment">
					<th>
						<?php _e( 'Apply adjustment to', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment]" id="<?php echo $suffix_id . '[apply_adjustment]' ?>" class="yith-ywdpd-eventype-select" data-field="apply_adjustment">
							<?php foreach ( $pricing_rules_options['apply_adjustment'] as $key_type => $type ): ?>
								<option value="<?php echo $key_type ?>"><?php echo $type ?></option>
							<?php endforeach ?>
						</select>
						<span class="desc-inline"><?php _e( 'Select the products to which applying the adjustments' ) ?></span>
					</td>
				</tr>
				<tr class="deps-apply_adjustment" data-type="products_list">
					<th>
						<?php _e( 'Select Products', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment_products_list][]" class="chosen ajax_chosen_select_products" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_products_list]' ?>" data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
						</select>

					</td>
				</tr>
				<tr class="deps-apply_adjustment" data-type="products_list_excluded">
					<th>
						<?php _e( 'Select Products', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment_products_list_excluded][]" class="chosen ajax_chosen_select_products" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_products_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_adjustment" data-type="categories_list">
					<th>
						<?php _e( 'Categories', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment_categories_list][]" class="chosen ajax_chosen_select_categories" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_categories_list]' ?>" data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_adjustment" data-type="categories_list_excluded">
					<th>
						<?php _e( 'Categories', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment_categories_list_excluded][]" class="chosen ajax_chosen_select_categories" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_categories_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_adjustment" data-type="tags_list">
					<th>
						<?php _e( 'Tags', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment_tags_list][]" class="chosen ajax_chosen_select_tags" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_tags_list]' ?>" data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<tr class="deps-apply_adjustment" data-type="tags_list_excluded">
					<th>
						<?php _e( 'Tags', 'ywdpd' ); ?>
					</th>
					<td>
						<select name="<?php echo $suffix_name ?>[apply_adjustment_tags_list_excluded][]" class="chosen ajax_chosen_select_tags" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_tags_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
						</select>
					</td>
				</tr>
				<?php if ( defined( 'YITH_WPV_PREMIUM' ) ): ?>

					<tr class="deps-apply_adjustment" data-type="vendor_list">
						<th>
							<?php _e( 'Vendors', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_adjustment_vendor_list][]" class="chosen ajax_chosen_select_vendors" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_vendor_list]' ?>" data-placeholder="<?php _e( 'Search for a vendor', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

					<tr class="deps-apply_adjustment" data-type="vendor_list_excluded">
						<th>
							<?php _e( 'Vendors', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_adjustment_vendor_list_excluded][]" class="chosen ajax_chosen_select_vendors" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_vendor_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a vendor', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

				<?php endif ?>
				<?php if ( defined( 'YITH_WCBR_PREMIUM_INIT' ) ): ?>

					<tr class="deps-apply_adjustment" data-type="brand_list">
						<th>
							<?php _e( 'Brands', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_adjustment_brand_list][]" class="chosen ajax_chosen_select_brands" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_brand_list]' ?>" data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

					<tr class="deps-apply_adjustment" data-type="brand_list_excluded">
						<th>
							<?php _e( 'Brands', 'ywdpd' ); ?>
						</th>
						<td>
							<select name="<?php echo $suffix_name ?>[apply_adjustment_brand_list_excluded][]" class="chosen ajax_chosen_select_brands" multiple="multiple" id="<?php echo $suffix_id . '[apply_adjustment_brand_list_excluded]' ?>" data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
							</select>
						</td>
					</tr>

				<?php endif ?>
				<tr class="deps-discount_mode" data-type="bulk">
					<th>
						<?php _e( 'Discount Rules', 'ywdpd' ); ?>
					</th>
					<td>
						<table class="discount-rules">
							<tr>
								<th><?php _e( 'Minimum Quantity', 'ywdpd' ) ?></th>
								<th><?php _e( 'Maximum Quantity', 'ywdpd' ) ?></th>
								<th><?php _e( 'Type of Discount', 'ywdpd' ) ?></th>
								<th><?php _e( 'Discount Amount', 'ywdpd' ) ?></th>
								<th></th>
							</tr>
							<tr data-index="1">
								<td>
									<input type="text" name="<?php echo $suffix_name . '[rules][1][min_quantity]' ?>" id="<?php echo $suffix_id . '[rules][1][min_quantity]' ?>" value="" placeholder="<?php _e( 'e.g. 5', 'ywdpd' ) ?>">
								</td>
								<td>
									<input type="text" name="<?php echo $suffix_name . '[rules][1][max_quantity]' ?>" id="<?php echo $suffix_id . '[rules][1][max_quantity]' ?>" value="" placeholder="<?php _e( 'e.g. 10 - * for unlimited items', 'ywdpd' ) ?>">
								</td>
								<td>
									<select name="<?php echo $suffix_name . '[rules][1][type_discount]' ?>" id="<?php echo $suffix_id . '[rules][1][type_discount]' ?>">
										<?php foreach ( $pricing_rules_options['type_of_discount'] as $key => $type ): ?>
											<option value="<?php echo $key ?>"><?php echo $type ?></option>
										<?php endforeach ?>
									</select>
								</td>
								<td>
									<input type="text" name="<?php echo $suffix_name . '[rules][1][discount_amount]' ?>" id="<?php echo $suffix_id . '[rules][1][discount_amount]' ?>" value="" placeholder="<?php _e( 'e.g. 50', 'ywdpd' ) ?>">
								</td>
								<td><span class="add-row"></span><span class="remove-row hide-remove"></span></td>
							</tr>
						</table>
					</td>
				</tr>


				<!-- SPECIAL OFFERS DISCOUNT RULES -->
				<tr class="deps-discount_mode" data-type="special_offer">
					<th>
						<?php _e( 'Special Offer Rules', 'ywdpd' ); ?>
					</th>

					<td>
						<table class="special-offers-rules">
							<tr>
								<th><?php _e( 'Purchase', 'ywdpd' ) ?></th>
								<th><?php _e( 'Receive', 'ywdpd' ) ?></th>
								<th><?php _e( 'Type of Discount', 'ywdpd' ) ?></th>
								<th><?php _e( 'Discount Amount', 'ywdpd' ) ?></th>
								<th><?php _e( 'Repeat', 'ywdpd' ) ?></th>
							</tr>
							<tr>
								<td>
									<input type="text" name="<?php echo $suffix_name . "[so-rule][purchase]" ?>" id="<?php echo $suffix_id . "[so-rule][purchase]" ?>" value="" placeholder="<?php _e( 'e.g. 5', 'ywdpd' ) ?>">
								</td>
								<td>
									<input type="text" name="<?php echo $suffix_name . "[so-rule][receive]" ?>" id="<?php echo $suffix_id . "[so-rule][receive]" ?>" value="" placeholder="<?php _e( 'e.g. 10 - * for unlimited items', 'ywdpd' ) ?>">
								</td>
								<td>
									<select name="<?php echo $suffix_name . "[so-rule][type_discount]" ?>" id="<?php echo $suffix_id . "[so-rule][type_discount]" ?>">
										<?php foreach ( $pricing_rules_options['type_of_discount'] as $key_type => $type ): ?>
											<option value="<?php echo $key_type ?>"><?php echo $type ?></option>
										<?php endforeach ?>
									</select>
								</td>
								<td>
									<input type="text" name="<?php echo $suffix_name . "[so-rule][discount_amount]" ?>" id="<?php echo $suffix_id . "[so-rule][discount_amount]" ?>" value="" placeholder="<?php _e( 'e.g. 50', 'ywdpd' ) ?>">
								</td>
								<td>
									<input type="checkbox" name="<?php echo $suffix_name . "[so-rule][repeat]" ?>" id="<?php echo $suffix_id . "[so-rule][repeat]" ?>" value="1" />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="deps-discount_mode" data-type="bulk;special_offer">
					<td colspan="2">
						<table>
							<tr>
								<th>
									<?php
									//@since 1.1.0
									_e( 'Notes shown on "Apply to" products', 'ywdpd' ); ?>
								</th>

								<td>
									<textarea name="<?php echo $suffix_name . "[table_note_apply_to]" ?>" id="<?php echo $suffix_id . "[table_note_apply_to]" ?>" value="" placeholder=""></textarea>
								</td>
								<th>
									<?php
									//@since 1.1.0
									_e( 'Notes shown on "Apply adjustment to" products', 'ywdpd' ); ?>
								</th>

								<td>
									<textarea name="<?php echo $suffix_name . "[table_note_adjustment_to]" ?>" id="<?php echo $suffix_id . "[table_note_adjustment_to]" ?>" value="" placeholder=""></textarea>
								</td>
							</tr>
							<tr>
								<th>
									<?php
									//@since 1.1.0
									_e( 'Notes shown in the discount table', 'ywdpd' ); ?>
								</th>

								<td>
									<textarea name="<?php echo $suffix_name . "[table_note]" ?>" id="<?php echo $suffix_id . "[table_note]" ?>" value="" placeholder=""></textarea>
								</td>

							</tr>
						</table>
					</td>
				</tr>

				<!-- WHEN APPLY THIS DISCOUNT -->
				<tr class="deps-discount_mode" data-type="bulk;special_offer">
					<th><?php _e( 'Apply this discount', 'ywdpd' ) ?></th>
					<td>
						<table>
							<tr>
								<th><?php _e( 'With other rules <br><small style="font-weight:normal">Only one quantity discount per product can be applied at the same time</small>', 'ywdpd' ) ?></th>
								<td>
									<input type="checkbox" value="1" name="<?php echo $suffix_name ?>[apply_with_other_rules]" id="<?php echo $suffix_name ?>[apply_with_other_rules]" />
								</td>
								<th><?php _e( 'Even if the product is on sale', 'ywdpd' ) ?></th>
								<td>
									<input type="checkbox" value="1" name="<?php echo $suffix_name ?>[apply_on_sale]" id="<?php echo $suffix_name ?>[apply_on_sale]" <?php echo ( isset( $db_value[ $key ]['apply_on_sale'] ) && $db_value[ $key ]['apply_on_sale'] == 1 ) ? 'checked' : '' ?>/>
								</td>
							</tr>
							<tr>
								<th><?php _e( 'Disable with other coupon', 'ywdpd' ) ?></th>
								<td colspan="3">
									<input type="checkbox" value="1" name="<?php echo $suffix_name ?>[disable_with_other_coupon]" id="<?php echo $suffix_name ?>[disable_with_other_coupon]" />
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input style="float: left; margin-right: 10px;" class="button-primary" type="submit" value="<?php _e( 'Save changes', 'ywdpd' ) ?>" />
					</td>
				</tr>
			</table>
		</div>

	</div>
</form>
</div>