<?php
/**
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 */

/**
 * Manage Applications.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 * @author     wpgenious <contact@wpgenious.com>
 */

require_once WPGENIOUS_JOB_LISTING_PUBLIC_DIR.'class/class-wpgenious-job-listing-notification.php';
require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-job-listing-tools.php';

class Wpgenious_Job_Listing_Application
{
    private static $instance = null;

    /**
     * @return Wpgenious_Job_Listing_Application
     */
    public static function init()
    {
        return (  self::$instance === null ) ? ( self::$instance = new self() ) : self::$instance;
    }

    /**
     * @param $file
     * @return array
     */
    private function wpg_validate_file($file) {
        $allowed_upload_file_ext = wpgenious_get_plugin_option('allowed_upload_file_ext');
        $file['name'] = sanitize_file_name($file['name']);
        $file['type'] = sanitize_mime_type($file['type']);
        $ext =  pathinfo($file['name'], PATHINFO_EXTENSION);
        if(!in_array($ext, $allowed_upload_file_ext)){
            return array(
                'error' => esc_html__(
                    sprintf(esc_html__('Extension ( %1$s ) is not allowed.', 'wpgenious-job-listing'), $ext)
                    , 'wpgenious-job-listing'
                )
            );
        }

        return $file;
    }

    /**
     * Create new application
     */
    public function create_application()
    {
        $response = array(
            'success' => array(),
            'error'   => array(),
        );

        // Google reCaptcha Validation
        $g_recaptcha_enabled = wpgenious_get_plugin_option('g_recaptcha_enabled');

        if( $g_recaptcha_enabled ) {
            $recaptcha_secret = wpgenious_get_plugin_option('g_recaptcha_secret');

            if ( empty( $_POST['g-recaptcha-response'] ) ) {
                $response['error'][] = esc_html__('Invalid recaptcha. Please reload page.', 'wpgenious-job-listing');
            } else {
                $args = array(
                    'body' => array(
                        'secret'   => $recaptcha_secret,
                        'response' => $_REQUEST['g-recaptcha-response'],
                        'remoteip' => array_key_exists(
                            'HTTP_X_FORWARDED_FOR',
                            $_SERVER
                        ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    ),
                );

                $captcha = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', $args );
                $captcha = json_decode( $captcha['body'] );

                if( empty( $captcha->success ) || ! $captcha->success || $captcha->score < 0.5 ) {
                    $response['error'][] = esc_html__('Error with captcha. Please reload page and re-submit your application.', 'wpgenious-job-listing');
                }
            }

            if( count($response['error']) > 0 ) {
                wp_send_json($response);
                exit();
            }
        }

        $action = sanitize_text_field($_POST['action']);

        if(($_SERVER['REQUEST_METHOD'] === 'POST') && ($action === 'wpgenious-job-listing_apply_form_submission')) {
            $job_id = (int) $_POST['job_id'];
            $applicant_name       = sanitize_text_field($_POST['wpgenious_job_listing_candidature_name']);
            $applicant_email      = sanitize_email($_POST['wpgenious_job_listing_candidature_email']);
            $applicant_phone      = sanitize_text_field($_POST['wpgenious_job_listing_candidature_phone']);
            $applicant_letter     = sanitize_text_field($_POST['wpgenious_job_listing_candidature_letter']);
            $attachment           = $this->wpg_validate_file($_FILES['wpgenious_job_listing_candidature_file']);
            $agree_gdpr           = sanitize_text_field($_POST['gdpr']) === 'yes';
            $default_error_msg    = esc_html__('Error in submitting your application. Please refresh the page and retry.', 'wpgenious-job-listing');
            $gdpr_field_enabled   = wpgenious_get_plugin_option('gdpr_field_enabled');

            if (get_post_type($job_id) !== 'job_listing' ) {
                $response['error'][] = esc_html__('Error occurred: Invalid Job.', 'wpgenious-job-listing');
            }

            if (get_post_status($job_id) === 'expired' ) {
                $response['error'][] = esc_html__('Sorry! This job is expired.', 'wpgenious-job-listing');
            }

            if (empty($applicant_name) ) {
                $response['error'][] = esc_html__('Name is required.', 'wpgenious-job-listing');
            }

            if (empty($applicant_email) ) {
                $response['error'][] = esc_html__('Email is required.', 'wpgenious-job-listing');
            } elseif (! filter_var($applicant_email, FILTER_VALIDATE_EMAIL) ) {
                $response['error'][] = esc_html__('Invalid email format.', 'wpgenious-job-listing');
            }

            if (empty($applicant_phone) ) {
                $response['error'][] = esc_html__('Contact number is required.', 'wpgenious-job-listing');
            } elseif (! preg_match('%^[+]?[0-9()/ -]*$%', trim($applicant_phone)) ) {
                $response['error'][] = esc_html__('Invalid phone number.', 'wpgenious-job-listing');
            }

            if ($attachment['error'] > 0 ) {
                $response['error'][] = esc_html__('Please select your cv/resume.', 'wpgenious-job-listing');
            }

            if((bool)$gdpr_field_enabled && !$agree_gdpr) {
                $response['error'][] = esc_html__( 'Please agree to our privacy policy.', 'wpgenious-job-listing' );
            }

            if (count($response['error']) === 0 ) {

                if (! function_exists('wp_handle_upload') ) {
                    include ABSPATH . 'wp-admin/includes/file.php';
                }
                if (! function_exists('wp_crop_image') ) {
                    include ABSPATH . 'wp-admin/includes/image.php';
                }

                $mimes              = array();
                $allowed_mime_types = get_allowed_mime_types();
                $allowed_extensions = Wpgenious_Job_Listing_Tools::allowed_file_extensions();

                foreach ( $allowed_extensions as $allowed_extension ) {
                    if (isset($allowed_mime_types[ $allowed_extension ]) ) {
                        $mimes[ $allowed_extension ] = $allowed_mime_types[ $allowed_extension ];
                    }
                }

                $override = array(
                    'test_form'                => false,
                    'mimes'                    => $mimes,
                    'unique_filename_callback' => array( $this, 'hash_file_name' ),
                );

                add_filter('upload_dir', array( $this, 'upload_dir' ));
                $file = wp_handle_upload($attachment, $override);
                remove_filter('upload_dir', array( $this, 'upload_dir' ));

                if ($file && empty($file['error']) ) {
                    $post_data   = array(
                        'post_title'     => $applicant_name,
                        'post_content'   => '',
                        'post_status'    => 'publish',
                        'comment_status' => 'closed',
                    );

                    $application_data = array_merge(
                        $post_data,
                        array(
                            'post_type'   => 'job_application',
                            'post_parent' => $job_id,
                        )
                    );

                    $application_id   = wp_insert_post($application_data);

                    if (! empty($application_id) && ! is_wp_error($application_id) ) {
                        $attachment_data = array_merge(
                            $post_data,
                            array(
                                'post_mime_type' => $file['type'],
                                'guid'           => $file['url'],
                            )
                        );
                        $attachment_id = wp_insert_attachment($attachment_data, $file['file'], $application_id);

                        if (! empty($attachment_id) && ! is_wp_error($attachment_id) ) {

                            $attach_data = wp_generate_attachment_metadata($attachment_id, $file['file']);
                            wp_update_attachment_metadata($attachment_id, $attach_data);
                            $applicant_details = array(
                                'job_id'           => $job_id,
                                'apply_for'        => esc_html(get_the_title($job_id)),
                                'applicant_ip'     => !empty($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '',
                                'applicant_name'   => $applicant_name,
                                'applicant_email'  => $applicant_email,
                                'applicant_phone'  => $applicant_phone,
                                'applicant_letter' => $applicant_letter,
                                'attachment_id'    => $attachment_id,
                                'applicant_date'   => date("Y-m-d H:i"),
                                'applicant_status' => 'new'
                            );

                            if((bool)$gdpr_field_enabled) {
                                $applicant_details['agree_gdpr'] = $agree_gdpr;
                            }

                            foreach ( $applicant_details as $key => $value ) {
                                update_post_meta($application_id, $key, $value);
                            }

                            $applicant_details['application_id'] = $application_id;
                            // Now, send notification email
                            Wpgenious_Job_Listing_Notification::init()->email_notification($applicant_details);

                            $response['success'][] = esc_html__('Your application has been submitted.', 'wpgenious-job-listing');
                        }else {
                            $response['error'][] = $default_error_msg;
                        }
                    }else {
                        $response['error'][] = $default_error_msg;
                    }
                }else {
                    $response['error'][] = $file['error'];
                }
            }
        }

        wp_send_json($response);
    }

    /**
     * hash file name
     *
     * @param  $name
     * @param  $ext
     * @return string
     */
    public function hash_file_name( $name, $ext )
    {
        $file_name = hash('sha1', ( $name . uniqid(mt_rand(), true) )) . time();
        return sanitize_file_name($file_name . $ext);
    }

    /**
     * @param  $param
     * @return mixed
     */
    public function upload_dir( $param )
    {
        if (!empty($_POST['action']) && ($_POST['action'] === 'wpgenious-job-listing_apply_form_submission') ) {
            $subdir = '/' . WPGENIOUS_JOB_LISTING_UPLOAD_DIR_NAME;
            if (empty($param['subdir']) ) {
                $param['path']   = $param['path'] . $subdir;
                $param['url']    = $param['url'] . $subdir;
                $param['subdir'] = $subdir;
            } else {
                $subdir         .= $param['subdir'];
                $param['path']   = str_replace($param['subdir'], $subdir, $param['path']);
                $param['url']    = str_replace($param['subdir'], $subdir, $param['url']);
                $param['subdir'] = str_replace($param['subdir'], $subdir, $param['subdir']);
            }
        }

        return $param;
    }
}
