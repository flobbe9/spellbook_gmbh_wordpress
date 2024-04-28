<?php
require_once dirname(__DIR__, 2) . "/themes/spellbook_ug_theme/adminPages/init.php";

/**
 * @package AdminPages
 */
/*
Plugin Name: Admin Pages
Description: Initializes custom admin pages in wp.
Version: 0.0.1
Author: Florin Schikarski
License: GPLv2 or later
*/


add_action("admin_menu", "initAdminPages");