<?php
/**
 * Add to Cart Button Labels for WooCommerce - Settings
 *
 * @version 2.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_Add_To_Cart_Button_Labels' ) ) :

class Alg_WC_Settings_Add_To_Cart_Button_Labels extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {

		$this->id    = 'alg_wc_add_to_cart_button_labels';
		$this->label = __( 'Add to Cart Button Labels', 'add-to-cart-button-labels-for-woocommerce' );

		parent::__construct();

		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'alg_wc_atcbl_sanitize' ), PHP_INT_MAX, 3 );

		// Sections
		require_once( 'class-alg-wc-atcbl-settings-section.php' );
		require_once( 'class-alg-wc-atcbl-settings-general.php' );
		foreach ( alg_wc_atcbl()->sections as $section ) {
			$sections[] = new Alg_WC_Add_To_Cart_Button_Labels_Settings_Section( $section );
		}

	}

	/**
	 * alg_wc_atcbl_sanitize.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function alg_wc_atcbl_sanitize( $value, $option, $raw_value ) {
		if ( ! empty( $option['alg_wc_atcbl_sanitize'] ) ) {
			switch ( $option['alg_wc_atcbl_sanitize'] ) {
				case 'textarea':
					return wp_kses_post( trim( $raw_value ) );
				default:
					$func = $option['alg_wc_atcbl_sanitize'];
					return ( function_exists( $func ) ? $func( $raw_value ) : $value );
			}
		}
		return $value;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.2.1
	 * @since   1.0.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.2.1
	 * @since   1.2.1
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Settings_Add_To_Cart_Button_Labels();
