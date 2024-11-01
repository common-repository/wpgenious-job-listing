<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 * @author     wpgenious <contact@wpgenious.com>
 */

require_once WPGENIOUS_JOB_LISTING_PUBLIC_DIR.'class/class-wpgenious-job-listing-form-builder.php';
require_once WPGENIOUS_JOB_LISTING_PUBLIC_DIR.'class/class-wpgenious-job-listing-application.php';

class Wpgenious_Job_Listing_Public
{

    private $plugin_name;
    private $version;
    private $lang;
    /**
     * 
     *
     * @var Wpgenious_Job_Listing_Form_Builder
     */
    private $form_builder;
    /**
     * 
     *
     * @var Wpgenious_Job_Listing_Application
     */
    private $application;

    /**
     * Wpgenious_Job_Listing_Public constructor.
     *
     * @param $plugin_name
     * @param $version
     */
    public function __construct( $plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->form_builder = Wpgenious_Job_Listing_Form_Builder::init();
        $this->application  = Wpgenious_Job_Listing_Application::init();

        $this->lang = get_locale();
    }

    public function init_actions()
    {
        $this->add_job_taxonomies();
    }

    /**
     * Register job taxonomies
     */
    public function add_job_taxonomies()
    {
        $job_fields = wpgenious_get_plugin_option('job_custom_fields') ? unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];

        foreach ($job_fields as $value) {

            $taxonomy = $value['slug'];
            $name = $value['name'];

            // Create taxonomy by lang
            if(function_exists('pll_languages_list')) {
                foreach (pll_languages_list() as $language) {
                    $tax = sanitize_title( $taxonomy . '-' . $language );
                    $this->register_wpg_job_taxonomy($tax, $name);
                }
            }else {
                $this->register_wpg_job_taxonomy($taxonomy, $name);
            }
        }
    }

    /**
     * @param $taxonomy
     * @param $name
     */
    private function register_wpg_job_taxonomy($taxonomy, $name) {
        $tax_length = strlen($taxonomy);
        if (! taxonomy_exists($taxonomy) && ( $tax_length > 0 && $tax_length <= 32 ) ) {
            $args = array(
                'labels'       => array( 'name' => esc_html($name) ),
                'show_ui'      => false,
                'show_in_menu' => false,
                'query_var'    => true,
                'rewrite'      => array( 'slug' => $taxonomy ),
            );
            register_taxonomy($taxonomy, array( 'wpgenious_job_listing' ), $args);
        }
    }

    /**
     * Display apply form
     */
    public function application_form_fields()
    {
        $this->form_builder->application_form_fields();
    }

    /**
     * Store application
     */
    public function create_application()
    {
        $this->application->create_application();
    }

    public function search_jobs()
    {
        $action = sanitize_text_field($_POST['action']);

        if(($_SERVER['REQUEST_METHOD'] === 'POST') && ($action === 'search_job')) {

            $terms = array_map('sanitize_text_field', $_POST['search-by']);
            $args = array();

            foreach ($terms as $key => $value) {
                if($key === 'keyword') {
                    $args['s'] = $value;
                }elseif($value) {
                    $args['tax_query'][] = array(
                        'taxonomy' => $key,
                        'field' => 'term_id',
                        'terms' => $value,
                    );
                }
            }

            $args['post_type'] = 'job_listing';
            $args['posts_per_page'] = sanitize_text_field($_POST['jobs_per_page']) ?: 10 ;

            if(!empty($_POST['paged'])) {
                $args['paged'] = sanitize_text_field($_POST['paged']);
            }

            // Check if polylang is activated
            if(function_exists('pll_the_languages')) {
                $args['lang'] = pll_current_language();
            }

            $query = new WP_Query($args);
            ob_start();
            include_once WPGENIOUS_JOB_LISTING_PUBLIC_DIR . 'partials/results-job_listing.php';
            $result = ob_get_clean();

            wp_send_json(
                array(
                'response' => $result,
                'max_num_pages' => $query->max_num_pages
                )
            );
        }

        wp_send_json('');
    }

    /**
     * Enqueue public styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL. 'css/public/wpgenious-job-listing-public.css', array(), $this->version, 'all');
        wp_enqueue_style('job-icons-style', WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'css/fonts/icons.css', false, $this->version, 'all');
    }

    /**
     * Enqueue public scripts
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'js/public/wpgenious-job-listing-public.js', array( 'jquery' ), $this->version, false);

        wp_localize_script(
            $this->plugin_name,
            'script_info',
            $this->get_javascript_vars()
        );

        if( wpgenious_get_plugin_option('g_recaptcha_enabled') && is_singular('job_listing') ) {

            $key      = wpgenious_get_plugin_option('g_recaptcha_key');
            $language = get_bloginfo( 'language' );

            wp_enqueue_script(
                'google-recaptcha',
                "//www.google.com/recaptcha/api.js?render=${key}&hl=${language}",
                array(),
                null,
                false
            );
        }
    }

    /**
     * @return array
     */
    private function get_javascript_vars() {
        global $post;
        return array(
            'ajaxurl'             => admin_url('admin-ajax.php'),
            'job_id'              => is_singular('job_listing') ? $post->ID : 0,
            'jobs_per_page'       => is_post_type_archive('job_listing') ? wpgenious_get_plugin_option('listing_jobs_per_page') : 0,
            'wp_max_upload_size'  => ( wp_max_upload_size() ) ? ( wp_max_upload_size() ) : 0,
            'g_recaptcha_key'     => wpgenious_get_plugin_option('g_recaptcha_key'),
            'g_recaptcha_enabled' => wpgenious_get_plugin_option('g_recaptcha_enabled'),
            'messages'            => array(
                'loading'   => esc_html__('Loading...', 'wpgenious-job-listing'),
                'form_error' => array(
                    'general'         => esc_html__('Error in submitting your application. Please try again later!', 'wpgenious-job-listing'),
                    'file_size' => esc_html__('The file you have selected is too large.', 'wpgenious-job-listing'),
                ),
            ),
        );
    }

    /**
     * Templates
     *
     * @param  $template
     * @return string
     */
    public function template_include( $template )
    {
        $job_templates        = array( 'job-listing/single-job_listing.php' );
        $archive_templates    = array( 'job-listing/archive-job_listing.php' );

        if(is_singular('job_listing') && locate_template($job_templates)) {
            $template = locate_template($job_templates);
        }elseif (is_post_type_archive('job_listing')) {
            $template = locate_template($archive_templates)?: plugin_dir_path(__DIR__) . 'public/partials/archive-job_listing.php';
        }

        return $template;
    }

    /**
     * @param  $content
     * @return false|string
     */
    public function job_content( $content )
    {
        if (! is_singular('job_listing') || ! in_the_loop() || ! is_main_query() ) {
            return $content;
        }

        // Display JobPosting json
        $this->get_job_posting_data();

        ob_start();
        if(is_singular('job_listing')) {
            include plugin_dir_path(__DIR__) . 'public/partials/single-job_listing.php';
        }

        return ob_get_clean();
    }

    /**
     * Count views for job
     */
    public function job_views_counter()
    {
        $job_id = sanitize_text_field($_POST['job_id']);

        if (!empty($job_id) && get_post_type((int) $job_id) === 'job_listing' ) {
            $count           = 1;
            $post_view_count = get_post_meta($job_id, 'job_views_count', true);
            if (! empty($post_view_count) ) {
                $count = $post_view_count + 1;
            }
            update_post_meta($job_id, 'job_views_count', $count);
        }
    }

    /**
     * JobPosting data
     */
    private function get_job_posting_data() {
        global $post;

        $post_id         = $post->ID;
        $data            = array(
            '@context'    => 'http://schema.org/',
            '@type'       => 'JobPosting',
            'title'       => wp_strip_all_tags( get_the_title() ),
            'description' => get_the_content(),
            'datePosted'  => get_post_time( 'c' ),
        );

        $company_name = wpgenious_get_plugin_option('company_name');

        if ( ! empty( $company_name ) ) {
            $data['hiringOrganization'] = array(
                '@type'  => 'Organization',
                'name'   => $company_name,
                'sameAs' => esc_url( home_url() ),
            );
        }

        $fields = wpgenious_get_plugin_option('job_custom_fields');

        if($fields) {
            $fields = unserialize($fields);
            foreach ($fields as $field) {
                if(!empty($field['itemprop'])) {
                    $itemprop = $field['itemprop'];
                    $terms = Wpgenious_Job_Listing_Tools::wpg_get_job_terms($field['slug'], $post_id);
                    $data[$itemprop] = '';

                    if($terms) {
                        foreach ($terms as $term) {
                            $itemprop_value = $data[$itemprop] ? $data[$itemprop] . $term->name : $term->name;

                            if( ($term !== $terms[count($terms) - 1]) && ($term !== $terms[0]) ) {
                                $itemprop_value .= ", ";
                            }

                            if( $itemprop === 'addressLocality' || $itemprop === 'addressRegion' ) {
                                $data['jobLocation'] = array(
                                    '@type'   => 'Place',
                                    'address' => array(
                                        '@type' => 'PostalAddress',
                                        $itemprop => $itemprop_value
                                    )
                                );
                            }else {
                                $data[$itemprop] = $itemprop_value;
                            }
                        }
                    }
                }
            }
        }

        printf( '<script type="application/ld+json">%s</script>', wp_json_encode( $data ) );
    }

    /**
     * Translate job custom fields
     *
     * @since 1.0.5
     */
    public function translate_job_custom_fields() {
        Wpgenious_Job_Listing_Tools::translate_job_custom_fields();
    }
}
