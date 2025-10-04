<?php

use SpellbookGmbhTheme\PostTypes\ShoppingPostType;
use SpellbookGmbhTheme\PostTypes\PlayingPostType;
use SpellbookGmbhTheme\PostTypes\TestPostType;

function initPostTypes(): void {

    $playingPostType = new PlayingPostType();
    $playingPostType->register();

    $shoppingPostType = new ShoppingPostType();
    $shoppingPostType->register();

    $devPostType = new TestPostType();
    $devPostType->register();
}