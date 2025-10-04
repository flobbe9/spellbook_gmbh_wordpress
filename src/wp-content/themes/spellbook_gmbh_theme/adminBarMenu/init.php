<?php

/**
 * Customizes the horizontal menu at the top (containing the wordpress logo and profile link etc.). Also refered to as wp_admin_bar or toolBar.
 * 
 * Docs: https://cusmin.com/blog/customize-bar/
 * 
 * Possible hooks: wp_before_admin_bar_render, admin_bar_meu
 */


function initAdminBarMenu(): void {
    global $wp_admin_bar;

    // remove the +New button since it gives users access to "posts"
    $wp_admin_bar->remove_node('new-content');
}