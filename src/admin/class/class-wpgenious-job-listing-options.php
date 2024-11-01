<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/class/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/class/admin
 * @author     wpgenious <contact@wpgenious.com>
 */

if (! defined('ABSPATH') ) {
    exit;
}

require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-job-listing-tools.php';

class Wpgenious_Job_Listing_Options
{

    private $plugin_name = 'wpgenious-job-listing';

    private static $instance = null;

    /**
     * @return Wpgenious_Job_Listing_Options
     */
    public static function init()
    {
        return (  self::$instance  === null ) ? new self() : self::$instance;
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate_settings' ) );
        if(!get_option('wpgenious-job-listing_flush_rewrite_rules')) {
            flush_rewrite_rules();
            update_option('wpgenious-job-listing_flush_rewrite_rules', true);
        }
    }

    public function validate_settings($input) {
        $settings = get_option($this->plugin_name);

        /********* General settings ************/
        if(isset($input['company_name'])) {
            $settings['company_name'] = sanitize_text_field($input['company_name']);
        }
        if(isset($input['hr_email'])) {
            $settings['hr_email'] = sanitize_text_field($input['hr_email']);
        }
        if(isset($input['archive_slug'])) {
            $settings['archive_slug'] = $this->wpg_sanitize_jobs_archive_slug($input['archive_slug']);
            update_option('wpgenious-job-listing_flush_rewrite_rules', false);
        }
        if(isset($input['default_msg_no_job_found'])) {
            $settings['default_msg_no_job_found'] = sanitize_text_field($input['default_msg_no_job_found']);
        }

        /********* Form settings ************/
        if(isset($input['gdpr_field_enabled'])) {
            $settings['gdpr_field_enabled'] = sanitize_text_field($input['gdpr_field_enabled']);
        }
        if(isset($input['gdpr_field_text'])) {
            $settings['gdpr_field_text'] = $this->gdpr_text_handle($input['gdpr_field_text']);
        }
        if(isset($input['allowed_upload_file_ext'])) {
            $settings['allowed_upload_file_ext'] = $this->wpg_sanitize_upload_file_extns($input['allowed_upload_file_ext']);
        }
        if (isset($input['g_recaptcha_enabled'])) {
            $settings['g_recaptcha_enabled'] = (bool) sanitize_text_field($input['g_recaptcha_enabled']);
        }
        if (isset($input['g_recaptcha_key'])) {
            $settings['g_recaptcha_key'] = sanitize_text_field($input['g_recaptcha_key']);
        }
        if (isset($input['g_recaptcha_secret'])) {
            $settings['g_recaptcha_secret'] = sanitize_text_field($input['g_recaptcha_secret']);
        }

        /********* Custom Job Fields settings ************/
        if(isset($input['job_custom_fields'])) {
            $settings['job_custom_fields'] = $this->wpg_sanitize_job_fields($input['job_custom_fields']);
        }

        /********* Single and archive page layout settings ************/
        if(isset($input['single_page_layout'])) {
            $settings['single_page_layout'] = sanitize_text_field($input['single_page_layout']);
        }
        if(isset($input['single_display_job_custom_fields'])) {
            $settings['single_display_job_custom_fields'] = sanitize_text_field($input['single_display_job_custom_fields']);
        }
        if(isset($input['single_custom_fields_position'])) {
            $settings['single_custom_fields_position'] = sanitize_text_field($input['single_custom_fields_position']);
        }
        if(isset($input['listing_view'])) {
            $settings['listing_view'] = sanitize_text_field($input['listing_view']);
        }
        if(isset($input['listing_page_type'])) {
            $settings['listing_page_type'] = sanitize_text_field($input['listing_page_type']);
        }
        if(isset($input['listing_grid_number_columns'])) {
            $settings['listing_grid_number_columns'] = sanitize_text_field($input['listing_grid_number_columns']);
        }
        if(isset($input['listing_jobs_per_page'])) {
            $settings['listing_jobs_per_page'] = sanitize_text_field($input['listing_jobs_per_page']);
        }
        if(isset($input['listing_display_search_form'])) {
            $settings['listing_display_search_form'] = sanitize_text_field($input['listing_display_search_form']);
        }
        if(isset($input['listing_search_by']) || isset($input['listing_display_search_form'])) {
            $settings['listing_search_by'] = $input['listing_search_by'] ? $this->wpg_sanitize_array_values($input['listing_search_by']) : [];
        }

        /********* Email notifications settings ************/
        if(isset($input['from_email_notification'])) {
            $settings['from_email_notification'] = sanitize_text_field($input['from_email_notification']);
        }
        if(isset($input['reply_to_notification'])) {
            $settings['reply_to_notification'] = sanitize_text_field($input['reply_to_notification']);
        }
        if(isset($input['applicant_notification'])) {
            $settings['applicant_notification'] = sanitize_text_field($input['applicant_notification']);
        }
        if(isset($input['hr_notification'])) {
            $settings['hr_notification'] = sanitize_text_field($input['hr_notification']);
        }
        if(isset($input['notification_subject'])) {
            $settings['notification_subject'] = sanitize_text_field($input['notification_subject']);
        }
        if(isset($input['notification_content'])) {
            $settings['notification_content'] = sanitize_text_field($input['notification_content']);
        }
        if(isset($input['admin_from_email_notification'])) {
            $settings['admin_from_email_notification'] = sanitize_text_field($input['admin_from_email_notification']);
        }
        if(isset($input['admin_reply_to_notification'])) {
            $settings['admin_reply_to_notification'] = sanitize_text_field($input['admin_reply_to_notification']);
        }
        if(isset($input['admin_to_notification'])) {
            $settings['admin_to_notification'] = sanitize_text_field($input['admin_to_notification']);
        }
        if(isset($input['admin_hr_notification'])) {
            $settings['admin_hr_notification'] = sanitize_text_field($input['admin_hr_notification']);
        }
        if(isset($input['admin_notification_subject'])) {
            $settings['admin_notification_subject'] = sanitize_text_field($input['admin_notification_subject']);
        }
        if(isset($input['admin_notification_content'])) {
            $settings['admin_notification_content'] = sanitize_text_field($input['admin_notification_content']);
        }

        return $settings;
    }

    /**
     * @param  $input
     * @return array
     */
    public function wpg_sanitize_upload_file_extns( $input )
    {
        $valid              = true;
        $allowed_extensions = Wpgenious_Job_Listing_Tools::allowed_file_extensions();
        $default_extensions = Wpgenious_Job_Listing_Tools::allowed_file_extensions();

        if (is_array($input) ) {
            foreach ( $input as $ext ) {
                if (! in_array($ext, $allowed_extensions, true) ) {
                    $valid = false;
                    break;
                }
            }
        }

        if(!$valid) {
            add_settings_error('allowed_upload_file_ext', 'wpgenious-job-listing-upload-file-extension', esc_html__('Error in saving file upload types!', 'wpgenious-job-listing'));
            return $default_extensions;
        }

        if (empty($input) ) {
            return $default_extensions;
        }

        return array_map('sanitize_text_field', $input);
    }

    /**
     * @param  $input
     * @return string
     */
    public function gdpr_text_handle( $input )
    {
        $gdpr_enable = wpgenious_get_plugin_option('gdpr_field_enabled');
        if (! empty($gdpr_enable) && empty($input) ) {
            $input = esc_html__('By using this form you agree with our RDPG policy.', 'wpgenious-job-listing');
        }
        return htmlentities($input, ENT_QUOTES);
    }

    /**
     * @param  $input
     * @return string
     */
    public function wpg_sanitize_jobs_archive_slug($input)
    {
        $old_value = wpgenious_get_plugin_option('archive_slug');

        if (empty($input) ) {
            add_settings_error('archive_slug', 'wpgenious-job-listing-jobs-archive-slug', esc_html__('URL slug cannot be empty.', 'wpgenious-job-listing'));
            $input = $old_value;
        }

        $slug = sanitize_title($input, 'jobs');
        $page = get_page_by_path($slug, ARRAY_N);

        if (! empty($page) && is_array($page) ) {
            add_settings_error('archive_slug', 'wpgenious-job-listing-jobs-archive-slug', esc_html__('Slug cannot be updated. A page with same slug exists. Please choose a different URL slug.', 'wpgenious-job-listing'));
            $slug = $old_value;
        }

        return $slug;
    }

    /***
     * @param  $input
     * @return array
     */
    public function wpg_sanitize_array_values( $input )
    {
        if (is_array($input) ) {
            $input = array_map('sanitize_text_field', $input);
        }
        return $input;
    }

    /**
     * @param  $input
     * @return mixed
     */
    public function wpg_sanitize_job_fields($input)
    {
        $old_value = wpgenious_get_plugin_option('job_custom_fields');

        if(!is_array($input)) {
            return $old_value;
        }

        $tax = [];

        foreach ($input as $key => $value) {
            if(empty($value['name'])) {
                return $old_value;
            }

            $slug = sanitize_title($value['name']);

            if(in_array($slug, $tax)) {
                return $old_value;
            }

            $tax[] = $input[$key]['slug'] = $slug;
        }

        return serialize($input);
    }
}
