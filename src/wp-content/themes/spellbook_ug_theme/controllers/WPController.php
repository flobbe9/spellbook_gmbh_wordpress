<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractController.php";


/**
 * Adding endpoints to the existing ```/wp/v2/``` endpoint. Any endpoint will be accessible under 
 * ```/wp/v2/custom/```. E.g. ```/wp/v2/custom/myCustomPagesPath```.
 * 
 * @since 0.0.1
 */
class WPController extends AbstractController {

    public function __construct() {

        parent::__construct("wp", "v2", WP_JSON_CUSTOM_MAPPING);
    }


    /**
     * Register all routes in this method.
     */
    public function register(): void {

        $this->register_getPages();
        $this->register_getMenus();
    }


    /**
     * ```/pages```
     */
    private function register_getPages(): void {

        register_rest_route(parent::getMapping(), "/pages", [
            "methdos" => "GET",
            "callback" => function() {

                $pages = get_pages();
                
                foreach ($pages as $page) 
                    $page->blocks = parse_blocks($page->post_content);

                return $pages;
            }
        ]);
    }


    private function register_getMenus(): void {

        register_rest_route(parent::getMapping(), "/menus", [
            "methods"=> "GET",
            "callback"=> function() {

                $menus = get_terms(["taxonomy" => "nav_menu"]);

                foreach ($menus as $menu)
                    $menu->items = wp_get_nav_menu_items($menu->term_id); 

                return $menus;
            }
        ]);
    }
}


/** 
 * String that is appended to any customized ```/wp-json/wp/v2/``` call. 
 * 
 * E.g. ```/wp-json/wp/v2/{WP_JSON_CUSTOM_MAPPING}/myEndpoint```
 * */
const WP_JSON_CUSTOM_MAPPING = "custom";