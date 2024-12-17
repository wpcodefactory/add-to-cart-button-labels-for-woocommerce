<?php
/**
 * Add to Cart Button Labels for WooCommerce - Meta Boxes Settings
 *
 * @version 2.2.0
 * @since   1.3.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

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
	 * @version 2.2.0
	 * @since   1.0.0
	 */
	function save_custom_add_to_cart_meta_box( $post_id, $post ) {

		// Check that we are saving with custom add to cart metabox displayed
		if (
			! isset( $_POST['alg_wc_custom_add_to_cart_save_post'] ) ||            // phpcs:ignore WordPress.Security.NonceVerification.Missing
			! isset( $_POST[ 'alg_wc_add_to_cart_button_labels_' . 'single' ] ) || // phpcs:ignore WordPress.Security.NonceVerification.Missing
			! isset( $_POST[ 'alg_wc_add_to_cart_button_labels_' . 'archive' ] )   // phpcs:ignore WordPress.Security.NonceVerification.Missing
		) {
			return;
		}

		$single  = sanitize_text_field( wp_unslash( $_POST[ 'alg_wc_add_to_cart_button_labels_' . 'single' ] ) );  // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$archive = sanitize_text_field( wp_unslash( $_POST[ 'alg_wc_add_to_cart_button_labels_' . 'archive' ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta(
			$post_id,
			'_' . 'alg_wc_add_to_cart_button_labels_' . 'single',
			wp_kses_post( trim( $single ) )
		);
		update_post_meta(
			$post_id,
			'_' . 'alg_wc_add_to_cart_button_labels_' . 'archive',
			wp_kses_post( trim( $archive ) )
		);

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
	 * @version 2.2.0
	 * @since   1.0.0
	 */
	function create_custom_add_to_cart_meta_box() {

		$current_post_id = get_the_ID();
		$options = array(
			'single'  => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
			'archive' => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
		);

		?><table class="widefat striped"><?php
		foreach ( $options as $option_key => $option_desc ) {

			$option_id    = 'alg_wc_add_to_cart_button_labels_' . $option_key;
			$option_value = get_post_meta( $current_post_id, '_' . $option_id, true );

			?><tr>
				<th style="width:20%;"><?php echo esc_html( $option_desc ); ?></th>
				<td style="width:80%;">
					<input
						style="width:100%;"
						type="text"
						id="<?php echo esc_attr( $option_id ); ?>"
						name="<?php echo esc_attr( $option_id ); ?>"
						value="<?php echo esc_attr( $option_value ); ?>"
					>
				</td>
			</tr><?php

		}
		?></table>
		<input type="hidden" name="alg_wc_custom_add_to_cart_save_post" value="alg_wc_custom_add_to_cart_save_post"><?php

	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Settings_Meta_Boxes();
