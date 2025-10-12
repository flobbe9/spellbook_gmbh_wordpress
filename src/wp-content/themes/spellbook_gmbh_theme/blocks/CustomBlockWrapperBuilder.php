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

    public function icon(string $icon): CustomBlockWrapperBuilder {
        $this->customBlockWrapper->icon = $icon;
        return $this;
    }
    
    public function build(): CustomBlockWrapper {
        Utils::assertNotNullBlankOrThrow($this->customBlockWrapper->blockTitle, $this->customBlockWrapper->description, $this->customBlockWrapper->fields);

        return $this->customBlockWrapper;
    }
}