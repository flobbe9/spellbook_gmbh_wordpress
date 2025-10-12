<?php
namespace SpellbookGmbhTheme\Abstracts;

use SpellbookGmbhTheme\PostTypes\PagePostType;
use SpellbookGmbhTheme\PostTypes\PlayingPostType;
use SpellbookGmbhTheme\PostTypes\ShoppingPostType;
use SpellbookGmbhTheme\PostTypes\TestPostType;
use Carbon_Fields\Block;
use SpellbookGmbhTheme\Blocks\CustomBlockWrapper;
require_once dirname(__DIR__, 1) . "/blocks/customBlocks.php";
require_once dirname(__DIR__, 1) . "/blocks/init.php";


/**
 * Abstract super class for any custom PostyType class.
 * 
 * @since 0.0.1
 */
abstract class AbstractPostType {

    /** The slug of the post type */
    private string $name;

    /** Options to pass to `register_post_type`. Default is `[]` */
    private array $options;


    public function __construct(string $name, array $options = []) {
        $this->name = $name;
        $this->options = $options;
    }


    public function getName(): string {
        return $this->name;
    }
    

    public function setName(string $name): void {
        $this->name = $name;
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

    /**
     * Register post type in wordpress. Only call this once in `init` hook
     */
    function register(): void {
        register_post_type($this->name, $this->options);
    }

    /**
     * The blockname should look like `core/columns` or `carbon-fields/myCustomBlock`.
     * 
     * Get all custom blocks with
     * 
     * ```
     * require_once dirname(__DIR__, 1) . "/blocks/customBlocks.php";
     * 
     * return array_map(
     *     function(CustomBlockWrapper $block) {
     *         return $block->getBlockType();
     *     },
     *     array_values(customBlocks())
     * );
     * ```
     * 
     * @return string[]|bool list of all block types that should be allowed for this post type in gutenberg editor or `true` in order to allow all.
     * Allow all custom blocks and `allowedGutenBergBlocks` by default
     */
    public function getAllowedBlockTypes(): array|bool {
        return array_map(
            function(CustomBlockWrapper|string $block) {
                if (is_string($block))
                    return $block;
                return $block->getBlockType();
            },
            [...array_values(customBlocks()), ...allowedGutenBergBlocks()]
        );
    }
}