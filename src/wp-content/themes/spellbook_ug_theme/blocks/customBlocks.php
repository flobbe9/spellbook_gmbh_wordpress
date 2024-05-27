<?php
require_once (dirname(__DIR__, 1) . "/views/ThemeSettings.php");
use Carbon_Fields\Block;
use Carbon_Fields\Field;
use Carbon_Fields\Container\Block_Container;
/**
 * Custom carbon field blocks will get a name starting with "carbon-fields/" followed by the 
 * block name (set in ```Block::make($blockName)```) split with "-". 
 * 
 * E.g. ```Block::make(myBlock)``` would have the name "carbon-fields/my-block".
 * 
 * Notice that when changing the field structure or field names, the frontend has to be adjusted too!
 */


/**
 * Init custom block "Image Slider" for wp editor.
 */
// IDEA: consider 
    // core/cover
    // core/embed
// TODO: add layout tabbed-horizontal
function registerImageSliderBlock(): array {

    return registerCustomBlock(
        __("Image Slider"),
        [
            Field::make("complex", __("image_slider"))
                ->add_fields([
                    // image url
                    Field::make("image", __("image"))->set_value_type("url"),
                    // link to redirect to on click
                    Field::make("text", __("link")),
                    // true if link should be opened in new tab
                    Field::make("checkbox", __("open_in_new_tab"))
                ])
                ->set_layout("tabbed-horizontal")
        ],
        __("Reihe von Bildern, horizontal nebeneinander, mit Buttons zum scrollen."));
}


function registerParallaxBlock(): array {

    return registerCustomBlock(
        __("Hintergrund Bild"), 
        [
            // dont change the second arg, it's hardcoded in frontend
            Field::make("image", "image", __("Hintergrund Bild"))->set_value_type("url")
        ],
        __("Hintergrund Bild für die gesamte Seite, das aussieht, als wäre es weiter hinten im Bildschirm.
            Wird leicht transparent dargestellt. Solltest du mehrere Hintergrund Bilder einfügen, werden diese sich überlappen.
            Wähle ein Bild mit hoher Auflösung.")
    );
}


/**
 * @param string $blockName name of the block
 * @param Field[] $fields to add to the block 
 * @param string $description of the block. Default is ```""``` 
 * @return Field[] fields array passed to this function
 */
function registerCustomBlock(string $blockName, array $fields, string $description = ""): array {

    $block = Block::make(is_string($blockName) ? $blockName : __("Unnamed block"));
    // case: wrong block class
    if (!$block instanceof Block_Container)
        return [];

    $block->add_fields(is_array($fields) ? $fields : []);
    $block->set_description($description);
    $block->set_render_callback(function($fields, $attributes, $inner_blocks) {});

    return $fields;
}