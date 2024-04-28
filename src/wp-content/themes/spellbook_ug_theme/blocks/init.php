<?php
use Carbon_Fields\Carbon_Fields;
require_once "customBlocks.php";


/**
 * Init custom blocks.
 */
function initBlocks() {
    
    // do this before adding fields and blocks
    initCarbonFields();
    
    // init all custom blocks
    initImageSliderBlock();
    
    // setAllowedBlockTypes();
}


function initCarbonFields() {
    
    Carbon_Fields::boot();
}