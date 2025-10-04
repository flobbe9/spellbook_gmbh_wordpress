<?php
namespace SpellbookGmbhTheme\Services;

use HttpResponse;
use SpellbookGmbhTheme\PostTypes\TestPostType;
use WP_Post;
use WP_REST_Response;

require_once dirname(__DIR__, 1) ."/helpers/Utils.php";


/**
 * Class defining some more logic that is mainly used inside {@link WPController}.
 * 
 * @since 0.0.1
 */
class WPService {

    /**
     * Get list of all publicly registered post types.
     * 
     * @param string $format possible values are "objects" and "names".
     */
    public static function getAllPostTypes(string $format = "names"): array {

        $allPostTypes = get_post_types(
            [
                "public" => true, // is page post type
                "hierarchical" => true // is page
            ]
            , $format);

        return $allPostTypes;
    }


    public static function getHiddenInFrontendPostTypeNames(): array {

        $testPostType = new TestPostType();

        return [
            "post",
            $testPostType->getName()
        ];
    }


    public static function getPostTypeNamesHiddenInMenu(): array {

        return [
            "post"
        ];
    }


    /**
     * @return string[] post_statuses of posts that should be displayed in rest api
     */
    public static function getPermittedPostStatuses(): array {

        return [
            "publish",
            "private"
        ];
    }


    /**
     * Add blocks and path. Remove ```post_content``` since blocks are used for rendering.
     * 
     * Will set a page to "private" if its ```post_type``` is included in ```WPService::getHiddenInFrontendPostTypeNames()```.
     * 
     * @param WP_Post[] | bool $pages to map
     * @return WP_Post[] same $pages array but with parsed blocks ("blocks") and paths ("path"). Dont append or prepend "/" to "path".
     */
    public static function mapPages(array $pages): array {

        if (!is_array($pages)) 
            return [];

        $frontPageId = intval(get_option('page_on_front'));

        return array_map(function(WP_Post $page) use ($frontPageId) {
            // case: hide in frontend
            if (in_array($page->post_type, WPService::getHiddenInFrontendPostTypeNames()))
                WPService::makePagePrivate($page->ID);

            // get parsed blocks
            $pageBlocks = parse_blocks($page->post_content);
            // modify a bit
            $pageBlocks = WPService::addIndexToColumnBlocks($pageBlocks);
            // add to page
            $page->blocks = $pageBlocks;

            // add path
            if ($page->post_type === "page") {
                // case: is front page
                if ($frontPageId === $page->ID)
                    $page->path = "";
                
                // dont use "page" post_type in path
                else
                    $page->path = $page->post_name;

            } else
                $page->path = $page->post_type . "/" . $page->post_name;

            // remove html content (using blocks only)
            unset($page->post_content);

            return $page;

        }, $pages);
    }


    /**
     * Validate given user credentials and user role.
     * 
     * @param $emailOrUserName email or user name of user
     * @param $password decrypted
     * @param $path path of the request (anything after base url)
     * @param $role user role that is required. Default is "administrator"
     * @return WP_REST_Response containing the http status code depending on given params:
     * 
     *             ```406```: no user with this email or user name
     * 
     *             ```401```: wrong password
     * 
     *             ```403```: user does not have given role
     * 
     *             ```200```: all good
     */
    public static function validateUser(string $emailOrUserName, string $decryptedPassword, $path = "", string $role = "administrator"): WP_REST_Response {

        $user = get_user_by("email", $emailOrUserName);

        // case: no user with this email
        if (empty($user)) {
            // case: no user with this userName
            if (empty($user = get_user_by("slug", $emailOrUserName)))
                return HttpResponse::asRestResponse(406, 'User invalid', 'Could not find user with given email or user name', $path);
        }

        // case: wrong password
        if (!wp_check_password($decryptedPassword, $user->user_pass))
        return HttpResponse::asRestResponse(401, 'User invalid', 'Wrong password', $path );

        // case: not an administrator
        if (!in_array($role, $user->roles))
            return HttpResponse::asRestResponse(403, 'User invalid', 'Forbidden', $path);

        return HttpResponse::asRestResponse(200, null, "User valid", $path);
    }


    /**
     * Update given post if present in db and setting ```post_status``` to "private". 
     * 
     * @param int $pageId id of post
     */
    private static function makePagePrivate(int $pageId): void {

        $page = get_post($pageId);
        // case: no page with this id
        if (!$page)
            return;

        // case: already private
        if ($page->post_status === "private")
            return;

        // get page by id first
        wp_update_post([
            "ID" => $pageId,
            "post_status" => "private"
        ]);
    }


    /**
     * @param array $blocks parsed blocks of a page (e.g. retrieved by ```WP_Post->parse_blocks```)
     */
    private static function addIndexToColumnBlocks(array $blocks): array {

        // case: falsy param
        if (!is_array($blocks))
            return $blocks;

        foreach ($blocks as $index => $block) {
            // find core/columns block
            if ($blocks[$index]["blockName"] !== "core/columns")
                continue;

            $innerBlocks = $block["innerBlocks"];
            
            // count core/column blocks
            $columnBlockCount = 0;
            foreach ($innerBlocks as $innerBlockIndex => $innerBlock)
                if ($innerBlock["blockName"] === "core/column")
                    $columnBlockCount++;

            foreach ($innerBlocks as $innerBlockIndex => $innerBlock) {
                // find core/column block
                if ($innerBlock["blockName"] !== "core/column")
                    continue;

                // assign columnBlock->attrs->columnIndex
                $blocks[$index]["innerBlocks"][$innerBlockIndex]["attrs"]["columnIndex"] = $innerBlockIndex;
                // assing columnBlock->attrs->totalNumColumnBlocks
                $blocks[$index]["innerBlocks"][$innerBlockIndex]["attrs"]["totalNumColumnBlocks"] = $columnBlockCount;
            }
        }

        return $blocks;
    }
}