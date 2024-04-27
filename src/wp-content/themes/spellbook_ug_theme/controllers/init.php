<?php
require_once "WPController.php";
require_once "PostTypeController.php";
require_once dirname(__DIR__, 1) . "/postTypes/ShoppingPostType.php";
require_once dirname(__DIR__, 1) . "/postTypes/PlayingPostType.php";


function initControllers(): void {

    // /wp/v2
    $wpController = new WPController();
    $wpController->register();

    // /kaufen/v1
    $shoppingController = new PostTypeController(new ShoppingPostType());
    $shoppingController->register();

    // /spielen/v1
    $playingController = new PostTypeController(new PlayingPostType());
    $playingController->register();
}