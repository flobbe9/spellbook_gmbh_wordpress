<?php
namespace SpellbookGmbhTheme\Dto;


/**
 * Wrapper for html `<a>` tag attributes other than "href", e.g. "_target" or "rel".
 * 
 * Ideally keep the field names exactly like the html attribute name.
 * 
 * @since latest
 */
class LinkAttributes {
    /** Default should be "_self" */
    public string $_target;


    public function __construct(string $_target) {
        $this->_target = $_target;
    }

    /**
     * @param WP_Post $navMenuItemPage
     */
    public static function parseWpObj($navMenuItemPage): ?LinkAttributes {
        if (!$navMenuItemPage)
            return null;

        $_target = $navMenuItemPage->target;
        if (isBlank($_target))
            $_target = "_self";

        return new LinkAttributes($_target);
    }
}