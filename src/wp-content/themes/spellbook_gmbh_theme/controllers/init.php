<?php

use SpellbookGmbhTheme\Controllers\DefaultController;
use SpellbookGmbhTheme\Controllers\PostTypeController;
use SpellbookGmbhTheme\PostTypes\PagePostType;
use SpellbookGmbhTheme\PostTypes\PlayingPostType;
use SpellbookGmbhTheme\PostTypes\ShoppingPostType;
use SpellbookGmbhTheme\PostTypes\TestPostType;

function initControllers(): void {

    $controller = new DefaultController("v1");
    $controller->registerAllRoutes();

    $controller = new PostTypeController(new PagePostType(), "v1");
    $controller->registerAllRoutes();

    $controller = new PostTypeController(new ShoppingPostType(), "v1");
    $controller->registerAllRoutes();

    $controller = new PostTypeController(new PlayingPostType(), "v1");
    $controller->registerAllRoutes();
    
    $controller = new PostTypeController(new TestPostType(), "v1");
    $controller->registerAllRoutes();
}