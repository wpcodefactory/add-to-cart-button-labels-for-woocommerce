# Changelog

**Types of changes**
* `Added` for new features.
* `Changed` for changes in existing functionality.
* `Deprecated` for soon-to-be removed features.
* `Removed` for now removed features.
* `Fixed` for any bug fixes.
* `Security` in case of vulnerabilities

## [1.0.3] - 2024-10-14
### Fixed
* Capability to `manage_woocommerce` if woocommerce is enabled, or else `manage_options`.

## [1.0.2] - 2024-10-08
### Changed
* Visibility from settings tabs to hidden by default when accessing the current plugin settings page.
* Default `page_title` param set from `menu_title` param when using the `move_wc_settings_tab_to_wpfactory_menu()` method.

### Added
* Flag `$show_current_plugin_tab` on `WC_Settings_Menu_Item_Swapper:hide_wc_settings_tabs()` to show/hide current plugin tab.
* Method `add_submenu_page()`.

## [1.0.1] - 2024-09-19
### Added
* Method `move_wc_settings_tab_to_wpfactory_menu()` with the possibility of adding specific WooCommerce settings tabs as WPFactory submenu items.
* Version property.
* Translation files.

## [1.0.0] - 2024-09-17
### Added
* Initial release.