<?php
/**
 * Template for displaying single job listing content
 *
 * Override this by copying it to current-theme/wpgenious-job-listing/single-job_listing.php
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

$no_job_found = wpgenious_get_plugin_option('default_msg_no_job_found');
$grid_cols = wpgenious_get_plugin_option('listing_grid_number_columns');

if(!isset($query) ) {
    $jobs_per_page = wpgenious_get_plugin_option('listing_jobs_per_page');

    $args = array(
        'post_type' => 'job_listing',
        'posts_per_page' => $jobs_per_page
    );

    if(!empty($url_params)) {
        foreach ($url_params as $key => $value) {
            if($key === 'keyword') {
                $args['s'] = $value;
            }else {
                $args['tax_query'][] = array(
                    'taxonomy' => $key,
                    'field' => 'term_id',
                    'terms' => $value,
                );
            }
        }
    }

    $query = new WP_Query($args);
}

$max_num_pages = $query->max_num_pages;
$paged = ( $query->query_vars['paged'] ) ? $query->query_vars['paged'] : 1;
$jobs = $query->posts;

?>

<?php if(count($jobs)) : ?>
    <?php foreach ($jobs as $job) : ?>
        <div class="wpgenious-job-listing-job-item wpgenious-job-listing-<?php echo $job->ID; ?> wpgenious-job-listing-grid-col-<?php echo $grid_cols; ?>">
            <div class="job-details">
                <div class="wpgenious-job-listing-job-left-part">
                    <h2 class="wpgenious-job-listing-job-title">
                        <a href="<?php echo esc_url($job->guid); ?>">
                            <?php echo esc_html($job->post_title); ?>
                        </a>
                    </h2>

                    <?php Wpgenious_Job_Listing_Tools::job_custom_fields($job->ID); ?>

                </div>
                <div class="wpgenious-job-listing-job-more-details">
                    <?php Wpgenious_Job_Listing_Tools::job_link(); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="wpgenious-job-listing-no-job-found">
        <?php echo esc_html_e($no_job_found, 'wpgenious-job-listing'); ?>
    </div>
<?php endif; ?>
