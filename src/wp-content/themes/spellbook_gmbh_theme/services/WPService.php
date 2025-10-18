<?php
namespace SpellbookGmbhTheme\Services;

use SpellbookGmbhTheme\PostTypes\PagePostType;
use SpellbookGmbhTheme\PostTypes\TestPostType;
use WP_Post;


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
            "publish"
        ];
    }

    /**
     * @param array $options optionally pass more filter options to `get_pages`. Wont override default filters. Default is `[]`
     * @return WP_Post[] pages (not posts) visible to frontend users. 
     * @see `getPermittedPostStatuses`, `getAllPostTypes`
     */
    public static function getPublicPages(array $options = []): array {
        $pages = get_posts([
            ...$options,
            "post_type" => WPService::getAllPostTypes(),
            "post_status" => WPService::getPermittedPostStatuses()
        ]);

        return $pages ? $pages : [];
    }

    /**
     * Add blocks and path. Remove ```post_content``` since blocks are used for rendering.
     * 
     * Will set a page to "private" if its ```post_type``` is included in ```WPService::getHiddenInFrontendPostTypeNames()```.
     * 
     * @param WP_Post[] | bool $pages to map
     * @return WP_Post[] modified `$pages` array
     */
    public static function mapPages(array $pages): array {
        if (!is_array($pages)) 
            return [];

        return array_map(function(WP_Post $page) {
            // case: hide in frontend
            if (in_array($page->post_type, WPService::getHiddenInFrontendPostTypeNames()))
                WPService::makePagePrivate($page->ID);

            // get parsed blocks
            $pageBlocks = parse_blocks($page->post_content);

            return [
                "ID" => $page->ID,
                "post_date" => $page->post_date,
                "post_title" => $page->post_title,
                "post_status" => $page->post_status,
                "menu_order" => $page->menu_order,
                "post_type" => $page->post_type,

                // custom
                "path" => WPService::formatPagePath($page),
                "blocks" => $pageBlocks
            ];

        }, $pages);
    }

    /**
     * @param WP_Post $page to get path for
     * @return string|null the path visible in frontend for `$page`. Always prepend slash
     */
    public static function formatPagePath(WP_Post $page): string|null {
        if (!$page)
            return null;

        $path = "";

        // don't prepend post type for "page"
        if ($page->post_type === PagePostType::NAME) {
            // case: no home page
            if (WPService::getHomePageId() !== $page->ID)
                $path = $page->post_name;

        } else
            $path = $page->post_type . "/" . $page->post_name;

        return "/$path";
    }

    /**
     * @return int the page id of the page marked as home page in wordpress or 0 if not found
     */
    public static function getHomePageId(): int {
        return intval(get_option('page_on_front'));
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
     * @archived while "core/columns" is n ot an allowed block
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