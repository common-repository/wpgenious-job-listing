<?php
if (! defined('ABSPATH') ) {
    exit;
}

$job_id = get_post_meta(get_the_ID(), 'job_id', true);
$job = get_post($job_id);

?>

<table class="wpgenious-job-listing-application-job-details">
    <tr>
        <td>
            <?php echo __('Job', 'wpgenious-job-listing'); ?>
        </td>
        <td>
            <strong>
                <?php echo sprintf('<a target="_blank" href="%1$s">%2$s</a>', $job->guid, $job->post_title); ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo __('Views', 'wpgenious-job-listing'); ?>
        </td>
        <td>
            <strong>
                <?php
                    $job_views = Wpgenious_Job_Listing_Tools::get_job_views($job_id);
                    echo esc_html($job_views);
                ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo __('Applications', 'wpgenious-job-listing'); ?>
        </td>
        <td>
            <strong>
                <?php
                $application_count = Wpgenious_Job_Listing_Tools::get_applications_count($job_id);
                echo sprintf(
                    '<a href="%1$s">%2$s</a>',
                    esc_url(admin_url('edit.php?post_type=job_application&job_id=' . $job_id)),
                    esc_html($application_count)
                );
                ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo __('Conversions', 'wpgenious-job-listing'); ?>
        </td>
        <td>
            <strong>
                <?php if(!empty($job_views)): ?>
                    <?php echo round(( $application_count / $job_views ) * 100, 2) . '%'; ?>
                <?php else : ?>
                    <span aria-hidden="true">—</span>
                <?php endif; ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo __('Created at', 'wpgenious-job-listing'); ?>
        </td>
        <td>
            <strong>
                <?php echo date_i18n(get_option('date_format'), strtotime($job->post_date)); ?>
            </strong>
        </td>
    </tr>
</table>
