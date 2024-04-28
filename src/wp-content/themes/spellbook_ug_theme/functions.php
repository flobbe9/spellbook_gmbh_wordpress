<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/controllers/init.php";
require_once __DIR__ . "/postTypes/init.php";
require_once __DIR__ . "/menus/init.php";
require_once __DIR__ . "/adminPages/init.php";
require_once __DIR__ . "/blocks/init.php";

/**
 * Admin pages are initialized via "adminPages" plugin.
 */


// copied from wp-settings.php(675) in order to register rest api here
if ( ! class_exists( 'WP_Site_Health' ) )
	require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
WP_Site_Health::get_instance();


initPostTypes();

initControllers();

initMenus();

initBlocks();