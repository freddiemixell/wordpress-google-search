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

$textdomain = 'fm-google-site-search';

$config = array(
  'api_key' => 'AIzaSyDUb8Sq1NhQPxQCstoG_bzsuEVYah0BMDg',
  'cx'      => '003422635988353438707:zn9yenb4_xq'
);

class GoogleCSE {
  protected $api_key;
  protected $cx;

  public function __construct( $config )
  {
    $this->api_key = $config['api_key'];
    $this->cx = $config['cx'];
    add_shortcode('google_search', array($this, 'shortcode'));
    add_action('admin_menu', array($this, 'google_search_admin'));
  }

  public function shortcode()
  {
    return "This is working";
  }

  public function google_search_admin()
  {
    add_menu_page(
      __( 'Google CSE', $textdomain ),
      'Google CSE',
      'manage_options',
      'fm-google-site-search',
      array($this, 'render_admin_page')
    );
  }

  public function render_admin_page()
  {
    esc_html_e( 'Admin Page Test', $textdomain );
  }
};

new GoogleCSE( $config );
