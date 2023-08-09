<?php 
/*
Plugin Name: Invision Community Single Logout
Description: This plugin logs a user out from Invision Community when they visit a specified URL, and then logs them out of WordPress. Helpful when using WordPress as an Oauth Server since Invision Community does not allow for a custom logout URL.
Version: 0.3.1
Author: The team at PIE
Author URI: https://pie.co.de
*/

namespace PIE\InvisionCommunitySingleLogout;

/**
 * Load Composer autoloader
 */
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$update_checker = PucFactory::buildUpdateChecker(
    'https://pie.github.io/invision-community-single-logout/update.json',
    __FILE__,
    'invision-community-single-logout'
);

/**
 * The path to the Invision Community installation
 * 
 * @todo factor out the hard coded 'community' option
 */
const COMMUNITY_PATH = 'community';

/**
 * The plugin slug
 */
// const PLUGIN_SLUG = sanitize_key(__NAMESPACE__);

register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');
add_action('init', __NAMESPACE__ . '\add_endpoint', 10);
add_action('template_redirect', __NAMESPACE__ . '\maybe_log_the_user_out_from_ipb', 11);
add_action('clear_auth_cookie', __NAMESPACE__ . '\clear_ipb_auth_cookie');

/**
 * Adds our endpoint and then flushes the rewrite rules to make sure that it sticks
 *
 * @hooked plugin activation
 */
function activation(): void
{
    add_endpoint();
    add_rewrite_rule('^' . COMMUNITY_PATH .'/logout/?', 'index.php?' . sanitize_key(__NAMESPACE__) . '=logout', 'top');
    flush_rewrite_rules();
}

/**
 * Flushes the rewrite rules without our endpoint  to clean up after ourselves
 *
 * @hooked plugin deactivation
 */
function deactivation(): void
{
    flush_rewrite_rules();
}

/**
 * Adds our rewrite endpoint to the permalink structure
 */
function add_endpoint(): void
{
    add_rewrite_endpoint(sanitize_key(__NAMESPACE__), EP_ROOT );
}


/**
 * If we are at /icsl/logout then set the Invision Community cookies to 0, forcing a logout, then
 * redirect the user to the WP Logout URL and then on to the home page
 *
 * @todo factor out the hard coded 'community' option
 * @return void
 */
function maybe_log_the_user_out_from_ipb(): void
{
    clear_ipb_auth_cookie();
    if ('logout' === get_query_var(sanitize_key(__NAMESPACE__))) {
        header("Location: " . html_entity_decode(wp_logout_url("/")));
        exit;
    }
}

/**
 * Clears the Invision Community cookies, hooked into the clear_auth_cookie action so that it is called
 * automatically when a user logs out of WordPress
 * 
 * @hooked clear_auth_cookie
 * @return void
 */
function clear_ipb_auth_cookie(): void {
    setcookie('ips4_member_id', 0, 0, '/' . COMMUNITY_PATH .'/');
    setcookie('ips4_loggedIn', 0, 0, '/' . COMMUNITY_PATH .'/');
}