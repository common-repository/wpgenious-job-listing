<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link  http://wpgenious.com/
 * @since 1.0.0
 *
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wpgenious_Job_Listing
 * @subpackage Wpgenious_Job_Listing/includes
 * @author     wpgenious <contact@wpgenious.com>
 */
class Wpgenious_Job_Listing_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {
        //die(dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
        load_plugin_textdomain(
            'wpgenious-job-listing',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/../languages/'
        );

    }



}
