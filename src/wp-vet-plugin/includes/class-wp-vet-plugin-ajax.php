<?php

/**
 * Handles all AJAX requests for the WP Vet Plugin.
 *
 * This class is responsible for the CRUD (Create, Read, Update, Delete) operations
 * for appointments, ensuring proper security checks (nonces and user capabilities)
 * and sending notifications via Telegram upon successful actions.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/includes
 * @author     Gemini
 */
class WPVetPlugin_Ajax {

    /**
     * AJAX handler to fetch all appointments.
     *
     * Verifies the nonce and user permissions, then queries all 'appointment'
     * custom post types. It formats them into an array suitable for FullCalendar.js
     * and returns them as a JSON object.
     */
    public function get_appointments_ajax() {
        check_ajax_referer('wp_vet_get_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('You do not have permission to view appointments.', 'wp-vet-plugin')]);
        }

        $args = [
            'post_type' => 'appointment',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        $query = new WP_Query($args);
        $events = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $owner_name = get_post_meta($post_id, '_appointment_owner_name', true);
                $pet_name = get_post_meta($post_id, '_appointment_pet_name', true);

                $events[] = [
                    'id' => $post_id,
                    'title' => sprintf(__('%s (%s)', 'wp-vet-plugin'), esc_html($pet_name), esc_html($owner_name)),
                    'start' => get_post_meta($post_id, '_appointment_date', true),
                    'ownerName' => $owner_name,
                    'petName' => $pet_name,
                    'reason' => get_the_content(),
                ];
            }
        }
        wp_reset_postdata();

        wp_send_json_success($events);
    }

    /**
     * AJAX handler to create a new appointment.
     *
     * Verifies nonce and permissions. Sanitizes and validates input data,
     * creates a new 'appointment' post, saves custom meta fields, and sends a
     * Telegram notification.
     */
    public function create_appointment_ajax() {
        check_ajax_referer('wp_vet_create_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('You do not have permission to create appointments.', 'wp-vet-plugin')]);
        }

        // Sanitize input
        $owner_name = isset($_POST['ownerName']) ? sanitize_text_field($_POST['ownerName']) : '';
        $pet_name = isset($_POST['petName']) ? sanitize_text_field($_POST['petName']) : '';
        $reason = isset($_POST['reason']) ? sanitize_textarea_field($_POST['reason']) : '';
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';

        if (empty($owner_name) || empty($pet_name) || empty($reason) || empty($date)) {
            wp_send_json_error(['message' => __('All fields are required.', 'wp-vet-plugin')]);
        }

        $post_title = sprintf('Pet: %s - Owner: %s', $pet_name, $owner_name);
        $post_id = wp_insert_post([
            'post_title' => $post_title,
            'post_content' => $reason,
            'post_status' => 'publish',
            'post_type' => 'appointment',
        ]);

        if (is_wp_error($post_id)) {
            wp_send_json_error(['message' => $post_id->get_error_message()]);
        }

        // Save meta fields
        update_post_meta($post_id, '_appointment_date', $date);
        update_post_meta($post_id, '_appointment_owner_name', $owner_name);
        update_post_meta($post_id, '_appointment_pet_name', $pet_name);

        $message = sprintf("New appointment created for *%s* (Owner: *%s*) on *%s*.", $pet_name, $owner_name, $date);
        WPVetPlugin::send_telegram_notification($message);
        
        // Return the newly created event data
        $new_event = [
            'id' => $post_id,
            'title' => sprintf(__('%s (%s)', 'wp-vet-plugin'), esc_html($pet_name), esc_html($owner_name)),
            'start' => $date,
            'ownerName' => $owner_name,
            'petName' => $pet_name,
            'reason' => $reason,
        ];

        wp_send_json_success($new_event);
    }

    /**
     * AJAX handler to update an existing appointment.
     *
     * Verifies nonce and permissions. Sanitizes input, updates the post and its meta
     * fields, and sends a Telegram notification about the change.
     */
    public function update_appointment_ajax() {
        check_ajax_referer('wp_vet_update_nonce', 'nonce');

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if (empty($id) || !current_user_can('edit_post', $id)) {
            wp_send_json_error(['message' => __('You do not have permission to edit this appointment.', 'wp-vet-plugin')]);
        }

        // Sanitize input
        $owner_name = isset($_POST['ownerName']) ? sanitize_text_field($_POST['ownerName']) : '';
        $pet_name = isset($_POST['petName']) ? sanitize_text_field($_POST['petName']) : '';
        $reason = isset($_POST['reason']) ? sanitize_textarea_field($_POST['reason']) : '';
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';

        if (empty($owner_name) || empty($pet_name) || empty($reason) || empty($date)) {
            wp_send_json_error(['message' => __('All fields are required.', 'wp-vet-plugin')]);
        }
        
        $original_post = get_post($id);
        $original_title = $original_post->post_title;
        $original_date = get_post_meta($id, '_appointment_date', true);

        $post_title = sprintf('Pet: %s - Owner: %s', $pet_name, $owner_name);
        $post_id = wp_update_post([
            'ID' => $id,
            'post_title' => $post_title,
            'post_content' => $reason,
        ]);

        if (is_wp_error($post_id)) {
            wp_send_json_error(['message' => $post_id->get_error_message()]);
        }

        // Update meta fields
        update_post_meta($id, '_appointment_date', $date);
        update_post_meta($id, '_appointment_owner_name', $owner_name);
        update_post_meta($id, '_appointment_pet_name', $pet_name);

        $message = sprintf("Appointment updated for *%s*.\nOld Date: *%s*\nNew Date: *%s*", $pet_name, $original_date, $date);
        WPVetPlugin::send_telegram_notification($message);

        wp_send_json_success();
    }

    /**
     * AJAX handler to delete an appointment.
     *
     * Verifies nonce and permissions. Deletes the specified post and sends a
     * Telegram notification.
     */
    public function delete_appointment_ajax() {
        check_ajax_referer('wp_vet_delete_nonce', 'nonce');

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if (empty($id) || !current_user_can('delete_post', $id)) {
            wp_send_json_error(['message' => __('You do not have permission to delete this appointment.', 'wp-vet-plugin')]);
        }

        $post = get_post($id);
        $pet_name = get_post_meta($id, '_appointment_pet_name', true);
        $date = get_post_meta($id, '_appointment_date', true);

        $result = wp_delete_post($id, true); // True to force delete

        if ($result) {
            $message = sprintf("Appointment DELETED for *%s* that was on *%s*.", $pet_name, $date);
            WPVetPlugin::send_telegram_notification($message);
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => __('Failed to delete appointment.', 'wp-vet-plugin')]);
        }
    }
}
