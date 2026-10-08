<?php
/**
 * Add to Cart Button Labels for WooCommerce - Per User Role Class
 *
 * @version 2.3.0
 * @since   2.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels\Sections
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL_Per_User_Role' ) ) :

	/**
	 * Alg_WC_ATCBL_Per_User_Role class.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	class Alg_WC_ATCBL_Per_User_Role extends Alg_WC_ATCBL_Handler {

		/**
		 * Current user roles.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var array
		 */
		public $current_user_roles;

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function __construct() {
			$this->id    = 'per_user_role';
			$this->title = __( 'Per User Role', 'add-to-cart-button-labels-for-woocommerce' );
			$this->desc  = __( 'This section lets you set "Add to cart" button text on per user role basis.', 'add-to-cart-button-labels-for-woocommerce' );

			parent::__construct();
		}

		/**
		 * Button text.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @param string $text              The current button text.
		 * @param string $single_or_archive Whether the button is on a single product page or an archive page.
		 *
		 * @return string The modified button text.
		 */
		public function button_text( $text, $single_or_archive ) {
			if ( ! isset( $this->current_user_roles ) ) {
				$current_user             = wp_get_current_user();
				$this->current_user_roles = (
					$current_user && ! empty( $current_user->roles ) ?
					(array) $current_user->roles :
					array( 'guest' )
				);
			}

			if ( ! isset( $this->options ) ) {
				$this->options = array(
					'enabled'    => get_option( 'alg_wc_atcbl_per_user_role_group_enabled', array() ),
					'user_roles' => get_option( 'alg_wc_atcbl_per_user_role_group_roles', array() ),
				);
			}

			if ( ! isset( $this->labels[ $single_or_archive ] ) ) {
				$this->labels[ $single_or_archive ] = get_option( 'alg_wc_atcbl_per_user_role_group_label_' . $single_or_archive, array() );
			}

			$total = get_option( 'alg_wc_atcbl_per_user_role_total_number', 1 );
			for ( $i = 1; $i <= $total; $i++ ) {
				if (
					(
						! isset( $this->options['enabled'][ $i ] ) ||
						'yes' === $this->options['enabled'][ $i ]
					) &&
					! empty( $this->options['user_roles'][ $i ] ) &&
					$this->do_array_intersect( $this->current_user_roles, $this->options['user_roles'][ $i ] )
				) {
					return ( $this->labels[ $single_or_archive ][ $i ] ?? '' );
				}
			}

			return $text;
		}

		/**
		 * Get settings.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @todo (feature) "Admin group title (optional)".
		 */
		public function get_settings() {
			global $wp_roles;
			$user_roles = array_merge(
				array( 'guest' => __( 'Guest', 'add-to-cart-button-labels-for-woocommerce' ) ),
				wp_list_pluck( apply_filters( 'editable_roles', $wp_roles->roles ), 'name' ) // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			);

			$settings = array(
				array(
					'title' => __( 'Per User Role Options', 'add-to-cart-button-labels-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => $this->desc,
					'id'    => 'alg_wc_atcbl_per_user_role_options',
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
					'title'             => __( 'Total user role groups', 'add-to-cart-button-labels-for-woocommerce' ),
					'desc_tip'          => __( 'Click "Save changes" after you update this number.', 'add-to-cart-button-labels-for-woocommerce' ),
					'id'                => 'alg_wc_atcbl_per_user_role_total_number',
					'default'           => 1,
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '1',
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_atcbl_per_user_role_options',
				),
			);

			$total = get_option( 'alg_wc_atcbl_per_user_role_total_number', 1 );
			for ( $i = 1; $i <= $total; $i++ ) {
				$settings = array_merge(
					$settings,
					array(
						array(
							'title' => sprintf(
								/* Translators: %d: Group ID. */
								__( 'Group #%d', 'add-to-cart-button-labels-for-woocommerce' ),
								$i
							),
							'type'  => 'title',
							'id'    => 'alg_wc_atcbl_per_user_role_group_options_' . $i,
						),
						array(
							'title'   => sprintf(
								/* Translators: %s: Group title. */
								__( 'Enable %s', 'add-to-cart-button-labels-for-woocommerce' ),
								sprintf(
									/* Translators: %d: Group ID. */
									__( 'group #%d', 'add-to-cart-button-labels-for-woocommerce' ),
									$i
								)
							),
							'desc'    => __( 'Enable', 'add-to-cart-button-labels-for-woocommerce' ),
							'id'      => "alg_wc_atcbl_per_user_role_group_enabled[{$i}]",
							'default' => 'yes',
							'type'    => 'checkbox',
						),
						array(
							'title'   => __( 'User roles', 'add-to-cart-button-labels-for-woocommerce' ),
							'id'      => "alg_wc_atcbl_per_user_role_group_roles[{$i}]",
							'default' => array(),
							'type'    => 'multiselect',
							'class'   => 'chosen_select',
							'options' => $user_roles,
						),
						array(
							'title'                 => __( 'Single product page', 'add-to-cart-button-labels-for-woocommerce' ),
							'id'                    => "alg_wc_atcbl_per_user_role_group_label_single[{$i}]",
							'default'               => '',
							'type'                  => 'text',
							'css'                   => 'width:100%;',
							'alg_wc_atcbl_sanitize' => 'textarea',
						),
						array(
							'title'                 => __( 'Shop page', 'add-to-cart-button-labels-for-woocommerce' ),
							'id'                    => "alg_wc_atcbl_per_user_role_group_label_archive[{$i}]",
							'default'               => '',
							'type'                  => 'text',
							'css'                   => 'width:100%;',
							'alg_wc_atcbl_sanitize' => 'textarea',
						),
						array(
							'type' => 'sectionend',
							'id'   => 'alg_wc_atcbl_per_user_role_group_options_' . $i,
						),
					)
				);
			}

			return $settings;
		}
	}

endif;

return new Alg_WC_ATCBL_Per_User_Role();
