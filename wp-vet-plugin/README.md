
# WP Vet Plugin

A WordPress plugin for managing a veterinary practice, built on top of FullCalendar.

## Description

WP Vet Plugin provides a simple and effective way to manage appointments for a veterinary practice. It creates a custom post type for appointments and displays them on a modern, interactive calendar on your WordPress site. This plugin is designed to be easy to use for both administrators managing the practice and clients viewing the schedule.

---

## User Documentation

This section is for site visitors or clients who interact with the calendar.

### Viewing the Appointment Calendar

You can view the practice's appointment schedule on the "Calendar" page of this website.

- **Navigate to the Calendar:** Simply go to the "Calendar" page in the site's menu.
- **View Appointments:** The calendar displays all scheduled appointments. You can navigate through months to see past and future bookings.
- **Appointment Details:** Each entry on the calendar shows the title of the appointment.

---

## Administrator Documentation

This section is for site administrators who will manage the plugin and the appointments.

### Installation

1.  **Download:** Download the plugin from the [GitHub repository](https://github.com/your-repo/wp-vet-plugin) or clone it directly into your `wp-content/plugins` directory.
2.  **Activate:** In your WordPress dashboard, navigate to **Plugins > Installed Plugins**, find "WP Vet Plugin," and click **Activate**.
3.  **Automatic Page Creation:** Upon activation, the plugin automatically creates a new page named "Calendar" and places the required `[wp_vet_calendar]` shortcode on it. This page will be publicly visible. If you wish to remove it, you can delete it from the **Pages** section of WordPress.

### Managing Appointments

All appointments are managed through a custom post type called "Appointments" in the WordPress admin area.

#### Creating a New Appointment

1.  In the WordPress dashboard, go to **Appointments > Add New**.
2.  **Title:** Add a title for the appointment (e.g., "Fluffy - Annual Check-up").
3.  **Date:** In the "Appointment Details" box on the right, select the `appointment_date`. This is the date that will be used to place the event on the calendar.
4.  **Content:** (Optional) Add any additional details about the appointment in the main content editor.
5.  **Publish:** Click the **Publish** button to save the appointment. It will now appear on the calendar.

#### Editing or Deleting an Appointment

1.  In the WordPress dashboard, go to **Appointments**.
2.  You will see a list of all existing appointments.
3.  Hover over an appointment to see the **Edit** and **Trash** links.
4.  Click **Edit** to modify the appointment details or **Trash** to delete it.

### Displaying the Calendar Manually

If you accidentally delete the "Calendar" page or want to display the calendar on a different page, you can use the following shortcode:

`[wp_vet_calendar]`

Simply create a new page or edit an existing one, and insert this shortcode into the content editor.

## Technical Details

*   **Custom Post Type:** The plugin registers a custom post type named `appointment`.
*   **Custom Fields:** It uses a custom meta field `appointment_date` to store the date for each appointment.
*   **FullCalendar:** The front-end calendar is rendered using the [FullCalendar](https://fullcalendar.io/) JavaScript library.
*   **AJAX:** To ensure fast loading times, the calendar loads appointments dynamically from the server using AJAX. It only fetches the appointments for the date range currently being viewed.

## Future Enhancements

*   **Appointment Editing:** Allow users to edit appointments directly from the calendar.
*   **Categorization:** Add taxonomies to categorize appointments by doctor, service, or other criteria.
*   **User-Facing Forms:** Create a form for clients to request appointments from the front end.
