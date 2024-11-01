<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 * @author     wpgenious <contact@wpgenious.com>
 */

require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-widgets.php';

class Wpgenious_Job_Listing
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    Wpgenious_Job_Listing_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->version = WPGENIOUS_JOB_LISTING_VERSION;
        $this->plugin_name = 'wpgenious-job-listing';

        $this->init();
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Init plugin
     *
     * @since  1.0.0
     * @access private
     */
    public function init()
    {
        Wpgenious_Widgets::init();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wpgenious_Job_Listing_Loader. Orchestrates the hooks of the plugin.
     * - Wpgenious_Job_Listing_i18n. Defines internationalization functionality.
     * - wpgenious_job_listing_Admin. Defines all hooks for the admin area.
     * - Wpgenious_Job_Listing_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once WPGENIOUS_JOB_LISTING_INC_DIR . 'class-wpgenious-job-listing-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        include_once WPGENIOUS_JOB_LISTING_INC_DIR. 'class-wpgenious-job-listing-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        include_once WPGENIOUS_JOB_LISTING_ADMIN_DIR . 'class-wpgenious-job-listing-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        include_once WPGENIOUS_JOB_LISTING_PUBLIC_DIR . 'class-wpgenious-job-listing-public.php';

        $this->loader = new Wpgenious_Job_Listing_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wpgenious_Job_Listing_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function set_locale()
    {

        $plugin_i18n = new Wpgenious_Job_Listing_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Wpgenious_Job_Listing_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_admin, 'register_post_types');
        $this->loader->add_action('init', $plugin_admin, 'translate_job_custom_fields');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'custom_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_boxes');
        $this->loader->add_action('save_post', $plugin_admin, 'save_job_and_application_post_type', 100, 2);
        $this->loader->add_action('edit_form_top', $plugin_admin, 'admin_single_subtitle');
        $this->loader->add_action('in_admin_header', $plugin_admin, 'admin_nav');
        $this->loader->add_action('parse_query', $plugin_admin, 'filter_posts');
        $this->loader->add_filter('manage_job_listing_posts_columns', $plugin_admin, 'custom_job_columns');
        $this->loader->add_filter('manage_job_listing_posts_custom_column', $plugin_admin, 'custom_job_columns_data', 10, 2);
        $this->loader->add_filter('manage_job_application_posts_columns', $plugin_admin, 'custom_applicant_columns');
        $this->loader->add_filter('manage_job_application_posts_custom_column', $plugin_admin, 'custom_applicant_columns_data', 10, 2);
        $this->loader->add_filter('post_row_actions', $plugin_admin, 'posts_row_actions', 10, 2);
        $this->loader->add_filter('admin_body_class', $plugin_admin, 'admin_body_class');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Wpgenious_Job_Listing_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_public, 'init_actions');
        $this->loader->add_action('init', $plugin_public, 'translate_job_custom_fields');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_job_views_count', $plugin_public, 'job_views_counter');
        $this->loader->add_action('job_application_form_fields', $plugin_public, 'application_form_fields');
        $this->loader->add_action('wp_ajax_nopriv_wpgenious-job-listing_apply_form_submission', $plugin_public, 'create_application');
        $this->loader->add_action('wp_ajax_nopriv_search_job', $plugin_public, 'search_jobs');
        $this->loader->add_action('wp_ajax_wpgenious-job-listing_apply_form_submission', $plugin_public, 'create_application');
        $this->loader->add_action('wp_ajax_search_job', $plugin_public, 'search_jobs');
        $this->loader->add_filter('single_template', $plugin_public, 'template_include');
        $this->loader->add_filter('job_template', $plugin_public, 'template_include');
        $this->loader->add_filter('the_content', $plugin_public, 'job_content', 100);
        $this->loader->add_filter('archive_template', $plugin_public, 'template_include');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.0.0
     * @return Wpgenious_Job_Listing_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.0.0
     * @return string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
