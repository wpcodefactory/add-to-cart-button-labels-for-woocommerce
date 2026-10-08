<?php
/**
 * Add to Cart Button Labels for WooCommerce - General Section Settings
 *
 * @version 2.3.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL_Settings_General' ) ) :

	/**
	 * Alg_WC_ATCBL_Settings_General class.
	 *
	 * @version 2.3.0
	 * @since   1.0.0
	 */
	class Alg_WC_ATCBL_Settings_General extends Alg_WC_ATCBL_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id   = '';
			$this->desc = __( 'General', 'add-to-cart-button-labels-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 *
		 * @todo (desc) `'<small>' . $section->desc . '</small>'`?
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Add to Cart Button Labels Options', 'add-to-cart-button-labels-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_add_to_cart_button_labels_options',
				),
				array(
					'title'   => __( 'Add to Cart Button Labels', 'add-to-cart-button-labels-for-woocommerce' ),
					'desc'    => '<strong>' . __( 'Enable plugin', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
					'id'      => 'alg_wc_add_to_cart_button_labels_enabled',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
			);

			foreach ( alg_wc_atcbl()->sections as $i => $section ) {
				$settings = array_merge(
					$settings,
					array(
						array(
							'title'         => (
								0 === (int) $i ?
								__( 'Sections', 'add-to-cart-button-labels-for-woocommerce' ) :
								''
							),
							'desc'          => '<a href="' . add_query_arg( 'section', $section->id ) . '">' .
								$section->title .
							'</a>',
							'id'            => 'alg_wc_add_to_cart_button_labels_' . $section->id . '_enabled',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => (
								0 === (int) $i ?
								'start' :
								(
									( count( alg_wc_atcbl()->sections ) - 1 ) === (int) $i ?
									'end' :
									''
								)
							),
						),
					)
				);
			}

			$settings = array_merge(
				$settings,
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'alg_wc_add_to_cart_button_labels_options',
					),
				)
			);

			return $settings;
		}
	}

endif;

return new Alg_WC_ATCBL_Settings_General();
