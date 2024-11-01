<?php

/**
 * Fired during plugin activation
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 * @author     wpgenious <contact@wpgenious.com>
 */

require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-job-listing-tools.php';

class Wpgenious_Job_Listing_Activator
{

    /**
     *
     * @since 1.0.0
     */
    public static function activate()
    {
        self::default_settings();
    }

    /**
     * Register default settings
     */
    public static function default_settings()
    {
        if ((bool) get_option('wpgenious_job_listing_register_default_settings') ) {
            return ;
        }

        $settings = array(
            'company_name' => get_bloginfo('name'),
            'hr_email' => '',
            'archive_slug' => 'jobs',
            'default_msg_no_job_found' => __('No job found . ', 'wpgenious-job-listing'),
            'gdpr_field_enabled' => true,
            'g_recaptcha_enabled' => false,
            'g_recaptcha_key' => '',
            'g_recaptcha_secret' => '',
            'gdpr_field_text' => __('By using this form you agree with our RDPG policy . ', 'wpgenious-job-listing'),
            'allowed_upload_file_ext' => Wpgenious_Job_Listing_Tools::allowed_file_extensions(),
            'single_page_layout' => '2',
            'single_display_job_custom_fields' => true,
            'single_custom_fields_position' => 'after',
            'listing_view' => '1',
            'listing_page_type' => 'list',
            'listing_grid_number_columns' => '3',
            'listing_jobs_per_page' => '9',
            'listing_display_search_form' => true,
            'listing_search_by' => array('job-category', 'job-type', 'job-location'),
            'from_email_notification' => get_option('admin_email'),
            'reply_to_notification' => '{job-title}',
            'applicant_notification' => '{applicant-email}',
            'hr_notification' => '',
            'notification_subject' => __('Thanks for submitting your application for a job for ', 'wpgenious-job-listing').'{job-title}',
            'notification_content' => 'Dear {applicant}, <br> <p>This letter is to inform you that we received your application for <a href="{job-link}"><strong>{job-title}</strong></strong></a>. We appreciate you taking the time to apply. </p><p>Thank you again for the time you invested in your application.</p> <p>With appreciation,</p>',
            'admin_from_email_notification' => get_option('admin_email'),
            'admin_reply_to_notification' => '{applicant-email}',
            'admin_to_notification' => '',
            'admin_hr_notification' => '',
            'admin_notification_subject' => __('New application for the position {job-title} - {job-id}'),
            'admin_notification_content' => '<p>New application :<br> Job : <a href="{job-link}"><strong>{job-title} - {job-id}</strong></a> </p><p>Applicant <br>Name: {applicant}<br>Email: {applicant-email}<br>Phone: {applicant-phone}<br>Resume: {applicant-resume}<br>Cover letter: {applicant-cover}</p><p> Email received from {site-name}</p>',
            'job_custom_fields' => serialize(array(
                array( 'name' => 'Job Category', 'slug' => 'job-category', 'icon' => '', 'itemprop' => '' ),
                array( 'name' => 'Job Type', 'slug' => 'job-type', 'icon' => '' , 'itemprop' => '' ),
                array( 'name' => 'Job Location', 'slug' => 'job-location', 'icon' => '', 'itemprop' => '' )
            )),
            'application_status' => array('new' => array('name' => 'New', 'color' => '#807c67'),'in-progress' => array('name' => 'In progress', 'color' => '#d39953'),'shortlisted' => array('name' => 'Shortlisted', 'color' => '#3780a5'),'rejected' => array('name' => 'Rejected', 'color' => '#b14745'),'selected' => array('name' => 'Selected', 'color' => '#448244'),)
        );

        update_option('wpgenious-job-listing', $settings);

        update_option('wpgenious_job_listing_register_default_settings', 1);
    }
}
