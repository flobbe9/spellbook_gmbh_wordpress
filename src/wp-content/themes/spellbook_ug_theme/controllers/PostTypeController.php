<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractController.php";
require_once dirname(__DIR__, 1) . "/services/WPService.php";


class PostTypeController extends AbstractController {

    public function __construct(AbstractPostType $postType, $requestMapping = "") {

        parent::__construct($postType->getName(), $postType->getVersion(), $requestMapping);
    }


    public function register(): void {

        register_rest_route(parent::getMapping(), "/pages", [
            "methdos" => "GET",
            "callback" => function($data) {

                $pages = get_pages([
                    "post_type" => $this->getPostTypeName()
                ]);
                
                return WPService::mapPages($pages);
            },
            'permission_callback' => "__return_true"
        ]);
    }
}