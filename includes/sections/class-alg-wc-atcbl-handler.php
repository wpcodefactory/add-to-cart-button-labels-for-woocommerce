<?php
/**
 * Add to Cart Button Labels for WooCommerce - Handler Class
 *
 * @version 2.3.0
 * @since   2.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels\Sections
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL_Handler' ) ) :

	/**
	 * Alg_WC_ATCBL_Handler class.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	class Alg_WC_ATCBL_Handler {

		/**
		 * ID.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var string
		 */
		public $id;

		/**
		 * Description.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var string
		 */
		public $desc;

		/**
		 * Title.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var string
		 */
		public $title;

		/**
		 * Options.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var array
		 */
		public $options;

		/**
		 * Labels.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var array
		 */
		public $labels;

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function __construct() {
			if ( $this->is_enabled() ) {
				add_filter(
					'woocommerce_product_single_add_to_cart_text',
					array( $this, 'button_text_single' ),
					PHP_INT_MAX
				);
				add_filter(
					'woocommerce_product_add_to_cart_text',
					array( $this, 'button_text_archive' ),
					PHP_INT_MAX
				);
			}
		}

		/**
		 * Is enabled.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function is_enabled() {
			return ( 'yes' === get_option( 'alg_wc_add_to_cart_button_labels_' . $this->id . '_enabled', 'no' ) );
		}

		/**
		 * Button text single.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @param string $text The current button text.
		 */
		public function button_text_single( $text ) {
			return do_shortcode(
				$this->button_text(
					$text,
					'single'
				)
			);
		}

		/**
		 * Button text archive.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @param string $text The current button text.
		 */
		public function button_text_archive( $text ) {
			return do_shortcode(
				$this->button_text(
					$text,
					( 'per_product_type' === $this->id ? 'archives' : 'archive' )
				)
			);
		}

		/**
		 * Button text.
		 *
		 * This should be overridden in child classes.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @param string $text              The current button text.
		 * @param string $single_or_archive Whether the button is on a single product page or an archive page.
		 *
		 * @return string The modified button text.
		 */
		public function button_text( $text, $single_or_archive ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
			return $text;
		}

		/**
		 * Is WC version below 3.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function is_wc_version_below_3() {
			return version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );
		}

		/**
		 * Get product or variation parent ID.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 *
		 * @param WC_Product $product The product object.
		 *
		 * @return int
		 *
		 * @todo (dev) Just product ID (i.e., no parent for variation).
		 */
		public function get_product_or_variation_parent_id( $product ) {
			return (
				$this->is_wc_version_below_3() ?
				$product->id :
				(
					$product->is_type( 'variation' ) ?
					$product->get_parent_id() :
					$product->get_id()
				)
			);
		}

		/**
		 * Do array intersect.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @param array $a1 The first array.
		 * @param array $a2 The second array.
		 *
		 * @return bool True if there is an intersection, false otherwise.
		 */
		public function do_array_intersect( $a1, $a2 ) {
			$intersect = array_intersect( $a1, $a2 );
			return ( ! empty( $intersect ) );
		}
	}

endif;
