<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per Product Class
 *
 * @version 2.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Per_Product' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Per_Product extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'per_product';
		$this->title = __( 'Per Product', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text on per individual product basis.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
		if ( is_admin() && $this->is_enabled() ) {
			require_once( untrailingslashit( plugin_dir_path( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) ) .
				'/includes/settings/class-alg-wc-atcbl-settings-meta-boxes.php' );
		}
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function button_text( $text, $single_or_archive ) {
		global $product;
		if ( ! $product ) {
			return $text;
		}
		$label = get_post_meta( $this->get_product_or_variation_parent_id( $product ),
			'_' . 'alg_wc_add_to_cart_button_labels_' . $single_or_archive, true );
		return ( '' != $label ? $label : $text );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'    => __( 'Per Product Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc . ' ' .
					__( 'When enabled, label for each product can be set on "Edit product" page.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_options',
			),
			array(
				'title'    => __( 'Per product labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Per_Product();
