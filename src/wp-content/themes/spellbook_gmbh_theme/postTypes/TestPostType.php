<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractPostType.php";


/**
 * Custom post type dedicated for testing only. Will be kept private by ```WPService::mapPages```.
 * 
 * @since 0.0.1
 */
class TestPostType extends AbstractPostType {

    public function __construct() {

        parent::__construct(
            "test", 
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


    public function register(): void {

        register_post_type(parent::getName(), parent::getOptions());
    }
}