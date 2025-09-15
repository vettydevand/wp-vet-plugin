<?php

class WPVetPlugin_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('init', array($this, 'create_appointment_post_type'));
        add_action('add_meta_boxes', array($this, 'add_appointment_meta_box'));
        add_action('save_post_appointment', array($this, 'save_meta_box_data'));
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_VET_PLUGIN_URL . 'admin/css/wp-vet-plugin-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_VET_PLUGIN_URL . 'admin/js/wp-vet-plugin-admin.js', array('jquery', 'jquery-ui-datepicker'), $this->version, false);
        wp_enqueue_style('jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
    }

    public function add_appointment_meta_box() {
        add_meta_box(
            'appointment_details_meta_box',
            __('Appointment Details', 'wp-vet-plugin'),
            array($this, 'render_appointment_meta_box'),
            'appointment',
            'side',
            'core'
        );
    }

    public function render_appointment_meta_box($post) {
        wp_nonce_field('save_appointment_meta_box_data', 'appointment_meta_box_nonce');
        $appointment_date = get_post_meta($post->ID, 'appointment_date', true);
        ?>
        <p>
            <label for="appointment_date"><?php _e('Appointment Date:', 'wp-vet-plugin'); ?></label>
            <input type="date" id="appointment_date" name="appointment_date" value="<?php echo esc_attr($appointment_date); ?>" />
        </p>
        <?php
    }

    public function save_meta_box_data($post_id) {
        if (!isset($_POST['appointment_meta_box_nonce']) || !wp_verify_nonce($_POST['appointment_meta_box_nonce'], 'save_appointment_meta_box_data')) {
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
            $date_format = 'Y-m-d';
            $d = DateTime::createFromFormat($date_format, $sanitized_date);
            if ($d && $d->format($date_format) === $sanitized_date) {
                update_post_meta($post_id, 'appointment_date', $sanitized_date);
            }
        }
    }

    public function create_appointment_post_type() {
        $labels = array(
            'name' => _x('Appointments', 'Post Type General Name', 'wp-vet-plugin'),
            'singular_name' => _x('Appointment', 'Post Type Singular Name', 'wp-vet-plugin'),
            'menu_name' => __('Appointments', 'wp-vet-plugin'),
            'all_items' => __('All Appointments', 'wp-vet-plugin'),
            'add_new_item' => __('Add New Appointment', 'wp-vet-plugin'),
            'add_new' => __('Add New', 'wp-vet-plugin'),
            'new_item' => __('New Appointment', 'wp-vet-plugin'),
            'edit_item' => __('Edit Appointment', 'wp-vet-plugin'),
            'update_item' => __('Update Appointment', 'wp-vet-plugin'),
            'view_item' => __('View Appointment', 'wp-vet-plugin'),
            'search_items' => __('Search Appointment', 'wp-vet-plugin'),
            'not_found' => __('Not found', 'wp-vet-plugin'),
            'not_found_in_trash' => __('Not found in Trash', 'wp-vet-plugin'),
        );

        $args = array(
            'label' => __('Appointment', 'wp-vet-plugin'),
            'description' => __('Appointments for the veterinary clinic', 'wp-vet-plugin'),
            'labels' => $labels,
            'supports' => array('title', 'editor'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );

        register_post_type('appointment', $args);
    }
}
