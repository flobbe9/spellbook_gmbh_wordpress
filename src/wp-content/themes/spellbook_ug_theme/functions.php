<?php
require_once __DIR__ . "/controllers/init.php";
require_once __DIR__ . "/postTypes/init.php";
require_once __DIR__ . "/menus/init.php";
require_once __DIR__ . "/adminPages/init.php";
require_once __DIR__ . "/blocks/init.php";
// TODO 
    // custom dashboard (/wp-admin/index.php) (?) or short tutorial on whatsapp :)
    // cant publish when site and home url are different
        // register post types properly?
    // remove unnecessary themes and plugins (manually)
    // version??
    // licenses???
    // README

// TODO: 
    // slider 
        // try wp custom block, dont use carbon fields (?)
        // links
        // images disappear
    // register at least one example menu


/**
 * PostTypes
 */
add_action("init", "initPostTypes");

/**
 * AdminMenus
 */
add_action("admin_menu", "initAdminPages");

/**
 * NavMenus
 */
add_action("wp_update_nav_menu", "initMenus");

/**
 * Controllers
 */
add_action("rest_api_init", "initControllers");

/**
 * BlockTypes
 */
add_action("carbon_fields_register_fields", "initBlocks");
add_action("after_setup_theme", "initCarbonFields");