<?php
/**
 * Template for displaying single job listing content
 *
 * Override this by copying it to current-theme/wpgenious-job-listing/single-job_listing.php
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public/partials
 */

if (! defined('ABSPATH') ) {
    exit;
}

$job_layout_cols = wpgenious_get_plugin_option('single_page_layout');
$display_job_custom_fields = wpgenious_get_plugin_option('single_display_job_custom_fields');
$job_custom_fields_position = wpgenious_get_plugin_option('single_custom_fields_position');

/**
 * before_wpgenious_job_listing_content hook
 *
 * @since 1.0.0
 */
do_action('before_jobs_offer_template');

?>

    <div class="wpgenious-job-listing-main single-wpgenious_job_listing-main <?php echo ($job_layout_cols === '1') ? 'wpgenious-job-listing-single-col' : 'wpgenious-job-listing-two-cols'; ?>">
        <div class="wpgenious-job-listing-col wpgenious-job-listing-job-content">

            <?php if ($job_custom_fields_position === 'before' && ((bool) $display_job_custom_fields)) : ?>
                <?php include_once plugin_dir_path(__FILE__).'custom-fields.php';  ?>
            <?php endif; ?>

            <?php
            /**
             * before_wpgenious_job_listing_content hook
             *
             * @since 1.0.0
             */
            do_action('before_wpgenious_job_listing_content');
            ?>

            <?php
                // override the_content filter
                echo $content;
            ?>

            <?php
            /**
             * after_wpgenious_job_listing_content hook
             *
             * @since 1.0.0
             */
            do_action('after_wpgenious_job_listing_content');
            ?>

            <?php if ($job_custom_fields_position === 'after' && ((bool) $display_job_custom_fields)) : ?>
                <?php include_once plugin_dir_path(__FILE__).'custom-fields.php';  ?>
            <?php endif; ?>

        </div>

        <div class="wpgenious-job-listing-col wpgenious-job-listing-job-form">
            <?php require_once plugin_dir_path(__FILE__).'form.php';  ?>
        </div>

    </div>

<?php

/**
 * after_wpgenious_job_listing_content hook
 *
 * @since 1.0.0
 */
do_action('after_wpgenious_job_listing_template');
?>
