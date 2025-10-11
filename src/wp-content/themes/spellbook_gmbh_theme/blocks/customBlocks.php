<?php

use Carbon_Fields\Field\Field;
use SpellbookGmbhTheme\Blocks\CustomBlockWrapper;

/**
 * Only add custom blocks here. Make each of them return Field[]
 */

/**
 * Add all custom blocks in here.
 * 
 * @return array `[blockNameWithinBackend => block]
 */
function customBlocks(): array {
    return [
        "separatorBlock" => new CustomBlockWrapper(
            // type select
                // yugioh
                // magic
                // pokemon
                // line
            "Trenner",
            "Beschreibung",
            [
                Field::factory("text", "color", __("Farbe"))
                    ->set_required(true)
            ]
        ),
    ];
}