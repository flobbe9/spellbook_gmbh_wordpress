<?php
require_once "ShoppingPostType.php";
require_once "PlayingPostType.php";


function initPostTypes(): void {

    $shoppingPostType = new ShoppingPostType();
    $shoppingPostType->register();

    $playingPostType = new PlayingPostType();
    $playingPostType->register();
}