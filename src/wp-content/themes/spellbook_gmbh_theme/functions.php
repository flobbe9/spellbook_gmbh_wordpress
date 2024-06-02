<?php
require_once __DIR__ . "/controllers/init.php";
require_once __DIR__ . "/postTypes/init.php";
require_once __DIR__ . "/menus/init.php";
require_once __DIR__ . "/adminPages/init.php";
require_once __DIR__ . "/adminBarMenu/init.php";
require_once __DIR__ . "/config/init.php";
require_once __DIR__ . "/blocks/init.php";
require_once __DIR__ . "/utils/Utils.php";

// IDEA
    // add english language for whole site
    
// TODO 
    // add go live button or something in theme settings
    // add cardmarket link to social links?
    // wp update not permanent??
    // auto db and updates folder backup?
    // wrong version 
    // give wp writing permission for .htaccess

    // url
        // write version update .md file, also edit github var

/**
 * PostTypes
 */
add_action("init", "initPostTypes");

/**
 * AdminMenus
 */
add_action("admin_menu", "initAdminPages");

/**
 * AdminToolBar
 */
add_action("admin_bar_menu", "initAdminBarMenu");

/**
 * NavMenus
 */
add_action("after_setup_theme", "initMenus");

/**
 * Controllers
 */
add_action("rest_api_init", "initControllers");

/**
 * BlockTypes
 */
add_action("carbon_fields_register_fields", "initBlocks");
add_action("after_setup_theme", "initCarbonFields");

/**
 * Config
 */
add_action("wp_loaded", "initConfig");