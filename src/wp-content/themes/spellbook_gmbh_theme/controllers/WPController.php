<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractController.php";
require_once dirname(__DIR__, 1) . "/services/WPService.php";
require_once __DIR__ . "/HttpResponse.php";
require_once dirname(__DIR__, 1) ."/utils/Utils.php";


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

        $this->registerPages();
        $this->registerAllPages();
        $this->registerMenus();
        $this->registerPostTypes();
        $this->registerValidateUser();
    }


    /**
     * ```/pages```
     * 
     * @return array of all posts with post_type "page" plus their parsed blocks ("blocks" attribute)
     */
    private function registerPages(): void {

        register_rest_route(parent::getMapping(), "/pages", [
            "methdos" => "GET",
            "callback" => function() {

                $pages = get_pages();
                
                return WPService::mapPages($pages);
            },
            'permission_callback' => "__return_true"
        ]);
    }

    
    /**
     * ```/allPages```
     * 
     * @return array with all page objects regardless of the post_type plus their parsed blocks ("blocks" attribute). Exclude post_type "post".
     */
    private function registerAllPages(): void {

        register_rest_route(parent::getMapping(), "/allPages", [
            "methdos" => "GET",
            "callback" => function() {

                $pages = get_posts([
                    "posts_per_page" => -1, // get all posts
                    "post_status" => WPService::getPermittedPostStatuses(),
                    "post_type" => WPService::getAllPostTypes()
                ]);

                return WPService::mapPages($pages);
            },
            'permission_callback' => "__return_true"
        ]);
    }


    /**
     * ```/menus```
     * 
     * Dont map menu items to pages, that don't have ```post_status``` "publish".
     * 
     * @return array of nav menu objects
     */
    private function registerMenus(): void {

        register_rest_route(parent::getMapping(), "/menus", [
            "methods"=> "GET",
            "callback"=> function() {

                $menus = get_terms(["taxonomy" => "nav_menu"]);

                foreach ($menus as $menu) {
                    $navMenuItems = wp_get_nav_menu_items($menu->term_id); 

                    // assign nav items
                    $menu->items = !$navMenuItems ? [] : $navMenuItems;

                    // adjust menu items
                    for ($i = 0; $i < sizeof($navMenuItems); $i++) {
                        $item = $navMenuItems[$i];
                        $itemPage = get_post($item->object_id);

                        // case: page not public
                        if ($itemPage && $itemPage->post_status !== "publish") {
                            unset($menu->items[$i]);
                            continue;
                        }

                        $item->url = str_replace($_ENV["BASE_URL"], "", $item->url);
                        $item->isInternalLink = isUrlInternal($item->url);
                    }
                }

                return $menus;
            },
            'permission_callback' => "__return_true"
        ]);
    }


    /**
     * ```/postTypes```
     * 
     * @return array of public post type names
     */
    private function registerPostTypes(): void {

        register_rest_route(parent::getMapping(), "/postTypes", [
            "methods"=> "GET",
            "callback"=> function() {

                return WPService::getAllPostTypes();
            },
            'permission_callback' => "__return_true"
        ]);
    }


    /**
     * ```/validateUser```
     * 
     * Expect ```email``` and decrypted ```password``` inside request body (both required). Email may as well be the user name.
     *  
     * @return WP_Error 400 in case of missing prop. See ```WPService::validateUser()``` for all other resopnses.
     */
    private function registerValidateUser(): void {

        register_rest_route(parent::getMapping(), "/validateUser", [
            "methods"=> "POST",
            "callback"=> function(WP_REST_Request $requestBody) {

                $emailOrUserName = $requestBody["email"];
                $password = $requestBody["password"];

                // case: missing a prop
                if (empty($emailOrUserName) || empty($password))
                    return HttpResponse::asRestResponse(400, "Bad Request", "Missing either email or password prop", $requestBody->get_route());

                return WPService::validateUser($emailOrUserName, $password, $requestBody->get_route());
            },
            'permission_callback' => "__return_true"
        ]);
    }
}


/** 
 * String that is appended to any customized ```/wp-json/wp/v2/``` call. 
 * 
 * E.g. ```/wp-json/wp/v2/{WP_JSON_CUSTOM_MAPPING}/myEndpoint```
 * */
const WP_JSON_CUSTOM_MAPPING = "custom";