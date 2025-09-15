<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 */
?>

<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form action="options.php" method="post">
        <?php
        // This prints out all hidden setting fields
        settings_fields('wp_vet_plugin_options');
        do_settings_sections('wp_vet_plugin_options');
        submit_button();
        ?>
    </form>
</div>
