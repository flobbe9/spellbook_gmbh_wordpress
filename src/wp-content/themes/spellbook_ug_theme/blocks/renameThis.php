<?php

function setAllowedBlockTypes() {

    add_filter(
        "allowed_block_types_all",
        function() {
            return [
                // "core/heading"
            ];
        });
}


function createCustomBlock() {

    // $block = Block::make("customBlock");
    // if (!$block instanceof Block_Container)
    //     return;

    // $block->add_fields([
    //     Field::make("textarea", "title", "donno");
    // ]);

    // $block->set_render_callback(function($fields, $attributes, $inner_blocks) {

    //     $_GET["fields"] = $fields;
    //     // require block view
    // });
}