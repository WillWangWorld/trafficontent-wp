<?php
/**
 * Plugin Name: Trafficontent
 * Plugin URI: https://github.com/WillWangWorld/trafficontent-wp
 * Text Domain: trafficontent
 * Description: Automatically connect your WordPress site to Trafficontent to generate AI blog posts.
 * Version: 1.0.2
 * Author: George Wang
 * Author URI: https://trafficontent.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.5
 * GitHub Plugin URI: https://github.com/WillWangWorld/trafficontent-wp
 * Update URI: https://github.com/WillWangWorld/trafficontent-wp
 */

// Ensure WordPress context
if (!defined('ABSPATH')) exit;

// Optionally include plugin update checker if available
// require plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php';
// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//     'https://github.com/WillWangWorld/trafficontent-wp/',
//     __FILE__,
//     'trafficontent'
// );

// Include admin pages first
require_once plugin_dir_path(__FILE__) . 'admin/settings.php'; 
require_once plugin_dir_path(__FILE__) . 'admin/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'admin/welcome.php';

// Plugin activation hook
register_activation_hook(__FILE__, function() {
    add_option('trafficontent_token', '');
    add_option('trafficontent_channel_id', '');
    set_transient('_trafficontent_activation_redirect', true, 30);
});

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