<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per Tag Class
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Per_Tag' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Per_Tag extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id    = 'per_tag';
		$this->title = __( 'Per Product Tag', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text on per product tag basis.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function button_text( $text, $single_or_archive ) {
		$product_tags = get_the_terms( get_the_ID(), 'product_tag' );
		if ( empty( $product_tags ) || is_wp_error( $product_tags ) ) {
			return $text;
		} else {
			$product_tags = wp_list_pluck( $product_tags, 'term_id' );
		}
		if ( ! isset( $this->options ) ) {
			$this->options = array(
				'enabled' => get_option( 'alg_wc_atcbl_per_tag_group_enabled', array() ),
				'ids'     => get_option( 'alg_wc_atcbl_per_tag_group_ids',     array() ),
			);
		}
		if ( ! isset( $this->labels[ $single_or_archive ] ) ) {
			$this->labels[ $single_or_archive ] = get_option( 'alg_wc_atcbl_per_tag_group_label_' . $single_or_archive, array() );
		}
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_tag', 1 ); $i++ ) {
			if (
				( ! isset( $this->options['enabled'][ $i ] ) || 'yes' === $this->options['enabled'][ $i ] ) &&
				! empty( $this->options['ids'][ $i ] ) &&
				$this->do_array_intersect( $product_tags, $this->options['ids'][ $i ] )
			) {
				return ( isset( $this->labels[ $single_or_archive ][ $i ] ) ? $this->labels[ $single_or_archive ][ $i ] : '' );
			}
		}
		return $text;
	}

	/**
	 * get_settings_lost_term_name.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_lost_term_name( $value ) {
		return sprintf( __( 'Tag #%d', 'add-to-cart-button-labels-for-woocommerce' ), $value );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (dev) WPML: `suppress_filter`?
	 * @todo    (feature) "Admin group title (optional)"
	 */
	function get_settings() {

		$product_tags = get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => false ) );
		$product_tags = ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ?
			array_combine( wp_list_pluck( $product_tags, 'term_id' ), wp_list_pluck( $product_tags, 'name' ) ) : array() );
		$_product_tags = get_option( 'alg_wc_atcbl_per_tag_group_ids', array() );

		$settings = array(
			array(
				'title'    => __( 'Per Tag Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc,
				'id'       => 'alg_wc_atcbl_per_tag_options',
			),
			array(
				'title'    => __( 'Per product tag labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'desc_tip' => '',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_tag_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Total tag groups', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc_tip' => __( 'Click "Save changes" after you update this number.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_atcbl_per_tag_total_number',
				'default'  => 1,
				'type'     => 'number',
				'desc'     => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', sprintf(
					'You will need <a target="_blank" href="%s">Add to Cart Button Labels for WooCommerce Pro</a> plugin to add more than one tag group.',
						'https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/' ) ),
				'custom_attributes' => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', array( 'step' => '1', 'min' => '1', 'max' => '1' ), 'array' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_atcbl_per_tag_options',
			),
		);
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_tag', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Group', 'add-to-cart-button-labels-for-woocommerce' ) . ' #' . $i,
					'type'     => 'title',
					'id'       => 'alg_wc_atcbl_per_tag_group_options_' . $i,
				),
				array(
					'title'    => sprintf( __( 'Enable %s', 'add-to-cart-button-labels-for-woocommerce' ),
						sprintf( __( 'group #%d', 'add-to-cart-button-labels-for-woocommerce' ), $i ) ),
					'desc'     => __( 'Enable', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_tag_group_enabled[{$i}]",
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Tags', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_tag_group_ids[{$i}]",
					'default'  => array(),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => ( ! empty( $_product_tags[ $i ] ) ?
						array_replace(
							array_combine( $_product_tags[ $i ], array_map( array( $this, 'get_settings_lost_term_name' ), $_product_tags[ $i ] ) ),
							$product_tags
						) :
						$product_tags ),
				),
				array(
					'title'    => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_tag_group_label_single[{$i}]",
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'title'    => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_tag_group_label_archive[{$i}]",
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_atcbl_per_tag_group_options_' . $i,
				),
			) );
		}

		return $settings;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Per_Tag();
