<?php
/**
 * Provide a admin area view for the plugin
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

require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-job-listing-tools.php';

$options = array(
    'gdpr_field_enabled'      => wpgenious_get_plugin_option('gdpr_field_enabled'),
    'gdpr_field_text'         => wpgenious_get_plugin_option('gdpr_field_text'),
    'g_recaptcha_enabled'     => wpgenious_get_plugin_option('g_recaptcha_enabled'),
    'g_recaptcha_key'         => wpgenious_get_plugin_option('g_recaptcha_key'),
    'g_recaptcha_secret'      => wpgenious_get_plugin_option('g_recaptcha_secret'),
    'allowed_upload_file_ext' => wpgenious_get_plugin_option('allowed_upload_file_ext') ? wpgenious_get_plugin_option('allowed_upload_file_ext') : array(),
);

$allowed_extensions = Wpgenious_Job_Listing_Tools::allowed_file_extensions();
?>

<!-- Apply form settings -->
<div id="wpgenious-job-listing-settings-form" class="wpgenious-job-listing-admin-settings">
    <form method="POST" action="options.php">
        <?php
        settings_fields('wpgenious-job-listing');
        ?>
        <div class="wpgenious-job-listing-form-settings-main">
            <table class="form-table">
                <tbody>
                    <!-- Upload Supported extensions -->
                    <tr>
                        <th>
                            <h2><?php echo __('Upload options', 'wpgenious-job-listing'); ?></h2>
                        </th>
                    </tr>
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[allowed_upload_file_ext][]">
                                <?php echo __('Supported upload file types : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                        <td>
                            <?php foreach ($allowed_extensions as $allowed_extension): ?>
                                <input <?php echo (in_array($allowed_extension, $options['allowed_upload_file_ext'])) ? 'checked' : ''; ?> type="checkbox" name="wpgenious-job-listing[allowed_upload_file_ext][]" value="<?php echo esc_html($allowed_extension); ?>"/>
                                <?php echo esc_html_e(ucfirst($allowed_extension), 'wpgenious-job-listing'); ?> <br>
                            <?php endforeach; ?>
                            <span class="description">
                                <?php echo __('Select the supported file types for CV upload field', 'wpgenious-job-listing'); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- GDRP Field -->
                    <tr>
                        <th>
                            <h2><?php echo __('GDPR Compliance', 'wpgenious-job-listing'); ?></h2>
                        </th>
                    </tr>
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[gdpr_field_enabled]">
                                <?php echo __('Enable the GDPR : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                        <td>
                            <input <?php echo ( (bool) esc_html($options['gdpr_field_enabled'])) ? 'checked' : ''; ?> type="radio" id="gdpr_field_enabled_1" name="wpgenious-job-listing[gdpr_field_enabled]" value="1"/>
                            <?php echo __('Yes', 'wpgenious-job-listing'); ?>
                            <input <?php echo ( !(bool) esc_html($options['gdpr_field_enabled'])) ? 'checked' : ''; ?> type="radio" id="gdpr_field_enabled_0" name="wpgenious-job-listing[gdpr_field_enabled]" value="0"/>
                            <?php echo __('No', 'wpgenious-job-listing'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[gdpr_field_text]">
                                <?php echo __('GDPR text : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea
                                    placeholder="<?php echo __('GDPR text : ', 'wpgenious-job-listing'); ?>"
                                    name="wpgenious-job-listing[gdpr_field_text]"
                                    id="gdpr_field_text" cols="50" rows="3"><?php echo esc_html($options['gdpr_field_text']); ?></textarea>
                        </td>
                    </tr>
                    <!-- Google reCaptcha v3 -->
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[gdpr_field_text]">
                                <?php echo __('Google reCAPTCHA V3 : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                    </tr>
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[gdpr_field_enabled]">
                                <?php echo __('Enable google reCaptcha : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                        <td>
                            <input <?php echo ( (bool) ( isset($options['g_recaptcha_enabled']) && esc_html($options['g_recaptcha_enabled']))) ? 'checked' : ''; ?> type="radio" id="g_recaptcha_enabled_1" name="wpgenious-job-listing[g_recaptcha_enabled]" value="1"/>
                            <?php echo __('Yes', 'wpgenious-job-listing'); ?>
                            <input <?php echo ( !(bool) ( isset($options['g_recaptcha_enabled']) && esc_html($options['g_recaptcha_enabled']))) ? 'checked' : ''; ?> type="radio" id="g_recaptcha_enabled_0" name="wpgenious-job-listing[g_recaptcha_enabled]" value="0"/>
                            <?php echo __('No', 'wpgenious-job-listing'); ?>
                            <br>
                            <p>
                                <a class="button" href="https://www.google.com/recaptcha/admin/" target="_blank">
                                    <?php echo __('Get google reCaptcha v3 keys', 'wpgenious-job-listing'); ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[allowed_upload_file_ext][]">
                                <?php echo __('reCaptcha Key : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" id="g_recaptcha_key" name="wpgenious-job-listing[g_recaptcha_key]"
                                   value="<?php echo isset($options['g_recaptcha_key']) ? esc_html($options['g_recaptcha_key']) : '';  ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="row">
                            <label for="wpgenious-job-listing[g_recaptcha_secret]">
                                <?php echo __('reCaptcha Secret : ', 'wpgenious-job-listing'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" id="g_recaptcha_secret" name="wpgenious-job-listing[g_recaptcha_secret]"
                                   value="<?php echo isset($options['g_recaptcha_secret']) ? esc_html($options['g_recaptcha_secret']) : '';  ?>"/>
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
