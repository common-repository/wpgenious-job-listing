<?php
if (! defined('ABSPATH') ) {
    exit;
}

global $post;

$application_status = wpgenious_get_plugin_option('application_status');
$current_status = esc_attr(get_post_meta($post->ID, 'applicant_status', true));
$rating = get_post_meta($post->ID, 'applicant_rating', true);

?>

<div class="submitbox awsm-application-actions-disabled" id="submitpost">

    <div id="minor-publishing">
    <table class="wpgenious-job-listing_table wpgenious-job-listing_meta_box_table">
        <tr>
            <td>
                <span class="dashicons dashicons-post-status"></span> <?php echo __('Status', 'wpgenious-job-listing'); ?>
            </td>
            <td>
                <select name="applicant_status" id="applicant_status">
                    <?php foreach ($application_status as $key => $status) : ?>
                        <option <?php echo ($current_status === $key) ? 'selected' : ''; ?> value="<?php echo esc_html($key); ?>">
                            <?php echo esc_html_e($status['name'], 'wpgenious-job-listing'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="dashicons dashicons-calendar-alt"></span>
                <?php
                $date = date_i18n(get_option('time_format'). ', ' . get_option('date_format'), strtotime($post->post_date));
                echo sprintf(__('<span class="text">Received %s</span>', 'wpgenious-job-listing'), $date);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="wpgenious-job-listing-rating action <?php echo empty($rating) ? 'empty-rating' : ''; ?>">
                    <label class="dashicons dashicons-star-<?php echo ($rating >= 1) ? 'filled' : 'empty'; ?>" for="1"></label>
                    <input <?php echo ($rating == 1) ? 'checked' : ''; ?> name="applicant_rating" type="radio" value="1" id="1">
                    <label class="dashicons dashicons-star-<?php echo ($rating >= 2) ? 'filled' : 'empty'; ?>" for="2"></label>
                    <input <?php echo ($rating == 2) ? 'checked' : ''; ?> name="applicant_rating" type="radio" value="2" id="2">
                    <label class="dashicons dashicons-star-<?php echo ($rating >= 3) ? 'filled' : 'empty'; ?>" for="3"></label>
                    <input <?php echo ($rating == 3) ? 'checked' : ''; ?> name="applicant_rating" type="radio" value="3" id="3">
                    <label class="dashicons dashicons-star-<?php echo ($rating >= 4) ? 'filled' : 'empty'; ?>" for="4"></label>
                    <input <?php echo ($rating == 4) ? 'checked' : ''; ?> name="applicant_rating" type="radio" value="4" id="4">
                    <label class="dashicons dashicons-star-<?php echo ($rating == 5) ? 'filled' : 'empty'; ?>" for="5"></label>
                    <input <?php echo ($rating == 5) ? 'checked' : ''; ?> name="applicant_rating" type="radio" value="5" id="5">
                </div>
            </td>
        </tr>
    </table>
</div>

    <div id="major-publishing-actions" >
        <?php $this->application_delete_action($post->ID); ?>
        <?php $this->application_update_action(); ?>
        <div class="clear"></div>
    </div>

</div>
