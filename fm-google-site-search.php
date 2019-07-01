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

// Avoid name collisions
if ( ! class_exists( 'GoogleCSE' ) ) {

  class GoogleCSE {

    private $textdomain;
    private $options;
    private $results_url;

    public function __construct()
    {
      $this->textdomain = 'fm-google-site-search';
      $this->options = get_option( 'google-options' );
      $this->results_url = 'google-search-results';
      add_shortcode( 'google_search', array( $this, 'shortcode' ) );
      add_action( 'admin_menu', array( $this, 'google_search_options_page' ) );
      add_action( 'admin_init', array( $this, 'google_settings' ) );
      add_action( 'admin_post_nopriv_google_search', array( $this, 'search_site' ) );
      add_action( 'admin_post_google_search', array( $this, 'search_site' ) );
      add_action( 'query_vars', array( $this, 'set_query_var' ) );
      add_action( 'init', array( $this, 'custom_add_rewrite_rule' ), 10 );
      add_action( 'init', array( $this, 'google_search_flush_rewrite_rules_maybe' ), 20 );
      add_filter('template_include', array( $this, 'google_results_page' ) );
      register_activation_hook( __FILE__, array( $this, 'flush_on_activate' ) );
    }

    public function search_site()
    {
      $next_page;
      $prev_page;
      $query = $_POST['google-search-input'];
      $base_url = 'https://www.googleapis.com/customsearch/v1';
      $fields = 'items(title,link,displayLink,snippet),queries,searchInformation(formattedSearchTime,formattedTotalResults)';
      global $wp_version;
      $args = array(
        'headers' => array(
          'Referer' => site_url(),
          'Accept-Encoding' => 'gzip',
          'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
        )
      );

      $request = $base_url .
        '?key=' . $this->options['api_key'] .
        '&cx=' . $this->options['search_id'] .
        '&q=' . $query .
        '&fields=' . $fields;

      $response = wp_remote_get( esc_url_raw( $request ), $args);

      if ( is_array( $response ) ) {// array of http header lines
        $api_response = json_decode( wp_remote_retrieve_body( $response ), true );

        // Request is always present in response
        // It is always an array with just one element
        $request_arr = $api_response['queries']['request'][0];

        // Next Page not present if the current results are the last page
        if ( !empty( $api_response['queries']['nextPage'] ) && is_array( $api_response['queries']['nextPage'] ) ) {
          $next_page = $api_response['queries']['nextPage'][0]['startIndex'];
        }

        // Previous Page not present if the current results are the first page
        if ( !empty( $api_response['queries']['previousPage'] ) && is_array( $api_response['queries']['previousPage'] ) ) {
          $prev_page = $api_response['queries']['previousPage'][0]['startIndex'];
        }

        $search_term = $request_arr['searchTerms'];
        $count = $request_arr['count'];
        $start_index = $request_arr['startIndex'];

        // Search Information
        $search_time = $api_response['searchInformation']['formattedSearchTime'];
        $total_results = $api_response['searchInformation']['formattedTotalResults'];

        // Search Result Items
        $items = $api_response['items'];

        require_once( __DIR__ . '/views/results_page.php' );
        $url = site_url() . '/' . $this->results_url;
        wp_redirect( $url );
        die;
      }

    }
  
    public function shortcode()
    {
      /*
      Do we have our api key and cx?
      If not don't display the form
      */
      if (
        ! $this->options['api_key'] ||
        ! $this->options['search_id']
        ) {
        return;
      }

      $form = '<form action="' .  esc_url( admin_url( "admin-post.php" ) ) . '" method="post">';
      $form .= '<input type="hidden" name="action" value="google_search" />';
      $form .= '<input name="google-search-input" placeholder="' . __( 'Search...', $this->textdomain ) . '" required type="text" />';
      $form .= '<button class="fm-google-search-submit" id="google-cse-submit">' . __( 'Search', $this->textdomain ) . '</button>';
      $form .= wp_nonce_field( 'fm_form_submit', 'fm_nonce', true, false );
      $form .= '</form>';

      return $form;
    }
  
    public function google_search_options_page()
    {
      // This page will be under "Settings"
      add_options_page(
        __( 'Google Search Admin', $this->textdomain ), 
        __( 'Google Search Settings', $this->textdomain ), 
        'manage_options', 
        'google-search-settings', 
        array( $this, 'create_admin_page' )
      );
    }
  
    public function create_admin_page()
    { 
      ?>
        <div class="wrap">
          <h1><?php echo esc_html__( 'Google Search Settings', $this->textdomain ) ?></h1>
          <form method="post" action="options.php">
          <?php
            // This prints out all hidden setting fields
            settings_fields( 'google_option_group' );
            do_settings_sections( 'google-search-settings' );
            submit_button();
          ?>
          </form>
        </div>
      <?php
    }
  
    public function google_settings()
    {
      register_setting(
        'google_option_group', // Option group
        'google-options', // Option name
        array( $this, 'sanitize' ) // Sanitize
      );
  
      add_settings_section(
        'google_search_section_id', // ID
        __( 'API Key &amp; Search Engine ID', $this->textdomain ), // Title
        array( $this, 'print_section_info' ), // Callback
        'google-search-settings' // Page
      );  
  
      add_settings_field(
        'api_key', // ID
        __( 'API Key', $this->textdomain ), // Title
        array( $this, 'api_key_callback' ), // Callback
        'google-search-settings', // Page
        'google_search_section_id' // Section
      );      
  
      add_settings_field(
        'search_id',
        __( 'Search Engine ID', $this->textdomain ),
        array( $this, 'search_id_callback' ),
        'google-search-settings',
        'google_search_section_id'
      );      
    }
  
    private function sanitize( $input )
    {
      $new_input = array();
      if( isset( $input['api_key'] ) ) {
        $new_input['api_key'] = sanitize_text_field( $input['api_key'] );
      }
  
      if( isset( $input['search_id'] ) ) {
        $new_input['search_id'] = sanitize_text_field( $input['search_id'] );
      }
  
      return $new_input;
    }
  
    private function print_section_info()
    {
      $google_cse_url = 'https://cse.google.com/cse/';
      $google_api_url = 'https://developers.google.com/custom-search/v1/overview';
  
      echo '<p>' . esc_html__( 'Create Search Engine ID: ', $this->textdomain ) . '<a href="' . esc_url( $google_cse_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( $google_cse_url, $this->textdomain ) . '</a></p>';
      echo '<p>' . esc_html__( 'Create Google Custom Search v1 api key ', $this->textdomain ) . '<a href="' . esc_url( $google_api_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( $google_api_url, $this->textdomain ) . '</a></p>';
    }
  
    private function api_key_callback()
    {
      printf(
        '<input type="text" id="api_key" name="google-options[api_key]" value="%s" />',
        isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
      );
    }
  
    private function search_id_callback()
    {
      printf(
        '<input type="text" id="search_id" name="google-options[search_id]" value="%s" />',
        isset( $this->options['search_id'] ) ? esc_attr( $this->options['search_id'] ) : ''
      );
    }

    public function set_query_var( $vars )
    {
      $vars[] = 'results_page'; // ref url redirected to in add rewrite rule
      return $vars;
    }

    public function custom_add_rewrite_rule()
    {
      add_rewrite_rule('^' . $this->results_url . '$','index.php?results_page=1','top');
      flush_rewrite_rules();
    }
    
    public function google_results_page( $template )
    {
      if( get_query_var( 'results_page' ) ){
        $template = WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)) ."/views/results_page.php";
      }
      return $template;
    }

    public function flush_on_activate()
    {
      if ( ! get_option( 'google_search_flush_rewrite_rules_flag' ) ) {
        add_option( 'google_search_flush_rewrite_rules_flag', true );
      }
    }

    public function google_search_flush_rewrite_rules_maybe()
    {
      if ( get_option( 'google_search_flush_rewrite_rules_flag' ) ) {
        flush_rewrite_rules();
        delete_option( 'google_search_flush_rewrite_rules_flag' );
      }
    }

  // End Class
  };

  // Instantiate Class
  new GoogleCSE();

  // End If Class Exists
}
