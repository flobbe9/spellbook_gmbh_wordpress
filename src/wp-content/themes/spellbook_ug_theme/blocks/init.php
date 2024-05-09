<?php
use Carbon_Fields\Carbon_Fields;
require_once "customBlocks.php";


/**
 * Init custom blocks.
 */
function initBlocks() {

    registerImageSliderBlock();
    
    setAllowedBlockTypes();
}


function initCarbonFields() {
    
    Carbon_Fields::boot();
}


/**
 * @param array blockNames. List of names of blocks to allow in wp editor.
 */
function setAllowedBlockTypes($blockNames = []): void {

    add_filter(
        "allowed_block_types_all",
        function() use ($blockNames) {
            return [
                "core/heading",
                "core/image",
                "core/paragraph",
                "core/columns",
                "carbon-fields/image-slider",
                ...$blockNames
            ];
        }
    );
}