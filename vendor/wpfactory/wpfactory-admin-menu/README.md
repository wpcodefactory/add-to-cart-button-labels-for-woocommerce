# WPFactory Admin Menu

A library that adds a WPFactory menu on WordPress dashboard.

## Installation

Installation via Composer. Instructions to setup the `composer.json`.

1. Add these objects to the `repositories` array:

```json
"repositories": [    
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-admin-menu"
    }
]
```

2. Require the library and its dependencies:

```json
"require": {  
  "wpfactory/wpfactory-admin-menu": "*"
},
```

3. Use `preferred-install` parameter set as `dist` on `config`.

```json
"config": {
  "preferred-install": "dist"
}
```

**Full Example:**

```json
{
  "repositories": [        
    {
      "type": "vcs",
      "url": "https://github.com/wpcodefactory/wpfactory-admin-menu"
    }
  ],
  "require": {
    "wpfactory/wpfactory-admin-menu": "*"
  },
  "config": {
    "preferred-install": "dist"
  }
}
```

## How to use it?
1. Create/Put the composer.json on the root folder.

2. Require the Composer `autoload.php` on main plugin file. Most of our plugins are already doing it. Example:
```php
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
```

3. Then initialize the library with `\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance()` from within the main plugin class. Probably the best place is inside the hook `plugins_loaded`. If the main class is already being loaded with that hook, you can simply load the library in the class constructor. Try to remember to only run it inside a `is_admin()` check.
> [!NOTE]  
> Most probably, you won't even need to initialize it because it should have been initialized already by some other library.

*Example:*

```php
add_action( 'plugins_loaded', function(){  
    $main_plugin_class = new Main_Plugin_Class();  
} );
```

```php
class Main_Plugin_Class(){
    function __construct() { 
        if ( is_admin() ) {
            \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();
        }
    }
}
```

## Methods

### `move_wc_settings_tab_to_wpfactory_menu( array $args = null )`

Adds WooCommerce Settings tab as a WPFactory submenu item.

**Parameters:**

* **`wc_settings_tab_id`** (string) - The WooCommerce tab id from the settings page. Ex: From a settings tab such as `wp-admin/admin.php?page=wc-settings&tab=alg_wc_cost_of_goods`, the id would be `alg_wc_cost_of_goods`.
* **`menu_title`** (string) - The submenu item label displayed on WPFactory menu.
* **`page_title`** (string) - The title displayed above the new settings page. Default value: `'WPFactory plugins settings'`.
* **`capability`** (string) - Capability string used on `add_submenu_page()`. Default value: 'manage_options'.
* **`position`** (int) - Position used on `add_submenu_page()`. Default value: `30`.

**Example:**

```php
if ( is_admin() ) {
    $wpf_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();
    if ( method_exists( $wpf_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
        $wpf_admin_menu->move_wc_settings_tab_to_wpfactory_menu( array(
            'wc_settings_tab_id' => 'alg_wc_cost_of_goods',
            'menu_title'         => 'Cost of Goods',
        ) );
    }
}
```
> [!NOTE]  
> It's a good idea to check if the method exists with `method_exists( $wpf_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' )` to make sure it will be compatible with previous versions.
