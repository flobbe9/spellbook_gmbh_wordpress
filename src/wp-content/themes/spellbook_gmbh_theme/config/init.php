<?php
require_once dirname(__DIR__, 1) . "/services/WPService.php";
require_once dirname(__DIR__, 1) . "/helpers/Utils.php";


function initConfig(): void {

    deleteDefaultPlugins();
    deleteDefaultThemes();
}


/**
 * Delete plugins shipped with wp Docker image.
 */
function deleteDefaultPlugins(): void {

    deleteDir(dirname(__DIR__, 3) . "/plugins/akismet");

    $hello = dirname(__DIR__, 3) . "/plugins/hello.php";
    if (file_exists($hello))
        unlink($hello);
}


/**
 * Delete themes shipped with wp Docker image.
 */
function deleteDefaultThemes(): void {

    deleteDir(dirname(__DIR__, 2) . "/twentytwentytwo");
    deleteDir(dirname(__DIR__, 2) . "/twentytwentythree");
    deleteDir(dirname(__DIR__, 2) . "/twentytwentyfour");
}