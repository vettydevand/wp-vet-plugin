<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 */
?>

<div id="wp-vet-calendar-wrapper">
    <div id="calendar-loader" class="loader"></div>
    <div id="calendar"></div>
    <div id="calendar-legend">
        <h4><?php _e('Calendar Legend', 'wp-vet-plugin'); ?></h4>
        <ul>
            <li><strong><?php _e('Click on a day:', 'wp-vet-plugin'); ?></strong> <?php _e('Add a new appointment.', 'wp-vet-plugin'); ?></li>
            <li><strong><?php _e('Drag an appointment:', 'wp-vet-plugin'); ?></strong> <?php _e('Change the date.', 'wp-vet-plugin'); ?></li>
            <li><strong><?php _e('Click on an appointment:', 'wp-vet-plugin'); ?></strong> <?php _e('Edit or delete it.', 'wp-vet-plugin'); ?></li>
        </ul>
    </div>
</div>
