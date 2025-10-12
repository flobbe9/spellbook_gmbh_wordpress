<?php
namespace SpellbookGmbhTheme\PostTypes;

use Override;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;


/**
 * @since 0.0.1
 * @deprecated marked for removal once all pages are moved to new "page" post type
 */
class PlayingPostType extends AbstractPostType {

    const NAME = "spielen";

    public function __construct() {
        parent::__construct(
            PlayingPostType::NAME, 
            [
                "label" => __("Spielen"),
                "public" => true,
                "hierarchical" => true, // page instead of post
                "show_in_rest" => true // pretty edit view
            ]);
    }
}