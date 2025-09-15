<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 * Its purpose is to clean up the WordPress environment, such as flushing rewrite
 * rules, to ensure the site continues to function correctly without the plugin.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/includes
 * @author     Gemini
 */
class WPVetPlugin_Deactivator {

    /**
     * The main deactivation method.
     *
     * This static method is called when the plugin is deactivated. It triggers a flush
     * of WordPress's rewrite rules to remove the permalink structure associated with
     * the 'appointment' custom post type, preventing potential 404 errors on the site.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }

}
