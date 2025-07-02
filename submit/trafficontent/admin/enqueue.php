<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_trafficontent-settings') return;
    wp_enqueue_style('trafficontent-admin-style', plugin_dir_url(dirname(__FILE__)) . 'assets/style.css', [], '1.0');
    wp_enqueue_script('trafficontent-admin-script', plugin_dir_url(dirname(__FILE__)) . 'assets/script.js', [], '1.0', true);
});