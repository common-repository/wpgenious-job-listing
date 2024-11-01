<?php
/**
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 */

/**
 * Manage Notifications.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 * @author     wpgenious <contact@wpgenious.com>
 */

class Wpgenious_Job_Listing_Notification
{
    private static $instance = null;

    /**
     * @return Wpgenious_Job_Listing_Notification
     */
    public static function init()
    {
        return (self::$instance === null) ? (self::$instance = new self()) : self::$instance;
    }

    /**
     * @param $data
     */
    public function email_notification($data)
    {
        do_action('before_job_email_notification', $data);

        $tags = $this->get_tags($data);
        $search = array_keys($tags);
        $replace = array_values($tags);

        $applicant = array(
            'from' => wpgenious_get_plugin_option('from_email_notification'),
            'reply_to' => wpgenious_get_plugin_option('reply_to_notification'),
            'to' => wpgenious_get_plugin_option('applicant_notification'),
            'cc' => wpgenious_get_plugin_option('hr_notification'),
            'subject' => wpgenious_get_plugin_option('notification_subject'),
            'content' => wpgenious_get_plugin_option('notification_content'),
        );

        // Replace tags
        foreach ($applicant as $key => $value) {
            $applicant[$key] = str_replace($search, $replace, $value);
        }

        // Send email to applicant
        $this->send_email($applicant);

        $admin = array(
            'from' => wpgenious_get_plugin_option('admin_from_email_notification'),
            'reply_to' => wpgenious_get_plugin_option('admin_reply_to_notification'),
            'to' => wpgenious_get_plugin_option('admin_to_notification'),
            'cc' => wpgenious_get_plugin_option('admin_hr_notification'),
            'subject' => wpgenious_get_plugin_option('admin_notification_subject'),
            'content' => wpgenious_get_plugin_option('admin_notification_content'),
        );

        // Replace tags
        foreach ($admin as $key => $value) {
            $admin[$key] = str_replace($search, $replace, $value);
        }

        // Send email to admin
        $this->send_email($applicant);

        do_action('after_job_email_notification', $data);
    }

    /**
     * @param  $data
     * @return bool
     */
    public function send_email($data)
    {
        $company = wpgenious_get_plugin_option('company_name', '');
        $from    = ( ! empty($company) ) ? $company : get_option('blogname');
        $content = !empty($data['content']) ? nl2br($data['content']) : '';
        $subject = !empty($data['subject']) ? $data['subject'] : '';
        $to = !empty($data['to']) ? nl2br($data['to']) : '';
        $cc = !empty($data['cc']) ? $data['cc'] : '';
        $is_mail_send = false;

        /**
         * Filters the notification mail headers.
         *
         * @since 1.0.0
         *
         * @param array $headers Additional headers
         */
        $headers = apply_filters(
            'applicant_notification_mail_headers',
            array(
                'content_type' => 'Content-Type: text/html; charset=UTF-8',
                'from'         => sprintf('From: %1$s <%2$s>', $from, $data['from']),
                'reply_to'     => 'Reply-To: ' . $data['to'],
                'cc'           => 'Cc: ' . $data['cc'],
            )
        );

        if (empty($to) ) {
            unset($headers['reply_to']);
        }

        if (empty($cc) ) {
            unset($headers['reply_to']);
        }

        if(!empty($to)) {
            $is_mail_send = wp_mail($to, $subject, $content, array_values($headers));
        }

        return $is_mail_send;
    }

    /**
     * @param  $data
     * @return array
     */
    public function get_tags($data)
    {
        $company_name = wpgenious_get_plugin_option('company_name') ? wpgenious_get_plugin_option('company_name') : get_bloginfo('name');
        $admin_email = get_option('admin_email');
        $hr_email = wpgenious_get_plugin_option('hr_email');

        return array(
            '{applicant}' => $data['applicant_name'],
            '{application-id}' => $data['application_id'],
            '{applicant-email}' => $data['applicant_email'],
            '{applicant-phone}' => $data['applicant_phone'],
            '{applicant-resume}' => 'resumer ',
            '{applicant-cover}' => $data['applicant_letter'],
            '{job-title}' => $data['apply_for'],
            '{job-id}' => $data['job_id'],
            '{job-expiry}' => '{job-expiry}',//$data[''],
            '{site-title}'   => esc_html(get_bloginfo('name')),
            '{site-description}' => esc_html(get_bloginfo('description')),
            '{site-url}'     => esc_url(site_url('/')),
            '{company}'      => esc_html($company_name),
            '{admin-email}'  => esc_html($admin_email),
            '{hr-email}'     => esc_html($hr_email),
        );
    }
}
