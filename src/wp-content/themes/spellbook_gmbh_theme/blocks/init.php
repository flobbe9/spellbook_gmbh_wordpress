<?php

use Carbon_Fields\Carbon_Fields;
use SpellbookGmbhTheme\Abstracts\AbstractPostType;
use SpellbookGmbhTheme\Helpers\Utils;

require_once "customBlocks.php";


/**
 * Configure allowed block types and register custom blocks
 */
function initBlocks() {
    foreach (customBlocks() as $customBlockWrapper)
        $customBlockWrapper->register();

    configureAllowedBlockTypes();

    loadBlockAssets();
}

function initCarbonFields() {
    Carbon_Fields::boot();
}

/**
 * Set allowed block types for the current post type. 
 * 
 * @see `AbstractPostType->getAllowedBlockTypes`
 */
function configureAllowedBlockTypes(): void {
    add_filter(
        "allowed_block_types_all",
        function($allowedBlocks, $context) {
            $currentPostType = $context->post->post_type;
            $postTypeInstance = AbstractPostType::getInstance($currentPostType);
            if (!$postTypeInstance) 
                return $allowedBlocks;

            return $postTypeInstance->getAllowedBlockTypes();
        },
        10,
        2
    );
}

/**
 * @return string[]
 */
function allowedGutenBergBlocks(): array {
    return [
        "core/spacer"
    ];
}

function loadBlockAssets(): void {
    Utils::loadAsset("cfVideoBlockThumbnailFix.js", "defer"); // wait for window.wp and helpers.js to load
    Utils::loadAsset("gutenbergEditor.css");
}