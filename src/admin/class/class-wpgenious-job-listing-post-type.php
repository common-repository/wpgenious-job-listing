<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/admin
 * @author     wpgenious <contact@wpgenious.com>
 */

if (! defined('ABSPATH') ) {
    exit;
}

class Wpgenious_Job_Listing_Post_Type
{

    /**
     * Post type job offer
     */
    public static function register_wpgenious_job_listing()
    {
        if (!post_type_exists('job_listing') ) {

            $labels = array(
                'name' => __('Jobs', 'wpgenious-job-listing'),
                'singular_name' => __('Job', 'wpgenious-job-listing'),
                'add_new' => __('New Job', 'wpgenious-job-listing'),
                'add_new_item' => __('Add New Job', 'wpgenious-job-listing'),
                'edit_item' => __('Edit Job', 'wpgenious-job-listing'),
                'new_item' => __('New Job', 'wpgenious-job-listing'),
                'search_items' => __('Search Jobs', 'wpgenious-job-listing'),
                'not_found' => __('No Jobs found', 'wpgenious-job-listing'),
                'not_found_in_trash' => __('No Jobs found in Trash', 'wpgenious-job-listing'),
                'parent_item_colon' => __('Parent Job :', 'wpgenious-job-listing'),
                'menu_name' => __('Job Listing', 'wpgenious-job-listing'),
                'view_item' => __('View Job listing', 'wpgenious-job-listing'),
                'view_items' => __('View Job listings', 'wpgenious-job-listing'),
                'item_published' => __('Job listing published.', 'wpgenious-job-listing'),
                'item_published_privately' => __('Job listing published privately.', 'wpgenious-job-listing'),
                'item_reverted_to_draft' => __('Job listing reverted to draft.', 'wpgenious-job-listing'),
                'item_scheduled' => __('Job listing scheduled.', 'wpgenious-job-listing'),
                'item_updated' => __('Job listing updated.', 'wpgenious-job-listing')
            );

            /**
             * Filters 'wpgenious_job_listing' post type arguments.
             *
             * @since 1.0.0
             *
             * @param array $args arguments.
             */
            $args = apply_filters(
                'wpgenious_job_listing_args',
                array(
                    'has_archive' => true,
                    'labels' => $labels,
                    'hierarchical' => false,
                    'map_meta_cap' => true,
                    'taxonomies' => array(),
                    'public' => true,
                    'show_ui' => true,
                    'show_in_rest' => true,
                    'show_in_menu' => true,
                    'rewrite' => array('slug' => wpgenious_get_plugin_option('archive_slug') ? wpgenious_get_plugin_option('archive_slug') : 'jobs'),
                    'capability_type' => 'post',
                    'supports' => array('title', 'thumbnail', 'custom-fields', 'editor', 'publicize'),
                    'menu_icon'       => 'dashicons-businessman'
                )
            );

            register_post_type('job_listing', $args);
        }
    }

    /**
     * Post type application
     */
    public static function register_job_application()
    {
        if (!post_type_exists('job_application') ) {

            $labels = array(
                'name' => __('Applications', 'wpgenious-job-listing'),
                'singular_name' => __('Application', 'wpgenious-job-listing'),
                'menu_name' => __('Applications', 'wpgenious-job-listing'),
                'edit_item' => __('Application', 'wpgenious-job-listing'),
                'search_items' => __('Search Applications', 'wpgenious-job-listing'),
                'not_found' => __('No Applications found', 'wpgenious-job-listing'),
                'not_found_in_trash' => __('No Applications found in Trash', 'wpgenious-job-listing'),
            );

            /**
             * Filters 'job_application' post type arguments.
             *
             * @since 1.4
             *
             * @param array $args arguments.
             */
            $args = apply_filters(
                'job_application_args',
                array(
                    'labels' => $labels,
                    'public' => false,
                    'show_ui' => true,
                    'map_meta_cap' => true,
                    'show_in_menu' => 'edit.php?post_type=job_listing',
                    'capability_type' => 'post',
                    'capabilities' => array(
                        'create_posts' => false,
                    ),
                    'supports' => false,
                    'rewrite' => false,
                )
            );

            register_post_type('job_application', $args);
        }
    }

    /**
     * @param  $actions
     * @param  $post
     * @return mixed
     */
    public static function row_actions($actions, $post)
    {
        if ($post->post_type === 'job_application' ) {
            unset($actions['inline hide-if-no-js']);
        }

        return $actions;
    }

    /**
     * Custom columns for application
     *
     * @return array
     */
    public static function custom_applicant_columns()
    {
        return array(
            'cb'                 => '<input type="checkbox" />',
            'avatar'             => '<span class="dashicons dashicons-camera" title="Avatar"></span>',
            'title'              => esc_attr__('Applicant Name', 'wpgenious-job-listing'),
            'applicant_email'    => esc_attr__('Applicant Email', 'wpgenious-job-listing'),
            'application_job'    => esc_attr__('Job', 'wpgenious-job-listing'),
            'application_date'   => esc_attr__('Date', 'wpgenious-job-listing'),
            'application_status' => esc_attr__('Status', 'wpgenious-job-listing'),
            'application_rating' => esc_attr__('Rating', 'wpgenious-job-listing'),
        );
    }

    /**
     * Data for application columns
     *
     * @param $column
     * @param $post_id
     */
    public static function custom_applicant_columns_data( $column, $post_id )
    {
        $email = esc_attr(get_post_meta($post_id, 'applicant_email', true));

        switch ($column) {
        case 'avatar' :
            echo get_avatar($email, 32);
            break;

        case 'applicant_email' :
            echo sprintf('<a href="%s">%s</a>', "mailto:".esc_html($email), esc_html($email));
            break;

        case 'application_job':
            $job_id = esc_attr(get_post_meta($post_id, 'job_id', true));
            $job_title = esc_attr(get_post_meta($post_id, 'apply_for', true));
            echo sprintf('<a target="_blank" href="%s">%s</a>', get_permalink($job_id), esc_html($job_title));
            break;

        case 'application_date':
            $applicant = get_post($post_id);
            echo date_i18n(get_option('date_format'). ', ' . get_option('time_format'), strtotime($applicant->post_date));
            break;

        case 'application_status':
            $application_status = wpgenious_get_plugin_option('application_status');
            $current_status = esc_attr(get_post_meta($post_id, 'applicant_status', true));

            if(!empty($application_status[$current_status])) {
                $status = $application_status[$current_status];
                echo sprintf(
                    '<span style="background: %s" class="applicant-status">%s</span>',
                    esc_html($status['color']),
                    esc_html($status['name'])
                );
            }
            break;

        case 'application_rating':
            $rating = esc_attr(get_post_meta($post_id, 'applicant_rating', true));
            ?>
                <div>
                    <span class="dashicons dashicons-star-<?php echo ($rating >= 1 ) ? 'filled' : 'empty'; ?>"></span>
                    <span class="dashicons dashicons-star-<?php echo ($rating >= 2 ) ? 'filled' : 'empty'; ?>"></span>
                    <span class="dashicons dashicons-star-<?php echo ($rating >= 3 ) ? 'filled' : 'empty'; ?>"></span>
                    <span class="dashicons dashicons-star-<?php echo ($rating >= 4 ) ? 'filled' : 'empty'; ?>"></span>
                    <span class="dashicons dashicons-star-<?php echo ($rating == 5 ) ? 'filled' : 'empty'; ?>"></span>
                </div>
                <?php
            break;
        }
    }

    /**
     * Custom columns for job
     *
     * @return array
     */
    public static function custom_job_columns()
    {
        return array(
            'cb'                     => '<input type="checkbox" />',
            'title'                  => esc_attr__('Job', 'wpgenious-job-listing'),
            'wpgenious_job_listing_id'           => esc_attr__('ID', 'wpgenious-job-listing'),
            'wpgenious_job_listing_applications' => esc_attr__('Applications', 'wpgenious-job-listing'),
            'wpgenious_job_listing_post_views'   => esc_attr__('Views', 'wpgenious-job-listing'),
            'wpgenious_job_listing_conversion'   => esc_attr__('Conversion', 'wpgenious-job-listing'),
            'job_status'             => esc_attr__('Status', 'wpgenious-job-listing'),
        );
    }

    /**
     * Data for job offer columns
     *
     * @param $column
     * @param $post_id
     */
    public static function custom_job_columns_data( $column, $post_id )
    {
        $application_count = Wpgenious_Job_Listing_Tools::get_applications_count($post_id);
        $job_views         = Wpgenious_Job_Listing_Tools::get_job_views($post_id);
        $default_display   = '—';

        switch ( $column ) {
        case 'wpgenious_job_listing_id' :
            edit_post_link(esc_html($post_id));
            break;
        case 'wpgenious_job_listing_applications' :
            $output = $default_display;
            if ($application_count > 0 ) {
                echo sprintf('<a target="_blank" href="%1$s">%2$s</a>', esc_url(admin_url('edit.php?post_type=job_application&job_id=' . $post_id)), $application_count);
            } else {
                echo esc_html($output);
            }
            break;
        case 'wpgenious_job_listing_post_views' :
            echo ( ! empty($job_views) ) ? esc_html($job_views) : esc_html($default_display);
            break;
        case 'wpgenious_job_listing_conversion' :
            $output = $default_display;
            if ($job_views > 0 ) {
                $conversion_rate = ( $application_count / $job_views ) * 100;
                $output          = round($conversion_rate, 2) . '%';
            }
            echo esc_html($output);
            break;
        case 'job_status':
            if(Wpgenious_Job_Listing_Tools::is_expired_job($post_id)) {
                $job_status = esc_attr__('Expiry', 'wpgenious-job-listing');
                $job_status_color = '#c2022e';
            }else{
                $job_status = esc_attr__('Active', 'wpgenious-job-listing');
                $job_status_color = '#4ca276';
            }

            echo sprintf(
                '<span class="job-status" style="background: %s;">%s</span>',
                $job_status_color,
                esc_attr__($job_status, 'wpgenious-job-listing')
            );

            break;
        default:
            break;
        }
    }

    /**
     * Subtitle for application
     */
    public static function admin_single_subtitle()
    {
        global $action, $post;
        if ($post->post_type === 'job_application' && $action === 'edit' ) {

            $date = date_i18n(get_option('time_format'). ', ' . get_option('date_format'), strtotime($post->post_date));
            $submitted_date = sprintf(__('Received on %s', 'wpgenious-job-listing'), $date);
            $subtitle       = '<span>' . esc_html($submitted_date) . '</span>';
            $user_ip        = get_post_meta($post->ID, 'applicant_ip', true);

            if (! empty($user_ip) ) {
                $subtitle .= ' - ' . esc_html(__('from IP ', 'wpgenious-job-listing') . $user_ip);
            }

            echo esc_html($subtitle);
        }
    }

    /**
     * @param $query
     */
    public static function filter_posts($query)
    {
        global $typenow;
        global $pagenow;

        $job_id = ( isset($_GET['job_id'])  && intval($_GET['job_id'])) ? $_GET['job_id'] : false;

        if ( $typenow === 'job_application' && is_admin() && $pagenow === 'edit.php' && $job_id && $query->is_main_query() ) {
            $meta_value = $job_id;

            $query->query_vars['meta_key']   = 'job_id';
            $query->query_vars['meta_value'] = $meta_value;

        }
    }
}
