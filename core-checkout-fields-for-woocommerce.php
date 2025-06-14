<?php
/*
Plugin Name: Checkout Field Editor and Customizer for WooCommerce
Plugin URI: https://wordpress.org/plugins/core-checkout-fields-for-woocommerce/
Description: Customize the core (i.e., standard) WooCommerce checkout fields.
Version: 2.0.0
Author: Algoritmika Ltd
Author URI: https://profiles.wordpress.org/algoritmika/
Requires at least: 4.4
Text Domain: core-checkout-fields-for-woocommerce
Domain Path: /langs
WC tested up to: 9.9
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

if ( 'core-checkout-fields-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	$plugin = 'core-checkout-fields-for-woocommerce-pro/core-checkout-fields-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		(
			is_multisite() &&
			array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
		)
	) {
		defined( 'ALG_WC_CORE_CHECKOUT_FIELDS_FILE_FREE' ) || define( 'ALG_WC_CORE_CHECKOUT_FIELDS_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_CORE_CHECKOUT_FIELDS_VERSION' ) || define( 'ALG_WC_CORE_CHECKOUT_FIELDS_VERSION', '2.0.0' );

defined( 'ALG_WC_CORE_CHECKOUT_FIELDS_FILE' ) || define( 'ALG_WC_CORE_CHECKOUT_FIELDS_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-core-checkout-fields.php';

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
