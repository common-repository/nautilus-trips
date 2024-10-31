<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/admin
 * @author     Adam Chernenkoff <adam@nautilusdiveadventures.com>
 */
class Nautilus_Trips_Admin {

	private static $initiated = false;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $nautilus_trips    The ID of this plugin.
	 */
	private $nautilus_trips;

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
	 * @param      string    $nautilus_trips       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $nautilus_trips, $version ) {

		$this->nautilus_trips = $nautilus_trips;
		$this->version = $version;

		$this->init();
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
		 * defined in Nautilus_Trips_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nautilus_Trips_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->nautilus_trips, plugin_dir_url( __FILE__ ) . 'css/nautilus-trips-admin.css', array(), $this->version, 'all' );

	}

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks() {

		self::$initiated = true;

		add_action( 'admin_init', array( 'Nautilus_Trips_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Nautilus_Trips_Admin', 'admin_menu' ) );

		add_filter( 'plugin_action_links_'.plugin_basename( NAUTILUS_TRIPS_PLUGIN_DIR . 'nautilus-trips.php'), array( 'Nautilus_Trips_Admin', 'admin_plugin_settings_link' ) );
	}

	//public static function plugin_action_links( $links, $file ) {
	//	if ( $file == plugin_basename( NAUTILUS_TRIPS_PLUGIN_DIR . '/nautilus-trips.php' ) ) {
	//		$links[] = '<a href="' . esc_url( self::get_page_url() ) . '">'.esc_html__( 'Settings' , 'nautilus-trips').'</a>';
	//	}

	//	return $links;
	//}


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
		 * defined in Nautilus_Trips_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nautilus_Trips_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->nautilus_trips, plugin_dir_url( __FILE__ ) . 'js/nautilus-trips-admin.js', array( 'jquery' ), $this->version, false );

	}

	public static function admin_plugin_settings_link( $links ) {
  		$settings_link = '<a href="'.esc_url( self::get_page_url() ).'">'.__('Settings', 'nautilus-trips-config').'</a>';
  		array_unshift( $links, $settings_link );
  		return $links;
	}

	public static function load_menu() {

		$hook = add_menu_page( __('Nautilus Trips', 'nautilus-trips'), __('Nautilus Trips', 'nautilus-trips'), 'manage_options', 'nautilus-trips-config', array( 'Nautilus_Trips_Admin', 'display_page' ) );

		//if ( $hook ) {
		//	add_action( "load-$hook", array( 'Nautilus_Trips_Admin', 'admin_help' ) );
		//}
	}

	public static function display_page() {

		$message = NULL;
		$nautilus_trips_api_key = '';
		$nautilus_trips_admin_email = '';
		$nautilus_trips_ships = array();
		$nautilus_trips_years = array();
		$nautilus_trips_months = array();
		$nautilus_trips_destinations = array();

		// Retrieve data options for user to choose from
		$nautilus_ships = Nautilus_Trips::get_nautilus_trips_ships();
		$nautilus_years = Nautilus_Trips::get_nautilus_trips_years();
		$nautilus_months = Nautilus_Trips::get_nautilus_trips_months();
		$nautilus_destinations = Nautilus_Trips::get_nautilus_trips_destinations();

		if (!empty($_POST['save-nautilus-trips-settings'])){
			// Defaults / NULL
			$json_ships = $json_years = $json_months = $json_destinations = '';

			// Check for POST and SAVE data to options
			if ( ! empty( $_POST['nautilus_trips_api_key'] ) ) {
				$nautilus_trips_api_key = sanitize_text_field( $_POST['nautilus_trips_api_key'] );
				update_option('nautilus_trips_api_key', $nautilus_trips_api_key);
			}
			if ( ! empty( $_POST['nautilus_trips_admin_email'] ) ) {
				$nautilus_trips_admin_email = sanitize_text_field( $_POST['nautilus_trips_admin_email'] );
				update_option('nautilus_trips_admin_email', $nautilus_trips_admin_email);
			}

			// Check our allowed values to see if they are checked, and then save
			if ($nautilus_ships != NULL && count($nautilus_ships) > 0) {
				$nautilus_trips_ships_save = array();
				foreach (array_keys($nautilus_ships) as $shipId) {
					if (isset($_POST['nautilus_trips_ships_' . $shipId]) && absint($_POST['nautilus_trips_ships_' . $shipId]) == 1) {
						$nautilus_trips_ships_save[] = absint($shipId);
					}
				}
				$json_ships = json_encode($nautilus_trips_ships_save);
			}

			if ($nautilus_years != NULL && count($nautilus_years) > 0) {
				$nautilus_trips_years_save = array();
				foreach (array_keys($nautilus_years) as $year) {
					if (isset($_POST['nautilus_trips_years_' . $year]) && absint($_POST['nautilus_trips_years_' . $year]) == 1) {
						$nautilus_trips_years_save[] = absint($year);
					}
				}
				$json_years = json_encode($nautilus_trips_years_save);
			}

			if ($nautilus_months != NULL && count($nautilus_months) > 0) {
				$nautilus_trips_months_save = array();
				foreach (array_keys($nautilus_months) as $month) {
					if (isset($_POST['nautilus_trips_months_' . $month]) && absint($_POST['nautilus_trips_months_' . $month]) == 1) {
						$nautilus_trips_months_save[] = sanitize_text_field($month);
					}
				}
				$json_months = json_encode($nautilus_trips_months_save);
			}

			if ($nautilus_destinations != NULL && count($nautilus_destinations) > 0) {
				$nautilus_trips_destinations_save = array();
				foreach (array_keys($nautilus_destinations) as $destId) {
					if (isset($_POST['nautilus_trips_destinations_' . $destId]) && absint($_POST['nautilus_trips_destinations_' . $destId]) == 1) {
						$nautilus_trips_destinations_save[] = absint($destId);
					}
				}
				$json_destinations = json_encode($nautilus_trips_destinations_save);
			}

			update_option('nautilus_trips_ships', $json_ships);
			update_option('nautilus_trips_years', $json_years);
			update_option('nautilus_trips_months', $json_months);
			update_option('nautilus_trips_destinations', $json_destinations);

			$message = 'Settings Saved';
		}

		// Retrieve Saved Settings from WP DB for the admin settings page
		$nautilus_trips_page_id = get_option('nautilus_trips_page_id');
		$nautilus_trips_api_key = get_option('nautilus_trips_api_key');
		$nautilus_trips_admin_email = get_option('nautilus_trips_admin_email');

		$json_ships = get_option('nautilus_trips_ships');
		$nautilus_trips_ships = ($json_ships != '' ? json_decode($json_ships) : array());

		$json_years = get_option('nautilus_trips_years');
		$nautilus_trips_years = ($json_years != '' ? json_decode($json_years) : array());

		$json_months = get_option('nautilus_trips_months');
		$nautilus_trips_months = ($json_months != '' ? json_decode($json_months) : array());

		$json_destinations = get_option('nautilus_trips_destinations');
		$nautilus_trips_destinations = ($json_destinations != '' ? json_decode($json_destinations) : array());


		// Coming soon: List Bookings created through the plugin in the Wordpress Admin.
		//$bookings = Nautilus_Trips::get_bookings();

		// Load the Admin Settings Page
		include('partials/nautilus-trips-admin-display.php');
	}

	public static function admin_menu() {
		self::load_menu();
	}

	public static function admin_head() {
		if ( !current_user_can( 'manage_options' ) )
			return;
	}

	public static function admin_init() {
		load_plugin_textdomain( 'nautilus-trips' );
		//add_meta_box( 'nautilus-trips-status', __('Comment History', 'nautilus-trips'), array( 'Nautilus_Trips_Admin', 'comment_status_meta_box' ), 'comment', 'normal' );

		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			wp_add_privacy_policy_content(
				__( 'Nautilus Trips', 'nautilus-trips' ),
				__( 'We collect name, email address, and phone number of people that book through the Nautilus Trips plugin to create their reservation in our Reservation System. This information is required for anyone that will board a trip on one of our vessels.', 'nautilus-trips' )
			);
		}
	}

	public static function get_page_url( $page = 'config' ) {

		$args = array( 'page' => 'nautilus-trips-config' );

		$url = add_query_arg( $args, admin_url( 'admin.php' ) );

		return $url;
	}

}
