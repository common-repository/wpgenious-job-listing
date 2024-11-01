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

const JOB_ICON_PREFIX = 'job-listing-icon job-listing-icon-' ;

wp_enqueue_style('select2css');
wp_enqueue_script('select2js');
wp_enqueue_style('job-icons-style');

$options = array(
    'job_custom_fields' =>  is_array(unserialize(wpgenious_get_plugin_option('job_custom_fields'))) ? unserialize(wpgenious_get_plugin_option('job_custom_fields')): [],
);

// Get icons list
$content = file_get_contents(WPGENIOUS_JOB_LISTING_ASSETS_DIR . 'js/admin/icons.json');
$icons = json_decode( $content , true );

// JobPosting Schema.org Type
$content = file_get_contents(WPGENIOUS_JOB_LISTING_ASSETS_DIR . 'js/admin/job-posting.json');
$micro_data = json_decode( $content , true );
?>

<!-- Job Custom Fields -->
<div id="wpgenious-job-listing-settings-form" class="wpgenious-job-listing-admin-settings">
    <form method="POST" action="options.php">
        <?php
        settings_fields('wpgenious-job-listing');
        ?>
        <table class="form-table wpgenious-job-listing-job-custom-fields">
            <thead>
                <tr>
                    <th class="wpgenious-job-listing-fields-drag-control-wrapper"></th>
                    <th><?php esc_html_e('Field Name : ', 'wpgenious-job-listing'); ?></th>
                    <th><?php esc_html_e('Micro data ( optional ) : ', 'wpgenious-job-listing'); ?></th>
                    <th><?php esc_html_e('Icon ( optional ) : ', 'wpgenious-job-listing'); ?></th>
                    <th><?php esc_html_e('Action : ', 'wpgenious-job-listing'); ?></th>
                </tr>
            </thead>
            <tbody id="table-body-custom-fields">
                <?php foreach ($options['job_custom_fields'] as $key => $field) : ?>
                    <tr data-index="<?php echo  esc_html($key); ?>" id="job-wrapper-field-<?php echo esc_html($key); ?>">
                        <td class="wpgenious-job-listing-fields-drag-control-wrapper">
                            <span class="wpgenious-job-listing-fields-drag-control dashicons dashicons-move"></span>
                        </td>
                        <td>
                            <input value="<?php echo esc_html($field['name']) ?>" type="text" name="wpgenious-job-listing[job_custom_fields][<?=$key ?>][name]" required>
                        </td>
                        <td>
                            <select class="select2 simple" name="wpgenious-job-listing[job_custom_fields][<?=esc_html($key) ?>][itemprop]">
                                <option value=""><?php esc_html_e('Select ', 'wpgenious-job-listing'); ?></option>
                                <?php
                                foreach ($micro_data['data'] as $data) {
                                    $selected = ( $data === $field['itemprop'] ) ? 'selected' : '';
                                    echo sprintf(
                                        '<option %1$s value="%2$s"> %2$s </option>',
                                        $selected,
                                        esc_html($data)
                                    );
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="select2 select2-icons" name="wpgenious-job-listing[job_custom_fields][<?=esc_html($key) ?>][icon]">
                                <option value=""><?php esc_html_e('Select icon ', 'wpgenious-job-listing'); ?></option>
                                <?php
                                    foreach ($icons['icons'] as $icon) {
                                        $selected = ( JOB_ICON_PREFIX.$icon === $field['icon'] ) ? 'selected' : '';
                                        echo sprintf(
                                            '<option %1$s 
                                                value="'.JOB_ICON_PREFIX.'%2$s">
                                                <i class="'.JOB_ICON_PREFIX.'%2$s"></i> %2$s
                                            </option>',
                                            $selected,
                                            esc_html($icon)
                                        );
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <button type="button" data-field-index="<?php echo esc_html($key); ?>" class="button button-link-delete delete-job-field"><?php echo __('Delete ', 'wpgenious-job-listing'); ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- job-fields-template -->
        <script type="text/html" id="tmpl-wpgenious-job-listing-job-field">
            <tr data-index="{{data.index}}" id="job-wrapper-field-{{data.index}}">
                <td class="wpgenious-job-listing-fields-drag-control-wrapper">
                    <span class="wpgenious-job-listing-fields-drag-control dashicons dashicons-move"></span>
                </td>
                <td>
                    <input value="" type="text" name="wpgenious-job-listing[job_custom_fields][{{data.index}}][name]">
                </td>
                <td>
                    <select class="select2 simple" name="wpgenious-job-listing[job_custom_fields][{{data.index}}][itemprop]">
                        <option value=""><?php esc_html_e('Select ', 'wpgenious-job-listing'); ?></option>
                        <?php
                        foreach ($micro_data['data'] as $data) {
                            $selected = ( $data === $field['itemprop'] ) ? 'selected' : '';
                            echo sprintf(
                                '<option value="%1$s"> %1$s </option>',
                                esc_html($data)
                            );
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="select2 select2-icons" name="wpgenious-job-listing[job_custom_fields][{{data.index}}][icon]">
                        <option value=""><?php esc_html_e('Select icon ', 'wpgenious-job-listing'); ?></option>
                        <?php
                        foreach ($icons['icons'] as $icon) {
                            echo sprintf(
                              '<option value="job-listing-icon job-listing-icon-%1$s">
                                <i class="job-listing-icon job-listing-icon-%1$s"></i> %1$s
                              </option>',
                              esc_html($icon)
                            );
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <button type="button" data-field-index="{{data.index}}" class="button button-link-delete delete-job-field"><?php echo __('Delete ', 'wpgenious-job-listing'); ?></button>
                </td>
            </tr>
        </script>

        <div>
            <button type="button" id="add-new-job-field" class="button"><?php echo __('Add new field ', 'wpgenious-job-listing'); ?></button>
        </div>

        <div>
            <?php submit_button(); ?>
        </div>
    </form>
</div>
