<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/class/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/class/admin
 * @author     wpgenious <contact@wpgenious.com>
 */

if (! defined('ABSPATH') ) {
    exit;
}

class Wpgenious_Job_Listing_Meta
{
    private static $instance = null;
    private $path;

    /**
     * Wpgenious_Job_Listing_Meta constructor.
     */
    public function __construct()
    {
        $this->path = untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * @return Wpgenious_Job_Listing_Meta
     */
    public static function init()
    {
        return (  self::$instance  === null ) ? new self() : self::$instance;
    }

    /**
     * Add meta boxes for job offer and applicant
     */
    public function add_meta_boxes()
    {
        global $action;

        if ($action === 'edit' ) {
            add_meta_box('job-information', esc_html__('Job Information', 'wpgenious-job-listing'), array( $this, 'job_information' ), 'job_listing', 'side', 'low');
            add_meta_box('applicant-details', esc_html__('Application details', 'wpgenious-job-listing'), array( $this, 'application_details' ), 'job_application', 'normal', 'high');
            add_meta_box('applicant-resume-preview', esc_html__('Resume preview', 'wpgenious-job-listing'), array( $this, 'resume_preview' ), 'job_application', 'normal', 'default');
            add_meta_box('applicant-job-details', esc_html__('Job', 'wpgenious-job-listing'), array( $this, 'application_job_details' ), 'job_application', 'side', 'low');
            add_meta_box('applicant-actions', esc_html__('Actions', 'wpgenious-job-listing'), array( $this, 'application_actions' ), 'job_application', 'side', 'high');

            // Remove meta boxes
            remove_meta_box('submitdiv', 'job_application', 'side');
        }

        add_meta_box('job-custom-field', esc_html__('Job Custom Fields', 'wpgenious-job-listing'), array( $this, 'job_custom_fields' ), 'job_listing', 'normal', 'high');
        add_meta_box('job-expiry', esc_html__('Job Expiry', 'wpgenious-job-listing'), array( $this, 'job_expiry' ), 'job_listing', 'side', 'low');
    }

    /**
     * @param $application_id
     */
    public function application_delete_action( $application_id )
    {
        ?>
        <div id="delete-action">
            <?php
            if (current_user_can('delete_post', $application_id) ) {
                if (! EMPTY_TRASH_DAYS ) {
                    $delete_text = __('Delete Permanently', 'default');
                } else {
                    $delete_text = __('Move to Trash', 'default');
                }
                printf('<a class="submitdelete deletion" href="%2$s">%1$s</a>', esc_html($delete_text), esc_url(get_delete_post_link($application_id)));
            }
            ?>
        </div>
        <?php
    }

    public function application_update_action()
    {
        ?>
        <div id="publishing-action">
            <span class="spinner"></span>
            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update'); ?>" />
            <?php submit_button(__('Update'), 'primary large', 'save', false, array( 'id' => 'publish' )); ?>
        </div>
        <?php
    }

    /**
     * @param $application
     */
    private function application_details_list($application)
    {
        $details = array(
            array(
                'name' => __('Name', 'wpgenious-job-listing'),
                'key' => 'applicant_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Email', 'wpgenious-job-listing'),
                'key' => 'applicant_email',
                'type' => 'link',
                'href_prefix' => 'mailto:',
            ),
            array(
                'name' => __('Phone', 'wpgenious-job-listing'),
                'key' => 'applicant_phone',
                'type' => 'link',
                'href_prefix' => 'phone:',
            ),
            array(
                'name' => __('Resume', 'wpgenious-job-listing'),
                'key' => 'attachment_id',
                'type' => 'file',
            ),
            array(
                'name' => __('Cover Letter', 'wpgenious-job-listing'),
                'key' => 'applicant_letter',
                'type' => 'text',
            ),
        );

        ?>
        <ul class="application-details">
            <?php foreach ($details as $detail) : ?>
            <li>
                <?php
                    echo sprintf('<span class="name">%s : </span>', esc_html($detail['name']));
                    switch ($detail['type']) :
                        case 'text':
                            echo sprintf('<span class="detail">%s</span>', get_post_meta($application->ID, esc_html($detail['key']), true));
                            break;
                        case 'link':
                            $value = get_post_meta($application->ID, $detail['key'], true);
                            $href = empty($detail['href_prefix']) ? '' : $detail['href_prefix'];
                            $href .= $value;
                            echo sprintf('<a target="_blank" href="%s" class="detail">%s</a>', esc_url($href), esc_html($value));
                            break;
                        case 'file':
                            $attachment_id = get_post_meta($application->ID, 'attachment_id', true);
                            $attachment = $this->get_attachment_info($attachment_id);
                            echo sprintf(
                                '<a target="_blank" class="wpgenious-job-listing-file-download-link" href="%s">%s</a>',
                                esc_url($attachment['link']),
                                sprintf(__('Download resume (%s)', 'wpgenious-job-listing'), esc_html($attachment['extension']))
                            );
                            break;
                    endswitch;
                    ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

    /**
     *
     */
    public function application_actions()
    {
        include $this->path . '/../partials/templates/application-actions.php';
    }

    /**
     * Job details in application
     *
     */
    public function application_job_details()
    {
        include $this->path . '/../partials/templates/application-job-details.php';
    }

    /**
     * @param  $attachment_id
     * @return array
     */
    private function get_attachment_info($attachment_id)
    {
        $url = wp_get_attachment_url($attachment_id);
        $extension = pathinfo($url, PATHINFO_EXTENSION);

        return array(
            'link' => $url,
            'extension' => $extension
        );
    }

    public function resume_preview($post)
    {
        $application = $post;
        $attachment_id = get_post_meta($application->ID, 'attachment_id', true);

        $attachment = $this->get_attachment_info($attachment_id);
        $attachment_url = $attachment['link'];
        if(!empty($attachment_url)) :
            switch ($attachment['extension']) {
                case 'pdf':
                    ?>
                    <embed height="550" style="width: 100%; height: 550px; border: none;" src="<?php echo esc_url($attachment_url); ?>">
                    <?php
                    break;
                case 'doc':
                case 'docm':
                case 'docx':
                    ?>
                    <object height="550" style="width: 100%; border: none;" data="<?php echo  'https://docs.google.com/viewer?embedded=true&url=' . esc_url($attachment_url); ?>"></object>
                    <?php
                    break;
                default:
                    echo sprintf(
                        '<a target="_blank" class="wpgenious-job-listing-file-download-link" href="%s">%s</a>',
                        esc_url($attachment['link']),
                        sprintf(__('Download resume (%s)', 'wpgenious-job-listing'), esc_html($attachment['extension']))
                    );
                    break;
            }

        else :
            echo __('No attachment found', 'wpgenious-job-listing');
        endif;
    }

    /**
     * Application details
     */
    public function application_details($post)
    {
        include $this->path . '/../partials/templates/application-details.php';
    }

    /**
     * Display Job information
     */
    public function job_information()
    {
        include $this->path . '/../partials/templates/job-information.php';
    }

    /**
     * Job fields
     */
    public function job_custom_fields()
    {
        include $this->path . '/../partials/templates/job-custom-fields.php';
    }

    /**
     * Job expiry
     */
    public function job_expiry()
    {
        include $this->path . '/../partials/templates/job-expiry.php';
    }

    /**
     * @param $array
     * @return mixed
     */
    private  function wpg_sanitize_assoc_array($array) {
        foreach ($array as $key => $value) {
            $array[sanitize_text_field($key)] = sanitize_text_field($value);
        }

        return $array;
    }

    /**
     * @param $post_id
     */
    public function save_job_custom_fields($post_id)
    {
        if(!empty($_POST['job_custom_fields'])) {

            $fields = $this->wpg_sanitize_assoc_array($_POST['job_custom_fields']);

            $language = false;

            // Get job language
            if(function_exists('pll_get_post')) {
                $language = pll_get_post_language($post_id, 'slug');
            }

            foreach ($fields as $taxonomy => $term) {
                if(!empty($term)) {
                    // Add language to taxonomy
                    $taxonomy = $language ? sanitize_title( $taxonomy . '-' . $language ) : $taxonomy;
                    $terms_id = wp_set_object_terms($post_id, $term, $taxonomy, false);

                    // term translation
                    if ( function_exists( 'pll_set_term_language' ) ) {
                        foreach ($terms_id as $term_id) {
                            pll_set_term_language( $term_id, $language );
                        }
                    }
                }else {
                    wp_delete_object_term_relationships($post_id, $taxonomy);
                }
            }
        }

        if(!empty($_POST['job_expiry'])) {
            $expiry = sanitize_text_field($_POST['job_expiry']);

            update_post_meta($post_id, 'job_expiry', $expiry);
        }

        if(!empty($_POST['display_job_expiry'])) {
            $display_expiry = sanitize_text_field($_POST['display_job_expiry']);

            update_post_meta($post_id, 'display_job_expiry', $display_expiry);
        }else {
            delete_post_meta($post_id, 'display_job_expiry');
        }
    }

    /**
     * @param $post_id
     */
    public function update_job_application($post_id)
    {
        if(!empty($status = sanitize_text_field($_POST['applicant_status']))) {
            update_post_meta($post_id, 'applicant_status', $status);
        }

        if(!empty($rating = sanitize_text_field($_POST['applicant_rating']))) {
            update_post_meta($post_id, 'applicant_rating', $rating);
        }
    }
}
