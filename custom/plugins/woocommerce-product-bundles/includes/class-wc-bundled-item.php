<?php

/**
 * Bundled Item Container.
 *
 * The bunded item class is a container that initializes and holds all pricing, availability and variation/attribute-related data of a bundled item.
 *
 * @class 	WC_Bundled_Item
 * @version 4.9.4
 * @since   4.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Bundled_Item {

	public $item_id;
	private $item_data;

	public $product_id;
	public $product;

	public $bundle_id;

	private $optional;
	private $quantity;
	private $discount;
	private $sign_up_discount;

	private $per_product_pricing;

	private $title;
	private $description;
	private $visibility;

	private $selection_overrides;
	private $allowed_variations;

	private $purchasable;
	private $sold_individually;
	private $on_sale;
	private $nyp;

	private $stock_status;

	public $min_price;
	public $max_price;
	public $min_regular_price;
	public $max_regular_price;

	public $min_recurring_price;
	public $max_recurring_price;
	public $min_regular_recurring_price;
	public $max_regular_recurring_price;

	public $min_price_incl_tax;
	public $min_price_excl_tax;

	private $product_attributes;
	private $selected_product_attributes;

	private $product_variations;

	private $is_front_end;

	function __construct( $bundled_item_id, $parent ) {

		$this->item_id    = $bundled_item_id;
		$this->product_id = $parent->bundle_data[ $bundled_item_id ][ 'product_id' ];
		$this->bundle_id  = $parent->id;

		$this->item_data  = $parent->bundle_data[ $bundled_item_id ];

		// Do not process bundled item stock data in the back end, in order to speed things up just a bit
		$this->is_front_end = ( ! is_admin() ) || ( defined( 'DOING_AJAX' ) && true == DOING_AJAX );

		do_action( 'woocommerce_before_init_bundled_item', $this );

		$bundled_product = WC_PB_Core_Compatibility::wc_get_product( $this->product_id );

		// if not present, item cannot be purchased
		if ( $bundled_product ) {

			$this->product = $bundled_product;

			$this->title               = ! empty( $this->item_data[ 'override_title' ] ) && $this->item_data[ 'override_title' ] === 'yes' ? $this->item_data[ 'product_title' ] : $bundled_product->get_title();
			$this->description         = ! empty( $this->item_data[ 'override_description' ] ) && $this->item_data[ 'override_description' ] === 'yes' ? $this->item_data[ 'product_description' ] : $bundled_product->post->post_excerpt;
			$this->visibility          = ! empty( $this->item_data[ 'visibility' ] ) && in_array( $this->item_data[ 'visibility' ], array( 'hidden', 'secret' ) ) ? $this->item_data[ 'visibility' ] : 'visible';
			$this->optional            = ! empty( $this->item_data[ 'optional' ] ) && $this->item_data[ 'optional' ] === 'yes' ? 'yes' : 'no';
			$this->hide_thumbnail      = ! empty( $this->item_data[ 'hide_thumbnail' ] ) && $this->item_data[ 'hide_thumbnail' ] === 'yes' ? 'yes' : 'no';
			$this->quantity            = isset( $this->item_data[ 'bundle_quantity' ] ) ? absint( $this->item_data[ 'bundle_quantity' ] ) : 1;
			$this->discount            = ! empty( $this->item_data[ 'bundle_discount' ] ) ? ( double ) $this->item_data[ 'bundle_discount' ] : 0.0;
			$this->sign_up_discount    = ! empty( $this->item_data[ 'bundle_sign_up_discount' ] ) ? ( double ) $this->item_data[ 'bundle_sign_up_discount' ] : 0.0;
			$this->selection_overrides = ! empty( $this->item_data[ 'override_defaults' ] ) && $this->item_data[ 'override_defaults' ] === 'yes' ? $this->item_data[ 'bundle_defaults' ] : '';
			$this->allowed_variations  = ! empty( $this->item_data[ 'filter_variations' ] ) && $this->item_data[ 'filter_variations' ] === 'yes' ? $this->item_data[ 'allowed_variations' ] : '';
			$this->per_product_pricing = $parent->is_priced_per_product();
			$this->sold_individually   = false;
			$this->on_sale             = false;
			$this->nyp                 = false;
			$this->purchasable         = false;

			if ( $bundled_product->is_purchasable() ) {
				$this->purchasable = true;
				$this->init();
			}
		}

		do_action( 'woocommerce_after_init_bundled_item', $this );
	}

	/**
	 * Initializes a bundled item for access by the container: Calculates min and max prices, checks availability info, etc.
	 * @return void
	 */
	function init() {

		global $woocommerce_bundles;

		$product_id      = $this->product_id;
		$bundled_product = $this->product;

		$quantity = $this->get_quantity();
		$discount = $this->get_discount();

		$this->add_price_filters();

		/*-----------------------------------------------------------------------------------*/
		/*	Simple Subs
		/*-----------------------------------------------------------------------------------*/

		if ( $bundled_product->product_type === 'subscription' ) {

			if ( $this->is_front_end ) {

				if ( $bundled_product->is_sold_individually() ) {
					$this->sold_individually = true;
				}

				if ( ! $bundled_product->is_in_stock() || ! $bundled_product->has_enough_stock( $quantity ) ) {
					$this->stock_status = 'out-of-stock';
				}

				if ( $bundled_product->is_on_backorder() && $bundled_product->backorders_require_notification() ) {
					$this->stock_status = 'available-on-backorder';
				}
			}

			//	Recurring price

			$regular_recurring_fee = $bundled_product->get_regular_price();
			$recurring_fee         = $bundled_product->get_price();

			$this->min_regular_recurring_price = $this->max_regular_recurring_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $regular_recurring_fee );
			$this->min_recurring_price         = $this->max_recurring_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $recurring_fee );

			if ( $regular_recurring_fee > $recurring_fee ) {
				$this->on_sale = true;
			}

			//	Sign up price

			$regular_signup_fee      = $bundled_product->get_sign_up_fee();
			$signup_fee              = $this->get_sign_up_fee( $regular_signup_fee, $bundled_product );

			$regular_up_front_fee    = $regular_signup_fee + $this->get_prorated_price_for_subscription( $regular_recurring_fee );
			$up_front_fee            = $signup_fee + $this->get_prorated_price_for_subscription( $recurring_fee );

			$this->min_regular_price = $this->max_regular_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $regular_up_front_fee );
			$this->min_price         = $this->max_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $up_front_fee );

			if ( $regular_up_front_fee > $up_front_fee ) {
				$this->on_sale = true;
			}

			// Save prices incl or excl tax

			if ( $woocommerce_bundles->helpers->wc_option_calculate_taxes === 'yes' && $this->is_front_end ) {

				if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop === 'incl' ) {

					if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax === 'yes' ) {
						$this->min_price_incl_tax = $up_front_fee;
						$this->min_price_excl_tax = $bundled_product->get_price_excluding_tax( $quantity, $up_front_fee ) / $quantity;
					} else {
						$this->min_price_incl_tax = $this->min_price;
						$this->min_price_excl_tax = $up_front_fee;
					}
				} else {

					if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax === 'yes' ) {
						$this->min_price_incl_tax = $up_front_fee;
						$this->min_price_excl_tax = $this->min_price;
					} else {
						$this->min_price_incl_tax = $bundled_product->get_price_including_tax( $quantity, $up_front_fee ) / $quantity;
						$this->min_price_excl_tax = $up_front_fee;
					}
				}
			}

		/*-----------------------------------------------------------------------------------*/
		/*	Simple Products
		/*-----------------------------------------------------------------------------------*/

		} elseif ( $bundled_product->product_type == 'simple' ) {

			if ( $this->is_front_end ) {

				if ( $bundled_product->is_sold_individually() ) {
					$this->sold_individually = true;
				}

				if ( ! $bundled_product->is_in_stock() || ! $bundled_product->has_enough_stock( $quantity ) ) {
					$this->stock_status = 'out-of-stock';
				}

				if ( $bundled_product->is_on_backorder() && $bundled_product->backorders_require_notification() ) {
					$this->stock_status = 'available-on-backorder';
				}

			}

			$regular_price = $bundled_product->get_regular_price();
			$price         = $bundled_product->get_price();

			// Name your price support

			if ( $woocommerce_bundles->compatibility->is_nyp( $bundled_product ) ) {

				$regular_price = $price = WC_Name_Your_Price_Helpers::get_minimum_price( $product_id ) ? WC_Name_Your_Price_Helpers::get_minimum_price( $product_id ) : 0;
				$this->nyp = true;
			}

			$this->min_regular_price = $this->max_regular_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $regular_price );
			$this->min_price         = $this->max_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $price );

			if ( $regular_price > $price ) {
				$this->on_sale = true;
			}

			// Save prices incl or excl tax

			if ( $woocommerce_bundles->helpers->wc_option_calculate_taxes === 'yes' && $this->is_front_end ) {

				if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop === 'incl' ) {

					if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax === 'yes' ) {
						$this->min_price_incl_tax = $price;
						$this->min_price_excl_tax = $bundled_product->get_price_excluding_tax( $quantity, $price ) / $quantity;
					} else {
						$this->min_price_incl_tax = $this->min_price;
						$this->min_price_excl_tax = $price;
					}

				} else {

					if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax === 'yes' ) {
						$this->min_price_incl_tax = $price;
						$this->min_price_excl_tax = $this->min_price;
					} else {
						$this->min_price_incl_tax = $bundled_product->get_price_including_tax( $quantity, $price ) / $quantity;
						$this->min_price_excl_tax = $price;
					}
				}
			}

		/*-----------------------------------------------------------------------------------*/
		/*	Variable Products
		/*-----------------------------------------------------------------------------------*/

		} elseif ( $bundled_product->product_type === 'variable' ) {

			$min_variation_regular_price  = '';
			$min_variation_price          = '';
			$max_variation_regular_price  = '';
			$max_variation_price          = '';

			$min_variation_price_incl_tax = '';
			$min_variation_price_excl_tax = '';

			// check stock status of variations - if all of them are out of stock, the product cannot be purchased
			$variation_in_stock_exists    = $this->is_front_end ? false : true;
			$all_variations_on_backorder  = $this->is_front_end ? true : false;

			// sold individually status
			if ( $this->is_front_end && $bundled_product->is_sold_individually() == 'yes' ) {
				$this->sold_individually = true;
			}

			// Process variations
			foreach ( $bundled_product->get_children() as $child_id ) {

				// Do not continue if variation is filtered
				if ( ! empty( $this->allowed_variations ) ) {
					if ( is_array( $this->allowed_variations ) && ! in_array( $child_id, $this->allowed_variations ) ) {
						continue;
					}
				}

				// Use this to improve performance instead of grabbing it like so: $variation = $bundled_product->get_child( $child_id, $args )
				$variation = $woocommerce_bundles->helpers->get_variation( $child_id, $bundled_product );

				if ( ! empty( $variation->variation_id ) ) {

					if ( $this->is_front_end ) {

						// check stock status of variation - if one of them is in stock, the product can be purchased
						if ( ! $variation_in_stock_exists && $variation->is_in_stock() && $variation->has_enough_stock( $quantity ) ) {
							$variation_in_stock_exists = true;
						}

						if ( $all_variations_on_backorder && ( ! $variation->is_on_backorder() || ! $bundled_product->backorders_require_notification() ) ) {
							$all_variations_on_backorder = false;
						}
					}

					// Price info

					// Calculate min / max variation price only if filters are present.
					// Otherwise, its much faster to grab those from the parent product.

					if ( $this->is_priced_per_product() && ! empty( $this->allowed_variations ) ) {

						$variation_regular_price_raw = $variation->get_regular_price();
						$variation_price_raw         = $variation->get_price();

						$variation_regular_price     = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $variation, $variation_regular_price_raw );
						$variation_price             = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $variation, $variation_price_raw );

						// on sale status
						if ( $variation_regular_price > $variation_price ) {
							$this->on_sale = true;
						}

						// Prices incl and excl tax

						if ( $woocommerce_bundles->helpers->wc_option_calculate_taxes == 'yes' && $this->is_front_end ) {

							if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop == 'incl' ) {

								if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax == 'yes' ) {
									$variation_price_incl_tax = $variation_price_raw;
									$variation_price_excl_tax = $bundled_product->get_price_excluding_tax( $quantity, $variation_price_raw ) / $quantity;
								} else {
									$variation_price_incl_tax = $variation_price;
									$variation_price_excl_tax = $variation_price_raw;
								}

							} else {

								if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax == 'yes' ) {
									$variation_price_incl_tax = $variation_price_raw;
									$variation_price_excl_tax = $variation_price;
								} else {
									$variation_price_incl_tax = $bundled_product->get_price_including_tax( $quantity, $variation_price_raw ) / $quantity;
									$variation_price_excl_tax = $variation_price_raw;
								}
							}
						}

						// lowest price
						if ( ! is_numeric( $min_variation_regular_price ) || $variation_regular_price < $min_variation_regular_price ) {
							$min_variation_regular_price = $variation_regular_price;
						}
						if ( ! is_numeric( $min_variation_price ) || $variation_price < $min_variation_price ) {
							$min_variation_price = $variation_price;
						}

						// highest price
						if ( ! is_numeric( $max_variation_regular_price ) || $variation_regular_price > $max_variation_regular_price ) {
							$max_variation_regular_price = $variation_regular_price;
						}
						if ( ! is_numeric( $max_variation_price ) || $variation_price > $max_variation_price ) {
							$max_variation_price = $variation_price;
						}

						// taxed
						if ( $woocommerce_bundles->helpers->wc_option_calculate_taxes == 'yes' && $this->is_front_end ) {

							if ( ! is_numeric( $min_variation_price_incl_tax ) || $variation_price_incl_tax < $min_variation_price_incl_tax ) {
								$min_variation_price_incl_tax = $variation_price_incl_tax;
							}

							if ( ! is_numeric( $min_variation_price_excl_tax ) || $variation_price_excl_tax < $min_variation_price_excl_tax ) {
								$min_variation_price_excl_tax = $variation_price_excl_tax;
							}
						}
					}
				}
			}

			if ( $variation_in_stock_exists == false ) {
				$this->stock_status = 'out-of-stock';
			}

			if ( $all_variations_on_backorder ) {
				$this->stock_status = 'available-on-backorder';
			}

			// When no variation filters are present, its much faster to grab the min/max variation price data from the parent product meta.
			if ( empty( $this->allowed_variations ) ) {

				if ( ! empty( $discount ) ) {

					// Product may need to be synced
					if ( $bundled_product->get_variation_regular_price( 'min', false ) === false ) {
						$bundled_product->variable_product_sync();
					}

					// Grab the min/max regular price variation since discounts are calculated on top of the regular price only.
					$min_variation_id            = get_post_meta( $this->product_id, '_min_regular_price_variation_id', true );
					$min_variation               = $woocommerce_bundles->helpers->get_variation( $min_variation_id, $bundled_product );

					$min_variation_price_raw     = $min_variation->get_price();

					$min_variation_regular_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $min_variation->get_regular_price() );
					$min_variation_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $min_variation_price_raw );


					$max_variation_id            = get_post_meta( $this->product_id, '_max_regular_price_variation_id', true );
					$max_variation               = $woocommerce_bundles->helpers->get_variation( $max_variation_id, $bundled_product );

					$max_variation_regular_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $max_variation->get_regular_price() );
					$max_variation_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $max_variation->get_price() );

					$this->on_sale = $max_variation_regular_price > 0 || $max_variation_price > 0;

				} else {

					// Product may need to be synced
					if ( $bundled_product->get_variation_price( 'min', false ) === false ) {
						$bundled_product->variable_product_sync();
					}

					// Grab the min/max price variation since there is no discount.
					$min_variation_id            = get_post_meta( $this->product_id, '_min_price_variation_id', true );
					$min_variation               = $woocommerce_bundles->helpers->get_variation( $min_variation_id, $bundled_product );

					$min_variation_price_raw     = $min_variation->get_price();

					$min_variation_regular_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $min_variation->get_regular_price() );
					$min_variation_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $min_variation_price_raw );


					$max_variation_id            = get_post_meta( $this->product_id, '_max_price_variation_id', true );
					$max_variation               = $woocommerce_bundles->helpers->get_variation( $max_variation_id, $bundled_product );

					$max_variation_regular_price = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $max_variation->get_regular_price() );
					$max_variation_price         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $max_variation->get_price());

					// sale status can be obtained from the parent since all variations are active
					if ( $this->is_priced_per_product() ) {
						$this->on_sale = $bundled_product->is_on_sale();
					}
				}

				// Prices incl and excl tax

				if ( $woocommerce_bundles->helpers->wc_option_calculate_taxes == 'yes' && $this->is_front_end ) {

					if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop == 'incl' ) {

						if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax == 'yes' ) {
							$min_variation_price_incl_tax = $min_variation_price_raw;
							$min_variation_price_excl_tax = $bundled_product->get_price_excluding_tax( $quantity, $min_variation_price_raw ) / $quantity;
						} else {
							$min_variation_price_incl_tax = $min_variation_price;
							$min_variation_price_excl_tax = $min_variation_price_raw;
						}

					} else {

						if ( $woocommerce_bundles->helpers->wc_option_prices_include_tax == 'yes' ) {
							$min_variation_price_incl_tax = $min_variation_price_raw;
							$min_variation_price_excl_tax = $min_variation_price;
						} else {
							$min_variation_price_incl_tax = $bundled_product->get_price_including_tax( $quantity, $min_variation_price_raw ) / $quantity;
							$min_variation_price_excl_tax = $min_variation_price_raw;
						}
					}
				}

			}

			$this->min_price          = ( $min_variation_regular_price < $min_variation_price ) ? $min_variation_regular_price : $min_variation_price;
			$this->min_regular_price  = $min_variation_regular_price;

			$this->max_price          = ( $max_variation_regular_price < $max_variation_price ) ? $max_variation_regular_price : $max_variation_price;
			$this->max_regular_price  = $max_variation_regular_price;


			$this->min_price_excl_tax = $min_variation_price_excl_tax;
			$this->min_price_incl_tax = $min_variation_price_incl_tax;
		}

		$this->remove_price_filters();

	}

	/**
	 * True if the bundled item is priced per product.
	 *
	 * @return boolean
	 */
	function is_priced_per_product() {

		$is_ppp = false;

		if ( $this->per_product_pricing ) {
			$is_ppp = true;
		}

		return apply_filters( 'woocommerce_bundle_is_priced_per_product', $is_ppp, $this );
	}

	/**
	 * Bundled item sale status.
	 *
	 * @return  boolean  true if on sale
	 */
	function is_on_sale() {

		$on_sale = $this->on_sale;

		if ( $this->is_out_of_stock() ) {
			return false;
		}

		return $on_sale;
	}

	/**
	 * Bundled item purchasable status.
	 *
	 * @return  boolean  true if purchasable
	 */
	function is_purchasable() {

		return $this->purchasable;
	}

	/**
	 * Bundled item exists status.
	 *
	 * @return  boolean  true if purchasable
	 */
	function exists() {

		return ! empty( $this->product );
	}

	/**
	 * Bundled item out of stock status.
	 *
	 * @return  boolean  true if out of stock
	 */
	function is_out_of_stock() {

		if ( $this->stock_status == 'out-of-stock' ) {
			return true;
		}

		return false;
	}

	/**
	 * Bundled item backorder status.
	 *
	 * @return  boolean  true if on backorder
	 */
	function is_on_backorder() {

		if ( $this->stock_status == 'available-on-backorder' ) {
			return true;
		}

		return false;
	}

	/**
	 * Bundled item sold individually status.
	 *
	 * @return boolean  true if sold individually
	 */
	function is_sold_individually() {

		if ( $this->sold_individually == true ) {
			return true;
		}

		return false;
	}

	/**
	 * Bundled item name-your-price status.
	 *
	 * @return boolean  true if item is NYP
	 */
	function is_nyp() {

		return $this->nyp;
	}

	/**
	 * Check if the product has variables to adjust before adding to cart.
	 * Conditions: ( is NYP ) or ( has required addons ) or ( has options )
	 *
	 * @return boolean  true if the item has variables to adjust before adding to cart
	 */
	function has_variables() {

		global $woocommerce_bundles;

		if ( $this->is_nyp() || $woocommerce_bundles->compatibility->has_required_addons( $this->product_id ) || $this->product->product_type === 'variable' ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the item is a subscription.
	 *
	 * @return boolean  true if the item is a sub
	 */
	function is_sub() {

		if ( $this->product->product_type === 'subscription' ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the variation attributes array if this product is variable.
	 *
	 * @return array
	 */
	function get_product_variation_attributes() {

		if ( ! empty( $this->product_attributes ) ) {
			return $this->product_attributes;
		}

		if ( $this->product->product_type === 'variable' ) {
			$this->product_attributes = $this->product->get_variation_attributes();
			return $this->product_attributes;
		}

		return false;

	}

	/**
	 * Returns the selected variation attributes if this product is variable.
	 *
	 * @return array
	 */
	function get_selected_product_variation_attributes() {

		if ( ! empty( $this->selected_product_attributes ) ) {
			return $this->selected_product_attributes;
		}

		if ( $this->product->product_type === 'variable' ) {

			if ( ! empty( $this->selection_overrides ) ) {
				$selected_product_attributes = $this->selection_overrides;
			} else {
				$selected_product_attributes = ( array ) maybe_unserialize( get_post_meta( $this->product_id, '_default_attributes', true ) );
			}

			$this->selected_product_attributes = apply_filters( 'woocommerce_product_default_attributes', $selected_product_attributes );

			return $this->selected_product_attributes;
		}

		return false;
	}

	/**
	 * Returns this product's available variations array.
	 *
	 * @return array
	 */
	function get_product_variations() {

		if ( ! empty( $this->product_variations ) ) {
			return $this->product_variations;
		}

		if ( $this->product->product_type === 'variable' ) {

			do_action( 'woocommerce_before_init_bundled_item', $this );

			// filter children to exclude filtered out variations
			add_filter( 'woocommerce_get_children', array( $this, 'bundled_item_children' ), 10, 2 );

			// filter variations data
			add_filter( 'woocommerce_available_variation', array( $this, 'bundled_item_available_variation' ), 10, 3 );

			$this->add_price_filters();

			$bundled_item_variations = $this->product->get_available_variations();

			$this->remove_price_filters();

			remove_filter( 'woocommerce_available_variation', array( $this, 'bundled_item_available_variation' ), 10, 3 );

			remove_filter( 'woocommerce_get_children', array( $this, 'bundled_item_children' ), 10, 2 );

			do_action( 'woocommerce_after_init_bundled_item', $this );

			// add only active variations
			foreach ( $bundled_item_variations as $variation_data ) {

				if ( ! empty( $variation_data ) ) {
					$this->product_variations[] = $variation_data;
				}

			}

			return $this->product_variations;
		}

		return false;
	}

	/**
	 * Filter variable product children to exclude filtered out variations and improve performance of 'WC_Product_Variable::get_available_variations'
	 *
	 * @param  array                $children         ids of variations to load
	 * @param  WC_Product_Variable  $bundled_product  variable bundled product
	 * @return array                                  modified ids of variations to load
	 */
	function bundled_item_children( $children, $bundled_product ) {

		if ( empty( $this->allowed_variations ) || ! is_array( $this->allowed_variations ) ) {
			return $children;
		} else {
			$filtered_children = array();

			foreach ( $children as $variation_id ) {
				// Remove if filtered
				if ( in_array( $variation_id, $this->allowed_variations ) ) {
					$filtered_children[] = $variation_id;
				}
			}

			return $filtered_children;
		}
	}

	/**
	 * Modifies the results of get_available_variations() to implement variation filtering and bundle discounts for variable products.
	 * Also calculates variation prices incl. or excl. tax.
	 *
	 * @param  array                  $variation_data     unmodified variation data
	 * @param  WC_Product             $bundled_product    the bundled product
	 * @param  WC_Product_Variation   $bundled_variation  the variation in question
	 * @return array                                      modified variation data
	 */
	function bundled_item_available_variation( $variation_data, $bundled_product, $bundled_variation ) {

		global $woocommerce_bundles;

		$bundled_item_id = $this->item_id;

		// Disable if certain conditions are met
		if ( ! empty( $this->allowed_variations ) ) {

			if ( ! is_array( $this->allowed_variations ) ) {
				return array();
			}

			if ( ! in_array( $bundled_variation->variation_id, $this->allowed_variations ) ) {
				return array();
			}
		}

		if ( $bundled_variation->price === '' ) {
			return array();
		}

		// Modify product id for JS (deprecated)
		$variation_data[ 'product_id' ]    = $bundled_item_id;

		// Add price data
		$variation_data[ 'regular_price' ] = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_variation, $bundled_variation->get_regular_price() );
		$variation_data[ 'price' ]         = $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_variation, $bundled_variation->get_price() );

		$variation_data[ 'price_html' ]    = $this->is_priced_per_product() ? ( $variation_data[ 'price_html' ] === '' ? '<p class="price">' . $bundled_variation->get_price_html() . '</p>' : $variation_data[ 'price_html' ] ) : '';

		// Modify availability data
		$quantity     = $this->get_quantity();
		$quantity_max = $this->get_quantity( 'max' );
		$availability = $this->get_availability( $bundled_variation );

		if ( ! $bundled_variation->is_in_stock() || ! $bundled_variation->has_enough_stock( $quantity ) ) {
			$variation_data[ 'is_in_stock' ] = false;
		}

		if ( $bundled_variation->is_on_backorder() && $bundled_product->backorders_require_notification() ) {
			$variation_data[ 'is_on_backorder' ] = 'available-on-backorder';
		}

		$availability_html = ( ! empty( $availability[ 'availability' ] ) ) ? apply_filters( 'woocommerce_stock_html', '<p class="stock ' . $availability[ 'class' ] . '">'. $availability[ 'availability' ].'</p>', $availability[ 'availability' ]  ) : '';

		$variation_data[ 'availability_html' ] = $availability_html;

		$variation_data[ 'min_qty' ] = $quantity;
		$variation_data[ 'max_qty' ] = $quantity_max;

		return $variation_data;
	}

	/**
	 * Add price filters to implement bundle discounts.
	 *
	 * @return void
	 */
	function add_price_filters() {

		add_filter( 'woocommerce_get_price', array( $this, 'get_price' ), 15, 2 );
		add_filter( 'woocommerce_get_sale_price', array( $this, 'get_sale_price' ), 15, 2 );
		add_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price' ), 15, 2 );
		add_filter( 'woocommerce_get_price_html', array( $this, 'get_price_html' ), 10, 2 );
		add_filter( 'woocommerce_get_variation_price_html', array( $this, 'get_price_html' ), 10, 2 );
	}

	/**
	 * Removes discount filters.
	 *
	 * @return void
	 */
	function remove_price_filters() {

		remove_filter( 'woocommerce_get_price', array( $this, 'get_price' ), 15, 2 );
		remove_filter( 'woocommerce_get_sale_price', array( $this, 'get_sale_price' ), 15, 2 );
		remove_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price' ), 15, 2 );
		remove_filter( 'woocommerce_get_price_html', array( $this, 'get_price_html' ), 10, 2 );
		remove_filter( 'woocommerce_get_variation_price_html', array( $this, 'get_price_html' ), 10, 2 );
	}

	/**
	 * Filter get_price() calls for bundled products to include discounts.
	 *
	 * @param  double       $price      unmodified price
	 * @param  WC_Product   $product    the bundled product
	 * @return double                   modified price
	 */
	function get_price( $price, $product ) {

		if ( $product->id !== $this->product->id || $price === '' ) {
			return $price;
		}

		if ( ! $this->is_priced_per_product() ) {
			return 0;
		}

		if ( apply_filters( 'woocommerce_bundled_item_discount_from_regular', true, $this ) ) {
			$regular_price = $product->get_regular_price();
		} else {
			$regular_price = $price;
		}

		$discount                    = $this->get_discount();
		$bundled_item_price          = empty( $discount ) ? ( double ) $price : round( ( double ) $regular_price * ( 100 - $discount ) / 100, wc_bundles_get_price_decimals() );

		$product->bundled_item_price = $bundled_item_price;

		return apply_filters( 'woocommerce_bundled_item_price', $bundled_item_price, $product, $discount );
	}

	/**
	 * Filter get_sale_price() calls for bundled products to include discounts.
	 *
	 * @param  double       $price      unmodified reg price
	 * @param  WC_Product   $product    the bundled product
	 * @return double                   modified reg price
	 */
	function get_sale_price( $sale_price, $product ) {

		if ( $product->id !== $this->product->id ) {
			return $sale_price;
		}

		if ( ! $this->is_priced_per_product() ) {
			return 0;
		}

		$discount   = $this->get_discount();
		$sale_price = empty( $discount ) ? ( double ) $sale_price : $this->get_price( $product->price, $product );

		return $sale_price;
	}

	/**
	 * Filter get_regular_price() calls for bundled products to include discounts.
	 *
	 * @param  double       $price      unmodified reg price
	 * @param  WC_Product   $product    the bundled product
	 * @return double                   modified reg price
	 */
	function get_regular_price( $regular_price, $product ) {

		if ( $product->id !== $this->product->id ) {
			return $regular_price;
		}

		if ( ! $this->is_priced_per_product() ) {
			return 0;
		}

		return empty( $regular_price ) ? ( double ) $product->price : ( double ) $regular_price;
	}

	/**
	 * Filter the html price string of bundled items to show the correct price with discount and tax - needs to be hidden in per-product pricing mode.
	 *
	 * @param  string      $price_html    unmodified price string
	 * @param  WC_Product  $product       the bundled product
	 * @return string                     modified price string
	 */
	function get_price_html( $price_html, $product ) {

		global $woocommerce_bundles;

		if ( ! $this->is_priced_per_product() ) {
			return '';
		}

		$quantity = $this->get_quantity();

		/* translators: for quantity use %2$s */
		return apply_filters( 'woocommerce_bundled_item_price_html', $quantity > 1 ? sprintf( __( '%1$s <span class="bundled_item_price_quantity">/ pc.</span>', 'woocommerce-product-bundles' ), $price_html, $quantity ) : $price_html, $price_html, $this );
	}

	/**
	 * Filter get_sign_up_fee() calls for bundled subs to include discounts.
	 *
	 * @param  double       $price      unmodified price
	 * @param  WC_Product   $product    the bundled sub
	 * @return double                   modified price
	 */
	function get_sign_up_fee( $sign_up_fee, $product ) {

		if ( $product->id !== $this->product->id ) {
			return $price;
		}

		if ( ! $this->is_priced_per_product() ) {
			return 0;
		}

		$discount = $this->get_sign_up_discount();

		return empty( $discount ) ? ( double ) $sign_up_fee : ( double ) $sign_up_fee * ( 100 - $discount ) / 100;
	}

	/**
	 * True if there is a title override.
	 *
	 * @return boolean
	 */
	function has_title_override() {
		if ( ! empty( $this->item_data[ 'override_title' ] ) && $this->item_data[ 'override_title' ] == 'yes' ) {
			return true;
		}

		return false;
	}

	/**
	 * Item title.
	 *
	 * @return string item title
	 */
	function get_title() {
		return apply_filters( 'woocommerce_bundled_item_title', $this->title, $this );
	}

	/**
	 * Item raw item title.
	 *
	 * @return string item title
	 */
	function get_raw_title() {

		$title = $this->get_title();

		if ( $title === '' ) {
			$title = $this->product->get_title();
		}

		return apply_filters( 'woocommerce_bundled_item_raw_title', $title, $this );
	}

	/**
	 * Item title.
	 *
	 * @return string item title
	 */
	function get_description() {
		return apply_filters( 'woocommerce_bundled_item_description', $this->description, $this );
	}

	/**
	 * Visible or hidden item.
	 *
	 * @return boolean true if visible
	 */
	function is_visible() {
		return $this->visibility === 'visible' ? true : false;
	}

	/**
	 * Item hidden from all templates.
	 *
	 * @return boolean true if secret
	 */
	function is_secret() {
		return $this->visibility === 'secret' ? true : false;
	}

	/**
	 * Optional item.
	 *
	 * @return boolean true if optional
	 */
	function is_optional() {
		return $this->optional === 'yes' ? true : false;
	}

	/**
	 * Item min/max quantity.
	 *
	 * @return int
	 */
	function get_quantity( $min_or_max = 'min' ) {

		$qty = $this->quantity;

		if ( $min_or_max === 'min' ) {
			$qty = apply_filters( 'woocommerce_bundled_item_quantity', $qty, $this );
		} elseif ( $min_or_max === 'max' ) {
			$qty = ! empty( $this->item_data[ 'bundle_quantity_max' ] ) ? apply_filters( 'woocommerce_bundled_item_quantity_max', max( $this->item_data[ 'bundle_quantity_max' ], $qty ), $this ) : $qty;
		}

		return $qty;
	}

	/**
	 * Item discount.
	 *
	 * @return int
	 */
	function get_discount() {
		return apply_filters( 'woocommerce_bundled_item_discount', $this->discount, $this );
	}

	/**
	 * Item sign-up discount.
	 *
	 * @return int
	 */
	function get_sign_up_discount() {
		return apply_filters( 'woocommerce_bundled_item_sign_up_discount', $this->sign_up_discount, $this );
	}

	/**
	 * Checkbox state for optional bundled items.
	 *
	 * @return boolean
	 */
	function is_optional_checked() {

		if ( ! $this->is_optional() ) {
			return false;
		}

		if ( isset( $_REQUEST[ apply_filters( 'woocommerce_product_bundle_field_prefix', '', $this->product_id ) . 'bundle_selected_optional_' . $this->item_id ] ) ) {
			$checked = true;
		} else {
			$checked = false;
		}

		return apply_filters( 'woocommerce_bundled_item_is_optional_checked', $checked, $this );
	}

	/**
	 * Visible or hidden item thumbnail.
	 *
	 * @return boolean true if visible
	 */
	function is_thumbnail_visible() {
		return $this->hide_thumbnail === 'yes' ? false : true;
	}

	/**
	 * Get classes for template use.
	 *
	 * @return string
	 */
	function get_classes() {

		$classes = array();

		if ( $this->get_quantity( 'min' ) !== $this->get_quantity( 'max' ) & ! $this->is_out_of_stock() ) {
			$classes[] = 'has_qty_input'; // add 'float_qty_input' class to float the quantity input right
		}

		return implode( ' ', apply_filters( 'woocommerce_bundled_item_classes', $classes, $this ) );
	}

	/**
	 * Bundled product availability that takes min_quantity > 1 into account.
	 *
	 * @return array
	 */
	function get_availability( $product = false ) {

		if ( ! $product ) {
			$product = $this->product;
		}

		$quantity     = $this->get_quantity();
		$availability = $class = '';

		if ( $product->managing_stock() ) {

			if ( $product->is_in_stock() && $product->get_total_stock() > get_option( 'woocommerce_notify_no_stock_amount' ) && $product->get_total_stock() >= $quantity ) {

				switch ( get_option( 'woocommerce_stock_format' ) ) {

					case 'no_amount' :
						$availability = __( 'In stock', 'woocommerce' );
					break;

					case 'low_amount' :
						if ( $product->get_total_stock() <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
							$availability = sprintf( __( 'Only %s left in stock', 'woocommerce' ), $product->get_total_stock() );

							if ( $product->backorders_allowed() && $product->backorders_require_notification() ) {
								$availability .= ' ' . __( '(can be backordered)', 'woocommerce' );
							}
						} else {
							$availability = __( 'In stock', 'woocommerce' );
						}
					break;

					default :
						$availability = sprintf( __( '%s in stock', 'woocommerce' ), $product->get_total_stock() );

						if ( $product->backorders_allowed() && $product->backorders_require_notification() ) {
							$availability .= ' ' . __( '(can be backordered)', 'woocommerce' );
						}
					break;
				}

				$class = 'in-stock';

			} elseif ( $product->backorders_allowed() && $product->backorders_require_notification() ) {

				if ( $product->get_total_stock() >= $quantity || get_option( 'woocommerce_stock_format' ) === 'no_amount' || $product->get_total_stock() <= 0 ) {
					$availability = __( 'Available on backorder', 'woocommerce' );
				} else {
					$availability = __( 'Available on backorder', 'woocommerce' ) . ' ' . sprintf( __( '(only %s left in stock)', 'woocommerce-product-bundles' ), $product->get_total_stock() );
				}

				$class = 'available-on-backorder';

			} elseif ( $product->backorders_allowed() ) {

				$availability = __( 'In stock', 'woocommerce' );
				$class        = 'in-stock';

			} else {

				if ( $product->is_in_stock() && $product->get_total_stock() > get_option( 'woocommerce_notify_no_stock_amount' ) ) {

					if ( get_option( 'woocommerce_stock_format' ) === 'no_amount' ) {
						$availability = __( 'Insufficient stock', 'woocommerce-product-bundles' );
					} else {
						$availability = __( 'Insufficient stock', 'woocommerce-product-bundles' ) . ' ' . sprintf( __( '(only %s left in stock)', 'woocommerce-product-bundles' ), $product->get_total_stock() );
					}

					$class = 'out-of-stock';

				} else {

					$availability = __( 'Out of stock', 'woocommerce' );
					$class        = 'out-of-stock';
				}
			}

		} elseif ( ! $product->is_in_stock() ) {

			$availability = __( 'Out of stock', 'woocommerce' );
			$class        = 'out-of-stock';
		}

		return apply_filters( 'woocommerce_bundled_item_availability', array( 'availability' => $availability, 'class' => $class ), $this );
	}

	/**
	 * Get prorated sub price.
	 *
	 * @param  double     $recurring_price
	 * @param  WC_Product $product
	 * @return double
	 */
	public function get_prorated_price_for_subscription( $recurring_price, $product = false ) {

		if ( ! $product ) {
			$product = $this->product;
		}

		$price   = 0;

		if ( WC_Subscriptions_Product::is_subscription( $product ) ) {

			if ( 0 == WC_Subscriptions_Product::get_trial_length( $product ) ) {

				if ( WC_Subscriptions_Synchroniser::is_product_prorated( $product ) ) {

					$next_payment_date = WC_Subscriptions_Synchroniser::calculate_first_payment_date( $product, 'timestamp' );

					if ( WC_Subscriptions_Synchroniser::is_today( $next_payment_date ) ) {
						return $recurring_price;
					}

					switch( $product->subscription_period ) {
						case 'week' :
							$days_in_cycle = 7 * $product->subscription_period_interval;
							break;
						case 'month' :
							$days_in_cycle = date( 't' ) * $product->subscription_period_interval;
							break;
						case 'year' :
							$days_in_cycle = ( 365 + date( 'L' ) ) * $product->subscription_period_interval;
							break;
					}

					$days_until_next_payment = ceil( ( $next_payment_date - gmdate( 'U' ) ) / ( 60 * 60 * 24 ) );

					$price = $days_until_next_payment * ( $recurring_price / $days_in_cycle );

				} else {

					$price = $recurring_price;
				}

			}

		}

		return $price;
	}
}
