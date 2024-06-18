<?php
/**
 * Add to Cart Button Labels for WooCommerce - Meta Boxes Settings
 *
 * @version 2.1.0
 * @since   1.3.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Settings_Meta_Boxes' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Settings_Meta_Boxes {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.3.0
	 */
	function __construct() {
		add_action( 'add_meta_boxes',    array( $this, 'add_custom_add_to_cart_meta_box' ) );
		add_action( 'save_post_product', array( $this, 'save_custom_add_to_cart_meta_box' ), PHP_INT_MAX, 2 );
	}

	/**
	 * save_custom_add_to_cart_meta_box.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function save_custom_add_to_cart_meta_box( $post_id, $post ) {
		// Check that we are saving with custom add to cart metabox displayed
		if ( ! isset( $_POST['alg_wc_custom_add_to_cart_save_post'] ) ) {
			return;
		}
		update_post_meta( $post_id, '_' . 'alg_wc_add_to_cart_button_labels_' . 'single',
			wp_kses_post( trim( $_POST[ 'alg_wc_add_to_cart_button_labels_' . 'single' ] ) ) );
		update_post_meta( $post_id, '_' . 'alg_wc_add_to_cart_button_labels_' . 'archive',
			wp_kses_post( trim( $_POST[ 'alg_wc_add_to_cart_button_labels_' . 'archive' ] ) ) );
	}

	/**
	 * add_custom_add_to_cart_meta_box.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_add_to_cart_meta_box() {
		add_meta_box(
			'alg-wc-custom-add-to-cart',
			__( 'Custom Add to Cart Button Labels', 'add-to-cart-button-labels-for-woocommerce' ),
			array( $this, 'create_custom_add_to_cart_meta_box' ),
			'product',
			'normal',
			'high'
		);
	}

	/**
	 * create_custom_add_to_cart_meta_box.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function create_custom_add_to_cart_meta_box() {
		$current_post_id = get_the_ID();
		$options = array(
			'single'  => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
			'archive' => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
		);
		$html = '<table class="widefat striped">';
		foreach ( $options as $option_key => $option_desc ) {
			$option_id    = 'alg_wc_add_to_cart_button_labels_' . $option_key;
			$option_value = get_post_meta( $current_post_id, '_' . $option_id, true );
			$html .= '<tr>';
			$html .= '<th style="width:20%;">' . $option_desc . '</th>';
			$html .= '<td style="width:80%;">';
			$html .= '<input style="width:100%;" type="text" id="' . $option_id . '" name="' . $option_id . '" value="' . $option_value . '">';
			$html .= '</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		$html .= '<input type="hidden" name="alg_wc_custom_add_to_cart_save_post" value="alg_wc_custom_add_to_cart_save_post">';
		echo $html;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Settings_Meta_Boxes();
