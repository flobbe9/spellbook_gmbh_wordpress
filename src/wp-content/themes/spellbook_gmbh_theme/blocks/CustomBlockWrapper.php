<?php
namespace SpellbookGmbhTheme\Blocks;

use Carbon_Fields\Container\Block_Container;
require_once dirname(__DIR__, 1) . "/helpers/Utils.php";


/**
 * Wrapper class containing a carbon-fields block and it's title. 
 * 
 * @since latest
 */
class CustomBlockWrapper {

    /** The carbon fields block */
    public Block_Container $block;
    /** Name of this block visible to the user */
    public string $blockTitle;
    /** The first part of the block type name. E.g. in "core/paragraph" the blockTypeCategory is "core" */
    public string $blockTypeCategory;


    public function __construct(Block_Container $block, string $blockTitle, string $blockTypeCategory) {

        $this->block = $block;
        $this->blockTitle = $blockTitle;
        $this->blockTypeCategory = $blockTypeCategory;
    }


    /**
     * @return string the ```blockTypeCategory``` concatenated with the modified ```blockTitle```.
     * 
     *                E.g. "carbon-fields/my-paragraph" with "carbon-fields" beeing the ```blockTypeCategory``` and "my-paragraph" beeing the
     *                modified ```blockTitle``` derived from "My Paragraph"
     */
    public function getBlockName(): string {

        if (isBlank($this->blockTitle) || isBlank($this->blockTypeCategory))
            return "";

        // to lowercase and replace " " with "-" 
        $modifiedBlockTitle = str_replace(" ", "-", strtolower($this->blockTitle));

        return "{$this->blockTypeCategory}/$modifiedBlockTitle";
    }
}