<?php

class WPVetPlugin_Ajax {

    public function create_appointment_ajax() {
        check_ajax_referer('create_appointment_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('You do not have permission to create appointments.');
        }

        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';

        if (empty($title) || empty($date)) {
            wp_send_json_error('Title and date are required.');
        }

        $post_data = array(
            'post_title' => $title,
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'appointment',
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            wp_send_json_error($post_id->get_error_message());
        }

        update_post_meta($post_id, 'appointment_date', $date);

        $message = "New appointment created: *{$title}* on {$date}";
        WPVetPlugin::send_telegram_notification($message);

        wp_send_json_success(array('id' => $post_id));
    }

    public function update_appointment_ajax() {
        check_ajax_referer('update_appointment_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('You do not have permission to update appointments.');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';

        if (empty($id) || empty($title) || empty($date)) {
            wp_send_json_error('ID, title and date are required.');
        }
        
        $original_post = get_post($id);
        $original_title = $original_post->post_title;
        $original_date = get_post_meta($id, 'appointment_date', true);


        $post_data = array(
            'ID' => $id,
            'post_title' => $title,
        );

        $post_id = wp_update_post($post_data);

        if (is_wp_error($post_id)) {
            wp_send_json_error($post_id->get_error_message());
        }

        update_post_meta($id, 'appointment_date', $date);
        
        $message = "Appointment updated:\nFrom: *{$original_title}* on {$original_date}\nTo: *{$title}* on {$date}";
        WPVetPlugin::send_telegram_notification($message);

        wp_send_json_success();
    }

    public function delete_appointment_ajax() {
        check_ajax_referer('delete_appointment_nonce', 'nonce');

        if (!current_user_can('delete_posts')) {
            wp_send_json_error('You do not have permission to delete appointments.');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if (empty($id)) {
            wp_send_json_error('Appointment ID is required.');
        }
        
        $post = get_post($id);
        $title = $post->post_title;
        $date = get_post_meta($id, 'appointment_date', true);

        $result = wp_delete_post($id, true);

        if ($result) {
            $message = "Appointment deleted: *{$title}* on {$date}";
            WPVetPlugin::send_telegram_notification($message);
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to delete appointment.');
        }
    }
}
