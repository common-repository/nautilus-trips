<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/includes
 * @author     Adam Chernenkoff <adam@nautilusdiveadventures.com>
 */
class Nautilus_Trips_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Get Saved page id.
		$saved_page_id = get_option( 'nautilus_trips_page_id' );

		// Check if the saved page id exists.
		if ( $saved_page_id ) {
			// Delete saved page.
			wp_delete_post( $saved_page_id, true );

			// Delete saved page id record in the database.
			delete_option( 'nautilus_trips_page_id' );
			delete_option( 'nautilus_trips_api_key' );
			delete_option( 'nautilus_trips_admin_email' );
			delete_option( 'nautilus_trips_ships' );
			delete_option( 'nautilus_trips_years' );
			delete_option( 'nautilus_trips_months' );
			delete_option( 'nautilus_trips_destinations' );
		}
	}

	public static function delete_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'nautilus_trips_booking';
	    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

	    //delete_option("my_plugin_db_version");
	}

}
