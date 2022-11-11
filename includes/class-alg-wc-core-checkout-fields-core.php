<?php
/**
 * Core Checkout Fields for WooCommerce - Core Class
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Core_Checkout_Fields_Core' ) ) :

class Alg_WC_Core_Checkout_Fields_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @see     https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
	 * @todo    [dev] (maybe) default overrides should be `disable`
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_core_checkout_fields_plugin_enabled', 'yes' ) ) {
			add_filter( 'woocommerce_checkout_fields' , array( $this, 'customize_checkout_fields' ), PHP_INT_MAX );
			if ( 'disable' != ( $this->country_locale_override = get_option( 'alg_wc_core_checkout_fields_override_country_locale_fields', 'billing' ) ) ) {
				add_filter( 'woocommerce_get_country_locale', array( $this, 'custom_override_country_locale_fields' ), PHP_INT_MAX );
			}
			if ( 'disable' != ( $this->default_address_override = get_option( 'alg_wc_core_checkout_fields_override_default_address_fields', 'billing' ) ) ) {
				add_filter( 'woocommerce_default_address_fields', array( $this, 'custom_override_default_address_fields' ), PHP_INT_MAX );
			}
		}
	}

	/**
	 * get_fields.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @todo    [dev] (maybe) there should be a better way to get core fields directly from WC
	 */
	function get_fields() {
		return array(
			'billing_country'     => __( 'Billing country', 'core-checkout-fields-for-woocommerce' ),
			'billing_first_name'  => __( 'Billing first name', 'core-checkout-fields-for-woocommerce' ),
			'billing_last_name'   => __( 'Billing last name', 'core-checkout-fields-for-woocommerce' ),
			'billing_company'     => __( 'Billing company', 'core-checkout-fields-for-woocommerce' ),
			'billing_address_1'   => __( 'Billing address', 'core-checkout-fields-for-woocommerce' ),
			'billing_address_2'   => __( 'Billing address 2', 'core-checkout-fields-for-woocommerce' ),
			'billing_city'        => __( 'Billing city', 'core-checkout-fields-for-woocommerce' ),
			'billing_state'       => __( 'Billing state', 'core-checkout-fields-for-woocommerce' ),
			'billing_postcode'    => __( 'Billing postcode', 'core-checkout-fields-for-woocommerce' ),
			'billing_email'       => __( 'Billing email', 'core-checkout-fields-for-woocommerce' ),
			'billing_phone'       => __( 'Billing phone', 'core-checkout-fields-for-woocommerce' ),
			'shipping_country'    => __( 'Shipping country', 'core-checkout-fields-for-woocommerce' ),
			'shipping_first_name' => __( 'Shipping first name', 'core-checkout-fields-for-woocommerce' ),
			'shipping_last_name'  => __( 'Shipping last name', 'core-checkout-fields-for-woocommerce' ),
			'shipping_company'    => __( 'Shipping company', 'core-checkout-fields-for-woocommerce' ),
			'shipping_address_1'  => __( 'Shipping address', 'core-checkout-fields-for-woocommerce' ),
			'shipping_address_2'  => __( 'Shipping address 2', 'core-checkout-fields-for-woocommerce' ),
			'shipping_city'       => __( 'Shipping city', 'core-checkout-fields-for-woocommerce' ),
			'shipping_state'      => __( 'Shipping state', 'core-checkout-fields-for-woocommerce' ),
			'shipping_postcode'   => __( 'Shipping postcode', 'core-checkout-fields-for-woocommerce' ),
			'account_username'    => __( 'Account username', 'core-checkout-fields-for-woocommerce' ),
			'account_password'    => __( 'Account password', 'core-checkout-fields-for-woocommerce' ),
			'account_password-2'  => __( 'Account password 2', 'core-checkout-fields-for-woocommerce' ),
			'order_comments'      => __( 'Order comments', 'core-checkout-fields-for-woocommerce' ),
		);
	}

	/**
	 * get_data_options.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_data_options() {
		return apply_filters( 'alg_wc_core_checkout_fields_data_options', array(
				'required'    => 'default',
				'label'       => '',
				'placeholder' => '',
				'description' => '',
				'class'       => 'default',
			) );
	}

	/**
	 * get_visibility_options.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_visibility_options() {
		return apply_filters( 'alg_wc_core_checkout_fields_visibility_options', array(
				'enabled'     => 'default',
			) );
	}

	/**
	 * get_options.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_options() {
		return array_merge( $this->get_visibility_options(), $this->get_data_options() );
	}

	/**
	 * maybe_override_fields.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @todo    [dev] (maybe) add `enabled` to `$options_to_override`
	 * @todo    [feature] (maybe) add option to choose `$options_to_override`
	 */
	function maybe_override_fields( $fields, $override_with_section ) {
		$fields_data = $this->get_fields_data();
		foreach ( $fields as $field_key => $field_values ) {
			$this->set_field_data_options( $fields[ $field_key ], $fields_data[ $override_with_section . '_' . $field_key ] );
		}
		return $fields;
	}

	/**
	 * custom_override_country_locale_fields.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function custom_override_country_locale_fields( $fields ) {
		foreach ( $fields as $country => $country_fields ) {
			$fields[ $country ] = $this->maybe_override_fields( $country_fields, $this->country_locale_override );
		}
		return $fields;
	}

	/**
	 * custom_override_default_address_fields.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function custom_override_default_address_fields( $fields ) {
		return $this->maybe_override_fields( $fields, $this->default_address_override );
	}

	/**
	 * get_fields_data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_fields_data() {
		if ( isset( $this->fields_data ) ) {
			return $this->fields_data;
		}
		$options_data = array();
		foreach ( $this->get_options() as $option => $default ) {
			$options_data[ $option ] = get_option( 'alg_wc_core_checkout_field_' . $option, array() );
		}
		$this->fields_data = array();
		foreach ( $this->get_fields() as $field_id => $field_title ) {
			foreach ( $this->get_options() as $option => $default ) {
				$this->fields_data[ $field_id ][ $option ] = ( isset( $options_data[ $option ][ $field_id ] ) ? $options_data[ $option ][ $field_id ] : $default );
			}
		}
		return $this->fields_data;
	}

	/**
	 * set_field_data_options.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function set_field_data_options( &$field, $field_data ) {
		foreach ( $this->get_data_options() as $option_id => $option_default ) {
			if ( $option_default != $field_data[ $option_id ] ) {
				if ( 'required' === $option_id ) {
					$field[ $option_id ] = ( 'yes' === $field_data[ $option_id ] );
				} elseif ( 'class' === $option_id ) {
					$field[ $option_id ] = array( $field_data[ $option_id ] );
				} else {
					$field[ $option_id ] = $field_data[ $option_id ];
				}
			}
		}
	}

	/**
	 * get_field_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_field_section( $field_id ) {
		$field_parts = explode( '_', $field_id, 2 );
		return ( ! empty( $field_parts ) && is_array( $field_parts ) ? $field_parts[0] : '' );
	}

	/**
	 * is_enabled_and_visible.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @todo    [feature] add "per products"
	 */
	function is_enabled_and_visible( $field_data ) {
		return ( 'no' === $field_data['enabled'] || ! $this->is_visible( array(
				'include_products'   => '',
				'exclude_products'   => '',
				'include_categories' => ( isset( $field_data['cats_incl'] ) ? $field_data['cats_incl'] : '' ),
				'exclude_categories' => ( isset( $field_data['cats_excl'] ) ? $field_data['cats_excl'] : '' ),
				'include_tags'       => ( isset( $field_data['tags_incl'] ) ? $field_data['tags_incl'] : '' ),
				'exclude_tags'       => ( isset( $field_data['tags_excl'] ) ? $field_data['tags_excl'] : '' ),
			) ) ? false : true );
	}

	/**
	 * customize_checkout_fields.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @todo    [dev] (maybe) fix - priority seems to not affect tab order
	 * @todo    [dev] (maybe) force enable, if was not enabled by default, i.e. `! isset( $checkout_fields[ $section ][ $field ] )`
	 */
	function customize_checkout_fields( $checkout_fields ) {
		foreach ( $this->get_fields_data() as $field_id => $field_data ) {
			$section = $this->get_field_section( $field_id );
			if ( ! isset( $checkout_fields[ $section ][ $field_id ] ) ) {
				continue;
			} elseif ( ! $this->is_enabled_and_visible( $field_data ) ) {
				unset( $checkout_fields[ $section ][ $field_id ] );
			} else {
				$this->set_field_data_options( $checkout_fields[ $section ][ $field_id ], $field_data );
			}
		}
		if ( 'yes' === get_option( 'alg_wc_core_checkout_fields_force_sort_by_priority', 'no' ) ) {
			$field_sets = array( 'billing', 'shipping', 'account', 'order' );
			foreach ( $field_sets as $field_set ) {
				if ( isset( $checkout_fields[ $field_set ] ) ) {
					uasort( $checkout_fields[ $field_set ], array( $this, 'sort_by_priority' ) );
				}
			}
		}
		return $checkout_fields;
	}

	/**
	 * is_product_term.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function is_product_term( $product_id, $term_ids, $taxonomy ) {
		if ( empty( $term_ids ) ) {
			return false;
		}
		$product_terms = get_the_terms( $product_id, $taxonomy );
		if ( empty( $product_terms ) || is_wp_error( $product_terms ) ) {
			return false;
		}
		foreach( $product_terms as $product_term ) {
			if ( in_array( $product_term->term_id, $term_ids ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * is_enabled_for_product.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @todo    [feature] require *at least one* || require *all*
	 */
	function is_enabled_for_product( $product_id, $args ) {
		if ( isset( $args['include_products'] ) && ! empty( $args['include_products'] ) ) {
			if ( ! is_array( $args['include_products'] ) ) {
				$args['include_products'] = array_map( 'trim', explode( ',', $args['include_products'] ) );
			}
			if ( ! in_array( $product_id, $args['include_products'] ) ) {
				return false;
			}
		}
		if ( isset( $args['exclude_products'] ) && ! empty( $args['exclude_products'] ) ) {
			if ( ! is_array( $args['exclude_products'] ) ) {
				$args['exclude_products'] = array_map( 'trim', explode( ',', $args['exclude_products'] ) );
			}
			if ( in_array( $product_id, $args['exclude_products'] ) ) {
				return false;
			}
		}
		if ( isset( $args['include_categories'] ) && ! empty( $args['include_categories'] ) ) {
			if ( ! $this->is_product_term( $product_id, $args['include_categories'], 'product_cat' ) ) {
				return false;
			}
		}
		if ( isset( $args['exclude_categories'] ) && ! empty( $args['exclude_categories'] ) ) {
			if ( $this->is_product_term( $product_id, $args['exclude_categories'], 'product_cat' ) ) {
				return false;
			}
		}
		if ( isset( $args['include_tags'] ) && ! empty( $args['include_tags'] ) ) {
			if ( ! $this->is_product_term( $product_id, $args['include_tags'], 'product_tag' ) ) {
				return false;
			}
		}
		if ( isset( $args['exclude_tags'] ) && ! empty( $args['exclude_tags'] ) ) {
			if ( $this->is_product_term( $product_id, $args['exclude_tags'], 'product_tag' ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * is_visible.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @todo    [dev] (maybe) add variations
	 */
	function is_visible( $args ) {
		foreach ( $args as $arg ) {
			if ( ! empty( $arg ) ) {
				// At least one arg is filled - checking products in cart
				if ( ! isset( $this->cart_product_ids ) ) {
					$this->cart_product_ids = array();
					foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
						$this->cart_product_ids[] = $values['product_id'];
					}
				}
				foreach ( $this->cart_product_ids as $product_id ) {
					if ( ! $this->is_enabled_for_product( $product_id, $args ) ) {
						return false;
					}
				}
				break;
			}
		}
		return true;
	}

	/**
	 * sort_by_priority.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function sort_by_priority( $a, $b ) {
		$a = ( isset( $a['priority'] ) ? $a['priority'] : 0 );
		$b = ( isset( $b['priority'] ) ? $b['priority'] : 0 );
		if ( $a == $b ) {
			return 0;
		}
		return ( $a < $b ? -1 : 1 );
	}

}

endif;

return new Alg_WC_Core_Checkout_Fields_Core();
