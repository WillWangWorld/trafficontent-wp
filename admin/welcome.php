<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    add_submenu_page(
        'trafficontent-dashboard',
        'Connector',
        'Connector',
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
        <div style="border-left: 5px solid #3B82F6; background: #35316f; padding: 20px; margin-top: 50px; border-radius: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin-bottom: 160px;">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/logo.png'; ?>" alt="Trafficontent Logo" style="height: 60px; margin-bottom: 10px;" />
            <h2 style="font-size: 22px; margin-top: 0; color:rgb(109, 142, 232);">🚀 Welcome to Trafficontent!</h2>
            <p style="font-size: 15px; color:rgb(222, 230, 243);">Boost your organic traffic by filling your blog with automated endless content.</p>
            <form method="post" id="trafficontent-connect-form" style="margin-top: 15px;">
                <label style="font-size: 14px;color:rgb(222, 230, 243);display: flex; align-items: center; justify-content: center;">
                    <input type="checkbox" name="trafficontent_agree" id="trafficontent_agree" style="margin-right: 10px;" required />
                    I consent to connect this site and share my admin email with Trafficontent.
                </label>
                <button id="trafficontent-connect-btn" type="submit" class="button button-primary" style="margin-top: 20px; padding: 15px 40px; font-size: 16px; border-radius: 8px; background-color: #805AF5; color: #fff; border: none; transition: all 0.3s ease; transform: scale(1);"
                    onmouseover="this.style.backgroundColor='#6c45e0'; this.style.transform='scale(1.05)';"
                    onmouseout="this.style.backgroundColor='#805AF5'; this.style.transform='scale(1)';"
                    onmousedown="this.style.transform='scale(0.98)';"
                    onmouseup="this.style.transform='scale(1.05)';">
                    <span class="dashicons dashicons-yes-alt" style="vertical-align: middle; margin-right: 6px; color: #fff;"></span>
                    <span class="btn-text">Connect Trafficontent</span>
                    <span class="spinner" style="display: none; margin-left: 10px; vertical-align: middle;"></span>
                </button>

                <?php if (get_option('trafficontent_channel_id')): ?>
                    <div class="trafficontent-success-message">🎉 Site successfully connected to Trafficontent!</div>
                <?php endif; ?>

            </form>
        </div>
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 40px; margin-bottom: 50px;">
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-chart-line" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Smart SEO Insights</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                    Gain intelligent keyword suggestions and performance analytics for every post you publish.
                </p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-edit-page" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Automated Writing</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                    Let AI write high-quality blogs for you — just pick your topic and review.
                </p>
            </div>
            <div style="flex: 1 1 200px;">
                <span class="dashicons dashicons-schedule" style="font-size: 40px; color: #3B82F6;"></span>
                <h3 style="font-size: 18px; color: #1d4ed8; margin-bottom: 10px;">Schedule & Forget</h3>
                <p style="margin: 0 auto; max-width: 250px; line-height: 1.5;">
                    Set up once and let Trafficontent publish on your behalf automatically.
                </p>
            </div>
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

        <style>
            .trafficontent-success-message {
                background: #d1fae5;
                color: #065f46;
                padding: 15px 20px;
                margin: 20px auto;
                border-radius: 8px;
                font-weight: 600;
                text-align: center;
                max-width: 700px;
            }
            .spinner {
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-top: 2px solid #fff;
                border-radius: 50%;
                width: 16px;
                height: 16px;
                animation: spin 0.6s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

        <script>
            document.getElementById('trafficontent-connect-form').addEventListener('submit', function(e) {
                if (!document.getElementById('trafficontent_agree').checked) {
                    e.preventDefault();
                    alert('Please check the box to continue.');
                    return;
                }

                const button = document.getElementById('trafficontent-connect-btn');
                const spinner = button.querySelector('.spinner');
                const btnText = button.querySelector('.btn-text');

                btnText.textContent = 'Connecting...';
                spinner.style.display = 'inline-block';
                spinner.classList.add('is-active');
                button.disabled = true;
                button.style.opacity = '0.7';
            });
        </script>
    </div>
    <?php
}

add_action('admin_init', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        add_action('admin_head', function () {
            $screen = get_current_screen();
            if ($screen && $screen->id === 'trafficontent_page_trafficontent-welcome') {
                remove_all_actions('admin_notices');
            }
        });
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trafficontent_agree'])) {
        if (!current_user_can('manage_options')) return;

        update_option('trafficontent_consent_given', true);

        // Prevent duplicate registration
        $channel_id = get_option('trafficontent_channel_id');
        if ($channel_id) {
            wp_redirect(admin_url('admin.php?page=trafficontent-channels'));
            exit;
        }

        $site_url = get_site_url();
        $site_name = get_bloginfo('name');
        $admin_email = get_option('admin_email');

        $response = wp_remote_post('https://trafficontent.com/api/register_wp_site/', [
            'timeout' => 15,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'site_url' => $site_url,
                'site_name' => $site_name,
                'email' => $admin_email,
                'token' => wp_generate_password(32, false), // Unique install token
            ])
        ]);

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            error_log('Trafficontent registration response: ' . print_r($data, true));

            if (!empty($data['channel_id'])) {
                update_option('trafficontent_channel_id', $data['channel_id']);
                if (!empty($data['token'])) {
                    update_option('trafficontent_token', $data['token']);
                }

                wp_redirect(admin_url('admin.php?page=trafficontent-channels'));
                exit;
            }
        }
        else {
            error_log('Trafficontent registration failed. Response: ' . wp_remote_retrieve_body($response));
        }

        // Optional: Show error message in admin UI
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>Trafficontent registration failed.</strong></p></div>';
        });
    }
});