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
 * @fixed 0.2.2
 */
function addCors(): void {

    // decide by http origin, add "www" if necessary
    $allowedOrigin = getAllowOriginResponseHeader();

    header("Access-Control-Allow-Origin: $allowedOrigin");
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


/**
 * Get the value for response header "Access-Control-Allow-Origin". Since this can only be one domain,
 * check http origin agains frontend url to cover both "https.//..." and "https://www...." urls.
 * 
 * @since 0.2.2
 * @return string the frontend base url possibly with "www." depending on the request origin
 */
function getAllowOriginResponseHeader(): string {

    $frontendBaseUrl = getFrontendBaseUrl();
    
    // case: no origin, just use default frontend base url
    if (!isset($_SERVER["HTTP_ORIGIN"]))
        return $frontendBaseUrl;

    $frontendBaseUrlWithWWW = getFrontendBaseUrl(true);

    if ($_SERVER["HTTP_ORIGIN"] === $frontendBaseUrlWithWWW)
        return $frontendBaseUrlWithWWW;

    return $frontendBaseUrl;
}


/**
 * Get clean frontend base url optionally with "www.". Ommits ```PORT``` if is port "80" or "443".
 * 
 * @since 0.2.2
 * @param mixed $isIncludeWWW if ```true``` "www." is appended after protocol
 * @return string frontend base url
 */
function getFrontendBaseUrl($isIncludeWWW = false): string {

    // case: is default port
    if ($_ENV["PORT"] === "80" || $_ENV["PORT"] === "443")
        return $_ENV["FRONTEND_BASE_URL_NO_PORT"];

    if ($isIncludeWWW)
        return $_ENV['FRONTEND_PROTOCOL'] . "://www." . $_ENV['FRONTEND_HOST'] . ":" . $_ENV['FRONTEND_PORT'];

    return $_ENV["FRONTEND_BASE_URL"];
}