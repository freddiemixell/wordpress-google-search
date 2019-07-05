<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/freddiemixell
 * @since      1.0.0
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/includes
 * @author     Freddie Mixell <fmixell@gmail.com>
 */
class Fm_Google_Site_Search_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

    if ( get_option('fm-google-site-search-results-id') ) {
      delete_option('fm-google-site-search-results-id');
    }
	}

}
