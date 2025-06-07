<?php
add_action('admin_menu', function () {
    add_submenu_page(
        null, // No parent slug, hidden from menu
        'Trafficontent Welcome',
        'Trafficontent Welcome',
        'manage_options',
        'trafficontent-welcome',
        'trafficontent_welcome_page'
    );
});

function trafficontent_welcome_page() {
    ?>
      <div class="wrap" style="max-width: 900px; margin: 40px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; text-align: center;">
        <p style="color: #3B82F6; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;">We Are Hiring</p>
        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 40px;">If You’re Looking To Make An Impact,<br />We’re Looking For You</h1>
        
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 40px; margin-bottom: 40px;">
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-groups" style="font-size: 40px; color: #3B82F6;"></span>
                <p style="margin-top: 10px;">We work together as a friendly,<br />supportive team</p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-lightbulb" style="font-size: 40px; color: #3B82F6;"></span>
                <p style="margin-top: 10px;">We strive to find the best solution,<br />not the easy one</p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-visibility" style="font-size: 40px; color: #3B82F6;"></span>
                <p style="margin-top: 10px;">We go the extra mile to deliver work<br />we're proud of</p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-heart" style="font-size: 40px; color: #3B82F6;"></span>
                <p style="margin-top: 10px;">We put our customers at the heart<br />of everything we do</p>
            </div>
        </div>

        <a href="#" class="button button-primary" style="padding: 10px 24px; font-size: 15px;">See Open Positions</a>
    </div>
    <div class="wrap" style="max-width: 700px; margin: 40px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
        <h1 style="font-size: 28px; margin-bottom: 10px;">🚀 Welcome to Trafficontent!</h1>
        <p style="font-size: 16px; color: #555;">Generate AI-powered blog content for your WordPress site. To continue, please consent to connect your site.</p>

        <form method="post" id="trafficontent-connect-form">
            <label style="font-size: 15px; display: flex; align-items: center; margin-top: 20px;">
                <input type="checkbox" name="trafficontent_agree" id="trafficontent_agree" style="margin-right: 10px;" required />
                I consent to connect this site and share my admin email with Trafficontent.
            </label>
            <button type="submit" class="button button-primary" style="margin-top: 20px;">✅ Connect Site</button>
        </form>
    </div>
    <script>
        document.getElementById('trafficontent-connect-form').addEventListener('submit', function(e) {
            if (!document.getElementById('trafficontent_agree').checked) {
                e.preventDefault();
                alert('Please check the box to continue.');
            }
        });
    </script>
    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trafficontent_agree'])) {
        update_option('trafficontent_consent_given', true);
        add_action('admin_init', function () {
            if (!current_user_can('manage_options')) return;

            $token = get_option('trafficontent_token');
            if ($token) return;

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
        echo '<div class="notice notice-success"><p>✅ Site successfully connected!</p></div>';
        echo '<script>setTimeout(() => location.reload(), 1500);</script>';
    }
}