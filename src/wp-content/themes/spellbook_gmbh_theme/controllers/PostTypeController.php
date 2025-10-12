<?php
namespace SpellbookGmbhTheme\Controllers;

use SpellbookGmbhTheme\Abstracts\AbstractController;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;
use SpellbookGmbhTheme\Dto\CustomResponseFormat;
use SpellbookGmbhTheme\Helpers\Utils;
use SpellbookGmbhTheme\Services\WPService;
use WP_REST_Request;

/**
 * @since 0.0.1
 */
class PostTypeController extends AbstractController {

    public function __construct(AbstractPostType $postType, string $version) {
        parent::__construct(parent::THEME_NAME_SPACE, $version, $postType->getName());
    }

    public function registerAllRoutes(): void {
        $this->registerGetBySlug();    
    }

    /**
     * Leave the "slug" param blank to get the "/" slug page.
     * 
     * @throws 400 if "slug" param is missing completely, 404 if page not found
     */
    public function registerGetBySlug(): void {
        $this->registerRoute(
            ["GET"],
            "getBySlug",
            function(WP_REST_Request $request) {

                if (!$request->has_param('slug'))
                    return CustomResponseFormat::asRestResponse(400, "Missing required param 'slug'", $request);

                $slug = $request->get_param('slug');
                $page = get_page_by_path($slug, OBJECT, $this->getPostType());

                if (!$page)
                    return CustomResponseFormat::asRestResponse(404, "Could not find page with slug '$slug'", $request);

                return WPService::mapPages([$page])[0];
            }
        );
    }

    private function getPostType(): string {
        return $this->getRequestMapping();
    }
}