<?php
add_action('admin_init', function () {
    if (!current_user_can('manage_options')) return;

    // Only proceed if consent has been granted
    if (!get_option('trafficontent_consent_given')) return;

    $token = get_option('trafficontent_token');
    if ($token) return; // Already registered

    $site_url = get_site_url();
    $admin_email = get_option('admin_email');

    $response = wp_remote_post('https://trafficontent.com/api/register_wp_site/', [
        'method' => 'POST',
        'timeout' => 15,
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode([
            'site_url' => $site_url,
            'email' => $admin_email
        ])
    ]);

    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($data['token']) && !empty($data['channel_id'])) {
            update_option('trafficontent_token', $data['token']);
            update_option('trafficontent_channel_id', $data['channel_id']);
        }
    }
});