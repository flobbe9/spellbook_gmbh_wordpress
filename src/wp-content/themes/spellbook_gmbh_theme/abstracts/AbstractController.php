<?php
namespace SpellbookGmbhTheme\Abstracts;

/**
 * @since 0.0.1
 */
abstract class AbstractController {

    const THEME_NAME_SPACE = "spellbook_gmbh";

    private string $nameSpace;

    private string $version;

    private string $requestMapping;


    public function __construct(string $nameSpace, string $version, string $requestMapping = "") {
        $this->nameSpace = $nameSpace;
        $this->version = $version;
        $this->requestMapping = $requestMapping;
    }

    public function getNameSpace(): string {
        return $this->nameSpace;
    }

    
    public function setNameSpace(string $nameSpace): void {
        
        $this->nameSpace = $nameSpace;
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
        return $this->nameSpace . "/" . $this->version . ($this->requestMapping ? "/" . $this->requestMapping : "");
    }

    public function registerRoute(array $methods, string $route, callable $callback): void {
        register_rest_route(
            $this->getMapping(), 
            $route, 
            [
                "methods" => $methods,
                "callback" => $callback,
                'permission_callback' => "__return_true"
            ]
        );
    } 

    /**
     * Call ```register_rest_route()``` to activate the custom controller.
     */
    abstract function registerAllRoutes(): void;
}