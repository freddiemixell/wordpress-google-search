<?php
/*
Plugin Name: Google Site Search
Description: Search your site using Google Algoritm. Pipe that data directly into Google Analytics.
Author: Freddie Mixell
Author URI: https://github.com/freddiemixell
Version: 0.0.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

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
  }

  public function shortcode()
  {
    return "This is working";
  }
};

new GoogleCSE( $config );
