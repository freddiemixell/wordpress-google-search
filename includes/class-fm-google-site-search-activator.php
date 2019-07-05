<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/freddiemixell
 * @since      1.0.0
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/includes
 * @author     Freddie Mixell <fmixell@gmail.com>
 */
class Fm_Google_Site_Search_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

    $results_page = array(
      'post_title'    => wp_strip_all_tags( 'Search Results' ),
      'post_content'  => '[google_search_results]',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
    );

    if ( get_page_by_title( $results_page['post_title'] ) == null ) {

      // Insert the results page into the database
      $result = wp_insert_post( $results_page, true );

      if (is_wp_error( $result ) ) {

        $echo_string = $result->get_error_message();

        esc_html_e( $echo_string, 'fm-google-site-search' );

      } else {

        add_option( 'fm-google-site-search-results-id', $result );

      }
    }

	}

}
