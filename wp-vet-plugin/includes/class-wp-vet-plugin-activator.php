<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class WPVetPlugin_Activator {

    /**
     * The main activation method.
     *
     * This method is called when the plugin is activated. It should be used for one-time
     * setup, like registering post types and flushing rewrite rules.
     */
    public static function activate() {
        // Post type registration should be part of the main plugin class,
        // but we call it here to ensure it's available before flushing rules.
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-vet-plugin.php';
        
        // Ensure the post type is registered before flushing.
        // Note: The CPT registration is hooked to 'init', so we can't call it directly.
        // We will register it temporarily here to be safe.
        
        $plugin = new WPVetPlugin();
        $plugin->register_appointment_post_type();

        // Flush rewrite rules to ensure the 'appointment' post type URLs work correctly.
        flush_rewrite_rules();
    }
}
