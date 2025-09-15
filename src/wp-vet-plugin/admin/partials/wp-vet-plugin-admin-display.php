<?php
/**
 * Provides the admin area view for the plugin's settings page.
 *
 * This file is used to markup the admin-facing aspects of the plugin, specifically
 * the form that allows administrators to configure the Telegram integration.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/admin/partials
 * @author     Gemini
 */
?>

<div class="wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form action="options.php" method="post">
        <?php
        /**
         * WordPress function that outputs security-related hidden fields (nonce, action, option_page) for the settings form.
         * It corresponds to the option group name registered with register_setting().
         */
        settings_fields('wp_vet_plugin_options_group');

        /**
         * WordPress function that prints out all settings sections added to a particular settings page.
         * It corresponds to the page slug passed to add_settings_section() and add_settings_field().
         */
        do_settings_sections('wp-vet-plugin-settings');

        /**
         * WordPress function that outputs a submit button.
         */
        submit_button();
        ?>
    </form>

</div>
