<?php


/**
 * Abstract super class for any custom PostyType class.
 * 
 * @since 0.0.1
 */
abstract class AbstractPostType {

    private string $name;

    private string $version;

    private array $options;


    public function __construct(string $name, string $version, array $options = []) {

        $this->name = $name;
        $this->version = $version;
        $this->options = $options;
    }


    public function getName(): string {

        return $this->name;
    }
    

    public function setName(string $name): void {

        $this->name = $name;
    }
        
    
    public function getVersion(): string {
        
        return $this->version;
    }


    public function setVersion(string $version): void {

        $this->version = $version;
    }


    public function getOptions(): array {

        return $this->options;
    }
    

    public function setOptions(array $options): void {

        $this->options = $options;
    }


    abstract function register(): void;
}