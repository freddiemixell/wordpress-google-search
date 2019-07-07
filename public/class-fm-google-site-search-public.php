<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/freddiemixell
 * @since      1.0.0
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Fm_Google_Site_Search
 * @subpackage Fm_Google_Site_Search/public
 * @author     Freddie Mixell <fmixell@gmail.com>
 */
class Fm_Google_Site_Search_Public {

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

  public $search_site;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $textdomain, $options ) {

		$this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->textdomain = $textdomain;
    $this->options = $options;
    add_shortcode( 'google_search', array( $this, 'search_box' ) );
    add_shortcode( 'google_search_results', array( $this, 'search_results' ) );

  }

  public function redirect_search() {
    status_header(200);

    $search_input = $_POST['search-input'];

    wp_redirect( '/search-results?query=' . $search_input );
    die;
  }

  public function search_results() {

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

    require_once( FM_GOOGLE_SITE_SEARCH_PATH . '/public/partials/fm-google-site-search-public-results.php');
  }
  
  public function search_box() {
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

    $data = array(
      'textdomain' => $this->textdomain
    );

    require_once( FM_GOOGLE_SITE_SEARCH_PATH . '/public/partials/fm-google-site-search-public-display.php' );

  }

  public function register_search() {
    register_rest_route( $this->plugin_name . '/v1', 'search_site',
      ['methods' => ['GET'], 'callback' => array($this, 'search_site')]
    );
  }

  public function search_site(WP_REST_Request $request) {
    $query = $request['query'];
    $start = isset( $request['start'] ) ? $request['start'] : null;
    $base_url = 'https://www.googleapis.com/customsearch/v1';
    $fields = 'items(title,link,snippet),queries,searchInformation(formattedSearchTime,formattedTotalResults)';
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

    if ($start !== null) {
      $request = $request . '&start=' . $start;
    }

    $response = wp_remote_get( esc_url_raw( $request ), $args);

    return json_decode( wp_remote_retrieve_body( $response ), true );

  }

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fm-google-site-search-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script(
      $this->plugin_name,
      plugin_dir_url( __FILE__ ) . 'dist/bundle.js',
      ['wp-element', 'wp-components'],
      time(),
      true
    );
	}

}
