<?php
namespace SpellbookGmbhTheme\Dto;

/**
 * Refers to wordpress' taxonomy "nav_menu".
 * 
 * @since latest
 */
class NavigationMenu {

    const NAVIGATION_LINKS_MENU_NAME = 'Navigation Links';

    public string $label;

    /** @var $items NavigationMenuItem[] */
    public array $items;


    /**
     * @param string $label
     * @param NavigationMenuItem[] $items
     */
    public function __construct(string $label, array $items) {
        $this->label = $label;
        $this->items = $items;
    }

    /**
     * @param WP_Term $navMenu
     */
    public static function parseWpObj($navMenu): ?NavigationMenu {
        if (!$navMenu)
            return null;

        $label = $navMenu->name ?? "";
        $items = [];
        
        $navMenuItems = wp_get_nav_menu_items($navMenu->term_id);
        $navMenuItems = $navMenuItems ? $navMenuItems : [];
        foreach ($navMenuItems as $navMenuItem)
            $items[] = NavigationMenuItem::parseWpObj($navMenuItem);

        return new NavigationMenu($label, $items);
    }
}