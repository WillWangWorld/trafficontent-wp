<?php
// This file is called by WordPress during uninstall
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all Trafficontent plugin options
delete_option('trafficontent_token');
delete_option('trafficontent_channel_id');

// Clean multisite if needed
if (is_multisite()) {
    $sites = get_sites(['fields' => 'ids']);
    foreach ($sites as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('trafficontent_token');
        delete_option('trafficontent_channel_id');
        restore_current_blog();
    }
}