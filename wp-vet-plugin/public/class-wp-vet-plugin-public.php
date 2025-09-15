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
        wp_enqueue_style('toastr', WP_VET_PLUGIN_URL . 'assets/css/toastr.min.css', array(), '1.0.0', 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_VET_PLUGIN_URL . 'public/js/wp-vet-plugin-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script('fullcalendar', WP_VET_PLUGIN_URL . 'assets/js/fullcalendar.min.js', array('jquery'), '6.1.11', true);
        wp_enqueue_script('toastr', WP_VET_PLUGIN_URL . 'assets/js/toastr.min.js', array(), '1.0.0', true);

        wp_localize_script(
            $this->plugin_name,
            'wp_vet_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('get_appointments_nonce'),
                'create_nonce' => wp_create_nonce('create_appointment_nonce'),
                'update_nonce' => wp_create_nonce('update_appointment_nonce'),
                'delete_nonce' => wp_create_nonce('delete_appointment_nonce'),
            )
        );
    }

    private function get_appointments($start_date, $end_date) {
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
                    'id' => get_the_ID(),
                );
            }
            wp_reset_postdata();
        }

        return $events;
    }

    public function get_appointments_ajax() {
        check_ajax_referer('get_appointments_nonce', 'nonce');
        
        $start_date = isset($_POST['start']) ? sanitize_text_field($_POST['start']) : null;
        $end_date = isset($_POST['end']) ? sanitize_text_field($_POST['end']) : null;

        if (!$start_date || !$end_date) {
            wp_send_json_error('Invalid date range.');
        }

        $events = $this->get_appointments($start_date, $end_date);

        wp_send_json_success($events);
    }

    public function display_calendar() {
        $calendar_html = '<div id="wp-vet-calendar-wrapper">';
        $calendar_html .= '<div id="calendar-loader" class="loader"></div>';
        $calendar_html .= '<div id="calendar"></div>';
        $calendar_html .= '<div id="calendar-legend">';
        $calendar_html .= '<h4>Calendar Legend</h4>';
        $calendar_html .= '<ul>';
        $calendar_html .= '<li><strong>Click on a day:</strong> Add a new appointment.</li>';
        $calendar_html .= '<li><strong>Drag an appointment:</strong> Change the date.</li>';
        $calendar_html .= '<li><strong>Click on an appointment:</strong> Delete it.</li>';
        $calendar_html .= '</ul>';
        $calendar_html .= '</div>';
        $calendar_html .= '</div>';

        return $calendar_html;
    }
}
