<?php
namespace SpellbookGmbhTheme\Blocks;

use SpellbookGmbhTheme\Helpers\Utils;

class CustomBlockWrapperBuilder {
    private CustomBlockWrapper $customBlockWrapper;

    public function __construct() {
        $this->customBlockWrapper = new CustomBlockWrapper();
    }

    public function fields(array $fields): CustomBlockWrapperBuilder {
        $this->customBlockWrapper->fields = $fields;
        return $this;
    }

    public function blockTitle(string $blockTitle): CustomBlockWrapperBuilder {
        $this->customBlockWrapper->blockTitle = $blockTitle;
        return $this;
    }

    public function description(string $description): CustomBlockWrapperBuilder {
        $this->customBlockWrapper->description = $description;
        return $this;
    }

    public function previewCallback(callable $previewCallback): CustomBlockWrapperBuilder {
        $this->customBlockWrapper->previewCallback = $previewCallback;
        return $this;
    }

    /**
     * @param bool $isCustomIcon indicates that this icon name is not from documentation but defined in "gutenbergEditor.css". Default is `false`
     */
    public function icon(string $icon, bool $isCustomIcon = false): CustomBlockWrapperBuilder {
        if ($isCustomIcon)
            $icon = $icon . " dashicons-custom"; // add additional classname .dashicons-custom
        
        $this->customBlockWrapper->icon = $icon;

        return $this;
    }
    
    public function build(): CustomBlockWrapper {
        Utils::assertNotNullBlankOrThrow($this->customBlockWrapper->blockTitle, $this->customBlockWrapper->description, $this->customBlockWrapper->fields);

        return $this->customBlockWrapper;
    }
}