<?php
/**
 * Add to Cart Button Labels for WooCommerce - Meta Boxes Settings
 *
 * @version 2.3.0
 * @since   1.3.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL_Settings_Meta_Boxes' ) ) :

	/**
	 * Alg_WC_ATCBL_Settings_Meta_Boxes class.
	 *
	 * @version 2.3.0
	 * @since   1.3.0
	 */
	class Alg_WC_ATCBL_Settings_Meta_Boxes {

		/**
		 * Constructor.
		 *
		 * @version 2.3.0
		 * @since   1.3.0
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_custom_add_to_cart_meta_box' ) );
			add_action( 'save_post_product', array( $this, 'save_custom_add_to_cart_meta_box' ), PHP_INT_MAX );
		}

		/**
		 * Save custom add to cart meta box.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 *
		 * @param int $post_id The ID of the post being saved.
		 */
		public function save_custom_add_to_cart_meta_box( $post_id ) {
			// Check nonce.
			if (
				! isset( $_POST['alg_wc_atcbl_save_product_nonce'] ) ||
				! wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_POST['alg_wc_atcbl_save_product_nonce'] ) ),
					'alg_wc_atcbl_save_product'
				)
			) {
				return;
			}

			// Check user capabilities.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if this is an autosave or a revision.
			if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
				return;
			}

			// Check that we are saving with custom add to cart metabox displayed.
			if (
				! isset(
					$_POST['alg_wc_custom_add_to_cart_save_post'],
					$_POST['alg_wc_add_to_cart_button_labels_single'],
					$_POST['alg_wc_add_to_cart_button_labels_archive']
				)
			) {
				return;
			}

			// Sanitize input values.
			$single  = sanitize_text_field( wp_unslash( $_POST['alg_wc_add_to_cart_button_labels_single'] ) );
			$archive = sanitize_text_field( wp_unslash( $_POST['alg_wc_add_to_cart_button_labels_archive'] ) );

			// Update post meta with sanitized values.
			update_post_meta(
				$post_id,
				'_alg_wc_add_to_cart_button_labels_single',
				wp_kses_post( trim( $single ) )
			);
			update_post_meta(
				$post_id,
				'_alg_wc_add_to_cart_button_labels_archive',
				wp_kses_post( trim( $archive ) )
			);
		}

		/**
		 * Add custom add to cart meta box.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_custom_add_to_cart_meta_box() {
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
		 * Create custom add to cart meta box.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 */
		public function create_custom_add_to_cart_meta_box() {
			$current_post_id = get_the_ID();
			$options         = array(
				'single'  => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
				'archive' => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
			);

			?>
			<table class="widefat striped">
			<?php
			foreach ( $options as $option_key => $option_desc ) {
				$option_id    = 'alg_wc_add_to_cart_button_labels_' . $option_key;
				$option_value = get_post_meta( $current_post_id, '_' . $option_id, true );

				?>
				<tr>
					<th style="width:20%;"><?php echo esc_html( $option_desc ); ?></th>
					<td style="width:80%;">
						<input
							style="width:100%;"
							type="text"
							id="<?php echo esc_attr( $option_id ); ?>"
							name="<?php echo esc_attr( $option_id ); ?>"
							value="<?php echo esc_attr( $option_value ); ?>"
						/>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
			<input type="hidden" name="alg_wc_custom_add_to_cart_save_post" value="alg_wc_custom_add_to_cart_save_post" />
			<?php
			wp_nonce_field(
				'alg_wc_atcbl_save_product',
				'alg_wc_atcbl_save_product_nonce'
			);
		}
	}

endif;

return new Alg_WC_ATCBL_Settings_Meta_Boxes();
