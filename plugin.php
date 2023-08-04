<?php 
/*
Plugin Name: Invision Community Single Logout
Description: This plugin logs a user out from Invision Community when they visit a specified URL, and then logs them out of WordPress. Helpful when using WordPress as an Oauth Server since Invision Community does not allow for a custom logout URL.
Version: 0.1.4
Author: The team at PIE
Author URI: https://pie.co.de
*/

namespace PIE\InvisionCommunitySingleLogout;

register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\unistall_my_plugin');
add_action('init', __NAMESPACE__ . '\add_endpoint', 10);
add_action('template_redirect', __NAMESPACE__ . '\maybe_log_the_user_out_from_ipb', 11);

/**
 * Adds our endpoint and then flushes the rewrite rules to make sure that it sticks
 *
 * @hooked plugin activation
 */
function activation(): void
{
    add_endpoint();
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
    add_rewrite_endpoint('icsl', EP_ROOT );
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
    if ('logout' === get_query_var('icsl')) {
        setcookie('ips4_member_id', 0, 0, '/community/');
        setcookie('ips4_loggedIn', 0, 0, '/community/');
        header("Location: " . html_entity_decode(wp_logout_url("/")));
        exit;
    }
}

/**
 * Load Composer autoloader
 */
require get_template_directory() . '/vendor/autoload.php';
$update_checker = Puc_v4_Factory::buildUpdateChecker(
    'https://pie.github.io/invision-community-single-logout/update.json',
    __FILE__,
    'invision-community-single-logout'
);