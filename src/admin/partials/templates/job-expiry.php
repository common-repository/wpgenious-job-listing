<?php

if (! defined('ABSPATH') ) {
    exit;
}

$post_id = get_the_ID();
$expiry = get_post_meta($post_id, 'job_expiry', true);
$display_expiry = get_post_meta($post_id, 'display_job_expiry', true);

?>
<table class="wpgenious-job-listing_table wpgenious-job-listing_meta_box_table">
    <tr>
        <td>
            <label for="job_expiry"><?php echo __('Expiry date', 'wpgenious-job-listing'); ?></label>
            <input type="date" name="job_expiry" value="<?php echo esc_html($expiry); ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for="display_job_expiry"><?php echo __('Display expiry date', 'wpgenious-job-listing'); ?></label>
            <input type="checkbox" name="display_job_expiry" value="1" <?php echo ($display_expiry === '1') ? 'checked' : ''; ?>>
        </td>
    </tr>
</table>
