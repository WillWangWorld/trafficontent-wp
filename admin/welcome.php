<?php
add_action('admin_menu', function () {
    add_submenu_page(
        'trafficontent-settings', // Show under main Trafficontent menu
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
        <p style="color: #3B82F6; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;">Welcome to Trafficontent</p>
        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 40px;">Effortless AI Content Automation for WordPress</h1>
        
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 40px; margin-bottom: 40px;">
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-groups" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Why Choose Trafficontent?</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                  Automatically generate high-quality blog posts, optimize for SEO, and schedule content directly from your WordPress dashboard.
                </p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-lightbulb" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">AI-Powered Content Engine</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                    Harness the power of AI to craft engaging, relevant, and SEO-friendly content tailored to your audience.
                </p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-visibility" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Seamless Scheduling</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                    Schedule and automate your blog publishing workflow without ever leaving your WordPress dashboard.
                </p>
            </div>
           
        </div>

         
    </div>
    <div class="notice notice-info" style="border-left: 5px solid #3B82F6; background: #f3e8ff; padding: 20px; margin-top: 40px; border-radius: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
        <h2 style="font-size: 22px; margin-top: 0; color: #1d4ed8;">🚀 Welcome to Trafficontent!</h2>
        <p style="font-size: 15px; color: #333;">Generate AI-powered blog content for your WordPress site.</p>
        <form method="post" id="trafficontent-connect-form" style="margin-top: 15px;">
            <label style="font-size: 14px; display: flex; align-items: center;">
                <input type="checkbox" name="trafficontent_agree" id="trafficontent_agree" style="margin-right: 10px;" required />
                I consent to connect this site and share my admin email with Trafficontent.
            </label>
            <button type="submit" class="button button-primary" style="margin-top: 15px; padding: 12px 30px; font-size: 16px; border-radius: 8px;">
                <span class="dashicons dashicons-yes-alt" style="vertical-align: middle; margin-right: 6px;"></span>
                Connect Site
            </button>
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