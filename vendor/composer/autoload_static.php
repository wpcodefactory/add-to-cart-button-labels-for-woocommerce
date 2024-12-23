<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit975a31b1bba1e5b13ef53e6b3d59cf0b
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WPFactory\\WPFactory_Admin_Menu\\Singleton' => __DIR__ . '/..' . '/wpfactory/wpfactory-admin-menu/src/php/trait-singleton.php',
        'WPFactory\\WPFactory_Admin_Menu\\WC_Settings_Menu_Item_Swapper' => __DIR__ . '/..' . '/wpfactory/wpfactory-admin-menu/src/php/class-wc-settings-menu-item-swapper.php',
        'WPFactory\\WPFactory_Admin_Menu\\WPFactory_Admin_Menu' => __DIR__ . '/..' . '/wpfactory/wpfactory-admin-menu/src/php/class-wpfactory-admin-menu.php',
        'WPFactory\\WPFactory_Cross_Selling\\Product_Categories' => __DIR__ . '/..' . '/wpfactory/wpfactory-cross-selling/src/php/class-product-categories.php',
        'WPFactory\\WPFactory_Cross_Selling\\Products' => __DIR__ . '/..' . '/wpfactory/wpfactory-cross-selling/src/php/class-products.php',
        'WPFactory\\WPFactory_Cross_Selling\\Singleton' => __DIR__ . '/..' . '/wpfactory/wpfactory-cross-selling/src/php/trait-singleton.php',
        'WPFactory\\WPFactory_Cross_Selling\\WPFactory_Cross_Selling' => __DIR__ . '/..' . '/wpfactory/wpfactory-cross-selling/src/php/class-wpfactory-cross-selling.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit975a31b1bba1e5b13ef53e6b3d59cf0b::$classMap;

        }, null, ClassLoader::class);
    }
}
