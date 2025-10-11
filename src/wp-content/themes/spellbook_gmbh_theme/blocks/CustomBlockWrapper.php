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
 * @since 0.2.3
 */
class CustomBlockWrapper {

    /** 
     * The carbon fields block 
     * @var Field[]
     */
    private array $fields;

    /** Name of this block visible to the user. CAnnot be blank */
    private string $blockTitle;

    /** Short explanation for user */
    private string $description;


    public function __construct(string $blockTitle, string $description, array $fields) {
        $this->blockTitle = $blockTitle;
        $this->description = $description;
        $this->fields = $fields;
    }

    /**
     * @return string the ```CARBON_FIELDS_BLOCK_TYPE_CATEGORY``` concatenated with the modified ```blockTitle```.
     */
    public function getBlockType(): string {
        if (Utils::isBlank($this->blockTitle))
            return "";

        // to lowercase and replace " " with "-" 
        $modifiedBlockTitle = str_replace(" ", "-", strtolower($this->blockTitle));

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
        $block->set_render_callback(function($fields, $attributes, $inner_blocks) {});
    }
}