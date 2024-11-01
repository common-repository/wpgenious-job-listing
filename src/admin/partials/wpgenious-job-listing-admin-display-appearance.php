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
    'single_page_layout' => wpgenious_get_plugin_option('single_page_layout'),
    'single_display_job_custom_fields' => wpgenious_get_plugin_option('single_display_job_custom_fields'),
    'single_custom_fields_position' => wpgenious_get_plugin_option('single_custom_fields_position'),
    'listing_view' => wpgenious_get_plugin_option('listing_view'),
    'listing_page_type' => wpgenious_get_plugin_option('listing_page_type'),
    'listing_grid_number_columns' => wpgenious_get_plugin_option('listing_grid_number_columns'),
    'listing_jobs_per_page' => wpgenious_get_plugin_option('listing_jobs_per_page'),
    'listing_display_search_form' => wpgenious_get_plugin_option('listing_display_search_form'),
    'listing_search_by' => wpgenious_get_plugin_option('listing_search_by')?: [],
);

$job_fields = is_array(unserialize(wpgenious_get_plugin_option('job_custom_fields'))) ? unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];

?>

<!-- Jobs Archive And Single Job Appearance -->
<div class="wpgenious-job-listing-admin-settings">
    <form method="POST" action="options.php">
        <?php
        settings_fields('wpgenious-job-listing');
        ?>
        <table class="form-table">
        <tr>
            <th colspan="2">
                <h2><?php echo __('Listing layout options :', 'wpgenious-job-listing'); ?></h2>
            </th>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[listing_view]">
                    <?php echo __('Layout of jobs listing page', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <input name="wpgenious-job-listing[listing_view]" type="radio" value="1" <?php echo ( esc_html($options['listing_view']) === '1') ? 'checked' : ''; ?>>
                <?php echo __('One column', 'wpgenious-job-listing'); ?>
                <input name="wpgenious-job-listing[listing_view]" type="radio" value="2" <?php echo ( esc_html($options['listing_view']) === '2') ? 'checked' : ''; ?>>
                <?php echo __('Two columns', 'wpgenious-job-listing'); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[listing_page_type]">
                    <?php echo __('Jobs list view', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <input class="toggle-row" data-toggle="wpgenious-job-listing_wpgenious_job_listing_grid_number_columns" data-action="hide"
                       name="wpgenious-job-listing[listing_page_type]" type="radio" value="list" <?php echo ( esc_html($options['listing_page_type']) === 'list') ? 'checked' : ''; ?>>
                <?php echo __('List', 'wpgenious-job-listing'); ?>
                <input class="toggle-row" data-toggle="wpgenious-job-listing_wpgenious_job_listing_grid_number_columns" data-action="show"
                       name="wpgenious-job-listing[listing_page_type]" type="radio" value="grid" <?php echo ( esc_html($options['listing_page_type']) === 'grid') ? 'checked' : ''; ?>>
                <?php echo __('Grid', 'wpgenious-job-listing'); ?>
            </td>
        </tr>
        <tr id="wpgenious-job-listing_wpgenious_job_listing_grid_number_columns">
            <th>
                <label for="wpgenious-job-listing[listing_grid_number_columns]">
                    <?php echo __('Number of columns', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <select name="wpgenious-job-listing[listing_grid_number_columns]">
                    <option <?php echo ( esc_html($options['listing_grid_number_columns']) === '1') ? 'selected' : ''; ?> value="1"><?php echo __('1 Column', 'wpgenious-job-listing'); ?></option>
                    <option <?php echo ( esc_html($options['listing_grid_number_columns']) === '2') ? 'selected' : ''; ?> value="2"><?php echo __('2 Columns', 'wpgenious-job-listing'); ?></option>
                    <option <?php echo ( esc_html($options['listing_grid_number_columns']) === '3') ? 'selected' : ''; ?> value="3"><?php echo __('3 Columns', 'wpgenious-job-listing'); ?></option>
                    <option <?php echo ( esc_html($options['listing_grid_number_columns']) === '4') ? 'selected' : ''; ?> value="4"><?php echo __('4 Columns', 'wpgenious-job-listing'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[listing_jobs_per_page]">
                    <?php echo __('Jobs per page', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <input name="wpgenious-job-listing[listing_jobs_per_page]" type="number" value="<?php echo esc_html($options['listing_jobs_per_page'])?>">
                <p class="description">
                    <?php echo __('Number of jobs per page', 'wpgenious-job-listing'); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <h2><?php echo __('Search options :', 'wpgenious-job-listing'); ?></h2>
            </th>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[listing_display_search_form]">
                    <?php echo __('Jobs search', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <input name="wpgenious-job-listing[listing_display_search_form]" type="radio" value="1" <?php echo ( esc_html($options['listing_display_search_form']) == '1') ? 'checked' : ''; ?>>
                <?php echo __('Yes', 'wpgenious-job-listing'); ?>
                <input name="wpgenious-job-listing[listing_display_search_form]" type="radio" value="0" <?php echo ( esc_html($options['listing_display_search_form']) != '1') ? 'checked' : ''; ?>>
                <?php echo __('No', 'wpgenious-job-listing'); ?>
                <p class="description"><?php echo __('Enable jobs search', 'wpgenious-job-listing'); ?></p>
            </td>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[listing_search_by][]">
                    <?php echo __('Search by', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <?php foreach ($job_fields as $field) : ?>
                    <input
                            name="wpgenious-job-listing[listing_search_by][]"
                            type="checkbox"
                            value="<?php echo esc_html($field['slug']); ?>"
                        <?php echo (in_array($field['slug'], $options['listing_search_by'])) ? 'checked' : ''; ?>>
                    <?php echo esc_html($field['name']); ?><br>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <h2><?php echo __('Single job page :', 'wpgenious-job-listing'); ?></h2>
            </th>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[single_page_layout]">
                    <?php echo __('Layout of job details page :', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <input name="wpgenious-job-listing[single_page_layout]" type="radio" value="1" <?php echo ( esc_html($options['single_page_layout']) === '1') ? 'checked' : ''; ?>>
                <?php echo __('Single Column', 'wpgenious-job-listing'); ?>
                <input name="wpgenious-job-listing[single_page_layout]" type="radio" value="2" <?php echo ( esc_html($options['single_page_layout']) === '2') ? 'checked' : ''; ?>>
                <?php echo __('Two Columns', 'wpgenious-job-listing'); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[single_display_job_custom_fields]">
                    <?php echo __('Custom Fields :', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <input name="wpgenious-job-listing[single_display_job_custom_fields]" type="radio" value="1" <?php echo ( esc_html($options['single_display_job_custom_fields']) == '1') ? 'checked' : ''; ?>>
                <?php echo __('Yes', 'wpgenious-job-listing'); ?>
                <input name="wpgenious-job-listing[single_display_job_custom_fields]" type="radio" value="0" <?php echo ( esc_html($options['single_display_job_custom_fields']) != '1') ? 'checked' : ''; ?>>
                <?php echo __('No', 'wpgenious-job-listing'); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="wpgenious-job-listing[single_custom_fields_position]">
                    <?php echo __('Job Custom Fields Position :', 'wpgenious-job-listing'); ?>
                </label>
            </th>
            <td>
                <select name="wpgenious-job-listing[single_custom_fields_position]">
                    <option value="before" <?php echo ( esc_html($options['single_custom_fields_position']) === 'before') ? 'selected' : ''; ?>>
                        <?php echo __('Before Job Content', 'wpgenious-job-listing'); ?>
                    </option>
                    <option value="after" <?php echo ( esc_html($options['single_custom_fields_position']) === 'after') ? 'selected' : ''; ?>>
                        <?php echo __('After Job Content', 'wpgenious-job-listing'); ?>
                    </option>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <?php submit_button(); ?>
            </td>
        </tr>
    </table>
</div>
