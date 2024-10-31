<?php

/**
 * Fired during plugin activation
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/includes
 * @author     Adam Chernenkoff <adam@nautlusdiveadventures.com>
 */
class Nautilus_Trips_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$saved_page_args = array(
			'post_title'   => __( 'Nautilus Trips', 'nautilus-trips' ),
			'post_content' => '[nautilus-trips]',
			'post_status'  => 'publish',
			'post_type'    => 'page'
		);
		// Insert the page and get its id.
		$saved_page_id = wp_insert_post( $saved_page_args );
		// Save page id to the database.
		add_option( 'nautilus_trips_page_id', $saved_page_id );
	}

	public static function create_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'nautilus_trips_booking';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			rqId INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			bookFk INT(11) UNSIGNED NOT NULL DEFAULT 0,
			tripFk INT(11) UNSIGNED NOT NULL DEFAULT 0,
			shipFk SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
			tripClassFk SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
			retailPrice DECIMAL(10,2) NOT NULL DEFAULT 0.00,
			boarddate DATE NOT NULL DEFAULT '0000-00-00',
			requestDate DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			api_errcode SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
			api_error VARCHAR(132) NULL,
			confirmation VARCHAR(32) NULL,
			firstname VARCHAR(100) NULL,
			lastname VARCHAR(100) NULL,
			email VARCHAR(100) NULL,
			phone VARCHAR(100) NULL,
			booked VARCHAR(255) NULL,
			comments TEXT NULL,
			conf_html TEXT NULL,
			PRIMARY KEY  (rqId)
		) Engine=InnoDB $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
