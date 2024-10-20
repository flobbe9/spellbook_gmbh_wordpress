<?php
use Carbon_Fields\Carbon_Fields;
require_once "customBlocks.php";
require_once dirname(__DIR__, 1) . "/services/WPService.php";


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
 * Lists allowed blocks.
 * 
 * Will allow all blocks if ```post_type``` is included in ```WPService::getAllowAllBlockTypesPostTypeNames()```.
 * 
 * @param array blockNames. List of names of blocks to allow in wp editor.
 */
function setAllowedBlockTypes($blockNames = []): void {

    add_filter(
        "allowed_block_types_all",
        function($allowedBlocks, $context) use ($blockNames) {
            // case: allow all blocks for this
            if (in_array($context->post->post_type, WPService::getAllowAllBlockTypesPostTypeNames()))
                return;

            return [
                "core/columns",
                "core/heading",
                "core/image",
                "core/list",
                "core/list-item",
                "core/paragraph",
                "core/spacer",
                "core/separator",
                ...$blockNames
            ];
        },
        10,
        2
    );
}