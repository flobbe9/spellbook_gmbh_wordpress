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
        "boxContainer" => CustomBlockWrapper::builder()
            ->blockTitle("Box")
            ->description("Enthält Content über die volle Bildschirmbreite, optional mit Hintergrundbild")
            ->icon("welcome-widgets-menus")
            ->fields([
                ...backgroundFields("Box Container"),
                fieldFactory("checkbox", "display-flex", "Elemente horizontal anordnen", "Box Container"),
                fieldFactory("radio", "justify-content", "Elementausrichtung", "Box Container")
                    ->add_options([
                        "center" => "Mittig",
                        "left" => "Links",
                        "right" => "Rechts"
                    ])
                    ->set_default_value("center"),
                fieldFactory("complex", "boxes", "Elemente", "Box Container")
                    ->add_fields(boxFields("Box", "Element Typ"))
                    ->set_layout("tabbed-horizontal")
            ])
            ->build(),

        "accordion" => CustomBlockWrapper::builder() 
            ->blockTitle("Accordion")
            ->description("Ausfahrbahrer Text")
            ->icon("accordion", true)
            ->fields([
                fieldFactory("complex", "header-bodies", "Accordion", "Accordion")
                    ->set_min(1)
                    ->set_layout("tabbed-horizontal")
                    ->add_fields([
                        fieldFactory("rich_text", "header", "Fixierter Text", "Accordion")
                            ->set_required(true),
                        fieldFactory("rich_text", "body", "Ausfahrbarer Text", "Accordion"),
                    ])
            ])
            ->build(),

        "slider" => CustomBlockWrapper::builder()
            ->blockTitle("Slider")
            ->description("Bilder- / Text-Gallerie in voller Breite zum sliden (horizontal)")
            ->icon("slider", true)
            ->fields([
                fieldFactory("complex", "slides", "Slides", "Slider")
                    ->set_layout("tabbed-horizontal")
                    ->add_fields([
                        fieldFactory("radio", "type", "Slide Typ", "Slide")
                            ->set_required(true)
                            ->add_options([
                                "image" => "Bild",
                                "video" => "Video",
                                "text" => "Text"
                            ]),
                        ...imageFields("Slide", "", "Bild", true, [[
                            'field' => CustomBlockWrapper::parseName("Slide type"),
                            'value' => "image"
                        ]]),
                        fieldFactory("image", "video-url", "Video", "Slide")
                            ->set_type(["video"])
                            ->set_value_type("url")
                            ->set_required(true)
                            ->set_conditional_logic([[
                                'field' => CustomBlockWrapper::parseName("Slide type"),
                                'value' => "video"
                            ]]),
                        fieldFactory("rich_text", "text", "Text", "Slide")
                            ->set_required(true)
                            ->set_conditional_logic([[
                                'field' => CustomBlockWrapper::parseName("Slide type"),
                                'value' => "text"
                            ]])
                    ])
            ])
            ->build(),

        ...simpleBlocks()
    ];
}

/** 
 * Non-complex blocks that should be able to fit into any complex block.
 * 
 * @return array `["blockNameWithinBackend" => blockWrapper]`
 */
function simpleBlocks(): array {
    return [
        "richText" => CustomBlockWrapper::builder()
            ->blockTitle("Text")
            ->description("Normaler Text mit style Optionen")
            ->fields([
                fieldFactory("rich_text", "rich-text", __("Text"), "text")
            ])
            ->icon("text")
            ->build(),

        "buttonLink" => CustomBlockWrapper::builder()
            ->blockTitle("Button Link")
            ->description("Button, der zu einer anderen Seite weiterleitet")
            ->fields([
                fieldFactory("text", "label", __("Button Text"), "Button Link")
                    ->set_required(true),
                ...linkFields("Button Link"),
                colorsField("Button Link", "background", "Hintergrundfarbe")
            ])
            ->icon("button")
            ->build(),

        "separator" => CustomBlockWrapper::builder()
            ->blockTitle("Trenner")
            ->description("Trenne Abschnitte mit einem icon oder einer horizontalen Linie")
            ->fields([
                fieldFactory("select", "type", __("Typ"), "Trenner")
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
    ];
}

/**
 * Call this instead of `Field::factory` to ensure field name uniqueness and prevent invalid field anames
 * 
 * @param string $fieldType see `Field::factory`
 * @param string $fieldName will be parsed
 * @param string $fieldLabel optional, use parsed and concatenated `fieldName` if `null`
 * @param string $blockName parsed and prepended to the `fieldName`
 */
function fieldFactory(string $fieldType, string $fieldName, string|null $fieldLabel, string $blockName): Field {
    Utils::assertNotNullBlankOrThrow($fieldType, $fieldName, 2, $blockName);

    $fieldName = CustomBlockWrapper::parseName($blockName) . "-" . CustomBlockWrapper::parseName($fieldName);

    return Field::factory($fieldType, $fieldName, $fieldLabel);
}

/**
 * Always make select inputs required to force a user selection since input values are not set on load without user selection.
 *  
 * @param string $blockName
 * @return Field[] 
 */
function linkFields(string $blockName): array {
    $scopeFieldName = "scope";
    $scopeFieldValueInternal = "internal";
    $scopeFieldValueExternal = "external";

    return [
        fieldFactory("radio", $scopeFieldName, "Link Typ", $blockName)
            ->add_options([
                $scopeFieldValueInternal => "Interne Seite",
                $scopeFieldValueExternal => "Externe URL"
            ])
            ->set_required(true),

        // internal path
        fieldFactory("select", "internal-page", "Interne Seite", $blockName)
            ->add_options("mapInternalPageOptions")
            ->set_conditional_logic( array(
                array(
                    'field' => CustomBlockWrapper::parseName($blockName) . "-" . $scopeFieldName,
                    'value' => $scopeFieldValueInternal,
                )
            ))
            ->set_required(true),

        // external url
        fieldFactory("text", "external-url", "Externe URL", $blockName)
            ->set_attribute("placeholder", "https://...")
            ->set_conditional_logic([[
                'field' => CustomBlockWrapper::parseName($blockName) . "-" . $scopeFieldName,
                'value' => $scopeFieldValueExternal,
            ]])
            ->set_required(true),

        fieldFactory("checkbox", "open-in-new-tab", "In neuem Tab öffnen", $blockName)
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
 * @param string $blockName
 * @param string $fieldNamePrefix prepend this to the field name to clarify what exactly is colored, e.g. "background"
 * @param string $label default is "Farbe"
 * @return Field select field with all theme colors 
 * @see `Utils.php` color constants
 */
function colorsField(string $blockName, string $fieldNamePrefix, $label = "Farbe"): Field {
    $fieldName = CustomBlockWrapper::parseName($fieldNamePrefix) . "-color";

    return fieldFactory("select", $fieldName, __($label), $blockName)
        ->add_options([
            ...noSelectOption(),
            Utils::PRIMARY_COLOR_HEX => "Spellbook Blau (" . Utils::PRIMARY_COLOR_HEX . ")",
            Utils::SECONDARY_COLOR_HEX => "Weiß",
            Utils::ACCENT_COLOR_HEX => "Schwarz"
        ])
        ->set_required(true);
}

/**
 * @return array
 */
function noSelectOption(): array {
    return ["" => "Auswählen..."];
}

/**
 * @param string $blockName
 * @param string $fieldNamePrefix prepend this to the field name to clarify what this image is for exactly, e.g. "background"
 * @param string $label
 * @param bool $hideFixedCheckbox whether not to offer the "fixed image" choice. Default is `false`
 * @param array $conditional_logic 2d array with conditional logic applied to all fields. Default is `[]`
 * @return Field[]
 */
function imageFields(string $blockName, string $fieldNamePrefix, string $label = "Bild", bool $hideFixedCheckbox = false, array $conditional_logic = []): array {
    $fields = [];

    if (!Utils::isBlank($fieldNamePrefix))
        $fieldNamePrefix = CustomBlockWrapper::parseName($fieldNamePrefix) . "-";

    $fields[] = fieldFactory("image", $fieldNamePrefix . "image-url", $label, $blockName)
        ->set_value_type("url")
        ->set_conditional_logic($conditional_logic)
        ->set_required(true);

    if (!$hideFixedCheckbox)
        $fields[] = fieldFactory("checkbox", $fieldNamePrefix . "image-fixed", __("Bild fixieren (Bild soll sich beim Scrollen nicht bewegen)"), $blockName)
            ->set_conditional_logic($conditional_logic);

    return $fields;
}

/**
 * @param string $blockName
 * @param bool $hideFixedImageCheckbox whether not to offer the "fixed image" choice. Default is `false`
 * @return Field[]
 */
function backgroundFields(string $blockName, bool $hideFixedImageCheckbox = false): array {
    $typeFieldName = "background-type";
    $typeFieldValueBackgroundColor = "backgroundColor";
    $typeFieldValueImage = "image";

    return [
        fieldFactory("radio", $typeFieldName, "Hintergrund", $blockName)
            ->add_options([
                $typeFieldValueImage => "Bild",
                $typeFieldValueBackgroundColor => "Farbe"
            ])
            ->set_required(true),

        // background image
        ...imageFields(
            $blockName,
            "background",
            "Hintergrundbild",
            $hideFixedImageCheckbox, 
            [[
                'field' => CustomBlockWrapper::parseName($blockName) . "-" . $typeFieldName,
                'value' => $typeFieldValueImage,
            ]]
        ),

        // background color
        colorsField($blockName, "background", "Hintergrundfarbe")
            ->set_conditional_logic([[
                'field' => CustomBlockWrapper::parseName($blockName) . "-" . $typeFieldName,
                'value' => $typeFieldValueBackgroundColor,
            ]]),
    ];
}

/**
 * Select input for simple blocks.
 * 
 * @param string $blockName
 * @param bool $required defaults to `true`
 * @return Field[]
 */
function simpleBlockSelectorFields(string $blockName, string $label = "Typ", bool $required = true): array {
    $blockSelectOptions = noSelectOption();
    $simpleBlockFields = [];

    $selectFieldName = "simple-block-type";

    foreach (simpleBlocks() as $blockWrapper) {
        $selectFieldValue = $blockWrapper->getBlockType();
        
        // add select option
        $blockSelectOptions[$selectFieldValue] = $blockWrapper->blockTitle;
        
        // add conditional to simple block fields
        foreach ($blockWrapper->fields as $blockWrapperField) {
            $existingFieldConditionalLogic = $blockWrapperField->get_conditional_logic();

            // get existing logic
            if (isset($existingFieldConditionalLogic["rules"]))
                $existingFieldConditionalLogic = $existingFieldConditionalLogic["rules"];

            $blockWrapperField->set_conditional_logic(
                [
                    ...$existingFieldConditionalLogic,
                    [
                        'field' => CustomBlockWrapper::parseName($blockName) . "-" . $selectFieldName,
                        'value' => $selectFieldValue
                    ],
                ],
            );
            $simpleBlockFields[] = $blockWrapperField;
        }
    }

    return [
        fieldFactory("select", $selectFieldName, $label, $blockName)
            ->add_options($blockSelectOptions)
            ->set_required($required),
        ...$simpleBlockFields
    ];
}

/**
 * Rendered inside a `boxContainer` block
 * 
 * @param string $blockName
 * @param string $label default is "Block"
 * @return Field[]
 */
function boxFields(string $blockName, string $label = "Block"): array {
    return [
        ...backgroundFields($blockName, true),
        fieldFactory("radio", "width", __("Breite"), $blockName)
            ->add_options([
                "fit-content" => __("An Inhalt anpassen (Element wird so breit sein wie der Inhalt)"),
                "66%" => __("2/3 der vollen Breite"),
                "100%" => __("Volle Breite"),
            ])
            ->set_default_value("fit-content"),
        fieldFactory("checkbox", "more-padding", __("Extra Abstand zum inneren Rand"), $blockName),
        ...simpleBlockSelectorFields($blockName, $label, false),
    ];
}
