<?php

function addAdminPage(): void {

    add_menu_page(
        "Admin thing",
        "Menu title",
        "editor",
        "admin-thing",
        function($page) {

        }
    );
}