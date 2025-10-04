<?php
use Carbon_Fields\Carbon_Fields;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;

require_once "customBlocks.php";


/**
 * Init custom blocks.
 */
function initBlocks() {
    setAllowedBlockTypes([
        // NOTE: these dont work properly yet
        // registerImageSliderBlock()?->getBlockName(),
        // registerParallaxBlock()?->getBlockName()
    ]);
}

function initCarbonFields() {
    Carbon_Fields::boot();
}

/**
 * Set allowed block types for the current post type. 
 * 
 * @param array blockNames. List of names of blocks to allow in wp editor.
 * @see `AbstractPostType->getAllowedBlockNames`
 */
function setAllowedBlockTypes($blockNames = []): void {
    add_filter(
        "allowed_block_types_all",
        function($allowedBlocks, $context) use ($blockNames) {
            $currentPostType = $context->post->post_type;
            $postTypeInstance = AbstractPostType::getInstance($currentPostType);

            if (!$postTypeInstance) 
                return $allowedBlocks;

            return $postTypeInstance->getAllowedBlockNames($blockNames);
        },
        10,
        2
    );
}