<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 */
class WPVetPlugin_Deactivator {

    /**
     * The main deactivation method.
     *
     * This method is called when the plugin is deactivated. It's a good place to clean up
     * options, transient data, or rewrite rules.
     */
    public static function deactivate() {
        // Flush rewrite rules to remove the rules for the 'appointment' custom post type.
        flush_rewrite_rules();
    }

}
