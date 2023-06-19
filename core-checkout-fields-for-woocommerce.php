<?php
/*
Plugin Name: Core Checkout Fields for WooCommerce
Plugin URI: https://wpfactory.com/item/core-checkout-fields-for-woocommerce/
Description: Customize the core (i.e. standard) WooCommerce checkout fields.
Version: 1.2.1
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: core-checkout-fields-for-woocommerce
Domain Path: /langs
WC tested up to: 7.8
*/

defined( 'ABSPATH' ) || exit;

if ( 'core-checkout-fields-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	$plugin = 'core-checkout-fields-for-woocommerce-pro/core-checkout-fields-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

defined( 'ALG_WC_CORE_CHECKOUT_FIELDS_VERSION' ) || define( 'ALG_WC_CORE_CHECKOUT_FIELDS_VERSION', '1.2.1' );

defined( 'ALG_WC_CORE_CHECKOUT_FIELDS_FILE' ) || define( 'ALG_WC_CORE_CHECKOUT_FIELDS_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-core-checkout-fields.php' );

if ( ! function_exists( 'alg_wc_core_checkout_fields' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Core_Checkout_Fields to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_core_checkout_fields() {
		return Alg_WC_Core_Checkout_Fields::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_core_checkout_fields' );
