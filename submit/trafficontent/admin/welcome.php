<?php
defined('ABSPATH') || exit;


// Register the Trafficontent Welcome page under Tools menu
add_action('admin_menu', function () {
    add_submenu_page(
        'tools.php',
        'Trafficontent Welcome',
        'Trafficontent',
        'manage_options',
        'trafficontent-welcome',
        'trafficontent_welcome_page'
    );
});

function trafficontent_welcome_page() {
    // Optional: manual force cleanup via URL, with nonce check
    if (
        isset($_GET['force_disconnect']) &&
        current_user_can('manage_options') &&
        check_admin_referer('trafficontent_disconnect_nonce')
    ) {
        delete_option('trafficontent_channel_id');
        delete_option('trafficontent_consent_given');
    }
    // Only render the welcome page HTML once per request
    if (did_action('trafficontent_rendered_welcome') === 0) {
        do_action('trafficontent_rendered_welcome');
        ?>
        <div class="wrap trafficontent-welcome-wrap">
            <p class="trafficontent-welcome-header">Welcome to Trafficontent</p>
            <h1 class="trafficontent-welcome-title">Effortless Blog Content Automation for WordPress</h1>
            <div class="trafficontent-welcome-box">
            <?php
            // Show plugin logo securely using WordPress image handler
            if ($attachment_id = attachment_url_to_postid(plugins_url('assets/logo_l.png', dirname(__FILE__)))) {
                echo wp_kses_post(
                    wp_get_attachment_image(
                        $attachment_id,
                        'medium',
                        false,
                        [
                            'class' => 'trafficontent-logo',
                            'alt' => esc_attr__('Trafficontent Logo', 'trafficontent')
                        ]
                    )
                );
            }
            ?>
            <h2 class="trafficontent-welcome-subtitle">🚀 Welcome to Trafficontent!</h2>
                <p class="trafficontent-welcome-desc">Boost your organic traffic by filling your blog with automated endless content.</p>
                <form id="trafficontent-connect-form" class="trafficontent-connect-form" method="post">
                    <?php wp_nonce_field('trafficontent_connect_nonce_action', 'trafficontent_connect_nonce'); ?>
                    <?php wp_nonce_field('trafficontent_disconnect_nonce'); ?>
                    <label class="trafficontent-consent-label">
                        <input type="checkbox" name="trafficontent_agree" id="trafficontent_agree" class="trafficontent-consent-checkbox" required />
                        I consent to connect this site and share my admin email with Trafficontent.
                    </label>
                    <?php if (get_option('trafficontent_channel_id')): ?>
                        <button id="trafficontent-connect-btn" type="submit" class="button button-secondary trafficontent-connect-btn">
                            <span class="dashicons dashicons-update"></span>
                            <span class="btn-text">Reconnect Trafficontent</span>
                            <span class="trafficontent-spinner spinner"></span>
                        </button>
                    <?php else: ?>
                        <button id="trafficontent-connect-btn" type="submit" class="button button-primary trafficontent-connect-btn">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <span class="btn-text">Connect Trafficontent</span>
                            <span class="trafficontent-spinner spinner"></span>
                        </button>
                    <?php endif; ?>
                </form>
            </div>
            <div class="trafficontent-features">
                <div class="trafficontent-feature">
                    <span class="dashicons dashicons-chart-line"></span>
                    <h3>Smart SEO Insights</h3>
                    <p>
                        Gain intelligent keyword suggestions and performance analytics for every post you publish.
                    </p>
                </div>
                <div class="trafficontent-feature">
                    <span class="dashicons dashicons-edit-page"></span>
                    <h3>Automated Writing</h3>
                    <p>
                        Let AI write high-quality blogs for you — just pick your topic and review.
                    </p>
                </div>
                <div class="trafficontent-feature">
                    <span class="dashicons dashicons-schedule"></span>
                    <h3>Schedule & Forget</h3>
                    <p>
                        Set up once and let Trafficontent publish on your behalf automatically.
                    </p>
                </div>
                <div class="trafficontent-feature">
                    <span class="dashicons dashicons-groups"></span>
                    <h3>Organic Traffic</h3>
                    <p>
                        Did you know that organic traffic is the most cost-effective growth channel?
                    </p>
                </div>
                <div class="trafficontent-feature">
                    <span class="dashicons dashicons-lightbulb"></span>
                    <h3>Your Content Engine</h3>
                    <p>
                        Research and explore the topic to craft engaging, relevant, and SEO-friendly content tailored to your audience.
                    </p>
                </div>
                <div class="trafficontent-feature">
                    <span class="dashicons dashicons-visibility"></span>
                    <h3>Seamless Scheduling</h3>
                    <p>
                        Save time, save money, schedule and automate your blog publishing workflow without ever leaving your WordPress dashboard.
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}

add_action('admin_init', function () {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        add_action('admin_head', function () {
            $screen = get_current_screen();
            if ($screen && $screen->id === 'trafficontent_page_trafficontent-welcome') {
                remove_all_actions('admin_notices');
            }
        });
    }
    if (
        isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['trafficontent_agree'])
        && isset($_POST['trafficontent_connect_nonce'])
    ) {
        $raw_nonce = isset($_POST['trafficontent_connect_nonce']) ? sanitize_text_field(wp_unslash($_POST['trafficontent_connect_nonce'])) : '';
        // Ensure nonce is set and valid before proceeding
        if (!$raw_nonce || !wp_verify_nonce($raw_nonce, 'trafficontent_connect_nonce_action')) {
            return;
        }
        if (!current_user_can('manage_options')) return;

        // If receiving JSON (from JS), verify nonce from JSON body and only process required fields
        $body_raw = file_get_contents('php://input');
        if (!$body_raw || !is_string($body_raw)) {
            wp_send_json_error(['message' => 'Empty request body'], 400);
            exit;
        }

        $body = json_decode($body_raw, true);
        if (!is_array($body)) {
            wp_send_json_error(['message' => 'Malformed JSON input'], 400);
            exit;
        }

        // Sanitize and extract only expected keys
        $channel_id = array_key_exists('channel_id', $body) ? sanitize_text_field($body['channel_id']) : '';
        $site_url   = array_key_exists('site_url', $body) ? esc_url_raw($body['site_url']) : '';
        $email      = array_key_exists('email', $body) ? sanitize_email($body['email']) : '';
        $nonce      = array_key_exists('nonce', $body) ? sanitize_text_field($body['nonce']) : '';

        if (!empty($body)) {
            if (empty($nonce) || !wp_verify_nonce($nonce, 'trafficontent_connect_nonce_action')) {
                wp_send_json_error(['message' => 'Invalid nonce'], 403);
                exit;
            }
        }

        update_option('trafficontent_consent_given', true);

        // Prevent duplicate registration if channel_id exists
        $channel_id_option = get_option('trafficontent_channel_id');
        if (!empty($channel_id_option)) {
            // Trafficontent: Channel ID already exists, skipping registration.
            wp_safe_redirect('https://trafficontent.com/creator/settings/');
            exit;
        }

// Registration now handled by JS fetch()

        // Show error message in admin UI with API failure message
        add_action('admin_notices', function () {
            printf(
                '<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
                esc_html__('Trafficontent:', 'trafficontent'),
                esc_html__('Please use the Connect button to register the site.', 'trafficontent')
            );
        });
    }
});
// Redirect to welcome/setup page after activation
register_activation_hook(__FILE__, function () {
    delete_option('trafficontent_channel_id');
    delete_option('trafficontent_consent_given');
    delete_option('trafficontent_do_activation_redirect');
    update_option('trafficontent_do_activation_redirect', wp_create_nonce('trafficontent_activation_redirect'));
    // Force UI cleanup flag for JS
    update_option('trafficontent_ui_reset_needed', true);
});

add_action('admin_init', function () {
    // UI cleanup after reactivation
    if (get_option('trafficontent_ui_reset_needed')) {
        delete_option('trafficontent_ui_reset_needed');
        add_action('admin_footer', function () {
            wp_add_inline_script('trafficontent-script', 'localStorage.removeItem("trafficontent_channel_synced");');
        });
    }
    $activation_nonce = get_option('trafficontent_do_activation_redirect', false);
    if ($activation_nonce) {
        delete_option('trafficontent_do_activation_redirect');
        // Only redirect if not in bulk activation and nonce is valid
        $wpnonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) : '';
        if (!isset($_GET['activate-multi']) && $wpnonce && wp_verify_nonce($wpnonce, 'trafficontent_activation_redirect')) {
            wp_safe_redirect(admin_url('admin.php?page=trafficontent-welcome'));
            exit;
        }
    }
});
 

// Enqueue admin assets for the welcome page
function trafficontent_enqueue_admin_assets($hook) {
wp_enqueue_style('trafficontent-style', plugins_url('assets/style.css', dirname(__FILE__)), [], '1.0.3');
wp_enqueue_script('trafficontent-script', plugins_url('assets/script.js', dirname(__FILE__)), ['jquery'], '1.0.3', true);
 

    // Localize admin email, site URL, blog name, and ajax_url to JS
    wp_localize_script('trafficontent-script', 'trafficontent_vars', [
        'admin_email' => sanitize_email(get_option('admin_email')),
        'site_url'    => home_url(),
        'blog_name'   => get_bloginfo('name'),
        'ajax_url'    => admin_url('admin-ajax.php'),
    ]);

    $inline_script = "localStorage.removeItem('trafficontent_channel_synced');";
    wp_add_inline_script('trafficontent-script', $inline_script);
}
add_action('admin_enqueue_scripts', 'trafficontent_enqueue_admin_assets');

// Omit Token Only Login

// Handle AJAX token generation for JS fetch
add_action('wp_ajax_trafficontent_generate_token', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Permission denied'], 403);
    }

    $user = wp_get_current_user();

    // Fallback for when the app password was previously generated.
    $app_password = null;
    if (class_exists('WP_Application_Passwords')) {
        $existing = WP_Application_Passwords::get_user_application_passwords($user->ID);
        if (!empty($existing)) {
            foreach ($existing as $app) {
                if ($app['name'] === 'Trafficontent Auto') {
                    $app_password = $app['new_password'] ?? null;
                    break;
                }
            }
        }
        if (!$app_password) {
            $generated = WP_Application_Passwords::create_new_application_password($user->ID, array('name' => 'Trafficontent Auto'));
            if (is_array($generated)) {
                $app_password = $generated[0];
            }
        }
    }

    // Fallback for older WP without WP_Application_Passwords or if plugin not available
    if (!$app_password) {
        // Try legacy method
        $app_passwords = get_user_meta($user->ID, '_application_passwords', true);
        $first = $app_passwords && is_array($app_passwords) ? reset($app_passwords) : null;
        if (!$first) {
            $new = wp_generate_application_password($user->ID, 'Trafficontent Integration');
            if (is_wp_error($new)) {
                wp_send_json_error(['message' => 'Failed to generate app password'], 500);
            }
            $app_password = is_array($new) ? $new[0] : $new;
        } else {
            $app_password = $first['password'] ?? $first['new_password'] ?? null;
        }
    }

    wp_send_json([
        'username' => $user->user_login,
        'password' => $app_password,
        'email'    => $user->user_email,
    ]);
});

// Register the Trafficontent Channels page under Tools menu as a placeholder
function trafficontent_channels_page() {
    wp_safe_redirect('https://trafficontent.com/creator/settings/');
    exit;
}
add_action('admin_menu', function () {
    add_submenu_page(
        'tools.php',
        'Trafficontent Channels',
        'Channels',
        'manage_options',
        'trafficontent-channels',
        'trafficontent_channels_page'
    );
});