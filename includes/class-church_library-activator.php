<?php

/**
 * Fired during plugin activation
 *
 * @link       https://daworks.io
 * @since      1.0.0
 *
 * @package    Church_library
 * @subpackage Church_library/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Church_library
 * @subpackage Church_library/includes
 * @author     디자인아레테 <cs@daworks.org>
 */
class Church_library_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		global $wpdb;
		
		$wpdb->show_errors();
		$table_name = $wpdb->prefix . 'book_info';
		$db_version = '1.0';
		
		if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
					  id int(11) unsigned NOT NULL AUTO_INCREMENT,
					  title varchar(255) DEFAULT NULL,
					  writer varchar(255) DEFAULT NULL,
					  count varchar(255) DEFAULT NULL,
					  publisher varchar(255) DEFAULT NULL,
					  published_year varchar(255) DEFAULT NULL,
					  classified_code varchar(255) DEFAULT NULL,
					  book_code varchar(255) DEFAULT NULL,
					  isbn varchar(255) DEFAULT NULL,
					  created_at datetime DEFAULT '0000-00-00 00:00:00' NULL,
            PRIMARY KEY (id)
					) $charset_collate;";
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			add_option('daworks_church_library_db_version', $db_version );
		}
	}

}
