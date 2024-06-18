<?php
/**
 * Add to Cart Button Labels for WooCommerce - All Products Class
 *
 * @version 2.1.0
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_All_Products' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_All_Products extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function __construct() {
		$this->id    = 'all_products';
		$this->title = __( 'All Products', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text for all products at once.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function button_text( $text, $single_or_archive ) {
		return ( '' != ( $label = get_option( 'alg_wc_add_to_cart_button_labels_all_products_' . $single_or_archive, '' ) ) ?
			$label : $text );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'All Products Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc,
				'id'       => 'alg_wc_add_to_cart_button_labels_all_products_options',
			),
			array(
				'title'    => __( 'All products labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_add_to_cart_button_labels_all_products_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if empty.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_add_to_cart_button_labels_all_products_single',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:100%;',
				'alg_wc_atcbl_sanitize' => 'textarea',
			),
			array(
				'title'    => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if empty.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_add_to_cart_button_labels_all_products_archive',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:100%;',
				'alg_wc_atcbl_sanitize' => 'textarea',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_add_to_cart_button_labels_all_products_options',
			),
		);
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_All_Products();
