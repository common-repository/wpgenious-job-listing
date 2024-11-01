<?php
/**
 * Template for displaying apply form
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

?>

<div class="wpgenious-job-listing-job-form">
    <div class="wpgenious-job-listing-job-form-inner">
        <?php if(Wpgenious_Job_Listing_Tools::is_expired_job(get_the_ID())) : ?>
            <?php
            if((bool) get_post_meta(get_the_ID(),'display_job_expiry', true)) {
                $job_expiry_date = get_post_meta(get_the_ID(), 'job_expiry', true);
                $job_expiry_date = date_i18n(get_option('date_format'), strtotime($job_expiry_date));
                echo sprintf(
                    esc_attr__('This job has expired on %s .', 'wpgenious-job-listing'),
                    esc_html($job_expiry_date)
                );
            }else {
                echo esc_attr__('This job has expired.', 'wpgenious-job-listing');
            }
            ?>
        <?php else : ?>
            <?php
            /**
             * before_job_application_form hook
             *
             * @since 1.0.0
             */
            do_action('before_job_application_form');
            ?>

            <h2>
                <?php echo esc_html(apply_filters('job_application_form_title', __('Apply for this job', 'wpgenious-job-listing'))); ?>
            </h2>

            <?php
            /**
             * job_application_form_description hook
             *
             * @since 1.0.0
             */
            do_action('job_application_form_description');
            ?>

            <form id="wpgenious-job-listing-apply-form" name="application_form" method="post" enctype="multipart/form-data">
                <?php
                /**
                 * job_application_form_fields hook
                 *
                 * Initialize job application form fields
                 *
                 * @hooked Wpgenious_Job_Listing_Public::form_field_init()
                 *
                 * @since 1.0.0
                 */
                do_action('job_application_form_fields');
                ?>

                <input type="hidden" name="job_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                <input type="hidden" name="action" value="wpgenious-job-listing_apply_form_submission" >
                <div class="form-group wpgenious-job-listing-form-group">
                    <?php Wpgenious_Job_Listing_Form_Builder::submit_btn(); ?>
                </div>
            </form>

            <div id="wpgenious-job-listing-apply-message" style="display: none;"></div>

            <?php
            /**
             * after_job_application_form hook
             *
             * @since 1.0.0
             */
            do_action('after_job_application_form');
            ?>
        <?php endif; ?>
    </div>
</div>
