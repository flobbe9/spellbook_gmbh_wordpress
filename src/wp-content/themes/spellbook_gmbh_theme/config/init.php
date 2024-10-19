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
    $frontendBaseUrlWithWWW = getFrontendBaseUrl(true);

    logg($frontendBaseUrl);
    logg($frontendBaseUrlWithWWW);

    $requestOrigin = getRequestOrigin();

    // case: url uses "www"
    if ($requestOrigin === $frontendBaseUrlWithWWW)
        return $frontendBaseUrlWithWWW;

    return $frontendBaseUrl;
}


/**
 * Get the request origin url. Try ```$["HTTP_ORIGIN"]``` ("Origin" request header) first and fallback on ```$["HTTP_REFERER"]``` ("Referer" request header),
 * cutting the trailing slash.
 * 
 * @since 0.2.2
 * @return string the request origin or a blank string
 */
function getRequestOrigin(): string {

    if (isset($_SERVER["HTTP_ORIGIN"]))
        return $_SERVER["HTTP_ORIGIN"];

    if (isset($_SERVER["HTTP_REFERER"]))
        return substr($_SERVER["HTTP_REFERER"], 0, strlen($_SERVER["HTTP_REFERER"]) - 1);
    
    return "";
}


/**
 * Get clean frontend base url optionally with "www.". Ommits ```PORT``` if is port "80" or "443".
 * 
 * @since 0.2.2
 * @param mixed $isIncludeWWW if ```true``` "www." is appended after protocol
 * @return string frontend base url
 */
function getFrontendBaseUrl($isIncludeWWW = false): string {

    $isDefaultPort = $_ENV["FRONTEND_PORT"] === "80" || $_ENV["FRONTEND_PORT"] === "443";

    if ($isIncludeWWW) {
        $port = $isDefaultPort ? "" : ":{$_ENV["FRONTEND_PORT"]}";
    
        return $_ENV['FRONTEND_PROTOCOL'] . "://www." . $_ENV['FRONTEND_HOST'] . $port;
    }

    return $isDefaultPort ? $_ENV["FRONTEND_BASE_URL_NO_PORT"] : $_ENV["FRONTEND_BASE_URL"];
}