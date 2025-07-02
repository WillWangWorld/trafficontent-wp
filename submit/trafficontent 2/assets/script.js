// Trafficontent admin JS

// Trafficontent: Connect logic moved from welcome.php and ensured only defined once.
if (typeof trafficontent_vars !== 'undefined') {
    window.connectTrafficontent = function () {
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
        spinner.style.animation = 'spin 6s linear infinite';
        button.disabled = true;
        button.style.opacity = '0.7';

        function getCookie(name) {
            const match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
            return match ? match[2] : null;
        }

        fetch(trafficontent_vars.ajax_url + "?action=trafficontent_generate_token")
            .then(res => res.json())
            .then(tokenData => {
                console.log({
                    email: trafficontent_vars.admin_email || tokenData.email,
                    username: tokenData.username,
                    app_password: tokenData.password,
                    site_url: window.location.origin,
                    blog_name: window.location.hostname
                });
                fetch("https://trafficontent.com/api/register_wp_site/", {
                    method: "POST",
                    credentials: "omit",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        email: trafficontent_vars.admin_email || tokenData.email,
                        site_url: window.location.origin,
                        blog_name: window.location.hostname,
                        username: tokenData.username,
                        app_password: tokenData.password
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.channel_id && data.auto_login_token) {
                        spinner.style.display = 'none';
                        spinner.style.animation = 'none';
                        btnText.textContent = 'Connected!';
                        button.disabled = true;
                        button.style.opacity = '1';
                        const channel_id = data.channel_id;
                        const token = data.auto_login_token;
                        window.location.href = `https://trafficontent.com/creator/wp-bridge/?token=${channel_id}:${token}&next=/creator/settings/`;
                    } else {
                        alert("Trafficontent: Failed to register. Please try again.");
                        button.disabled = false;
                        spinner.style.display = 'none';
                        spinner.style.animation = 'none';
                        btnText.textContent = 'Connect Trafficontent';
                        button.style.opacity = '1';
                    }
                })
                .catch(err => {
                    console.error('Trafficontent: Error connecting.', err);
                    alert("Trafficontent: Error connecting.");
                    button.disabled = false;
                    spinner.style.display = 'none';
                    spinner.style.animation = 'none';
                    btnText.textContent = 'Connect Trafficontent';
                    button.style.opacity = '1';
                });
            });
    };
}

window.addEventListener("message", function(event) {
    if (event.data && event.data.type === "CHANNEL_CREATED") {
        // Open new wp-bridge login page with token in channel_id:token format
        // The PHP code for this token is not available here, so this must be handled server-side if needed
        // window.open(`https://trafficontent.com/creator/wp-bridge/?token=${encodeURIComponent(token)}&next=/creator/settings/`, '_blank');
    }
});
// UI reset if channel_id missing (after forced disconnect or reactivation)
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('trafficontent-connect-btn');
    if (button) {
        button.disabled = false;
        button.style.opacity = "1";
    }
    const form = document.getElementById('trafficontent-connect-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (typeof connectTrafficontent === 'function') {
                connectTrafficontent();
            }
        });
    }
});