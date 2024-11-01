<?php
/**
 * Email notifications settings
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

$options = array(
    'from_email_notification' => wpgenious_get_plugin_option('from_email_notification'),
    'reply_to_notification' => wpgenious_get_plugin_option('reply_to_notification'),
    'applicant_notification' => wpgenious_get_plugin_option('applicant_notification'),
    'hr_notification' => wpgenious_get_plugin_option('hr_notification'),
    'notification_subject' => wpgenious_get_plugin_option('notification_subject'),
    'notification_content' => wpgenious_get_plugin_option('notification_content'),
    'admin_from_email_notification' => wpgenious_get_plugin_option('admin_from_email_notification'),
    'admin_reply_to_notification' => wpgenious_get_plugin_option('admin_reply_to_notification'),
    'admin_to_notification' => wpgenious_get_plugin_option('admin_to_notification'),
    'admin_hr_notification' => wpgenious_get_plugin_option('admin_hr_notification'),
    'admin_notification_subject' => wpgenious_get_plugin_option('admin_notification_subject'),
    'admin_notification_content' => wpgenious_get_plugin_option('admin_notification_content')
);

?>
<div class="wpgenious-job-listing-admin-settings">
    <div id="wpgenious-job-listing-settings-form" class="wpgenious-job-listing-admin-settings">
        <div class="wpgenious-job-listing-col-7">
            <div class="wrapper">
                <div class="tabs">
                    <div class="tabs__header">
                        <div class="tabs__heading tab-1 is-active" data-tab-index="tab-1">
                            <?php echo __('Application Received ( Applicant Notification )', 'wpgenious-job-listing'); ?>
                        </div>
                        <div class="tabs__heading tab-2" data-tab-index="tab-2">
                            <?php echo __('Application Received ( Admin Notification )', 'wpgenious-job-listing'); ?>
                        </div>
                    </div>
                    <div class="tabs__body">
                        <form method="POST" action="options.php">
                            <?php
                            settings_fields('wpgenious-job-listing');
                            ?>
                            <div class="tabs__content tab-1 is-active">
                                <div class="wpgenious-job-listing-container">
                                        <div class="wpgenious-job-listing-row">
                                            <div class="wpgenious-job-listing-col col-half">
                                                <label for="wpgenious-job-listing[from_email_notification]">
                                                    <?php echo __('From : ', 'wpgenious-job-listing'); ?>
                                                </label>
                                                <input class="wpgenious-job-listing-input" type="email" value="<?php echo esc_html($options['from_email_notification']); ?>" name="wpgenious-job-listing[from_email_notification]">
                                            </div>
                                            <div class="wpgenious-job-listing-col col-half">
                                                <label for="wpgenious-job-listing[reply_to_notification]">
                                                    <?php echo __('Reply-To : ', 'wpgenious-job-listing'); ?>
                                                </label>
                                                <input class="wpgenious-job-listing-input" type="text" value="<?php echo esc_html($options['reply_to_notification']); ?>" name="wpgenious-job-listing[reply_to_notification]">
                                            </div>
                                        </div>
                                        <div class="wpgenious-job-listing-row">
                                            <div class="wpgenious-job-listing-col col-half">
                                                <label for="wpgenious-job-listing[applicant_notification]">
                                                    <?php echo __('To : ', 'wpgenious-job-listing'); ?>
                                                </label>
                                                <input class="wpgenious-job-listing-input" type="text" readonly disabled value="<?php echo esc_html($options['applicant_notification']); ?>" name="wpgenious-job-listing[applicant_notification]">
                                            </div>
                                            <div class="wpgenious-job-listing-col col-half">
                                                <label for="wpgenious-job-listing[hr_notification]">
                                                    <?php echo __('CC : ', 'wpgenious-job-listing'); ?>
                                                </label>
                                                <input class="wpgenious-job-listing-input" type="email" value="<?php echo esc_html($options['hr_notification']); ?>" name="wpgenious-job-listing[hr_notification]">
                                            </div>
                                        </div>
                                        <div class="wpgenious-job-listing-row">
                                            <div class="wpgenious-job-listing-col col-full">
                                                <label for="wpgenious-job-listing[notification_subject]">
                                                    <?php echo __('Subject : ', 'wpgenious-job-listing'); ?>
                                                </label>
                                                <input class="wpgenious-job-listing-input" type="text" value="<?php echo esc_html($options['notification_subject']); ?>" name="wpgenious-job-listing[notification_subject]">
                                            </div>
                                        </div>
                                        <div class="wpgenious-job-listing-row">
                                            <div class="wpgenious-job-listing-col col-full">
                                                <label for="wpgenious-job-listing[notification_content]">
                                                    <?php echo __('Content : ', 'wpgenious-job-listing'); ?>
                                                </label>
                                                <?php wp_editor($options['notification_content'], 'notification_content', array('textarea_rows' => 6, 'textarea_name' => 'wpgenious-job-listing[notification_content]')); ?>
                                            </div>
                                        </div>
                                        <div class="wpgenious-job-listing-row">
                                            <div class="wpgenious-job-listing-col col-full">
                                                <?php submit_button(); ?>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="tabs__content tab-2">
                                <div class="wpgenious-job-listing-container">
                                    <div class="wpgenious-job-listing-row">
                                        <div class="wpgenious-job-listing-col col-half">
                                            <label for="wpgenious-job-listing[admin_from_email_notification]">
                                                <?php echo __('From : ', 'wpgenious-job-listing'); ?>
                                            </label>
                                            <input class="wpgenious-job-listing-input" type="email" value="<?php echo esc_html($options['admin_from_email_notification']); ?>" name="wpgenious-job-listing[admin_from_email_notification]">
                                        </div>
                                        <div class="wpgenious-job-listing-col col-half">
                                            <label for="wpgenious-job-listing[admin_reply_to_notification]">
                                                <?php echo __('Reply-To : ', 'wpgenious-job-listing'); ?>
                                            </label>
                                            <input class="wpgenious-job-listing-input" type="text" value="<?php echo esc_html($options['admin_reply_to_notification']); ?>" name="wpgenious-job-listing[admin_reply_to_notification]">
                                        </div>
                                    </div>
                                    <div class="wpgenious-job-listing-row">
                                        <div class="wpgenious-job-listing-col col-half">
                                            <label for="wpgenious-job-listing[admin_to_notification]">
                                                <?php echo __('To : ', 'wpgenious-job-listing'); ?>
                                            </label>
                                            <input class="wpgenious-job-listing-input" type="email" value="<?php echo esc_html($options['admin_to_notification']); ?>" name="wpgenious-job-listing[admin_to_notification]">
                                        </div>
                                        <div class="wpgenious-job-listing-col col-half">
                                            <label for="wpgenious-job-listing[admin_hr_notification]">
                                                <?php echo __('CC : ', 'wpgenious-job-listing'); ?>
                                            </label>
                                            <input class="wpgenious-job-listing-input" type="email" value="<?php echo esc_html($options['admin_hr_notification']); ?>" name="wpgenious-job-listing[admin_hr_notification]">
                                        </div>
                                    </div>
                                    <div class="wpgenious-job-listing-row">
                                        <div class="wpgenious-job-listing-col col-full">
                                            <label for="wpgenious-job-listing[admin_notification_subject]">
                                                <?php echo __('Subject : ', 'wpgenious-job-listing'); ?>
                                            </label>
                                            <input class="wpgenious-job-listing-input" type="text" value="<?php echo esc_html($options['admin_notification_subject']); ?>" name="wpgenious-job-listing[admin_notification_subject]">
                                        </div>
                                    </div>
                                    <div class="wpgenious-job-listing-row">
                                        <div class="wpgenious-job-listing-col col-full">
                                            <label for="wpgenious-job-listing[admin_notification_content]">
                                                <?php echo __('Content : ', 'wpgenious-job-listing'); ?>
                                            </label>
                                            <?php wp_editor($options['admin_notification_content'], 'admin_notification_content', array('textarea_rows' => 6, 'textarea_name' => 'wpgenious-job-listing[admin_notification_content]')); ?>
                                        </div>
                                    </div>
                                    <div class="wpgenious-job-listing-row">
                                        <div class="wpgenious-job-listing-col col-full">
                                            <?php submit_button(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgenious-job-listing-col-3">
            <div class="wpgenious-job-listing-aside">
                <div class="wpgenious-job-listing-row">
                    <div class="wpgenious-job-listing-col col-full">
                        <h3><?php echo __('Template Tags', 'wpgenious-job-listing'); ?></h3>
                        <div class="tags">
                            <table>
                                <tr>
                                    <td><?php echo __('Applicant Name : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{applicant}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Application ID : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{applicant-id}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Applicant Email : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{applicant-email}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Applicant Phone : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{applicant-phone}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Applicant Resume : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{applicant-resume}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Cover letter : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{applicant-cover}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Job Title : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{job-title}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Job ID : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{job-id}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Job Expiry Date : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{job-expiry}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Site admin email : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{admin-email}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Site name : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{site-name}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Site title : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{site-title}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Site description : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{site-description}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Site Url : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{site-url}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Company : ', 'wpgenious-job-listing'); ?></td>
                                    <td>{company}</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('HR Email	: ', 'wpgenious-job-listing'); ?></td>
                                    <td>{hr-email}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
