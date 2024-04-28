<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
use Carbon_Fields\Container\Block_Container;

/**
 * @param array blockNames. List of names of blocks to allow in wp editor.
 */
function setAllowedBlockTypes($blockNames = []): void {

    add_filter(
        "allowed_block_types_all",
        function() use ($blockNames) {
            return [
                // "core/heading"
                ...$blockNames
            ];
        }
    );
}


/**
 * Init custom block "Image Slider" for wp editor.
 */
function initImageSliderBlock(): void {

    $block = Block::make("Image Slider");
    // case: wrong block
    if (!$block instanceof Block_Container)
        return;

    // add elements
    $block->add_fields([
        Field::make("image", "image_slider", "Image Slider")
    ]);

    // initialize view
    $block->set_render_callback(function($fields, $attributes, $inner_blocks) {

        $_GET["fields"] = $fields;
        // require block view
    });
}