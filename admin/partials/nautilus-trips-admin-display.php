<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>Nautilus Trips Plugin Settings</h1>
<?php
if ($message != NULL) {
?>

<div class="notice notice-success is-dismissible">
    <p><?php _e( $message, 'sample-text-domain' ); ?></p>
</div>
<?php
}
?>

<p>This Plugin is to allow an approved Nautilus Dealer to display Nautilus Dive Adventures trips on their website with
    real-time availability. This makes it really easy for dealers, dive shop owners, tour operators, and similar organizations
    to promote and sell trips aboard the Nautilus vessels.</p>

<p>If you are an approved Nautilus Dealer already, please put your Nautilus API Key in the field below. If you don't have an
    API key or don't know what that is, you can sign in to your <a href="https://nautilusdealers.com" target="_blank">Nautilus Dealers</a>
    account and visit the "My Profile" page to find or create an API key.</p>
<p>If you are not a Nautilus Dealer, you can apply to become a Nautilus Dealer on
    <a href="https://nautilusdealers.com/contact" target="_blank">our contact page</a> pending approval from
    the Nautilus Boat Operations team.</p>
<p>* Nautilus Dealer API Key and Email Address are required settings for this plugin to function.</p>
<p>If you've got an API Key and Email Address saved, see the <a href="<?php echo esc_url( get_page_link( absint($nautilus_trips_page_id) ) ); ?>">Nautilus Trips page here</a>.</p>

<form name="nautilus_trips_form" action="<?php echo esc_url( Nautilus_Trips_Admin::get_page_url() ); ?>" method="post" id="nautilus-trips-activate" class="nautilus-trips-right">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="nautilus_trips_api_key">Nautilus Dealer API Key *</label>
            </th>
            <td>
                <input type="text" name="nautilus_trips_api_key" id="nautilus_trips_api_key" value="<?php echo esc_attr($nautilus_trips_api_key); ?>" class="regular-text ltr" />
            </td>
            <td></td>
        </tr>
        <tr>
            <th>
                <label for="nautilus_trips_admin_email">Email Address for Booking Requests *</label>
            </th>
            <td>
                <input type="text" name="nautilus_trips_admin_email" id="nautilus_trips_admin_email" value="<?php echo esc_attr($nautilus_trips_admin_email); ?>"  class="regular-text ltr"/>
            </td>
            <td></td>
        </tr>
        <tr>
            <th style="vertical-align:top;">
                <label for="nautilus_trips_destinations">Destinations to include</label><br>
                <em>Leave unchecked for "all"</em>
            </th>
            <td>
<?php
                if ($nautilus_destinations != NULL) {
                    foreach ($nautilus_destinations as $destId => $destName) {
                        echo '<label for="nautilus_trips_destinations_' . absint($destId) . '">
                            <input type="checkbox" id="nautilus_trips_destinations_' . absint($destId) . '" '
                                . (in_array(absint($destId), $nautilus_trips_destinations) ? 'checked="checked"' : '')
                                . ' name="nautilus_trips_destinations_' . absint($destId) . '" value="1" />' . esc_html($destName) . '
                        </label><br>' . "\r\n";
                    }
                }
?>
            </td>
            <td><p><strong>NOTE:</strong> Please ensure ship/location and destinations match or filter only by Vessel <strong>OR</strong> Destination, <strong>not both!</strong><br>
                San Ignacio Lagoon is a shore-based destination</p>
            </td>
        </tr>
        <tr>
            <th style="vertical-align:top;">
                <label for="nautilus_trips_ships">Vessels</label><br>
                <em>Leave unchecked for "all"</em>
            </th>
            <td>
<?php
                if ($nautilus_ships != NULL) {
                    foreach ($nautilus_ships as $shipId => $shipName) {
                        echo '<label for="nautilus_trips_ships_' . absint($shipId) . '">
                            <input type="checkbox" id="nautilus_trips_ships_' . absint($shipId) . '" '
                                . (in_array(absint($shipId), $nautilus_trips_ships) ? 'checked="checked"' : '')
                                . ' name="nautilus_trips_ships_' . absint($shipId) . '" value="1" />' . esc_html($shipName) . '
                        </label><br>' . "\r\n";
                    }
                }
?>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <th style="vertical-align:top;">
                <label for="nautilus_trips_years">Years to Include</label><br>
                <em>Leave unchecked for "all"</em>
            </th>
            <td>
<?php
                if ($nautilus_years != NULL) {
                    foreach ($nautilus_years as $yearId => $year) {
                        echo '<label for="nautilus_trips_years_' . absint($yearId) . '">
                            <input type="checkbox" id="nautilus_trips_years_' . absint($yearId) . '" '
                                . (in_array(absint($yearId), $nautilus_trips_years) ? 'checked="checked"' : '')
                                . ' name="nautilus_trips_years_' . absint($yearId) . '" value="1" />' . esc_html($year) . '
                        </label><br>' . "\r\n";
                    }
                }
?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th style="vertical-align:top;">
                <label for="nautilus_trips_months">Months to Include</label><br>
                <em>Leave unchecked for "all"</em>
            </th>
            <td>
<?php
                if ($nautilus_months != NULL) {
                    foreach ($nautilus_months as $monthId => $month) {
                        echo '<label for="nautilus_trips_months_' . esc_attr($monthId) . '">
                            <input type="checkbox" id="nautilus_trips_months_' . esc_attr($monthId) . '"' .
                                (in_array(esc_attr($monthId), $nautilus_trips_months) ? 'checked="checked"' : '')
                                . '  name="nautilus_trips_months_' . esc_attr($monthId) . '" value="1" />' . esc_html($month) . '
                        </label><br>' . "\r\n";
                    }
                }
?>
            </td>
            <td><strong>NOTE:</strong> Destinations are seasonal:<br>
                Socorro season is November - July<br>
                San Ignacio Lagoon is January - March<br>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="submit" name="save-nautilus-trips-settings" id="save-nautilus-trips-settings" class="button button-primary" value="Save Settings" />
            </td>
        </tr>
    </table>
    <h3>How to add different destinations on different pages</h3>
    <p>If you would like to add different destinations to different pages, you can use a shortcode on each page.
        This is a great way to promote a specific destination with its own page content targeted to the amazing interactions for that destination.</p>
    <p>Please copy and paste the following shortcodes for their respective destinations:</p>
    <ul>
        <li><span style="margin-right:20px;"><input type="text" readonly="readonly" value='[nautilus-trips dest="4"]' /></span> Guadalupe Island Great White Sharks</li>
        <li><span style="margin-right:20px;"><input type="text" readonly="readonly" value='[nautilus-trips dest="5"]' /></span> Socorro Island Giant Mantas</li>
        <li><span style="margin-right:20px;"><input type="text" readonly="readonly" value='[nautilus-trips dest="12"]' /></span> Sea of Cortez</li>
        <li><span style="margin-right:20px;"><input type="text" readonly="readonly" value='[nautilus-trips dest="18"]' /></span> Cabo Pulmo Bull Sharks</li>
        <li><span style="margin-right:20px;"><input type="text" readonly="readonly" value='[nautilus-trips dest="19"]' /></span> Bahia de Los Angeles Whale Sharks</li>
        <li><span style="margin-right:20px;"><input type="text" readonly="readonly" value='[nautilus-trips dest="20"]' /></span> San Ignacio Lagoon Gray Whales</li>
    </ul>

</form>


<?php
/*
?>

<div id="nt-trips-booked">
<?php
if (count($bookings) > 0) {
?>

    <h2>Booking Requests</h2>
    <table class="nt-bookings-table">
        <tr>
            <th>Trip Date<br>Vessel<br>Confirmation #</th>
            <th>Guest Name<br>Email<br>Phone</th>
            <th>Berths Requested</th>
            <th>API Error</th>
        </tr>
<?php
    foreach ($bookings as $booking){
        $lines = array();
        $rq = json_decode( $booking->booked );
        foreach ($rq as $k => $v) {
            $lines[] = $k . ': ' . $v;
        }
        $requested = implode(', ', $lines);
?>

        <tr>
            <td class="nt-center">
                <?php echo esc_html( date('d M Y', strtotime($booking->boarddate)) ); ?><br>
                <?php echo esc_html( (isset($nautilus_ships[$booking->shipFk]) ? $nautilus_ships[$booking->shipFk] : '') ); ?>
                <?php echo ($booking->confirmation != NULL ? '<br><a href="https://book.nautilusdive.com/booking/pay/' . esc_url($booking->confirmation) . '" target="_blank">' . esc_html($booking->confirmation) . '</a>' : ''); ?>
            </td>
            <td class="nt-center">
                <?php echo esc_html($booking->firstname . ' ' . $booking->lastname); ?><br>
                <?php echo esc_html($booking->email); ?><br>
                <?php echo esc_html($booking->phone); ?>
            </td>
            <td><?php echo esc_html($requested); ?></td>
            <td><?php echo ($booking->api_errcode > 0 ? 'Error ' . esc_html($booking->api_errcode) . ': ' . esc_html($booking->api_error) : ''); ?></td>
        </tr>
<?php
    }
?>

    </table>
    </div>
<?php
}
/**/
