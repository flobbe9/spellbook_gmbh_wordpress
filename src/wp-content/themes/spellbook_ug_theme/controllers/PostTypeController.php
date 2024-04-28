<?php
require_once dirname(__DIR__, 1) . "/abstracts/AbstractController.php";


class PostTypeController extends AbstractController {

    public function __construct(AbstractPostType $postType, $requestMapping = "") {

        parent::__construct($postType->getName(), $postType->getVersion(), $requestMapping);
    }


    public function register(): void {

        register_rest_route(parent::getMapping(), "/pages", [
            "methdos" => "GET",
            "callback" => function() {

                $pages = get_pages([
                    "post_type" => $this->getPostTypeName()
                ]);
                
                foreach ($pages as $page) 
                    $page->blocks = parse_blocks($page->post_content);
        
                return $pages;
            }
        ]);
    }
}