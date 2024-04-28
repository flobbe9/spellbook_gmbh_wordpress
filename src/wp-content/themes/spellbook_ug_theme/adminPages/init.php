<?php

/**
 * Register all custom admin pages.
 */
function initAdminPages(): void {

    registerThemeSettings();
}


function registerThemeSettings(): void {

    add_menu_page(
        "Theme settings",
        "Theme settings",
        "edit_posts",
        "theme-settings",
        function() {
            require_once dirname(__DIR__, 1) . "/views/ThemeSettings.php";
        }
    );
}