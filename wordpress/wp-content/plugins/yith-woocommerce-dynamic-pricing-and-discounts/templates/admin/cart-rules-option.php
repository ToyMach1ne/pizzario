<?php
if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWDPD_VERSION' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template For Cart Rules
 *
 * @package YITH WooCommerce Dynamic Pricing and Discounts
 * @since   1.0.0
 * @author  Yithemes
 */

global $wp_roles;

$id                 = 'yit_ywdpd_options_cart-rules';
$name               = 'yit_ywdpd_options[cart-rules]';
$cart_rules_options = YITH_WC_Dynamic_Pricing()->cart_rules_options;
$rules              = YITH_WC_Dynamic_Pricing_Helper()->get_roles();

?>
<div id="<?php echo $id ?>-container" class="ywdpd-sections-group ui-sortable">
	<?php if ( is_array( $db_value ) ) :
		foreach ( $db_value as $key => $value ) :
			$suffix_id = $id . '[' . $key . ']';
			$suffix_name = $name . '[' . $key . ']';
			?>
			<div class="ywdpd-section-handle" data-key="<?php echo $key ?>">
				<form class="form-cart-rules" id="form-<?php echo $key ?>" method="post">
					<input type="hidden" name="ywdpd-action" value="save-options" />
					<input type="hidden" name="section-key" value="<?php echo $key ?>" />
					<div class="ywdpd-section ywdpd-select-wrapper section-<?php echo $key ?>">

						<div class="ywdpd-section-head">
						<span
							class="ywdpd-active <?php echo ( $db_value[ $key ]['active'] == 'yes' ) ? 'activated' : '' ?>"
							data-section="<?php echo $key ?>"></span>
							<?php echo $db_value[ $key ]['description'] ?>

							<input type="hidden" name="<?php echo $suffix_name . '[active]' ?>"
							       id="<?php echo $suffix_id . '[active]' ?>" class="active-hidden-field"
							       value="<?php echo $db_value[ $key ]['active'] ?>">
                            <span class="ywdpd-clone" data-section="<?php echo $key ?>"><img src="data:image/svg+xml;base64,CjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDEwMDAgMTAwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwMCAxMDAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPG1ldGFkYXRhPiBTdmcgVmVjdG9yIEljb25zIDogaHR0cDovL3d3dy5vbmxpbmV3ZWJmb250cy5jb20vaWNvbiA8L21ldGFkYXRhPgogIDxnPjxwYXRoIGQ9Ik0zNzIuMiw0NzguN2gtMjEzdjQyLjZoMjEzVjQ3OC43eiBNMTU5LjEsNzc3aDQ2OC43di00Mi42SDE1OS4xVjc3N3ogTTM3Mi4yLDM1MC45aC0yMTN2NDIuNmgyMTNWMzUwLjl6IE0xNTkuMSw2NDkuMWg0NjguN3YtNDIuNkgxNTkuMVY2NDkuMXogTTc3MC43LDQyMWwtMjYzLTI4My4yaC05Mi45Yy0xNTUuOSwwLTMxOS42LDAtMzE5LjYsMEM0OC4yLDEzNy44LDEwLDE3NiwxMCwyMjN2NjgxLjdjMCw0Ny4xLDM4LjIsODUuMiw4NS4yLDg1LjJoNTk2LjVjNDcuMSwwLDg1LjItMzguMiw4NS4yLTg1LjJWNDM2LjFsLTcuNS03LjVMNzcwLjcsNDIxeiBNNTIxLjMsMjE1LjhsMTg2LjUsMTk5SDU2My45Yy0yMy41LDAtNDIuNi0xOS4xLTQyLjYtNDIuNlYyMTUuOHogTTczNC4zLDkwNC44YzAsMjMuNS0xOS4xLDQyLjYtNDIuNiw0Mi42SDk1LjJjLTIzLjUsMC00Mi42LTE5LjEtNDIuNi00Mi42VjIyM2MwLTIzLjUsMTkuMS00Mi42LDQyLjYtNDIuNmgzODMuNXYxOTEuN2MwLDQ3LjEsMzguMiw4NS4yLDg1LjIsODUuMmgxNzAuNFY5MDQuOHogTTk4Mi41LDMwMC43bDEuMy03LjVMNzIwLjgsMTBoLTkzYy0xNTUuOSwwLTMxOS42LDAtMzE5LjYsMEMyNjEuMiwxMCwyMjMsNDguMiwyMjMsOTUuMmg0Mi42YzAtMjMuNSwxOS4xLTQyLjYsNDIuNi00Mi42aDM4My41djE5MS43YzAsNDcuMSwzOC4yLDg1LjIsODUuMiw4NS4yaDE3MC40Vjc3N2MwLDIzLjUtMTkuMSw0Mi42LTQyLjYsNDIuNmgtODUuMnY0Mi42aDg1LjJjNDcuMSwwLDg1LjItMzguMiw4NS4yLTg1LjJWMzA4LjNMOTgyLjUsMzAwLjd6IE03NzcsMjg3Yy0yMy41LDAtNDIuNi0xOS4xLTQyLjYtNDIuNlY4OGwxODYuNSwxOTlINzc3eiIgc3R5bGU9ImZpbGw6IzU2NTY1NiI+PC9wYXRoPjwvZz48L3N2Zz4KICA=" width="16" height="16" alt="<?php _e('Duplicate', 'ywdpd') ?>"></span>
							<span class="ywdpd-remove" data-section="<?php echo $key ?>"></span>
						</div>

						<div class="section-body">
							<table>
								<!-- DESCRIPTION -->
								<tr>
									<th>
										<label
											for="<?php echo $suffix_id . '[description]' ?>"><?php _e( 'Description', 'ywdpd' ); ?></label>
									</th>
									<td>
										<input type="text" name="<?php echo $suffix_name . '[description]' ?>"
										       id="<?php echo $suffix_id . '[description]' ?>"
										       value="<?php echo $db_value[ $key ]['description'] ?>">
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
												<td><input type="" class="datepicker"
												           name="<?php echo $suffix_name ?>[schedule_from]"
												           id="<?php echo $suffix_id . '[schedule_from]' ?>"
												           value="<?php echo $db_value[ $key ]['schedule_from'] ?>" />
												</td>
												<td><?php _e( 'To', 'ywdpd' ) ?></td>
												<td><input type="" class="datepicker"
												           name="<?php echo $suffix_name ?>[schedule_to]"
												           id="<?php echo $suffix_id . '[schedule_to]' ?>"
												           value="<?php echo $db_value[ $key ]['schedule_to'] ?>" />
												</td>
											</tr>
										</table>
									</td>
								</tr>

								<!-- DISCOUNT COMBINED -->
								<tr>
									<th>
										<label
											for="<?php echo $suffix_id ?>[discount_combined]"><?php _e( 'Discount Combined', 'ywdpd' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="<?php echo $suffix_name ?>[discount_combined]"
										       id="<?php echo $suffix_id ?>[discount_combined]"
										       value="1" <?php echo isset( $db_value[ $key ]['discount_combined'] ) ? checked( $db_value[ $key ]['discount_combined'], 1, false ) : ''; ?>>
										<span
											class="description"><?php _e( 'Choose if this cart discount must be combined with other coupon', 'ywdpd' ) ?></span>
									</td>
								</tr>


								<tr class="deps-discount_mode">
									<th>
										<?php _e( 'Discount Rules', 'ywdpd' ); ?>
									</th>
									<td>
										<table class="discount-rules">
											<tr>
												<th><?php _e( 'Rule', 'ywdpd' ) ?></th>
												<th><?php _e( 'Value', 'ywdpd' ) ?></th>
												<th></th>
											</tr>
											<?php

											$limit = isset( $db_value[ $key ]['rules'] ) ? count( $db_value[ $key ]['rules'] ) : 1;
											for ( $i = 1; $i <= $limit; $i ++ ):
												$hide_first_remove = ( $i == 1 ) ? ' hide-remove' : '';
												?>
												<tr data-index="<?php echo $i ?>" class="ywdpd-select-wrapper">
													<td>
														<select
															name="<?php echo $suffix_name . "[rules][{$i}][rules_type]" ?>"
															id="<?php echo $suffix_id . "[rules][{$i}][rules_type]" ?>"
															class="yith-ywdpd-eventype-select" data-field="rules_type">
															<?php foreach ( $cart_rules_options['rules_type'] as $key_c => $type_c ): ?>
																<?php if ( isset( $type_c['label'] ) ): ?>
																	<optgroup label="<?php echo $type_c['label'] ?>">
																		<?php foreach ( $type_c['options'] as $key_opt => $value_opt ): ?>
																			<option
																				value="<?php echo $key_opt ?>" <?php ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type'] ) ) ? selected( $db_value[ $key ]['rules'][ $i ]['rules_type'], $key_opt ) : '' ?> ><?php echo $value_opt ?></option>
																		<?php endforeach ?>
																	</optgroup>
																<?php endif ?>
															<?php endforeach ?>
														</select>
													</td>
													<td>
														<table>
															<tr class="deps-rules_type" data-type="role_list">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_role_list][]" ?>"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_role_list]" ?>"
																		data-placeholder="<?php _e( 'Select a role', 'ywdpd' ) ?>">
																		<?php foreach ( $rules as $key_rule => $rule_value ): ?>
																			<option
																				value="<?php echo $key_rule ?>" <?php isset( $db_value[ $key ]['rules'][ $i ]['rules_type_role_list'] ) ? selected( in_array( $key_rule, $db_value[ $key ]['rules'][ $i ]['rules_type_role_list'] ) ) : '' ?> ><?php echo $rule_value ?></option>
																		<?php endforeach; ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="role_list_excluded">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_role_list_excluded][]" ?>"
																		multiple="multiple"
																		id="<?php echo $suffix_name . "[rules][{$i}][rules_type_role_list_excluded][]" ?>"
																		data-placeholder="<?php _e( 'Select a role', 'ywdpd' ) ?>">
																		<?php foreach ( $rules as $key_rule => $rule ):
																			?>
																			<option
																				value="<?php echo $key_rule ?>" <?php isset( $db_value[ $key ]['rules'][ $i ]['rules_type_role_list_excluded'] ) ? selected( in_array( $key_rule, $db_value[ $key ]['rules'][ $i ]['rules_type_role_list_excluded'] ) ) : '' ?>><?php echo $rule ?></option>
																		<?php endforeach; ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="customers_list">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_customers_list][]" ?>"
																		class="chosen ajax_chosen_select_customers"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_customers_list]" ?>"
																		data-placeholder="<?php _e( 'Search for a customer', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_customers_list'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_customers_list'] as $customer_id ):
																				$user = get_user_by( 'id', $customer_id );
																				?>
																				<option value="<?php echo $customer_id ?>"
																				        selected="selected"><?php echo $user->data->user_email ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="customers_list_excluded">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_customers_list_excluded][]" ?>"
																		class="chosen ajax_chosen_select_customers"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_customers_list_excluded]" ?>"
																		data-placeholder="<?php _e( 'Search for a customer', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_customers_list_excluded'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_customers_list_excluded'] as $customer_id ):
																				$user = get_user_by( 'id', $customer_id );
																				?>
																				<option value="<?php echo $customer_id ?>"
																				        selected="selected"><?php echo $user->data->user_email ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="num_of_orders">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_num_of_orders]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_num_of_orders]" ?>"
																	       placeholder="<?php _e( 'Minimum number of orders required', 'ywdpd' ) ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_num_of_orders'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_num_of_orders'] : '' ?>" />
																</td>
															</tr>


															<tr class="deps-rules_type" data-type="amount_spent">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_amount_spent]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_amount_spent]" ?>"
																	       placeholder="<?php _e( 'Minimum past expense required', 'ywdpd' ) ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_amount_spent'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_amount_spent'] : '' ?>">
																</td>
															</tr>


															<tr class="deps-rules_type" data-type="products_list">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_products_list][]" ?>"
																		class="chosen ajax_chosen_select_products"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_products_list]" ?>"
																		data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_products_list'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_products_list'] as $product_id ):
																				$product = wc_get_product( $product_id );
																				if ( ! $product || ! $product->is_purchasable() ) {
																					continue;
																				}
																				?>
																				<option value="<?php echo $product_id ?>"
																				        selected="selected"><?php echo $product->get_formatted_name() ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>


															<tr class="deps-rules_type" data-type="products_list_and">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_products_list_and][]" ?>"
																		class="chosen ajax_chosen_select_products"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_products_list_and]" ?>"
																		data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_products_list_and'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_products_list_and'] as $product_id ):
																				$product = wc_get_product( $product_id );
																				if ( ! $product || ! $product->is_purchasable() ) {
																					continue;
																				}
																				?>
																				<option value="<?php echo $product_id ?>"
																				        selected="selected"><?php echo $product->get_formatted_name() ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="products_list_excluded">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_products_list_excluded][]" ?>"
																		class="chosen ajax_chosen_select_products"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_products_list_excluded]" ?>"
																		data-placeholder="<?php _e( 'Search for a product', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_products_list_excluded'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_products_list_excluded'] as $product_id ):
																				$product = wc_get_product( $product_id );
																				if ( ! $product || ! $product->is_purchasable() ) {
																					continue;
																				}
																				?>
																				<option value="<?php echo $product_id ?>"
																				        selected="selected"><?php echo $product->get_formatted_name() ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>


															<tr class="deps-rules_type" data-type="categories_list">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_categories_list][]" ?>"
																		class="chosen ajax_chosen_select_categories"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_categories_list]" ?>"
																		data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_categories_list'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_categories_list'] as $term_id ):
																				$current_category = get_term_by( 'term_id', $term_id, 'product_cat' );
																				?>
																				<option value="<?php echo $term_id ?>"
																				        selected="selected"><?php echo $current_category->name ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="categories_list_and">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_categories_list_and][]" ?>"
																		class="chosen ajax_chosen_select_categories"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_categories_list_and]" ?>"
																		data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_categories_list_and'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_categories_list_and'] as $term_id ):
																				$current_category = get_term_by( 'term_id', $term_id, 'product_cat' );
																				?>
																				<option value="<?php echo $term_id ?>"
																				        selected="selected"><?php echo $current_category->name ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>


															<tr class="deps-rules_type"
															    data-type="categories_list_excluded">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_categories_list_excluded][]" ?>"
																		class="chosen ajax_chosen_select_categories"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_categories_list_excluded]" ?>"
																		data-placeholder="<?php _e( 'Search for a category', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_categories_list_excluded'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_categories_list_excluded'] as $term_id ):
																				$current_category = get_term_by( 'term_id', $term_id, 'product_cat' );
																				?>
																				<option value="<?php echo $term_id ?>"
																				        selected="selected"><?php echo $current_category->name ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="tags_list">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_tags_list][]" ?>"
																		class="chosen ajax_chosen_select_tags"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_tags_list]" ?>"
																		data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_tags_list'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_tags_list'] as $term_id ):
																				$current_tag = get_term_by( 'term_id', $term_id, 'product_tag' );
																				?>
																				<option value="<?php echo $term_id ?>"
																				        selected="selected"><?php echo $current_tag->name ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="tags_list_and">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_tags_list_and][]" ?>"
																		class="chosen ajax_chosen_select_tags"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_tags_list_and]" ?>"
																		data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_tags_list_and'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_tags_list_and'] as $term_id ):
																				$current_tag = get_term_by( 'term_id', $term_id, 'product_tag' );
																				?>
																				<option value="<?php echo $term_id ?>"
																				        selected="selected"><?php echo $current_tag->name ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>


															<tr class="deps-rules_type" data-type="tags_list_excluded">
																<td>
																	<select
																		name="<?php echo $suffix_name . "[rules][{$i}][rules_type_tags_list_excluded][]" ?>"
																		class="chosen ajax_chosen_select_tags"
																		multiple="multiple"
																		id="<?php echo $suffix_id . "[rules][{$i}][rules_type_tags_list_excluded]" ?>"
																		data-placeholder="<?php _e( 'Search for a tag', 'ywdpd' ) ?>">
																		<?php
																		if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_tags_list_excluded'] ) ):
																			foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_tags_list_excluded'] as $term_id ):
																				$current_tag = get_term_by( 'term_id', $term_id, 'product_tag' );
																				?>
																				<option value="<?php echo $term_id ?>"
																				        selected="selected"><?php echo $current_tag->name ?></option>
																			<?php endforeach;
																		endif ?>
																	</select>
																</td>
															</tr>
															<?php if ( defined( 'YITH_WCBR_PREMIUM_INIT' ) ): ?>
                                                                <tr class="deps-rules_type" data-type="brand_list">
                                                                    <td>
                                                                        <select
                                                                                name="<?php echo $suffix_name . "[rules][{$i}][rules_type_brand_list][]" ?>"
                                                                                class="chosen ajax_chosen_select_brands"
                                                                                multiple="multiple"
                                                                                id="<?php echo $suffix_id . '[rules][{$i}][rules_type_brands_list]' ?>"
                                                                                data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
																			<?php
																			if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_brand_list'] ) ):
																				foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_brand_list'] as $brand_id ):
																					$current_brand = get_term_by( 'term_id', $brand_id, YITH_WCBR::$brands_taxonomy );
																					?>
                                                                                    <option value="<?php echo $brand_id ?>"
                                                                                            selected="selected"><?php echo $current_brand->name ?></option>
																				<?php endforeach;
																			endif ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>

                                                                <tr class="deps-rules_type" data-type="brand_list_and">
                                                                    <td>
                                                                        <select
                                                                                name="<?php echo $suffix_name . "[rules][{$i}][rules_type_brand_list_and][]" ?>"
                                                                                class="chosen ajax_chosen_select_brands"
                                                                                multiple="multiple"
                                                                                id="<?php echo $suffix_id . "[rules][{$i}][rules_type_brand_list_and]" ?>"
                                                                                data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
																			<?php
																			if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_brand_list_and'] ) ):
																				foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_brand_list_and'] as $term_id ):
																					$current_brand = get_term_by( 'term_id', $term_id, YITH_WCBR::$brands_taxonomy );
																					?>
                                                                                    <option value="<?php echo $term_id ?>"
                                                                                            selected="selected"><?php echo $current_brand->name ?></option>
																				<?php endforeach;
																			endif ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>


                                                                <tr class="deps-rules_type"
                                                                    data-type="brand_list_excluded">
                                                                    <td>
                                                                        <select
                                                                                name="<?php echo $suffix_name . "[rules][{$i}][rules_type_brand_list_excluded][]" ?>"
                                                                                class="chosen ajax_chosen_select_brands"
                                                                                multiple="multiple"
                                                                                id="<?php echo $suffix_id . '[rules][{$i}][rules_type_brands_list_excluded]' ?>"
                                                                                data-placeholder="<?php _e( 'Search for a brand', 'ywdpd' ) ?>">
																			<?php
																			if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_brand_list_excluded'] ) ):
																				foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_brand_list_excluded'] as $brand_id ):
																					$current_brand = get_term_by( 'term_id', $brand_id, YITH_WCBR::$brands_taxonomy );
																					?>
                                                                                    <option value="<?php echo $brand_id ?>"
                                                                                            selected="selected"><?php echo $current_brand->name ?></option>
																				<?php endforeach;
																			endif ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>

															<?php endif ?>


															<?php if ( defined( 'YITH_WPV_PREMIUM' ) ): ?>
																<tr class="deps-rules_type" data-type="vendor_list">
																	<td>
																		<select
																			name="<?php echo $suffix_name . "[rules][{$i}][rules_type_vendor_list][]" ?>"
																			class="chosen ajax_chosen_select_vendors"
																			multiple="multiple"
																			id="<?php echo $suffix_id . '[rules][{$i}][rules_type_vendors_list]' ?>"
																			data-placeholder="<?php _e( 'Search for a vendor', 'ywdpd' ) ?>">
																			<?php
																			if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_vendor_list'] ) ):
																				foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_vendor_list'] as $vendor_id ):
																					$current_vendor = yith_get_vendor( $vendor_id, 'vendor' );
																					?>
																					<option value="<?php echo $vendor_id ?>"
																					        selected="selected"><?php echo $current_vendor->name ?></option>
																				<?php endforeach;
																			endif ?>
																		</select>
																	</td>
																</tr>


																<tr class="deps-rules_type"
																    data-type="vendor_list_excluded">
																	<td>
																		<select
																			name="<?php echo $suffix_name . "[rules][{$i}][rules_type_vendor_list_excluded][]" ?>"
																			class="chosen ajax_chosen_select_vendors"
																			multiple="multiple"
																			id="<?php echo $suffix_id . '[rules][{$i}][rules_type_vendors_list_excluded]' ?>"
																			data-placeholder="<?php _e( 'Search for a vendor', 'ywdpd' ) ?>">
																			<?php
																			if ( ! empty( $db_value[ $key ]['rules'][ $i ]['rules_type_vendor_list_excluded'] ) ):
																				foreach ( $db_value[ $key ]['rules'][ $i ]['rules_type_vendor_list_excluded'] as $vendor_id ):
																					$current_vendor = yith_get_vendor( $vendor_id, 'vendor' );
																					?>
																					<option value="<?php echo $vendor_id ?>"
																					        selected="selected"><?php echo $current_vendor->name ?></option>
																				<?php endforeach;
																			endif ?>
																		</select>
																	</td>
																</tr>

															<?php endif ?>



															<tr class="deps-rules_type" data-type="sum_item_quantity">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_sum_item_quantity]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_sum_item_quantity]" ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_sum_item_quantity'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_sum_item_quantity'] : '' ?>">
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="sum_item_quantity_less">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_sum_item_quantity_less]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_sum_item_quantity_less]" ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_sum_item_quantity_less'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_sum_item_quantity_less'] : '' ?>">
																</td>
															</tr>

															<tr class="deps-rules_type"
															    data-type="count_cart_items_at_least">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_count_cart_items_at_least]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_count_cart_items_at_least]" ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_count_cart_items_at_least'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_count_cart_items_at_least'] : '' ?>">
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="count_cart_items_less">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_count_cart_items_less]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_count_cart_items_less]" ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_count_cart_items_less'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_count_cart_items_less'] : '' ?>">
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="subtotal_at_least">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_subtotal_at_least]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_subtotal_at_least]" ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_subtotal_at_least'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_subtotal_at_least'] : '' ?>">
																</td>
															</tr>

															<tr class="deps-rules_type" data-type="subtotal_less">
																<td>
																	<input type="text"
																	       name="<?php echo $suffix_name . "[rules][{$i}][rules_type_subtotal_less]" ?>"
																	       id="<?php echo $suffix_name . "[rules][{$i}][rules_type_subtotal_less]" ?>"
																	       value="<?php echo ( isset( $db_value[ $key ]['rules'][ $i ]['rules_type_subtotal_less'] ) ) ? $db_value[ $key ]['rules'][ $i ]['rules_type_subtotal_less'] : '' ?>">
																</td>
															</tr>


														</table>
													</td>
													<td><span class="add-row"></span><span
															class="remove-row <?php echo $hide_first_remove ?>"></span>
													</td>
												</tr>
											<?php endfor; ?>
										</table>
									</td>
								</tr>

								<tr>
									<th></th>
									<td>
										<table class="cart-discount-amount">
											<tr>
												<th><?php _e( 'Discount Type', 'ywdpd' ); ?></th>
												<th><?php _e( 'Amount', 'ywdpd' ); ?></th>
											</tr>
											<tr>
												<td>
													<select name="<?php echo $suffix_name ?>[discount_type]"
													        id="<?php echo $suffix_id . '[discount_type]' ?>">
														<?php foreach ( $cart_rules_options['discount_type'] as $key_type => $type ): ?>
															<option
																value="<?php echo $key_type ?>" <?php selected( $db_value[ $key ]['discount_type'], $key_type ) ?>><?php echo $type ?></option>
														<?php endforeach ?>
													</select>
												</td>

												<td>
													<input type="text" name="<?php echo $suffix_name ?>[discount_amount]"
													       id="<?php echo $suffix_id . '[discount_amount]' ?>"
													       value="<?php echo ( isset( $db_value[ $key ]['discount_amount'] ) ) ? $db_value[ $key ]['discount_amount'] : '' ?>" />
												</td>
											</tr>
										</table>
									</td>
								</tr>

								<tr>
									<td colspan="2">
										<input style="float: left; margin-right: 10px;" class="button-primary" type="submit"
										       value="<?php _e( 'Save changes', 'ywdpd' ) ?>" />
									</td>
								</tr>

							</table>
						</div>

					</div>
				</form>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>