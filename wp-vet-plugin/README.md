# WP Vet Plugin

WP Vet Plugin is a WordPress plugin that allows veterinary clinics to manage their appointments through a calendar interface. It includes features like creating, editing, and deleting appointments, as well as sending notifications via Telegram.

## Features

*   **Interactive Calendar:** A FullCalendar-based interface for managing appointments.
*   **AJAX-Powered:** All operations are performed without page reloads for a smoother user experience.
*   **Modal-Based Editing:** A clean and modern modal for creating and editing appointments.
*   **Telegram Notifications:** Get notified in real-time about appointment changes.
*   **Responsive Design:** The calendar is optimized for both desktop and mobile devices.

## Installation

1.  **Download the Plugin:** Download the latest version of the plugin as a ZIP file.
2.  **Upload to WordPress:** In your WordPress admin panel, go to **Plugins > Add New** and click on **Upload Plugin**. Select the downloaded ZIP file and click **Install Now**.
3.  **Activate the Plugin:** Once installed, click **Activate Plugin**.
4.  **Configure Telegram (Optional):** To enable Telegram notifications, go to **Settings > WP Vet Plugin** and enter your Telegram Bot Token and Chat ID.

## Usage

Once the plugin is activated, a new custom post type called "Appointments" will be available. You can manage appointments from the WordPress admin or directly through the calendar on the front-end.

To display the calendar, you can use the `[wp_vet_calendar]` shortcode on any page or post.
