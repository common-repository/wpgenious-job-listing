<?php

require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-widgets.php';

class Wpgenious_latest_jobs extends Wpgenious_Widgets {

    /**
     * Wpgenious_latest_jobs constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'wp-genius-latest-jobs',
            _x( 'Latest Jobs', 'widget', 'wpgenious-job-listing' ),
            _x( 'Display latest jobs', 'widget', 'wpgenious-job-listing' )
        );
    }

    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        if ( $instance ) {
            $title          = $instance['title'];
            $number         = (int) $instance['number'] ?: 10;
        } else {
            $title          = '';
            $number         = 10;
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php
                _ex(
                    'Title',
                    'widget',
                    'wpgenious-job-listing'
                );
                ?>
                :</label>
            <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>">
                <?php
                _ex(
                    'Number of jobs to display',
                    'widget',
                    'wpgenious-job-listing'
                );
                ?>
                :</label>
            <input class="widefat" name="<?php echo $this->get_field_name( 'number' ); ?>"
                   type="number" value="<?php echo esc_attr($number); ?>"/>
        </p>
        <?php
    }

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance             = $old_instance;
        $instance['title']    = wp_strip_all_tags( (string) $new_instance['title'] );
        $instance['number']   = (int) esc_attr($new_instance['number']);

        return $instance;
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $title          = (string) $instance['title'];
        $number         = (int) $instance['number'];

        $default  = array(
            'before_title'  => '',
            'after_title'   => '',
            'before_widget' => '',
            'after_widget'  => ''
        );
        $args     = wp_parse_args( $args, $default );

        $query = new WP_Query(array(
            'post_type' => 'job_listing',
            'posts_per_page' => $number
        ));

        echo $args['before_widget'];
        ?>

        <div class="wpgenious-job-listing-widget latest-jobs">
            <?php echo $args['before_title'] . $title . $args['after_title'] ?>

            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="wpgenious-job-item">

                    <div class="wpgenious-list-left-col">
                        <h3 class="wpgenious-job-title">
                            <?php echo get_the_title(); ?>
                        </h3>
                    </div>

                    <div class="wpgenious-list-right-col">

                        <?php Wpgenious_Job_Listing_Tools::job_link(); ?>

                    </div>
                </div>
            <?php endwhile;?>

        </div>

        <?php
        echo $args['after_widget'];
    }
}
