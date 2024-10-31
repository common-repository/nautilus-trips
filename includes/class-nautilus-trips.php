<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/includes
 * @author     Adam Chernenkoff <adam@nautilusdiveadventures.com>
 */
class Nautilus_Trips {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nautilus_Trips_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	protected static $_api_trips_url = 'https://book.nautilusdive.com/api/gettrips';
	protected static $_api_trip_url = 'https://book.nautilusdive.com/api/gettrip';
	protected static $_api_book_url = 'https://book.nautilusdive.com/api/booking';

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $nautilus_trips    The string used to uniquely identify this plugin.
	 */
	protected $nautilus_trips;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'NAUTILUS_TRIPS_VERSION' ) ) {
			$this->version = NAUTILUS_TRIPS_VERSION;
		//} else {
		//	$this->version = '1.0.1';
		}
		$this->nautilus_trips = 'nautilus-trips';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Nautilus_Trips_Loader. Orchestrates the hooks of the plugin.
	 * - Nautilus_Trips_i18n. Defines internationalization functionality.
	 * - Nautilus_Trips_Admin. Defines all hooks for the admin area.
	 * - Nautilus_Trips_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nautilus-trips-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nautilus-trips-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nautilus-trips-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nautilus-trips-public.php';

		$this->loader = new Nautilus_Trips_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nautilus_Trips_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Nautilus_Trips_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Nautilus_Trips_Admin( $this->get_nautilus_trips(), NAUTILUS_TRIPS_VERSION );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Nautilus_Trips_Public( $this->get_nautilus_trips(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}


	function nautilus_trips_add_get_val() {
		global $wp;
		$wp->add_query_var('tripId');
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	public static function get_nautilus_trips_destinations(){
		$dest = array(
            3 => 'Socorro Combo Expeditions',
//            4 => 'Guadalupe Great White Sharks',
            5 => 'Socorro Giant Mantas',
            12 => 'Sea of Cortez Get-Aways',
            22 => 'Magdalena Bay Marlin & Bait Balls Mexico Sardine Run',
            20 => 'San Ignacio Lagoon Gray Whales',
            21 => 'Espiritu Santo',
            27 => 'Orcas & Mobulas in the Sea of Cortez',
            28 => 'Loreto Blue Whales',
            29 => 'Baja Ultimate Whales',
            30 => 'Mothership Kayaking and Glamping',
            32 => 'Ultimate Whale Sharks in the Sea of Cortez',
            37 => 'Summer adventures and whale sharks',
            42 => 'Luxury Liveaboard Kayaking',
            47 => 'Loreto Great Circle / La Paz',
            23 => 'Cocos Island of the Sharks',
            25 => 'Ocean & Rainforest Adventures',
            52 => 'Swimming',
            57 => 'Special Expeditions',
		);
		return $dest;
	}

	public static function get_nautilus_trips_years(){
		$years = array();
		$start = date('Y');
		$end = date('Y') + 4;
		for ($i = $start; $i < $end; $i++)
			$years[$i] = $i;
		return $years;
	}

	public static function get_nautilus_trips_months(){
		$months = array(
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December'
		);
		return $months;
	}

	public static function get_nautilus_trips_ships(){
		$ships = array(
			1 => 'Nautilus Explorer',
			3 => 'Nautilus Belle Amie',
			4 => 'Nautilus UnderSea',
			5 => 'Nautilus Gallant Lady',
            6 => 'Nautilus Westerly',
            15 => 'Mango Wind',
            //7 => 'San Ignacio Lagoon Glamping',
        );
		return $ships;
	}

	public static function get_destination_description(){
		$descriptions = array(
			4 => 'Guadalupe Island provides some of the worlds best great white sharks diving.
			Beautiful weather topside, warm &amp; clear water and a really interesting mix of both male and female sharks at different times of the season. With 350 great white sharks identified so far and still counting! 125+ ft visibility. Shark wrangling platforms for lots of surface action. Up to 5 submersible and surface cages for virtually unlimited diving. We specialize in natural interaction and discovered many years ago that blood, chum, oil and all that stuff are not needed to provide the best great white shark diving experience in the industry.',

			5 => 'Nowhere else in the world boasts giant mantas that literally seek out interaction with divers and snorkellers. The behaviour these mantas exhibit is almost inexplicable. These gentle giants with wingspans up to 20 feet consistently approach divers for intimate eye-to-eye encounters. If you are can remain calm and still in the water, you are almost sure to experience what we call "manta love." While mantas are the main reason to voyage out to Socorro, there are also: schooling hammerheads and silky sharks, plus 8 other species of sharks, bottlenose dolphins that regularly hang out with divers at some dive sites, large schools of pelagic fish, and, in season, humpback whales.',

			12 => 'The Sea of Cortez is an interesting place. We started diving here many years ago when giant mantas were common and multiple schools of hammerhead sharks would swirl around El Bajo. Commercial fishermen hit the place really hard in the 1990\'s before the government of Mexico enacted exclusionary zones and marine protected area. Our beautiful sea has come back to life but it is different than before. Whale sharks are still resident seasonally off La Paz. The sea lions at Los Islotes were never impacted. Humpback whales still show up every winter. And the huge schools of panamic fish are as abundant as ever before. We are seeing hammerhead sharks at El Bajo and Los Animas and mantas at La Reyna although not as abundantly as before. We finally figured out where the humboldt squid are and it\'s quite a trek to get there! We discovered the stunning number of whale sharks at Bahia de Los Angeles in the northern sea of cortez. And there is an excellent chance of seeing bull sharks at Cabo Pulmo.
			It\'s not Socorro. It\'s not Guadalupe Island. The Sea of Cortez is a unique and different destination with it\'s own charm - and generally calm water - albeit not the same number of big animals as our other destinations. We welcome the opportunity to give you more information and ensure that we meet your expectations.',

			18 => 'The southern Sea of Cortez offers the classic type of diving we remember from the old days. This area took a heavy hit form over fishing in the 1990s but with tight control and regulations by the government of Mexico the life has rebounded. It is once again possible to see schooling hammerheads, mantas, and all the life that the Sea of Cortez is famous for. We will be exploring the protected biosphere reserve UNESCO World Heritage site at Cabo Pulmo and resident population of bull sharks. Please be aware that diving in the park is tightly controlled. We will also be traveling further north to La Paz and old favourites like Cerralvo, La Reina, and Las Animas. These trips depart and return to Cabo san Lucas.',

			19 => 'Wayyyyy off the beaten path in a hidden bay in the northern sea of cortez up to 220 whale sharks gather very summer. The whale sharks are amazing and we love nothing better than getting in the water with them. The nearby scuba diving is great. And local colonies of up to 1000 sea lions are the icing on this particular cake. Trip meets at our San Diego Hospitality Suite.
			The southern Sea of Cortez offers the classic type of diving we remember from the old days. This area took a heavy hit form over fishing in the 1990\'s but with tight control and regulations by the government of Mexico the life has rebounded. It is once again possible to see schooling hammerheads, mantas, and all the life that the Sea of Cortez is famous for. We will be exploring the protected biosphere reserve UNESCO World Heritage site at Cabo Pulmo and resident population of bull sharks. Please be aware that diving in the park is tightly controlled. We will also be traveling further north to La Paz and old favourites like Cerralvo, La Reina, and Las Animas. This trip returns to Cabo San Lucas.',

			20 => 'There is a legendary lagoon on the Pacific coast of Baja Mexico that is spoken of in hushed whispers, where gray whales finish an 11,000 mile migration from the Bering Sea and Alaska to mate and calve each year.
			The gray whales here are uniquely curious and interactive, allowing for close-up, intimate encounters with these gentle giants and their newborn calves.
			This is San Ignacio Lagoon, one of only 4 mating and birthing lagoons in the world, with the highest concentration of sociable whales, and the only lagoon still untouched by human development.
			With a private charter flight from Cabo San Lucas, it has never been easier to arrive at San Ignacio for 3 nights of fully catered glamping and virtually unlimited whale encounters.',

			22 => 'During October and November, a magical event happens in the nutrient rich swirling blue waters off Magdalena Bay where striped marlin gather to hunt on the second largest sardine run in the world. This thrilling underwater action is spectacular. There is an excellent chance of also seeing california sea lions, dolphin, wahoo, pelicans, different species of gulls, boobie birds, frigate birds, shearwaters and even the awe inspiring albatross.
			While it\'s best to snorkel or free dive around the bait balls there will be plenty of scuba diving opportunities on your week long trip with massive schools of fish, turtles and the wreck of a WW1 submarine and the passenger steam ship SS Independence. But wait, there is more! Humpback whales are on the move at this time of year and if we are really lucky, we might even see orcas or blue whales!',
		);
		return $descriptions;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_nautilus_trips() {
		return $this->nautilus_trips;
	}

	public function nautilus_trips_create_booking( $tripId ){
		global $wpdb;

		$table_name = $wpdb->prefix . 'nautilus_trips_booking';
		$params = array(
			'siteUrl' => get_site_url(),
			'apiKey' => sanitize_text_field( get_option('nautilus_trips_api_key') ),
			'dealerEmail' => sanitize_email( get_option('nautilus_trips_admin_email') ),

			'guestFirstName' => sanitize_text_field( $_POST['firstName'] ),
			'guestLastName' => sanitize_text_field( $_POST['lastName'] ),
			'guestEmail' => sanitize_text_field( $_POST['email'] ),
			'guestPhone' => sanitize_text_field( $_POST['phone'] ),
			'tripId' => absint($tripId),
		);

		// Process the parameters needed to create the booking in Nautilus ssytems
		// and to save a local copy of the request for reference on the dealers site.
		$booked = array();
		if (!empty($_POST['comments']))
			$params['comments'] = sanitize_text_field( $_POST['comments'] );

		if (!empty($_POST['nt-rt-premium-f']))
			$params['premiumF'] = $booked['premiumF'] = absint( $_POST['nt-rt-premium-f'] );
		if (!empty($_POST['nt-rt-premium-m']))
			$params['premiumM'] = $booked['premiumM'] = absint( $_POST['nt-rt-premium-m'] );
		if (!empty($_POST['nt-rt-superior-f']))
			$params['superiorF'] = $booked['superiorF'] = absint( $_POST['nt-rt-superior-f'] );
		if (!empty($_POST['nt-rt-superior-m']))
			$params['superiorM'] = $booked['superiorM'] = absint( $_POST['nt-rt-superior-m'] );
		if (!empty($_POST['nt-rt-stateroom-f']))
			$params['stateroomF'] = $booked['stateroomF'] = absint( $_POST['nt-rt-stateroom-f'] );
		if (!empty($_POST['nt-rt-stateroom-m']))
			$params['stateroomM'] = $booked['stateroomM'] = absint( $_POST['nt-rt-stateroom-m'] );
		if (!empty($_POST['nt-rt-single-f']))
			$params['singleF'] = $booked['singleF'] = absint( $_POST['nt-rt-single-f'] );
		if (!empty($_POST['nt-rt-single-m']))
			$params['singleM'] = $booked['singleM'] = absint( $_POST['nt-rt-single-m'] );
		if (!empty($_POST['nt-rt-triple-f']))
			$params['tripleF'] = $booked['tripleF'] = absint( $_POST['nt-rt-triple-f'] );
		if (!empty($_POST['nt-rt-triple-m']))
			$params['tripleM'] = $booked['tripleM'] = absint( $_POST['nt-rt-triple-m'] );

		$res = NULL;
		// Save the requested booking details to the local table before we try to make
		// the API call and actually create the booking in Nautilus systems.
		$sql = "INSERT INTO " . $table_name . "
			(tripFk, shipFk, tripClassFk, boarddate, requestDate, firstname, lastname, email, phone, comments, booked) VALUES
			(%d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s)";
		$vals = array(
			absint($tripId),
			(isset( $_POST['shipFk'] ) ? absint( $_POST['shipFk'] ) : 0),
			(isset( $_POST['tripClassFk'] ) ? absint( $_POST['tripClassFk'] ) : 0),
			(isset( $_POST['boarddate'] ) ? sanitize_text_field( $_POST['boarddate'] ) : '0000-00-00'),
			sanitize_text_field( date('Y-m-d H:i:s') ),
			$params['guestFirstName'], $params['guestLastName'],
			$params['guestEmail'], $params['guestPhone'],
			(isset($params['comments']) ? $params['comments'] : ''),
			json_encode($booked)
		);
		$query = $wpdb->prepare($sql, $vals);
		$res = $wpdb->query( $query );
		$rqId = $wpdb->insert_id;


		if ($res != NULL) {
			$book_trip_url = self::$_api_book_url;

			$jsonResult = NULL;
			// Make the actual API Request to source server and see if we have a successful booking
			try {
				$jsonResult = $this->_nautilus_trips_do_api_call( self::$_api_book_url, $params );
			} catch (Exception $e) {
				//echo 'Caught exception: ' . $e->getMessage();
				// If we fail on CURL, ensure we skip the next block and fail out with "Contact Administrator" error
				$jsonResult = NULL;
			}

			if ($jsonResult != NULL) {

				$result = json_decode($jsonResult);

				if (isset($result->status)) {
					if ($result->status) {

						// Save the result of the API request to the local DB to display to user and save for the Dealer
						$sql = "UPDATE " . $table_name . " SET bookFk=%d, retailprice=%s, confirmation=%s, conf_html=%s WHERE rqId=%d";
						$vals = array(
							absint($result->bookFk),
							sanitize_text_field($result->retailprice),
							sanitize_text_field($result->confirmation),
							wp_filter_post_kses($result->conf_html),
							absint($rqId)
						);
						$query = $wpdb->prepare($sql, $vals);
						$updated = $wpdb->query( $query );

					} else {
						// Response from API call is false, log the error code and message in the local db
						$sql = "UPDATE " . $table_name . " SET api_errcode=%s, api_error=%s WHERE rqId=%d";
						$vals = array(
							absint($result->error_code),
							sanitize_text_Field($result->error_message),
							absint($rqId)
						);
						$query = $wpdb->prepare($sql, $vals);
						$updated = $wpdb->query( $query );
					}
				}

				return $result;
			}
		}

		return array(
			'status' => false,
			'error_code' => 101,
			'error_message' => 'There was an error saving your request to the website. Please contact the site administrator.'
		);
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param    string    $rooms_array  	Array of rooms for a trip to be cleaned for JS use
	 */
	public function clean_trip_rooms( $rooms_array ) {
		if (is_array($rooms_array)) {
			foreach ($rooms_array as $key => $room){
				$rooms_array[$key]->berths = absint($room->berths);
				$rooms_array[$key]->name = sanitize_text_field($room->name);
				$rooms_array[$key]->price = sanitize_text_field($room->price);
				$rooms_array[$key]->preTax = sanitize_text_field($room->preTax);
			}
		}
		return $rooms_array;
	}

	/**
	 * Set up parameters needed to perform a CURL call to the API endpoint. This will work
	 * for both a general request with no $tripId, or a specific request by $tripId.
	 *
	 * @since    1.0.0
	 * @param    string    $tripId 	Optional trip ID for retrieving from API endpoint
	 */
	public function nautilus_trips_api_get_trips( $tripId=NULL, $shortcode_dest=NULL ) {

		$api_key = get_option('nautilus_trips_api_key');

		$params = array();
		if ($api_key != NULL)
			$params['apikey'] = $api_key;

		if ($tripId != NULL) {

			$params['tripId'] = absint($tripId);
			$get_trip_url = self::$_api_trip_url . '/' . absint($tripId);
			$trip = $this->_nautilus_trips_do_api_call( $get_trip_url, $params );

			return $trip;

		} else {
			// Gather values selected for trip filtering by website owner.
			$ships = get_option('nautilus_trips_ships');
			$years = get_option('nautilus_trips_years');
			$months = get_option('nautilus_trips_months');
			$admin_email = get_option('nautilus_trips_admin_email');
			$destinations = get_option('nautilus_trips_destinations');
			$dest_list = $this->get_nautilus_trips_destinations();

			$params['numPerPage'] = 100;
			$params['start'] = 0;
			if ($ships != NULL)
				$params['ship'] = esc_attr($this->_nautilus_trips_parse_json_to_param( $ships ) );
			if ($years != NULL)
				$params['year'] = esc_attr($this->_nautilus_trips_parse_json_to_param( $years ) );
			if ($months != NULL)
				$params['month'] = esc_attr($this->_nautilus_trips_parse_json_to_param( $months ) );
			if ($shortcode_dest != NULL && array_key_exists($shortcode_dest, $dest_list))
				$params['destination'] = esc_attr($shortcode_dest);
			elseif ($destinations != NULL)
				$params['destination'] = esc_attr($this->_nautilus_trips_parse_json_to_param( $destinations ) );

			$trips = $this->_nautilus_trips_do_api_call( self::$_api_trips_url, $params );

			return $trips;
		}
	}

	/**
	 * Pull a list of locally saved Bookings created through the plugin
	 *
	 * @since    1.0.0
	 */
	public function get_bookings() {
		global $wpdb;
		// Used in admin panel for listing bookings generated
		$table_name = $wpdb->prefix . 'nautilus_trips_booking';
		$rows = $wpdb->get_results("SELECT *, DATE_FORMAT(requestDate, '%e %b %Y %H:%i') as niceDate FROM " . $table_name . " WHERE 1=1 ORDER BY rqId DESC");

		return $rows;
	}

	/**
	 * We pass these through the API as a comma-separated string of values.
	 * Convert the array parameter for use in an API request.
	 *
	 * @since    1.0.0
	 */
	private function _nautilus_trips_parse_json_to_param( $param ) {

		if ($param != NULL && ($data = json_decode($param)) != NULL) {
			return implode(',', $data);
		}
		return NULL;
	}

	/**
	 * Set up and perform a remote API call to the API endpoint with the parameters passed in through @postFields.
	 *
	 * @since    1.0.0
	 * @param	 array 	$postFields  Array of fields to POST to the API endpoint.
	 */
	private function _nautilus_trips_do_api_call($curl_url, $postFields=NULL, $httpAuth=NULL, $cookie=NULL){

		$args = array(
			'body' => $postFields,
			'timeout' => '90000',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'cookies' => array()
		);
		$response = wp_remote_post($curl_url, $args);
		if (is_array($response) && isset($response['response']['code']) && $response['response']['code'] == 200) {
			$output = wp_remote_retrieve_body( $response );
			return $output;
		} else {
			return array(
				'status' => false,
				'error_code' => 102,
				'error_message' => 'There was an error making a request to the API. Please contact the site administrator.'
			);
		}
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nautilus_Trips_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public static function get_version() {
		return NAUTILUS_TRIPS_VERSION;
	}

}
