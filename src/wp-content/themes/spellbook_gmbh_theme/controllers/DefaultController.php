<?php
namespace SpellbookGmbhTheme\Controllers;

use CustomResponseFormat;
use SpellbookGmbhTheme\Abstracts\AbstractController;
use SpellbookGmbhTheme\Services\WPService;
use WP_Post;
use WP_REST_Request;

require_once dirname(__DIR__, 1) ."/helpers/Utils.php";
require_once dirname(__DIR__, 1) ."/helpers/SiteMapGenerator.php";


/**
 * General endpoints unrelated to specific post types
 * 
 * @since latest
 */
class DefaultController extends AbstractController {

    public function __construct(string $version) {
        parent::__construct(parent::THEME_NAME_SPACE, $version);
    }

    /**
     * Register all routes in this method.
     */
    public function registerAllRoutes(): void {
        $this->registerSlugs();
        $this->registerMenus();
    }

    /**
     * Dont map menu items to pages, that don't have ```post_status``` "publish".
     */
    private function registerMenus(): void {
        $this->registerRoute(
            ["GET"],
            "/menus",
            function() {
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
            }
        );
    }

    /**
     * @return string[] list of page slugs of all public pages. Includes the post type (except for "page") and prepends a slash but does not 
     * contain a trailing slash
     */
    private function registerSlugs(): void {
        $this->registerRoute(
            ["GET"],
            "slugs",
            function(WP_REST_Request $request) {
                $publicPages = WPService::getPublicPages();

                return array_map(
                    function(WP_Post $page) {
                        return WPService::formatPagePath($page);
                    }, $publicPages
                );
            }
        );
    }
}