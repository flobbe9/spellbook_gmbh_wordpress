<?php
namespace SpellbookGmbhTheme\PostTypes;

use SpellbookGmbhTheme\Abstracts\AbstractPostType;


/**
 * Represents the default wordpress "page" post type, should not be registered.
 * 
 * @since latest
 */
class PagePostType extends AbstractPostType {

    const NAME = "page";

    public function __construct() {
        parent::__construct(
            PagePostType::NAME,
            "v1",
            [
                "label" => __("Pages"),
                "public" => true,
                "hierarchical" => true,
                "show_in_rest" => true // gutenberg editor
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
            ...$customBlockNames
        ];
    }

    public function register(): void {
        // not implemented, this is a default wordpress post type
    }
}