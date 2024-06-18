<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per Product Type Class
 *
 * @version 2.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Per_Product_Type' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Per_Product_Type extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * default_values.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $default_values;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'per_product_type';
		$this->title = __( 'Per Product Type & Condition', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text for various products types and various conditions.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) variable products: per variation, e.g., "already in cart" for single view
	 */
	function button_text( $text, $single_or_archives ) {

		global $product;

		if ( ! $product ) {
			return $text;
		}

		$product_type = $this->get_product_type( $product );
		if ( ! in_array( $product_type, array( 'external', 'grouped', 'simple', 'variable' ) ) ) {
			$product_type = 'other';
		}

		// Already in Cart
		if ( '' != ( $label = $this->get_custom_label_value( $single_or_archives, $product_type, '_in_cart' ) ) && isset( WC()->cart ) ) {
			foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( get_the_ID() == $this->get_product_or_variation_parent_id( $_product ) ) {
					return $label;
				}
			}
		}

		// Empty Price
		if ( '' != ( $label = $this->get_custom_label_value( $single_or_archives, $product_type, '_no_price' ) ) && '' === $product->get_price() ) {
			return $label;
		}

		// Zero Price
		if ( '' != ( $label = $this->get_custom_label_value( $single_or_archives, $product_type, '_zero_price' ) ) && '0' === $product->get_price() ) {
			return $label;
		}

		// On sale
		if ( '' != ( $label = $this->get_custom_label_value( $single_or_archives, $product_type, '_on_sale' ) ) && apply_filters( 'alg_wc_add_to_cart_button_labels_on_sale', false, $product ) ) {
			return $label;
		}

		// Normal Price
		return ( '' != ( $label = $this->get_custom_label_value( $single_or_archives, $product_type, '' ) ) ? $label : $text );

	}

	/**
	 * get_custom_label_value.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_custom_label_value( $single_or_archives, $product_type, $condition ) {
		return get_option( 'alg_wc_add_to_cart_p_prod_type_on_' . $single_or_archives . $condition . '_' . $product_type, '' );
	}

	/**
	 * get_product_type.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function get_product_type( $product ) {
		return ( $this->is_wc_version_below_3() ? $product->product_type : $product->get_type() );
	}

	/**
	 * get_settings_desc_tip.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function get_settings_desc_tip( $single_or_archives, $product_type, $condition ) {
		return __( 'Ignored if blank.', 'add-to-cart-button-labels-for-woocommerce' ) . '<br>' .
			sprintf( __( 'Default: %s', 'add-to-cart-button-labels-for-woocommerce' ),
				'<em>' . $this->get_settings_default_label_value( $single_or_archives, $product_type, $condition ) . '</em>' );
	}

	/**
	 * get_settings_default_label_value.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function get_settings_default_label_value( $single_or_archives, $product_type, $condition ) {
		return ( isset( $this->default_values[ $product_type ][ $condition ][ $single_or_archives ] ) ?
			$this->default_values[ $product_type ][ $condition ][ $single_or_archives ] : '' );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_settings() {
		$this->default_values = array(
			'simple' => array(
				'' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_on_sale' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_zero_price' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_no_price' => array(
					'archives' => __( 'Read more', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_in_cart' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
			),
			'variable' => array(
				'' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Select options', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_on_sale' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Select options', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_zero_price' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Select options', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_in_cart' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Select options', 'add-to-cart-button-labels-for-woocommerce' ),
				),
			),
			'external' => array(
				'' => array(
					'single'   => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_on_sale' => array(
					'single'   => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_zero_price' => array(
					'single'   => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_no_price' => array(
					'single'   => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Buy product', 'add-to-cart-button-labels-for-woocommerce' ),
				),
			),
			'grouped' => array(
				'' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'View products', 'add-to-cart-button-labels-for-woocommerce' ),
				),
			),
			'other' => array(
				'' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_on_sale' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_zero_price' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_no_price' => array(
					'archives' => __( 'Read more', 'add-to-cart-button-labels-for-woocommerce' ),
				),
				'_in_cart' => array(
					'single'   => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
					'archives' => __( 'Add to cart', 'add-to-cart-button-labels-for-woocommerce' ),
				),
			),
		);
		$product_types = array(
			'simple'        => __( 'Simple Products', 'add-to-cart-button-labels-for-woocommerce' ),
			'variable'      => __( 'Variable Products', 'add-to-cart-button-labels-for-woocommerce' ),
			'external'      => __( 'External Products', 'add-to-cart-button-labels-for-woocommerce' ),
			'grouped'       => __( 'Grouped Products', 'add-to-cart-button-labels-for-woocommerce' ),
			'other'         => __( 'Other Products', 'add-to-cart-button-labels-for-woocommerce' ),
		);
		$views = array(
			'single'        => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
			'archives'      => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
		);
		$conditions = array(
			''              => '',
			'_on_sale'      => __( 'On sale', 'add-to-cart-button-labels-for-woocommerce' ),
			'_zero_price'   => __( 'Free product', 'add-to-cart-button-labels-for-woocommerce' ),
			'_no_price'     => __( 'Empty price', 'add-to-cart-button-labels-for-woocommerce' ),
			'_in_cart'      => __( 'Already in cart', 'add-to-cart-button-labels-for-woocommerce' ),
		);
		$settings = array(
			array(
				'title'    => __( 'Per Product Type & Condition Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc,
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_options',
			),
			array(
				'title'    => __( 'Per product type & condition labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_type_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_options',
			),
		);
		foreach ( $product_types as $product_type_id => $product_type_title ) {
			$settings[] = array(
				'title'    => $product_type_title,
				'type'     => 'title',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_product_' . $product_type_id . '_options',
			);
			foreach ( $conditions as $condition_id => $condition_desc ) {
				foreach ( $views as $view_id => $view_desc ) {
					if ( '' !== $this->get_settings_default_label_value( $view_id, $product_type_id, $condition_id ) ) {
						$settings[] = array(
							'title'    => $view_desc . ( '' != $condition_desc ? ': ' . $condition_desc : '' ),
							'id'       => 'alg_wc_add_to_cart_p_prod_type_on_' . $view_id . $condition_id . '_' . $product_type_id,
							'desc_tip' => $this->get_settings_desc_tip( $view_id, $product_type_id, $condition_id ),
							'default'  => '',
							'type'     => 'text',
							'css'      => 'width:100%;',
							'desc'     => ( '_on_sale' === $condition_id ? apply_filters( 'alg_wc_add_to_cart_button_labels_settings', sprintf(
								'You will need <a target="_blank" href="%s">Add to Cart Button Labels for WooCommerce Pro</a> plugin to set separate label for "On sale" condition.',
									'https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/' ) ) : '' ),
							'custom_attributes' => ( '_on_sale' === $condition_id ? apply_filters( 'alg_wc_add_to_cart_button_labels_settings', array( 'readonly' => 'readonly' ) ) : '' ),
							'alg_wc_atcbl_sanitize' => 'textarea',
						);
					}
				}
			}
			$settings = array_merge( $settings, array(
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_add_to_cart_button_labels_per_product_' . $product_type_id . '_options',
				),
			) );
		}
		return $settings;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Per_Product_Type();
