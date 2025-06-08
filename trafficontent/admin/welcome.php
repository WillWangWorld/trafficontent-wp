<?php
add_action('admin_menu', function () {
    add_submenu_page(
        'trafficontent-dashboard',
        'Welcome',
        'Welcome',
        'manage_options',
        'trafficontent-welcome',
        'trafficontent_welcome_page'
    );
});

function trafficontent_welcome_page() {
    ?>
      <div class="wrap" style="max-width: 900px; margin: 50px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; text-align: center;">
        <p style="color: #3B82F6; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;">Welcome to Trafficontent</p>
        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 40px;">Effortless Blog Content Automation for WordPress</h1>
        
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 40px; margin-bottom: 50px;">
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-groups" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Organic Traffic</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                Did you know that organic traffic is the most cost-effective growth channel?
                </p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-lightbulb" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Your Content Engine</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                   Research and explore the topic to craft engaging, relevant, and SEO-friendly content tailored to your audience.
                </p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-visibility" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Seamless Scheduling</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                    Save time, save money, schedule and automate your blog publishing workflow without ever leaving your WordPress dashboard.
                </p>
            </div>
           
        </div>

         
    </div>
    <div class="notice notice-info" style="border-left: 5px solid #3B82F6; background: #35316f; padding: 20px; margin-top: 50px; border-radius: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
       <img src="https://www.trafficontent.com/static/images/logo/logo.png" alt="Trafficontent Logo" style="height: 60px; margin-bottom: 10px;" />
       <h2 style="font-size: 22px; margin-top: 0; color:rgb(109, 142, 232);">🚀 Welcome to Trafficontent!</h2>
        <p style="font-size: 15px; color:rgb(222, 230, 243);">Boost your organic traffic by filling your blog with automated endless content.</p>
        <form method="post" id="trafficontent-connect-form" style="margin-top: 15px;">
            <label style="font-size: 14px;color:rgb(222, 230, 243);display: flex; align-items: center; justify-content: center;">
                <input type="checkbox" name="trafficontent_agree" id="trafficontent_agree" style="margin-right: 10px;" required />
                I consent to connect this site and share my admin email with Trafficontent.
            </label>
            <button type="submit" class="button button-primary" style="
    margin-top: 20px;
    padding: 15px 40px;
    font-size: 16px;
    border-radius: 8px;
    background-color: #805AF5;
    color: #fff;
    border: none;
    transition: all 0.3s ease;
    transform: scale(1);
" onmouseover="this.style.backgroundColor='#6c45e0'; this.style.transform='scale(1.05)';"
   onmouseout="this.style.backgroundColor='#805AF5'; this.style.transform='scale(1)';"
   onmousedown="this.style.transform='scale(0.98)';"
   onmouseup="this.style.transform='scale(1.05)';">
                <span class="dashicons dashicons-yes-alt" style="vertical-align: middle; margin-right: 6px; color: #fff;"></span>
                Connect Trafficontent
            </button>
        </form>
    </div>

    <div style="max-width: 900px; margin: 30px auto;">
        <img src="<?php echo plugins_url('assets/calendar.png', __FILE__); ?>" alt="Calendar Demo" style="width: 100%; max-width: 100%; height: auto; display: block; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" />
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