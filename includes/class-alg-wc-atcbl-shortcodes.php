<?php
/**
 * Add to Cart Button Labels for WooCommerce - Shortcodes Class
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Shortcodes' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Shortcodes {

	/**
	 * shortcodes.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $shortcodes;

	/**
	 * current_user.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $current_user;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (feature) more shortcodes, e.g., product category/tag (current), product categories/tags, etc.
	 * @todo    (feature) common atts: `before`, `after`, etc.
	 * @todo    (feature) optional `product_id` attribute in all `product_...` shortcodes
	 */
	function __construct() {

		$this->shortcodes = array(
			'user_name',
			'product_title',
			'product_price',
			'product_stock',
			'product_meta',
			'product_func',
			'translate',
		);

		foreach ( $this->shortcodes as $shortcode ) {
			add_shortcode( 'alg_wc_atcbl_' . $shortcode, array( $this, $shortcode ) );
		}

	}

	/**
	 * product_meta.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (feature) add `use_parent_product` attribute?
	 */
	function product_meta( $atts, $content = '' ) {
		global $product;
		return ( $product && isset( $atts['key'] ) ?
			get_post_meta( $product->get_id(), wc_clean( $atts['key'] ), true ) :
			''
		);
	}

	/**
	 * product_func.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_func( $atts, $content = '' ) {
		global $product;
		return (
			$product &&
			isset( $atts['name'] ) &&
			( $func = wc_clean( $atts['name'] ) ) &&
			is_callable( array( $product, $func ) ) ?
				$product->$func() :
				''
		);
	}

	/**
	 * product_price.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (dev) `$product->get_price()`?
	 * @todo    (dev) `strip_tags( $product->get_price_html() )`?
	 */
	function product_price( $atts, $content = '' ) {
		global $product;
		return ( $product ? strip_tags( wc_price( $product->get_price() ) ) : '' );
	}

	/**
	 * product_stock.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_stock( $atts, $content = '' ) {
		global $product;
		return ( $product ? $product->get_stock_quantity() : '' );
	}

	/**
	 * product_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_title( $atts, $content = '' ) {
		global $product;
		return ( $product ? $product->get_title() : '' );
	}

	/**
	 * user_name.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (dev) customizable "Guest"
	 */
	function user_name( $atts, $content = '' ) {
		if ( ! isset( $this->current_user ) ) {
			$this->current_user = wp_get_current_user();
		}
		return ( $this->current_user ?
			$this->current_user->user_nicename :
			__( 'Guest', 'add-to-cart-button-labels-for-woocommerce' )
		);
	}

	/**
	 * translate.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function translate( $atts, $content = '' ) {

		// E.g.: `[alg_wc_atcbl_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}

		// E.g.: `[alg_wc_atcbl_translate lang="EN,DE"]Text for EN & DE[/alg_wc_atcbl_translate][alg_wc_atcbl_translate not_lang="EN,DE"]Text for other languages[/alg_wc_atcbl_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;

	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Shortcodes();
