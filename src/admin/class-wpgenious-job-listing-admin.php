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

require_once WPGENIOUS_JOB_LISTING_ADMIN_DIR. 'class/class-wpgenious-job-listing-options.php';
require_once WPGENIOUS_JOB_LISTING_ADMIN_DIR. 'class/class-wpgenious-job-listing-meta.php';
require_once WPGENIOUS_JOB_LISTING_ADMIN_DIR. 'class/class-wpgenious-job-listing-post-type.php';
require_once WPGENIOUS_JOB_LISTING_INC_DIR. 'class-wpgenious-job-listing-tools.php';

class Wpgenious_Job_Listing_Admin
{

    private $plugin_name;
    private $version;
    private $job_options;
    private $job_meta;

    /**
     * wpgenious_job_listing_Admin constructor.
     *
     * @param $plugin_name
     * @param $version
     */
    public function __construct( $plugin_name, $version )
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->job_options = Wpgenious_Job_Listing_Options::init();
        $this->job_meta = Wpgenious_Job_Listing_Meta::init();
    }

    /**
     * Plugins settings
     */
    public function register_settings()
    {
        $this->job_options->register_settings();
    }

    /**
     * Meta boxes
     */
    public function add_meta_boxes()
    {
        $this->job_meta->add_meta_boxes();
    }

    /**
     * @param $post_id
     * @param $post
     */
    public function save_job_and_application_post_type($post_id, $post)
    {
        if (! current_user_can('edit_post', $post_id) ) {
            return;
        }

        if ($post->post_type === 'job_listing') {
            $this->job_meta->save_job_custom_fields($post_id);
        }elseif ($post->post_type === 'job_application') {
            $this->job_meta->update_job_application($post_id);
        }
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'css/admin/wpgenious-job-listing-admin.css', array(), $this->version, 'all');
        wp_register_style('select2css', WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'css/admin/select2.min.css', false, $this->version, 'all');
        wp_register_style('job-icons-style', WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'css/fonts/icons.css', false, $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'js/admin/wpgenious-job-listing-admin.js', array( 'jquery', 'wp-util', 'jquery-ui-sortable' ), $this->version, false);
        wp_register_script('select2js', WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL . 'js/admin/select2.min.js', array('jquery'), $this->version, true);
    }

    /**
     * Subtitle for application
     */
    public function admin_single_subtitle()
    {
        Wpgenious_Job_Listing_Post_Type::admin_single_subtitle();
    }

    /**
     * Create posts types
     */
    public function register_post_types()
    {
        Wpgenious_Job_Listing_Post_Type::register_wpgenious_job_listing();
        Wpgenious_Job_Listing_Post_Type::register_job_application();
    }

    /**
     * @param $actions
     * @param $post
     * @return mixed
     */
    public function posts_row_actions($actions, $post)
    {
        return Wpgenious_Job_Listing_Post_Type::row_actions($actions, $post);
    }

    /**
     * @param $query
     */
    public function filter_posts($query)
    {
        Wpgenious_Job_Listing_Post_Type::filter_posts($query);
    }

    /**
     * Custom columns for application
     *
     * @return array
     */
    public function custom_applicant_columns()
    {
        return Wpgenious_Job_Listing_Post_Type::custom_applicant_columns();
    }

    /**
     * @param $column
     * @param $post_id
     */
    public function custom_applicant_columns_data( $column, $post_id )
    {
        Wpgenious_Job_Listing_Post_Type::custom_applicant_columns_data($column, $post_id);
    }

    /**
     * Custom columns for job offer post
     *
     * @return array
     */
    public function custom_job_columns()
    {
        return Wpgenious_Job_Listing_Post_Type::custom_job_columns();
    }

    /**
     * @param $column
     * @param $post_id
     */
    public function custom_job_columns_data( $column, $post_id )
    {
        Wpgenious_Job_Listing_Post_Type::custom_job_columns_data($column, $post_id);
    }

    /**
     * Add custom menu
     */
    public function custom_admin_menu()
    {
        add_submenu_page(
            'edit.php?post_type=job_listing',
            esc_html__('Settings', 'wpgenious-job-listing'),
            esc_html__('Settings', 'wpgenious-job-listing'),
            'manage_options',
            $this->plugin_name,
            array( $this, 'settings_page' )
        );

        global $submenu;
        $submenu['edit.php?post_type=job_listing'][] = array(
            sprintf('<span>%s</span>', esc_html__('Contact Us', 'wpgenious-job-listing')),
            'edit_others_applications',
            esc_url('#'),
        );
    }

    /**
     * Display settings page
     */
    public function settings_page()
    {
        include_once WPGENIOUS_JOB_LISTING_ADMIN_DIR . 'partials/wpgenious-job-listing-admin-display.php';
    }

    /**
     * Display admin nav
     */
    public function admin_nav()
    {
        $screen  = get_current_screen();
        $post_type = $screen->post_type;
        if( (($post_type === 'job_listing') || ($post_type === 'job_application') ) && !(method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor())) {
            $items = array(
                array(
                    'visible' => current_user_can( 'edit_posts' ),
                    'label'   => __('Jobs', 'wpgenious-job-listing'),
                    'url'     => admin_url('edit.php?post_type=job_listing'),
                    'class'   => ( $post_type === 'job_listing' && !isset($_GET['page'])) ?  array('active') :  array()
                ),
                array(
                    'visible' => current_user_can( 'edit_posts' ),
                    'label'   => __('Applications', 'wpgenious-job-listing'),
                    'url'     => admin_url('edit.php?post_type=job_application'),
                    'class'   => ( $post_type === 'job_application') ?  array('active') :  array()
                ),
                array(
                    'visible' => current_user_can( 'edit_posts' ),
                    'label'   => __('Settings', 'wpgenious-job-listing'),
                    'url'     => admin_url('edit.php?post_type=job_listing&page=wpgenious-job-listing'),
                    'class'   => ( $post_type === 'job_listing' && isset($_GET['page']) ) ?  array('active') :  array()
                ),
                array(
                    'label'   => __('Documentation', 'wpgenious-job-listing'),
                    'url'     => 'http://wpgenious.com/docs/doc/introduction',
                    'target'  => '_blank'
                ),
                array(
                    'label'   => __('Contact Us', 'wpgenious-job-listing'),
                    'class'   => array( 'button' ),
                    'url'     => 'http://wpgenious.com/contact',
                    'target'  => '_blank'
                ),
            );

            ?>
            <div class="wpgenious-job-listing-admin-nav-header">
                <div class="wpgenious-job-listing-logo">
                    <a href="http://wpgenious.com/contact/" target="_blank">
                        <img src="<?php echo WPGENIOUS_JOB_LISTING_ASSETS_DIR_URL. 'img/logo.png' ; ?>">
                    </a>
                </div>
                <div class="wpgenious-job-listing-admin-nav">
                    <ul>
                    <?php foreach ($items as $item): ?>
                        <?php $display = isset( $item['visible'] ) ? $item['visible'] : true; ?>
                        <?php if($display) : ?>
                            <li>
                                <a class="<?php echo  (!empty($item['class']) && is_array($item['class'])) ? join(' ', $item['class']) : '';  ?>"
                                    <?php echo !empty($item['target']) ? 'target="'.esc_attr($item['target']).'"' : ''; ?>
                                    href="<?php echo esc_url($item['url']); ?>">
                                    <?php echo esc_html($item['label']); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Add class to body
     *
     * @param  $classes
     * @return string
     */
    public function admin_body_class( $classes )
    {
        $screen  = get_current_screen();
        $post_type = $screen->post_type;
        if(($post_type === 'job_listing') || ($post_type === 'job_application')) {
            $classes .= ' wpgenious-job-listing-admin-page ';
        }

        return $classes;
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
