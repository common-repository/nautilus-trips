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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="nt-trip-block">
<?php
$trips = $tripData->trips;
$status = $tripData->status;
$numTrips = $tripData->numTrips;


$guad = $socorro = $pulmo = $bahia = $cortez = $magbay = $ignacio = '';
$guadCount = $socorroCount = $pulmoCount = $bahiaCount = $cortezCount = $magbayCount = $ignacioCount = 0;
$guadEndDiv = $socorroEndDiv = $pulmoEndDiv = $bahiaEndDiv = $cortezEndDiv = $magbayEndDiv = $ignacioEndDiv = '';


$thisPage = get_permalink();
$isQ = (strpos($thisPage, '?') !== false);
$thisPage .= ($isQ ? '&' : '?');

if ($trips != NULL) {
    foreach ($trips as $trip) {
        //echo '<pre>$trip: ';
        //print_r( $trip );
        //echo '</pre>';
        // We really do not need to show "Inquire"
        if ($trip->visible <= 0)
            continue;

        $avail = $avClass = '';

		$availability=0;
        $priceFrom = 99999999.99;
        if (isset($trip->rooms) && count($trip->rooms) > 0) {
            foreach ($trip->rooms as $room) {
				$availability+=$room->berths;
                if ($priceFrom > 0.00 && $priceFrom > $room->price && $room->berths > 0)
                    $priceFrom = absint(floor($room->preTax));
            }
        }
		if( $priceFrom == 99999999.99){
			 $priceFrom = "-";
		}
		if($availability>$trip->availability){
			$availability=$trip->availability;
		}
		if ($trip->visible < 0)
            $avail .= '<em class="nt-vis-' . esc_attr($trip->visible) . '">' . $trip->remarks . '</em>';
        elseif ($availability >= 5)
            $avail .= $availability . ' berth' . ($availability == 1 ? '' : 's') . ' available';
        elseif ($availability < 5) {
            $avail = 'Only ' . $availability . ' berth' . ($availability == 1 ? '' : 's') . ' left';
            $avClass = ' nt-avail-low';
        }

        $startFormat = 'M d';
        $endFormat = 'd, Y';
        if (substr($trip->departdate, 0, 4) != substr($trip->arrivedate, 0, 4)) {
            $startFormat = 'M d, Y';
            $endFormat = 'M d, Y';

        } elseif (substr($trip->departdate, 6, 2) != substr($trip->arrivedate, 6, 2)) {
            $startFormat = 'M d';
            $endFormat = 'M d, Y';
        }

        ob_start();
?>

    <a href="<?php echo esc_url($thisPage . 'tripId=' . absint($trip->tripId)); ?>" class="nt-triprow">
        <div class="nt-tripdetails">
            <div class="nt-tripdates"><?php echo date($startFormat, strtotime(esc_attr($trip->departdate))); ?> - <?php echo date($endFormat, strtotime(esc_attr($trip->arrivedate))); ?></div>
            <div class="nt-tripship"><?php echo ($trip->length+1); ?> days on the <?php echo esc_html($trip->vessel); ?></div>
            <div class="nt-tripinfo nt-avail <?php echo esc_attr($avClass); ?>"><?php echo esc_html($avail); ?></div>
            <div class="nt-tripprice">USD $<?php echo esc_html($priceFrom); ?>*</div>
        </div>
    </a>
<?php
        $destName = NULL;
        if (in_array($trip->sea, array(4,6,9))) {
            $guadCount++;
            $destName = 'guad';

            if ($guadCount == 10) {
                echo '<div class="nt-more" id="nt-guad-hide-more" onclick="ntShow(\'nt-guad-hide\');">Show more dates</div><div class="nt-hide" id="nt-guad-hide">' . "\r\n";
                $guadEndDiv .= '</div>';
            }
        }
        elseif (in_array($trip->sea, array(3,5,7))) {
            $socorroCount++;
            $destName = 'socorro';
            if ($socorroCount == 10) {
                echo '<div class="nt-more" id="nt-socorro-hide-more" onclick="ntShow(\'nt-socorro-hide\');">Show more dates</div><div class="nt-hide" id="nt-socorro-hide">' . "\r\n";
                $socorroEndDiv .= '</div>';
            }
        }
        elseif ($trip->sea == 12) {
            $cortezCount++;
            $destName = 'cortez';
            if ($cortezCount == 10) {
                echo '<div class="nt-more" id="nt-cortez-hide-more" onclick="ntShow(\'nt-cortez-hide\');">Show more dates</div><div class="nt-hide" id="nt-cortez-hide">' . "\r\n";
                $cortezEndDiv .= '</div>';
            }
        }
        elseif ($trip->sea == 18) {
            $pulmoCount++;
            $destName = 'pulmo';
            if ($pulmoCount == 10) {
                echo '<div class="nt-more" id="nt-pulmo-hide-more" onclick="ntShow(\'nt-pulmo-hide\');">Show more dates</div><div class="nt-hide" id="nt-pulmo-hide">' . "\r\n";
                $pulmoEndDiv .= '</div>';
            }
        }
        elseif ($trip->sea == 19) {
            $bahiaCount++;
            $destName = 'bahia';
            if ($bahiaCount == 10) {
                echo '<div class="nt-more" id="nt-bahia-hide-more" onclick="ntShow(\'nt-bahia-hide\');">Show more dates</div><div class="nt-hide" id="nt-bahia-hide">' . "\r\n";
                $bahiaEndDiv .= '</div>';
            }
        }
        elseif ($trip->sea == 20) {
            $ignacioCount++;
            $destName = 'ignacio';
            if ($ignacioCount == 10) {
                echo '<div class="nt-more" id="nt-ignacio-hide-more" onclick="ntShow(\'nt-ignacio-hide\');">Show more dates</div><div class="nt-hide" id="nt-ignacio-hide">' . "\r\n";
                $ignacioEndDiv .= '</div>';
            }
        }
        elseif ($trip->sea == 22) {
            $magbayCount++;
            $destName = 'magbay';
            if ($magbayCount == 10) {
                echo '<div class="nt-more" id="nt-magbay-hide-more" onclick="ntShow(\'nt-magbay-hide\');">Show more dates</div><div class="nt-hide" id="nt-magbay-hide">' . "\r\n";
                $magbayEndDiv .= '</div>';
            }
        }

        if ($$destName == '') {
            ob_start();
            include('nautilus-trips-destination.php');
            $$destName .= ob_get_contents();
            ob_end_clean();
        }

        $$destName .= ob_get_contents();
        ob_end_clean();
    }
}
?>

    <div id="nt-dest-4" class="nt-dest">
        <?php echo $guad . $guadEndDiv; ?>
    </div>
    <div id="nt-dest-5" class="nt-dest">
        <?php echo $socorro . $socorroEndDiv; ?>
    </div>
    <div id="nt-dest-12" class="nt-dest">
        <?php echo $cortez . $cortezEndDiv; ?>
    </div>
    <div id="nt-dest-18" class="nt-dest">
        <?php echo $pulmo . $pulmoEndDiv; ?>
    </div>
    <div id="nt-dest-19" class="nt-dest">
        <?php echo $bahia . $bahiaEndDiv; ?>
    </div>
    <div id="nt-dest-20" class="nt-dest">
        <?php echo $ignacio . $ignacioEndDiv; ?>
    </div>
    <div id="nt-dest-22" class="nt-dest">
        <?php echo $magbay . $magbayEndDiv; ?>
    </div>

    <div class="nt-disclaimer">
        * Trip Prices do not include taxes or a $65 per person port and park fee payable in cash ($35 park/landing fee for San Ignacio Lagoon)
    </div>
</div>
<script type="text/javascript">
function ntShow(elId){
    var count = 0;
    var children = '';
    var removeBtn = false;
    jQuery('#' + elId).children().each( function(i,item){
        if (++count > 10)
            return;

        children += '<a href="' + jQuery(item).attr('href') + '" class="nt-triprow">' + jQuery(item).html() + '</a>';
        jQuery(item).remove();
        if (jQuery('#' + elId).children().length == 0)
            removeBtn = true;
    });
    jQuery(children).insertBefore('#' + elId + '-more');
    if (removeBtn){
        jQuery('#' + elId + '-more').remove();
    }
};
</script>
