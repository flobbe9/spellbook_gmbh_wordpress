<?php
require_once "AbstractPostType.php";


class ShoppingPostType extends AbstractPostType {

    public function __construct() {

        parent::__construct(
            "kaufen",
            "v1",
             [
                "label" => "Kaufen",
                "public" => true,
                "hierarchical" => true,
                "menu_position" => 4,
            ]);
    }


    public function register(): void {

        register_post_type(parent::getName(), parent::getOptions());
    }
}