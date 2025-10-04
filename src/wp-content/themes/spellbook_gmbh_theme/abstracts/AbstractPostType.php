<?php
namespace SpellbookGmbhTheme\Abstracts;

use SpellbookGmbhTheme\PostTypes\PagePostType;
use SpellbookGmbhTheme\PostTypes\PlayingPostType;
use SpellbookGmbhTheme\PostTypes\ShoppingPostType;
use SpellbookGmbhTheme\PostTypes\TestPostType;

/**
 * Abstract super class for any custom PostyType class.
 * 
 * @since 0.0.1
 */
abstract class AbstractPostType {

    /** The slug of the post type */
    private string $name;

    /** Endpoint version */
    private string $version;

    /** Options to pass to `register_post_type`. Default is `[]` */
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

    /**
     * @param string $postTypeName `name` class field of the desired post type instance
     * @return AbstractPostType|null the empty post type instance or `null` if arg is not handled
     */
    public static function getInstance(string $postTypeName): ?AbstractPostType {
        switch ($postTypeName) {
            case PlayingPostType::NAME: 
                return new PlayingPostType();

            case ShoppingPostType::NAME: 
                return new ShoppingPostType();

            case TestPostType::NAME: 
                return new TestPostType();

            case PagePostType::NAME:
                return new PagePostType();
                    
            default:
                return null;
        }
    }


    abstract function register(): void;


    /**
     * The blockname should look like `core/columns` or `carbon-fields/myCustomBlock`.
     * 
     * @param string[] $customBlockNames list of carbon-fields block names. Default is `[]`
     * @return string[]|null list of all block names that should be allowed for this post type in gutenberg editor.
     * Return `null` to allow all, return an empty array to allow none.
     */
    abstract function getAllowedBlockNames($customBlockNames = []): array | null;
}