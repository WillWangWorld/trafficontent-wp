<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_menu', function () {
    add_menu_page(
        'Trafficontent',
        'Trafficontent 🔗',
        'manage_options',
        'trafficontent-welcome',
        'trafficontent_render_connector_page',
        plugin_dir_url(dirname(__FILE__)) . 'assets/logo.png',
        80
    );

    
});

 

// The Channels submenu/page is removed as per instructions.

// Render function for Connector submenu
function trafficontent_render_connector_page() {
    if (function_exists('trafficontent_welcome_page')) {
        trafficontent_welcome_page();
    } else {
        echo '<div class="wrap"><h2>Connector</h2><p>Connector page could not be loaded.</p></div>';
    }
}