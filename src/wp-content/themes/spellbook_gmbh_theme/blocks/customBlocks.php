<?php

use Carbon_Fields\Field\Field;
use SpellbookGmbhTheme\Blocks\CustomBlockWrapper;
use SpellbookGmbhTheme\Helpers\Utils;
use SpellbookGmbhTheme\Services\WPService;

/**
 * Only add custom blocks here. Make each of them return Field[]
 */


/**
 * Add all custom blocks in here.
 * 
 * @return array `[blockNameWithinBackend => block]`
 */
function customBlocks(): array {
    return [
        "separator" => CustomBlockWrapper::builder()
            ->blockTitle("Trenner")
            ->description("Trenne Abschnitte mit einem icon oder einer horizontalen Linie")
            ->fields([
                Field::factory("select", "type", __("Typ"))
                    ->add_options([
                        ...noSelectOption(),
                        "line" => __("Horizontale Linie"),
                        "yugioh" => __("Yugioh Icon"),
                        "magic" => __("Magic Icon"),
                        "pokemon" => __("Pokemon Icon")
                    ])
            ])
            ->icon("minus")
            ->build(),

        "richText" => CustomBlockWrapper::builder()
            ->blockTitle("Text")
            ->description("Normaler Text mit style Optionen")
            ->fields([
                Field::factory("rich_text", "rich-text", __("Text"))
            ])
            ->icon("text")
            ->build(),

        "buttonLink" => CustomBlockWrapper::builder()
            ->blockTitle("Button Link")
            ->description("Button, der zu einer anderen Seite weiterleitet")
            ->fields([
                Field::factory("text", "label", __("Button Text"))
                    ->set_required(true),
                ...linkFields(),
            ])
            ->icon("button")
            ->build()
    ];
}

/**
 * Always make select inputs required to force a user selection since input values are not set on load without user selection.
 *  
 * @return Field[] 
 */
function linkFields(): array {
    $scopeFieldName = "scope";
    $scopeFieldValueInternal = "internal";
    $scopeFieldValueExternal = "external";

    return [
        Field::factory("select", $scopeFieldName, "Link Typ")
            ->add_options([
                ...noSelectOption(),
                $scopeFieldValueInternal => "Interne Seite",
                $scopeFieldValueExternal => "Externe URL"
            ])
            ->set_required(true),

        // internal path
        Field::factory("select", "internal-page", "Interne Seite")
            ->add_options("mapInternalPageOptions")
            ->set_conditional_logic( array(
                array(
                    'field' => $scopeFieldName,
                    'value' => $scopeFieldValueInternal,
                )
            ))
            ->set_required(true),

        // external url
        Field::factory("text", "external-url", "Externe URL")
            ->set_attribute("placeholder", "https://...")
            ->set_conditional_logic( array(
                array(
                    'field' => $scopeFieldName,
                    'value' => $scopeFieldValueExternal,
                )
            ))
            ->set_required(true),

        Field::factory("checkbox", "open-in-new-tab", "In neuem Tab öffnen")
    ];
}

/**
 * @return array `[pagePath => pageTitle]` for select options
 */
function mapInternalPageOptions(): array {
    $internalPagesOptions = noSelectOption();

    foreach (WPService::getPublicPages() as $publicPage)
        $internalPagesOptions[WPService::formatPagePath($publicPage)] = $publicPage->post_title;

    return $internalPagesOptions;
}

/**
 * @return Field select field with all theme colors 
 * @see `Utils.php` color constants
 */
function selectColorsField(): Field {
    return Field::factory("select", "colors", __("Farbe"))
        ->add_options([
            ...noSelectOption(),
            Utils::PRIMARY_COLOR_HEX => "Blau (theme)",
            Utils::SECONDARY_COLOR_HEX => "Weiß",
            Utils::ACCENT_COLOR_HEX => "Schwarz"
        ])
        ->set_required(true);
}

function noSelectOption(): array {
    return ["" => "Auswählen..."];
}