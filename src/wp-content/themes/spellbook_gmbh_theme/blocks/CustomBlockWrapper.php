<?php
namespace SpellbookGmbhTheme\Blocks;

use Carbon_Fields\Block;
use Carbon_Fields\Container\Block_Container;
use Carbon_Fields\Field\Field;
use Error;
use SpellbookGmbhTheme\Helpers\Utils;

/**
 * Wrapper class containing a carbon-fields block and it's title. 
 * 
 * NOTE: optional fields cannot have a type for some reason
 * 
 * Naming:
 * 
 * `blockType`: "carbon-fields/myBlock"
 * 
 * `blockName`: "myBlock"
 * 
 * `blockTypeCategory`: "carbon-fields"
 * 
 * @since 0.2.3
 */
class CustomBlockWrapper {

    /** 
     * The carbon fields block.
     * 
     * Required
     * 
     * @var Field[]
     */
    public array $fields;

    /** 
     * Name of this block visible to the user. Will be used for the `blockname`
     * 
     * Required
     */
    public string $blockTitle;

    /** 
     * Short explanation for user 
     * 
     * Required
     */
    public string $description;

    /**
     * Will show when hovering the custom block in gutenberg editor.
     * 
     * Possible args: `$fields, $attributes, $inner_blocks`
     * 
     * Optional
     * 
     * @var callable|null
     */
    public $previewCallback;

    /**
     * Visible in gutenberg editor when selecting the block. Adds className "dashicon-{iconString}" to the block select button.
     * 
     * Optional
     * 
     * @var string
     * @see https://developer.wordpress.org/resource/dashicons/#rest-api
     */
    public $icon;


    public function __construct() {
        // use builder() instead
    }

    public static function builder(): CustomBlockWrapperBuilder {
        return new CustomBlockWrapperBuilder();
    }

    /**
     * @return string the ```CARBON_FIELDS_BLOCK_TYPE_CATEGORY``` concatenated with the modified ```blockTitle```.
     */
    public function getBlockType(): string {
        if (Utils::isBlank($this->blockTitle))
            return "";

        // to lowercase and replace " " with "-" 
        $modifiedBlockTitle = CustomBlockWrapper::parseName($this->blockTitle);

        return Utils::CARBON_FIELDS_BLOCK_TYPE_CATEGORY . "/$modifiedBlockTitle";
    }

    /**
     * Register this block in wordpress. This should only be called once using the `carbon_fields_register_fields` hook.
     * 
     * @throws Error if `blockTitle` is blank or `fields` is empty
     */
    public function register(): void {
        if (!is_string($this->blockTitle))
            throw new Error("Cannot register custom block without title");

        if (!is_array($this->fields) || count($this->fields) == 0)
            throw new Error("Cannot register custom block without fields");

        $block = Block::make($this->blockTitle);

        // case: wrong block class (should not happen)
        if (!$block instanceof Block_Container)
            throw new Error("Block::make did not create an instance of Block_Container for some reasons...");

        $block->add_fields($this->fields);
        $block->set_description($this->description);

        if ($this->previewCallback)
            $block->set_mode("preview");
        $block->set_render_callback($this->previewCallback ?? function($fields, $attributes, $inner_blocks) {});

        $block->set_icon($this->icon);
    }

    /**
     * Lower-case and repalce whitespace with dashes. Valid for both blocks and fields
     * 
     * @param string $string to format
     * @return string slightly modified `$string`
     */
    public static function parseName(string $string): string {
        Utils::assertNotNullBlankOrThrow($string);

        $string = str_replace(" ", "-", strtolower($string));

        return $string;
    }
}