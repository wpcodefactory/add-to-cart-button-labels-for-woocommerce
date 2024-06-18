<?php
/**
 * Add to Cart Button Labels for WooCommerce - Handler Class
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Handler' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * id.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $id;

	/**
	 * desc.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $desc;

	/**
	 * title.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $title;

	/**
	 * options.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $options;

	/**
	 * labels.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $labels;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		if ( $this->is_enabled() ) {
			add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'button_text_single' ),  PHP_INT_MAX );
			add_filter( 'woocommerce_product_add_to_cart_text',        array( $this, 'button_text_archive' ), PHP_INT_MAX );
		}
	}

	/**
	 * is_enabled.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function is_enabled() {
		return ( 'yes' === get_option( 'alg_wc_add_to_cart_button_labels_' . $this->id . '_enabled', 'no' ) );
	}

	/**
	 * button_text_single.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function button_text_single( $text ) {
		return do_shortcode( $this->button_text( $text, 'single' ) );
	}

	/**
	 * button_text_archive.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function button_text_archive( $text ) {
		return do_shortcode( $this->button_text( $text, ( 'per_product_type' === $this->id ? 'archives' : 'archive' ) ) );
	}

	/**
	 * button_text.
	 *
	 * This should be overridden in child classes.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function button_text( $text, $single_or_archive ) {
		return $text;
	}

	/**
	 * is_wc_version_below_3.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function is_wc_version_below_3() {
		return version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );
	}

	/**
	 * get_product_or_variation_parent_id.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 *
	 * @todo    (dev) just product id (i.e., no parent for variation)
	 */
	function get_product_or_variation_parent_id( $product ) {
		return ( $this->is_wc_version_below_3() ? $product->id : ( $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id() ) );
	}

	/**
	 * do_array_intersect.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function do_array_intersect( $a1, $a2 ) {
		$intersect = array_intersect( $a1, $a2);
		return ( ! empty( $intersect ) );
	}

}

endif;
