<?php
require_once "ShoppingPostType.php";
require_once "PlayingPostType.php";


function initPostTypes(): void {

    $playingPostType = new PlayingPostType();
    $playingPostType->register();

    $shoppingPostType = new ShoppingPostType();
    $shoppingPostType->register();
}