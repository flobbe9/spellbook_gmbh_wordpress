<?php


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

        $allPostTypes = get_post_types(["public" => true], $format);
        
        return $allPostTypes;
    }


    public static function getHiddenInFrontendPostTypeNames(): array {

        $testPostType = new TestPostType();

        return [
            "post",
            $testPostType->getName()
        ];
    }


    public static function getAllowAllBlockTypesPostTypeNames(): array {

        $testPostType = new TestPostType();
        
        return [
            $testPostType->getName()
        ];
    }


    public static function getPostTypeNamesHiddenInMenu(): array {

        return [
            "post"
        ];
    }


    /**
     * Add blocks and path. Remove ```post_content``` since blocks are used for rendering.
     * 
     * Will ignore a page if its ```post_type``` is included in ```WPService::getHiddenInFrontendPostTypeNames()```.
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
                return;

            // add parsed blocks
            $page->blocks = parse_blocks($page->post_content);

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
}