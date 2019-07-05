<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/freddiemixell
 * @since      1.0.0
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/admin
 * @author     Freddie Mixell <fmixell@gmail.com>
 */
class Fm_Google_Site_Search_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
  private $version;
  
  private $textdomain;

  private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $textdomain, $options ) {

		$this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->textdomain = $textdomain;
    $this->options = $options;

  }

  public function google_search_options_page() {
    // This page will be under "Settings"
    add_options_page(
      __( 'Google Search Admin', $this->textdomain ), 
      __( 'Google Search Settings', $this->textdomain ), 
      'manage_options', 
      'google-search-settings', 
      array( $this, 'create_admin_page' )
    );
  }

  public function create_admin_page() { 
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

  public function google_settings() {
    register_setting(
      'google_option_group', // Option group
      'google-options',
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
  
  private function sanitize( $input ) {
    $new_input = array();
    if( isset( $input['api_key'] ) ) {
      $new_input['api_key'] = sanitize_text_field( $input['api_key'] );
    }

    if( isset( $input['search_id'] ) ) {
      $new_input['search_id'] = sanitize_text_field( $input['search_id'] );
    }

    return $new_input;
  }

  public function print_section_info() {
    $google_cse_url = 'https://cse.google.com/cse/';
    $google_api_url = 'https://developers.google.com/custom-search/v1/overview';

    echo '<p>' . esc_html__( 'Create Search Engine ID: ', $this->textdomain ) . '<a href="' . esc_url( $google_cse_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( $google_cse_url, $this->textdomain ) . '</a></p>';
    echo '<p>' . esc_html__( 'Create Google Custom Search v1 api key ', $this->textdomain ) . '<a href="' . esc_url( $google_api_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( $google_api_url, $this->textdomain ) . '</a></p>';
  }

  public function api_key_callback() {
    printf(
      '<input type="text" id="api_key" name="google-options[api_key]" value="%s" />',
      isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
    );
  }

  public function search_id_callback() {
    printf(
      '<input type="text" id="search_id" name="google-options[search_id]" value="%s" />',
      isset( $this->options['search_id'] ) ? esc_attr( $this->options['search_id'] ) : ''
    );
  }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fm_Google_Site_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fm_Google_Site_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fm-google-site-search-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fm_Google_Site_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fm_Google_Site_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fm-google-site-search-admin.js', array( 'jquery' ), $this->version, false );

	}

}
