<?php

class WPVetPlugin_Public {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('wp_vet_calendar', array($this, 'display_calendar'));

        add_action('wp_ajax_get_appointments', array($this, 'get_appointments_ajax'));
        add_action('wp_ajax_nopriv_get_appointments', array($this, 'get_appointments_ajax'));
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_VET_PLUGIN_URL . 'public/css/wp-vet-plugin-public.css', array(), $this->version, 'all');
        wp_enqueue_style('fullcalendar', WP_VET_PLUGIN_URL . 'assets/css/fullcalendar.min.css', array(), '6.1.11', 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_VET_PLUGIN_URL . 'public/js/wp-vet-plugin-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script('fullcalendar', WP_VET_PLUGIN_URL . 'assets/js/fullcalendar.min.js', array('jquery'), '6.1.11', true);

        wp_localize_script(
            $this->plugin_name,
            'wp_vet_ajax',
            array('ajax_url' => admin_url('admin-ajax.php'))
        );
    }
    
    public function get_appointments_ajax() {
        $start_date = sanitize_text_field($_POST['start']);
        $end_date = sanitize_text_field($_POST['end']);

        $events = array();
        $args = array(
            'post_type' => 'appointment',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'appointment_date',
                    'value' => array($start_date, $end_date),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ),
            ),
        );

        $appointments_query = new WP_Query($args);

        if ($appointments_query->have_posts()) {
            while ($appointments_query->have_posts()) {
                $appointments_query->the_post();
                
                $appointment_date = get_post_meta(get_the_ID(), 'appointment_date', true);
                if (empty($appointment_date)) {
                    $appointment_date = get_the_date('Y-m-d');
                }

                $events[] = array(
                    'title' => get_the_title(),
                    'start' => $appointment_date,
                );
            }
            wp_reset_postdata();
        }

        wp_send_json_success($events);
    }


    public function display_calendar() {
        return '<div id="calendar"></div>';
    }
}
