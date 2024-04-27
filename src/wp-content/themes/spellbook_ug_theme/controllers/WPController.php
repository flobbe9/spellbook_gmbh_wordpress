<?php
require_once "AbstractController.php";


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


    public function register(): void {

        $this->register_getPages();
    }


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
}


/** 
 * String that is appended to any customized ```/wp-json/wp/v2/``` call. 
 * 
 * E.g. ```/wp-json/wp/v2/{WP_JSON_CUSTOM_MAPPING}/myEndpoint```
 * */
const WP_JSON_CUSTOM_MAPPING = "custom";