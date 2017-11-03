<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://daworks.io
 * @since      1.0.0
 *
 * @package    Church_library
 * @subpackage Church_library/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Church_library
 * @subpackage Church_library/public
 * @author     디자인아레테 <cs@daworks.org>
 */
class Church_library_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/church_library-public.css', array(), $this->version, 'all' );

	}
	
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/church_library-public.js', array( 'jquery' ), $this->version, false );
	}
	
	
	public function church_library_shortcode_init()
	{
		
		function church_library_shortcode($atts = [], $content = null)
		{
			ob_start();
			$content = require plugin_dir_path(__FILE__) . 'partials/church_library-public-display.php';
			return ob_get_clean ();
			wp_die();
		}
		add_shortcode('daworks_church_library', 'church_library_shortcode');
		
		function church_library_latest_shortcode($atts=[], $content = null, $tag = '' )
		{
			ob_start();
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
			$daworks_atts = shortcode_atts ( ['nums' => 5], $atts, $tag);
			
			require plugin_dir_path(__FILE__) . 'partials/latest_list.php';
			return ob_get_clean();
			wp_die();
		}
		add_shortcode('daworks_church_library_latest', 'church_library_latest_shortcode');
	}
	
	
}
