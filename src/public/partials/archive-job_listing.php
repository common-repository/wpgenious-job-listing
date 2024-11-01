<?php
/**
 * Template for displaying single job listing content
 *
 * Override this by copying it to current-theme/job-listing/archive-job_listing.php
 */

if (! defined('ABSPATH') ) {
    exit;
}

get_header();

$listing_cols = wpgenious_get_plugin_option('listing_view');
$listing_type = wpgenious_get_plugin_option('listing_page_type');
$listing_grid_number_cols = wpgenious_get_plugin_option('grid_number_columns');
$listing_jobs_per_page = wpgenious_get_plugin_option('listing_jobs_per_page');
$display_search_form = wpgenious_get_plugin_option('listing_display_search_form');
$search_by = wpgenious_get_plugin_option('listing_search_by')?: [];
$list_type = wpgenious_get_plugin_option('listing_page_type');

$url_params = [];
foreach ($_GET as $key => $value) {
    if(strpos(sanitize_text_field($key), '_field')) {
        $url_params[str_replace('_field', '', $key)] = sanitize_text_field($value);
    }
}

/**
 * before_job_archive_template hook
 *
 * @since 1.0.0
 */
do_action('before_job_archive_template');

?>

    <div class="main job-archive-main job-archive-content">
        <div class="wpgenious-job-listing-container">
            <h1 class="page-title wpgenious-job-listing-job-archive-title">
                <?php echo __('Job offers', 'wpgenious-job-listing'); ?>
            </h1>
            <div class="<?php echo ($listing_cols == 2) ? 'wpgenious-job-listing-listing-two-cols' : ''; ?>">
                <div class="wpgenious-job-listing-search-form <?php echo ($listing_cols === '2' && (bool) $display_search_form) ? 'wpgenious-job-listing-two-cols ' : 'wpgenious-job-listing-single-col '; echo !((bool) $display_search_form)? 'hidden' : '';?> ">
                    <form id="wpgenious-job-listing-search-form" action="" method="post" class="search-job-form">
                        <input type="hidden" name="action" value="search_job">
                        <div class="wpgenious-job-listing-search-field form-group wpgenious-job-listing-form-group">
                            <input value="<?php echo isset($url_params['keyword']) ? $url_params['keyword'] : ''; ?>"
                                   data-name="keyword" class="wpgenious-job-listing-job-form-control form-control search-jobs"
                                   type="text" name="search-by[keyword]" placeholder="<?php echo __('Keyword', 'wpgenious-job-listing'); ?>">
                        </div>
                        <?php foreach ($search_by as $slug) : ?>
                            <?php  $slug = Wpgenious_Job_Listing_Tools::wpg_get_job_slug($slug); ?>
                            <?php if(taxonomy_exists($slug)) : ?>
                                <?php
                                $taxonomy = get_taxonomy($slug);
                                $terms = get_terms(
                                    array(
                                        'taxonomy'   => $slug,
                                        'orderby'    => 'name',
                                        'hide_empty' => true,
                                    )
                                );
                                ?>
                                <?php if(!empty($terms)) : ?>
                                    <div class="wpgenious-job-listing-search-field form-group wpgenious-job-listing-form-group">
                                        <select data-name="<?php echo $taxonomy->name; ?>" class="wpgenious-job-listing-job-form-control form-control search-jobs" name="search-by[<?php echo $taxonomy->name; ?>]" >
                                            <option value=""><?php echo Wpgenious_Job_Listing_Tools::translate($taxonomy->label); ?></option>
                                            <?php foreach ($terms as $term) : ?>
                                                <option <?php
                                                echo (!empty($url_params[$taxonomy->name]) && ($url_params[$taxonomy->name] == $term->term_id)) ? 'selected' : '';
                                                ?>
                                                        value="<?php echo esc_html($term->term_id); ?>">
                                                    <?php echo esc_html($term->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="wpgenious-job-listing-search-field form-group wpgenious-job-listing-form-group">
                            <input type="submit" value="<?php echo __('Search', 'wpgenious-job-listing'); ?>" id="wpgenious-job-listing-search-btn" class="search-job wpgenious-job-listing-job-form-control form-control wpgenious-job-listing-btn" data-response-text="<?php echo __('Searching...', 'wpgenious-job-listing'); ?>">
                        </div>
                    </form>
                </div>

                <div class="wpgenious-job-listing-wpgenious-job-listing <?php echo ($listing_cols === '2' && (bool) $display_search_form) ? 'wpgenious-job-listing-two-cols' : 'wpgenious-job-listing-single-col' ?> ">
                <div class="wpgenious-job-listing-job-list-wrapper wpgenious-job-listing-job-list-type-<?php echo esc_html($list_type); ?>" id="wpgenious-job-listing-job-list-wrapper">
                    <?php require_once WPGENIOUS_JOB_LISTING_PUBLIC_DIR . 'partials/results-job_listing.php'; ?>
                </div>
                <?php if(isset($max_num_pages) && isset($paged) && $max_num_pages > 1 && $paged < $max_num_pages) : ?>
                    <div class="wpgenious-job-listing-job-load-more">
                        <a data-max-page="<?php echo esc_html($max_num_pages); ?>"  href="#" id="wpgenious-job-listing-load-more-jobs" class="wpgenious-job-listing-load-more-jobs">
                            <?php echo __('Load more', 'wpgenious-job-listing'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
    </div>

<?php

/**
 * after_job_archive_template hook
 *
 * @since 1.0.0
 */
do_action('after_job_archive_template');

get_footer();

?>
