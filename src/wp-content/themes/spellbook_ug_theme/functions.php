<?php
require_once __DIR__ . "/controllers/init.php";
require_once __DIR__ . "/postTypes/init.php";
require_once __DIR__ . "/menus/init.php";
require_once __DIR__ . "/adminPages/init.php";
require_once __DIR__ . "/blocks/init.php";
/**
 * Admin pages are initialized via "adminPages" plugin.
 */
// TODO: https
// TODO: Dockerfile
// TODO: make note of the version somewhere on the page

// call this first always
someConfig();

initMenus();

initPostTypes();

initControllers();

add_action("carbon_fields_register_fields", "initBlocks");
add_action("after_setup_theme", "initCarbonFields");


/**
 * Do some configuration from wp-settings. 
 */
function someConfig(): void {
    
    // copied from wp-settings.php(675) in order to register rest api here
    if ( ! class_exists( 'WP_Site_Health' ) )
        require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
    WP_Site_Health::get_instance();
}