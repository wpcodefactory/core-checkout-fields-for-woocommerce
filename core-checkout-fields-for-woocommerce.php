<?php
/*
Plugin Name: Core Checkout Fields for WooCommerce
Plugin URI: https://wpfactory.com/item/core-checkout-fields-for-woocommerce/
Description: Customize core (i.e. standard) WooCommerce checkout fields.
Version: 1.2.0-dev
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: core-checkout-fields-for-woocommerce
Domain Path: /langs
WC tested up to: 7.1
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Core_Checkout_Fields' ) ) :

/**
 * Main Alg_WC_Core_Checkout_Fields Class
 *
 * @class   Alg_WC_Core_Checkout_Fields
 * @version 1.1.0
 * @since   1.0.0
 */
final class Alg_WC_Core_Checkout_Fields {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.2.0-dev-20221111-2227';

	/**
	 * @var   Alg_WC_Core_Checkout_Fields The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Core_Checkout_Fields Instance
	 *
	 * Ensures only one instance of Alg_WC_Core_Checkout_Fields is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Core_Checkout_Fields - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Core_Checkout_Fields Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Check for active plugins
		if (
			! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ||
			( 'core-checkout-fields-for-woocommerce.php' === basename( __FILE__ ) && $this->is_plugin_active( 'core-checkout-fields-for-woocommerce-pro/core-checkout-fields-for-woocommerce-pro.php' ) )
		) {
			return;
		}

		// Set up localisation
		load_plugin_textdomain( 'core-checkout-fields-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Pro
		if ( 'core-checkout-fields-for-woocommerce-pro.php' === basename( __FILE__ ) ) {
			require_once( 'includes/pro/class-alg-wc-core-checkout-fields-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * is_plugin_active.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', ( array ) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, ( array ) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once( 'includes/class-alg-wc-core-checkout-fields-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		require_once( 'includes/settings/class-alg-wc-core-checkout-fields-settings-section.php' );
		require_once( 'includes/settings/class-alg-wc-core-checkout-fields-settings-field.php' );
		$this->settings = array();
		$this->settings['general'] = require_once( 'includes/settings/class-alg-wc-core-checkout-fields-settings-general.php' );
		foreach ( $this->core->get_fields() as $field_id => $field_title ) {
			$this->settings[ $field_id ] = new Alg_WC_Core_Checkout_Fields_Settings_Field( $field_id, $field_title );
		}
		// Version update
		if ( get_option( 'alg_wc_core_checkout_fields_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_core_checkout_fields' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'core-checkout-fields-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a target="_blank" href="https://wpfactory.com/item/core-checkout-fields-for-woocommerce/">' .
				__( 'Unlock All', 'core-checkout-fields-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Core Checkout Fields settings tab to WooCommerce settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-alg-wc-settings-core-checkout-fields.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( 'alg_wc_core_checkout_fields_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_core_checkout_fields' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Core_Checkout_Fields to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Core_Checkout_Fields
	 * @todo    [dev] `plugins_loaded`
	 */
	function alg_wc_core_checkout_fields() {
		return Alg_WC_Core_Checkout_Fields::instance();
	}
}

alg_wc_core_checkout_fields();
