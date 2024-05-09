<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractPostType.php";


/**
 * Custom post type reachable under ```/kaufen/v1/```.
 * 
 * @since 0.0.1
 */
class ShoppingPostType extends AbstractPostType {

    public function __construct() {

        parent::__construct(
            "kaufen",
            "v1",
             [
                "label" => __("Kaufen"),
                "public" => true,
                "hierarchical" => true,
                "show_in_rest" => true // pretty edit view
            ]);
    }


    public function register(): void {

        register_post_type(parent::getName(), parent::getOptions());
    }
}