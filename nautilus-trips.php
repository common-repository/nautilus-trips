<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://nautilusdealers.com
 * @since             1.0.0
 * @package           Nautilus_Trips
 *
 * @wordpress-plugin
 * Plugin Name:       Nautilus Trips
 * Plugin URI:        https://nautilusdealers.com/marketing/nautilus_trips_plugin/
 * Description:       This plugin allows Nautilus Dealers to readily display available trips on their website for customers to book trips.
 * Version:           1.0.9
 * Author:            Nautilus Liveaboards
 * Author URI:        https://book.nautilusdive.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nautilus-trips
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'NAUTILUS_TRIPS_VERSION', '1.0.9' );
define( 'NAUTILUS_TRIPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nautilus-trips-activator.php
 */
function activate_nautilus_trips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nautilus-trips-activator.php';
	Nautilus_Trips_Activator::activate();
	Nautilus_Trips_Activator::create_table();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nautilus-trips-deactivator.php
 */
function deactivate_nautilus_trips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nautilus-trips-deactivator.php';
	Nautilus_Trips_Deactivator::deactivate();
	Nautilus_Trips_Deactivator::delete_table();
}

register_activation_hook( __FILE__, 'activate_nautilus_trips' );
register_deactivation_hook( __FILE__, 'deactivate_nautilus_trips' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nautilus-trips.php';


function nautilus_trips_set_html_mail_content_type() {
    return 'text/html';
}
add_filter( 'wp_mail_content_type', 'nautilus_trips_set_html_mail_content_type' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nautilus_trips() {
	$plugin = new Nautilus_Trips();
	$plugin->run();
}
run_nautilus_trips();

add_action( 'init', 'nautilus_trips_add_get_val' );
add_shortcode("nautilus-trips", "nautilus_trips_process_shortcode");

function nautilus_trips_add_get_val() {
	$plugin = new Nautilus_Trips();
	return $plugin->nautilus_trips_add_get_val();
	//return Nautilus_Trips::nautilus_trips_add_get_val();
}

function nautilus_trips_process_shortcode($atts=[], $content=NULL, $tag=NULL){
    return Nautilus_Trips_Public::nautilus_trips_do_page($atts);
}
