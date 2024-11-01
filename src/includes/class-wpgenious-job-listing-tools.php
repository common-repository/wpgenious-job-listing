<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 * @author     wpgenious <contact@wpgenious.com>
 */
class Wpgenious_Job_Listing_Tools
{

    /**
     * Get applications count for specific job
     *
     * @param  $post_id
     * @return int
     */
    public static function get_applications_count($post_id)
    {
        return count(self::get_applications($post_id));
    }

    /**
     * @param  $post_id
     * @return int[]|WP_Post[]
     */
    public static function get_applications($post_id)
    {
        return get_children(
            array(
                'post_parent' => $post_id,
                'post_type'   => 'job_application',
                'numberposts' => -1,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'fields'      => 'all',
            )
        );
    }

    /**
     * @param  $post_id
     * @return int|mixed
     */
    public static function get_job_views($post_id)
    {
        $views = get_post_meta($post_id, 'job_views_count', true);

        return $views ? $views : 0;
    }

    /**
     * @return string[]
     */
    public static function allowed_file_extensions()
    {
        return array('pdf', 'doc', 'docx');
    }

    /**
     * @param $job_id
     * @return bool
     */
    public static function is_expired_job($job_id) {
        $job_expiry_date = get_post_meta($job_id, 'job_expiry', true);

        if(empty($job_expiry_date)) {
            return false;
        }

        return  strtotime($job_expiry_date) < strtotime(date("Y-m-d"));
    }

    /**
     * display job link
     */
    public static function job_link( $job_id = null ) {
        echo sprintf(
            '<a class="wpgenious-job-listing-btn wpgenious-job-listing-job-more-details-link" href="%2$s">%1$s<span></span></a>',
            __('More Details', 'wpgenious-job-listing'),
            esc_url( get_permalink($job_id))
        );
    }

    /**
     * Display Job specifications
     * @param $job_id
     */
    public static function job_custom_fields($job_id) {
        $fields = wpgenious_get_plugin_option('job_custom_fields') ? unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];
        ?>
        <div class="wpgenious-job-listing-job-custom-fields">
            <ul>
                <?php foreach ($fields as $key => $field) : ?>
                    <?php $terms = self::wpg_get_job_terms($field['slug'], $job_id); ?>

                    <?php if(!empty($terms) ) : ?>

                        <li>
                            <?php if(!empty($field['icon'])) : ?>
                                <span class="<?php echo esc_html($field['icon']); ?>"></span>
                            <?php else: ?>
                                <span class="wpgenious-job-listing-job-custom-field-name">
                                    <strong>
                                        <?php echo esc_html_e($field['name'], 'wpgenious-job-listing'); ?> :
                                    </strong>
                                </span>
                            <?php endif; ?>
                            <?php foreach ($terms as $term) : ?>
                                <span class="job-field job-field-<?php echo esc_html($term->slug); ?>"><?php echo esc_html($term->name); ?></span>
                            <?php endforeach; ?>
                        </li>

                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * @param $slug
     * @param $job_id
     * @return false|WP_Error|WP_Term[]
     *
     * @since 1.0.5
     */
    public static function wpg_get_job_terms($slug, $job_id) {
        if(function_exists('pll_get_post_language')) {
            $language = pll_get_post_language($job_id, 'slug');
            $slug = sanitize_title( $slug . '-' . $language );
        }

        return get_the_terms($job_id, $slug);
    }

    /**
     * @param $slug
     * @return string
     *
     *  @since 1.0.5
     */
    public static function wpg_get_job_slug($slug) {
        if (function_exists('pll_current_language')) {
            return sanitize_title( $slug . '-' . pll_current_language() );
        }

        return $slug;
    }

    /**
     * Translate job custom fields
     *
     * @since 1.0.5
     */
    public static function translate_job_custom_fields() {
        if(function_exists('pll_register_string')) {
            $job_fields = wpgenious_get_plugin_option('job_custom_fields') ? unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];

            foreach ($job_fields as $value) {
                $slug = sanitize_title($value['name']);

                pll_register_string(
                    sanitize_title( 'wpg-job-listing'.$slug ),
                    $value['name']
                );
            }
        }
    }

    /**
     * @param $str
     * @return string
     *
     * @since 1.0.5
     */
    public static function translate($str) {
        if (function_exists('pll__')) {
            return pll__(esc_html($str));
        }

        return esc_html($str);
    }
}
