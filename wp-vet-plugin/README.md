# WP Vet Plugin

A WordPress plugin for managing a veterinary practice, built on top of FullCalendar.

## Description

WP Vet Plugin provides a simple and effective way to manage appointments for a veterinary practice. It creates a custom post type for appointments and displays them on a modern, interactive calendar.

## Installation

1.  **Download:** Download the plugin from the [GitHub repository](https://github.com/your-repo/wp-vet-plugin) or clone it directly into your `wp-content/plugins` directory.
2.  **Activate:** Activate the plugin through the 'Plugins' menu in WordPress.
3.  **Calendar Page:** The plugin automatically creates a "Calendar" page with the `[wp_vet_calendar]` shortcode. If you need to create it manually, simply add the shortcode to any page.

## Usage

### Managing Appointments

Appointments can be managed through the WordPress admin area under the "Appointments" custom post type.

### Displaying the Calendar

The calendar is displayed on the front end using the `[wp_vet_calendar]` shortcode. Simply add this shortcode to any page or post to display the calendar.

## Technical Details

*   **Custom Post Type:** The plugin registers a custom post type named `appointment` to store appointment data.
*   **FullCalendar:** The plugin uses the [FullCalendar](https://fullcalendar.io/) library to display the calendar.
*   **AJAX:** The calendar loads appointments dynamically using AJAX for improved performance.

## Future Enhancements

*   **Appointment Editing:** Allow users to edit appointments directly from the calendar.
*   **Categorization:** Add taxonomies to categorize appointments by doctor, service, or other criteria.
*   **User-Facing Forms:** Create a form for clients to request appointments from the front end.
