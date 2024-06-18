<?php
/**
 * Add to Cart Button Labels for WooCommerce - Section Settings
 *
 * @version 2.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Settings_Section' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Settings_Section {

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
	 * section.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $section;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct( $section = false ) {
		if ( $section ) {
			$this->section = $section;
			$this->id      = $section->id;
			$this->desc    = $section->title;
		}
		add_filter( 'woocommerce_get_sections_alg_wc_add_to_cart_button_labels',                   array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_wc_add_to_cart_button_labels' . '_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings() {
		if ( isset( $this->section ) ) {
			return array_merge(
				$this->section->get_settings(),
				array(
					array(
						'title'    => __( 'Shortcodes', 'add-to-cart-button-labels-for-woocommerce' ),
						'type'     => 'title',
						'id'       => 'alg_wc_add_to_cart_button_labels_shortcodes',
						'desc'     => sprintf( __( 'You can use shortcodes in all labels: %s', 'add-to-cart-button-labels-for-woocommerce' ),
							'<pre>' . '[alg_wc_atcbl_' . implode( '], [alg_wc_atcbl_', alg_wc_atcbl()->shortcodes->shortcodes ) . ']' . '</pre>' ),
					),
					array(
						'type'     => 'sectionend',
						'id'       => 'alg_wc_add_to_cart_button_labels_shortcodes',
					),
				)
			);
		}
	}

}

endif;
