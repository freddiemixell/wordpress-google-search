<?php
/*
Plugin Name: Google Site Search
Description: Search your site using Google's Algorithm. Pipe that data directly into Google Analytics.
Text Domain: fm-google-site-search
Author: Freddie Mixell
Author URI: https://github.com/freddiemixell
Version: 0.0.1
*/

/*
Prevent Direct Access
Description: If WordPress Accesses this wp-config.php will load
In that case 'ABSPATH' will be defined.
If accessed directly wp-config.php isn't loaded so we exit.
*/
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

$config = array(
  'api_key'    => 'AIzaSyDUb8Sq1NhQPxQCstoG_bzsuEVYah0BMDg',
  'cx'         => '003422635988353438707:zn9yenb4_xq',
  'textdomain' => 'fm-google-site-search',
);

class GoogleCSE {

  public function __construct( $config )
  {
    $this->api_key = $config['api_key'];
    $this->cx = $config['cx'];
    $this->textdomain = $config['textdomain'];
    add_shortcode('google_search', array($this, 'shortcode'));
    add_action('admin_menu', array($this, 'google_search_admin'));
    register_activation_hook( __FILE__, array($this, 'activate_google_search'));
    register_deactivation_hook( __FILE__, array($this, 'deactivate_google_search'));
  }

  public function activate_google_search()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'fm_google_site_search';
  
    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      api_key varchar(50) NOT NULL DEFAULT '',
      search_id varchar(50) NOT NULL DEFAULT '',
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }

  public function deactivate_google_search()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fm_google_site_search';
    $wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $table_name ) );
  }

  public function shortcode()
  {
    return "This is working";
  }

  public function google_search_admin()
  {
    add_menu_page(
      __( 'Google CSE', $this->textdomain ),
      'Google CSE',
      'manage_options',
      'fm-google-site-search',
      array($this, 'render_admin_page')
    );
  }

  public function render_admin_page()
  {
    esc_html_e( 'Admin Page Test', $this->textdomain );
  }
};

new GoogleCSE( $config );
