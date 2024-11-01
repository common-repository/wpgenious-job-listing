<?php

require_once WPGENIOUS_JOB_LISTING_INC_DIR.'class-wpgenious-widgets.php';

class Wpgenious_similar_jobs extends Wpgenious_Widgets {

    /**
     * Wpgenious_latest_jobs constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'wp-genius-similar-jobs',
            _x( 'Similar Jobs', 'widget', 'wpgenious-job-listing' ),
            _x( 'Display Similar jobs', 'widget', 'wpgenious-job-listing' )
        );
    }

    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        $fields = wpgenious_get_plugin_option('job_custom_fields') ?
            unserialize(wpgenious_get_plugin_option('job_custom_fields')) : [];

        if ( $instance ) {
            $title          = $instance['title'];
            $number         = (int) $instance['number'] ?: 3;
            $similar_by     = $instance['similar_by'];
        } else {
            $title          = '';
            $number         = 3 ;
            $similar_by     = '';
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
                    'Number of jobs similar to display',
                    'widget',
                    'wpgenious-job-listing'
                );
                ?>
                :</label>
            <input class="widefat" name="<?php echo $this->get_field_name( 'number' ); ?>"
                   type="number" value="<?php echo esc_attr($number); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'similar_by' ); ?>">
                <?php
                _ex(
                    'Number of jobs similar to display',
                    'widget',
                    'wpgenious-job-listing'
                );
                ?>
                :</label>
            <select class="widefat" name="<?php echo $this->get_field_name( 'similar_by' ); ?>">
                <?php foreach ($fields as $field) : ?>
                    <option <?php echo ( $field['slug'] === $similar_by ) ? 'selected' : ''; ?> value="<?php echo $field['slug'] ?>">
                        <?php echo $field['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance               = $old_instance;
        $instance['title']      = wp_strip_all_tags( (string) $new_instance['title'] );
        $instance['number']     = (int) esc_attr($new_instance['number']);
        $instance['similar_by'] = wp_strip_all_tags( (string) $new_instance['similar_by'] );

        return $instance;
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $title      = (string) $instance['title'];
        $number     = (int) $instance['number'];
        $similar_by = $instance['similar_by'];

        $default  = array(
            'before_title'  => '',
            'after_title'   => '',
            'before_widget' => '',
            'after_widget'  => ''
        );

        $args = wp_parse_args( $args, $default );

        $jobs = $this->similar_jobs($number, $similar_by);

        if (is_singular('job_listing')) :
            echo $args['before_widget'];
            ?>

            <div class="wpgenious-job-listing-widget similar-jobs">
                <?php echo $args['before_title'] . $title . $args['after_title'] ?>

                <?php foreach ($jobs as $job) : ?>
                    <div class="wpgenious-job-item">

                        <div class="wpgenious-list-left-col">
                            <h3 class="wpgenious-job-title">
                                <?php echo $job->post_title; ?>
                            </h3>
                        </div>

                        <div class="wpgenious-list-right-col">

                            <?php Wpgenious_Job_Listing_Tools::job_link($job->ID); ?>

                        </div>
                    </div>
                <?php endforeach;?>

            </div>

            <?php
            echo $args['after_widget'];
        endif;
    }

    /**
     * @param $count
     * @param $slug
     * @return int[]|WP_Post[]
     *
     * @since 1.0.5
     */
    private function similar_jobs( $count, $slug ) {
        $job_id = get_the_ID();

        $terms = Wpgenious_Job_Listing_Tools::wpg_get_job_terms($slug, $job_id);

        $slug = Wpgenious_Job_Listing_Tools::wpg_get_job_slug($slug);

        if ( empty( $terms ) ) $terms = array();

        $term_list = wp_list_pluck( $terms, 'slug' );

        $related_args = array(
            'post_type' => 'job_listing',
            'posts_per_page' => $count,
            'post_status' => 'publish',
            'post__not_in' => array( $job_id ),
            'orderby' => 'rand',
            'tax_query' => array(
                array(
                    'taxonomy' => $slug,
                    'field' => 'slug',
                    'terms' => $term_list
                )
            )
        );

        $query = new WP_Query( $related_args );

        return $query->posts;
    }
}
