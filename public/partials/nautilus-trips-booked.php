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

<div id="nt-trip-block">
    <h2>Thanks for booking with us!</h2>

    <p>You should receive a copy of this Booking Request Confirmation in your email shortly.</p>

    <p>We will be in touch to follow up with the details of your booking and get you ready for your Nautilus adventure.</p>

    <p>Please see the details of your booking below. If you have any questions or concerns about your reservation please contact
        <a href="mailto:<?php echo esc_attr($admin_email); ?>"><?php echo esc_html($admin_email); ?></a> to discuss your dive trip.</p>

    <?php echo wp_kses_post($response->conf_html); ?>

    <p>We are excited for you to book a trip with us. You can keep
        <a href="<?php echo get_page_link( absint($page_id) ); ?>">looking at trips</a> or use our main menu
        to find something else.</p>

    <div><a href="<?php echo get_page_link( absint($page_id) ); ?>" class="nt-back-button">&lt; Back to Trips</a></div>
</div>
