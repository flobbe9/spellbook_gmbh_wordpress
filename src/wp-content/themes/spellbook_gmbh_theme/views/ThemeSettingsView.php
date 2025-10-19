<?php

use SpellbookGmbhTheme\Helpers\Utils;

require_once "ThemeSettings.php";
?>


<head>
    <link rel="stylesheet" href="<?php echo Utils::getStyleSheetUrl("ThemeSettings.css") ?>">
</head>

<div class="themeSettingsContainer">
    <h1>Theme settings</h1>
    <hr>
</div>

<footer class="footerContainer">
    <div class="flexRight footerItemRight blueLink">
        <!-- Theme version -->
        <a href="<?php echo $_ENV["BASE_URL"] . "/wp-admin/themes.php" ?>">
            <?echo Utils::getSiteTitle() . " Version " . $_ENV["VERSION"]?>
        </a>
    </div>
</footer>