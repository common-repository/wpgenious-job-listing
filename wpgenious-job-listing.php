<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    http://wpgenious.com/
 * @since   1.0.0
 * @package wpgenious_job_listing
 *
 * @wordpress-plugin
 * Plugin Name:       WpGenius Job Listing
 * Plugin URI:        https://wordpress.org/plugins/wpgenious-job-listing
 * Description:       Wpgenious job listing it’s a simple and powerful wordpress plugin useful for adding job listing functionality to your job career website.
 * Version:           1.0.5
 * Author:            wpgenious
 * Author URI:        http://wpgenious.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpgenious-job-listing
 * Domain Path:       /languages
 */

if (! defined('WPINC') ) {
    die;
}

if (! defined('WPGENIOUS_JOB_LISTING_ADMIN_DIR') ) {
    define('WPGENIOUS_JOB_LISTING_ADMIN_DIR', plugin_dir_path(__FILE__).'src/admin/');
}

if (! defined('WPGENIOUS_JOB_LISTING_PUBLIC_DIR') ) {
    define('WPGENIOUS_JOB_LISTING_PUBLIC_DIR', plugin_dir_path(__FILE__).'src/public/');
}

if (! defined('WPGENIOUS_JOB_LISTING_INC_DIR') ) {
    define('WPGENIOUS_JOB_LISTING_INC_DIR', plugin_dir_path(__FILE__).'src/includes/');
}

if (! defined('WPGENIOUS_JOB_LISTING_ASSETS_DIR') ) {
    define('WPGENIOUS_JOB_LISTING_ASSETS_DIR', plugin_dir_path(__FILE__).'assets/');
}

if (! defined('WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL') ) {
    define('WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL', plugin_dir_url(__FILE__).'assets/');
}

if (! defined('WPGENIOUS_JOB_LISTING_UPLOAD_DIR_NAME') ) {
    define('WPGENIOUS_JOB_LISTING_UPLOAD_DIR_NAME', 'job-listing-files');
}

/**
 * Currently plugin version.
 */
if (! defined('WPGENIOUS_JOB_LISTING_VERSION') ) {
    define('WPGENIOUS_JOB_LISTING_VERSION', '1.0.5');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpgenious-job-listing-activator.php
 */
function activate_wpgenious_job_listing()
{
    include_once WPGENIOUS_JOB_LISTING_INC_DIR . 'class-wpgenious-job-listing-activator.php';
    Wpgenious_Job_Listing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpgenious-job-listing-deactivator.php
 */
function deactivate_wpgenious_job_listing()
{
    include_once WPGENIOUS_JOB_LISTING_INC_DIR . 'class-wpgenious-job-listing-deactivator.php';
    Wpgenious_Job_Listing_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wpgenious_job_listing');
register_deactivation_hook(__FILE__, 'deactivate_wpgenious_job_listing');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WPGENIOUS_JOB_LISTING_INC_DIR . 'class-wpgenious-job-listing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */

$plugin_options = get_option('wpgenious-job-listing');
/**
 * @param  $key
 * @return mixed|string
 */
function wpgenious_get_plugin_option($key)
{
    global $plugin_options;
    return !empty($plugin_options[$key]) ? $plugin_options[$key] : '';
}

function wpgenious_job_listing_run()
{

    $plugin = new Wpgenious_Job_Listing();
    $plugin->run();

}

wpgenious_job_listing_run();
