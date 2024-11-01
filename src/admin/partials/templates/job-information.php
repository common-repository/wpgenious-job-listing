<?php
if (! defined('ABSPATH') ) {
    exit;
}

require_once WPGENIOUS_JOB_LISTING_INC_DIR. 'class-wpgenious-job-listing-tools.php';

$post_id = get_the_ID();
$application_count = Wpgenious_Job_Listing_Tools::get_applications_count($post_id);
$job_views         = Wpgenious_Job_Listing_Tools::get_job_views($post_id);

?>

<table class="wpgenious-job-listing_table wpgenious-job-listing_meta_box_table">
    <tr>
        <td><?php echo __('Job Title', 'wpgenious-job-listing'); ?></td>
        <td><?php echo get_the_title();?></td>
    </tr>
    <tr>
        <td><?php echo __('Views', 'wpgenious-job-listing'); ?></td>
        <td><?php echo esc_html($job_views); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Applications', 'wpgenious-job-listing'); ?></td>
        <td><?php echo esc_html($application_count); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Conversion', 'wpgenious-job-listing'); ?></td>
        <td>
            <?php
            if ($job_views > 0 ) {
                $conversion_rate = ( $application_count / $job_views ) * 100;
                echo round($conversion_rate, 2) . '%';
            }else {
                echo '<span aria-hidden="true">—</span>';
            }
            ?>
        </td>
    </tr>
</table>
