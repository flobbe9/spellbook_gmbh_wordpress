<?php
namespace SpellbookGmbhTheme\PostTypes;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;


/**
 * Custom post type reachable under ```/spielen/v1/```.
 * 
 * @since 0.0.1
 * @deprecated marked for removal once all pages are moved to new "page" post type
 */
class PlayingPostType extends AbstractPostType {

    const NAME = "spielen";

    public function __construct() {
        parent::__construct(
            PlayingPostType::NAME, 
            "v1",
            [
                "label" => __("Spielen"),
                "public" => true,
                "hierarchical" => true, // page instead of post
                "show_in_rest" => true // pretty edit view
            ]);
    }

    public function register(): void {
        register_post_type(parent::getName(), parent::getOptions());
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
}