<?php

class WPVetPlugin_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('add_meta_boxes', array($this, 'add_appointment_metabox'));
        add_action('save_post_appointment', array($this, 'save_appointment_metabox_data'));
    }

    public function add_appointment_metabox() {
        add_meta_box(
            'appointment_details',
            'Appointment Details',
            array($this, 'render_appointment_metabox'),
            'appointment',
            'normal',
            'high'
        );
    }

    public function render_appointment_metabox($post) {
        wp_nonce_field('save_appointment_metabox_data', 'appointment_metabox_nonce');

        $appointment_date = get_post_meta($post->ID, 'appointment_date', true);
        if (empty($appointment_date)) {
            $appointment_date = date('Y-m-d');
        }

        echo '<label for="appointment_date">Appointment Date:</label>';
        echo '<input type="date" id="appointment_date" name="appointment_date" value="' . esc_attr($appointment_date) . '" />';
    }

    public function save_appointment_metabox_data($post_id) {
        if (!isset($_POST['appointment_metabox_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['appointment_metabox_nonce'], 'save_appointment_metabox_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['appointment_date'])) {
            $sanitized_date = sanitize_text_field($_POST['appointment_date']);
            update_post_meta($post_id, 'appointment_date', $sanitized_date);
        }

        if (isset($_POST['post_title'])) {
            $sanitized_title = sanitize_text_field($_POST['post_title']);
            $post_data = array(
                'ID' => $post_id,
                'post_title' => $sanitized_title,
            );
            wp_update_post($post_data);
        }
    }
}
