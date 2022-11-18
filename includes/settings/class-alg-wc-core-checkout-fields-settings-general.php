<?php
/**
 * Core Checkout Fields for WooCommerce - Settings - General Section
 *
 * @version 1.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Core_Checkout_Fields_Settings_General' ) ) :

class Alg_WC_Core_Checkout_Fields_Settings_General extends Alg_WC_Core_Checkout_Fields_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'core-checkout-fields-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_settings() {

		$plugin_settings = array(
			array(
				'title'    => __( 'Core Checkout Fields Options', 'core-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_core_checkout_fields_plugin_options',
			),
			array(
				'title'    => __( 'Core WooCommerce Checkout Fields', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'core-checkout-fields-for-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'Customize WooCommerce core checkout fields.', 'core-checkout-fields-for-woocommerce' ),
				'id'       => 'alg_wc_core_checkout_fields_plugin_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_core_checkout_fields_plugin_options',
			),
		);

		$general_settings = array(
			array(
				'title'    => __( 'General Options', 'core-checkout-fields-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_core_checkout_fields_general_options',
			),
			array(
				'title'    => __( 'Override default address fields', 'core-checkout-fields-for-woocommerce' ),
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'id'       => 'alg_wc_core_checkout_fields_override_default_address_fields',
				'default'  => 'billing',
				'options'  => array(
					'billing'  => __( 'Override with billing fields', 'core-checkout-fields-for-woocommerce' ),
					'shipping' => __( 'Override with shipping fields', 'core-checkout-fields-for-woocommerce' ),
					'disable'  => __( 'Do not override', 'core-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Override country locale fields', 'core-checkout-fields-for-woocommerce' ),
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'id'       => 'alg_wc_core_checkout_fields_override_country_locale_fields',
				'default'  => 'billing',
				'options'  => array(
					'billing'  => __( 'Override with billing fields', 'core-checkout-fields-for-woocommerce' ),
					'shipping' => __( 'Override with shipping fields', 'core-checkout-fields-for-woocommerce' ),
					'disable'  => __( 'Do not override', 'core-checkout-fields-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Force fields sort by priority', 'core-checkout-fields-for-woocommerce' ),
				'desc'     => __( 'Enable', 'core-checkout-fields-for-woocommerce' ),
				'desc_tip' => __( 'Enable this if you are having theme related issues with "Position (i.e. priority)" options.', 'core-checkout-fields-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_core_checkout_fields_force_sort_by_priority',
				'default'  => 'no',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_core_checkout_fields_general_options',
			),
		);

		return array_merge( $plugin_settings, $general_settings );
	}

}

endif;

return new Alg_WC_Core_Checkout_Fields_Settings_General();
