<?php
namespace SpellbookGmbhTheme\Dto;

use WP_Post;

/**
 * @since latest
 * @see NavigationMenu
 */
class NavigationMenuItem {
    public string $label;

    /** Either absolute url or relative path (prepend a slash in this case but don't add trailing slash) */
    public string $link;

    public LinkAttributes $linkAttributes;

    
    public function __construct(string $label, string $link, LinkAttributes $linkAttributes) {
        $this->label = $label;
        $this->link = $link;
        $this->linkAttributes = $linkAttributes;
    }

    /**
     * Convert absolute internal urls to relative ones.
     * 
     * @param WP_Post $navMenuItemPage return type of `wp_get_nav_menu_items`
     */
    public static function parseWpObj(WP_Post $navMenuItemPage): ?NavigationMenuItem {
        if (!$navMenuItemPage)
            return null;

        $label = $navMenuItemPage->title;

        $link = $navMenuItemPage->url;

        // case: internal url, convert to relative url
        if (str_contains($link, $_ENV["FRONTEND_BASE_URL"])) {
            $link = str_replace($_ENV["FRONTEND_BASE_URL"], "", $navMenuItemPage->url);
            // only starting slash
            $link = "/" . trim($link, "/");
        }

        $linkAttributes = LinkAttributes::parseWpObj($navMenuItemPage);

        return new NavigationMenuItem($label, $link, $linkAttributes);
    }
}