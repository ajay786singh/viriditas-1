/* jshint -W069 */
/* jshint -W041 */
/* jshint -W018 */
jQuery( document ).ready( function($) {

	$( 'body' ).on( 'quick-view-displayed', function() {

		$( '.composite_form .composite_data' ).each( function() {
			$(this).wc_composite_form();
		} );
	} );

	// responsive form CSS (we can't rely on media queries since we must work with the .composite_form width, not screen width)
	$( window ).resize( function() {

		$( '.composite_form' ).each( function() {

			if ( $(this).width() <= wc_composite_params.small_width_threshold ) {
				$(this).addClass( 'small_width' );
			} else {
				$(this).removeClass( 'small_width' );
			}

			if ( $(this).width() > wc_composite_params.full_width_threshold ) {
				$(this).addClass( 'full_width' );
			} else {
				$(this).removeClass( 'full_width' );
			}

			if ( wc_composite_params.legacy_width_threshold ) {
				if ( $(this).width() <= wc_composite_params.legacy_width_threshold ) {
					$(this).addClass( 'legacy_width' );
				} else {
					$(this).removeClass( 'legacy_width' );
				}
			}
		} );

	} ).trigger( 'resize' );

	// blockui background params
	var wc_cp_block_params = {};

	if ( wc_composite_params.is_wc_version_gte_2_3 === 'yes' ) {

		wc_cp_block_params = {
			message:    null,
			overlayCSS: {
				background: 'rgba( 255, 255, 255, 0 )',
				opacity:    0.6
			}
		};
	} else {

		wc_cp_block_params = {
			message:    null,
			overlayCSS: {
				background: 'rgba( 255, 255, 255, 0 ) url(' + woocommerce_params.ajax_loader_url + ') no-repeat center',
				backgroundSize: '20px 20px',
				opacity:    0.6
			}
		};
	}

	$.fn.wc_composite_form = function() {

		if ( ! $(this).hasClass( 'composite_data' ) )
			return true;

		// Event Listeners

		/**
		 * Change the post names of variation/attribute fields to make them unique (for debugging purposes)
		 * Data from these fields is copied and posted in new unique vars - see below
		 * To maintain variations script compatibility with WC 2.2, we can't use our own unique field names in the variable-product.php template
		 */
		$( '.composite_add_to_cart_button' )

			.on( 'click', function() {

				$(this).closest( '.composite_form' ).children( '.component' ).each( function() {

					var item_id = $(this).attr( 'data-item_id' );

					$(this).find( '.variations_form .variations .attribute-options select, .variations_form .component_wrap input[name="variation_id"]' ).each( function() {

						$(this).attr( 'name', $(this).attr( 'name' ) + '_' + item_id );
					} );

					$(this).find( 'select, input' ).each( function() {

						$(this).prop( 'disabled', false );
					} );
				} );
			} );


		/**
		 * Update composite totals when a new NYP price is entered at composite level
		 */
		$(this).on( 'woocommerce-nyp-updated-item', function() {

			var form = $(this).closest( '.composite_form' );
			var nyp  = $(this).find( '.nyp' );

			if ( nyp.length > 0 ) {

				var price_data = $(this).data( 'price_data' );

				price_data[ 'base_price' ]      = nyp.data( 'price' );
				price_data[ 'price_undefined' ] = false;

				wc_cp_update_composite( form, true );
			}
		} );


		$( '.component' )

			.on( 'wc-composite-component-loaded', function() {

				if ( $.isFunction( $.fn.prettyPhoto ) ) {

					$(this).find( 'a[data-rel^="prettyPhoto"]' ).prettyPhoto( {
						hook: 'data-rel',
						social_tools: false,
						theme: 'pp_woocommerce',
						horizontal_padding: 20,
						opacity: 0.8,
						deeplinking: false
					} );
				}
			} )

			/**
			 * Update composite totals when a new Add-on is selected
			 */
			.on( 'woocommerce-product-addons-update', function() {

				var addons = $(this).find( '.addon' );

				if ( addons.length == 0 )
					return false;

				var form = $(this).closest( '.composite_form' );

				wc_cp_update_composite( form, true );
			} )

			/**
			 * Update composite totals when a new NYP price is entered
			 */
			.on( 'woocommerce-nyp-updated-item', function() {

				var item = $(this);
				var form = $(this).closest( '.composite_form' );
				var nyp  = $(this).find( '.cart .nyp' );

				if ( nyp.length > 0 && item.find( '.component_data' ).data( 'product_type' ) !== 'variable' ) {

					item.find( '.component_data' ).data( 'price', nyp.data( 'price' ) );
					item.find( '.component_data' ).data( 'regular_price', nyp.data( 'price' ) );

					wc_cp_update_composite( form, true );
				}
			} )

			/**
			 * Reset composite totals and form inputs when a new variation selection is initiated
			 */
			.on( 'woocommerce_variation_select_change', function( event ) {

				var summary = $(this).find( '.component_summary' );

				wc_cp_update_component_state( $(this) );

				// erase input data in submit form
				var item    = $(this);
				var form    = item.closest( '.composite_form' );
				var item_id = item.attr( 'data-item_id' );

				// Reset submit form data - TODO: get rid of this by making the variations script usable regardless of the variations form input names (https://github.com/woothemes/woocommerce/pull/6531)
				form.find( '.composite_data .composite_wrap .composite_button .form_data_' + item_id + ' .variation_input' ).remove();
				form.find( '.composite_data .composite_wrap .composite_button .form_data_' + item_id + ' .attribute_input' ).remove();

				// Mark component as not set
				summary.find( '.component_data' ).data( 'component_set', false );

				// Add images class to composited_product_images div ( required by the variations script to flip images )
				summary.find( '.composited_product_images' ).addClass( 'images' );

				$(this).find( '.variations .attribute-options select' ).each( function() {
					if ( $(this).val() === '' ) {
						summary.find( '.component_wrap .single_variation' ).html( '' );
						wc_cp_update_composite( form );
						return false;
					}
				} );
			} )

			.on( 'woocommerce_variation_select_focusin', function( event ) {

				wc_cp_update_selections( $(this) );
			} )

			.on( 'reset_image', function( event ) {

				var summary = $(this).find( '.component_summary' );

				// Remove images class from composited_product_images div in order to avoid styling issues
				summary.find( '.composited_product_images' ).removeClass( 'images' );
			} )

			/**
			 * Update composite totals and form inputs when a new variation is selected
			 */
			.on( 'found_variation', function( event, variation ) {

				var summary = $(this).find( '.component_summary' );

				wc_cp_update_component_state( $(this) );

				var item    = $(this);
				var form    = item.closest( '.composite_form' );
				var item_id = item.attr( 'data-item_id' );

				// Start copying submit form data - TODO: get rid of this by making the variations script usable regardless of the variations form input names (https://github.com/woothemes/woocommerce/pull/6531)
				var variation_data 	= '<input type="hidden" name="wccp_variation_id[' + item_id + ']" class="variation_input" value="' + variation.variation_id + '"/>';
				form.find( '.composite_data .composite_wrap .composite_button .form_data_' + item_id ).append( variation_data );

				for ( var attribute in variation.attributes ) {
					var attribute_data 	= '<input type="hidden" name="wccp_' + attribute + '[' + item_id + ']" class="attribute_input" value="' + $(this).find( '.variations .attribute-options select[name="' + attribute + '"]' ).val() + '"/>';
					form.find( '.composite_data .composite_wrap .composite_button .form_data_' + item_id ).append( attribute_data );
				}
				// End copying form data

				// Copy variation price data
				var price_data = form.find( '.composite_data' ).data( 'price_data' );

				if ( price_data[ 'per_product_pricing' ] == true ) {
					summary.find( '.component_data' ).data( 'price', variation.price );
					summary.find( '.component_data' ).data( 'regular_price', variation.regular_price );
				}

				// Mark component as set
				summary.find( '.component_data' ).data( 'component_set', true );

				// Remove images class from composited_product_images div in order to avoid styling issues
				summary.find( '.composited_product_images' ).removeClass( 'images' );

				// Handle sold_individually variations qty
				if ( variation.is_sold_individually === 'yes' ) {
					$(this).find( '.component_wrap input.qty' ).val( '1' ).change();
				}

				wc_cp_update_composite( form );
			} )

			/**
			 * Event triggered by custom product types to indicate that the state of the component selection has changed
			 */
			.on ( 'woocommerce-composited-product-update', function( event ) {

				var summary = $(this).find( '.component_summary' );

				wc_cp_update_component_state( $(this) );

				var item    = $(this);
				var form    = item.closest( '.composite_form' );

				var price_data = form.find( '.composite_data' ).data( 'price_data' );

				if ( price_data[ 'per_product_pricing' ] == true ) {

					var bundle_price         = summary.find( '.component_data' ).data( 'price' );
					var bundle_regular_price = summary.find( '.component_data' ).data( 'regular_price' );

					summary.find( '.component_data' ).data( 'price', bundle_price );
					summary.find( '.component_data' ).data( 'regular_price', bundle_regular_price );
				}

				wc_cp_update_composite( form );
			} )

			/**
			 * On clicking the clear options button
			 */
			.on( 'click', '.clear_component_options', function( event ) {

				if ( $(this).hasClass( 'reset_component_options' ) )
					return false;

				var item      = $(this).closest( '.component' );
				var selection = item.find( '.component_options select.component_options_select' );

				wc_cp_unblock_component_options( item );

				item.find( '.component_option_thumbnails .selected' ).removeClass( 'selected' );

				selection.val('').change();

				return false;
			} )

			/**
			 * On clicking the reset options button
			 */
			.on( 'click', '.reset_component_options', function( event ) {

				var item       = $(this).closest( '.component' );
				var form       = item.closest( '.composite_form' );
				var item_index = form.find( '.component' ).index( item );
				var selection  = item.find( '.component_options select.component_options_select' );

				wc_cp_unblock_component_options( item );

				item.find( '.component_option_thumbnails .selected' ).removeClass( 'selected' );

				wc_cp_set_active_component( item );

				selection.val('').change();

				wc_cp_block_components( form, item_index );

				return false;
			} )

			/**
			 * On clicking the blocked area in progressive mode
			 */
			.on( 'click', '.block_component_selections_inner', function( event ) {

				var item       = $(this).closest( '.component' );
				var form       = item.closest( '.composite_form' );
				var item_index = form.find( '.component' ).index( item );

				wc_cp_block_components( form, item_index );
				wc_cp_show_component( item );

				return false;
			} )

			/**
			 * On clicking a thumbnail
			 */
			.on( 'click', '.component_option_thumbnail', function( event ) {

				var item = $(this).closest( '.component' );

				if ( item.hasClass( 'disabled' ) || $(this).hasClass( 'disabled' ) )
					return true;

				$(this).blur();

				if ( ! $(this).hasClass( 'selected' ) ) {
					var value = $(this).data( 'val' );
					$(this).closest( '.component_options' ).find( 'select.component_options_select' ).val( value ).change();
				}

			} )

			.on( 'click', 'a.component_option_thumbnail_tap', function( event ) {
				$(this).closest( '.component_option_thumbnail' ).trigger( 'click' );
				return false;
			} )

			.on( 'focusin', '.component_options select.component_options_select', function( event ) {

				wc_cp_update_selections( $(this).closest( '.component' ) );

			} )

			/**
			 * On changing a component option
			 */
			.on( 'change', '.component_options select.component_options_select', function( event ) {

				var item                 = $(this).closest( '.component' );
				var component_selections = item.find( '.component_selections' );
				var component_content    = item.find( '.component_content' );
				var component_summary    = item.find( '.component_summary' );
				var summary_content      = item.find( '.component_summary > .content' );
				var item_id              = item.attr( 'data-item_id' );
				var form                 = item.closest( '.composite_form' );
				var container_id         = form.find( '.composite_data' ).data( 'container_id' );
				var form_data            = form.find( '.composite_data .composite_wrap .composite_button .form_data_' + item_id );
				var style                = form.find( '.composite_data' ).data( 'bto_style' );
				var scroll_to            = '';

				if ( style === 'paged' ) {
					scroll_to = form.find( '.scroll_select_component_option' );
				} else {
					scroll_to = item.next( '.component' );
				}

				var load_height    = component_summary.outerHeight();
				var new_height     = 0;
				var animate_height = false;

				$(this).blur();

				// Reset submit data
				form_data.find( '.variation_input' ).remove();
				form_data.find( '.attribute_input' ).remove();

				// Select thumbnail
				item.find( '.component_option_thumbnails .selected' ).removeClass( 'selected disabled' );
				item.find( '#component_option_thumbnail_' + $(this).val() ).addClass( 'selected' );

				var data = {
					action: 		'woocommerce_show_composited_product',
					product_id: 	$(this).val(),
					component_id: 	item_id,
					composite_id: 	container_id,
					security: 		wc_composite_params.show_product_nonce
				};

				// Remove all event listeners
				summary_content.removeClass( 'variations_form bundle_form cart' );
				component_content.off().find('*').off();

				if ( data.product_id !== '' ) {

					// block component selections
					component_selections.addClass( 'blocked_content' ).block( wc_cp_block_params );

					// block composite
					form.addClass( 'updating' );

					// get product info via ajax
					$.post( woocommerce_params.ajax_url, data, function( response ) {

						try {

							if ( response.product_data.purchasable === 'yes' ) {

								// lock height
								component_content.css( 'height', load_height );

								// put content in place
								summary_content.html( response.markup );

								wc_cp_init_quantity_buttons( item );
								wc_cp_initialize_component_scripts( item );

								wc_cp_update_selections( item );
								wc_cp_update_component_state( item );
								wc_cp_update_composite( form );

								item.trigger( 'wc-composite-component-loaded' );

								// measure height
								component_content.css( 'height', 'auto' );

								new_height = component_summary.outerHeight();

								if ( Math.abs( new_height - load_height ) > 1 ) {

									animate_height = true;

									// lock height
									component_content.css( 'height', load_height );
								}

							} else {

								// lock height
								component_content.css( 'height', load_height );

								summary_content.html( response.markup );

								// disable incompatible products and variations
								wc_cp_update_selections( item );
								wc_cp_update_component_state( item );

								wc_cp_update_summary( form );
								wc_cp_hide_composite( form );
							}

						} catch ( err ) {

							// show failure message
							console.log( err );

							// lock height
							component_content.css( 'height', load_height );

							// reset content
							summary_content.html( '<div class="component_data" data-component_set="true" data-price="0" data-regular_price="0" data-product_type="none" style="display:none;"></div>' );

							// disable incompatible products and variations
							wc_cp_update_selections( item );
							wc_cp_update_component_state( item );

							wc_cp_hide_composite( form );
						}

						// animate component content height and scroll to selected product details
						window.setTimeout( function() {

							if ( animate_height ) {

								// re-measure height to account for animations in loaded markup
								component_content.css( 'height', 'auto' );

								new_height = component_summary.outerHeight();

								if ( Math.abs( new_height - load_height ) > 1 ) {

									animate_height = true;

									// lock height
									component_content.css( 'height', load_height );
								}

								// animate component content height
								component_content.animate( { 'height' : new_height }, { duration: 200, queue: false, always: function() {

									// scroll
									if ( scroll_to.length > 0 && ! scroll_to.is_in_viewport( true ) ) {

										var window_offset = 0;

										if ( style === 'paged' ) {
											window_offset = scroll_to.hasClass( 'scroll_bottom' ) ? $(window).height() - 80 : 50;
										} else {
											window_offset = $(window).height() - 70;
										}

										$( 'html, body' ).animate( { scrollTop: scroll_to.offset().top - window_offset }, { duration: 200, queue: false, always: function() {

											// reset height
											component_content.css( { 'height' : 'auto' } );

											// unblock component
											component_selections.unblock().removeClass( 'blocked_content' );
											form.removeClass( 'updating' );

										} } );

									} else {

										// reset height
										component_content.css( { 'height' : 'auto' } );

										// unblock component
										component_selections.unblock().removeClass( 'blocked_content' );
										form.removeClass( 'updating' );
									}

								} } );

							} else {

								// scroll
								if ( scroll_to.length > 0 && ! scroll_to.is_in_viewport( true ) ) {

									var window_offset = 0;

									if ( style === 'paged' ) {
										window_offset = scroll_to.hasClass( 'scroll_bottom' ) ? $(window).height() - 80 : 50;
									} else {
										window_offset = $(window).height() - 70;
									}

									$( 'html, body' ).animate( { scrollTop: scroll_to.offset().top - window_offset }, { duration: 200, queue: false, always: function() {

										// unblock component
										component_selections.unblock().removeClass( 'blocked_content' );
										form.removeClass( 'updating' );

									} } );

								} else {

									// unblock component
									component_selections.unblock().removeClass( 'blocked_content' );
									form.removeClass( 'updating' );
								}
							}

						}, 250 );

					}, 'json' );

				} else {

					// lock height
					component_content.css( 'height', load_height );

					// reset content
					summary_content.html( '<div class="component_data" data-component_set="true" data-price="0" data-regular_price="0" data-product_type="none" style="display:none;"></div>' );

					wc_cp_update_selections( item );
					wc_cp_update_component_state( item );
					wc_cp_update_composite( form );

					// animate component content height
					component_content.animate( { 'height': component_summary.outerHeight() }, { duration: 200, queue: false, always: function() {
						component_content.css( { 'height': 'auto' } );
					} } );
				}

			} )

			/**
			 * Refresh component options upon clicking on a component options page
			 */
			.on( 'click', '.component_pagination a.component_pagination_element', function( event ) {

				var item                    = $(this).closest( '.component' );
				var item_id                 = item.attr( 'data-item_id' );
				var component_options       = item.find( '.component_options' );
				var component_ordering      = item.find( '.component_ordering select' );
				var form                    = item.closest( '.composite_form' );

				// Variables to post
				var page                    = parseInt( $(this).data( 'page_num' ) );
				var selected_option         = component_options.find( 'select.component_options_select' ).val();
				var container_id            = form.find( '.composite_data' ).data( 'container_id' );
				var filters                 = wc_cp_get_active_component_filters( item );

				var data = {
					action: 			'woocommerce_show_component_options',
					load_page: 			page,
					component_id: 		item_id,
					composite_id: 		container_id,
					selected_option: 	selected_option,
					filters:            filters,
					security: 			wc_composite_params.show_product_nonce
				};

				// Current 'orderby' setting
				if ( component_ordering.length > 0 ) {
					data.orderby = component_ordering.val();
				}

				// Update component options
				if ( data.load_page > 0 ) {
					$(this).blur();
					wc_cp_show_component_options( item, data );
				}

				// Finito
				return false;

			} )

			/**
			 * Refresh component options upon reordering
			 */
			.on( 'change', '.component_ordering select', function( event ) {

				var item                    = $(this).closest( '.component' );
				var item_id                 = item.attr( 'data-item_id' );
				var component_options       = item.find( '.component_options' );
				var form                    = item.closest( '.composite_form' );

				// Variables to post
				var selected_option         = component_options.find( 'select.component_options_select' ).val();
				var container_id            = form.find( '.composite_data' ).data( 'container_id' );
				var orderby                 = $(this).val();
				var filters                 = wc_cp_get_active_component_filters( item );

				var data = {
					action: 			'woocommerce_show_component_options',
					load_page: 			1,
					component_id: 		item_id,
					composite_id: 		container_id,
					selected_option: 	selected_option,
					orderby: 			orderby,
					filters:            filters,
					security: 			wc_composite_params.show_product_nonce
				};

				$(this).blur();

				// Update component options
				wc_cp_show_component_options( item, data );

				// Finito
				return false;

			} )

			/**
			 * Refresh component options upon activating a filter
			 */
			.on( 'click', '.component_filter_option a', function( event ) {

				var item                    = $(this).closest( '.component' );
				var item_id                 = item.attr( 'data-item_id' );
				var component_options       = item.find( '.component_options' );
				var component_ordering      = item.find( '.component_ordering select' );
				var form                    = item.closest( '.composite_form' );

				var component_filter_option = $(this).closest( '.component_filter_option' );

				// Variables to post
				var selected_option         = component_options.find( 'select.component_options_select' ).val();
				var container_id            = form.find( '.composite_data' ).data( 'container_id' );
				var filters                 = {};

				if ( ! component_filter_option.hasClass( 'selected' ) ) {
					component_filter_option.addClass( 'selected' );
				} else {
					component_filter_option.removeClass( 'selected' );
				}

				// add / remove 'active' classes
				wc_cp_update_component_filters_ui( item );

				// get active filters
				filters = wc_cp_get_active_component_filters( item );

				var data = {
					action: 			'woocommerce_show_component_options',
					load_page: 			1,
					component_id: 		item_id,
					composite_id: 		container_id,
					selected_option: 	selected_option,
					filters: 			filters,
					security: 			wc_composite_params.show_product_nonce
				};

				// Current 'orderby' setting
				if ( component_ordering.length > 0 ) {
					data.orderby = component_ordering.val();
				}

				$(this).blur();

				// Update component options
				wc_cp_show_component_options( item, data );

				// Finito
				return false;

			} )

			/**
			 * Refresh component options upon resetting all filters
			 */
			.on( 'click', '.component_filters a.reset_component_filters', function( event ) {

				var item                    = $(this).closest( '.component' );
				var item_id                 = item.attr( 'data-item_id' );
				var component_options       = item.find( '.component_options' );
				var component_ordering      = item.find( '.component_ordering select' );
				var form                    = item.closest( '.composite_form' );

				// Get active filters
				var component_filter_options = item.find( '.component_filters .component_filter_option.selected' );

				if ( component_filter_options.length == 0 ) {
					return false;
				}

				// Variables to post
				var selected_option         = component_options.find( 'select.component_options_select' ).val();
				var container_id            = form.find( '.composite_data' ).data( 'container_id' );
				var filters                 = {};

				component_filter_options.removeClass( 'selected' );

				// add / remove 'active' classes
				wc_cp_update_component_filters_ui( item );

				var data = {
					action: 			'woocommerce_show_component_options',
					load_page: 			1,
					component_id: 		item_id,
					composite_id: 		container_id,
					selected_option: 	selected_option,
					filters: 			filters,
					security: 			wc_composite_params.show_product_nonce
				};

				// Current 'orderby' setting
				if ( component_ordering.length > 0 ) {
					data.orderby = component_ordering.val();
				}

				$(this).blur();

				// Update component options
				wc_cp_show_component_options( item, data );

				// Finito
				return false;

			} )

			/**
			 * Refresh component options upon resetting a filter
			 */
			.on( 'click', '.component_filters a.reset_component_filter', function( event ) {

				var item                    = $(this).closest( '.component' );
				var item_id                 = item.attr( 'data-item_id' );
				var component_options       = item.find( '.component_options' );
				var component_ordering      = item.find( '.component_ordering select' );
				var form                    = item.closest( '.composite_form' );

				// Get active filters
				var component_filter_options = $(this).closest( '.component_filter' ).find( '.component_filter_option.selected' );

				if ( component_filter_options.length == 0 ) {
					return false;
				}

				// Variables to post
				var selected_option         = component_options.find( 'select.component_options_select' ).val();
				var container_id            = form.find( '.composite_data' ).data( 'container_id' );
				var filters                 = {};

				component_filter_options.removeClass( 'selected' );

				// add / remove 'active' classes
				wc_cp_update_component_filters_ui( item );

				// get active filters
				filters = wc_cp_get_active_component_filters( item );

				var data = {
					action: 			'woocommerce_show_component_options',
					load_page: 			1,
					component_id: 		item_id,
					composite_id: 		container_id,
					selected_option: 	selected_option,
					filters: 			filters,
					security: 			wc_composite_params.show_product_nonce
				};

				// Current 'orderby' setting
				if ( component_ordering.length > 0 ) {
					data.orderby = component_ordering.val();
				}

				$(this).blur();

				// Update component options
				wc_cp_show_component_options( item, data );

				// Finito
				return false;

			} )

			/**
			 * Expand / Collapse filters
			 */
			.on( 'click', '.component_filter_title label', function( event ) {

				var component_filter         = $(this).closest( '.component_filter' );
				var component_filter_content = component_filter.find( '.component_filter_content' );

				wc_cp_toggle_element( component_filter, component_filter_content );

				$(this).blur();

				// Finito
				return false;

			} )

			/**
			 * Expand / Collapse components
			 */
			.on( 'click', '.component_title', function( event ) {

				var component       = $(this).closest( '.component' );
				var component_inner = component.find( '.component_inner' );

				if ( ! component.hasClass( 'toggled' ) || $(this).hasClass( 'inactive' ) ) {
					return false;
				}

				if ( component.hasClass( 'progressive' ) && component.hasClass( 'active' ) ) {
					return false;
				}

				wc_cp_toggle_element( component, component_inner );

				if ( component.hasClass( 'progressive' ) && component.hasClass( 'blocked' ) ) {
					window.setTimeout( function() {
						form.find( '.page_button.next' ).click();
					}, 200 );
				}

				$(this).blur();

				// Finito
				return false;

			} )

			/**
			 * Update composite totals upon changing quantities
			 */
			.on( 'change', '.component_wrap input.qty', function( event ) {

				var form = $(this).closest( '.composite_form' );
				var min  = parseFloat( $(this).attr( 'min' ) );
				var max  = parseFloat( $(this).attr( 'max' ) );

				if ( min >= 0 && parseFloat( $(this).val() ) < min ) {
					$(this).val( min );
				}

				if ( max > 0 && parseFloat( $(this).val() ) > max ) {
					$(this).val( max );
				}

				wc_cp_update_composite( form );
			} );


		/**
		 * On clicking the Next / Previous navigation buttons
		 */
		$( '.composite_navigation' )

			.on( 'click', '.page_button', function( event ) {

				var form      = $(this).closest( '.composite_form' );
				var next_item = form.find( '.multistep.next' );
				var prev_item = form.find( '.multistep.prev' );

				if ( $(this).hasClass( 'next' ) ) {

					if ( next_item.hasClass( 'multistep' ) ) {

						wc_cp_show_component( next_item );

					} else {

						var scroll_to = form.find( '.scroll_final_step' );

						if ( scroll_to.length > 0 ) {

							var window_offset = scroll_to.hasClass( 'scroll_bottom' ) ? $(window).height() - 80 : 80;

							$( 'html, body' ).animate( { scrollTop: scroll_to.offset().top - window_offset }, 200 );

						} else {

							wc_cp_show_component( form.find( '.multistep.cart' ) );
						}
					}

				} else {

					if ( prev_item.hasClass( 'multistep' ) ) {

						wc_cp_show_component( prev_item );
					}
				}

				return false;

			} );


		/**
		 * On clicking a composite pagination link
		 */
		$( '.composite_pagination' )

			.on( 'click', '.pagination_element a', function( event ) {

				var form = $(this).closest( '.composite_form' );

				if ( $(this).hasClass( 'inactive' ) || form.hasClass( 'updating' ) )
					return false;

				var item_id   = $(this).closest( '.pagination_element' ).data( 'item_id' );
				var show_item = form.children( '.multistep[data-item_id="' + item_id + '"]' );

				wc_cp_show_component( show_item );

				return false;

			} );


		/**
		 * On clicking a composite summary link
		 */
		$( '.composite_summary' )

			.on( 'click', '.summary_element_link', function( event ) {

				var form              = $(this).closest( '.composite_form' );
				var composite_summary = $(this).closest( '.composite_summary' );

				if ( composite_summary.hasClass( 'widget_composite_summary' ) ) {

					var container_id = composite_summary.find( '.widget_composite_summary_content' ).data( 'container_id' );

					form = $( '#composite_data_' + container_id ).closest( '.composite_form' );
				}

				if ( $(this).hasClass( 'disabled' ) || form.hasClass( 'updating' ) ) {
					return false;
				}

				var element_index = composite_summary.find( '.summary_element' ).index( $(this).closest( '.summary_element' ) );
				var show_item     = form.children( '.multistep.component:eq(' + element_index + ')' );

				if ( show_item.hasClass( 'progressive' ) ) {
					wc_cp_block_components( form, element_index );
				}

				if ( ! show_item.hasClass( 'active' ) ) {
					wc_cp_show_component( show_item );
				}

				return false;

			} )

			.on( 'click', 'a.summary_element_tap', function( event ) {
				$(this).closest( '.summary_element_link' ).trigger( 'click' );
				return false;
			} );


		/**
		 * Initial states and loading
		 */

		// Save composite stock status
		if ( $(this).find( '.composite_wrap p.stock' ).length > 0 ) {
			this.data( 'stock_status', $(this).find( '.composite_wrap p.stock' ).clone().wrap( '<div>' ).parent().html() );
		}

		// Add-ons support - move totals container
		var addons_totals = $(this).find( '#product-addons-total' );
		$(this).find( '.composite_price' ).after( addons_totals );

		// NYP support
		$(this).find( '.nyp' ).trigger( 'woocommerce-nyp-updated-item' );

		var style           = $(this).data( 'bto_style' );
		var style_variation = $(this).data( 'bto_style_variation' );
		var form            = $(this).closest( '.composite_form' );

		if ( style === 'paged' ) {

			// If the composite-add-to-cart.php template is added right before the component divs, the step-based process will be replaced with a summary-based process
			if ( style_variation === 'componentized' ) {

				form.find( '.multistep.active' ).removeClass( 'active' );
				$(this).addClass( 'multistep active' );

				form.find( '.composite_pagination .pagination_element_review' ).remove();

				$( '.widget_composite_summary' ).hide();

			// If the composite-add-to-cart.php template is added right after the component divs, it will be used as the final step of the step-based configuration process
			} else if ( $(this).prev().hasClass( 'multistep' ) ) {

				$(this).addClass( 'multistep' );
				$(this).hide();

			} else {

				form.find( '.composite_pagination .pagination_element_review' ).remove();
			}
		}

		if ( style === 'progressive' ) {
			form.find( '.toggled:not(.active) .component_title' ).addClass( 'inactive' );
		}

		$(this).trigger( 'wc-composite-initialization' );

		/*
		 * Initialize component selection states and quantities for all modes
		 */
		form.find( '.component' ).each( function( index ) {

			var item = $(this);

			// Load main component scripts
			wc_cp_initialize_component_scripts( item );

			// Load 3rd party scripts
			item.trigger( 'wc-composite-component-loaded' );

		} );

		// Set the form as initialized
		form.find( '.composite_data' ).data( 'composite_initialized', true );
		form.css( 'visibility', 'visible' );

		/*
		 * Initialize component selections state and quantities for progressive and paged modes
		 */
		 var active_item;

		if ( style === 'paged' || style === 'progressive' ) {

			active_item = form.find( '.multistep.active' );

			wc_cp_show_component( active_item, false );

			wc_cp_update_composite( form );

		} else {

			active_item = form.find( '.component.first' );

			wc_cp_set_active_component( active_item );

			wc_cp_update_selections( active_item );

			wc_cp_update_composite( form );

		}

		// Let 3rd part scripts know that all component options are loaded
		form.find( '.component' ).each( function( index ) {
			$(this).trigger( 'wc-composite-component-options-loaded' );
		} );

	};


	/**
	 * Construct a variable product selected attributes short description
	 */
	function wc_cp_get_variable_product_attributes_description( variations ) {

		var attribute_options        = variations.find( '.attribute-options' );
		var attribute_options_length = attribute_options.length;
		var meta                     = '';

		if ( attribute_options_length == 0 )
			return '';

		attribute_options.each( function( index ) {
			var selected = $(this).find( 'select' ).val();

			if ( selected === '' ) {
				meta = '';
				return false;
			}

			meta = meta + $(this).data( 'attribute_label' ) + ': ' + $(this).find( 'select option:selected' ).text();

			if ( index !== attribute_options_length - 1 ) {
				meta = meta + ', ';
			}
		} );

		return meta;
	}


	/**
	 * Add active/filtered classes to the component filters markup, can be used for styling purposes
	 */
	function wc_cp_update_component_filters_ui( item ) {

		var component_filters = item.find( '.component_filters' );
		var filters           = component_filters.find( '.component_filter' );
		var all_empty         = true;

		if ( filters.length == 0 ) {
			return false;
		}

		filters.each( function() {

			if ( $(this).find( '.component_filter_option.selected' ).length == 0 ) {
				$(this).removeClass( 'active' );
			} else {
				$(this).addClass( 'active' );
				all_empty = false;
			}

		} );

		if ( all_empty ) {
			component_filters.removeClass( 'filtered' );
		} else {
			component_filters.addClass( 'filtered' );
		}

	}


	/**
	 * Collect active component filters and options and build an object for posting
	 */
	function wc_cp_get_active_component_filters( item ) {

		var component_filters = item.find( '.component_filters' );
		var filters           = {};

		if ( component_filters.length == 0 ) {
			return filters;
		}

		component_filters.find( '.component_filter_option.selected' ).each( function() {

			var filter_type = $(this).closest( '.component_filter' ).data( 'filter_type' );
			var filter_id   = $(this).closest( '.component_filter' ).data( 'filter_id' );
			var option_id   = $(this).data( 'option_id' );

			if ( filter_type in filters ) {

				if ( filter_id in filters[ filter_type ] ) {

					filters[ filter_type ][ filter_id ].push( option_id );

				} else {

					filters[ filter_type ][ filter_id ] = [];
					filters[ filter_type ][ filter_id ].push( option_id );
				}

			} else {

				filters[ filter_type ]              = {};
				filters[ filter_type ][ filter_id ] = [];
				filters[ filter_type ][ filter_id ].push( option_id );
			}

		} );

		return filters;
	}


	/**
	 * Update the available component options via ajax - called upon sorting, updating filters, or viewing a new page
	 */
	function wc_cp_show_component_options( item, data ) {

		var component_selections    = item.find( '.component_selections' );
		var component_options       = item.find( '.component_options' );
		var component_options_inner = component_options.find( '.component_options_inner' );
		var component_pagination    = item.find( '.component_pagination' );
		var form                    = item.closest( '.composite_form' );
		var load_height             = component_options.outerHeight();
		var new_height              = 0;
		var animate_height          = false;

		// Do nothing if the component is disabled
		if ( item.hasClass( 'disabled' ) ) {
			return false;
		}

		var animate_component_options = function() {

			// animate component options container
			window.setTimeout( function() {

				if ( animate_height ) {

					component_options.animate( { 'height' : new_height }, { duration: 200, queue: false, always: function() {
						component_options.css( { 'height' : 'auto' } );
						component_selections.unblock().removeClass( 'blocked_content refresh_component_options' );
					} } );

				} else {
					component_selections.unblock().removeClass( 'blocked_content refresh_component_options' );
				}

			}, 250 );
		};

		// block container
		component_selections.addClass( 'blocked_content' ).block( wc_cp_block_params );

		// get product info via ajax
		$.post( woocommerce_params.ajax_url, data, function( response ) {

			try {

				if ( response.result === 'success' ) {

					// fade thumbnails
					component_selections.addClass( 'refresh_component_options' );

					// lock height
					component_options.css( 'height', load_height );

					// store initial selection
					var initial_selection = component_options.find( '.component_options_select' ).val();

					// put content in place
					component_options_inner.html( $( response.options_markup ).find( '.component_options_inner' ).html() );

					// preload images before proceeding
					var thumbnails = component_options_inner.find( 'img' );

					var preload_images_then_show_component_options = function() {

						if ( thumbnails.length > 0 ) {

							var retry = false;

							thumbnails.each( function() {

								var thumbnail = $(this);

								if ( thumbnail.height() === 0 ) {
									retry = true;
									return false;
								}

							} );

							if ( retry ) {
								window.setTimeout( function() {
									preload_images_then_show_component_options();
								}, 100 );
							} else {
								show_component_options();
							}
						} else {
							show_component_options();
						}
					};

					var show_component_options = function() {

						// update pagination
						if ( response.pagination_markup ) {

							component_pagination.html( $( response.pagination_markup ).html() );
							component_pagination.slideDown( 200 );

						} else {
							component_pagination.slideUp( 200 );
						}

						// update component scenarios with new data
						var scenario_data = form.find( '.composite_data' ).data( 'scenario_data' );

						scenario_data.scenario_data[ data.component_id ] = response.component_scenario_data;

						// if the initial selection is not part of the result set, reset
						// note - in thumbnails mode, the initial selection is always appended to the (hidden) dropdown
						var current_selection = component_options.find( '.component_options_select' ).val();

						if ( initial_selection > 0 && ( current_selection === '' || typeof( current_selection ) === 'undefined' ) ) {

							component_options.find( '.component_options_select' ).change();

						} else {

							// disable newly loaded products and variations
							wc_cp_update_selections( item );

							// update component state
							wc_cp_update_component_state( item );
						}

						item.trigger( 'wc-composite-component-options-loaded' );

						// measure height
						component_options.css( 'height', 'auto' );

						new_height = component_options_inner.outerHeight();

						if ( Math.abs( new_height - load_height ) > 1 ) {

							animate_height = true;

							// lock height
							component_options.css( 'height', load_height );
						}

						animate_component_options();
					};

					preload_images_then_show_component_options();

				} else {

					// lock height
					component_options.css( 'height', load_height );

					// show failure message
					component_options_inner.html( response.options_markup );
				}

			} catch ( err ) {

				// show failure message
				console.log( err );
				animate_component_options();
			}

		}, 'json' );

	}


	/**
	 * Brings a new component into view - called when clicking on a navigation element
	 */
	function wc_cp_show_component( item, scroll ) {

		var form      = item.closest( '.composite_form' );
		var style     = form.find( '.composite_data' ).data( 'bto_style' );

		if ( typeof( scroll ) == 'undefined' ) {
			scroll = true;
		}

		// scroll to the desired section
		if ( style === 'paged' && scroll ) {

			var scroll_to = form.find( '.scroll_show_component' );

			// fade out
			form.find( '.multistep.active' ).animate( { opacity: 0 }, { duration: 200, queue: false } );

			if ( scroll_to.length > 0 && ! scroll_to.is_in_viewport( true ) ) {

				var window_offset = scroll_to.hasClass( 'scroll_bottom' ) ? $(window).height() - 80 : 50;

				$( 'html, body' ).animate( { scrollTop: scroll_to.offset().top - window_offset }, { duration: 200, queue: false } );
			}

			// fade out or show summary widget
			if ( item.hasClass( 'cart' ) ) {
				$( '.widget_composite_summary' ).animate( { opacity: 0 }, { duration: 200, queue: false } );
			} else {
				$( '.widget_composite_summary' ).slideDown( 200 );
				$( '.widget_composite_summary' ).animate( { opacity: 1 }, { duration: 200, queue: false } );
			}

			window.setTimeout( function() {

				// move summary widget out of the way if needed
				if ( item.hasClass( 'cart' ) ) {
					$( '.widget_composite_summary' ).slideUp( 200 );
				}

				// move active component
				wc_cp_set_active_component( item );

				// update blocks
				wc_cp_update_blocks( item );

				// update selections
				wc_cp_update_selections( item );

				// modify component navigation state
				wc_cp_update_component_state( item );

				// fade in
				item.css( { opacity: 0 } );
				item.animate( { opacity: 1 }, { duration: 100 } );

			}, 220 );

		} else {

			// move active component
			wc_cp_set_active_component( item );

			// update blocks
			wc_cp_update_blocks( item );

			// update selections
			if ( style === 'paged' || style === 'progressive' ) {
				wc_cp_update_selections( item );
			}

			// modify component navigation state
			wc_cp_update_component_state( item );

			if ( style === 'progressive' && scroll && item.hasClass( 'autoscrolled' ) ) {
				window.setTimeout( function() {

					var scroll_to = item;

					if ( scroll_to.length > 0 && ! item.is_in_viewport( false ) ) {
						$( 'html, body' ).animate( { scrollTop: scroll_to.offset().top }, { duration: 200, queue: false } );
					}

				}, 220 );
			}

		}

		item.trigger( 'wc-composite-show-component' );
	}


	/**
	 * Initializes external scripts dependent on product type - called when selecting a new Component Option
	 */
	function wc_cp_initialize_component_scripts( item ) {

		var product_type      = item.find( '.component_data' ).data( 'product_type' );
		var summary_content   = item.find( '.component_summary > .content' );

		if ( product_type == 'variable' ) {

			if ( ! summary_content.hasClass( 'cart' ) ) {
				summary_content.addClass( 'cart' );
			}

			if ( ! summary_content.hasClass( 'variations_form' ) ) {
				summary_content.addClass( 'variations_form' );
			}

			// Selections must be updated before firing script in order to load variation_data
			wc_cp_update_selections( item );

			// Initialize variations script
			summary_content.wc_variation_form();

			// Fire change in order to save 'variation_id' input
			summary_content.find( '.variations select' ).change();

		} else if ( product_type == 'bundle' ) {

			if ( ! summary_content.hasClass( 'bundle_form' ) ) {
				summary_content.addClass( 'bundle_form' );
			}

			// Initialize bundles script now
			summary_content.find( '.bundle_data' ).wc_pb_bundle_form();

		} else {

			if ( ! summary_content.hasClass( 'cart' ) ) {
				summary_content.addClass( 'cart' );
			}
		}
	}


	/**
	 * Manipulates markup when a new Component is brought into view
	 */
	function wc_cp_set_active_component( item ) {

		var form            = item.closest( '.composite_form' );
		var style           = form.find( '.composite_data' ).data( 'bto_style' );
		var style_variation = form.find( '.composite_data' ).data( 'bto_style_variation' );
		var active_item     = form.find( '.multistep.active' );

		if ( style !== 'progressive' ) {
			active_item.hide();
		}

		form.children( '.multistep.active, .multistep.next, .multistep.prev' ).removeClass( 'active next prev' );

		item.addClass( 'active' );

		if ( style !== 'progressive' ) {
			item.show();
		}

		var next_item = item.next();
		var prev_item = item.prev();

		if ( style === 'paged' && style_variation === 'componentized' ) {
			next_item = form.find( '.multistep.cart' );
			prev_item = form.find( '.multistep.cart' );
		}

		if ( next_item.hasClass( 'multistep' ) ) {
			next_item.addClass( 'next' );
		}

		if ( prev_item.hasClass( 'multistep' ) ) {
			prev_item.addClass( 'prev' );
		}

		item.trigger( 'wc-composite-set-active-component' );
	}


	/**
	 * True when a selected Component Option has been fully configured (for instance, there might be pending attribute selections if the selected product is variable)
	 */
	function wc_cp_component_is_set( item ) {

		var product_id   = item.find( '.component_options select.component_options_select' ).val();
		var stock        = item.find( '.component_content .component_summary .component_wrap .out-of-stock' ).length;
		var product_type = item.find( '.component_data' ).data( 'product_type' );

		if ( product_id > 0 && stock == 0 ) {

			if ( product_type == 'variable' ) {

				if ( item.find( '.variations_button input[name="variation_id"]' ).val() != '' )
					return true;
				else
					return false;

			}  else if ( product_type == 'simple' || product_type == 'none' ) {

				return true;

			} else {

				if ( item.find( '.component_data' ).data( 'component_set' ) == true )
					return true;
				else
					return false;
			}

		} else if ( product_id === '' && wc_cp_component_is_optional( item ) ) {

			return true;

		} else {

			return false;
		}
	}


	/**
	 * True when a Component is optional - takes Scenarios into account as the "None" option that controls the optional status of a component can be added/removed in Scenarios
	 */
	function wc_cp_component_is_optional( item ) {

		var form               = item.closest( '.composite_form' );
		var item_id            = item.attr( 'data-item_id' );
		var active_scenarios   = form.find( '.composite_data' ).data( 'active_scenarios' );
		var scenario_data      = wc_cp_get_scenario_data( form );
		var item_scenario_data = scenario_data[ item_id ];

		if ( 0 in item_scenario_data ) {

			if ( typeof( active_scenarios ) === 'undefined' ) {
				console.log( 'Warning: Composite not initialized properly: breaking out...' );
				return false;
			}

			var product_in_scenarios = item_scenario_data[ 0 ];
			var intersection         = wc_cp_intersect_safe( active_scenarios, product_in_scenarios );

			if ( intersection.length > 0 ) {
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}


	/**
	 * Updates the state of all pagination and navigation elements in all involved templates
	 */
	function wc_cp_update_component_state( item ) {

		// update navigation state
		wc_cp_update_navigation( item );

		// update pagination state
		wc_cp_update_pagination( item );

	}


	/**
	 * Updates the state of all pagination elements in the pagination and summary templates
	 */
	function wc_cp_update_pagination( item ) {

		var form                   = item.closest( '.composite_form' );
		var style                  = form.find( '.composite_data' ).data( 'bto_style' );
		var pagination             = form.find( '.composite_pagination' );
		var summary                = $.merge( form.find( '.composite_summary' ), $( '.widget_composite_summary') );
		var unset_component_exists = false;

		if ( pagination.length == 0 && summary.length == 0 ) {
			return false;
		}

		form.children( '.multistep' ).each( function( index ) {

			var check_item = $(this);
			var prev_item  = check_item.prev();
			var next_item  = check_item.next();
			var item_id    = check_item.attr( 'data-item_id' );

			var prev_component_set = check_item.hasClass( 'first' ) || next_item.hasClass( 'first' ) || wc_cp_component_is_set( prev_item );

			if ( ! prev_component_set ) {
				unset_component_exists = true;
			}

			// Update simple pagination
			if ( pagination.length > 0 ) {

				var pagination_element      = pagination.find( '.pagination_element_' + item_id );
				var pagination_element_link = pagination_element.find( '.element_link' );

				if ( check_item.hasClass( 'active' ) ) {

					pagination_element_link.addClass( 'inactive' );
					pagination_element.addClass( 'pagination_element_current' );

				} else {

					if ( unset_component_exists ) {

						pagination_element_link.addClass( 'inactive' );
						pagination_element.removeClass( 'pagination_element_current' );

					} else {

						pagination_element_link.removeClass( 'inactive' );
						pagination_element.removeClass( 'pagination_element_current' );

					}
				}
			}

			// Update summary links
			if ( summary.length > 0 ) {

				var summary_element      = summary.find( '.summary_element_' + item_id );
				var summary_element_link = summary_element.find( '.summary_element_link' );

				if ( check_item.hasClass( 'active' ) ) {

					summary_element_link.removeClass( 'disabled' );
					summary_element_link.addClass( 'selected' );

					summary_element.find( '.summary_element_selection_prompt' ).slideUp( 200 );

				} else {

					summary_element.find( '.summary_element_selection_prompt' ).slideDown( 200 );

					if ( unset_component_exists || ( check_item.hasClass( 'blocked' ) && ( ! prev_item.hasClass( 'active' ) || ! prev_component_set ) ) ) {

						summary_element_link.removeClass( 'selected' );
						summary_element_link.addClass( 'disabled' );

					} else {

						summary_element_link.removeClass( 'disabled' );
						summary_element_link.removeClass( 'selected' );

					}
				}
			}

		} );
	}


	/**
	 * Updates the state of Previous/Next buttons in the navigation template
	 */
	function wc_cp_update_navigation( item ) {

		var form            = item.closest( '.composite_form' );
		var style           = form.find( '.composite_data' ).data( 'bto_style' );
		var style_variation = form.find( '.composite_data' ).data( 'bto_style_variation' );
		var show_next       = false;

		if ( style == 'single' )
			return false;

		if ( ! item.hasClass( 'active' ) )
			return false;

		// paged previous / next
		if ( wc_cp_component_is_set( item ) || ( style_variation === 'componentized' && item.hasClass( 'component' ) ) ) {
			wc_cp_show_nav_next( item );
			show_next = true;
		} else {
			wc_cp_hide_nav_next( item );
		}

		// move to next component when using the progressive layout
		if ( style === 'progressive' ) {

			var navi      = form.find( '.composite_navigation.progressive' );
			var item_navi = item.find( '.composite_navigation.progressive' );
			var next_item = form.find( '.component.next' );

			if ( item_navi.length == 0 ) {
				navi.css( { visibility: 'hidden' } );
				navi.slideUp( { duration: 200, queue: false, always: function() {
					navi.appendTo( item.find( '.component_inner' ) ).css( { visibility: 'visible' } );
					if ( ! item.hasClass( 'last' ) && ( ! next_item.hasClass( 'toggled' ) || ! show_next ) ) {
						navi.slideDown( 200 );
					}
				} } );
			} else {
				if ( ! item.hasClass( 'last' ) && ( ! next_item.hasClass( 'toggled' ) || ! show_next ) ) {
					navi.css( { visibility: 'visible' } ).slideDown( 200 );
				} else {
					navi.css( { visibility: 'hidden' } ).slideUp( 200 );
				}
			}
		}
	}


	/**
	 * Updates the state of the Review/Summary template
	 */
	function wc_cp_update_summary( form ) {

		var composite_summary = form.find( '.composite_summary' );
		var price_data        = form.find( '.composite_data' ).data( 'price_data' );

		if ( composite_summary.length == 0 ) {
			return false;
		}

		form.find( '.component' ).each( function() {

			var item               = $(this);
			var item_id            = item.attr( 'data-item_id' );

			var item_summary       = composite_summary.find( '.summary_element_' + item_id );
			var item_summary_outer = item_summary.find( '.summary_element_wrapper' );
			var item_summary_inner = item_summary.find( '.summary_element_wrapper_inner' );

			var product_type       = item.find( '.component_data' ).data( 'product_type' );
			var product_id         = item.find( '#component_options_' + item_id ).val();
			var qty                = parseInt( item.find( '.component_wrap input.qty' ).val() );

			var title              = '';
			var select             = '';
			var image              = '';

			var product_title      = '';
			var product_quantity   = '';
			var product_meta       = '';

			var load_height        = item_summary_inner.outerHeight();

			// lock height
			item_summary_outer.css( 'height', load_height );

			// Get title and image
			if ( product_type === 'none' ) {

				if ( wc_cp_component_is_optional( item ) ) {
					title = $( '#component_options_' + item_id + ' option.none' ).data( 'title' );
				}

			} else if ( product_type === 'variable' ) {

				if ( product_id > 0 && ( qty > 0 || qty === 0 ) ) {

					product_title    = $( '#component_options_' + item_id + ' option:selected' ).data( 'title' );
					product_quantity = ' <strong>&times; ' + qty + '</strong>';
					product_meta     = wc_cp_get_variable_product_attributes_description( item.find( '.variations' ) );

					if ( product_meta ) {
						product_meta = ' (' + product_meta + ')';
					}

					title = product_title + product_meta + product_quantity;

					image = item.find( '#component_options_' + item_id + ' option:selected' ).data( 'image_src' );
				}

			} else if ( product_type === 'bundle' ) {

				if ( product_id > 0 && ( qty > 0 || qty === 0 ) ) {

					var selected_bundled_products = '';
					var bundled_products_num      = 0;

					item.find( '.bundled_product .cart' ).each( function( index ) {

						if ( $(this).data( 'quantity' ) > 0 )
							bundled_products_num++;
					} );

					if ( bundled_products_num == 0 ) {

						title = wc_composite_params.i18n_none;

					} else {

						item.find( '.bundled_product .cart' ).each( function( index ) {

							if ( $(this).data( 'quantity' ) > 0 ) {

								var item_meta = wc_cp_get_variable_product_attributes_description( $(this).find( '.variations' ) );

								if ( item_meta ) {
									item_meta = ' (' + item_meta + ')';
								}

								selected_bundled_products = selected_bundled_products + $(this).data( 'title' ) + item_meta + ' <strong>&times; ' + parseInt( $(this).data( 'quantity' ) * qty ) + '</strong></br>';
							}
						} );

						title = selected_bundled_products;
					}

					image = item.find( '#component_options_' + item_id + ' option:selected' ).data( 'image_src' );
				}

			} else {

				if ( product_id > 0 ) {

					product_title    = $( '#component_options_' + item_id + ' option:selected' ).data( 'title' );
					product_quantity = isNaN( qty ) ? '' : '<strong>&times; ' + qty + '</strong>';

					title = product_title + ' ' + product_quantity;

					image = item.find( '#component_options_' + item_id + ' option:selected' ).data( 'image_src' );
				}
			}

			// Selection text
			if ( title ) {

				if ( item.hasClass( 'static') ) {
					select = '<a href="">' + wc_composite_params.i18n_summary_static_component + '</a>';
				} else {
					select = '<a href="">' + wc_composite_params.i18n_summary_filled_component + '</a>';
				}

			} else {
				select = '<a href="">' + wc_composite_params.i18n_summary_empty_component + '</a>';
			}


			// Update title
			if ( title )
				item_summary.find( '.summary_element_selection' ).html( '<span class="summary_element_content">' + title + '</span><span class="summary_element_content summary_element_selection_prompt">' + select + '</span>' );
			else
				item_summary.find( '.summary_element_selection' ).html( '<span class="summary_element_content summary_element_selection_prompt">' + select + '</span>' );

			if ( $(this).hasClass( 'active' ) ) {
				item_summary.find( '.summary_element_selection_prompt' ).hide();
			}

			// Update image
			wc_cp_update_summary_element_image( item_summary, image );


			// Update price
			if ( price_data[ 'per_product_pricing' ] === true && product_id > 0 && qty > 0 && wc_cp_component_is_set( item ) ) {

				var price         = ( parseFloat( price_data[ 'prices' ][ item_id ] ) + parseFloat( price_data[ 'addons_prices' ][ item_id ] ) ) * qty;
				var regular_price = ( parseFloat( price_data[ 'regular_prices' ][ item_id ] ) + parseFloat( price_data[ 'addons_prices' ][ item_id ] ) ) * qty;

				var price_format         = wc_cp_woocommerce_number_format( wc_cp_number_format( price ) );
				var regular_price_format = wc_cp_woocommerce_number_format( wc_cp_number_format( regular_price ) );

				if ( regular_price > price ) {
					item_summary.find( '.summary_element_price' ).html( '<span class="price summary_element_content"><del>' + regular_price_format + '</del> <ins>' + price_format + '</ins></span>' );
				} else {
					item_summary.find( '.summary_element_price' ).html( '<span class="price summary_element_content">' + price_format + '</span>' );
				}

			} else {

				item_summary.find( '.summary_element_price' ).html( '' );
			}

			// Send an event to allow 3rd party code to add data to the summary
			item.trigger( 'wc-composite-component-update-summary-content' );

			item_summary_outer.animate( { 'height': item_summary_inner.outerHeight() }, { duration: 200, queue: false, always: function() {
				item_summary_outer.css( { 'height': 'auto' } );
			} } );

		} );

		// Update Summary Widget

		var widget = $( '.widget_composite_summary_content' );

		if ( widget.length > 0 ) {

			var clone = composite_summary.find( '.summary_elements' ).clone();

			clone.find( '.summary_element_wrapper' ).css( { 'height': 'auto' } );
			clone.find( '.summary_element' ).css( { 'width': '100%' } );
			clone.find( '.summary_element_selection_prompt' ).remove();

			widget.html( clone );
		}
	}


	/**
	 * Updates images in the Review/Summary template
	 */
	function wc_cp_update_summary_element_image( element, img_src ) {

		var element_image = element.find( '.summary_element_image img' );

		if ( element_image.length == 0 || element_image.hasClass( 'norefresh' ) ) {
			return false;
		}

		var o_src = element_image.attr( 'data-o_src' );

		if ( ! img_src ) {

			if ( typeof( o_src ) !== 'undefined' ) {
				element_image.attr( 'src', o_src );
			}

		} else {

			if ( typeof( o_src ) === 'undefined' ) {
				o_src = ( ! element_image.attr( 'src' ) ) ? '' : element_image.attr( 'src' );
				element_image.attr( 'data-o_src', o_src );
			}

			element_image.attr( 'src', img_src );
		}

	}


	/**
	 * Updates Previous/Next navigation buttons when progressing to the next screen is allowed
	 */
	function wc_cp_show_nav_next( item ) {

		var form            = item.closest( '.composite_form' );
		var navigation      = form.find( '.composite_navigation' );
		var next_item       = form.find( '.multistep.next' );
		var prev_item       = form.find( '.component.prev' );
		var style_variation = form.find( '.composite_data' ).data( 'bto_style_variation' );

		// hide navigation
		navigation.find( '.next' ).addClass( 'invisible' );
		navigation.find( '.prev' ).addClass( 'invisible' );

		// selectively show next/previous navigation buttons
		if ( next_item.length > 0 && style_variation !== 'componentized' ) {

			navigation.find( '.next' ).html( wc_composite_params.i18n_next_step.replace( '%s', next_item.data( 'nav_title' ) ) );
			navigation.find( '.next' ).removeClass( 'invisible' );

			if ( next_item.hasClass( 'toggled' ) ) {
				next_item.find( '.component_title' ).removeClass( 'inactive' );
			}

		} else {

			form.find( '.composite_navigation.paged .next' ).html( wc_composite_params.i18n_final_step );
			form.find( '.composite_navigation.paged .next' ).removeClass( 'invisible' );
		}

		if ( prev_item.hasClass( 'component' ) ) {

			form.find( '.composite_navigation.paged .prev' ).html( wc_composite_params.i18n_previous_step.replace( '%s', prev_item.data( 'nav_title' ) ) );
			form.find( '.composite_navigation.paged .prev' ).removeClass( 'invisible' );
		}

		navigation.find( '.prompt' ).html( '' );
		navigation.find( '.prompt' ).addClass( 'invisible' );
	}


	/**
	 * Updates Previous/Next navigation buttons when progressing to the next screen is not allowed
	 */
	function wc_cp_hide_nav_next( item ) {

		var form       = item.closest( '.composite_form' );
		var navigation = form.find( '.composite_navigation' );
		var next_item  = form.find( '.multistep.next' );
		var prev_item  = form.find( '.component.prev' );

		navigation.find( '.prev' ).addClass( 'invisible' );
		navigation.find( '.next' ).addClass( 'invisible' );

		if ( next_item.hasClass( 'toggled' ) ) {
			next_item.find( '.component_title' ).addClass( 'inactive' );
		}

		if ( prev_item.hasClass( 'component' ) ) {

			var product_id = prev_item.find( '.component_options select.component_options_select' ).val();

			if ( product_id > 0 || product_id === '0' || product_id === '' && wc_cp_component_is_optional( prev_item ) ) {

				form.find( '.composite_navigation.paged .prev' ).html( wc_composite_params.i18n_previous_step.replace( '%s', prev_item.data( 'nav_title' ) ) );
				form.find( '.composite_navigation.paged .prev' ).removeClass( 'invisible' );
			}
		}

		if ( item.hasClass( 'component' ) ) {

			// don't show the prompt if it's the last component of the progressive layout
			if ( ! item.hasClass( 'last' ) || ! item.hasClass( 'progressive' ) ) {

				navigation.find( '.prompt' ).html( wc_composite_params.i18n_select_component_options.replace( '%s', item.data( 'nav_title' ) ) );
				navigation.find( '.prompt' ).removeClass( 'invisible' );
			}
		}
	}


	/**
	 * Toggle-box handling
	 */
	function wc_cp_toggle_element( container, content ) {

		if ( container.hasClass( 'closed' ) ) {
			content.slideDown( { duration: 200, queue: false, always: function() {
				container.removeClass( 'animating' );
			} } );
			container.removeClass( 'closed' ).addClass( 'open animating' );
		} else {
			content.slideUp( { duration: 200, queue: false } );
			container.removeClass( 'open' ).addClass( 'closed' );
		}
	}


	/**
	 * Blocks access to subsequent components in progressive mode
	 */
	function wc_cp_block_component( item ) {

		item.addClass( 'blocked' );

		if ( item.hasClass( 'toggled' ) ) {

			if ( item.hasClass( 'open' ) ) {
				wc_cp_toggle_element( item, item.find( '.component_inner' ) );
			}

			item.find( '.component_title' ).addClass( 'inactive' );
		}
	}


	/**
	 * Unblocks access to a blocked component in progressive mode
	 */
	function wc_cp_unblock_component( item ) {

		if ( item.hasClass( 'toggled' ) ) {

			if ( item.hasClass( 'closed' ) ) {
				wc_cp_toggle_element( item, item.find( '.component_inner' ) );
			}

			item.find( '.component_title' ).removeClass( 'inactive' );
		}

		item.removeClass( 'blocked' );
	}


	/**
	 * Blocks access to multiple subsequent components in progressive mode
	 */
	function wc_cp_block_components( form, min_block_index ) {

		form.children( '.component' ).each( function( index ) {

			if ( index > min_block_index ) {

				if ( $(this).hasClass( 'disabled' ) ) {
					wc_cp_unblock_component_options( $(this) );
				}

				wc_cp_block_component( $(this) );
			}
		} );
	}


	/**
	 * Blocks controls of previous components in progressive mode
	 */
	function wc_cp_block_component_options( item ) {

		item.find( 'select, input' ).addClass( 'disabled_input' ).prop( 'disabled', 'disabled' );

		item.addClass( 'disabled' ).trigger( 'wc-composite-disable-component-options' );

		var reset_options = item.find( '.clear_component_options' );

		reset_options.html( wc_composite_params.i18n_reset_selection ).addClass( 'reset_component_options' );
	}


	/**
	 * Unblocks controls of previous components in progressive mode
	 */
	function wc_cp_unblock_component_options( item ) {

		item.find( 'select.disabled_input, input.disabled_input' ).removeClass( 'disabled_input' ).prop( 'disabled', false );

		item.removeClass( 'disabled' ).trigger( 'wc-composite-enable-component-options' );

		var reset_options = item.find( '.clear_component_options' );

		reset_options.html( wc_composite_params.i18n_clear_selection ).removeClass( 'reset_component_options' );
	}


	/**
	 * Updates the block state of a component when it is brought into view
	 */
	function wc_cp_update_blocks( item ) {

		var form  = item.closest( '.composite_form' );
		var style = form.find( '.composite_data' ).data( 'bto_style' );

		if ( style !== 'progressive' ) {
			return false;
		}

		var prev_item = item.prev();

		wc_cp_block_component_options( prev_item );

		wc_cp_unblock_component_options( item );
		wc_cp_unblock_component( item );

		var reset_options = prev_item.find( '.clear_component_options' );
		reset_options.html( wc_composite_params.i18n_reset_selection ).addClass( 'reset_component_options' );
	}


	/**
	 * Return all scenario ids
	 */
	function wc_cp_get_all_scenarios( form ) {

		var scenarios = form.find( '.composite_data' ).data( 'scenario_data' );

		return scenarios.scenarios;
	}


	/**
	 * Return all scenario data
	 */
	function wc_cp_get_scenario_data( form ) {

		var scenarios = form.find( '.composite_data' ).data( 'scenario_data' );

		return scenarios.scenario_data;
	}


	/**
	 * Updates the active/inactive state of Component Options based on the currently active Scenario(s)
	 * Starts by mapping the current selections state to a set of Scenarios
	 * Based on the active Scenarios, it activates or deactivates products and variations according to the Scenario definitions
	 */
	function wc_cp_update_selections( item ) {

		var item_id         = item.attr( 'data-item_id' );
		var form            = item.closest( '.composite_form' );
		var current_item_id = item_id;
		var style           = form.find( '.composite_data' ).data( 'bto_style' );
		var selection_mode  = form.find( '.composite_data' ).data( 'bto_selection_mode' );
		var item_index      = form.find( '.component' ).index( item );

		if ( item.hasClass( 'cart' ) ) {
			item_index = 1000;
		}

		// Calc active scenarios
		var active_scenarios_incl_current = wc_cp_get_all_scenarios( form );

		// Active scenarios excluding current component
		var active_scenarios_excl_current = [];

		if ( wc_composite_params.script_debug === 'yes' ) {
			console.log( '\nSelections update triggered by ' + item.data( 'nav_title' ) + ' at ' + new Date().getTime().toString() + '...' );
			console.log( '\nCalculating active scenarios...' );
		}

		form.children( '.component' ).each( function( index ) {

			// In single-page/multi-page progressive modes, when configuring a Component, Scenario restrictions must be evaluated based on previous Component selections only.
			// Any incompatible subsequent Component selections will be reset when the affected Component becomes active.

			if ( style == 'progressive' || style == 'paged' )
				if ( index > item_index )
					return true;
				else
					active_scenarios_excl_current = active_scenarios_incl_current;

			var item         = $(this);
			var product_id   = item.find( '.component_options select.component_options_select' ).val();
			var item_id      = item.attr( 'data-item_id' );
			var product_type = item.find( '.component_data' ).data( 'product_type' );

			// if incompatible default is selected, val will be null - use this fact to reset options before moving on
			if ( item.find( '.component_options select.component_options_select' ).val() === null ) {
				item.find( '.component_options select.component_options_select' ).val('').addClass( 'reset' );
				return false;
			}

			// The selections of the current item must not shape the constraints in non-proressive dropdown-only modes, in order to be able to switch the selection.
			if ( product_id >= 0 && ( current_item_id != item_id || selection_mode == 'thumbnails' || style == 'progressive' || style == 'paged' ) ) {

				var scenario_data      = wc_cp_get_scenario_data( form );
				var item_scenario_data = scenario_data[ item_id ];

				// Treat '' optional component selections as 'None' if the component is optional
				if ( product_id === '' ) {
					if ( 0 in item_scenario_data ) {
						product_id = '0';
					} else {
						return true;
					}
				}

				var product_in_scenarios = item_scenario_data[ product_id ];

				if ( wc_composite_params.script_debug === 'yes' ) {
					console.log( 'Selection #' + product_id + ' of ' + item.data( 'nav_title' ) + ' in scenarios: ' + product_in_scenarios.toString() );
				}

				if ( product_type == 'variable' ) {

					var variation_id = item.find( '.single_variation_wrap .variations_button input[name="variation_id"]' ).val();

					// The selections of the current item must not shape the constraints in non-proressive modes, in order to be able to switch the selection.
					// Variations are only selected with dropdowns, so we don't need to check for that.
					if ( variation_id > 0 && ( current_item_id != item_id ) ) {

						product_in_scenarios = item_scenario_data[ variation_id ];

						if ( wc_composite_params.script_debug === 'yes' ) {
							console.log( 'Variation selection #' + variation_id + ' of ' + item_id + ' in scenarios: ' + product_in_scenarios.toString() );
						}
					}
				}

				var intersection = wc_cp_intersect_safe( active_scenarios_incl_current, product_in_scenarios );

				if ( intersection.length > 0 ) {

					active_scenarios_incl_current = intersection;

				} else {
					// the intersection might be empty if the default options cannot be mapped to a scenario
					if ( product_id !== '0' ) {

						if ( wc_composite_params.script_debug === 'yes' ) {
							console.log( 'Selection incompatible - breaking out and resetting...' );
						}

						item.find( '.component_options select.component_options_select' ).val('').addClass( 'reset' );

					} else {

						if ( wc_composite_params.script_debug === 'yes' ) {
							console.log( 'Selection incompatible - breaking out...' );
						}
					}

					return false;
				}

			}

		} );

		// Disable or enable product and variation selections

		if ( wc_composite_params.script_debug === 'yes' ) {
			console.log( '\nUpdating selections...' );
		}

		form.children( '.component' ).each( function( index ) {

			var item             = $(this);
			var item_id          = item.attr( 'data-item_id' );
			var summary_content  = item.find( '.component_summary > .content' );
			var active_scenarios = [];

			if ( ( style == 'progressive' || style == 'paged' ) && item_index == index )
				active_scenarios = active_scenarios_excl_current;
			else
				active_scenarios = active_scenarios_incl_current;

			form.find( '.composite_data' ).data( 'active_scenarios', active_scenarios );

			if ( wc_composite_params.script_debug === 'yes' ) {
				console.log( 'Updating selections of ' + item.data( 'nav_title' ) + '...' );
				console.log( '	Active scenarios:' );
				console.log( '	' + active_scenarios.toString() );
			}

			// Disable incompatible products

			$(this).find( '.component_options select.component_options_select option' ).each( function() {

				var product_id = $(this).val();

				// The '' option cannot be disabled - if an option must be selected the add to cart button will be hidden and a message will be shown
				if ( product_id >= 0 && product_id !== '' ) {

					if ( wc_composite_params.script_debug === 'yes' ) {
						console.log( '	Updating selection #' + product_id + ':' );
					}

					var scenario_data        = wc_cp_get_scenario_data( form );
					var item_scenario_data   = scenario_data[ item_id ];
					var product_in_scenarios = item_scenario_data[ product_id ];
					var is_compatible        = false;

					if ( wc_composite_params.script_debug === 'yes' ) {
						console.log( '		Selection in scenarios: ' + product_in_scenarios.toString() );
					}

					for ( var i in product_in_scenarios ) {

						var scenario_id = product_in_scenarios[ i ];

						if ( $.inArray( scenario_id, active_scenarios ) > -1 ) {
							is_compatible = true;
							break;
						}
					}

					if ( ! is_compatible ) {

						if ( wc_composite_params.script_debug === 'yes' ) {
							console.log( '		Selection disabled.' );
						}

						$(this).prop( 'disabled', 'disabled' ).trigger( 'wc-composite-selection-incompatible' );
						item.find( '#component_option_thumbnail_' + $(this).val() ).addClass( 'disabled' );

					} else {

						if ( wc_composite_params.script_debug === 'yes' ) {
							console.log( '		Selection enabled.' );
						}

						$(this).prop( 'disabled', false ).trigger( 'wc-composite-selection-compatible' );
						item.find( '#component_option_thumbnail_' + $(this).val() ).removeClass( 'disabled' );
					}
				}
			} );


			// Disable incompatible variations

			var product_type = item.find( '.component_data' ).data( 'product_type' );

			if ( product_type == 'variable' ) {

				// Get all variations
				var product_variations = item.find( '.component_data' ).data( 'product_variations' );

				var product_variations_in_scenario = [];

				for ( var i in product_variations ) {

					var scenario_data          = wc_cp_get_scenario_data( form );
					var item_scenario_data     = scenario_data[ item_id ];
					var variation_in_scenarios = item_scenario_data[ product_variations[i].variation_id ];
					var is_compatible          = false;

					for ( var k in variation_in_scenarios ) {

						var scenario_id = variation_in_scenarios[k];

						if ( $.inArray( scenario_id, active_scenarios ) > -1 ) {
							is_compatible = true;
							break;
						}
					}

					// In WC 2.3, copy all variation objects but set the variation_is_active property to false in order to disable the attributes of incompatible variations
					if ( wc_composite_params.is_wc_version_gte_2_3 === 'yes' ) {

						var variation = $.extend( true, {}, product_variations[i] );

						var variation_has_empty_attributes = false;

						if ( ! is_compatible ) {

							variation.variation_is_active = false;

							// do not include incompatible variations with empty attributes - they can break stuff when prioritized
							for ( var attr_name in variation.attributes ) {
								if ( variation.attributes[ attr_name ] === '' ) {
									variation_has_empty_attributes = true;
									break;
								}
							}
						}

						if ( ! variation_has_empty_attributes ) {
							product_variations_in_scenario.push( variation );
						}

					// In WC 2.2/2.1, copy only compatible variations
					} else {
						if ( is_compatible ) {
							product_variations_in_scenario.push( product_variations[i] );
						}
					}
				}

				// Put filtered variations in place
				summary_content.data( 'product_variations', product_variations_in_scenario );
			}

		} );

		if ( wc_composite_params.script_debug === 'yes' ) {
			console.log( 'Finished updating component selections.\n\n' );
		}

		// Verification - reset to first available option if the currently active one was marked as incompatible
		if ( item.find( '.component_options select.component_options_select' ).hasClass( 'reset' ) ) {

			var valid_options = item.find( '.component_options select.component_options_select option' ).not( '[disabled="disabled"], [value=""]' );

			if ( valid_options.length > 0 && 1 === 2 ) {

				var first_valid = valid_options.first();
				item.find( '.component_options select.component_options_select' ).val( first_valid.val() ).change();

			} else {

				if ( ! item.find( '.component_options select.component_options_select' ).hasClass( 'resetting' ) ) {

					item.find( '.component_options select.component_options_select' ).addClass( 'resetting' );
					item.find( '.component_options select.component_options_select' ).val( '' ).change();
				}
			}

			item.find( '.component_options select.component_options_select' ).removeClass( 'reset' );
			item.find( '.component_options select.component_options_select' ).removeClass( 'resetting' );
		}

		// Variations verification - reset options if the current ones are not within the product_variations data
		var current_product_type = item.find( '.component_data' ).data( 'product_type' );

		if ( current_product_type == 'variable' ) {

			var current_item_summary_content = item.find( '.component_summary > .content' );

			// Verify that the selected attributes are valid
			// If not, reset options before initializing to prevent attribute match error
			if ( ! wc_cp_has_valid_default_attributes( current_item_summary_content ) ) {
				current_item_summary_content.find( '.variations select' ).val( '' ).change();
			}
		}
	}


	/**
	 * Checks if the default attributes of a variable product are valid based on the active Scenarios
	 */
	function wc_cp_has_valid_default_attributes( variation_form ) {

		var current_settings = {};

		variation_form.find( '.variations select' ).each( function() {

        	// Encode entities
        	var value = $(this).val();

			// Add to settings array
			current_settings[ $(this).attr( 'name' ) ] = value;

		} );

		var all_variations      = variation_form.data( 'product_variations' );
		var matching_variations = wc_cp_find_matching_variations( all_variations, current_settings );
		var variation           = matching_variations.shift();

		if ( variation ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Used by wc_cp_has_valid_default_attributes
	 */
    function wc_cp_find_matching_variations( product_variations, settings ) {

        var matching = [];

        for ( var i = 0; i < product_variations.length; i++ ) {

        	var variation = product_variations[i];

			if ( wc_cp_variations_match( variation.attributes, settings ) ) {
                matching.push( variation );
            }
        }

        return matching;
    }


	/**
	 * Used by wc_cp_has_valid_default_attributes
	 */
   function wc_cp_variations_match( attrs1, attrs2 ) {

        var match = true;

        for ( var attr_name in attrs1 ) {

            var val1 = attrs1[ attr_name ];
            var val2 = attrs2[ attr_name ];

            if ( val1 !== undefined && val2 !== undefined && val1.length != 0 && val2.length != 0 && val1 != val2 ) {
                match = false;
            }
        }

        return match;
    }


	/**
	 * Schedules an update of the composite totals and review/summary section
	 * Uses a dumb scheduler to avoid queueing multiple calls of wc_cp_update_composite_task - the "scheduler" simply introduces a 50msec execution delay during which all update requests are dropped
	 */
	function wc_cp_update_composite( form, update_only ) {

		var composite_data = form.find( '.composite_data' );

		// Break out if the initialization is not finished yet (function call triggered by a 'wc-composite-component-loaded' event listener)
		if ( composite_data.data( 'composite_initialized' ) !== true ) {
			return false;
		}

		// Dumb task scheduler
		if ( composite_data.data( 'update_lock' ) === true ) {
			return false;
		}

		composite_data.data( 'update_lock', true );

		window.setTimeout( function() {

			wc_cp_update_composite_task( form, update_only );
			composite_data.data( 'update_lock', false );

		}, 50 );
	}


	/**
	 * Updates the composite totals and review/summary section
	 */
	function wc_cp_update_composite_task( form, update_only ) {

		var composite_data = form.find( '.composite_data' );

		if ( typeof( update_only ) == 'undefined' ) {
			update_only = false;
		}

		var all_set            = true;
		var component_quantity = {};
		var out_of_stock       = [];

		var price_data         = composite_data.data( 'price_data' );
		var style              = composite_data.data( 'bto_style' );
		var progression_style  = composite_data.data( 'progression_style' );

		var composite_stock = composite_data.find( '.composite_wrap p.stock' );

		// Reset composite stock status
		if ( typeof( composite_data.data( 'stock_status' ) ) !== 'undefined' ) {
			composite_stock.replaceWith( $( composite_data.data( 'stock_status' ) ) );
		} else {
			composite_stock.remove();
		}

		// In progressive/paged mode, when the progression style is 'strict' the active component must be the last to continue
		if ( ( style === 'progressive' || style === 'paged' ) && progression_style === 'strict' && ! form.find( '.multistep' ).last().hasClass( 'active' ) ) {
			wc_cp_hide_composite( form );
			return false;
		}

		// Validate components
		form.children( '.component' ).each( function() {

			var item    = $(this);
			var item_id = item.attr( 'data-item_id' );

			// if a 'component' class exists somewhere it shouldn't, move on
			if ( typeof( item_id ) == 'undefined' )
				return true;

			var form_data = composite_data.find( '.composite_wrap .composite_button .form_data_' + item_id );

			// Verify submit form input data

			var product_input   = item.find( '#component_options_' + item_id ).val();
			var quantity_input  = item.find( '.component_wrap input.qty' ).val();
			var variation_input = form_data.find( 'input.variation_input' ).val();

			// Copy prices

			price_data[ 'prices' ][ item_id ]         = parseFloat( item.find( '.component_data' ).data( 'price' ) );
			price_data[ 'regular_prices' ][ item_id ] = parseFloat( item.find( '.component_data' ).data( 'regular_price' ) );

			// Save addons prices
			price_data[ 'addons_prices' ][ item_id ] = 0;

			item.find( '.addon' ).each( function() {

				var addon_cost = 0;

				if ( $(this).is('.addon-custom-price') ) {
					addon_cost = $(this).val();
				} else if ( $(this).is('.addon-input_multiplier') ) {
					if( isNaN( $(this).val() ) || $(this).val() == '' ) { // Number inputs return blank when invalid
						$(this).val('');
						$(this).closest('p').find('.addon-alert').show();
					} else {
						if( $(this).val() != '' ) {
							$(this).val( Math.ceil( $(this).val() ) );
						}
						$(this).closest('p').find('.addon-alert').hide();
					}
					addon_cost = $(this).data('price') * $(this).val();
				} else if ( $(this).is('.addon-checkbox, .addon-radio') ) {
					if ( $(this).is(':checked') )
						addon_cost = $(this).data('price');
				} else if ( $(this).is('.addon-select') ) {
					if ( $(this).val() )
						addon_cost = $(this).find('option:selected').data('price');
				} else {
					if ( $(this).val() )
						addon_cost = $(this).data('price');
				}

				if ( ! addon_cost )
					addon_cost = 0;

				price_data[ 'addons_prices' ][ item_id ] = parseFloat( price_data[ 'addons_prices' ][ item_id ] ) + parseFloat( addon_cost );

			} );

			var product_type = item.find( '.component_data' ).data( 'product_type' );
			var stock        = item.find( '.component_content .component_wrap .out-of-stock' );

			if ( typeof( product_type ) == 'undefined' || product_type == '' ) {
				all_set = false;
			} else if ( ! ( product_input > 0 ) && ! wc_cp_component_is_optional( item ) ) {
				all_set = false;
			} else if ( stock.length > 0 ) {
				out_of_stock.push( wc_composite_params.i18n_insufficient_item_stock.replace( '%s', $( '#component_options_' + item_id + ' option:selected' ).data( 'title' ) ).replace( '%v', item.data( 'nav_title' ) ) );
				all_set = false;
			} else if ( product_type !== 'none' && quantity_input === '' ) {
				all_set = false;
			} else if ( product_type === 'variable' && ( typeof( variation_input ) == 'undefined' || item.find( '.component_data' ).data( 'component_set' ) == false ) ) {
				all_set = false;
			} else if ( product_type !== 'variable' && product_type !== 'simple' && product_type !== 'none' && item.find( '.component_data' ).data( 'component_set' ) == false ) {
				all_set = false;
			} else {
				// Update quantity data for price calculations
				if ( quantity_input > 0 ) {
					component_quantity[ item_id ] = parseInt( quantity_input );
				} else {
					component_quantity[ item_id ] = 0;
				}
			}

		} );

		// Update paged layout summary state
		wc_cp_update_summary( form );

		// Add to cart button state and price
		if ( all_set ) {

			if ( ( price_data[ 'per_product_pricing' ] == false ) && ( price_data[ 'price_undefined' ] == true ) ) {
				wc_cp_hide_composite( form, wc_composite_params.i18n_unavailable_text );
				return false;
			}

			if ( price_data[ 'per_product_pricing' ] == true ) {

				price_data[ 'total' ]         = 0;
				price_data[ 'regular_total' ] = 0;

				for ( var item_id_ppp in price_data[ 'prices' ] ) {

					price_data[ 'total' ]         += ( parseFloat( price_data[ 'prices' ][ item_id_ppp ] ) + parseFloat( price_data[ 'addons_prices' ][ item_id_ppp ] ) ) * component_quantity[ item_id_ppp ];
					price_data[ 'regular_total' ] += ( parseFloat( price_data[ 'regular_prices' ][ item_id_ppp ] ) + parseFloat( price_data[ 'addons_prices' ][ item_id_ppp ] ) ) * component_quantity[ item_id_ppp ];
				}

				price_data[ 'total' ]         += parseFloat( price_data[ 'base_price' ] );
				price_data[ 'regular_total' ] += parseFloat( price_data[ 'base_regular_price' ] );

			} else {

				price_data[ 'total' ]         = parseFloat( price_data[ 'base_price' ] );
				price_data[ 'regular_total' ] = parseFloat( price_data[ 'base_regular_price' ] );

				for ( var item_id_sp in price_data[ 'addons_prices' ] ) {

					price_data[ 'total' ]         += parseFloat( price_data[ 'addons_prices' ][ item_id_sp ] ) * component_quantity[ item_id_sp ];
					price_data[ 'regular_total' ] += parseFloat( price_data[ 'addons_prices' ][ item_id_sp ] ) * component_quantity[ item_id_sp ];
				}
			}

			var composite_addon = composite_data.find( '#product-addons-total' );

			if ( composite_addon.length > 0 ) {
				composite_addon.data( 'price', price_data[ 'total' ] );
				composite_data.trigger( 'woocommerce-product-addons-update' );
			}

			if ( price_data[ 'total' ] == 0 && price_data[ 'show_free_string' ] == true ) {
				composite_data.find( '.composite_price' ).html( '<p class="price"><span class="total">' + wc_composite_params.i18n_total + '</span>'+ wc_composite_params.i18n_free +'</p>' );
			} else {

				var sales_price_format   = wc_cp_woocommerce_number_format( wc_cp_number_format( price_data[ 'total' ] ) );
				var regular_price_format = wc_cp_woocommerce_number_format( wc_cp_number_format( price_data[ 'regular_total' ] ) );

				if ( price_data[ 'regular_total' ] > price_data[ 'total' ] ) {
					composite_data.find( '.composite_price' ).html( '<p class="price"><span class="total">' + wc_composite_params.i18n_total + '</span><del>' + regular_price_format + '</del> <ins>' + sales_price_format + '</ins></p>' );
				} else {
					composite_data.find( '.composite_price' ).html( '<p class="price"><span class="total">' + wc_composite_params.i18n_total + '</span>' + sales_price_format + '</p>' );
				}
			}

			var button_behaviour = composite_data.data( 'button_behaviour' );

			if ( button_behaviour !== 'new' ) {
				composite_data.find( '.composite_wrap' ).slideDown( 200 );
			} else {
				composite_data.find( '.composite_button button' ).prop( 'disabled', false ).removeClass( 'disabled' );
			}

			composite_data.find( '.composite_wrap' ).trigger( 'wc-composite-show-add-to-cart' );

		} else {

			// List out-of-stock selections
			if ( out_of_stock.length > 0 ) {

				var composite_out_of_stock_string = '<p class="stock out-of-stock">' + wc_composite_params.i18n_insufficient_stock + '</p>';

				var loop = 0;
				var out_of_stock_string = '';

				for ( var i in out_of_stock ) {

					loop++;

					if ( out_of_stock.length == 1 || loop == 1 ) {
						out_of_stock_string = out_of_stock[i];
					} else {
						out_of_stock_string = wc_composite_params.i18n_insufficient_item_stock_comma_sep.replace( '%s', out_of_stock_string ).replace( '%v', out_of_stock[i] );
					}
				}

				if ( composite_data.find( '.composite_wrap p.stock' ).length > 0 ) {
					composite_data.find( '.composite_wrap p.stock' ).replaceWith( $( composite_out_of_stock_string.replace( '%s', out_of_stock_string ) ) );
				} else {
					composite_data.find( '.composite_wrap .composite_price' ).after( $( composite_out_of_stock_string.replace( '%s', out_of_stock_string ) ) );
				}
			}

			if ( ! update_only ) {
				wc_cp_hide_composite( form );
			}
		}

		// Update summary widget
		var widget = $( '.widget_composite_summary_content' );

		if ( widget.length > 0 ) {

			var price_clone = composite_data.find( '.composite_wrap .composite_price' ).clone();
			widget.append( price_clone.addClass( 'cp_clearfix' ) );
		}
	}


	/**
	 * Called when the Composite can't be added-to-cart - disables the add-to-cart button and builds a string with a human-friendly reason
	 */
	function wc_cp_hide_composite( form, hide_message ) {

		var composite_data   = form.find( '.composite_data' );
		var button_behaviour = composite_data.data( 'button_behaviour' );

		if ( button_behaviour == 'new' ) {

			if ( typeof( hide_message ) == 'undefined' ) {
				var pending = wc_cp_get_pending_components( form );
				if ( pending ) {
					hide_message = wc_composite_params.i18n_select_options.replace( '%s', wc_cp_get_pending_components( form ) );
				} else {
					hide_message = '';
				}
			}

			composite_data.find( '.composite_price' ).html( hide_message );
			composite_data.find( '.composite_button button' ).prop( 'disabled', true ).addClass( 'disabled' );

		} else {

			composite_data.find( '.composite_price' ).html( '' );
			composite_data.find( '.composite_wrap' ).slideUp( 200 );
		}

		composite_data.find( '.composite_wrap' ).trigger( 'wc-composite-hide-add-to-cart' );
	}


	/**
	 * Builds a string with all Components that require further user input
	 */
	function wc_cp_get_pending_components( form ) {

		var pending_components        = [];
		var pending_components_string = '';
		var progression_style         = form.find( '.composite_data' ).data( 'progression_style' );

		form.children( '.component' ).each( function() {

			var item      = $(this);
			var selection = item.find( '.component_options select.component_options_select' ).val();
			var item_set  = item.find( '.component_data' ).data( 'component_set' );

			if ( ( ! ( selection > 0 ) && ! wc_cp_component_is_optional( item ) ) || ( progression_style == 'strict' && item.hasClass( 'blocked' ) ) || item_set == false || typeof( item_set ) == 'undefined' ) {

				var item_title = item.data( 'nav_title' );
				pending_components.push( item_title );
			}

		} );

		var count = pending_components.length;

		if ( count > 0 ) {

			var loop = 0;

			for ( var i in pending_components ) {

				loop++;

				if ( count == 1 || loop == 1 ) {
					pending_components_string = '&quot;' + pending_components[i] + '&quot;';
				} else if ( loop == count ) {
					pending_components_string = wc_composite_params.i18n_select_options_and_sep.replace( '%s', pending_components_string ).replace( '%v', pending_components[i] );
				} else {
					pending_components_string = wc_composite_params.i18n_select_options_comma_sep.replace( '%s', pending_components_string ).replace( '%v', pending_components[i] );
				}
			}
		}

		return pending_components_string;
	}


	/**
	 * Initialize quantity buttons
	 */
	function wc_cp_init_quantity_buttons( item ) {

		// Quantity buttons
		if ( wc_composite_params.is_wc_version_gte_2_3 === 'no' ) {
			item.find( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' );
		}

		// Target quantity inputs on product pages
		item.find( '.component_wrap input.qty' ).each( function() {

			var min = parseFloat( $(this).attr( 'min' ) );

			if ( min >= 0 && parseFloat( $(this).val() ) < min ) {
				$(this).val( min );
			}

		} );
	}


	/**
	 * Various helper functions
	 */
	function wc_cp_woocommerce_number_format( price ) {

		var remove     = wc_composite_params.currency_format_decimal_sep;
		var position   = wc_composite_params.currency_position;
		var symbol     = wc_composite_params.currency_symbol;
		var trim_zeros = wc_composite_params.currency_format_trim_zeros;
		var decimals   = wc_composite_params.currency_format_num_decimals;

		if ( trim_zeros == 'yes' && decimals > 0 ) {
			for (var i = 0; i < decimals; i++) { remove = remove + '0'; }
			price = price.replace( remove, '' );
		}

		var price_format = '';

		if ( position == 'left' )
			price_format = '<span class="amount">' + symbol + price + '</span>';
		else if ( position == 'right' )
			price_format = '<span class="amount">' + price + symbol +  '</span>';
		else if ( position == 'left_space' )
			price_format = '<span class="amount">' + symbol + ' ' + price + '</span>';
		else if ( position == 'right_space' )
			price_format = '<span class="amount">' + price + ' ' + symbol +  '</span>';

		return price_format;
	}

	function wc_cp_number_format( number ) {

		var decimals      = wc_composite_params.currency_format_num_decimals;
		var decimal_sep   = wc_composite_params.currency_format_decimal_sep;
		var thousands_sep = wc_composite_params.currency_format_thousand_sep;

	    var n = number, c = isNaN( decimals = Math.abs( decimals ) ) ? 2 : decimals;
	    var d = typeof( decimal_sep ) == 'undefined' ? ',' : decimal_sep;
	    var t = typeof( thousands_sep ) == 'undefined' ? '.' : thousands_sep, s = n < 0 ? '-' : '';
	    var i = parseInt( n = Math.abs( +n || 0 ).toFixed(c) ) + '', j = ( j = i.length ) > 3 ? j % 3 : 0;

	    return s + ( j ? i.substr( 0, j ) + t : '' ) + i.substr(j).replace( /(\d{3})(?=\d)/g, '$1' + t ) + ( c ? d + Math.abs( n - i ).toFixed(c).slice(2) : '' );
	}

	function wc_cp_intersect_safe( a, b ) {

		var ai = 0, bi = 0;
		var result = new Array();

		a.sort();
		b.sort();

		while ( ai < a.length && bi < b.length ) {

			if ( a[ai] < b[bi] ) {
				ai++;
			} else if ( a[ai] > b[bi] ) {
				bi++;
			/* they're equal */
			} else {
				result.push( a[ai] );
				ai++;
				bi++;
			}
		}

		return result;
	}

    $.fn.is_in_viewport = function( partial, hidden, direction ) {

    	var $w = $( window );

        if (this.length < 1)
            return;

        var $t        = this.length > 1 ? this.eq(0) : this,
            t         = $t.get(0),
            vpWidth   = $w.width(),
            vpHeight  = $w.height(),
            direction = (direction) ? direction : 'both',
            clientSize = hidden === true ? t.offsetWidth * t.offsetHeight : true;

        if (typeof t.getBoundingClientRect === 'function'){

            // Use this native browser method, if available.
            var rec = t.getBoundingClientRect(),
                tViz = rec.top    >= 0 && rec.top    <  vpHeight,
                bViz = rec.bottom >  0 && rec.bottom <= vpHeight,
                lViz = rec.left   >= 0 && rec.left   <  vpWidth,
                rViz = rec.right  >  0 && rec.right  <= vpWidth,
                vVisible   = partial ? tViz || bViz : tViz && bViz,
                hVisible   = partial ? lViz || rViz : lViz && rViz;

            if(direction === 'both')
                return clientSize && vVisible && hVisible;
            else if(direction === 'vertical')
                return clientSize && vVisible;
            else if(direction === 'horizontal')
                return clientSize && hVisible;
        } else {

            var viewTop         = $w.scrollTop(),
                viewBottom      = viewTop + vpHeight,
                viewLeft        = $w.scrollLeft(),
                viewRight       = viewLeft + vpWidth,
                offset          = $t.offset(),
                _top            = offset.top,
                _bottom         = _top + $t.height(),
                _left           = offset.left,
                _right          = _left + $t.width(),
                compareTop      = partial === true ? _bottom : _top,
                compareBottom   = partial === true ? _top : _bottom,
                compareLeft     = partial === true ? _right : _left,
                compareRight    = partial === true ? _left : _right;

            if(direction === 'both')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop)) && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
            else if(direction === 'vertical')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
            else if(direction === 'horizontal')
                return !!clientSize && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
        }
    };

	$( '.composite_form .composite_data' ).each( function() {
		$(this).wc_composite_form();
	} );

} );
