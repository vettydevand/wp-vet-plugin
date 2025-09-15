<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 * Its primary role is to flush WordPress rewrite rules to ensure the custom
 * post type permalinks are recognized.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/includes
 * @author     Gemini
 */
class WPVetPlugin_Activator {

    /**
     * The main activation method.
     *
     * This method is called when the plugin is activated. It flushes the rewrite rules
     * to ensure that the URL structure for the 'appointment' custom post type is
     * recognized by WordPress immediately.
     *
     * The 'appointment' CPT itself is registered on the 'init' hook within the main plugin class,
     * so we do not need to register it here.
     *
     * @since    1.0.0
     */
    public static function activate() {
        flush_rewrite_rules();
    }
}
