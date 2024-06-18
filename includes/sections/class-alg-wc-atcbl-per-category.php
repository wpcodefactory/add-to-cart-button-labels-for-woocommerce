<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per Category Class
 *
 * @version 2.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Per_Category' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Per_Category extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'per_category';
		$this->title = __( 'Per Product Category', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text on per product category basis.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function button_text( $text, $single_or_archive ) {
		$product_cats = get_the_terms( get_the_ID(), 'product_cat' );
		if ( empty( $product_cats ) || is_wp_error( $product_cats ) ) {
			return $text;
		} else {
			$product_cats = wp_list_pluck( $product_cats, 'term_id' );
		}
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_category', 1 ); $i++ ) {
			if (
				( 'yes' === get_option( 'alg_wc_add_to_cart_per_category_enabled_group_' . $i, 'yes' ) ) &&
				( ( $cats = get_option( 'alg_wc_add_to_cart_per_category_ids_group_' . $i, array() ) ) && ! empty( $cats ) ) &&
				$this->do_array_intersect( $product_cats, $cats )
			) {
				return get_option( 'alg_wc_add_to_cart_per_category_text_' . $single_or_archive . '_group_' . $i, '' );
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
		return sprintf( __( 'Category #%d', 'add-to-cart-button-labels-for-woocommerce' ), $value );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) WPML: `suppress_filter`?
	 * @todo    (feature) "Admin group title (optional)"
	 */
	function get_settings() {

		$product_cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
		$product_cats = ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ?
			array_combine( wp_list_pluck( $product_cats, 'term_id' ), wp_list_pluck( $product_cats, 'name' ) ) : array() );

		$settings = array(
			array(
				'title'    => __( 'Per Category Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc,
				'id'       => 'alg_wc_add_to_cart_button_labels_per_category_options',
			),
			array(
				'title'    => __( 'Per product category labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'desc_tip' => '',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_category_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Total category groups', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc_tip' => __( 'Click "Save changes" after you update this number.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_add_to_cart_button_labels_per_category_total_number',
				'default'  => 1,
				'type'     => 'number',
				'desc'     => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', sprintf(
					'You will need <a target="_blank" href="%s">Add to Cart Button Labels for WooCommerce Pro</a> plugin to add more than one category group.',
						'https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/' ) ),
				'custom_attributes' => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', array( 'step' => '1', 'min' => '1', 'max' => '1' ), 'array' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_category_options',
			),
		);
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_category', 1 ); $i++ ) {
			$_product_cats = get_option( 'alg_wc_add_to_cart_per_category_ids_group_' . $i, array() );
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Group', 'add-to-cart-button-labels-for-woocommerce' ) . ' #' . $i,
					'type'     => 'title',
					'id'       => 'alg_wc_add_to_cart_per_category_options_group_' . $i,
				),
				array(
					'title'    => sprintf( __( 'Enable %s', 'add-to-cart-button-labels-for-woocommerce' ),
						sprintf( __( 'group #%d', 'add-to-cart-button-labels-for-woocommerce' ), $i ) ),
					'desc'     => __( 'Enable', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => 'alg_wc_add_to_cart_per_category_enabled_group_' . $i,
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Categories', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => 'alg_wc_add_to_cart_per_category_ids_group_' . $i,
					'default'  => array(),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $product_cats,
					'options'  => ( ! empty( $_product_cats ) ?
						array_replace( array_combine( $_product_cats, array_map( array( $this, 'get_settings_lost_term_name' ), $_product_cats ) ), $product_cats ) :
						$product_cats ),
				),
				array(
					'title'    => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => 'alg_wc_add_to_cart_per_category_text_single_group_' . $i,
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'title'    => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => 'alg_wc_add_to_cart_per_category_text_archive_group_' . $i,
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_add_to_cart_per_category_options_group_' . $i,
				),
			) );
		}

		return $settings;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Per_Category();
