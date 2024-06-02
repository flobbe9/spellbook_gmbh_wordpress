<?php


/**
 * Enabel "Appearance->Menus" option in wordpress.
 */
function initMenus() {

    register_nav_menus([]);

    restrictAddableMenuItems();
}


/**
 * Hide a few menu items that shouldn't be added to nav menus using the "Screen options" tab.
 */
function restrictAddableMenuItems(): void {

    add_filter(
        "hidden_meta_boxes", 
        function($hiddenMetaBoxes, WP_Screen $screen) {

            // case: menus
            if ("nav-menus" === $screen->base) {
                array_push($hiddenMetaBoxes, "add-post-type-post");
                array_push($hiddenMetaBoxes, "add-post-type-test");
                array_push($hiddenMetaBoxes, "add-category");
                array_push($hiddenMetaBoxes, "add-post_tag");
            }

            return $hiddenMetaBoxes;
        },
        10, 
        2
    );
}