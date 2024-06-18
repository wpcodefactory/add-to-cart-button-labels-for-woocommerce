<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per User Class
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Per_User' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Per_User extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * current_user_id.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $current_user_id;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id    = 'per_user';
		$this->title = __( 'Per User', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text on per user basis.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function button_text( $text, $single_or_archive ) {
		if ( ! isset( $this->current_user_id ) ) {
			$this->current_user_id = ( ( $current_user = wp_get_current_user() ) ? $current_user->ID : 'guest' );
		}
		if ( ! isset( $this->options ) ) {
			$this->options = array(
				'enabled' => get_option( 'alg_wc_atcbl_per_user_group_enabled', array() ),
				'users'   => get_option( 'alg_wc_atcbl_per_user_group_users',   array() ),
			);
		}
		if ( ! isset( $this->labels[ $single_or_archive ] ) ) {
			$this->labels[ $single_or_archive ] = get_option( 'alg_wc_atcbl_per_user_group_label_' . $single_or_archive, array() );
		}
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_user', 1 ); $i++ ) {
			if (
				( ! isset( $this->options['enabled'][ $i ] ) || 'yes' === $this->options['enabled'][ $i ] ) &&
				! empty( $this->options['users'][ $i ] ) &&
				in_array( $this->current_user_id, $this->options['users'][ $i ] )
			) {
				return ( isset( $this->labels[ $single_or_archive ][ $i ] ) ? $this->labels[ $single_or_archive ][ $i ] : '' );
			}
		}
		return $text;
	}

	/**
	 * get_user_nicename.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_user_nicename( $user ) {
		return sprintf( '%s (#%s)', $user->user_nicename, $user->ID );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (feature) "Admin group title (optional)"
	 */
	function get_settings() {

		$users = get_users( array( 'orderby' => 'user_nicename' ) );
		$users = array_combine( wp_list_pluck( $users, 'ID' ), array_map( array( $this, 'get_user_nicename' ), $users ) );
		$users = array_replace( array( 'guest' => __( 'Guest', 'add-to-cart-button-labels-for-woocommerce' ) . ' (#0)' ), $users );

		$settings = array(
			array(
				'title'    => __( 'Per User Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc,
				'id'       => 'alg_wc_atcbl_per_user_options',
			),
			array(
				'title'    => __( 'Per user labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'desc_tip' => '',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_user_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Total user groups', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc_tip' => __( 'Click "Save changes" after you update this number.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_atcbl_per_user_total_number',
				'default'  => 1,
				'type'     => 'number',
				'desc'     => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', sprintf(
					'You will need <a target="_blank" href="%s">Add to Cart Button Labels for WooCommerce Pro</a> plugin to add more than one user group.',
						'https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/' ) ),
				'custom_attributes' => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', array( 'step' => '1', 'min' => '1', 'max' => '1' ), 'array' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_atcbl_per_user_options',
			),
		);
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_user', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => sprintf( __( 'Group #%d', 'add-to-cart-button-labels-for-woocommerce' ), $i ),
					'type'     => 'title',
					'id'       => 'alg_wc_atcbl_per_user_group_options_' . $i,
				),
				array(
					'title'    => sprintf( __( 'Enable %s', 'add-to-cart-button-labels-for-woocommerce' ),
						sprintf( __( 'group #%d', 'add-to-cart-button-labels-for-woocommerce' ), $i ) ),
					'desc'     => __( 'Enable', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_group_enabled[{$i}]",
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Users', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_group_users[{$i}]",
					'default'  => array(),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $users,
				),
				array(
					'title'    => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_group_label_single[{$i}]",
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'title'    => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_group_label_archive[{$i}]",
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_atcbl_per_user_group_options_' . $i,
				),
			) );
		}

		return $settings;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Per_User();
