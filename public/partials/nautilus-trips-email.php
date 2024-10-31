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

<div style="font-family:Arial,Helvetica,sans-serif;">
<p>Dear <?php echo esc_html($firstName); ?>,<br>
Thank you for booking a trip with us and Nautilus Dive Adventures.
We will be in touch to follow up with the details of your booking and get you ready for your Nautilus adventure.</p>

<p>Please see the details of your booking below. If you have any questions or concerns, please contact:
    <a href="mailto:<?php echo esc_attr($admin_email); ?>"><?php echo esc_html($admin_email); ?></a></p>

<?php echo wp_kses_post($response->conf_html); ?>
</div>
