<?php
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_trafficontent-settings') return;
    // Add styles or scripts if needed
});