<?php
/**
 * Plugin Name: Clear Content
 * Description: Clear content based on post type
 * Version: 1
 * Author: Jason Lawton <jason@jasonlawton.com>
 */

define( 'JHL_CP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JHL_CP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include( 'inc/api.php' );

add_action( 'admin_menu', 'jhl_cp_add_admin_menu' );

function jhl_cp_add_admin_menu() {
    add_menu_path( 'Clear Content', 'Clear Content', 'manage_categories', 'clear_content', 'jhl_cp_options_page' );
}

function jhl_cp_options_page() {
    include 'options-form.php';
}