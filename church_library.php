<?php

/**
 * Plugin Name:       도서관리
 * Plugin URI:        https://daworks.io
 * Description:       도서관리 플러그인
 * Version:           1.0.0
 * Author:            디자인아레테
 * Author URI:        https://daworks.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       church_library
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-church_library-activator.php
 */
function activate_church_library() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-church_library-activator.php';
	Church_library_Activator::activate();
	
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-church_library-deactivator.php
 */
function deactivate_church_library() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-church_library-deactivator.php';
	Church_library_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_church_library' );
register_deactivation_hook( __FILE__, 'deactivate_church_library' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-church_library.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_church_library() {

	$plugin = new Church_library();
	$plugin->run();

}
run_church_library();

require plugin_dir_path(__FILE__) . 'includes/library.php';