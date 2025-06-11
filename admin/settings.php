<?php
add_action('admin_menu', function () {
    $channel_id = get_option('trafficontent_channel_id');
    add_menu_page(
        'Trafficontent',
        $channel_id ? '🟢 Trafficontent' : '🔴 Trafficontent',
        'manage_options',
        'trafficontent-dashboard',
        'trafficontent_settings_page',
        plugin_dir_url(dirname(__FILE__)) . 'assets/logo.png',
        80
    );

    add_submenu_page(
        'trafficontent-dashboard',
        'Channels',
        'Channels',
        'manage_options',
        'trafficontent-channels',
        'trafficontent_channels_page'
    );

    add_submenu_page(
        'trafficontent-dashboard',
        'Connector',
        'Connector',
        'manage_options',
        'trafficontent-welcome',
        'trafficontent_welcome_page'
    );
});

function trafficontent_settings_page() {
    ?>
    <div class="wrap">
        <h1>Trafficontent Dashboard</h1>
        <iframe
            src="https://trafficontent.com/creator/settings/"
            style="width:100%; height:90vh; border:none;"
        ></iframe>
    </div>
    <?php
}

function trafficontent_channels_page() {
    $channel_id = get_option('trafficontent_channel_id');
    $version = time(); // Cache-busting query param
    ?>
    <div class="wrap">
        <h1>Trafficontent Settings</h1>
        
        <iframe
            src="https://trafficontent.com/creator/settings/?v=<?php echo $version; ?>"
            style="width:100%; height:90vh; border:none;"
        ></iframe>
    </div>
    <?php
}