<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the public-facing side of the site.
 */
class WPVetPlugin_Public {

    private $plugin_name;
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_VET_PLUGIN_URL . 'public/css/wp-vet-plugin-public.css', array(), $this->version, 'all');
        wp_enqueue_style('fullcalendar', WP_VET_PLUGIN_URL . 'assets/css/fullcalendar.min.css', array(), '6.1.11', 'all');
        wp_enqueue_style('toastr', WP_VET_PLUGIN_URL . 'assets/css/toastr.min.css', array(), '1.0.0', 'all');
        wp_enqueue_style('modal', WP_VET_PLUGIN_URL . 'assets/css/modal.css', array(), '1.0.0', 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_VET_PLUGIN_URL . 'public/js/wp-vet-plugin-public.js', array('jquery'), $this->version, true);
        wp_enqueue_script('fullcalendar', WP_VET_PLUGIN_URL . 'assets/js/fullcalendar.min.js', array('jquery'), '6.1.11', true);
        wp_enqueue_script('toastr', WP_VET_PLUGIN_URL . 'assets/js/toastr.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('modal', WP_VET_PLUGIN_URL . 'assets/js/modal.js', array('jquery'), '1.0.0', true);

        wp_localize_script(
            $this->plugin_name,
            'wp_vet_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('get_appointments_nonce'),
                'create_nonce' => wp_create_nonce('create_appointment_nonce'),
                'update_nonce' => wp_create_nonce('update_appointment_nonce'),
                'delete_nonce' => wp_create_nonce('delete_appointment_nonce'),
            )
        );
    }

    /**
     * The callback for the [wp_vet_calendar] shortcode.
     *
     * @return string The HTML for the calendar.
     */
    public function display_calendar() {
        ob_start();
        include_once WP_VET_PLUGIN_DIR . 'public/partials/wp-vet-plugin-public-display.php';
        return ob_get_clean();
    }
}
