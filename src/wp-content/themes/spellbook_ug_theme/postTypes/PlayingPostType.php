<?php
require_once "AbstractPostType.php";


class PlayingPostType extends AbstractPostType {

    public function __construct() {

        parent::__construct(
            "spielen", 
            "v1",
            [
                "label" => "Spielen",
                "public" => true,
                "hierarchical" => true,
                "menu_position" => 4,
            ]);
    }


    public function register(): void {

        register_post_type(parent::getName(), parent::getOptions());
    }
}