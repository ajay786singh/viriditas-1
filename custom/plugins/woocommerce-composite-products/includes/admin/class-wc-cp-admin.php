<?php
/**
 * Admin filters and functions.
 *
 * @class 	WC_CP_Admin
 * @version 3.0.0
 * @since   2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_CP_Admin {

	private $save_errors = array();

	public function __construct() {

		// Admin jquery
		add_action( 'admin_enqueue_scripts', array( $this, 'composite_admin_scripts' ) );

		// Creates the admin Components and Scenarios panel tabs
		add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'composite_write_panel_tabs' ) );

		// Adds the base price options
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'composite_pricing_options' ) );

		// Creates the admin Components and Scenarios panels
		add_action( 'woocommerce_product_write_panels', array( $this, 'composite_write_panel' ) );
		add_action( 'woocommerce_product_options_stock', array( $this, 'composite_stock_info' ) );

		// Allows the selection of the 'composite product' type
		add_filter( 'product_type_options', array( $this, 'add_composite_type_options' ) );

		// Processes and saves the necessary post metas from the selections made above
		add_action( 'woocommerce_process_product_meta_composite', array( $this, 'process_composite_meta' ) );

		// Allows the selection of the 'composite product' type
		add_filter( 'product_type_selector', array( $this, 'add_composite_type' ) );

		// Ajax save composite config
		add_action( 'wp_ajax_woocommerce_bto_composite_save', array( $this, 'ajax_composite_save' ) );

		// Ajax add component
		add_action( 'wp_ajax_woocommerce_add_composite_component', array( $this, 'ajax_add_component' ) );

		// Ajax add scenario
		add_action( 'wp_ajax_woocommerce_add_composite_scenario', array( $this, 'ajax_add_scenario' ) );

		// Ajax search default component id
		add_action( 'wp_ajax_woocommerce_json_search_default_component_option', array( $this, 'json_search_default_component_option' ) );

		// Ajax search products and variations in scenarios
		add_action( 'wp_ajax_woocommerce_json_search_component_options_in_scenario', array( $this, 'json_search_component_options_in_scenario' ) );

		// Template override scan path
		add_filter( 'woocommerce_template_overrides_scan_paths', array( $this, 'composite_template_scan_path' ) );

		// Basic component config options
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_title' ), 10, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_description' ), 15, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_options' ), 20, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_quantity_min' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_quantity_max' ), 33, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_discount' ), 35, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( $this, 'component_config_optional' ), 40, 3 );

		// Advanced component configuration
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( $this, 'component_config_default_option' ), 5, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( $this, 'component_sort_filter_show_orderby' ), 10, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( $this, 'component_sort_filter_show_filters' ), 15, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( $this, 'component_layout_hide_product_title' ), 20, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( $this, 'component_layout_hide_product_description' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( $this, 'component_layout_hide_product_thumbnail' ), 30, 3 );

		// Scenario options
		add_action( 'woocommerce_composite_scenario_admin_info_html', array( $this, 'scenario_info' ), 10, 4 );
		add_action( 'woocommerce_composite_scenario_admin_config_html', array( $this, 'scenario_config' ), 10, 4 );

		// Delete component options query cache on product save
		add_action( 'woocommerce_delete_product_transients', array( $this, 'delete_cp_query_transients' ) );
	}

	/**
	 * Delete component options query cache on product save.
	 *
	 * @param  int   $post_id
	 * @return void
	 */
	function delete_cp_query_transients( $post_id ) {

		// Invalidate query cache
		if ( class_exists( 'WC_Cache_Helper' ) ) {
			WC_Cache_Helper::get_transient_version( 'wccp_q', true );
		}

		if ( ! wp_using_ext_object_cache() ) {

			global $wpdb;

			// Delete all query transients
			$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_wccp_q_%') OR `option_name` LIKE ('_transient_timeout_wccp_q_%')" );
		}
	}

	/**
	 * Add scenario title and description options.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $product_id
	 * @return void
	 */
	function scenario_info( $id, $scenario_data, $composite_data, $product_id ) {

		$title       = isset( $scenario_data[ 'title' ] ) ? $scenario_data[ 'title' ] : '';
		$position    = isset( $scenario_data[ 'position' ] ) ? $scenario_data[ 'position' ] : $id;
		$description = isset( $scenario_data[ 'description' ] ) ? $scenario_data[ 'description' ] : '';

		?>
		<div class="scenario_title">
			<div class="form-field">
				<label><?php echo __( 'Scenario Name', 'woocommerce-composite-products' ); ?>:</label>
				<input type="text" class="scenario_title component_text_input" name="bto_scenario_data[<?php echo $id; ?>][title]" value="<?php echo $title; ?>"/>
				<input type="hidden" name="bto_scenario_data[<?php echo $id; ?>][position]" class="scenario_position" value="<?php echo $position; ?>"/>
			</div>
		</div>
		<div class="scenario_description">
			<div class="form-field">
				<label><?php echo __( 'Scenario Description', 'woocommerce-composite-products' ); ?>:</label>
				<textarea class="scenario_description" name="bto_scenario_data[<?php echo $id; ?>][description]" id="scenario_description_<?php echo $id; ?>" placeholder="" rows="2" cols="20"><?php echo esc_textarea( $description ); ?></textarea>
			</div>
		</div>
		<?php
	}

	/**
	 * Add scenario config options.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $product_id
	 * @return void
	 */
	function scenario_config( $id, $scenario_data, $composite_data, $product_id ) {

		global $woocommerce_composite_products;

		?><div class="scenario_config_group"><?php

			foreach ( $composite_data as $component_id => $component_data ) {

				?><div class="bto_scenario_selector">
					<div class="form-field">
						<label><?php echo apply_filters( 'woocommerce_composite_component_title', $component_data[ 'title' ], $component_id, $product_id ); ?>:</label><?php

						$component_options = $woocommerce_composite_products->api->get_component_options( $component_data );

						if ( count( $component_options ) < 30 ) {

							$scenario_options    = array();
							$scenario_selections = array();

							if ( $component_data[ 'optional' ] == 'yes' ) {

								if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_data, $component_id, -1 ) ) {
									$scenario_selections[] = -1;
								}

								$scenario_options[ -1 ] = __( 'None', 'woocommerce-composite-products' );
							}

							if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_data, $component_id, 0 ) ) {
								$scenario_selections[] = 0;
							}

							$scenario_options[ 0 ] = __( 'All Products and Variations', 'woocommerce-composite-products' );

							foreach ( $component_options as $item_id ) {

								$title = $woocommerce_composite_products->api->get_product_title( $item_id );

								if ( ! $title ) {
									continue;
								}

								// Get product type
								$terms        = get_the_terms( $item_id, 'product_type' );
								$product_type = ! empty( $terms ) && isset( current( $terms )->name ) ? sanitize_title( current( $terms )->name ) : 'simple';


								if ( $product_type == 'variable' ) {

									$product_title = $title . ' ' . __( '&mdash; All Variations', 'woocommerce-composite-products' );

									$variation_descriptions = $woocommerce_composite_products->api->get_product_variation_descriptions( $item_id );

								} else {

									$product_title = $title;
								}

								if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_data, $component_id, $item_id ) ) {

									$scenario_selections[] = $item_id;
								}

								$scenario_options[ $item_id ] = $product_title;

								if ( $product_type == 'variable' ) {

									if ( ! empty( $variation_descriptions ) ) {

										foreach ( $variation_descriptions as $variation_id => $description ) {

											if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_data, $component_id, $variation_id ) ) {
												$scenario_selections[] = $variation_id;
											}

											$scenario_options[ $variation_id ] = $description;
										}
									}
								}

							}

							$optional_tip = $component_data[ 'optional' ] === 'yes' ? sprintf( __( '<br/><strong>Pro Tip</strong>: Exclude the <strong>None</strong> option to disable the <strong>Optional</strong> property of <strong>%s</strong> in this Scenario.', 'woocommerce-composite-products' ), apply_filters( 'woocommerce_composite_component_title', $component_data[ 'title' ], $component_id, $product_id ) ) : '';
							$select_tip   = sprintf( __( 'Select products and variations from <strong>%1$s</strong>.<br/><strong>Tip</strong>: Choose the <strong>All Products and Variations</strong> option to enable all products and variations available under <strong>%1$s</strong> in this Scenario.%2$s', 'woocommerce-composite-products' ), apply_filters( 'woocommerce_composite_component_title', $component_data[ 'title' ], $component_id, $product_id ), $optional_tip );

							?><select id="bto_scenario_ids_<?php echo $id; ?>_<?php echo $component_id; ?>" name="bto_scenario_data[<?php echo $id; ?>][component_data][<?php echo $component_id; ?>][]" style="width: 75%;" class="<?php echo WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ? 'wc-enhanced-select' : 'chosen_select'; ?> bto_scenario_ids" multiple="multiple" data-placeholder="<?php echo __( 'Select products &amp; variations&hellip;', 'woocommerce-composite-products' ); ?>"><?php

								foreach ( $scenario_options as $scenario_option_id => $scenario_option_description ) {
									$option_selected = in_array( $scenario_option_id, $scenario_selections ) ? 'selected="selected"' : '';
									echo '<option ' . $option_selected . 'value="' . $scenario_option_id . '">' . $scenario_option_description . '</option>';
								}

							?></select>
							<span class="bto_scenario_select tips" data-tip="<?php echo $select_tip; ?>"></span><?php

						} else {

							$selections_in_scenario = array();

							foreach ( $scenario_data[ 'component_data' ][ $component_id ] as $product_id_in_scenario ) {

								if ( $product_id_in_scenario == -1 ) {
									if ( $component_data[ 'optional' ] == 'yes' ) {
										$selections_in_scenario[ $product_id_in_scenario ] = __( 'None', 'woocommerce-composite-products' );
									}
								} elseif ( $product_id_in_scenario == 0 ) {
									$selections_in_scenario[ $product_id_in_scenario ] = __( 'All Products and Variations', 'woocommerce-composite-products' );
								} else {

									$product_in_scenario = WC_CP_Core_Compatibility::wc_get_product( $product_id_in_scenario );

									if ( ! $product_in_scenario ) {
										continue;
									}

									if ( ! in_array( $product_in_scenario->id, $component_options ) ) {
										continue;
									}

									if ( $product_in_scenario->product_type === 'variation' ) {
										$selections_in_scenario[ $product_id_in_scenario ] = $woocommerce_composite_products->api->get_product_variation_title( $product_in_scenario );
									} elseif ( $product_in_scenario->product_type === 'variable' ) {
										$selections_in_scenario[ $product_id_in_scenario ] = $woocommerce_composite_products->api->get_product_title( $product_in_scenario ) . ' ' . __( '&mdash; All Variations', 'woocommerce-composite-products' );
									} else {
										$selections_in_scenario[ $product_id_in_scenario ] = $woocommerce_composite_products->api->get_product_title( $product_in_scenario );
									}
								}
							}

							$optional_tip = $component_data[ 'optional' ] === 'yes' ? sprintf( __( '<br/><strong>Pro Tip</strong>: Exclude the <strong>None</strong> option to disable the <strong>Optional</strong> property of <strong>%s</strong> in this Scenario.', 'woocommerce-composite-products' ), apply_filters( 'woocommerce_composite_component_title', $component_data[ 'title' ], $component_id, $product_id ) ) : '';
							$search_tip   = sprintf( __( 'Search for products and variations from <strong>%1$s</strong>.<br/><strong>Tip</strong>: Choose the <strong>All Products and Variations</strong> option to enable all products and variations available under <strong>%1$s</strong> in this Scenario.%2$s', 'woocommerce-composite-products' ), apply_filters( 'woocommerce_composite_component_title', $component_data[ 'title' ], $component_id, $product_id ), $optional_tip );

							if ( WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ) {

								?><input type="hidden" id="bto_scenario_ids_<?php echo $id; ?>_<?php echo $component_id; ?>" name="bto_scenario_data[<?php echo $id; ?>][component_data][<?php echo $component_id; ?>]" class="wc-component-options-search" style="width: 75%;" data-component_optional="<?php echo $component_data[ 'optional' ]; ?>" data-component_id="<?php echo $component_id; ?>" data-placeholder="<?php _e( 'Search for products &amp; variations&hellip;', 'woocommerce-composite-products' ); ?>" data-action="woocommerce_json_search_component_options_in_scenario" data-multiple="true" data-selected="<?php

									echo esc_attr( json_encode( $selections_in_scenario ) );

								?>" value="<?php echo implode( ',', array_keys( $selections_in_scenario ) ); ?>" />
								<span class="bto_scenario_search tips" data-tip="<?php echo $search_tip; ?>"></span><?php

							} else {

								?><select id="bto_scenario_ids_<?php echo $id; ?>_<?php echo $component_id; ?>" name="bto_scenario_data[<?php echo $id; ?>][component_data][<?php echo $component_id; ?>][]" class="ajax_chosen_select_component_options" multiple="multiple" data-component_optional="<?php echo $component_data[ 'optional' ]; ?>" data-action="woocommerce_json_search_component_options_in_scenario" data-component_id="<?php echo $component_id; ?>" data-placeholder="<?php echo  __( 'Search for products &amp; variations&hellip;', 'woocommerce-composite-products' ); ?>"><?php

									if ( ! empty( $selections_in_scenario ) ) {

										foreach ( $selections_in_scenario as $selection_id_in_scenario => $selection_in_scenario ) {
											echo '<option value="' . $selection_id_in_scenario . '" selected="selected">' . $selection_in_scenario . '</option>';
										}
									}

								?></select>
								<span class="bto_scenario_search tips" data-tip="<?php echo $search_tip; ?>"></span><?php
							}
						}

						$exclude = isset( $scenario_data[ 'exclude' ][ $component_id ] ) ? $scenario_data[ 'exclude' ][ $component_id ] : 'no';
					?></div>
					<div class="form-field">
						<div class="bto_scenario_exclude_wrapper">
							<select class="bto_scenario_exclude" name="bto_scenario_data[<?php echo $id; ?>][exclude][<?php echo $component_id; ?>]">
								<option <?php selected( $exclude, 'no', true ); ?>value="no"><?php echo __( 'Include selected' ); ?></option>
								<option <?php selected( $exclude, 'yes', true ); ?> value="yes"><?php echo __( 'Exclude selected' ); ?></option>
							</select>
						</div>
					</div>
				</div><?php
			}

		?></div><?php
	}

	/**
	 * Add component layout hide title option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_layout_hide_product_title( $id, $data, $product_id ) {

		$hide_product_title = isset( $data[ 'hide_product_title' ] ) ? $data[ 'hide_product_title' ] : '';

		?>
		<div class="group_hide_product_title">
			<div class="form-field">
				<label for="group_hide_product_title_<?php echo $id; ?>">
					<?php echo __( 'Hide Selected Product Title', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Check this option to hide the selected product title, which is normally displayed under the available Component Options.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $hide_product_title == 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][hide_product_title]" <?php echo ( $hide_product_title == 'yes' ? 'value="1"' : '' ); ?>/>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component layout hide description option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_layout_hide_product_description( $id, $data, $product_id ) {

		$hide_product_description = isset( $data[ 'hide_product_description' ] ) ? $data[ 'hide_product_description' ] : '';

		?>
		<div class="group_hide_product_description" >
			<div class="form-field">
				<label for="group_hide_product_description_<?php echo $id; ?>">
					<?php echo __( 'Hide Selected Product Description', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Check this option to hide the selected product description, which is normally displayed under the available Component Options.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $hide_product_description == 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][hide_product_description]" <?php echo ( $hide_product_description == 'yes' ? 'value="1"' : '' ); ?>/>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component layout hide thumbnail option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_layout_hide_product_thumbnail( $id, $data, $product_id ) {

		$hide_product_thumbnail = isset( $data[ 'hide_product_thumbnail' ] ) ? $data[ 'hide_product_thumbnail' ] : '';

		?>
		<div class="group_hide_product_thumbnail" >
			<div class="form-field">
				<label for="group_hide_product_thumbnail_<?php echo $id; ?>">
					<?php echo __( 'Hide Selected Product Thumbnail', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Check this option to hide the selected product thumbnail, which is normally displayed under the available Component Options.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $hide_product_thumbnail == 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][hide_product_thumbnail]" <?php echo ( $hide_product_thumbnail == 'yes' ? 'value="1"' : '' ); ?>/>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component 'show orderby' option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_sort_filter_show_orderby( $id, $data, $product_id ) {

		$show_orderby = isset( $data[ 'show_orderby' ] ) ? $data[ 'show_orderby' ] : 'no';

		?>
		<div class="group_show_orderby" >
			<div class="form-field">
				<label for="group_show_orderby_<?php echo $id; ?>">
					<?php echo __( 'Show Component Options Sorting Dropdown', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Check this option to show a <strong>Sort options by</strong> dropdown. Use this setting if you have added a large number of Component Options. Recommended only in combination with the <strong>Product Thumbnails</strong> style.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $show_orderby == 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_orderby]" <?php echo ( $show_orderby == 'yes' ? 'value="1"' : '' ); ?>/>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component 'show filters' option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_sort_filter_show_filters( $id, $data, $product_id ) {

		$show_filters         = isset( $data[ 'show_filters' ] ) ? $data[ 'show_filters' ] : 'no';
		$selected_taxonomies  = isset( $data[ 'attribute_filters' ] ) ? $data[ 'attribute_filters' ] : array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		?>
		<div class="group_show_filters" >
			<div class="form-field">
				<label for="group_show_filters_<?php echo $id; ?>">
					<?php echo __( 'Show Layered Component Option Filters', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Check this option to configure and display layered attribute filters to narrow down Component Options. Use this setting if you have added a large number of Component Options. Recommended only in combination with the <strong>Product Thumbnails</strong> style.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $show_filters == 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_filters]" <?php echo ( $show_filters == 'yes' ? 'value="1"' : '' ); ?>/>
			</div>
		</div><?php

		if ( $attribute_taxonomies ) {

			$attribute_array = array();

			foreach ( $attribute_taxonomies as $tax ) {

				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) )
					$attribute_array[ $tax->attribute_id ] = $tax->attribute_label;
			}

			?><div class="group_filters" >
				<div class="bto_attributes_selector bto_multiselect">
					<div class="form-field">
						<label><?php echo __( 'Active Attribute Filters', 'woocommerce-composite-products' ); ?>:</label>
						<select id="bto_attribute_ids_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][attribute_filters][]" style="width: 75%" class="multiselect <?php echo WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ? 'wc-enhanced-select' : 'chosen_select'; ?>" multiple="multiple" data-placeholder="<?php echo  __( 'Select product attributes&hellip;', 'woocommerce-composite-products' ); ?>"><?php

							foreach ( $attribute_array as $attribute_taxonomy_id => $attribute_taxonomy_label )
								echo '<option value="' . $attribute_taxonomy_id . '" ' . selected( in_array( $attribute_taxonomy_id, $selected_taxonomies ), true, false ).'>' . $attribute_taxonomy_label . '</option>';

						?></select>
					</div>
				</div><?php

				// Hook here to add your own custom filter config options
				do_action( 'woocommerce_composite_component_admin_config_filter_options', $id, $data, $product_id );

			?></div><?php
		}
	}

	/**
	 * Add component config title option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_title( $id, $data, $product_id ) {

		$title    = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';
		$position = isset( $data[ 'position' ] ) ? $data[ 'position' ] : $id;

		?>
		<div class="group_title">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Name', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Name or title of this Component.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="text" class="group_title component_text_input" name="bto_data[<?php echo $id; ?>][title]" value="<?php echo $title; ?>"/>
				<input type="hidden" name="bto_data[<?php echo $id; ?>][position]" class="group_position" value="<?php echo $position; ?>" />
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config description option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_description( $id, $data, $product_id ) {

		$description = isset( $data[ 'description' ] ) ? $data[ 'description' ] : '';

		?>
		<div class="group_description">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Description', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Optional short description of this Component.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<textarea class="group_description" name="bto_data[<?php echo $id; ?>][description]" id="group_description_<?php echo $id; ?>" placeholder="" rows="2" cols="20"><?php echo esc_textarea( $description ); ?></textarea>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config multi select products option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_options( $id, $data, $product_id ) {

		global $woocommerce_composite_products;

		$query_type          = isset( $data[ 'query_type' ] ) ? $data[ 'query_type' ] : 'product_ids';
		$product_categories  = ( array ) get_terms( 'product_cat', array( 'get' => 'all' ) );
		$selected_categories = isset( $data[ 'assigned_category_ids' ] ) ? $data[ 'assigned_category_ids' ] : array();

		$select_by = array(
			'product_ids'  => __( 'Select products', 'woocommerce-composite-products' ),
			'category_ids' => __( 'Select categories', 'woocommerce-composite-products' )
		);

		// Add your own custom query option
		$select_by = apply_filters( 'woocommerce_composite_component_query_types', $select_by, $data, $product_id );

		?>
		<div class="bto_query_type">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Options', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Select the products you want to use as Component Options. You can add products individually, or select a category to add all associated products.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<select class="bto_query_type" name="bto_data[<?php echo $id; ?>][query_type]"><?php

					foreach ( $select_by as $key => $description ) {
						?><option value="<?php echo $key; ?>" <?php selected( $query_type, $key, true ); ?>><?php echo $description; ?></option><?php
					}

				?></select>
			</div>
		</div>

		<div class="bto_selector bto_query_type_selector bto_multiselect bto_query_type_product_ids">
			<div class="form-field"><?php

				$product_id_options = array();

				if ( ! empty( $data[ 'assigned_ids' ] ) ) {

					$item_ids = $data[ 'assigned_ids' ];

					foreach ( $item_ids as $item_id ) {

						$product_title = $woocommerce_composite_products->api->get_product_title( $item_id );

						if ( $product_title ) {

							$product_id_options[ $item_id ] = $product_title;
						}
					}

				}

				if ( WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ) {


					?><input type="hidden" id="bto_ids_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][assigned_ids]" class="wc-product-search" style="width: 75%;" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products" data-multiple="true" data-selected="<?php

						echo esc_attr( json_encode( $product_id_options ) );

					?>" value="<?php echo implode( ',', array_keys( $product_id_options ) ); ?>" /><?php

				} else {

					?><select id="bto_ids_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][assigned_ids][]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php echo  __( 'Search for a product&hellip;', 'woocommerce' ); ?>"><?php

						if ( ! empty( $product_id_options ) ) {

							foreach( $product_id_options as $product_id => $product_name ) {
								echo '<option value="' . $product_id . '" selected="selected">' . $product_name . '</option>';
							}
						}

					?></select><?php

				}
			?></div>
		</div>

		<div class="bto_category_selector bto_query_type_selector bto_multiselect bto_query_type_category_ids">
			<div class="form-field">

				<select id="bto_category_ids_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][assigned_category_ids][]" style="width: 75%" class="multiselect <?php echo WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ? 'wc-enhanced-select' : 'chosen_select'; ?>" multiple="multiple" data-placeholder="<?php echo  __( 'Select product categories&hellip;', 'woocommerce-composite-products' ); ?>"><?php

					foreach ( $product_categories as $product_category )
						echo '<option value="' . $product_category->term_id . '" ' . selected( in_array( $product_category->term_id, $selected_categories ), true, false ).'>' . $product_category->name . '</option>';

				?></select>
			</div>
		</div><?php

		// Hook here to add your own custom query config options
		do_action( 'woocommerce_composite_component_admin_config_query_options', $id, $data, $product_id );
	}

	/**
	 * Add component config default selection option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_default_option( $id, $data, $product_id ) {

		global $woocommerce_composite_products;

		?>
		<div class="default_selector">
			<div class="form-field">
				<label>
					<?php echo __( 'Default Component Option', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Select a product that you want to use as the default (pre-selected) Component Option. To use this option, you must first add some products in the <strong>Component Options</strong> field and then save your configuration.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label><?php

				// Run query to get component option ids
				$item_ids = $woocommerce_composite_products->api->get_component_options( $data );

				if ( ! empty( $item_ids ) ) {

					// If < 30 show a dropdown, otherwise show an ajax chosen field
					if ( count( $item_ids ) < 30 ) {

						?><select id="group_default_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][default_id]">
							<option value=""><?php echo __( 'No default option&hellip;', 'woocommerce-composite-products' ); ?></option><?php

							$selected_default = $data[ 'default_id' ];

							foreach ( $item_ids as $item_id ) {

								$product_title = $woocommerce_composite_products->api->get_product_title( $item_id );

								if ( $product_title ) {
									echo '<option value="' . $item_id . '" ' . selected( $selected_default, $item_id, false ) . '>'. $product_title . '</option>';
								}
							}

						?></select><?php

					} else {

						$selected_default = $data[ 'default_id' ];
						$product_title    = '';

						if ( $selected_default ) {

							$product_title = $woocommerce_composite_products->api->get_product_title( $selected_default );
						}

						if ( WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ) {

							?><input type="hidden" id="group_default_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][default_id]" class="wc-component-options-search" style="width: 75%;" data-component_id="<?php echo isset( $data[ 'component_id' ] ) ? $data[ 'component_id' ] : ''; ?>" data-placeholder="<?php _e( 'No default selected. Search for a product&hellip;', 'woocommerce-composite-products' ); ?>" data-allow_clear="true" data-action="woocommerce_json_search_default_component_option" data-multiple="false" data-selected="<?php

								echo esc_attr( $product_title ? $product_title : __( 'No default selected. Search for a product&hellip;', 'woocommerce-composite-products' ) );

							?>" value="<?php echo $product_title ? $selected_default : ''; ?>" /><?php

						} else {

							?><select id="group_default_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][default_id]" class="ajax_chosen_select_component_options" data-action="woocommerce_json_search_default_component_option" data-component_id="<?php echo isset( $data[ 'component_id' ] ) ? $data[ 'component_id' ] : ''; ?>" data-placeholder="<?php echo  __( 'No default selected. Search for a product&hellip;', 'woocommerce-composite-products' ); ?>">
								<option value=""><?php echo __( 'No default option&hellip;', 'woocommerce-composite-products' ); ?></option><?php

								$selected_default = $data[ 'default_id' ];

								if ( $selected_default ) {

									$product_title = $woocommerce_composite_products->api->get_product_title( $selected_default );

									if ( $product_title ) {
										echo '<option value="' . $selected_default . '" selected="selected">' . $product_title . '</option>';
									}
								}

							?></select><?php
						}
					}

				} else {

					?><div class="prompt"><em><?php _e( 'To choose a default product, you must first add some products in the Component Options field and then save your configuration&hellip;', 'woocommerce-composite-products' ); ?></em></div><?php
				}

			?></div>
		</div>
		<?php
	}

	/**
	 * Add component config min quantity option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_quantity_min( $id, $data, $product_id ) {

		$quantity_min = isset( $data[ 'quantity_min' ] ) ? $data[ 'quantity_min' ] : 1;

		?>
		<div class="group_quantity_min">
			<div class="form-field">
				<label for="group_quantity_min_<?php echo $id; ?>">
					<?php echo __( 'Min Quantity', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Set a minimum quantity for the selected Component Option.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="number" class="group_quantity_min" name="bto_data[<?php echo $id; ?>][quantity_min]" id="group_quantity_min_<?php echo $id; ?>" value="<?php echo $quantity_min; ?>" placeholder="" step="1" min="0" />
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config max quantity option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_quantity_max( $id, $data, $product_id ) {

		$quantity_max = isset( $data[ 'quantity_max' ] ) ? $data[ 'quantity_max' ] : 1;

		?>
		<div class="group_quantity_max">
			<div class="form-field">
				<label for="group_quantity_max_<?php echo $id; ?>">
					<?php echo __( 'Max Quantity', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Set a maximum quantity for the selected Component Option.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="number" class="group_quantity_max" name="bto_data[<?php echo $id; ?>][quantity_max]" id="group_quantity_max_<?php echo $id; ?>" value="<?php echo $quantity_max; ?>" placeholder="" step="1" min="0" />
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config discount option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_discount( $id, $data, $product_id ) {

		$discount = isset( $data[ 'discount' ] ) ? $data[ 'discount' ] : '';

		?>
		<div class="group_discount">
			<div class="form-field">
				<label for="group_discount_<?php echo $id; ?>">
					<?php echo __( 'Discount %', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Component-level discount applied to any selected Component Option when the <strong>Per-Item Pricing</strong> field is checked. Note that component-level discounts are calculated on top of regular product prices.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="text" class="group_discount input-text wc_input_decimal" name="bto_data[<?php echo $id; ?>][discount]" id="group_discount_<?php echo $id; ?>" value="<?php echo $discount; ?>" placeholder="" />
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config optional option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	function component_config_optional( $id, $data, $product_id ) {

		$optional = isset( $data[ 'optional' ] ) ? $data[ 'optional' ] : '';

		?>
		<div class="group_optional" >
			<div class="form-field">
				<label for="group_optional_<?php echo $id; ?>">
					<?php echo __( 'Optional', 'woocommerce-composite-products' ); ?>
					<img class="help_tip" data-tip="<?php echo __( 'Checking this option will allow customers to proceed without making any selection for this Component at all.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $optional == 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][optional]" <?php echo ( $optional == 'yes' ? ' value="1"' : '' ); ?> />
			</div>
		</div>
		<?php
	}

	/**
	 * Admin writepanel scripts.
	 *
	 * @return void
	 */
	function composite_admin_scripts() {

		global $woocommerce_composite_products;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( WC_CP_Core_Compatibility::is_wc_version_gte_2_2() ) {
			$writepanel_dependency = 'wc-admin-meta-boxes';
		} else {
			$writepanel_dependency = 'woocommerce_admin_meta_boxes';
		}

		wp_register_script( 'wc_composite_writepanel', $woocommerce_composite_products->plugin_url() . '/assets/js/wc-composite-write-panels' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker', $writepanel_dependency ), $woocommerce_composite_products->version );
		wp_register_style( 'wc_composite_writepanel_css', $woocommerce_composite_products->plugin_url() . '/assets/css/wc-composite-write-panels.css', array( 'woocommerce_admin_styles' ), $woocommerce_composite_products->version );
		wp_register_style( 'wc_composite_edit_order_css', $woocommerce_composite_products->plugin_url() . '/assets/css/wc-composite-edit-order.css', array( 'woocommerce_admin_styles' ), $woocommerce_composite_products->version );

		// Get admin screen id
		$screen = get_current_screen();

		// WooCommerce admin pages
		if ( in_array( $screen->id, array( 'product' ) ) ) {
			wp_enqueue_script( 'wc_composite_writepanel' );

			$params = array(
				'save_composite_nonce'      => wp_create_nonce( 'wc_bto_save_composite' ),
				'add_component_nonce'       => wp_create_nonce( 'wc_bto_add_component' ),
				'add_scenario_nonce'        => wp_create_nonce( 'wc_bto_add_scenario' ),
				'i18n_no_default'           => __( 'No default option&hellip;', 'woocommerce-composite-products' ),
				'i18n_all'                  => __( 'All Products and Variations', 'woocommerce-composite-products' ),
				'i18n_none'                 => __( 'None', 'woocommerce-composite-products' ),
				'is_wc_version_gte_2_3'     => WC_CP_Core_Compatibility::is_wc_version_gte_2_3() ? 'yes' : 'no',
				'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'woocommerce' ),
				'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'woocommerce' ),
				'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce' ),
				'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce' ),
				'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce' ),
				'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce' ),
				'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce' ),
				'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce' ),
			);

			wp_localize_script( 'wc_composite_writepanel', 'wc_composite_admin_params', $params );
		}

		if ( in_array( $screen->id, array( 'edit-product', 'product' ) ) )
			wp_enqueue_style( 'wc_composite_writepanel_css' );

		if ( in_array( $screen->id, array( 'shop_order', 'edit-shop_order' ) ) )
			wp_enqueue_style( 'wc_composite_edit_order_css' );
	}

	/**
	 * Adds the Composite Product write panel tabs.
	 *
	 * @return string
	 */
	function composite_write_panel_tabs() {

		echo '<li class="bto_product_tab show_if_composite linked_product_options composite_product_options"><a href="#bto_product_data">'.__( 'Components', 'woocommerce-composite-products' ).'</a></li>';
		echo '<li class="bto_product_tab show_if_composite linked_product_options composite_scenarios"><a href="#bto_scenario_data">'.__( 'Scenarios', 'woocommerce-composite-products' ).'</a></li>';
	}

	/**
	 * Adds the base and sale price option writepanel options.
	 *
	 * @return void
	 */
	function composite_pricing_options() {

		echo '<div class="options_group bto_base_pricing show_if_composite">';

		// Price
		woocommerce_wp_text_input( array( 'id' => '_base_regular_price', 'class' => 'short', 'label' => __( 'Base Regular Price', 'woocommerce-composite-products' ) . ' (' . get_woocommerce_currency_symbol().')', 'data_type' => 'price' ) );

		// Sale Price
		woocommerce_wp_text_input( array( 'id' => '_base_sale_price', 'class' => 'short', 'label' => __( 'Base Sale Price', 'woocommerce-composite-products' ) . ' (' . get_woocommerce_currency_symbol() . ')', 'data_type' => 'price' ) );

		// Hide Shop Price
		woocommerce_wp_checkbox( array( 'id' => '_bto_hide_shop_price', 'label' => __( 'Hide Price', 'woocommerce-composite-products' ), 'desc_tip' => true, 'description' => __( 'Check this box to hide the Composite price from the shop catalog and product summary.', 'woocommerce-composite-products' ) ) );

		echo '</div>';
	}

	/**
	 * Add Composited Products stock note.
	 *
	 * @return void
	 */
	function composite_stock_info() {
		global $post; ?>

		<p class="form-field show_if_composite composite_stock_msg">
			<label><?php _e( 'Note', 'woocommerce-composite-products' ); ?></label>
			<span class="note">
				<?php _e( 'Use these settings to enable stock management at composite level' ); ?>
				<img class="help_tip" data-tip="<?php echo __( 'By default, the sale of a product within a composite has the same effect on its stock as an individual sale. There are no separate inventory settings for composited items. However, this pane can be used to enable stock management at composite level. This can be very useful for allocating composite stock quota, or for keeping track of composited item sales.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
			</span>
		</p><?php

	}

	/**
	 * Components and Scenarios write panels.
	 *
	 * @return void
	 */
	function composite_write_panel() {

		global $woocommerce_composite_products, $post, $wpdb;

		$bto_data = get_post_meta( $post->ID, '_bto_data', true );

		?>
		<div id="bto_product_data" class="bto_panel panel woocommerce_options_panel wc-metaboxes-wrapper">

			<div class="options_group bundle_group bto_clearfix">
				<h3><?php _e( 'Layout Options:', 'woocommerce-composite-products' ); ?></h3>
				<div class="bto_layouts bto_clearfix form-field">
					<label class="bundle_group_label">
						<?php _e( 'Composite Layout', 'woocommerce-composite-products' ); ?>
						<img class="help_tip" data-tip="<?php echo __( 'Choose the <strong>Stacked</strong> or <strong>Progressive</strong> layout if the Components added below contain a few options with a minimal amount of information. Recommended when using the <strong>Dropdowns</strong> Options Style. Select the <strong>Stepped</strong> or <strong>Componentized</strong> layout if the Components added below contain multiple options with detailed descriptions and images. Recommended when using the <strong>Product Thumbnails</strong> Options Style.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
					</label>
					<ul class="bto_clearfix bto_layouts_list">
						<?php
						$layouts         = $woocommerce_composite_products->api->get_layout_options();
						$selected_layout = $woocommerce_composite_products->api->get_selected_layout_option( get_post_meta( $post->ID, '_bto_style', true ) );

						foreach ( $layouts as $layout_id => $layout_description ) {

							$layout_src     = apply_filters( 'woocommerce_composite_product_layout_image_src', $woocommerce_composite_products->plugin_url() . '/assets/images/' . $layout_id . '.png', $post->ID );
							$layout_tooltip = $woocommerce_composite_products->api->get_layout_tooltip( $layout_id );

							?><li><label class="bto_layout_label">
								<img src="<?php echo $layout_src; ?>" />
								<input <?php echo $selected_layout == $layout_id ? 'checked="checked"' : ''; ?> name="bto_style" type="radio" value="<?php echo $layout_id; ?>" />
								<br/>
								<span><?php echo $layout_description . ' ' . $layout_tooltip; ?></span>
							</label></li><?php
						}

					?></ul>
				</div>
				<p class="form-field">
					<label class="bundle_group_label">
						<?php _e( 'Options Style', 'woocommerce-composite-products' ); ?>
						<img class="help_tip" data-tip="<?php echo __( '<strong>Product Thumbnails</strong>:</br>Component Options are presented as product thumbnails, paginated and arranged in columns similar to the main shop loop. Use this setting if your Components include many product options. Also recommended if you want to display sorting and filtering controls.</br></br><strong>Dropdowns</strong>:</br>Component Options are listed in dropdown menus without any pagination. Use this setting if your Components include just a few product options. Also recommended if you want to keep the layout as compact as possible.', 'woocommerce-composite-products' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
					</label>
					<select name="bto_selection_mode">
						<?php

						$mode = get_post_meta( $post->ID, '_bto_selection_mode', true );

						if ( empty( $mode ) )
							$mode = 'dropdowns';

						echo '<option ' . selected( $mode, 'dropdowns', false ) .' value="dropdowns">' . __( 'Dropdowns', 'woocommerce-composite-products' ) . '</option>';
						echo '<option ' . selected( $mode, 'thumbnails', false ) .' value="thumbnails">' . __( 'Product Thumbnails', 'woocommerce-composite-products' ) . '</option>';
						?>
					</select>
				</p>
			</div>
			<div class="options_group config_group bto_clearfix">

				<h3><?php _e( 'Components:', 'woocommerce-composite-products' ); ?></h3>
				<p class="toolbar">
					<a href="#" class="help_tip tips" data-tip="<?php echo __( 'Composite Products consist of building blocks, called <strong>Components</strong>. Each Component offers an assortment of <strong>Component Options</strong> that correspond to existing Simple products, Variable products or Product Bundles.', 'woocommerce-composite-products' ); ?>" >[?]</a>
					<a href="#" class="close_all"><?php _e( 'Close all', 'woocommerce' ); ?></a>
					<a href="#" class="expand_all"><?php _e( 'Expand all', 'woocommerce' ); ?></a>
				</p>

				<div id="bto_config_group_inner">

					<div class="bto_groups wc-metaboxes ui-sortable" data-count="">

						<?php

						if ( $bto_data ) {

							$i = 0;

							foreach ( $bto_data as $group_id => $data ) {

								if ( ! isset( $data[ 'component_id' ] ) ) {
									$data[ 'component_id' ] = $group_id;
								}

								?><div class="bto_group wc-metabox closed" rel="<?php echo $data[ 'position' ]; ?>">
									<h3>
										<button type="button" class="remove_row button"><?php echo __( 'Remove', 'woocommerce' ); ?></button>
										<div class="handlediv" title="<?php echo __( 'Click to toggle', 'woocommerce' ); ?>"></div>
										<strong class="group_name"><?php echo apply_filters( 'woocommerce_composite_component_title', $data[ 'title' ], $group_id, $post->ID ); ?></strong>
										<input type="hidden" name="bto_data[<?php echo $i; ?>][group_id]" class="group_id" value="<?php echo $group_id; ?>" />
									</h3>
									<div class="bto_group_data wc-metabox-content">
										<ul class="subsubsub">
											<li><a href="#" data-tab="basic" class="current"><?php
												echo __( 'Basic Configuration', 'woocommerce-composite-products' );
											?></a> | </li>
											<li><a href="#" data-tab="advanced"><?php
												echo __( 'Advanced Configuration', 'woocommerce-composite-products' );
												?></a>
											</li>
										</ul>
										<div class="options_group options_group_basic">
											<?php do_action( 'woocommerce_composite_component_admin_config_html', $i, $data, $post->ID ); ?>
										</div>
										<div class="options_group options_group_advanced options_group_hidden">
											<?php do_action( 'woocommerce_composite_component_admin_advanced_html', $i, $data, $post->ID ); ?>
											<span class="group_id">
												<?php echo sprintf( __( '#id: %s', 'woocommerce-composite-products' ), $group_id ); ?>
											</span>
										</div>
									</div>
								</div><?php

								$i++;
							}
						}

					?></div>
				</div>

				<p class="toolbar borderless">
					<button type="button" class="button save_composition"><?php _e( 'Save Configuration', 'woocommerce-composite-products' ); ?></button>
					<button type="button" class="button button-primary add_bto_group"><?php _e( 'Add Component', 'woocommerce-composite-products' ); ?></button>
				</p>
			</div> <!-- options group -->
		</div>
		<?php

		$bto_scenarios = get_post_meta( $post->ID, '_bto_scenario_data', true );

		?>
		<div id="bto_scenario_data" class="bto_panel panel woocommerce_options_panel wc-metaboxes-wrapper">

			<div class="options_group">

				<div id="bto_scenarios_inner"><?php

					if ( $bto_data ) {

						?><p class="toolbar">
							<a href="#" class="help_tip tips" data-tip="<?php _e( 'Use Scenarios if your Composite contains products or variations that shouldn\'t be selected or purchased together, such as incompatible computer parts. With Scenarios, you can define all the valid configurations of your Composite: A Scenario is simply a group of mutually compatible products, which can be selected and purchased together.', 'woocommerce-composite-products' ); ?>">[?]</a>
							<a href="#" class="close_all"><?php _e( 'Close all', 'woocommerce' ); ?></a>
							<a href="#" class="expand_all"><?php _e( 'Expand all', 'woocommerce' ); ?></a>
						</p>

						<div class="bto_scenarios wc-metaboxes"><?php

							if ( $bto_scenarios ) {

								$i = 0;

								foreach ( $bto_scenarios as $scenario_id => $scenario_data ) {

									$scenario_data[ 'scenario_id' ] = $scenario_id;

									?><div class="bto_scenario bto_scenario_<?php echo $i; ?> wc-metabox closed" rel="<?php echo $scenario_data[ 'position' ]; ?>">
										<h3>
											<button type="button" class="remove_row button"><?php echo __( 'Remove', 'woocommerce' ); ?></button>
											<div class="handlediv" title="<?php echo __( 'Click to toggle', 'woocommerce' ); ?>"></div>
											<strong class="scenario_name"><?php echo $scenario_data[ 'title' ]; ?></strong>
											<input type="hidden" name="bto_scenario_data[<?php echo $i; ?>][scenario_id]" class="scenario_id" value="<?php echo $scenario_id; ?>"/>
										</h3>
										<div class="bto_scenario_data wc-metabox-content">
											<div class="options_group">
												<h4><?php echo __( 'Scenario Name &amp; Description', 'woocommerce-composite-products' ); ?></h4><?php

												do_action( 'woocommerce_composite_scenario_admin_info_html', $i, $scenario_data, $bto_data, $post->ID );

												?><h4><?php echo __( 'Component Options Enabled by Scenario', 'woocommerce-composite-products' ); ?></h4><?php

												do_action( 'woocommerce_composite_scenario_admin_config_html', $i, $scenario_data, $bto_data, $post->ID );

											?></div>
										</div>
									</div><?php

									$i++;
								}
							}

						?></div>

						<p class="toolbar borderless">
							<button type="button" class="button button-primary add_bto_scenario"><?php _e( 'Add Scenario', 'woocommerce-composite-products' ); ?></button>
						</p><?php

					} else {

						?><div id="bto-scenarios-message" class="inline woocommerce-message">
							<div class="squeezer">
								<p><?php _e( 'Scenarios can be defined only after creating and saving some Components from the <strong>Components</strong> tab.', 'woocommerce-composite-products' ); ?></p>
								<p class="submit"><a class="button-primary" href="<?php echo 'http://docs.woothemes.com/document/composite-products'; ?>" target="_blank"><?php _e( 'Learn more', 'woocommerce' ); ?></a></p>
							</div>
						</div><?php
					}

				?></div>
			</div>
		</div><?php
	}

	/**
	 * Product options for post-1.6.2 product data section.
	 *
	 * @param  array $options
	 * @return array
	 */
	function add_composite_type_options( $options ) {

		$options[ 'per_product_shipping_bto' ] = array(
			'id'            => '_per_product_shipping_bto',
			'wrapper_class' => 'show_if_composite',
			'label'         => __( 'Non-Bundled Shipping', 'woocommerce-composite-products' ),
			'description'   => __( 'If your Composite product consists of items that are assembled or packaged together, leave this box un-checked and define the shipping properties of the Composite below. If, however, the contents of the Composite are shipped individually, checking this option will allow them to retain their individual shipping properties. <strong>Non-Bundled Shipping</strong> should also be checked if all composited items are virtual.', 'woocommerce-composite-products' ),
			'default'       => 'no'
		);

		$options[ 'per_product_pricing_bto' ] = array(
			'id'            => '_per_product_pricing_bto',
			'wrapper_class' => 'show_if_composite bto_per_item_pricing',
			'label'         => __( 'Per-Item Pricing', 'woocommerce-composite-products' ),
			'description'   => __( 'When <strong>Per-Item Pricing</strong> is checked, the Composite product will be priced according to the cost of its contents. To add a fixed amount to the Composite price when thr <strong>Per-Item Pricing</strong> option is checked, use the Base Price fields below.', 'woocommerce-composite-products' ),
			'default'       => 'no'
		);

		return $options;
	}

	/**
	 * Adds the 'composite product' type to the menu.
	 *
	 * @param  array 	$options
	 * @return array
	 */
	function add_composite_type( $options ) {

		$options[ 'composite' ] = __( 'Composite product', 'woocommerce-composite-products' );

		return $options;
	}

	/**
	 * Process, verify and save composite product data.
	 *
	 * @param  int 	$post_id
	 * @return void
	 */
	function process_composite_meta( $post_id ) {

		global $woocommerce_composite_products;

		// Per-Item Pricing

		if ( isset( $_POST[ '_per_product_pricing_bto' ] ) ) {

			update_post_meta( $post_id, '_per_product_pricing_bto', 'yes' );

			update_post_meta( $post_id, '_base_sale_price', $_POST[ '_base_sale_price' ] === '' ? '' : stripslashes( wc_format_decimal( $_POST[ '_base_sale_price' ] ) ) );
			update_post_meta( $post_id, '_base_regular_price', $_POST[ '_base_regular_price' ] === '' ? '' : stripslashes( wc_format_decimal( $_POST[ '_base_regular_price' ] ) ) );

			if ( $_POST[ '_base_sale_price' ] !== '' ) {
				update_post_meta( $post_id, '_base_price', stripslashes( wc_format_decimal( $_POST[ '_base_sale_price' ] ) ) );
			} else {
				update_post_meta( $post_id, '_base_price', stripslashes( wc_format_decimal( $_POST[ '_base_regular_price' ] ) ) );
			}

			if ( ! empty( $_POST[ '_bto_hide_shop_price' ] ) ) {
				update_post_meta( $post_id, '_bto_hide_shop_price', 'yes' );
			} else {
				update_post_meta( $post_id, '_bto_hide_shop_price', 'no' );
			}

		} else {

			update_post_meta( $post_id, '_per_product_pricing_bto', 'no' );
		}


		// Shipping
		// Non-Bundled (per-item) Shipping

		if ( isset( $_POST[ '_per_product_shipping_bto' ] ) ) {
			update_post_meta( $post_id, '_per_product_shipping_bto', 'yes' );
			update_post_meta( $post_id, '_virtual', 'yes' );
			update_post_meta( $post_id, '_weight', '' );
			update_post_meta( $post_id, '_length', '' );
			update_post_meta( $post_id, '_width', '' );
			update_post_meta( $post_id, '_height', '' );
		} else {
			update_post_meta( $post_id, '_per_product_shipping_bto', 'no' );
			update_post_meta( $post_id, '_virtual', 'no' );
			update_post_meta( $post_id, '_weight', stripslashes( $_POST[ '_weight' ] ) );
			update_post_meta( $post_id, '_length', stripslashes( $_POST[ '_length' ] ) );
			update_post_meta( $post_id, '_width', stripslashes( $_POST[ '_width' ] ) );
			update_post_meta( $post_id, '_height', stripslashes( $_POST[ '_height' ] ) );
		}

		$this->save_composite_config( $post_id, $_POST );

	}

	/**
	 * Save components and scenarios.
	 *
	 * @param  int   $post_id
	 * @param  array $posted_composite_data
	 * @return boolean
	 */
	function save_composite_config( $post_id, $posted_composite_data ) {

		global $woocommerce_composite_products, $wpdb;

		// Composite style
		if ( isset( $posted_composite_data[ 'bto_style' ] ) )
			update_post_meta( $post_id, '_bto_style', stripslashes( $posted_composite_data[ 'bto_style' ] ) );
		else
			update_post_meta( $post_id, '_bto_style', 'single' );

		// Composite selection mode
		if ( isset( $posted_composite_data[ 'bto_selection_mode' ] ) )
			update_post_meta( $post_id, '_bto_selection_mode', stripslashes( $posted_composite_data[ 'bto_selection_mode' ] ) );
		else
			update_post_meta( $post_id, '_bto_selection_mode', 'dropdowns' );


		// Process Composite Product Configuration
		$zero_product_item_exists = false;
		$component_options_count  = 0;
		$bto_data                 = get_post_meta( $post_id, '_bto_data', true );

		if ( ! $bto_data )
			$bto_data = array();

		if ( isset( $posted_composite_data[ 'bto_data' ] ) ) {

			/* -------------------------- */
			/* Components
			/* -------------------------- */

			$counter  = 0;
			$ordering = array();

			foreach ( $posted_composite_data[ 'bto_data' ] as $row_id => $post_data ) {

				$bto_ids     = isset( $post_data[ 'assigned_ids' ] ) ? $post_data[ 'assigned_ids' ] : '';
				$bto_cat_ids = isset( $post_data[ 'assigned_category_ids' ] ) ? $post_data[ 'assigned_category_ids' ] : '';

				$group_id    = isset ( $post_data[ 'group_id' ] ) ? stripslashes( $post_data[ 'group_id' ] ) : ( current_time( 'timestamp' ) + $counter );
				$counter++;

				$bto_data[ $group_id ] = array();

				// Save component id
				$bto_data[ $group_id ][ 'component_id' ] = $group_id;

				// Save query type
				if ( isset( $post_data[ 'query_type' ] ) && ! empty( $post_data[ 'query_type' ] ) ) {
					$bto_data[ $group_id ][ 'query_type' ] = stripslashes( $post_data[ 'query_type' ] );
				} else {
					$bto_data[ $group_id ][ 'query_type' ] = 'product_ids';
				}

				if ( ! empty( $bto_ids ) ) {

					if ( is_array( $bto_ids ) ) {
						$bto_ids = array_map( 'intval', $post_data[ 'assigned_ids' ] );
					} else {
						$bto_ids = array_filter( array_map( 'intval', explode( ',', $post_data[ 'assigned_ids' ] ) ) );
					}

					foreach ( $bto_ids as $key => $id ) {

						// Get product type
						$terms        = get_the_terms( $id, 'product_type' );
						$product_type = ! empty( $terms ) && isset( current( $terms )->name ) ? sanitize_title( current( $terms )->name ) : 'simple';

						if ( $id && $id > 0 && in_array( $product_type, apply_filters( 'woocommerce_composite_products_supported_types', array( 'simple', 'variable', 'bundle' ) ) ) && $post_id != $id ) {

							// Check that product exists

							if ( ! get_post( $id ) ) {
								continue;
							}

							// Check Bundles version
							if ( $product_type == 'bundle' ) {

								global $woocommerce_bundles;

								if ( empty( $woocommerce_bundles ) || version_compare( $woocommerce_bundles->version, '4.8.7' ) < 0  ) {
									$this->save_errors[] = $this->add_admin_error( sprintf( __( 'To add Product Bundles to your Composites, please install Product Bundles version %s or newer.', 'woocommerce-composite-products' ), '4.8.7' ) );
									continue;
								}

							} else {

								$error = apply_filters( 'woocommerce_composite_products_custom_type_save_error', false, $id );

								if ( $error ) {
									$this->save_errors[] = $this->add_admin_error( $error );
									continue;
								}
							}

							// Save assigned ids
							$bto_data[ $group_id ][ 'assigned_ids' ][] = $id;
						}

					}

					if ( ! empty( $bto_data[ $group_id ][ 'assigned_ids' ] ) ) {
						$bto_data[ $group_id ][ 'assigned_ids' ] = array_unique( $bto_data[ $group_id ][ 'assigned_ids' ] );
					}

				}

				if ( ! empty( $bto_cat_ids ) ) {

					$bto_cat_ids = array_map( 'absint', $post_data[ 'assigned_category_ids' ] );

					$bto_data[ $group_id ][ 'assigned_category_ids' ] = array_values( $bto_cat_ids );
				}

				// True if no products were added
				if ( ( $bto_data[ $group_id ][ 'query_type' ] == 'product_ids' && empty( $bto_data[ $group_id ][ 'assigned_ids' ] ) ) || ( $bto_data[ $group_id ][ 'query_type' ] == 'category_ids' && empty( $bto_data[ $group_id ][ 'assigned_category_ids' ] ) ) ) {

					unset( $bto_data[ $group_id ] );
					$zero_product_item_exists = true;
					continue;

				}

				// Run query to get component option ids
				$component_options = $woocommerce_composite_products->api->get_component_options( $bto_data[ $group_id ] );

				// Add up options
				$component_options_count += count( $component_options );

				// Save default preferences
				if ( isset( $post_data[ 'default_id' ] ) && ! empty( $post_data[ 'default_id' ] ) && count( $component_options ) > 0 ) {

					if ( in_array( $post_data[ 'default_id' ], $component_options ) )

						$bto_data[ $group_id ][ 'default_id' ] = stripslashes( $post_data[ 'default_id' ] );

					else {

						$bto_data[ $group_id ][ 'default_id' ] = '';

						if ( ! empty( $post_data[ 'title' ] ) )
							$this->save_errors[] = $this->add_admin_error( sprintf( __( 'The default option that you selected for \'%s\' is inconsistent with the set of active Component Options. Always double-check your preferences before saving, and always save any changes made to the Component Options before choosing new defaults.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) ) );
					}

				} else {

					// If the component option is only one, set it as default
					if ( count( $component_options ) == 1 && ! isset( $post_data[ 'optional' ] ) )
						$bto_data[ $group_id ][ 'default_id' ] = $component_options[0];
					else
						$bto_data[ $group_id ][ 'default_id' ] = '';
				}

				// Save title preferences
				if ( isset( $post_data[ 'title' ] ) && ! empty( $post_data[ 'title' ] ) ) {
					$bto_data[ $group_id ][ 'title' ] = strip_tags ( stripslashes( $post_data[ 'title' ] ) );
				} else {
					$bto_data[ $group_id ][ 'title' ] = 'Untitled Component';
					$this->save_errors[] = $this->add_admin_error( __( 'Please give a valid Name to all Components before publishing.', 'woocommerce-composite-products' ) );

					if ( isset( $posted_composite_data[ 'post_status' ] ) && $posted_composite_data[ 'post_status' ] == 'publish' ) {
						global $wpdb;
						$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
					}
				}

				// Save description preferences
				if ( isset( $post_data[ 'description' ] ) && ! empty( $post_data[ 'description' ] ) ) {
					$bto_data[ $group_id ][ 'description' ] = wp_kses_post( stripslashes( $post_data[ 'description' ] ) );
				} else {
					$bto_data[ $group_id ][ 'description' ] = '';
				}

				// Save quantity data
				if ( isset( $post_data[ 'quantity_min' ] ) && isset( $post_data[ 'quantity_max' ] ) && is_numeric( $post_data[ 'quantity_min' ] ) && is_numeric( $post_data[ 'quantity_max' ] ) ) {

					$quantity_min = absint( $post_data[ 'quantity_min' ] );
					$quantity_max = absint( $post_data[ 'quantity_max' ] );

					if ( $quantity_min >= 0 && $quantity_max >= $quantity_min && $quantity_max > 0 ) {

						$bto_data[ $group_id ][ 'quantity_min' ] = $quantity_min;
						$bto_data[ $group_id ][ 'quantity_max' ] = $quantity_max;

					} else {
						$this->save_errors[] = $this->add_admin_error( sprintf( __( 'The quantities you entered for \'%s\' were not valid and have been reset. Please enter non-negative integer values, with Quantity Min greater than or equal to Quantity Max and Quantity Max greater than zero.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) ) );
						$bto_data[ $group_id ][ 'quantity_min' ] = 1;
						$bto_data[ $group_id ][ 'quantity_max' ] = 1;
					}

				} else {
					// If its not there, it means the product was just added
					$bto_data[ $group_id ][ 'quantity_min' ] = 1;
					$bto_data[ $group_id ][ 'quantity_max' ] = 1;
					$this->save_errors[] = $this->add_admin_error( sprintf( __( 'The quantities you entered for \'%s\' were not valid and have been reset. Please enter non-negative integer values, with Quantity Min greater than or equal to Quantity Max and Quantity Max greater than zero.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) ) );
				}

				// Save discount data
				if ( isset( $post_data[ 'discount' ] ) ) {

					if ( is_numeric( $post_data[ 'discount' ] ) ) {

						$discount = ( float ) wc_format_decimal( $post_data[ 'discount' ] );

						if ( $discount < 0 || $discount > 100 ) {
							$this->save_errors[] = $this->add_admin_error( sprintf( __( 'The discount value you entered for \'%s\' was not valid and has been reset. Please enter a positive number between 0-100.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) ) );
							$bto_data[ $group_id ][ 'discount' ] = '';
						} else {
							$bto_data[ $group_id ][ 'discount' ] = $discount;
						}
					} else {
						$bto_data[ $group_id ][ 'discount' ] = '';
					}
				} else {
					$bto_data[$group_id][ 'discount' ] = '';
				}

				// Save optional data
				if ( isset( $post_data[ 'optional' ] ) ) {
					$bto_data[ $group_id ][ 'optional' ] = 'yes';
				} else {
					$bto_data[ $group_id ][ 'optional' ] = 'no';
				}

				// Save hide product title data
				if ( isset( $post_data[ 'hide_product_title' ] ) ) {
					$bto_data[ $group_id ][ 'hide_product_title' ] = 'yes';
				} else {
					$bto_data[ $group_id ][ 'hide_product_title' ] = 'no';
				}

				// Save hide product description data
				if ( isset( $post_data[ 'hide_product_description' ] ) ) {
					$bto_data[ $group_id ][ 'hide_product_description' ] = 'yes';
				} else {
					$bto_data[ $group_id ][ 'hide_product_description' ] = 'no';
				}

				// Save hide product thumbnail data
				if ( isset( $post_data[ 'hide_product_thumbnail' ] ) ) {
					$bto_data[ $group_id ][ 'hide_product_thumbnail' ] = 'yes';
				} else {
					$bto_data[ $group_id ][ 'hide_product_thumbnail' ] = 'no';
				}

				// Save show orderby data
				if ( isset( $post_data[ 'show_orderby' ] ) ) {
					$bto_data[ $group_id ][ 'show_orderby' ] = 'yes';
				} else {
					$bto_data[ $group_id ][ 'show_orderby' ] = 'no';
				}

				// Save show filters data
				if ( isset( $post_data[ 'show_filters' ] ) ) {
					$bto_data[ $group_id ][ 'show_filters' ] = 'yes';
				} else {
					$bto_data[ $group_id ][ 'show_filters' ] = 'no';
				}

				// Save filters
				if ( ! empty( $post_data[ 'attribute_filters' ] ) ) {

					$attribute_filter_ids = array_map( 'absint', $post_data[ 'attribute_filters' ] );

					$bto_data[ $group_id ][ 'attribute_filters' ] = array_values( $attribute_filter_ids );
				}

				// Save position data
				if ( isset( $post_data[ 'position' ] ) ) {
					$bto_data[ $group_id ][ 'position' ] 		= (int) $post_data[ 'position' ];
					$ordering[ (int) $post_data[ 'position' ] ] = $group_id;
				} else {
					$bto_data[ $group_id ][ 'position' ] = -1;
					$ordering[ count( $ordering ) ]      = $group_id;
				}

				// Process custom data - add custom errors via $woocommerce_composite_products->admin->add_error()
				$bto_data[ $group_id ] = apply_filters( 'woocommerce_composite_process_component_data', $bto_data[ $group_id ], $post_data, $group_id, $post_id );

				// Invalidate query cache
				if ( class_exists( 'WC_Cache_Helper' ) ) {
					WC_Cache_Helper::get_transient_version( 'wccp_q', true );
				}

				if ( ! wp_using_ext_object_cache() ) {
					// Delete query transients
					$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_wccp_q_" . $group_id . "_%') OR `option_name` LIKE ('_transient_timeout_wccp_q_" . $group_id . "_%')" );
				}
			}

			ksort( $ordering );
			$ordered_bto_data = array();

			foreach ( $ordering as $group_id ) {
			    $ordered_bto_data[ $group_id ] = $bto_data[ $group_id ];
			}


			// Prompt user to activate the right options when a Composite includes a large number of Component Options
			if ( $component_options_count > 100 ) {

				$show_large_composite_prompt = false;
				$ppp_prompt                  = '';
				$dropdowns_prompt            = '';

				if ( isset( $_POST[ '_per_product_pricing_bto' ] ) && empty( $_POST[ '_bto_hide_shop_price' ] ) ) {
					$show_large_composite_prompt = true;
					$ppp_prompt = __( ' To avoid placing a big load on your server, consider checking the Hide Price option, located in the General tab. This setting will bypass all min/max pricing calculations which typically happen during product load when the Per-Item Pricing option is checked.', 'woocommerce-composite-products' );
				}

				if ( isset( $posted_composite_data[ 'bto_selection_mode' ] ) && $posted_composite_data[ 'bto_selection_mode' ] === 'dropdowns' ) {
					$show_large_composite_prompt = true;
					$dropdowns_prompt = sprintf( __( ' %s consider switching the Options Style of your Composite to Product Thumbnails. This setting can be changed from the Components tab.', 'woocommerce-composite-products' ), $ppp_prompt === '' ? __( 'To reduce the load on your server and make the configuration process easier,', 'woocommerce-composite-products' ) : __( 'To further reduce the load on your server and make the configuration process easier,', 'woocommerce-composite-products' ) );
				}

				if ( $show_large_composite_prompt ) {
					$large_composite_prompt = sprintf( __( 'You have added a significant number of Component Options to this Composite.%1$s%2$s', 'woocommerce-composite-products' ), $ppp_prompt, $dropdowns_prompt );

					$this->save_errors[] = $this->add_admin_error( $large_composite_prompt );
				}
			}


			/* -------------------------- */
			/* Scenarios
			/* -------------------------- */

			// Convert posted data coming from select2 ajax inputs
			$compat_scenario_data = array();

			if ( isset( $posted_composite_data[ 'bto_scenario_data' ] ) ) {
				foreach ( $posted_composite_data[ 'bto_scenario_data' ] as $scenario_id => $scenario_post_data ) {

					$compat_scenario_data[ $scenario_id ] = $scenario_post_data;

					if ( isset( $scenario_post_data[ 'component_data' ] ) ) {
						foreach ( $scenario_post_data[ 'component_data' ] as $component_id => $products_in_scenario ) {

							if ( ! empty( $products_in_scenario ) ) {
								if ( is_array( $products_in_scenario ) ) {
									$compat_scenario_data[ $scenario_id ][ 'component_data' ][ $component_id ] = array_unique( array_map( 'intval', $products_in_scenario ) );
								} else {
									$compat_scenario_data[ $scenario_id ][ 'component_data' ][ $component_id ] = array_unique( array_map( 'intval', explode( ',', $products_in_scenario ) ) );
								}
							} else {
								$compat_scenario_data[ $scenario_id ][ 'component_data' ][ $component_id ] = array();
							}
						}
					}
				}
				$posted_composite_data[ 'bto_scenario_data' ] = $compat_scenario_data;
			}
			// End conversion

			// Start processing
			$bto_scenario_data = array();

			$ordered_bto_scenario_data = array();

			if ( isset( $posted_composite_data[ 'bto_scenario_data' ] ) ) {

				$counter = 0;
				$scenario_ordering = array();

				foreach ( $posted_composite_data[ 'bto_scenario_data' ] as $scenario_id => $scenario_post_data ) {

					$scenario_id = isset ( $scenario_post_data[ 'scenario_id' ] ) ? stripslashes( $scenario_post_data[ 'scenario_id' ] ) : ( current_time( 'timestamp' ) + $counter );
					$counter++;

					$bto_scenario_data[ $scenario_id ] = array();

					// Save scenario title
					if ( isset( $scenario_post_data[ 'title' ] ) && ! empty( $scenario_post_data[ 'title' ] ) ) {
						$bto_scenario_data[ $scenario_id ][ 'title' ] = strip_tags ( stripslashes( $scenario_post_data[ 'title' ] ) );
					} else {
						unset( $bto_scenario_data[ $scenario_id ] );
						$this->save_errors[] = $this->add_admin_error( __( 'Please give a valid Name to all Scenarios before saving.', 'woocommerce-composite-products' ) );
						continue;
					}

					// Save scenario description
					if ( isset( $scenario_post_data[ 'description' ] ) && ! empty( $scenario_post_data[ 'description' ] ) ) {
						$bto_scenario_data[ $scenario_id ][ 'description' ] = wp_kses_post( stripslashes( $scenario_post_data[ 'description' ] ) );
					} else {
						$bto_scenario_data[ $scenario_id ][ 'description' ] = '';
					}

					// Save position data
					if ( isset( $scenario_post_data[ 'position' ] ) ) {
						$bto_scenario_data[ $scenario_id ][ 'position' ]                = ( int ) $scenario_post_data[ 'position' ];
						$scenario_ordering[ ( int ) $scenario_post_data[ 'position' ] ] = $scenario_id;
					} else {
						$bto_scenario_data[ $scenario_id ][ 'position' ]  = -1;
						$scenario_ordering[ count( $scenario_ordering ) ] = $scenario_id;
					}

					// Save component options in scenario
					$bto_scenario_data[ $scenario_id ][ 'component_data' ] = array();

					foreach ( $ordered_bto_data as $group_id => $group_data ) {

						// Save exclude flag
						if ( isset( $scenario_post_data[ 'exclude' ][ $group_id ] ) && $scenario_post_data[ 'exclude' ][ $group_id ] === 'yes' ) {

								if ( ! empty( $scenario_post_data[ 'component_data' ][ $group_id ] ) ) {

									if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_post_data, $group_id, 0 ) ) {
										$bto_scenario_data[ $scenario_id ][ 'exclude' ][ $group_id ] = 'no';
									} else {
										$bto_scenario_data[ $scenario_id ][ 'exclude' ][ $group_id ] = $scenario_post_data[ 'exclude' ][ $group_id ];
									}
								} else {
									$bto_scenario_data[ $scenario_id ][ 'exclude' ][ $group_id ] = 'no';
								}

						} else {
							$bto_scenario_data[ $scenario_id ][ 'exclude' ][ $group_id ] = 'no';
						}


						$all_active = false;

						if ( ! empty( $scenario_post_data[ 'component_data' ][ $group_id ] ) ) {

							$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ] = array();

							if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_post_data, $group_id, 0 ) ) {

								$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ][] = 0;
								$all_active = true;
							}

							if ( $all_active ) {
								continue;
							}

							if ( $woocommerce_composite_products->api->scenario_contains_product( $scenario_post_data, $group_id, -1 ) ) {
								$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ][] = -1;
							}

							// Run query to get component option ids
							$component_options = $woocommerce_composite_products->api->get_component_options( $group_data );


							foreach ( $scenario_post_data[ 'component_data' ][ $group_id ] as $item_in_scenario ) {

								if ( (int) $item_in_scenario === -1 || (int) $item_in_scenario === 0 ) {
									continue;
								}

								// Get product
								$product_in_scenario = get_product( $item_in_scenario );

								if ( $product_in_scenario->product_type === 'variation' ) {

									$parent_id = $product_in_scenario->id;

									if ( $parent_id && in_array( $parent_id, $component_options ) && ! in_array( $parent_id, $scenario_post_data[ 'component_data' ][ $group_id ] ) ) {
										$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ][] = $item_in_scenario;
									}

								} else {

									if ( in_array( $item_in_scenario, $component_options ) ) {
										$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ][] = $item_in_scenario;
									}
								}
							}

						} else {

							$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ]   = array();
							$bto_scenario_data[ $scenario_id ][ 'component_data' ][ $group_id ][] = 0;
						}

					}

					// Process custom data - add custom errors via $woocommerce_composite_products->admin->add_error()
					$bto_scenario_data[ $scenario_id ] = apply_filters( 'woocommerce_composite_process_scenario_data', $bto_scenario_data[ $scenario_id ], $scenario_post_data, $scenario_id, $ordered_bto_data, $post_id );
				}

				ksort( $scenario_ordering );

				foreach ( $scenario_ordering as $scenario_id ) {
				    $ordered_bto_scenario_data[ $scenario_id ] = $bto_scenario_data[ $scenario_id ];
				}

			}

			// Verify defaults
			if ( ! empty( $ordered_bto_scenario_data ) ) {

				// Only build scenarios for the defaults
				foreach ( $ordered_bto_data as $group_id => $group_data ) {
					$bto_data[ $group_id ][ 'current_component_options' ] = array( $group_data[ 'default_id' ] );
				}

				$scenarios_for_products = $woocommerce_composite_products->api->build_scenarios( $ordered_bto_scenario_data, $bto_data );

				$common_scenarios = array_values( $scenarios_for_products[ 'scenarios' ] );

				foreach ( $ordered_bto_data as $group_id => $group_data ) {

					$default_option_id = $group_data[ 'default_id' ];

					if ( $default_option_id !== '' ) {

						if ( empty( $scenarios_for_products[ 'scenario_data' ][ $group_id ][ $default_option_id ] ) )
							$this->save_errors[] = $this->add_admin_error( sprintf( __( 'The default option that you selected for \'%s\' is not active in any Scenarios. The default Component Options must be compatible in order to work. Always double-check your preferences before saving, and always save any changes made to the Component Options before choosing new defaults.', 'woocommerce-composite-products' ), $group_data[ 'title' ] ) );
						else
							$common_scenarios = array_intersect( $common_scenarios, $scenarios_for_products[ 'scenario_data' ][ $group_id ][ $default_option_id ] );

					}
				}

				if ( empty( $common_scenarios ) )
					$this->save_errors[] = $this->add_admin_error( __( 'The set of default Component Options that you selected was not found in any of the defined Scenarios. The default Component Options must be compatible in order to work. Always double-check the default Component Options before creating or modifying Scenarios.', 'woocommerce-composite-products' ) );
			}

			// Save config
			update_post_meta( $post_id, '_bto_data', $ordered_bto_data );
			update_post_meta( $post_id, '_bto_scenario_data', $ordered_bto_scenario_data );

			// Initialize and save price meta
			$composite = WC_CP_Core_Compatibility::wc_get_product( $post_id );

		}

		if ( ! isset( $posted_composite_data[ 'bto_data' ] ) || count( $bto_data ) == 0 ) {

			delete_post_meta( $post_id, '_bto_data' );

			$this->save_errors[] = $this->add_admin_error( __( 'Please create at least one Component before publishing. To add a Component, go to the Components tab and click on the Add Component button.', 'woocommerce-composite-products' ) );

			if ( isset( $posted_composite_data[ 'post_status' ] ) && $posted_composite_data[ 'post_status' ] == 'publish' ) {
				global $wpdb;
				$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
			}

			return false;
		}

		if ( $zero_product_item_exists ) {
			$this->save_errors[] = $this->add_admin_error( __( 'Please assign at least one valid Component Option to every Component. Once you have added a Component, you can add Component Options to it by selecting products individually, or by choosing product categories.', 'woocommerce-composite-products' ) );
			return false;
		}

		return true;
	}

	/**
	 * Handles saving composite config via ajax.
	 *
	 * @return void
	 */
	function ajax_composite_save() {

		check_ajax_referer( 'wc_bto_save_composite', 'security' );

		parse_str( $_POST[ 'data' ], $posted_composite_data );

		$post_id = absint( $_POST[ 'post_id' ] );

		$this->save_composite_config( $post_id, $posted_composite_data );

		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $this->save_errors );
		die();
	}

	/**
	 * Handles adding components via ajax.
	 *
	 * @return void
	 */
	function ajax_add_component() {

		check_ajax_referer( 'wc_bto_add_component', 'security' );

		$id      = intval( $_POST[ 'id' ] );
		$post_id = intval( $_POST[ 'post_id' ] );

		include( 'html-component-admin.php' );

		die();
	}

	/**
	 * Handles adding scenarios via ajax.
	 *
	 * @return void
	 */
	function ajax_add_scenario() {

		global $woocommerce_composite_products;

		check_ajax_referer( 'wc_bto_add_scenario', 'security' );

		$id      = intval( $_POST[ 'id' ] );
		$post_id = intval( $_POST[ 'post_id' ] );

		$composite_data = get_post_meta( $post_id, '_bto_data', true );
		$scenario_data  = array();

		include( 'html-scenario-admin.php' );

		die();
	}

	/**
	 * Search for default component option and echo json.
	 *
	 * @return void
	 */
	public function json_search_default_component_option() {
		$this->json_search_component_options();
	}

	/**
	 * Search for default component option and echo json.
	 *
	 * @return void
	 */
	public function json_search_component_options_in_scenario() {
		$this->json_search_component_options( 'search_component_options_in_scenario', $post_types = array( 'product', 'product_variation' ) );
	}

	/**
	 * Search for component options and echo json.
	 *
	 * @param   string $x (default: '')
	 * @param   string $post_types (default: array('product'))
	 * @return  void
	 */
	public function json_search_component_options( $x = 'default', $post_types = array( 'product' ) ) {

		global $woocommerce_composite_products;

		ob_start();

		check_ajax_referer( 'search-products', 'security' );

		$term         = (string) wc_clean( stripslashes( $_GET[ 'term' ] ) );
		$composite_id = $_GET[ 'composite_id' ];
		$component_id = $_GET[ 'component_id' ];

		if ( empty( $term ) || empty( $composite_id ) || empty( $component_id ) ) {
			die();
		}

		$composite_data = get_post_meta( $composite_id, '_bto_data', true );
		$component_data = isset( $composite_data[ $component_id ] ) ? $composite_data[ $component_id ] : false;

		if ( false == $composite_data || false == $component_data ) {
			die();
		}

		// Run query to get component option ids
		$component_options = $woocommerce_composite_products->api->get_component_options( $component_data );

		// Add variation ids to component option ids
		if ( $x == 'search_component_options_in_scenario' ) {
			$variations_args = array(
				'post_type'      => array( 'product_variation' ),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'post_parent'    => array_merge( array( '0' ), $component_options ),
				'fields'         => 'ids'
			);

			$component_options_variations = get_posts( $variations_args );

			$component_options = array_merge( $component_options, $component_options_variations );
		}

		if ( is_numeric( $term ) ) {

			$args = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'post__in'       => array(0, $term),
				'fields'         => 'ids'
			);

			$args2 = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => '_sku',
						'value'   => $term,
						'compare' => 'LIKE'
					)
				),
				'fields'         => 'ids'
			);

			$args3 = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'post_parent'    => $term,
				'fields'         => 'ids'
			);

			$posts = array_unique( array_intersect( $component_options, array_merge( get_posts( $args ), get_posts( $args2 ), get_posts( $args3 ) ) ) );

		} else {

			$args = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				's'              => $term,
				'fields'         => 'ids'
			);

			$args2 = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
					'key'     => '_sku',
					'value'   => $term,
					'compare' => 'LIKE'
					)
				),
				'fields'         => 'ids'
			);

			$posts = array_unique( array_intersect( $component_options, array_merge( get_posts( $args ), get_posts( $args2 ) ) ) );

		}

		$found_products = array();
		$loop           = 0;

		if ( $posts ) {
			foreach ( $posts as $post ) {

				if ( $loop > 1000 ) {
					continue;
				}

				$product = WC_CP_Core_Compatibility::wc_get_product( $post );

				if ( $product->product_type === 'variation' ) {
					$found_products[ $post ] = $woocommerce_composite_products->api->get_product_variation_title( $product );
				} else {
					if ( $x == 'search_component_options_in_scenario' && $product->product_type === 'variable' ) {
						$found_products[ $post ] = $woocommerce_composite_products->api->get_product_title( $product ) . ' ' . __( '&mdash; All Variations', 'woocommerce-composite-products' );
					} else {
						$found_products[ $post ] = $woocommerce_composite_products->api->get_product_title( $product );
					}
				}

				$loop++;
			}
		}

		wp_send_json( $found_products );
	}

	/**
	 * Support scanning for template overrides in extension.
	 *
	 * @param  array   $paths paths to check
	 * @return array          modified paths to check
	 */
	function composite_template_scan_path( $paths ) {

		global $woocommerce_composite_products;

		$paths[ 'WooCommerce Composite Products' ] = $woocommerce_composite_products->plugin_path() . '/templates/';

		return $paths;
	}

	/**
	 * Add and return admin errors.
	 *
	 * @param  string $error
	 * @return string
	 */
	private function add_admin_error( $error ) {

		WC_Admin_Meta_Boxes::add_error( $error );

		return $error;
	}

	/**
	 * Add custom save errors via filters.
	 *
	 * @param string $error
	 */
	function add_error( $error ) {

		$this->save_errors[] = $this->add_admin_error( $error );
	}
}
