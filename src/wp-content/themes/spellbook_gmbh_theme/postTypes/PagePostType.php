<?php
namespace SpellbookGmbhTheme\PostTypes;

use Override;
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
            [
                "label" => __("Pages"),
                "public" => true,
                "hierarchical" => true,
                "show_in_rest" => true // gutenberg editor
            ]);
    }

    #[Override]
    public function getAllowedBlockTypes(): array|bool {
        return true;
    }

    public function register(): void {
        // not implemented, this is a default wordpress post type
    }
}