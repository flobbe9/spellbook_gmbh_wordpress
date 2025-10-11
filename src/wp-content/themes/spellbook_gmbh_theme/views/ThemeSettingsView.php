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

    <form action="" class="hidden">
        <!-- Hidden inputs -->
        <div>
            <!-- in order for form to redirect to this page -->
            <input type="hidden" name="page" value="theme-settings">
        </div>

        <h2>Footer Icons</h2>
        <!--  
                display present ones as inputs with their respective values
                button add new
                max num? scroll in frontend?
        -->
        <div class="footerIcons">
            <!-- 
                link name
                link url
                link target
                image url
             -->
            <label for="linkName"></label>
            <br>
            <input name="linkName" type="text">
        </div>
            
        <br><br>

        <button class="wpButton" type="submit">
            Submit
        </button>
    </form>
</div>

<footer class="footerContainer">
    <div class="flexRight footerItemRight blueLink">
        <!-- Theme version -->
        <a href="<?php echo $_ENV["BASE_URL"] . "/wp-admin/themes.php" ?>">
            <?echo Utils::getSiteTitle() . " Version " . $_ENV["VERSION"]?>
        </a>
    </div>
</footer>