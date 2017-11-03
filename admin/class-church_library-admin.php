<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://daworks.io
 * @since      1.0.0
 *
 * @package    Church_library
 * @subpackage Church_library/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Church_library
 * @subpackage Church_library/admin
 * @author     디자인아레테 <cs@daworks.org>
 */
class Church_library_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/church_library-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/church_library-admin.js', array('jquery'), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'bootstrap/js/bootstrap.min.js', array(), $this->version, false);

	}
	
	public function add_plugin_admin_menu() {
		add_menu_page(
			'도서관리',
			'도서관리',
			'manage_options',
			'church-library',
			array($this, 'show_index_page'),
			'',
			6
		);
		add_submenu_page(
			'church-library',
			'도서 추가',
			'도서 추가',
			'manage_options',
			'church-library-create',
			array($this, 'show_create_page')
		);
	}
	
	public function show_index_page() {
		require plugin_dir_path(__FILE__) . 'partials/church_library-admin-display.php';
	}
	public function show_create_page() {
		require plugin_dir_path(__FILE__) . 'partials/church_library-admin-create.php';
	}

}
