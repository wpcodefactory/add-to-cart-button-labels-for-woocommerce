<?php
/**
 * Add to Cart Button Labels for WooCommerce - Section Settings
 *
 * @version 2.3.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL_Settings_Section' ) ) :

	/**
	 * Alg_WC_ATCBL_Settings_Section class.
	 *
	 * @version 2.3.0
	 * @since   2.1.0
	 */
	class Alg_WC_ATCBL_Settings_Section {

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
		 * Section.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var object
		 */
		public $section;

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param object|false $section Section object or false.
		 */
		public function __construct( $section = false ) {
			if ( $section ) {
				$this->section = $section;
				$this->id      = $section->id;
				$this->desc    = $section->title;
			}
			add_filter(
				'woocommerce_get_sections_alg_wc_add_to_cart_button_labels',
				array( $this, 'settings_section' )
			);
			add_filter(
				'woocommerce_get_settings_alg_wc_add_to_cart_button_labels_' . $this->id,
				array( $this, 'get_settings' ),
				PHP_INT_MAX
			);
		}

		/**
		 * Settings section.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param array $sections Array of WooCommerce settings sections.
		 *
		 * @return array Modified array of WooCommerce settings sections.
		 */
		public function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;
			return $sections;
		}

		/**
		 * Get settings.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_settings() {
			if ( isset( $this->section ) ) {
				return array_merge(
					$this->section->get_settings(),
					array(
						array(
							'title' => __( 'Shortcodes', 'add-to-cart-button-labels-for-woocommerce' ),
							'type'  => 'title',
							'id'    => 'alg_wc_add_to_cart_button_labels_shortcodes',
							'desc'  => sprintf(
								/* Translators: %s: Shortcode list. */
								__( 'You can use shortcodes in all labels: %s', 'add-to-cart-button-labels-for-woocommerce' ),
								'<pre>' .
									'[alg_wc_atcbl_' . implode( '], [alg_wc_atcbl_', alg_wc_atcbl()->shortcodes->shortcodes ) . ']' .
								'</pre>'
							),
						),
						array(
							'type' => 'sectionend',
							'id'   => 'alg_wc_add_to_cart_button_labels_shortcodes',
						),
					)
				);
			}
		}
	}

endif;
