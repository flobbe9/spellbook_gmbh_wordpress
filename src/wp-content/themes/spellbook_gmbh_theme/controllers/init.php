<?php

use SpellbookGmbhTheme\Controllers\PostTypeController;
use SpellbookGmbhTheme\Controllers\WPController;
use SpellbookGmbhTheme\PostTypes\PlayingPostType;
use SpellbookGmbhTheme\PostTypes\ShoppingPostType;


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