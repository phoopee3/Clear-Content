<?php
/**
 * Plugin Name: Clear Content
 * Description: Clear content based on post type
 * Version: 1.1.0
 * Author: Jason Lawton <jason@jasonlawton.com>
 */

include( 'inc/api.php' );

add_action( 'admin_menu', 'jhl_cc_add_admin_menu' );

function jhl_cc_add_admin_menu() {
    add_menu_page( 'Clear Content', 'Clear Content', 'manage_categories', 'clear_content', 'jhl_cc_options_page', 'dashicons-trash' );
}

function jhl_cc_options_page() {
    include 'options-form.php';
}
