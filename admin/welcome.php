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
    // Optional: manual force cleanup via URL
    if (isset($_GET['force_disconnect']) && current_user_can('manage_options')) {
        delete_option('trafficontent_channel_id');
        delete_option('trafficontent_consent_given');
    }
    ?>
    <div class="wrap" style="max-width: 900px; margin: 50px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; text-align: center;">
        <p style="color: #3B82F6; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;">Welcome to Trafficontent</p>
        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 40px;">Effortless Blog Content Automation for WordPress</h1>
        <div style="border-left: 5px solid #3B82F6; background: #35316f; padding: 20px; margin-top: 50px; border-radius: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin-bottom: 160px;">
            <img src="https://trafficontent.com/static/images/logo/logo.png" alt="Trafficontent Logo" style="height: 60px; margin-bottom: 10px;" />
            <h2 style="font-size: 22px; margin-top: 0; color:rgb(109, 142, 232);">🚀 Welcome to Trafficontent!</h2>
            <p style="font-size: 15px; color:rgb(222, 230, 243);">Boost your organic traffic by filling your blog with automated endless content.</p>
            <form id="trafficontent-connect-form" style="margin-top: 15px;" onsubmit="event.preventDefault(); connectTrafficontent();">
                <label style="font-size: 14px;color:rgb(222, 230, 243);display: flex; align-items: center; justify-content: center;">
                    <input type="checkbox" name="trafficontent_agree" id="trafficontent_agree" style="margin-right: 10px;" required />
                    I consent to connect this site and share my admin email with Trafficontent.
                </label>
                <?php if (get_option('trafficontent_channel_id')): ?>
                    <button id="trafficontent-connect-btn" type="submit" class="button button-secondary" style="margin-top: 20px; padding: 15px 40px; font-size: 16px; border-radius: 8px; background-color: #ccc; color: #333; border: none; transition: all 0.3s ease; transform: scale(1);"
                        onmouseover="this.style.backgroundColor='#bbb'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.backgroundColor='#ccc'; this.style.transform='scale(1)';"
                        onmousedown="this.style.transform='scale(0.98)';"
                        onmouseup="this.style.transform='scale(1.05)';">
                        <span class="dashicons dashicons-update" style="vertical-align: middle; margin-right: 6px;"></span>
                        <span class="btn-text">Reconnect Trafficontent</span>
                        <span class="spinner" style="display: none; margin-left: 10px; vertical-align: middle;"></span>
                    </button>
                <?php else: ?>
                    <button id="trafficontent-connect-btn" type="submit" class="button button-primary" style="margin-top: 20px; padding: 15px 40px; font-size: 16px; border-radius: 8px; background-color: #805AF5; color: #fff; border: none; transition: all 0.3s ease; transform: scale(1);"
                        onmouseover="this.style.backgroundColor='#6c45e0'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.backgroundColor='#805AF5'; this.style.transform='scale(1)';"
                        onmousedown="this.style.transform='scale(0.98)';"
                        onmouseup="this.style.transform='scale(1.05)';">
                        <span class="dashicons dashicons-yes-alt" style="vertical-align: middle; margin-right: 6px; color: #fff;"></span>
                        <span class="btn-text">Connect Trafficontent</span>
                        <span class="spinner" style="display: none; margin-left: 10px; vertical-align: middle;"></span>
                    </button>
                <?php endif; ?>

                <?php if (get_option('trafficontent_channel_id')): ?>
                    <div class="trafficontent-success-message">🎉 Site successfully connected to Trafficontent!</div>
                <?php else: ?>
                    <div class="trafficontent-success-message" style="display:none;"></div>
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
            function connectTrafficontent() {
                const checkbox = document.getElementById('trafficontent_agree');
                if (!checkbox.checked) {
                    alert('Please check the box to continue.');
                    return;
                }

                const button = document.getElementById('trafficontent-connect-btn');
                const spinner = button.querySelector('.spinner');
                const btnText = button.querySelector('.btn-text');

                btnText.textContent = 'Connecting...';
                spinner.style.display = 'inline-block';
                button.disabled = true;
                button.style.opacity = '0.7';

                // Helper to get CSRF cookie if present
                function getCookie(name) {
                    let cookieValue = null;
                    if (document.cookie && document.cookie !== '') {
                        const cookies = document.cookie.split(';');
                        for (let i = 0; i < cookies.length; i++) {
                            const cookie = cookies[i].trim();
                            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                                break;
                            }
                        }
                    }
                    return cookieValue;
                }

                fetch("https://trafficontent.com/api/register_wp_site/", {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json"
                  },
                  credentials: "include",
                  body: JSON.stringify({
                    site_url: window.location.origin,
                    site_name: window.location.hostname,
                    email: '<?php echo esc_js(get_option('admin_email')); ?>',
                    token: '<?php echo esc_js(wp_generate_password(32, false)); ?>'
                  })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.channel_id || <?php echo json_encode(get_option('trafficontent_channel_id')); ?>) {
                        try {
                            // Try redirecting within iframe
                            if (window.self !== window.top) {
                                window.top.location.href = '<?php echo admin_url('admin.php?page=trafficontent-channels'); ?>';
                            } else {
                                window.location.href = '<?php echo admin_url('admin.php?page=trafficontent-channels'); ?>';
                            }
                        } catch (e) {
                            window.location.href = '<?php echo admin_url('admin.php?page=trafficontent-channels'); ?>';
                        }
                    } else {
                        alert("Trafficontent: Failed to register. Please try again.");
                        button.disabled = false;
                        spinner.style.display = 'none';
                        btnText.textContent = 'Connect Trafficontent';
                        button.style.opacity = '1';
                    }
                })
                .catch(err => {
                    console.error('Trafficontent: Error connecting.', err);
                    alert("Trafficontent: Error connecting.");
                    button.disabled = false;
                    spinner.style.display = 'none';
                    btnText.textContent = 'Connect Trafficontent';
                    button.style.opacity = '1';
                });
            }
        </script>
    <script>
    window.addEventListener("message", function(event) {
        if (event.data?.type === "CHANNEL_CREATED") {
            // Force refresh of parent frame and navigation menu
            if (window.top !== window.self) {
                window.top.location.href = '<?php echo admin_url('admin.php?page=trafficontent-channels'); ?>';
            } else {
                location.reload();
            }
        }
    });
    // UI reset if channel_id missing (after forced disconnect or reactivation)
    document.addEventListener('DOMContentLoaded', () => {
        const channelId = <?php echo json_encode(get_option('trafficontent_channel_id')); ?>;
        const button = document.getElementById('trafficontent-connect-btn');
        const message = document.querySelector('.trafficontent-success-message');

        if (!channelId && button) {
            // Reset button to original "Connect" style
            button.innerHTML = `
                <span class="dashicons dashicons-yes-alt" style="vertical-align: middle; margin-right: 6px; color: #fff;"></span>
                <span class="btn-text">Connect Trafficontent</span>
                <span class="spinner" style="display: none; margin-left: 10px; vertical-align: middle;"></span>
            `;
            button.className = "button button-primary";
            button.style.backgroundColor = "#805AF5";
            button.disabled = false;
            button.style.opacity = "1";
            // Hide message
            if (message) message.style.display = "none";
        }
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

        // Prevent duplicate registration if channel_id exists
        $channel_id = get_option('trafficontent_channel_id');
        if (!empty($channel_id)) {
            error_log('Trafficontent: Channel ID already exists, skipping registration.');
            wp_redirect(admin_url('admin.php?page=trafficontent-channels'));
            exit;
        }

        // Removed wp_remote_post() call as registration now handled by JS fetch()

        // Show error message in admin UI with API failure message
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>Trafficontent:</strong> Please use the Connect button to register the site.</p></div>';
        });
    }
});
// Redirect to welcome/setup page after activation
register_activation_hook(__FILE__, function () {
    delete_option('trafficontent_channel_id');
    delete_option('trafficontent_consent_given');
    delete_option('trafficontent_do_activation_redirect');
    update_option('trafficontent_do_activation_redirect', true);
    // Force UI cleanup flag for JS
    update_option('trafficontent_ui_reset_needed', true);
});

add_action('admin_init', function () {
    // UI cleanup after reactivation
    if (get_option('trafficontent_ui_reset_needed')) {
        delete_option('trafficontent_ui_reset_needed');
        add_action('admin_footer', function () {
            echo "<script>localStorage.removeItem('trafficontent_channel_synced');</script>";
        });
    }
    if (get_option('trafficontent_do_activation_redirect', false)) {
        delete_option('trafficontent_do_activation_redirect');
        if (!isset($_GET['activate-multi'])) {
            wp_safe_redirect(admin_url('admin.php?page=trafficontent-welcome'));
            exit;
        }
    }
});