<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractPostType.php";


/**
 * Custom post type reachable under ```/spielen/v1/```.
 * 
 * @since 0.0.1
 */
class PlayingPostType extends AbstractPostType {

    public function __construct() {

        parent::__construct(
            "spielen", 
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
}