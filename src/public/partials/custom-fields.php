<?php
/**
 * Template for displaying custom fields for job
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public/partials
 */

if (! defined('ABSPATH') ) {
    exit;
}

$fields = wpgenious_get_plugin_option('job_custom_fields') ? unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];

?>

<div class="wpgenious-job-listing wpgenious-job-listing-job-fields">
    <?php
    /**
     * before_job_custom_fields hook
     *
     * @since 1.0.0
     */
    do_action('before_job_custom_fields');
    ?>
    <ul>
        <?php foreach ($fields as $key => $field) : ?>
            <?php $terms = Wpgenious_Job_Listing_Tools::wpg_get_job_terms($field['slug'], get_the_ID()); ?>
                <?php if(!empty($terms) ) : ?>
                    <li>
                        <?php if(!empty($field['icon'])) : ?>
                            <span class="<?php echo esc_html($field['icon']); ?>"></span>
                        <?php endif; ?>
                        <span class="wpgenious-job-listing-job-custom-field-name">
                            <?php echo Wpgenious_Job_Listing_Tools::translate($field['name']); ?>  :
                        </span>
                        <span class="wpgenious-job-listing-job-custom-field-value">
                            <?php foreach ($terms as $term) : ?>
                                <span class="job-field job-field-<?php echo esc_html($term->slug); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </span>
                            <?php endforeach; ?>
                        </span>
                    </li>
                <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
    /**
     * after_job_custom_fields hook
     *
     * @since 1.0.0
     */
    do_action('after_job_custom_fields');
    ?>
</div>
