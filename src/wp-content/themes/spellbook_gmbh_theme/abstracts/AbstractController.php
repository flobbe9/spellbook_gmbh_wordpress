<?php
namespace SpellbookGmbhTheme\Abstracts;

use WP_REST_Controller;

/**
 * @since 0.0.1
 */
abstract class AbstractController extends WP_REST_Controller {

    private string $postTypeName;

    private string $version;

    private string $requestMapping;


    public function __construct(string $postTypeName, string $version, string $requestMapping = "") {

        $this->postTypeName = $postTypeName;
        $this->version = $version;
        $this->requestMapping = $requestMapping;
    }


    public function getPostTypeName(): string {

        return $this->postTypeName;
    }

    
    public function setPostTypeName(string $postTypeName): void {
        
        $this->postTypeName = $postTypeName;
    }
    
    
    public function getVersion(): string {
        
        return $this->version;
    }


    public function setVersion(string $version): void {

        $this->version = $version;
    }


    public function getRequestMapping(): string {

        return $this->requestMapping;
    }
    

    public function setRequestMapping( string $requestMapping ): void {

        $this->requestMapping = $requestMapping;
    }


    /**
     * @return string complete mapping without "/" at the start or end.
     */
    public function getMapping(): string {

        return $this->postTypeName . "/" . $this->version . ($this->requestMapping ? "/" . $this->requestMapping : "");
    }


    /**
     * Return array with all posts of ```$this->postType``` and include all used block types 
     * (using ```parse_blocks()```).
     */
    public function getPostTypePages(): bool | array {

        $pages = get_pages([
            "post_type" => $this->getPostTypeName()
        ]);
        
        foreach ($pages as $page) 
            $page->blocks = parse_blocks($page->post_content);

        return $pages;
    }


    /**
     * Call ```register_rest_route()``` to activate the custom controller.
     */
    abstract function register(): void;
}