<?php
/*
Plugin Name: Customize Add to Cart Button Text for WooCommerce
Plugin URI: https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/
Description: Customize "Add to cart" button labels in WooCommerce. Beautifully.
Version: 2.1.1
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: add-to-cart-button-labels-for-woocommerce
Domain Path: /langs
WC tested up to: 9.1
Requires Plugins: woocommerce
*/

defined( 'ABSPATH' ) || exit;

if ( 'add-to-cart-button-labels-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 */
	$plugin = 'add-to-cart-button-labels-for-woocommerce-pro/add-to-cart-button-labels-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE_FREE' ) || define( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_VERSION' ) || define( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_VERSION', '2.1.1' );

defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE' ) || define( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-atcbl.php' );

if ( ! function_exists( 'alg_wc_atcbl' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Add_To_Cart_Button_Labels to prevent the need to use globals.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function alg_wc_atcbl() {
		return Alg_WC_Add_To_Cart_Button_Labels::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_atcbl' );
