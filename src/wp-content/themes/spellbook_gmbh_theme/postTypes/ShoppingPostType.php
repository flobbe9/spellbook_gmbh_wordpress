<?php
namespace SpellbookGmbhTheme\PostTypes;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;


/**
 * Custom post type reachable under ```/kaufen/v1/```.
 * 
 * @since 0.0.1
 * @deprecated marked for removal once all pages are moved to new "page" post type
 */
class ShoppingPostType extends AbstractPostType {

    const NAME = "kaufen";

    public function __construct() {
        parent::__construct(
            ShoppingPostType::NAME,
             [
                "label" => __("Kaufen"),
                "public" => true,
                "hierarchical" => true,
                "show_in_rest" => true // pretty edit view
            ]);
    }

    public function getAllowedBlockNames($customBlockNames = []): ?array {
        return [
            "core/columns",
            "core/heading",
            "core/image",
            "core/list",
            "core/list-item",
            "core/paragraph",
            "core/spacer",
            "core/separator",
        ];
    }

    public function register(): void {

        register_post_type(parent::getName(), parent::getOptions());
    }
}