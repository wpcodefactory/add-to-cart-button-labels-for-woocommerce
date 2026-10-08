<?php
/**
 * Add to Cart Button Labels for WooCommerce - Main Class
 *
 * @version 2.3.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL' ) ) :

	/**
	 * Main Alg_WC_ATCBL class.
	 *
	 * @version 2.3.0
	 * @since   1.0.0
	 */
	final class Alg_WC_ATCBL {

		/**
		 * Plugin version.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @var string
		 */
		public $version = ALG_WC_ADD_TO_CART_BUTTON_LABELS_VERSION;

		/**
		 * Shortcodes.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var Alg_WC_ATCBL_Shortcodes
		 */
		public $shortcodes;

		/**
		 * Sections.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var array
		 */
		public $sections;

		/**
		 * The single instance of the class.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 *
		 * @var Alg_WC_ATCBL
		 */
		protected static $instance = null;

		/**
		 * Main Alg_WC_ATCBL Instance.
		 *
		 * Ensures only one instance of Alg_WC_ATCBL is loaded or can be loaded.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 *
		 * @static
		 *
		 * @return Alg_WC_ATCBL
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Alg_WC_ATCBL Constructor.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 *
		 * @access public
		 */
		public function __construct() {
			// Check for active WooCommerce plugin.
			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			// Load libs.
			if ( is_admin() ) {
				require_once plugin_dir_path( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) . 'vendor/autoload.php';
			}

			// Declare compatibility with custom order tables for WooCommerce.
			add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

			// Pro.
			if ( 'add-to-cart-button-labels-for-woocommerce-pro.php' === basename( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'pro/class-alg-wc-atcbl-pro.php';
			}

			// Include required files.
			add_action( 'init', array( $this, 'includes' ) );

			// Admin.
			if ( is_admin() ) {
				$this->admin();
			}
		}

		/**
		 * WC declare compatibility.
		 *
		 * @version 2.1.0
		 * @since   2.0.3
		 *
		 * @see https://developer.woocommerce.com/docs/features/high-performance-order-storage/recipe-book/
		 */
		public function wc_declare_compatibility() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				$files = (
					defined( 'ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE_FREE' ) ?
					array( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE, ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE_FREE ) :
					array( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE )
				);
				foreach ( $files as $file ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
						'custom_order_tables',
						$file,
						true
					);
				}
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 2.2.0
		 * @since   1.0.0
		 *
		 * @todo (dev) `wpml-config.xml`.
		 * @todo (dev) Store settings as serialized values ("Per Product Type & Condition", "Per Category").
		 */
		public function includes() {
			if ( 'yes' === get_option( 'alg_wc_add_to_cart_button_labels_enabled', 'yes' ) ) {

				$this->shortcodes = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-atcbl-shortcodes.php';

				require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-handler.php';
				$this->sections   = array();
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-all-products.php';
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-per-product-type.php';
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-per-category.php';
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-per-tag.php';
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-per-product.php';
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-per-user-role.php';
				$this->sections[] = require_once plugin_dir_path( __FILE__ ) . 'sections/class-alg-wc-atcbl-per-user.php';
			}
		}

		/**
		 * Admin.
		 *
		 * @version 2.2.4
		 * @since   1.2.0
		 */
		public function admin() {
			// Action links.
			add_filter(
				'plugin_action_links_' . plugin_basename( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ),
				array( $this, 'action_links' )
			);

			// "Recommendations" page.
			add_action( 'init', array( $this, 'add_cross_selling_library' ) );

			// WC Settings tab as WPFactory submenu item.
			add_action( 'init', array( $this, 'move_wc_settings_tab_to_wpfactory_menu' ) );

			// Settings.
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

			// Version updated.
			if ( get_option( 'alg_wc_add_to_cart_button_labels_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @version 2.2.0
		 * @since   1.0.0
		 *
		 * @param mixed $links Action links for the plugin.
		 *
		 * @return array
		 */
		public function action_links( $links ) {
			$custom_links = array();

			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_add_to_cart_button_labels' ) . '">' .
				__( 'Settings', 'add-to-cart-button-labels-for-woocommerce' ) .
			'</a>';

			if ( 'add-to-cart-button-labels-for-woocommerce.php' === basename( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) ) {
				$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/">' .
					__( 'Go Pro', 'add-to-cart-button-labels-for-woocommerce' ) .
				'</a>';
			}

			return array_merge( $custom_links, $links );
		}

		/**
		 * Add cross selling library.
		 *
		 * @version 2.2.0
		 * @since   2.2.0
		 */
		public function add_cross_selling_library() {
			if ( ! class_exists( '\WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {
				return;
			}

			$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
			$cross_selling->setup( array( 'plugin_file_path' => ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) );
			$cross_selling->init();
		}

		/**
		 * Move WC settings tab to WPFactory menu.
		 *
		 * @version 2.2.4
		 * @since   2.2.0
		 */
		public function move_wc_settings_tab_to_wpfactory_menu() {
			if ( ! class_exists( '\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {
				return;
			}

			$wpfactory_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();

			if ( ! method_exists( $wpfactory_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
				return;
			}

			$wpfactory_admin_menu->move_wc_settings_tab_to_wpfactory_menu(
				array(
					'wc_settings_tab_id' => 'alg_wc_add_to_cart_button_labels',
					'menu_title'         => __( 'Add to Cart Button Labels', 'add-to-cart-button-labels-for-woocommerce' ),
					'page_title'         => __( 'Change Add to Cart Button Text for WooCommerce', 'add-to-cart-button-labels-for-woocommerce' ),
					'plugin_icon'        => array(
						'get_url_method'    => 'wporg_plugins_api',
						'wporg_plugin_slug' => 'add-to-cart-button-labels-for-woocommerce',
					),
				)
			);
		}

		/**
		 * Add Add to Cart Button Labels settings tab to WooCommerce settings.
		 *
		 * @version 2.2.0
		 * @since   1.0.0
		 *
		 * @param array $settings WooCommerce settings tabs.
		 *
		 * @return array Modified WooCommerce settings tabs.
		 */
		public function add_woocommerce_settings_tab( $settings ) {
			$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-settings-atcbl.php';
			return $settings;
		}

		/**
		 * Version updated.
		 *
		 * @version 1.2.0
		 * @since   1.2.0
		 */
		public function version_updated() {
			update_option( 'alg_wc_add_to_cart_button_labels_version', $this->version );
		}

		/**
		 * Get the plugin url.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugin_dir_url( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( ALG_WC_ADD_TO_CART_BUTTON_LABELS_FILE ) );
		}
	}

endif;
