<?php 
require_once dirname(__DIR__, 1) ."/utils/Utils.php";
require_once "ThemeSettings.php";
?>


<head>
    <link rel="stylesheet" href="<?php echo getStyleSheetUrl("ThemeSettings.css") ?>">
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

        <h2>Slider Image</h2>
        <div>
            <!-- Num images -->
            <label for="numSliderImages">Max Anzahl an Bildern für ImageSlider. Betrifft alle ImageSlider auf allen Seiten!</label>
            <br>
            <input name="numSliderImages" type="number" min="4">
        </div>
            
        <br><br>

        <button class="wpButton" type="submit">
            Submit
        </button>
    </form>
</div>

<footer class="footerContainer">
    <div class="flexRight footerItemRight blueLink">
        <a href="<?php echo $_ENV["BASE_URL"] . "/wp-admin/themes.php" ?>">
            <?echo getSiteTitle() . " Version " . $_ENV["VERSION"]?>
        </a>
    </div>
</footer>