<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and registers all hooks for the public-facing
 * side of the site. This class is responsible for enqueuing all the necessary
 * styles and scripts, and for rendering the main calendar view via a shortcode.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/public
 * @author     Gemini
 */
class WPVetPlugin_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * This method enqueues the main plugin stylesheet as well as the stylesheets
     * for third-party libraries like FullCalendar, Toastr, and the custom modal.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style('fullcalendar', WP_VET_PLUGIN_URL . 'assets/css/fullcalendar.min.css', array(), '6.1.11', 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * This method enqueues the main plugin script and third-party libraries.
     * It also uses wp_localize_script to pass PHP data (like AJAX URL, security nonces,
     * and translatable strings) to the main JavaScript file.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_VET_PLUGIN_URL . 'dist/bundle.js', array('jquery'), $this->version, true);

        // Pass data to the script.
        wp_localize_script(
            $this->plugin_name,
            'wp_vet_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wp_vet_nonce'),
                'get_nonce' => wp_create_nonce('wp_vet_get_nonce'),
                'create_nonce' => wp_create_nonce('wp_vet_create_nonce'),
                'update_nonce' => wp_create_nonce('wp_vet_update_nonce'),
                'delete_nonce' => wp_create_nonce('wp_vet_delete_nonce'),
            )
        );
        
        // Pass translatable strings to the script
        wp_localize_script(
            $this->plugin_name,
            'wp_vet_strings',
            array(
                'new_appointment'  => __('New Appointment', 'wp-vet-plugin'),
                'edit_appointment' => __('Edit Appointment', 'wp-vet-plugin'),
                'success'          => __('Operation completed successfully.', 'wp-vet-plugin'),
                'error'            => __('An unexpected error occurred.', 'wp-vet-plugin'),
                'ajax_error'       => __('AJAX request failed.', 'wp-vet-plugin'),
                'confirm_delete'   => __('Are you sure you want to delete this appointment?', 'wp-vet-plugin'),
            )
        );
    }

    /**
     * The callback for the [wp_vet_calendar] shortcode.
     *
     * This method uses output buffering to capture the HTML markup from the
     * partial file and return it as a string for WordPress to display on the page.
     *
     * @since     1.0.0
     * @return    string    The HTML for the calendar display.
     */
    public function display_calendar() {
        ob_start();
        include_once WP_VET_PLUGIN_DIR . 'public/partials/wp-vet-plugin-public-display.php';
        return ob_get_clean();
    }
}
