<?php
if (! defined('ABSPATH') ) {
    exit;
}

$application = $post;
$email = esc_attr(get_post_meta($application->ID, 'applicant_email', true));

?>

<div class="application-container">

    <div class="application-image-container">
        <div class="image">
            <?php echo get_avatar($email, 130); ?>
        </div>
    </div>

    <div class="application-details-container">
        <?php $this->application_details_list($application); ?>
    </div>

</div>
