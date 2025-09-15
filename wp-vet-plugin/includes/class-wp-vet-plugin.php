<?php

class WPVetPlugin {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        $this->load_dependencies();
        $this->setup_admin_hooks();
        $this->setup_public_hooks();
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'wp-vet-plugin',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    private function load_dependencies() {
        require_once WP_VET_PLUGIN_DIR . 'admin/class-wp-vet-plugin-admin.php';
        require_once WP_VET_PLUGIN_DIR . 'public/class-wp-vet-plugin-public.php';
    }

    private function setup_admin_hooks() {
        $admin = new WPVetPlugin_Admin('wp-vet-plugin', '1.0.0');
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));
    }

    private function setup_public_hooks() {
        $public = new WPVetPlugin_Public('wp-vet-plugin', '1.0.0');
        add_action('wp_enqueue_scripts', array($public, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($public, 'enqueue_scripts'));
    }
}
