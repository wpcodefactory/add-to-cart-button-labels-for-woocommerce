=== Customize Add to Cart Button Text for WooCommerce ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: woocommerce, add to cart, woo commerce
Requires at least: 4.4
Tested up to: 6.6
Stable tag: 2.1.1
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Customize "Add to cart" button labels in WooCommerce. Beautifully.

== Description ==

**Customize Add to Cart Button Text for WooCommerce** plugin lets you change text for *Add to cart* button.

### &#9989; Main Features ###

**Add to cart** labels can be set:

* For **individual products** (i.e., on per product basis).
* For **all products** at once.
* By **product category** and/or by **product tag**.
* By **view** (i.e., single product view and archives (e.g., category, shop) view).
* By **product type** (simple, variable, external, grouped, other).
* By **condition** (standard product, free product, product with empty price, product is already in cart, and more).
* By **user role** and/or by individual **user**.

Additionally, you can use **shortcodes** in labels, for example, show current product price in button label:

`Buy now for [alg_wc_atcbl_product_price]`

Plugin is **WPML/Polylang compatible**, i.e., you can set different labels for different languages on a multi-language sites.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/add-to-cart-button-labels-woocommerce/).

### &#8505; More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Add to Cart Button Labels".

== Changelog ==

= 2.1.1 - 30/07/2024 =
* WC tested up to: 9.1.
* Tested up to: 6.6.

= 2.1.0 - 18/06/2024 =
* Fix - Declaring HPOS compatibility for the free plugin version, even if the Pro version is activated.
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev - Code refactoring.
* WC tested up to: 8.9.
* Tested up to: 6.5.
* WooCommerce added to the "Requires Plugins" (plugin header).

= 2.0.8 - 23/11/2023 =
* WC tested up to: 8.3.
* Tested up to: 6.4.
* Plugin name updated.

= 2.0.7 - 21/09/2023 =
* wp.org logo updated.

= 2.0.6 - 21/09/2023 =
* Changelog updated.

= 2.0.5 - 21/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.

= 2.0.4 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 2.0.3 - 14/12/2022 =
* Dev - Compatibility with custom order tables for WooCommerce (High-Performance Order Storage (HPOS)) declared.
* WC tested up to: 7.2.

= 2.0.2 - 07/11/2022 =
* WC tested up to: 7.0.
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 2.0.1 - 06/04/2022 =
* WC tested up to: 6.3.
* Tested up to: 5.9.

= 2.0.0 - 06/09/2021 =
* Dev - Shortcodes are now processed in labels.
* Dev - Shortcodes - `[alg_wc_atcbl_user_name]` shortcode added.
* Dev - Shortcodes - `[alg_wc_atcbl_product_title]` shortcode added.
* Dev - Shortcodes - `[alg_wc_atcbl_product_price]` shortcode added.
* Dev - Shortcodes - `[alg_wc_atcbl_product_stock]` shortcode added.
* Dev - Shortcodes - `[alg_wc_atcbl_product_meta]` shortcode added.
* Dev - Shortcodes - `[alg_wc_atcbl_product_func]` shortcode added.
* Dev - Shortcodes - `[alg_wc_atcbl_translate]` shortcode added.
* Dev - "Per Product Tag" section added.
* Dev - "Per User Role" section added.
* Dev - "Per User" section added.
* Dev - Admin settings - Restyled and descriptions updated.
* Dev - Admin settings - All input is properly sanitized now.
* Dev - Plugin is initialized on the `plugins_loaded` action now.
* Dev - Code refactoring.
* Tested up to: 5.8.
* WC tested up to: 5.6.

= 1.3.1 - 17/02/2021 =
* Dev - Localisation - `load_plugin_textdomain()` function moved to the `init` action.
* Tested up to: 5.6.
* WC tested up to: 5.0.

= 1.3.0 - 24/03/2020 =
* Dev - Code refactoring.
* Dev - Admin settings - Descriptions updated.
* Tested up to: 5.3.
* WC tested up to: 4.0.

= 1.2.1 - 26/07/2019 =
* Dev - Admin settings - Descriptions updated; "Your settings have been reset" notice added.
* Tested up to: 5.2.
* WC tested up to: 3.6.

= 1.2.0 - 22/11/2018 =
* Dev - "All Products" section added.
* Dev - Per Product Type & Condition - "On sale" condition options added.
* Dev - Per Product Type & Condition - Options re-checked (i.e., added and/or removed) for all product types and conditions.
* Dev - "Raw" input is now allowed in admin label settings.
* Dev - Admin settings restyled and descriptions updated.
* Dev - Major code refactoring.
* Dev - Plugin URI updated.

= 1.1.0 - 25/07/2017 =
* Dev - WooCommerce v3 compatibility - Getting product ID and type with functions (instead of accessing properties directly).
* Dev - Autoloading plugin options.
* Dev - Link updated from http://coder.fm to https://wpcodefactory.com.
* Dev - Plugin header ("Text Domain" etc.) updated.
* Dev - POT file added.

= 1.0.0 - 14/02/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
