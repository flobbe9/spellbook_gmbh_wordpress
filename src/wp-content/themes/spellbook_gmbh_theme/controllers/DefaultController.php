<?php
namespace SpellbookGmbhTheme\Controllers;

use SpellbookGmbhTheme\Abstracts\AbstractController;
use SpellbookGmbhTheme\Dto\NavigationMenu;
use SpellbookGmbhTheme\Helpers\Utils;
use SpellbookGmbhTheme\Services\WPService;
use WP_Post;
use WP_REST_Request;

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
        $this->registerNavigationLinks();
    }

    /**
     * @return NavigationMenu[] all menus except the `NAVIGATION_LINKS_MENU_NAME` menu
     */
    private function registerMenus(): void {
        $this->registerRoute(
            ["GET"],
            "/menus",
            function() {
                $menus = get_terms(["taxonomy" => "nav_menu"]);
                $navigationMenus = [];

                foreach ($menus as $menu) {
                    $navigationMenu = NavigationMenu::parseWpObj($menu);

                    // don't make a menu for navigation links
                    if ($navigationMenu->label === NavigationMenu::NAVIGATION_LINKS_MENU_NAME)
                        continue;

                    $navigationMenus[] = $navigationMenu;
                }
                
                return $navigationMenus;
            }
        );
    }
    
    /**
     * @return NavigationMenuItem[] all menu items of `NAVIGATION_LINKS_MENU_NAME` menu
     */
    private function registerNavigationLinks(): void {
        $this->registerRoute(
            ["GET"],
            "/menus/navigationLinks",
            function() {
                $menus = get_terms(["taxonomy" => "nav_menu"]);
                $navigationMenu = [];

                foreach ($menus as $menu) {
                    $navigationMenu = NavigationMenu::parseWpObj($menu);

                    // don't make a menu for navigation links
                    if ($navigationMenu->label === NavigationMenu::NAVIGATION_LINKS_MENU_NAME) {
                        $navigationMenus = $navigationMenu;
                        break;
                    }
                }
                
                return $navigationMenus->items;
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