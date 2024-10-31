<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://nautilusliveaboards.com
 * @since      1.0.0
 *
 * @package    Nautilus_Trips
 * @subpackage Nautilus_Trips/public/partials
 */

if ($response != NULL && !$response->status) {
?>

    <div class="nt-form-err">There was an error creating your booking!!<br>
        Error: <?php echo esc_html($response->error_code); ?> - <?php echo esc_html($response->error_message); ?>
    </div>
<?php
}

if (isset($tripDetails->status) && $tripDetails->status) {
    $trip = $tripDetails->trip;
    $tripRooms = $Trip->clean_trip_rooms($trip->rooms);

    $tripleIdx = $premiumIdx = $superiorIdx = $singleIdx = $stateroomIdx = -1;

    $startFormat = 'F d';
    $endFormat = 'd, Y';
    if (substr($trip->departdate, 0, 4) != substr($trip->arrivedate, 0, 4)) {
        $startFormat = 'F d, Y';
        $endFormat = 'F d, Y';

    } elseif (substr($trip->departdate, 6, 2) != substr($trip->arrivedate, 6, 2)) {
        $startFormat = 'F d';
        $endFormat = 'F d, Y';
    }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="nt-trip-block">
    <div class="nt-trip-trip">
        <img src="<?php echo esc_url($trip->img); ?>" alt="" class="nt-dest-img" />
        <div class="nt-trip-details">
            <div class="triphead"><?php echo esc_html( date($startFormat, strtotime($trip->departdate)) ); ?> - <?php echo esc_html( date($endFormat, strtotime($trip->arrivedate)) ); ?></div>
            <div class="triptype"><?php echo esc_html($trip->brief); ?> - <?php echo esc_html($trip->tripType); ?></div>
            <div class="tripinfo"><?php echo absint( ($trip->length+1) ); ?> days on the <?php echo esc_html($trip->vessel); ?></div>
        </div>

        <div class="nt-back">
            <a href="<?php echo esc_url( get_page_link( absint($page_id) ) ); ?>" class="nt-back-button">&lt; Back to Trips</a>
        </div>

<?php
    if ($trip->availability > 0) {
?>

        <form method="post" action="">
<?php
        if ($errs != NULL && count($errs) > 0) {
?>

            <div class="nt-form-err"><?php
            foreach ($errs as $e) {
                echo esc_html($e) . '<br>';
            }
            ?></div>
<?php
        }
?>

    <div class="nt-trip-form">
        <div class="nt-half">
            <div class="nt-form-head">Personal Details</div>
            <p>Please provide some information so we can make a booking request. We need your name and contact information to be able to get in touch with you about your reservation.</p>
            <div class="nt-form-field">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" id="nt-firstName" value="<?php echo esc_attr($firstName); ?>" />
            </div>
            <div class="nt-form-field">
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" id="nt-lastName" value="<?php echo esc_attr($lastName); ?>" />
            </div>

            <div class="nt-form-field nt-clear">
                <label for="email">Email Address</label>
                <input type="text" name="email" id="nt-email" value="<?php echo esc_attr($email); ?>" />
            </div>
            <div class="nt-form-field">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="nt-phone" value="<?php echo esc_attr($phone); ?>" />
            </div>

            <div class="nt-form-comments nt-clear">
                <label for="comments">Additional Notes, Comments, or Requests</label>
                <textarea name="comments" id="nt-comments"><?php echo esc_attr($comments); ?></textarea>
            </div>
        </div>

<?php
    if ($trip->shipId < 7) {
?>
        <div class="nt-half nt-right">
            <div class="nt-trip-layout">
                <div class="triphead"><?php echo esc_html($trip->vessel); ?> Layout</div>
<?php
        if ($trip->shipId == 1) {
?>

                <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nex-deck-1.png" alt="" />
                <span class="caption">Nautilus Explorer Lower Deck</span>
                <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nex-deck-2.png" alt="" />
                <span class="caption">Nautilus Explorer Main Deck</span>
                <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nex-deck-3.png" alt="" />
                <span class="caption">Nautilus Explorer Wheelhouse Deck</span>
<?php
        } elseif ($trip->shipId == 3) {
?>

            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nba-deck-1.png" alt="" />
            <span class="caption">Nautilus Belle Amie Lower Deck</span>
            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nba-deck-2.png" alt="" />
            <span class="caption">Nautilus Belle Amie Main Deck</span>
            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nba-deck-3.png" alt="" />
            <span class="caption">Nautilus Belle Amie Wheelhouse Deck</span>
<?php
        } elseif ($trip->shipId == 4) {
?>

            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nus-deck-1.png" alt="" />
            <span class="caption">Nautilus UnderSea Lower Deck</span>
            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nus-deck-2.png" alt="" />
            <span class="caption">Nautilus UnderSea Main Deck</span>
            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/nus-deck-3.png" alt="" />
            <span class="caption">Nautilus UnderSea Sun Deck</span>
<?php
        } elseif ($trip->shipId == 5) {
?>

            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/ngl-deck-1.png" alt="" />
            <span class="caption">Nautilus Gallant Lady Lower Deck</span>
            <img src="<?php echo plugin_dir_url( NAUTILUS_TRIPS_PLUGIN_DIR ); ?>nautilus-trips/images/ngl-deck-2.png" alt="" />
            <span class="caption">Nautilus Gallant Lady Main Deck</span>
<?php
        } elseif ($trip->shipId == 7) {
?>

            <div style="margin:50px 0;">
                <em>San Ignacio camp layout coming soon</em>
            </div>
<?php
        }
?>

            </div>
        </div>
<?php
    }
?>

        <div class="nt-rooms">
            <div class="nt-form-head">Room Type and Numbers</div>
            <p>To make a booking request, we need to know how many rooms and which type of rooms you would like to book. If you know the gender of people booking, please specify in the Male or Female boxes below.</p>

<?php
            $triQuadName = NULL;
//            echo '<pre>'.print_r($trip,true).'</pre>';
            if (isset($trip->visible) && isset($trip->availability) && $trip->visible > 0 && isset($trip->availability) && $trip->availability > 0) {
                $types = (isset($trip->rooms) ? count($trip->rooms) : 0);

                // Loop through the room types and display boxes for each type of available room.
                foreach ($trip->rooms as $k => $room) {
                    $nameParts = explode(' ', $room->name);
                    $rt = strtolower(array_shift( $nameParts ));
//                    $rt = $room->class;

                    // This room is called a triple throughout the code, even when provisioned as a Quad onboard.
                    // Make sure we use the internal name "triple" regardless of the total capacity of this room.
                    if ($rt == 'quad') {
                        $rt = 'triple';
                    }
                    $name = $rt . 'Idx'; // Used for JS hash indexing below
                    $$name = $k;

                    $fPost = $rt . 'F';
                    $mPost = $rt . 'M';

                    // Ensure any POSTed values are unset if this room type is no longer available.
                    // The skip this room type in the display of availability.
                    if ($room->berths == 0) {
                        if ($$fPost != NULL)
                            $$fPost = 0;
                        if ($$mPost != NULL)
                            $$mPost = 0;

                        continue;   // This is a room class with no space available. Skip it entirely.
                    }
?>

                <div class="nt-roomtype-<?php echo absint($types); ?>">
                    <div class="nt-roomhead"><?php echo esc_html($room->name); ?></div>
                    <p><?php echo absint($room->berths) . ' space' . (absint($room->berths) == 1 ? '' : 's'); ?> available<br>
                        USD $<?php echo absint(floor($room->preTax)); ?></p>
                    <div class="nt-rs">
                        <label for="nt-rt-<?php echo esc_attr($rt); ?>-f">Female
                            <input type="number" id="nt-rt-<?php echo esc_attr($rt); ?>-f" class="nt-formfield" name="nt-rt-<?php echo esc_attr($rt); ?>-f" value="<?php echo ($$fPost > 0 ? absint($$fPost) : ''); ?>" onchange="ntCheckCount(this);" />
                        </label>
                    </div>
                    <div class="nt-rs">
                        <label for="nt-rt-<?php echo $rt; ?>-m">Male
                            <input type="number" id="nt-rt-<?php echo esc_attr($rt); ?>-m" class="nt-formfield" name="nt-rt-<?php echo esc_attr($rt); ?>-m" value="<?php echo ($$mPost > 0 ? absint($$mPost) : ''); ?>" onchange="ntCheckCount(this);" />
                        </label>
                    </div>

                    <input type="hidden" id="nt-rt-<?php echo esc_attr($rt); ?>-avail" name="nt-rt-<?php echo esc_attr($rt); ?>-avail" value="<?php echo esc_attr($room->berths); ?>" />
                    <input type="hidden" id="nt-rt-<?php echo esc_attr($rt); ?>-index" name="nt-rt-<?php echo esc_attr($rt); ?>-index" value="<?php echo esc_attr($k); ?>" />
                </div>
<?php
                }
            }
?>

            </div>

            <div class="nt-submit">
                <div id="nt-book-summary"></div>
                <input type="hidden" name="bookTotalCost" id="ntBookTotal" value="" />
                <input type="hidden" name="boarddate" value="<?php echo esc_attr($trip->departdate); ?>" />
                <input type="hidden" name="shipFk" value="<?php echo esc_attr($trip->shipId); ?>" />
                <input type="hidden" name="tripClassFk" value="<?php echo esc_attr($trip->sea); ?>" />

                <input type="submit" name="ntBookTrip" id="nt-book-button" value="Submit Booking Request" />
                <?php echo wp_nonce_field( 'book-trip_' . absint($trip->tripId) ); ?>
            </div>
        </div>

        <div class="nt-terms">
            <div class="nt-form-head">Complete Your Booking Request</div>
            <p>Clicking "Submit Booking Request" will submit your request to this website and will create the booking in
                the Nautilus Liveaboards system. Your name, email, phone number, and comments will be sent to the website owner
                as well as Nautilus Liveaboards along with the requested space(s).</p>
            <p>If your booking is created successfully you will be shown the full booking price as well as a confirmation number, and a
                copy will be emailed to the email address provided and the tour operator. You will be contacted by the tour
                operator for payment options soon.</p>
            <p>If there is a problem creating your booking, you will be shown a message describing the issue and can try booking again.</p>
            <p>Your personal information is kept in the strictest of confidence as required by privacy laws and the GDPR.</p>
        </div>
        <div class="nt-disclaimer">
            * Trip Prices do not include taxes or a $<?php echo ($trip->shipId == 7 ? '35' : '65'); ?> per person port and park fee payable in cash
        </div>

        </form>
<?php
    }
?>

    </div>
</div>

<script type="text/javascript">
var myBerths = {
    'premium':{'berths':<?php echo intval($premiumF + $premiumM); ?>,'index':<?php echo absint($premiumIdx); ?>},
    'superior':{'berths':<?php echo intval($superiorF + $superiorM); ?>,'index':<?php echo absint($superiorIdx); ?>},
    'stateroom':{'berths':<?php echo intval($stateroomF + $stateroomM); ?>,'index':<?php echo absint($stateroomIdx); ?>},
    'single':{'berths':<?php echo intval($singleF + $singleM); ?>,'index':<?php echo absint($singleIdx); ?>},
    'triple':{'berths':<?php echo intval($tripleF + $tripleM); ?>,'index':<?php echo absint($tripleIdx); ?>},
};
var tripRooms = <?php echo json_encode($tripRooms); ?>;
function ntCheckCount(el){
    var base = null;
    var elId = jQuery(el).attr('id');
    var fFound = elId.indexOf('-f');
    var mFound = elId.indexOf('-m');

    // Get a base field name to get data field values
    if (fFound >= 0){
        base = elId.replace('-f', '');
    }else{
        base = elId.replace('-m', '');
    }

    // Get field values to include in calculation
    var baseType = base.replace('nt-rt-', '');
    var numMale = (jQuery('#' + base + '-m').val() == '' ? 0 : parseInt( jQuery('#' + base + '-m').val() ));
    var numFemale = (jQuery('#' + base + '-f').val() == '' ? 0 : parseInt( jQuery('#' + base + '-f').val() ));
    var numAvail = parseInt( jQuery('#' + base + '-avail').val() );
    var typeIndex = parseInt( jQuery('#' + base + '-index').val() );

    var totalType = parseInt(numMale) + parseInt(numFemale);
    var typeCost = tripRooms[typeIndex].preTax;

    // Make sure we don't try to book more berths than are available per room type.
    if (totalType > numAvail){
        if (fFound >= 0 && numFemale > 0){
            jQuery('#' + base + '-f').val( (numAvail - numMale) );
        }else if (mFound >= 0 && numMale > 0){
            jQuery('#' + base + '-m').val( (numAvail - numFemale) );
        }
        totalType = numAvail;
    }

    // Save to our variable for calculating booking subtotal
    myBerths[baseType].index = typeIndex;
    myBerths[baseType].berths = totalType;
    ntDoPriceTable();

    return null;
};
function ntDoPriceTable(){
    var priceHtml = '';
    var bookingCost = 0;
    var priceHtmlHead = '<table id="nt-price-table"><tr><th>Room Type</th><th>Spaces</th><th>Room Cost</th><th>Price</th></tr>';
    // Loop through each berth type and add the cost for any we have selected
    jQuery.each(myBerths, function(i,item){
        // Sum up the specified rooms and display a table with the cost of the booking.
        var room = null;
        var roomTypeCost = 0;
        if (item.berths > 0){
            room = tripRooms[item.index];
            roomTypeCost = parseInt(item.berths) * parseInt( Math.floor(room.preTax) );
            bookingCost += roomTypeCost;
            priceHtml += '<tr><td>' + room.name + '</td><td class="nt-berths">' + item.berths + '</td><td class="nt-berths">$' + Math.floor(room.preTax) + '</td><td class="nt-price">$' + roomTypeCost + '</td></th>';
        }else{

        }
    });
    // If we've booked more than one berth, show a price table
    if (bookingCost > 0){
        priceHtml += '<tr><td colspan="3"><strong>Subtotal (USD)</strong></td><td class="nt-price"><strong>$' + bookingCost + '</strong></td></tr></table>';
        jQuery('#nt-book-summary').html( priceHtmlHead + priceHtml );
    }else{
        jQuery('#nt-book-summary').html( '' );
    }
    jQuery('#ntBookTotal').html( bookingCost );
    return null;
}
jQuery(document).ready(function(){
    jQuery.each( jQuery('.nt-formfield'), function(i,item){
        ntCheckCount(jQuery(item));
    });
    ntDoPriceTable();
});
</script>

<?php
} else {
?>

    There was a problem retrieving details for this trip.
<?php
}
