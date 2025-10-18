<?php

use SpellbookGmbhTheme\Helpers\Utils;
use SpellbookGmbhTheme\Services\WPService;

/**
 * Register all custom admin pages.
 */
function initAdminPages(): void {

    // register custom menus
    registerThemeSettings();

    // remove some menus
    removeAdminMenus();

    // set menu order
    add_filter( 'menu_order', 'getAdminMenuOrder', 10, 1 );
    add_filter( 'custom_menu_order', 'getAdminMenuOrder', 10, 1 );
}


/**
 * Call necessary wp function to register theme settings menu. 
 */
function registerThemeSettings(): void {

    add_menu_page(
        "Theme settings",
        "Theme settings",
        "administrator",
        "theme-settings",
        function() {
            require_once dirname(__DIR__, 1) . "/views/ThemeSettingsView.php";
        },
    );
}


/**
 * Remove admin menus found on the left at ```/wp-admin``` like "Posts". Wont unregister the url though.
 */
function removeAdminMenus(): void {

    // remove "posts" menu
    foreach (WPService::getPostTypeNamesHiddenInMenu() as $postTypeName) {
        if ("post" === $postTypeName)
            remove_menu_page("edit.php");

        remove_menu_page("edit.php?post_type=" . $postTypeName);
    }
}


/**
 * @return array[string] with php page names for each menu page, using the correct order.
 */
function getAdminMenuOrder($menuOrder): array | bool {

    if (!$menuOrder) 
        return true;

    return [
        'index.php', // Dashboard
        'separator1',

        ...mapPostTypeMenus(),
        'upload.php', // Media
        'edit-comments.php', // Comments
        'separator2',
        
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'separator-last',

        'options-general.php', // Settings
        "edit.php?page=theme-settings", // Theme Settings
    ];
}


/**
 * Will ignore a menu if its ```post_type``` is included in ```WPService::getPostTypeNamesHiddenInMenu()```.

 * @return array[string] formatted like "edit.php?post_type=```$postTypeName```"
 */
function mapPostTypeMenus(): array {

    $postTypeMenus = [];

    foreach(WPService::getAllPostTypes() as $postTypeName) {
        // case: not to be excluded from menu
        if (!in_array($postTypeName, WPService::getPostTypeNamesHiddenInMenu()))
            array_push($postTypeMenus, "edit.php?post_type=$postTypeName");
    }

    return $postTypeMenus;
}