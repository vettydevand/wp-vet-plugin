# WP Vet Plugin

A simple WordPress plugin to manage veterinary appointments with a calendar view.

## Description

This plugin provides a shortcode `[wp_vet_calendar]` to display a calendar on any page. The calendar allows users to view, create, update, and delete appointments.

## Features

*   AJAX-powered calendar for a smooth user experience.
*   FullCalendar integration for a rich and interactive interface.
*   Custom post type for appointments.
*   Telegram notifications for new appointments.
*   Settings page to configure the Telegram Bot Token and Chat ID.

## Installation

1.  Download the latest release from the [releases page](https://github.com/gemini/wp-vet-plugin/releases).
2.  Upload the ZIP file to your WordPress site via the "Plugins > Add New > Upload Plugin" page.
3.  Activate the plugin.
4.  Go to "Settings > WP Vet Plugin" to configure the Telegram notifications (optional).
5.  Add the `[wp_vet_calendar]` shortcode to any page or post.

## Development

This project uses `pnpm` for package management and `webpack` to bundle JavaScript and CSS assets.

### Prerequisites

*   [Node.js](https://nodejs.org/) (v14 or later)
*   [pnpm](https://pnpm.io/)

### Build Steps

1.  **Install dependencies:**

    ```bash
    pnpm install
    ```

2.  **Run the build process:**

    ```bash
    pnpm run build
    ```

    This will compile the assets and place the final `bundle.js` file in the `dist/` directory.
