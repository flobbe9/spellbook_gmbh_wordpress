<?php
namespace SpellbookGmbhTheme\PostTypes;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;


/**
 * Custom post type dedicated for testing only. Will be kept private by ```WPService::mapPages```.
 * 
 * @since 0.0.1
 * @deprecated marked for removal once all pages are moved to new "page" post type
 */
class TestPostType extends AbstractPostType {
    
    const NAME = "test";

    public function __construct() {

        parent::__construct(
            TestPostType::NAME, 
            "v1",
            [
                "label" => __("Testseiten"),
                "description" => __("."),
                "public" => true,
                "hierarchical" => true, // page instead of post
                "show_in_rest" => true, // enable gutenberg editor

                "register_meta_box_cb" => function() { // note at bottom of edit page
                    add_meta_box("metaBoxId", "Seiten unter 'Testseiten' bleiben dauerhaft 'privat'. Für normale Benutzer der Webseite werden sie also nie sichtbar werden.
                                               Du kannst sie dir aber anschauen, indem du dich auf der Webseite mit deinem Admin Account einloggst.", function() {});
                }
            ]
        );
    }

    public function getAllowedBlockNames($customBlockNames = []): ?array {
        return null;
    }


    public function register(): void {

        register_post_type(parent::getName(), parent::getOptions());
    }
}