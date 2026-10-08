<?php
/**
 * Plugin Name: Change Add to Cart Button Text for WooCommerce
 * Plugin URI: https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/
 * Description: Customize "Add to cart" button labels in WooCommerce. Beautifully.
 * Version: 2.3.0
 * Author: WPFactory
 * Author URI: https://wpfactory.com
 * Requires PHP: 7.4
 * Text Domain: add-to-cart-button-labels-for-woocommerce
 * Domain Path: /langs
 * WC tested up to: 11.1
 * Requires Plugins: woocommerce
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels
 */

defined( 'ABSPATH' ) || exit;

if ( 'add-to-cart-button-labels-for-woocommerce.php' === basename( __FILE__ ) ) {
	if ( ! function_exists( 'alg_wc_atcbl_is_pro_activated' ) ) {
		/**
		 * Check if Pro plugin version is activated.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 */
		function alg_wc_atcbl_is_pro_activated() {
			$plugin = 'add-to-cart-button-labels-for-woocommerce-pro/add-to-cart-button-labels-for-woocommerce-pro.php';
			return (
				in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
				(
					is_multisite() &&
					array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
				)
			);
		}
	}

	if ( alg_wc_atcbl_is_pro_activated() ) {
		defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE_FREE' ) || define( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE_FREE', __FILE__ );
		return;
	}
}

/**
 * Plugin version.
 */
defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_VERSION' ) || define( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_VERSION', '2.3.0' );

/**
 * Plugin file.
 */
defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE' ) || define( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE', __FILE__ );

/**
 * Include main plugin class.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-atcbl.php';

if ( ! function_exists( 'alg_wc_atcbl' ) ) {
	/**
	 * Returns the main instance of Alg_WC_ATCBL to prevent the need to use globals.
	 *
	 * @version 2.3.0
	 * @since   1.0.0
	 */
	function alg_wc_atcbl() {
		return Alg_WC_ATCBL::instance();
	}
}

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', 'alg_wc_atcbl' );
