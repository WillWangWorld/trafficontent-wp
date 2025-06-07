<?php
/*
Plugin Name: Trafficontent
Plugin URI: https://github.com/WillWangWorld/trafficontent-wp
GitHub Plugin URI: https://github.com/WillWangWorld/trafficontent-wp
Description: Automatically connect your WordPress site to Trafficontent to generate AI blog posts.
Version: 1.0.1
Author: George Wang
Author URI: https://trafficontent.com
License: GPL2
Requires at least: 5.0
Tested up to: 6.5
*/

if (!defined('ABSPATH')) exit;

// Include admin pages
require_once plugin_dir_path(__FILE__) . 'admin/settings.php'; 
require_once plugin_dir_path(__FILE__) . 'admin/enqueue.php';


// Plugin activation hook
register_activation_hook(__FILE__, function() {
    add_option('trafficontent_token', '');
    add_option('trafficontent_channel_id', '');
    set_transient('_trafficontent_activation_redirect', true, 30);
});

 
// Ensure admin settings and welcome pages load properly
include_once plugin_dir_path(__FILE__) . 'admin/welcome.php';

// Redirect to welcome page ONCE after activation if no token is set
add_action('admin_init', function () {
    if (get_transient('_trafficontent_activation_redirect')) {
        delete_transient('_trafficontent_activation_redirect');
        if (!get_option('trafficontent_token')) {
            wp_safe_redirect(admin_url('admin.php?page=trafficontent-welcome'));
            exit;
        }
    }
});