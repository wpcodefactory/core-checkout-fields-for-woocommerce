<?php
/**
 * Core Checkout Fields for WooCommerce - Field Settings
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Core_Checkout_Fields_Settings_Field' ) ) :

class Alg_WC_Core_Checkout_Fields_Settings_Field extends Alg_WC_Core_Checkout_Fields_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct( $id, $title ) {
		$this->id   = $id;
		$this->desc = $title;
		parent::__construct();
	}

	/**
	 * get_terms.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_terms( $args ) {
		if ( ! is_array( $args ) ) {
			$_taxonomy = $args;
			$args = array(
				'taxonomy'   => $_taxonomy,
				'orderby'    => 'name',
				'hide_empty' => false,
			);
		}
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( $args );
		} else {
			$_taxonomy = $args['taxonomy'];
			unset( $args['taxonomy'] );
			$_terms = get_terms( $_taxonomy, $args );
		}
		$_terms_options = array();
		if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ){
			foreach ( $_terms as $_term ) {
				$_terms_options[ $_term->term_id ] = $_term->name;
			}
		}
		return $_terms_options;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_settings() {

		$product_cats = $this->get_terms( 'product_cat' );
		$product_tags = $this->get_terms( 'product_tag' );
		$field        = $this->id;

		$fields_settings = array(
			array(
				'title'    => $this->desc,
				'type'     => 'title',
				'id'       => 'alg_wc_core_checkout_field_general_options',
			),
			array(
				'title'    => __( 'Enabled', 'core-checkout-fields-for-woocommerce' ),
				'id'       => "alg_wc_core_checkout_field_enabled[{$field}]",
				'default'  => 'default',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'default' => __( 'Default', 'core-checkout-fields-for-woocommerce' ),
					'yes'     => __( 'Enabled', 'core-checkout-fields-for-woocommerce' ),
					'no'      => __( 'Disabled', 'core-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Required', 'core-checkout-fields-for-woocommerce' ),
				'id'       => "alg_wc_core_checkout_field_required[{$field}]",
				'default'  => 'default',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'default' => __( 'Default', 'core-checkout-fields-for-woocommerce' ),
					'yes'     => __( 'Required', 'core-checkout-fields-for-woocommerce' ),
					'no'      => __( 'Not required', 'core-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Label', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Leave blank for WooCommerce defaults.', 'core-checkout-fields-for-woocommerce' ),
				'id'       => "alg_wc_core_checkout_field_label[{$field}]",
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Placeholder', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Leave blank for WooCommerce defaults.', 'core-checkout-fields-for-woocommerce' ),
				'id'       => "alg_wc_core_checkout_field_placeholder[{$field}]",
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Description', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Leave blank for WooCommerce defaults.', 'core-checkout-fields-for-woocommerce' ),
				'id'       => "alg_wc_core_checkout_field_description[{$field}]",
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Class', 'core-checkout-fields-for-woocommerce' ),
				'id'       => "alg_wc_core_checkout_field_class[{$field}]",
				'default'  => 'default',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'default'        => __( 'Default', 'core-checkout-fields-for-woocommerce' ),
					'form-row-first' => __( 'Align left', 'core-checkout-fields-for-woocommerce' ),
					'form-row-last'  => __( 'Align right', 'core-checkout-fields-for-woocommerce' ),
					'form-row-full'  => __( 'Full row', 'core-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Position (i.e. priority)', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Leave zero for WooCommerce defaults.', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_core_checkout_fields_settings', sprintf(
					'<br>' . __( 'You will need %s plugin to set priority.', 'core-checkout-fields-for-woocommerce' ),
					'<a target="_blank" href="https://wpfactory.com/item/core-checkout-fields-for-woocommerce/">' .
						__( 'Core Checkout Fields for WooCommerce Pro', 'core-checkout-fields-for-woocommerce' ) . '</a>' ) ),
				'id'       => "alg_wc_core_checkout_field_priority[{$field}]",
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => apply_filters( 'alg_wc_core_checkout_fields_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_core_checkout_field_general_options',
			),
			array(
				'title'    => __( 'Product Visibility', 'core-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_core_checkout_field_product_visibility_options',
			),
			array(
				'title'    => __( 'Include product categories', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'If not empty - selected categories products must be in the cart for current field to appear.', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_core_checkout_fields_settings', sprintf(
					'<br>' . __( 'You will need %s plugin to set included categories.', 'core-checkout-fields-for-woocommerce' ),
					'<a target="_blank" href="https://wpfactory.com/item/core-checkout-fields-for-woocommerce/">' .
						__( 'Core Checkout Fields for WooCommerce Pro', 'core-checkout-fields-for-woocommerce' ) . '</a>' ) ),
				'id'       => "alg_wc_core_checkout_field_cats_incl[{$field}]",
				'default'  => '',
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $product_cats,
				'custom_attributes' => apply_filters( 'alg_wc_core_checkout_fields_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Exclude product categories', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'If not empty - current field is hidden, if selected categories products are in the cart.', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_core_checkout_fields_settings', sprintf(
					'<br>' . __( 'You will need %s plugin to set excluded categories.', 'core-checkout-fields-for-woocommerce' ),
					'<a target="_blank" href="https://wpfactory.com/item/core-checkout-fields-for-woocommerce/">' .
						__( 'Core Checkout Fields for WooCommerce Pro', 'core-checkout-fields-for-woocommerce' ) . '</a>' ) ),
				'id'       => "alg_wc_core_checkout_field_cats_excl[{$field}]",
				'default'  => '',
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $product_cats,
				'custom_attributes' => apply_filters( 'alg_wc_core_checkout_fields_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Include product tags', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'If not empty - selected tags products must be in the cart for current field to appear.', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_core_checkout_fields_settings', sprintf(
					'<br>' . __( 'You will need %s plugin to set included tags.', 'core-checkout-fields-for-woocommerce' ),
					'<a target="_blank" href="https://wpfactory.com/item/core-checkout-fields-for-woocommerce/">' .
						__( 'Core Checkout Fields for WooCommerce Pro', 'core-checkout-fields-for-woocommerce' ) . '</a>' ) ),
				'id'       => "alg_wc_core_checkout_field_tags_incl[{$field}]",
				'default'  => '',
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $product_tags,
				'custom_attributes' => apply_filters( 'alg_wc_core_checkout_fields_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Exclude product tags', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'If not empty - current field is hidden, if selected tags products are in the cart.', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_core_checkout_fields_settings', sprintf(
					'<br>' . __( 'You will need %s plugin to set excluded tags.', 'core-checkout-fields-for-woocommerce' ),
					'<a target="_blank" href="https://wpfactory.com/item/core-checkout-fields-for-woocommerce/">' .
						__( 'Core Checkout Fields for WooCommerce Pro', 'core-checkout-fields-for-woocommerce' ) . '</a>' ) ),
				'id'       => "alg_wc_core_checkout_field_tags_excl[{$field}]",
				'default'  => '',
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $product_tags,
				'custom_attributes' => apply_filters( 'alg_wc_core_checkout_fields_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_core_checkout_field_product_visibility_options',
			),
		);

		return array_merge( $fields_settings );
	}

}

endif;
