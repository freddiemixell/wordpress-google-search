<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/freddiemixell
 * @since             1.0.0
 * @package           Fm_Google_Site_Search
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Google Custom Search
 * Plugin URI:        https://github.com/freddiemixell/wordpress-google-search
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.2.1
 * Author:            Freddie Mixell
 * Author URI:        https://github.com/freddiemixell
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fm-google-site-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FM_GOOGLE_SITE_SEARCH_VERSION', '1.2.1' );

define( 'FM_GOOGLE_SITE_SEARCH_PATH', __DIR__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fm-google-site-search-activator.php
 */
function activate_fm_google_site_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fm-google-site-search-activator.php';
	Fm_Google_Site_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fm-google-site-search-deactivator.php
 */
function deactivate_fm_google_site_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fm-google-site-search-deactivator.php';
	Fm_Google_Site_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fm_google_site_search' );
register_deactivation_hook( __FILE__, 'deactivate_fm_google_site_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fm-google-site-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fm_google_site_search() {

	$plugin = new Fm_Google_Site_Search();
	$plugin->run();

}
run_fm_google_site_search();
