<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 */
class WPVetPlugin {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var WPVetPlugin_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @var string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     */
    public function __construct() {
        if (defined('WP_VET_PLUGIN_VERSION')) {
            $this->version = WP_VET_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'wp-vet-plugin';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_ajax_hooks();

        // The post type registration is hooked to 'init'.
        add_action('init', array($this, 'register_appointment_post_type'));
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // The class responsible for orchestrating the actions and filters of the core plugin.
        require_once WP_VET_PLUGIN_DIR . 'includes/class-wp-vet-plugin-loader.php';

        // The class responsible for defining all actions that occur in the admin area.
        require_once WP_VET_PLUGIN_DIR . 'admin/class-wp-vet-plugin-admin.php';

        // The class responsible for defining all actions that occur in the public-facing area.
        require_once WP_VET_PLUGIN_DIR . 'public/class-wp-vet-plugin-public.php';

        // The class responsible for handling AJAX requests.
        require_once WP_VET_PLUGIN_DIR . 'includes/class-wp-vet-plugin-ajax.php';

        $this->loader = new WPVetPlugin_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     */
    private function set_locale() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'wp-vet-plugin',
                false,
                dirname(plugin_basename(WP_VET_PLUGIN_FILE)) . '/languages/'
            );
        });
    }

    /**
     * Register all of the hooks related to the admin area functionality of the plugin.
     */
    private function define_admin_hooks() {
        $plugin_admin = new WPVetPlugin_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_options_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    }

    /**
     * Register all of the hooks related to the public-facing functionality of the plugin.
     */
    private function define_public_hooks() {
        $plugin_public = new WPVetPlugin_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_shortcode('wp_vet_calendar', $plugin_public, 'display_calendar');

    }

    /**
     * Register all of the hooks related to AJAX functionality of the plugin.
     */
    private function define_ajax_hooks() {
        $ajax_handler = new WPVetPlugin_Ajax();

        $this->loader->add_action('wp_ajax_get_appointments', $ajax_handler, 'get_appointments_ajax', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_get_appointments', $ajax_handler, 'get_appointments_ajax', 10, 0);

        $this->loader->add_action('wp_ajax_create_appointment', $ajax_handler, 'create_appointment_ajax');
        $this->loader->add_action('wp_ajax_update_appointment', $ajax_handler, 'update_appointment_ajax');
        $this->loader->add_action('wp_ajax_delete_appointment', $ajax_handler, 'delete_appointment_ajax');
    }
    
    /**
     * Register the 'appointment' custom post type.
     */
    public function register_appointment_post_type() {
        $labels = array(
            'name' => _x('Appointments', 'Post Type General Name', 'wp-vet-plugin'),
            'singular_name' => _x('Appointment', 'Post Type Singular Name', 'wp-vet-plugin'),
            'menu_name' => __('Appointments', 'wp-vet-plugin'),
            'all_items' => __('All Appointments', 'wp-vet-plugin'),
            'add_new_item' => __('Add New Appointment', 'wp-vet-plugin'),
            'add_new' => __('Add New', 'wp-vet-plugin'),
        );
        $args = array(
            'label' => __('Appointment', 'wp-vet-plugin'),
            'description' => __('Veterinary appointments', 'wp-vet-plugin'),
            'labels' => $labels,
            'supports' => array('title', 'author', 'custom-fields'),
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
            'show_in_rest' => true, // Enable Gutenberg editor
        );
        register_post_type('appointment', $args);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Send a notification to a Telegram channel.
     */
    public static function send_telegram_notification($message) {
        $options = get_option('wp_vet_plugin_options');
        $bot_token = isset($options['telegram_bot_token']) ? $options['telegram_bot_token'] : '';
        $chat_id = isset($options['telegram_chat_id']) ? $options['telegram_chat_id'] : '';

        if (empty($bot_token) || empty($chat_id)) {
            return;
        }

        $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
        $params = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ];

        wp_remote_post($url, array('body' => $params));
    }
}
