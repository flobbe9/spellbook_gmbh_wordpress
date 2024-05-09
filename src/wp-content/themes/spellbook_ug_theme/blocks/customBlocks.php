<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
use Carbon_Fields\Container\Block_Container;
/**
 * Custom carbon field blocks will get a name starting with "carbon-fields/" followed by the 
 * block name (set in ```Block::make($blockName)```) split with "-". 
 * 
 * E.g. ```Block::make(myBlock)``` would have the name "carbon-fields/my-block".
 */


/**
 * Init custom block "Image Slider" for wp editor.
 */
// TODO: set number of images in the slider in theme settings
// TODO: consider 
    // core/cover
    // core/embed
function registerImageSliderBlock(): void {

    $block = Block::make("Image Slider");
    // case: wrong block class
    if (!$block instanceof Block_Container)
        return;

    // add elements
    $block->add_fields([
        Field::make("image", "image1")->set_value_type("url"),
        Field::make("image", "image2")->set_value_type("url"),
        Field::make("image", "image3")->set_value_type("url"),
        Field::make("image", "image4")->set_value_type("url"),
        Field::make("image", "image5")->set_value_type("url"),
        Field::make("image", "image6")->set_value_type("url"),
        Field::make("image", "image7")->set_value_type("url")
    ]);

    $block->set_description(__("Reihe von Bildern, horizontal nebeneinander, mit Buttons zum scrollen."));

    $block->set_render_callback(function($fields, $attributes, $inner_blocks) {
        error_log(print_r($fields, true));
    });
}