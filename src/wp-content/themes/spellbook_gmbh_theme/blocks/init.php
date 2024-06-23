<?php
use Carbon_Fields\Carbon_Fields;
require_once "customBlocks.php";
require_once dirname(__DIR__, 1) . "/services/WPService.php";


/**
 * Init custom blocks.
 */
function initBlocks() {

    registerImageSliderBlock();
    registerParallaxBlock();
    
    setAllowedBlockTypes();
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
                "core/heading",
                "core/image",
                "core/paragraph",
                "core/columns",
                "core/spacer",
                "carbon-fields/image-slider",
                "carbon-fields/hintergrund-bild",
                ...$blockNames
            ];
        },
        10,
        2
    );
}