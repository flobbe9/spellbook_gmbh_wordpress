<?php

use SpellbookGmbhTheme\Controllers\DefaultController;
use SpellbookGmbhTheme\Controllers\PostTypeController;
use SpellbookGmbhTheme\PostTypes\PagePostType;
use SpellbookGmbhTheme\PostTypes\PlayingPostType;
use SpellbookGmbhTheme\PostTypes\ShoppingPostType;


function initControllers(): void {

    $defaultController = new DefaultController("v1");
    $defaultController->registerAllRoutes();

    $pageController = new PostTypeController(new PagePostType(), "v1");
    $pageController->registerAllRoutes();

    $shoppingController = new PostTypeController(new ShoppingPostType(), "v1");
    $shoppingController->registerAllRoutes();

    $playingController = new PostTypeController(new PlayingPostType(), "v1");
    $playingController->registerAllRoutes();
}