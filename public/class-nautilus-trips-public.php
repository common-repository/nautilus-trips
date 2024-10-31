<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/public
 * @author     Adam Chernenkoff <adam@nautilusdiveadventures.com>
 */
class Nautilus_Trips_Public {

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
	 * @param      string    $nautilus_trips       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $nautilus_trips, $version ) {

		$this->nautilus_trips = $nautilus_trips;
		$this->version = $version;

	}

	public static function nautilus_trips_do_page($atts=NULL) {
		global $wpdb;

		ob_start();

		$shortcode_dest = NULL;
		if ($atts != NULL && is_array($atts)) {
			$shortcode_dest = (isset($atts['dest']) ? $atts['dest'] : NULL);
		}
		// Check that we have API key and dealer email: we need these to book.
		$api_key = get_option('nautilus_trips_api_key');
		$page_id = get_option('nautilus_trips_page_id');
		$admin_email = get_option('nautilus_trips_admin_email');

		// If we don't have these set, let the administrator know so they can fix it.
		if ($api_key == NULL || $admin_email == NULL) {

			include('partials/nautilus-trips-setup.php');

		} else {

			$saved = false;
			$errs = array();
			$response = NULL;

			$tripId = get_query_var('tripId', NULL);
			if ($tripId != NULL)
				$tripId = absint($tripId);
			$my_nonce = (isset($_POST['_wpnonce']) ? sanitize_text_field($_POST['_wpnonce']) : NULL);
			$nonce_check = wp_verify_nonce( $my_nonce, 'book-trip_' . $tripId );


			$Trip = new Nautilus_Trips;

			// Retrieve and validate the data we need to book
			$firstName = (!empty($_POST['firstName']) ? sanitize_text_field( $_POST['firstName'] ) : '');
			$lastName = (!empty($_POST['lastName']) ? sanitize_text_field( $_POST['lastName'] ) : '');
			$email = (!empty($_POST['email']) ? sanitize_text_field( $_POST['email'] ) : '');
			$phone = (!empty($_POST['phone']) ? sanitize_text_field( $_POST['phone'] ) : '');
			$comments = (!empty($_POST['comments']) ? sanitize_text_field( $_POST['comments'] ) : '');
			$bookTotalCost = (!empty($_POST['bookTotalCost']) ? absint( $_POST['bookTotalCost'] ) : 0);

			$premiumF = (!empty($_POST['nt-rt-premium-f']) ? absint( $_POST['nt-rt-premium-f'] ) : 0);
			$premiumM = (!empty($_POST['nt-rt-premium-m']) ? absint( $_POST['nt-rt-premium-m'] ) : 0);
			$superiorF = (!empty($_POST['nt-rt-superior-f']) ? absint( $_POST['nt-rt-superior-f'] ) : 0);
			$superiorM = (!empty($_POST['nt-rt-superior-m']) ? absint( $_POST['nt-rt-superior-m'] ) : 0);
			$stateroomF = (!empty($_POST['nt-rt-stateroom-f']) ? absint( $_POST['nt-rt-stateroom-f'] ) : 0);
			$stateroomM = (!empty($_POST['nt-rt-stateroom-m']) ? absint( $_POST['nt-rt-stateroom-m'] ) : 0);
			$singleF = (!empty($_POST['nt-rt-single-f']) ? absint( $_POST['nt-rt-single-f'] ) : 0);
			$singleM = (!empty($_POST['nt-rt-single-m']) ? absint( $_POST['nt-rt-single-m'] ) : 0);
			$tripleF = (!empty($_POST['nt-rt-triple-f']) ? absint( $_POST['nt-rt-triple-f'] ) : 0);
			$tripleM = (!empty($_POST['nt-rt-triple-m']) ? absint( $_POST['nt-rt-triple-m'] ) : 0);

			// Check for a submitted Booking Request and validate data
			if (isset($_POST['ntBookTrip'])) {

				$totalBooked = ($premiumF + $premiumM + $superiorF + $superiorM + $stateroomF + $stateroomM + $singleF + $singleM + $tripleF + $tripleM);

				// Perform data validation here and catch any problems before submitting
				if (!$nonce_check) {

					$errs[] = 'There was an error submitting the form. Please try again.';
				} else {
					if ($firstName == NULL)
						$errs[] = 'Please provide your First Name';
					if ($firstName == NULL)
						$errs[] = 'Please provide your Last Name';
					if (!is_email($email))
						$errs[] = 'Please provide a valid Email Address';
					if ($phone == NULL)
						$errs[] = 'Please provide your Phone Number';
					if ($totalBooked == 0)
						$errs[] = 'You must specify at least one berth to book';
				}

				if (count($errs) == 0) {
					// Save request to DB then submit to CURL and get the API response
					$response = $Trip->nautilus_trips_create_booking( $tripId );
					if (isset($response->status) && ($response->status == true || $response->status == 1)) {
						$saved = true;

						// Generate a "thanks" email and send it to the user
						$email_html = '';
						ob_start();
						include('partials/nautilus-trips-email.php');
						$email_html = ob_get_contents();
						ob_end_clean();

						$email_subject = 'New Liveaboard Reservation from ' . bloginfo('name');

						$sent = wp_mail( implode(',', array($admin_email, $email)), $email_subject, $email_html );
					}
				}
			}

			if ($saved) {
				// Display the Confirmation HTML content to the user, give them the dealers contact info to follow-up (email)
				// Allow user to print their conf HTML. - soon: TBD in an upcoming version

				// Build the thanks page and display to the user.
				include('partials/nautilus-trips-booked.php');

			} elseif ($tripId == NULL) {

				$descriptions = $Trip->get_destination_description();
				if (!array_key_exists($shortcode_dest, $descriptions))
					$shortcode_dest = NULL;
				$tripDataJson = $Trip->nautilus_trips_api_get_trips(NULL, $shortcode_dest);

				if ($tripDataJson != NULL) {
					$tripData = json_decode( $tripDataJson );
					include('partials/nautilus-trips-public-display.php');
				}

			} elseif ($tripId != NULL) {

				$tripJson = $Trip->nautilus_trips_api_get_trips( $tripId );

				if ($tripJson != NULL) {

					$tripDetails = json_decode( $tripJson );
					include('partials/nautilus-trips-trip.php');
				}
			}
		}
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function loads public facing css required for the plugin
		 */

		wp_enqueue_style( $this->nautilus_trips, plugin_dir_url( __FILE__ ) . 'css/nautilus-trips-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function loads JS for front side operation of the trips page
		 */

		wp_enqueue_script( $this->nautilus_trips, plugin_dir_url( __FILE__ ) . 'js/nautilus-trips-public.js', array( 'jquery' ), $this->version, false );

	}

}
