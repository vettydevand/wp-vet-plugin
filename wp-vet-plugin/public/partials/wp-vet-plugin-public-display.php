<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin,
 * including the calendar container, the legend, and the modal for appointments.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/public/partials
 * @author     Gemini
 */
?>

<!-- Main Calendar Wrapper -->
<div id="wp-vet-calendar-wrapper">
    <div id="calendar-loader" class="loader"></div>
    <div id="calendar"></div>

    <!-- Calendar Legend -->
    <div id="calendar-legend">
        <h4><?php _e('Calendar Legend', 'wp-vet-plugin'); ?></h4>
        <ul>
            <li><strong><?php _e('Click on a day:', 'wp-vet-plugin'); ?></strong> <?php _e('Add a new appointment.', 'wp-vet-plugin'); ?></li>
            <li><strong><?php _e('Drag an appointment:', 'wp-vet-plugin'); ?></strong> <?php _e('Change the date.', 'wp-vet-plugin'); ?></li>
            <li><strong><?php _e('Click on an appointment:', 'wp-vet-plugin'); ?></strong> <?php _e('Edit or delete it.', 'wp-vet-plugin'); ?></li>
        </ul>
    </div>
</div>

<!-- Appointment Modal -->
<div id="appointment-modal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2 id="modal-title"><?php _e('Appointment Details', 'wp-vet-plugin'); ?></h2>
        <form id="appointment-form">
            <input type="hidden" id="appointment-id" />
            
            <div class="form-group">
                <label for="owner-name"><?php _e('Owner Name', 'wp-vet-plugin'); ?></label>
                <input type="text" id="owner-name" required>
            </div>
            
            <div class="form-group">
                <label for="pet-name"><?php _e('Pet Name', 'wp-vet-plugin'); ?></label>
                <input type="text" id="pet-name" required>
            </div>
            
            <div class="form-group">
                <label for="appointment-reason"><?php _e('Reason for Visit', 'wp-vet-plugin'); ?></label>
                <textarea id="appointment-reason" rows="3" required></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" id="save-appointment" class="button button-primary"><?php _e('Save Appointment', 'wp-vet-plugin'); ?></button>
                <button type="button" id="delete-appointment" class="button button-danger" style="display: none;"><?php _e('Delete Appointment', 'wp-vet-plugin'); ?></button>
            </div>
        </form>
    </div>
</div>
