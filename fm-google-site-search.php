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

class GoogleCSE {

  private $textdomain;

  public function __construct()
  {
    $this->textdomain = 'fm-google-site-search';
    add_shortcode('google_search', array($this, 'shortcode'));
    add_action('admin_menu', array($this, 'google_search_options_page'));
    add_action( 'admin_init', array($this, 'google_settings') );
    add_action( 'admin_post_nopriv_google_search', array($this, 'search_site') );
    add_action( 'admin_post_google_search', array($this, 'search_site') );
  }

  public function search_site()
  {
    status_header(200);
    die("Server received '{$_POST['google-search-input']}' from your browser.");
  }

  public function shortcode()
  {
    $nonce_field = wp_nonce_field( 'fm_form_submit', 'fm_nonce', true, false );

    $form = '<form action="' .  esc_url( admin_url( "admin-post.php" ) ) . '" method="post">';
    $form .= '<input type="hidden" name="action" value="google_search" />';
    $form .= '<input name="google-search-input" placeholder="' . __( 'Search...', $this->textdomain ) . '" required type="text" />';
    $form .= '<button class="fm-google-search-submit" id="google-cse-submit">' . __( 'Search', $this->textdomain ) . '</button>';
    $form .= $nonce_field;
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
    // Set class property
    $this->options = get_option( 'google-options' );
    ?>
    <div class="wrap">
        <h1><?php echo __( 'Google Search Settings', $this->textdomain ) ?></h1>
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

  public function sanatize( $input )
  {
    $new_input = array();
        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = sanitize_text_field( $input['api_key'] );

        if( isset( $input['search_id'] ) )
            $new_input['search_id'] = sanitize_text_field( $input['search_id'] );

        return $new_input;
  }

  public function print_section_info()
  {
    $google_cse_url = 'https://cse.google.com/cse/';
    $google_api_url = 'https://code.google.com/apis/console/';

    echo '<p>' . esc_html__('Create Search Engine ID: ', $this->textdomain ) . '<a href="' . esc_url( $google_cse_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( $google_cse_url, $this->textdomain ) . '</a></p>';
    echo '<p>' . esc_html__('Create Google Custom Search v1 api key ', $this->textdomain ) . '<a href="' . esc_url( $google_api_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( $google_api_url, $this->textdomain ) . '</a></p>';
  }

  public function api_key_callback()
  {
    printf(
      '<input type="text" id="api_key" name="google-options[api_key]" value="%s" />',
      isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
    );
  }

  public function search_id_callback()
  {
    printf(
      '<input type="text" id="search_id" name="google-options[search_id]" value="%s" />',
      isset( $this->options['search_id'] ) ? esc_attr( $this->options['search_id']) : ''
    );
  }

// End Class
};

new GoogleCSE();
