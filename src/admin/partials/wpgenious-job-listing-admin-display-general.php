<?php

if (! defined('ABSPATH') ) {
    exit;
}

$options = array(
    'company_name' => wpgenious_get_plugin_option('company_name'),
    'hr_email' => wpgenious_get_plugin_option('hr_email'),
    'archive_slug' => wpgenious_get_plugin_option('archive_slug'),
    'default_msg_no_job_found' => wpgenious_get_plugin_option('default_msg_no_job_found'),
);

?>

<!-- General settings -->
<div id="wpgenious-job-listing-settings-form" class="wpgenious-job-listing-admin-settings">
    <form method="POST" action="options.php">
        <?php
        settings_fields('wpgenious-job-listing');
        ?>
        <div class="wpgenious-job-listing-form-settings-main">
            <table class="form-table">
                <tbody>
                <!-- Company -->
                <tr>
                    <th class="row">
                        <label for="wpgenious-job-listing[company_name]">
                            <?php echo __('Name of the Company : ', 'wpgenious-job-listing'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo esc_html($options['company_name']); ?>" name="wpgenious-job-listing[company_name]">
                    </td>
                </tr>
                <!-- hr email -->
                <tr>
                    <th class="row">
                        <label for="wpgenious-job-listing[hr_email]">
                            <?php echo __('HR Email	: ', 'wpgenious-job-listing'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="email" value="<?php echo esc_html($options['hr_email']); ?>" name="wpgenious-job-listing[hr_email]">
                        <p class="description"><?php echo __('Email for notifications', 'wpgenious-job-listing'); ?></p>
                    </td>
                </tr>
                <!-- slug -->
                <tr>
                    <th class="row">
                        <label for="wpgenious-job-listing[archive_slug]">
                            <?php echo __('URL slug for jobs : ', 'wpgenious-job-listing'); ?>
                        </label>
                    </th>
                    <td>
                        <input required type="text" value="<?php echo esc_html($options['archive_slug']); ?>" name="wpgenious-job-listing[archive_slug]">
                        <p class="description"><?php echo __('For jobs archive page url', 'wpgenious-job-listing'); ?></p>
                    </td>
                </tr>
                <!-- no job found -->
                <tr>
                    <th class="row">
                        <label for="wpgenious-job-listing[default_msg_no_job_found]">
                            <?php echo __('Default message for ( no job ) : ', 'wpgenious-job-listing'); ?>
                        </label>
                    </th>
                    <td>
                        <input required type="text" value="<?php echo esc_html($options['default_msg_no_job_found']); ?>" name="wpgenious-job-listing[default_msg_no_job_found]">
                        <p class="description"><?php echo __('No job found message', 'wpgenious-job-listing'); ?></p>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <?php submit_button(); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
