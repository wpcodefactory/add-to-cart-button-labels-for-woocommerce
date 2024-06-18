<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per User Role Class
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Add_To_Cart_Button_Labels_Per_User_Role' ) ) :

class Alg_WC_Add_To_Cart_Button_Labels_Per_User_Role extends Alg_WC_Add_To_Cart_Button_Labels_Handler {

	/**
	 * current_user_roles.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public $current_user_roles;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id    = 'per_user_role';
		$this->title = __( 'Per User Role', 'add-to-cart-button-labels-for-woocommerce' );
		$this->desc  = __( 'This section lets you set "Add to cart" button text on per user role basis.', 'add-to-cart-button-labels-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * button_text.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function button_text( $text, $single_or_archive ) {
		if ( ! isset( $this->current_user_roles ) ) {
			$this->current_user_roles = ( ( $current_user = wp_get_current_user() ) && ! empty( $current_user->roles ) ? ( array ) $current_user->roles : array( 'guest' ) );
		}
		if ( ! isset( $this->options ) ) {
			$this->options = array(
				'enabled'    => get_option( 'alg_wc_atcbl_per_user_role_group_enabled', array() ),
				'user_roles' => get_option( 'alg_wc_atcbl_per_user_role_group_roles',   array() ),
			);
		}
		if ( ! isset( $this->labels[ $single_or_archive ] ) ) {
			$this->labels[ $single_or_archive ] = get_option( 'alg_wc_atcbl_per_user_role_group_label_' . $single_or_archive, array() );
		}
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_user_role', 1 ); $i++ ) {
			if (
				( ! isset( $this->options['enabled'][ $i ] ) || 'yes' === $this->options['enabled'][ $i ] ) &&
				! empty( $this->options['user_roles'][ $i ] ) &&
				$this->do_array_intersect( $this->current_user_roles, $this->options['user_roles'][ $i ] )
			) {
				return ( isset( $this->labels[ $single_or_archive ][ $i ] ) ? $this->labels[ $single_or_archive ][ $i ] : '' );
			}
		}
		return $text;
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

		global $wp_roles;
		$user_roles = array_merge( array( 'guest' => __( 'Guest', 'add-to-cart-button-labels-for-woocommerce' ) ),
			wp_list_pluck( apply_filters( 'editable_roles', $wp_roles->roles ), 'name' ) );

		$settings = array(
			array(
				'title'    => __( 'Per User Role Options', 'add-to-cart-button-labels-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => $this->desc,
				'id'       => 'alg_wc_atcbl_per_user_role_options',
			),
			array(
				'title'    => __( 'Per user role labels', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'add-to-cart-button-labels-for-woocommerce' ) . '</strong>',
				'desc_tip' => '',
				'id'       => 'alg_wc_add_to_cart_button_labels_per_user_role_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Total user role groups', 'add-to-cart-button-labels-for-woocommerce' ),
				'desc_tip' => __( 'Click "Save changes" after you update this number.', 'add-to-cart-button-labels-for-woocommerce' ),
				'id'       => 'alg_wc_atcbl_per_user_role_total_number',
				'default'  => 1,
				'type'     => 'number',
				'desc'     => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', sprintf(
					'You will need <a target="_blank" href="%s">Add to Cart Button Labels for WooCommerce Pro</a> plugin to add more than one user role group.',
						'https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/' ) ),
				'custom_attributes' => apply_filters( 'alg_wc_add_to_cart_button_labels_settings', array( 'step' => '1', 'min' => '1', 'max' => '1' ), 'array' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_atcbl_per_user_role_options',
			),
		);
		for ( $i = 1; $i <= apply_filters( 'alg_wc_add_to_cart_button_labels_per_user_role', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => sprintf( __( 'Group #%d', 'add-to-cart-button-labels-for-woocommerce' ), $i ),
					'type'     => 'title',
					'id'       => 'alg_wc_atcbl_per_user_role_group_options_' . $i,
				),
				array(
					'title'    => sprintf( __( 'Enable %s', 'add-to-cart-button-labels-for-woocommerce' ),
						sprintf( __( 'group #%d', 'add-to-cart-button-labels-for-woocommerce' ), $i ) ),
					'desc'     => __( 'Enable', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_role_group_enabled[{$i}]",
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'User roles', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_role_group_roles[{$i}]",
					'default'  => array(),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $user_roles,
				),
				array(
					'title'    => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_role_group_label_single[{$i}]",
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'title'    => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'       => "alg_wc_atcbl_per_user_role_group_label_archive[{$i}]",
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
					'alg_wc_atcbl_sanitize' => 'textarea',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_atcbl_per_user_role_group_options_' . $i,
				),
			) );
		}

		return $settings;
	}

}

endif;

return new Alg_WC_Add_To_Cart_Button_Labels_Per_User_Role();
