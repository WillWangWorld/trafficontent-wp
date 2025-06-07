<?php
function trafficontent_admin_menu() {
    add_menu_page(
        'Trafficontent AutoPoster',
        'Trafficontent',
        'manage_options',
        'trafficontent-settings',
        'trafficontent_settings_page',
        'dashicons-edit',
        80
    );
}
add_action('admin_menu', 'trafficontent_admin_menu');

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