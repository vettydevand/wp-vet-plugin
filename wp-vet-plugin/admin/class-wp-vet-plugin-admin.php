<?php

class WPVetPlugin_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('init', array($this, 'create_appointment_post_type'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_VET_PLUGIN_URL . 'admin/css/wp-vet-plugin-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('fullcalendar', WP_VET_PLUGIN_URL . 'assets/css/fullcalendar.min.css', array(), '6.1.11', 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_VET_PLUGIN_URL . 'admin/js/wp-vet-plugin-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('fullcalendar', WP_VET_PLUGIN_URL . 'assets/js/fullcalendar.min.js', array('jquery'), '6.1.11', true);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Vet Appointments',
            'Vet Appointments',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_calendar_page'),
            'dashicons-calendar',
            6
        );
    }

    public function display_calendar_page() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
            <div id="calendar"></div>
        </div>
        <?php
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
            'label' => __('appointment', 'wp-vet-plugin'),
            'description' => __('Appointments for the veterinary clinic', 'wp-vet-plugin'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'custom-fields'),
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
