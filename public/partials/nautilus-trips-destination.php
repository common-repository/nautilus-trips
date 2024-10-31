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

// First Socorro trips of the year are combo but we don't want to display that as
// the primary description for all Socorro trips so we override that here.
if ($trip->sea == 3){
    $trip->sea = 5;
    $trip->brief = 'Socorro - giant mantas, sharks and dolphins';
    $trip->tripType = 'Socorro Expeditions';
}

if (isset($descriptions[ $trip->sea ]))
    $description = $descriptions[ $trip->sea ];
else
    $description = $trip->itinerary;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="nt-details">
    <img src="<?php echo esc_url($trip->img); ?>" alt="" class="nt-dest-img" />
    <div class="nt-dest-details">
        <div class="triptype"><?php echo esc_html($trip->brief); ?> - <?php echo esc_html($trip->tripType); ?></div>
        <?php /*<div class="triphead"></div>*/ ?>
        <div class="tripinfo"><?php echo esc_html($description); ?></div>
    </div>
</div>
