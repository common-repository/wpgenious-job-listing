<?php
if (! defined('ABSPATH') ) {
    exit;
}

$fields = wpgenious_get_plugin_option('job_custom_fields') ? unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];
$job_id = get_the_ID();

?>
<div class="job-custom-fields" id="job-custom-fields">
    <ul>
        <?php foreach($fields as $field) : ?>
            <?php $slug = $field['slug']; ?>
            <li class="wpgenious-job-listing_job_custom_wrapper">
                <label for="job_custom_fields[<?php echo esc_html($slug); ?>][]">
                    <?php echo esc_html($field['name']); ?>
                </label>
                <?php $terms = Wpgenious_Job_Listing_Tools::wpg_get_job_terms($slug, $job_id);  ?>
                <input value="<?php echo !empty($terms[0]) ? $terms[0]->name : ''; ?>"
                       type="text" id="wpgenious_job_listing_<?php echo esc_html($slug); ?>_field"
                       name="job_custom_fields[<?php echo esc_html($slug); ?>]" >
            </li>
        <?php endforeach; ?>
    </ul>
</div>
