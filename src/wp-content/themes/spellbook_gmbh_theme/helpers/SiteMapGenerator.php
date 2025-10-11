<?php

use SpellbookGmbhTheme\Helpers\Utils;
use SpellbookGmbhTheme\Services\WPService;


/**
 * Class handling the generation of the sitemap.xml file.
 * 
 * @since 0.1.3
 */
class SiteMapGenerator {

    /**
     * Called by ```transition_post_status``` hook. Calls ```updateSiteMap()``` if post is beeing published or unpublished.
     */
    public static function onPostStatusChange(string $new_status, string $old_status, WP_Post $post): void {

        // case: status changed from or to publish
        if ($new_status === "publish" || $old_status === "publish") 
            SiteMapGenerator::updateSiteMap();
    }

    /**
     * Write all necessary pages to file /var/www/html/sitemap.xml. Override file if already exists.
     */
    public static function updateSiteMap(): void {

        Utils::writeStringToFile(SiteMapGenerator::getXmlSitemapString(), ABSPATH . "sitemap.xml");
    }


    /**
     * @return string of full sitemap.xml text (xml formatted) containing all necessary pages
     */
    private static function getXmlSitemapString(): string {

        $mappedPages = SiteMapGenerator::mapSiteMapPageData();

        return  "<?xml version='1.0' encoding='UTF-8'?>\n" . 
                "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n" .
                    SiteMapGenerator::mapXmlUrls($mappedPages) .
                "</urlset>";
    }


    /**
     * @param array $mappedPages formatted page data array with all pages
     * @return string of ```<url>``` tags with ```loc``` and ```lastMod```
     */
    private static function mapXmlUrls(array $mappedPages): string {
        // case: falsy param
        if (!is_array($mappedPages)) {
            Utils::log("Failed to map xml urls. 'mappedPages' is not an array");
            return "";
        }

        $xmlUrlTags = "";

        foreach ($mappedPages as $mappedPage) {
            $xmlUrlTag = SiteMapGenerator::mapXmlUrl($mappedPage);

            // case: invalid url tag
            if (Utils::isBlank($xmlUrlTag))
                continue;

            $xmlUrlTags .= $xmlUrlTag;
        }

        return $xmlUrlTags;
    }


    /**
     * @param array | null $mappedPage formatted page data or ```null``` if page should be ignored
     * @return string | null ```<url>``` tag with ```loc``` and ```lastMod```, or ```null``` if no ```loc``` is present
     */
    private static function mapXmlUrl(array | null $mappedPage): string | null {

        // case: ignore this page
        if (!is_array($mappedPage))
            return null;

        $locXmlString = SiteMapGenerator::getLocXmlString($mappedPage["loc"]);

        // case: no location
        if (!$locXmlString) 
            return null;

        return "\t<url>\n" . 
                    $locXmlString .
                    SiteMapGenerator::getLastModXmlString($mappedPage["lastMod"]) . 
                "\t</url>\n";
    }


    private static function getLastModXmlString(string $lastMod): string {

        // case: falsy param
        if (Utils::isBlank($lastMod))
            $lastMod = "";

        return "\t\t<lastmod>$lastMod</lastmod>\n";
    }


    private static function getLocXmlString(string $loc): string | null {

        // case: falsy param
        if (Utils::isBlank($loc))
            return null;

        return "\t\t<loc>$loc</loc>\n";
    }


    /**
     * Iterate pages, filter relevant ones (see ```WPService::getHiddenInFrontendPostTypeNames()```) and retrieve only path and last modified date.
     * Also exclude /login page.
     * 
     * @return array 2d array formatted like ```[["loc" => $loc, "lastMod" => $lastMod], ...]``` both values beeing strings.
     * 
     * Inner array may be ```null``` if a page is skipped during iteration
     */
    private static function mapSiteMapPageData(): array {
        
        // all pages (pretty much)
        $pages = get_posts([
            "posts_per_page" => -1, // get all posts
            "post_status" => WPService::getPermittedPostStatuses(),
            "post_type" => WPService::getAllPostTypes()
        ]);

        return array_map(function(WP_Post $page) {
            // case: hide in frontend
            if (in_array($page->post_type, WPService::getHiddenInFrontendPostTypeNames()))
                return;

            // case: is login page
            if ($page->post_name === "login")
                return;

            return [
                "loc" => SiteMapGenerator::getLocByWpPage($page),
                "lastMod" => SiteMapGenerator::getLastModByWpPage($page)
            ];

        }, $pages);
    }


    /**
     * @param WP_Post $page to get path for
     * @return string path for given page as used by frontend and relative to base url. 
     * 
     * E.g the page "https://spellbook-gmbh.de/spielen/magic" would return ```spielen/magic```. No slashes will be prepended or appended. 
     */
    private static function getLocByWpPage(WP_Post $page): string {

        if (!$page) {
            Utils::log("Failed to get page path. 'page' is falsy");
            return "";
        }

        $frontPageId = intval(get_option('page_on_front'));

        $path = "";

        // case: is "page"
        if ($page->post_type === "page") {        
            // case: is not front page
            if ($frontPageId !== $page->ID)
                $path = $page->post_name;

        // case: is some "post_type"
        } else
            $path = $page->post_type . "/" . $page->post_name;  
        
        return SiteMapGenerator::getBaseUrl() . "/" . $path;
    }


    /**
     * @param WP_Post $page to get ```post_modified``` from
     * @return string last modified date of given formatted like ```yyyy-MM-dd```
     */
    private static function getLastModByWpPage(WP_Post $page): string {

        if (!$page) {
            Utils::log("Failed to get page last modified date. 'page' is falsy");
            return "";
        }

        return SiteMapGenerator::stripTimeFromDate($page->post_modified);
    }


    /**
     * @param string $timestamp to modify, expecting format ```yyyy-MM-dd HH:mm:ss```. E.g. ```2024-05-27 18:27:46```. The space separator
     *                          is the most important part.
     * @return string date without time, e.g. ```2024-05-27``` or ```""``` if error
     */
    private static function stripTimeFromDate(string $timestamp): string {

        if (Utils::isBlank($timestamp))
            return "";

        $arr = explode(" ", $timestamp);

        return count($arr) === 0 ? "" : trim($arr[0]);
    }


    /**
     * @return string ```PROTOCOL```://www.```HOST```
     */
    private static function getBaseUrl(): string {

        return $_ENV["PROTOCOL"] . "://" . $_ENV["FRONTEND_HOST"];
    }
}