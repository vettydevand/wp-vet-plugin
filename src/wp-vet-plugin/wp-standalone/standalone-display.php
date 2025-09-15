<!DOCTYPE html>
<html>
<head>
    <title>WP Vet Plugin - Standalone Calendar</title>
</head>
<body>
    <?php
        // Load WordPress
        require_once('../../../../wp-load.php');

        // Display the calendar
        echo do_shortcode('[wp_vet_calendar]');
    ?>
</body>
</html>
