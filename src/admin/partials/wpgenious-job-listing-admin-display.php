<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/admin/partials
 */

if (! defined('ABSPATH') ) {
    exit;
}

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';

$tabs = array(
    'general' => array(
        'title'       => __('General', 'wpgenious-job-listing'),
        'long_title'  => __('General settings', 'wpgenious-job-listing'),
        'description' => '',
        'view'        => 'wpgenious-job-listing-admin-display-general',
    ),
    'form' => array(
        'title'       => __('Form', 'wpgenious-job-listing'),
        'long_title'  => __('Apply form settings', 'wpgenious-job-listing'),
        'description' => '',
        'view'        => 'wpgenious-job-listing-admin-display-form',
    ),
    'job-fields' => array(
        'title'       => __('Job Fields', 'wpgenious-job-listing'),
        'long_title'  => __('Job custom fields', 'wpgenious-job-listing'),
        'description' => '',
        'view'        => 'wpgenious-job-listing-admin-display-fields',
    ),
    'appearance' => array(
        'title'       => __('Appearance', 'wpgenious-job-listing'),
        'long_title'  => __('Appearance', 'wpgenious-job-listing'),
        'description' => '',
        'view'        => 'wpgenious-job-listing-admin-display-appearance',
    ),
    'notification' => array(
        'title'       => __('E-mail Notifications', 'wpgenious-job-listing'),
        'long_title'  => __('E-mail notifications', 'wpgenious-job-listing'),
        'description' => '',
        'view'        => 'wpgenious-job-listing-admin-display-notification',
    ),
);

?>

<div class="wrap">

    <h1><?php echo __('Settings', 'wpgenious-job-listing') ?></h1>

    <h2 class="nav-tab-wrapper">
        <?php foreach ( $tabs as $tab_id => $tab ) : ?>
            <a href="?post_type=job_listing&page=<?php echo $this->plugin_name; ?>&tab=<?php echo $tab_id; ?>"
               class="nav-tab <?php echo $active_tab === $tab_id ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html($tab['title']); ?>
            </a>
        <?php endforeach; ?>
    </h2>
    <div class="tab-content">
        <h2><?php echo esc_html_e((string) $tabs[ $active_tab ]['long_title'], 'wpgenious-job-listing'); ?></h2>
        <p><?php echo esc_html_e((string) $tabs[ $active_tab ]['description'], 'wpgenious-job-listing'); ?></p>
        <?php
        if (! empty($tabs[ $active_tab ]['view']) && file_exists(
                WPGENIOUS_JOB_LISTING_ADMIN_DIR . 'partials/' . $tabs[ $active_tab ]['view'] . '.php'
        )
        ) {
            include $tabs[ $active_tab ]['view'] . '.php';
        } else {
            echo '<p>' . __('Coming soon...', 'wpgenious-job-listing') . '</p>';
        }
        ?>
    </div>
</div>

