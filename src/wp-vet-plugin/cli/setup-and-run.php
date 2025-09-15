<?php

require_once('wp-load.php');

// Create appointments
$appointments = array(
    array(
        'post_title' => 'Appointment with Dr. Smith',
        'post_content' => 'Annual check-up for Fluffy.',
        'post_status' => 'publish',
        'post_type' => 'appointment',
        'meta_input' => array(
            'appointment_date' => date('Y-m-d', strtotime('+1 day')),
        ),
    ),
    array(
        'post_title' => 'Appointment with Dr. Jones',
        'post_content' => 'Vaccination for Sparky.',
        'post_status' => 'publish',
        'post_type' => 'appointment',
        'meta_input' => array(
            'appointment_date' => date('Y-m-d', strtotime('+3 day')),
        ),
    ),
    array(
        'post_title' => 'Appointment with Dr. Evil',
        'post_content' => 'Laser beam alignment for Mr. Bigglesworth.',
        'post_status' => 'publish',
        'post_type' => 'appointment',
        'meta_input' => array(
            'appointment_date' => date('Y-m-d', strtotime('+5 day')),
        ),
    ),
);

foreach ($appointments as $appointment_data) {
    wp_insert_post($appointment_data);
}

// Create calendar page
$page_data = array(
    'post_title' => 'Calendar',
    'post_content' => '[wp_vet_calendar]',
    'post_status' => 'publish',
    'post_type' => 'page',
);

wp_insert_post($page_data);

echo "Appointments and calendar page created!";

?>
