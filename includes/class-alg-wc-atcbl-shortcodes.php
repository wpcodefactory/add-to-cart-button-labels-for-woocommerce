<?php
/**
 * Add to Cart Button Labels for WooCommerce - Shortcodes Class
 *
 * @version 2.3.0
 * @since   2.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Add_To_Cart_Button_Labels\Shortcodes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_ATCBL_Shortcodes' ) ) :

	/**
	 * Alg_WC_ATCBL_Shortcodes class.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	class Alg_WC_ATCBL_Shortcodes {

		/**
		 * Shortcodes.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var array
		 */
		public $shortcodes;

		/**
		 * Current user.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 *
		 * @var WP_User
		 */
		public $current_user;

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @todo (feature) More shortcodes, e.g., product category/tag (current), product categories/tags, etc.
		 * @todo (feature) Common atts: `before`, `after`, etc.
		 * @todo (feature) Optional `product_id` attribute in all `product_...` shortcodes.
		 */
		public function __construct() {
			$this->shortcodes = array(
				'user_name',
				'product_title',
				'product_price',
				'product_stock',
				'product_meta',
				'product_func',
				'translate',
			);

			foreach ( $this->shortcodes as $shortcode ) {
				add_shortcode( 'alg_wc_atcbl_' . $shortcode, array( $this, $shortcode ) );
			}
		}

		/**
		 * Product meta.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @param array $atts Shortcode attributes.
		 *
		 * @return string Product meta value or empty string.
		 *
		 * @todo (feature) Add `use_parent_product` attribute?
		 */
		public function product_meta( $atts ) {
			global $product;
			return (
				$product && isset( $atts['key'] ) ?
				wp_kses_post( get_post_meta( $product->get_id(), wc_clean( $atts['key'] ), true ) ) :
				''
			);
		}

		/**
		 * Product func.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @param array $atts Shortcode attributes.
		 *
		 * @return string Product function result or empty string.
		 */
		public function product_func( $atts ) {
			global $product;
			return (
				(
					$product &&
					isset( $atts['name'] ) &&
					( $func = wc_clean( $atts['name'] ) ) && // phpcs:ignore WordPress.CodeAnalysis.AssignmentInTernaryCondition.FoundInTernaryCondition, Squiz.PHP.DisallowMultipleAssignments.Found
					is_callable( array( $product, $func ) ) &&
					in_array(
						$func,
						apply_filters(
							'alg_wc_add_to_cart_button_labels_shortcode_allowed_functions',
							array(
								'add_to_cart_url',
								'get_average_rating',
								'get_backorders',
								'get_catalog_visibility',
								'get_clone_mode',
								'get_cogs_effective_value',
								'get_cogs_total_value',
								'get_cogs_value',
								'get_cogs_value_html',
								'get_description',
								'get_download_expiry',
								'get_download_limit',
								'get_downloadable',
								'get_featured',
								'get_file_download_path',
								'get_formatted_name',
								'get_global_unique_id',
								'get_height',
								'get_id',
								'get_image',
								'get_image_id',
								'get_length',
								'get_low_stock_amount',
								'get_manage_stock',
								'get_max_purchase_quantity',
								'get_menu_order',
								'get_meta_cache_key',
								'get_min_purchase_quantity',
								'get_name',
								'get_object_read',
								'get_parent_id',
								'get_permalink',
								'get_post_password',
								'get_price',
								'get_price_html',
								'get_price_suffix',
								'get_purchase_note',
								'get_purchase_quantity_step',
								'get_rating_count',
								'get_regular_price',
								'get_review_count',
								'get_reviews_allowed',
								'get_sale_price',
								'get_shipping_class',
								'get_shipping_class_id',
								'get_short_description',
								'get_sku',
								'get_slug',
								'get_sold_individually',
								'get_status',
								'get_stock_managed_by_id',
								'get_stock_quantity',
								'get_stock_status',
								'get_tax_class',
								'get_tax_status',
								'get_title',
								'get_total_sales',
								'get_type',
								'get_virtual',
								'get_weight',
								'get_width',
							)
						),
						true
					)
				) ?
				wp_kses_post( $product->$func() ) :
				''
			);
		}

		/**
		 * Product price.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @todo (dev) `$product->get_price()`?
		 * @todo (dev) `strip_tags( $product->get_price_html() )`?
		 */
		public function product_price() {
			global $product;
			return ( $product ? strip_tags( wc_price( $product->get_price() ) ) : '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
		}

		/**
		 * Product stock.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 */
		public function product_stock() {
			global $product;
			return ( $product ? wp_kses_post( $product->get_stock_quantity() ) : '' );
		}

		/**
		 * Product title.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 */
		public function product_title() {
			global $product;
			return ( $product ? wp_kses_post( $product->get_title() ) : '' );
		}

		/**
		 * User name.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @todo (dev) Customizable "Guest".
		 */
		public function user_name() {
			if ( ! isset( $this->current_user ) ) {
				$this->current_user = wp_get_current_user();
			}
			return (
				$this->current_user ?
				wp_kses_post( $this->current_user->user_nicename ) :
				esc_html__( 'Guest', 'add-to-cart-button-labels-for-woocommerce' )
			);
		}

		/**
		 * Translate.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 *
		 * @return string Translated text.
		 */
		public function translate( $atts, $content = '' ) {
			// E.g.: `[alg_wc_atcbl_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`.
			if (
				isset( $atts['lang_text'] ) &&
				isset( $atts['not_lang_text'] ) &&
				! empty( $atts['lang'] )
			) {
				return (
					(
						! defined( 'ICL_LANGUAGE_CODE' ) ||
						! in_array(
							strtolower( ICL_LANGUAGE_CODE ),
							array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ),
							true
						)
					) ?
					wp_kses_post( $atts['not_lang_text'] ) :
					wp_kses_post( $atts['lang_text'] )
				);
			}

			// E.g.: `[alg_wc_atcbl_translate lang="EN,DE"]Text for EN & DE[/alg_wc_atcbl_translate][alg_wc_atcbl_translate not_lang="EN,DE"]Text for other languages[/alg_wc_atcbl_translate]`.
			return (
				(
					(
						! empty( $atts['lang'] ) &&
						(
							! defined( 'ICL_LANGUAGE_CODE' ) ||
							! in_array(
								strtolower( ICL_LANGUAGE_CODE ),
								array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ),
								true
							)
						)
					) ||
					(
						! empty( $atts['not_lang'] ) &&
						(
							defined( 'ICL_LANGUAGE_CODE' ) &&
							in_array(
								strtolower( ICL_LANGUAGE_CODE ),
								array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ),
								true
							)
						)
					)
				) ?
				'' :
				wp_kses_post( $content )
			);
		}
	}

endif;

return new Alg_WC_ATCBL_Shortcodes();
