<?php
/**
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 */

/**
 * Manage Form.
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/public
 * @author     wpgenious <contact@wpgenious.com>
 */

class Wpgenious_Job_Listing_Form_Builder
{
    private static $instance = null;

    /**
     * @return wpgenious_job_listing_Form_Builder
     */
    public static function init()
    {
        return (  self::$instance  === null ) ? ( self::$instance = new self() ) : self::$instance;
    }

    /**
     * Display Form
     */
    public function application_form_fields()
    {
        $this->display_dynamic_fields();
        $this->gdpr_field();
    }

    /**
     * Display Form Fields
     */
    private function display_dynamic_fields()
    {
        $fields = $this->get_dynamic_form_fields();

        if(!empty($fields)) {
            $default_required_msg = esc_attr__('This field is required.', 'wpgenious-job-listing');
            $output = '';

            foreach ($fields as $key => $field) {
                $label = !empty($field['label']) ? $field['label'] : '';
                $id = $field['id'];
                $class = is_array($field['class']) ? implode(' ', $field['class']) : $field['class'];
                $type = !empty($field['field_type']['type']) ? $field['field_type']['type'] : '';
                $tag = !empty($field['field_type']['tag']) ? $field['field_type']['tag'] : '';
                $options = !empty($field['field_type']['options']) ? $field['field_type']['options'] : '';
                $required = !empty($field['required']) ? $field['required'] : true;
                $extra_content = !empty($field['content'])? $field['content'] : '';
                $required_attr = array(
                    'required' => $required ? 'required' : '',
                    'data' => $required ? ' data-msg-required="' . $default_required_msg . '"' : '',
                    'label' => $required ? ' <span class="wpgenious-job-listing-apply-form-error">*</span>' : '',
                    'error_msg' => $required ? ( !empty($field['error']['error_msg']) ? $field['error']['error_msg'] : '' ) : ''
                );
                $common_attrs = sprintf('name="%1$s" class="%2$s" id="%3$s"%4$s', esc_attr($key), esc_attr($class), esc_attr($id), $required_attr['required'] . $required_attr['data'] . $required_attr['error_msg']);
                $form_group_class = 'form-group wpgenious-job-listing-form-group';

                $field_content = '';

                $label_content = !empty($label) ?  sprintf('<label for="%2$s">%1$s</label>', $label.$required_attr['label'], esc_attr($id)) : '';

                switch ($tag) {
                case 'input':
                    $field_content .= sprintf('<input type="%1$s" %2$s />', esc_attr($type), $common_attrs);
                    break;
                case 'textarea':
                    $field_content .= sprintf('<textarea %1$s rows="5" cols="50"></textarea>', $common_attrs);
                    break;
                case 'select' || 'checkbox' || 'radio':
                    if(!empty($options) && is_array($options)) {
                        $options_content = '';

                        if($tag === 'select') {
                            $options_content .= sprintf('<option value="">%s</option>', esc_html__('Please Choose an Option', 'wpgenious-job-listing'));
                            foreach ( $options as $option ) {
                                $options_content .= sprintf('<option value="%s">%s</option>', esc_attr($option), esc_html($option));
                            }
                            $field_content .= sprintf('<select %2$s>%1$s</select>', $options_content, $common_attrs);
                        } else {
                            $id_suffix = 1;
                            foreach ( $options as $option ) {
                                $name_suffix      = ( $type === 'checkbox' ) ? '[]' : '';
                                $current_field_id = esc_attr($id . '_' . $id_suffix);
                                $common_attrs     = sprintf(
                                    'name="%1$s" class="%2$s" id="%3$s"%4$s',
                                    esc_attr($key . $name_suffix),
                                    esc_attr($class),
                                    $current_field_id,
                                    $required_attr['required'] .
                                    $required_attr['data'] .
                                    $required_attr['error_msg']
                                );
                                $options_content .= sprintf('<span><input type="%s" value="%s" %s /> <label for="%s">%s</label></span>', esc_attr($type), esc_attr($option), $common_attrs, $current_field_id, esc_html($option));
                                $id_suffix ++;
                            }
                            $field_content .= sprintf('<div class="wpgenious-job-listing-job-form-options-container">%s</div>', $options_content);
                        }

                    }
                    break;
                default:
                    break;
                }


                /**
                 * Filters the field content of a specific field key of the job application form.
                 *
                 * @since 1.0.0
                 */
                $field_content = apply_filters("wpgenious_job_listing_application_dynamic_form_{$key}_field_content", $field_content);
                $output  .= sprintf('<div class="%2$s">%1$s</div>', $label_content.$field_content.$extra_content, esc_attr($form_group_class));
            }

            /**
             * Filters the dynamic form fields content of the job application form.
             *
             * @since 1.0.0
             */
            echo apply_filters('wpgenious_job_listing_apply_form_fields_content', $output, $fields);
        }

    }

    /**
     * @return mixed
     */
    private function get_dynamic_form_fields()
    {
        $allowed_file_types   = wpgenious_get_plugin_option('allowed_upload_file_ext');
        $allowed_file_content = '';

        if (is_array($allowed_file_types) && ! empty($allowed_file_types) ) {
            $allowed_file_types = '.' . implode(', .', $allowed_file_types);
            $allowed_file_content = '<small>' . sprintf(esc_html__('Allowed Type(s): %1$s', 'wpgenious-job-listing'), $allowed_file_types) . '</small>';
        }

        $default_form_fields = array(
            'wpgenious_job_listing_candidature_name'   => array(
                'label' => __('Full Name', 'wpgenious-job-listing'),
                'field_type' => array(
                    'tag'  => 'input',
                    'type' => 'text',
                ),
                'id'    => 'wpgenious-job-listing-apply-form-name',
                'class' => array( 'wpgenious-job-listing-form-control' ),
            ),

            'wpgenious_job_listing_candidature_email'  => array(
                'label'      => __('Email', 'wpgenious-job-listing'),
                'field_type' => array(
                    'tag'  => 'input',
                    'type' => 'email',
                ),
                'id'         => 'wpgenious-job-listing-apply-form-email',
                'class'      => array( 'wpgenious-job-listing-form-control' ),
                'error'      => array(
                    'error_rule' => 'email',
                    'error_msg'  => __('Please enter a valid email address.', 'wpgenious-job-listing'),
                ),
            ),

            'wpgenious_job_listing_candidature_phone'  => array(
                'label'      => __('Phone', 'wpgenious-job-listing'),
                'field_type' => array(
                    'tag'  => 'input',
                    'type' => 'tel',
                ),
                'id'         => 'wpgenious-job-listing-apply-form-phone',
                'class'      => array( 'wpgenious-job-listing-form-control' ),
                'error'      => array(
                    'error_msg' => __('Please enter a valid phone number.', 'wpgenious-job-listing'),
                ),
            ),

            'wpgenious_job_listing_candidature_letter' => array(
                'label'      => __('Cover Letter', 'wpgenious-job-listing'),
                'field_type' => array(
                    'tag' => 'textarea',
                ),
                'id'         => 'wpgenious-job-listing-apply-form-cover-letter',
                'class'      => array( 'wpgenious-job-listing-job-form-control' ),
            ),

            'wpgenious_job_listing_candidature_file' => array(
                'label'      => __('Upload CV/Resume', 'wpgenious-job-listing'),
                'field_type' => array(
                    'tag'    => 'input',
                    'type'   => 'file',
                    'accept' => $allowed_file_types,
                ),
                'id'         => 'wpgenious-job-listing-apply-form-file',
                'class'      => array( 'wpgenious-job-listing-form-control', 'wpgenious-job-listing-form-file-control' ),
                'content'    => $allowed_file_content,
            ),
        );

        /**
         * Filters the default dynamic form fields content of the job application form.
         *
         * @since 1.0.0
         */
        return apply_filters('wpgenious_job_listing_candidature_form_fields', $default_form_fields);
    }

    /**
     * Display RDPG Fields
     */
    private function gdpr_field()
    {

        $text = wpgenious_get_plugin_option('gdpr_field_text');
        $enable  = wpgenious_get_plugin_option('gdpr_field_enabled');

        $label = ( (bool) $enable && ! empty($text) ) ? html_entity_decode($text, ENT_QUOTES) : null;

        if ($label !== null ) :
            ?>
                <div class="form-group wpgenious-job-listing-form-group wpgenious-job-listing-job-inline-group">
                    <input name="gdpr" class="wpgenious-job-listing-job-form-control" id="gdpr" required=""
                           data-msg-required="<?php echo esc_attr__('This field is required.', 'wpgenious-job-listing'); ?>"
                           value="yes" aria-required="true" type="checkbox">
                    <label for="gdpr"><?php echo  esc_html_e($label, 'wpgenious-job-listing'); ?>
                        <span class="wpgenious-job-listing-apply-form-error">*</span></label>
                </div>
            <?php
        endif;
    }

    /**
     * Submit button
     */
    public static function submit_btn()
    {
        $text     = apply_filters('job_application_form_submit_btn_text', __('Submit', 'wpgenious-job-listing'));
        $submitting_text = apply_filters('job_application_form_submit_btn_res_text', __('Submitting...', 'wpgenious-job-listing'));

        $g_recaptcha_enabled = wpgenious_get_plugin_option('g_recaptcha_enabled');
        $key = wpgenious_get_plugin_option('g_recaptcha_key');

        if($g_recaptcha_enabled):
            ?>
                <input type="submit" name="form_sub" class="wpgenious-job-listing-btn"
                       data-sitekey="<?php echo $key; ?>"
                       data-callback='onSubmit'
                       data-action='submit'
                       id="wpgenious-job-listing-application-submit-btn"
                       value="<?php echo esc_attr($text); ?>"
                       data-response-text="<?php echo esc_attr($submitting_text); ?>" />
            <?php
        else :
            ?>
                <input type="submit" name="form_sub" class="wpgenious-job-listing-btn"
                       id="wpgenious-job-listing-application-submit-btn"
                       value="<?php echo esc_attr($text); ?>"
                       data-response-text="<?php echo esc_attr($submitting_text); ?>" />
            <?php
        endif;
    }
}
