<?php
require_once dirname(__DIR__, 1) . "/services/WPService.php";
require_once dirname(__DIR__, 1) . "/helpers/Utils.php";


function initConfig(): void {

    deleteDefaultPlugins();
    deleteDefaultThemes();
    addCors();
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


/**
 * Add cors to allow frontend url only.
 * 
 * @since 0.2.1
 */
function addCors(): void {

    $frontendBaseUrl = $_ENV["FRONTEND_PORT"] === "443" ? $_ENV["FRONTEND_BASE_URL_NO_PORT"] : $_ENV["FRONTEND_BASE_URL"];

    header("Access-Control-Allow-Origin: $frontendBaseUrl");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        // "*" does not work here, simply allow what's requested
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}"); 
    
    // case: is preflight request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
        // somehow necessary for cors to work (see https://stackoverflow.com/questions/8719276/cross-origin-request-headerscors-with-php-headers)
        exit(0);
}