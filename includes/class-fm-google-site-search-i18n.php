<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/freddiemixell
 * @since      1.0.0
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/includes
 * @author     Freddie Mixell <fmixell@gmail.com>
 */
class Fm_Google_Site_Search_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fm-google-site-search',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
