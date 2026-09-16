=== Hospedajes España - HOSTPN ===
Contributors: felixmartinez, hamlet237
Donate link: https://padresenlanube.com/
Tags: hospedajes España, Spain, hosting, check-in, host register
Requires at least: 3.5
Tested up to: 7.0
Stable tag: 1.0.130
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow you to ask for, save and send the information required by spanish Royal Decree 933/2021, of October 26.

== Description ==

The Hospedajes España - HOSTPN application has been created to allow the sending of the information required by Royal Decree 933/2021, of October 26, which establishes the documentary and information registration obligations of natural or legal persons who carry out lodging and motor vehicle rental activities.

**Key features:**

* **Accommodation, Guest, Room & Contract management** — full CRUD for all property-related Custom Post Types with admin columns, meta boxes and AJAX-powered dashboard lists.
* **Part of Traveller (Parte de Viajero)** — create, edit, export to XML compliant with the SES/Hospedajes format, and download CSV reports filtered by year.
* **Multi-type rental contracts** — translatable contract templates for room rentals, tourist accommodations and long-stay leases with shortcode-based field resolution, inventory annexes and per-room share links.
* **Public contract view** — shared-link contract pages with signature pad, language selector and PDF download.
* **Frontend management panel** — tabbed interface on single accommodation pages for logged-in users with Contracts, Financial management, Cleaning task tracker and Inventory inspection checklist tabs (role-based visibility).
* **Cleaning task tracker** — per-room cleaning checklist with area-based tasks, date tracking, notes and save/mark-all-done actions.
* **Checkout inventory inspection** — generate a per-room inspection checklist from accommodation and room inventory items, mark OK/Issue per item with comments, save the inspection to the contract, and send the report by email to the landlord.
* **Create WordPress user from Guest** — one-click WP user creation from any Guest record, copying all personal data and sending a welcome email; auto-links to existing users when the email already matches.
* **Room availability waitlist** — visitors can subscribe to occupied rooms and receive automatic email notifications when the room becomes available.
* **Guest registration notifications** — configurable email notifications on guest registration to selected users and external addresses, with resend capability from both the dashboard list and the admin post list.
* **Financial management** — income/expense tracking per accommodation with admin dashboard and read-only frontend view.
* **Internationalization** — all UI strings are translatable; ships with Spanish (es_ES) translations and supports runtime locale switching for contracts.

== Credits ==
This plugin stands on the shoulders of giants

Owl Carousel v2.3.4
Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
Copyright 2013-2018 David Deutsch
https://owlcarousel2.github.io/OwlCarousel2/
https://github.com/OwlCarousel2/OwlCarousel2/blob/develop/dist/owl.carousel.js

Trumbowyg v2.27.3 - A lightweight WYSIWYG editor
alex-d.github.io/Trumbowyg/
License MIT - Author : Alexandre Demode (Alex-D)
https://github.com/Alex-D/Trumbowyg/blob/develop/src/ui/sass/trumbowyg.scss
https://github.com/Alex-D/Trumbowyg/blob/develop/src/ui/sass/trumbowyg.scss
https://github.com/Alex-D/Trumbowyg/blob/develop/src/trumbowyg.js


== Installation ==

1. Upload `hostpn.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I install the Hospedajes España - HOSTPN plugin? =

To install the Hospedajes España - HOSTPN plugin, you can either upload the plugin files to the /wp-content/plugins/hostpn directory, or install the plugin through the WordPress plugins screen directly. After uploading, activate the plugin through the 'Plugins' screen in WordPress.

= Can I customize the look and feel of my listings? =

Yes, you can customize the appearance of your listings by modifying the CSS styles provided in the plugin. Additionally, you can enqueue your own custom styles to override the default plugin styles.

= Where can I find the uncompressed source code for the plugin's JavaScript and CSS files? =

You can find the uncompressed source code for the JavaScript and CSS files in the src directory of the plugin. You can also visit our GitHub repository for the complete source code.

= How do I add a new Custom Post Type to my site? =

To add a new Custom Post Type, go to the 'Apartment / Part of Traveller / Guest' section in the WordPress dashboard and click on 'Add New'. Fill in the required details for your Custom Post Type, including any custom fields provided by the plugin. Once you're done, click 'Publish' to make the Custom Post Type live on your site.

= Can I use this plugin with any WordPress theme? =

Yes, the Hospedajes España - HOSTPN plugin is designed to be compatible with any WordPress theme. However, some themes may require additional customization to ensure the plugin's styles integrate seamlessly.

= Is the plugin translation-ready? =

Yes, the Hospedajes España - HOSTPN plugin is fully translation-ready. You can use translation plugins such as Loco Translate to translate the plugin into your desired language.

= How do I update the plugin? =

You can update the plugin through the WordPress plugins screen just like any other plugin. When a new version is available, you will see an update notification, and you can click 'Update Now' to install the latest version.

= How do I backup my Custom Post Types before updating the plugin? =

To backup your Custom Post Types, you can export your posts and custom post types from the WordPress Tools > Export menu. Choose the 'Host' post type and download the export file. You can import this file later if needed.

= How do I add ratings and reviews to my Custom Post Types? =

The plugin don't include a built-in ratings and reviews system yet. You can integrate third-party plugins that offer these features or customize the plugin to include them.

= How do I optimize my Custom Post Types for SEO? =

To optimize your Custom Post Types for SEO, ensure that you use relevant keywords in your host titles, descriptions, and content. You can also use SEO plugins like Yoast SEO to further enhance your host posts' search engine visibility.

= How do I get support for the Hospedajes España - HOSTPN plugin? =

For support, you can visit the plugin's support forum on the WordPress.org website or contact the plugin author directly through our contact information info@padresenlanube.com.

= Is the plugin compatible with the latest version of WordPress? =

The Hospedajes España - HOSTPN plugin is tested with the latest version of WordPress. However, it is always a good practice to check for any compatibility issues before updating WordPress or the plugin.

= How do I uninstall the plugin? =

To uninstall the plugin, go to the 'Plugins' screen in WordPress, find the Hospedajes España - HOSTPN plugin, and click 'Deactivate'. After deactivating, you can click 'Delete' to remove the plugin and its files from your site. Note that this will not delete your custom post types, but you should back up your data before uninstalling any plugin.


== Changelog ==

= 1.0.130 =

- Add frontend management panel with tabbed interface (Contracts, Financial management, Cleaning, Inventory) for logged-in admins and guests on single accommodation pages
- Add cleaning task tracker per room with checklist areas (Bedroom, Bathroom, Kitchen, Common areas, Other), date tracking, notes and save/mark-all-done functionality via AJAX
- Add checkout inventory inspection checklist that loads all inventory items (accommodation + room level) merged by category, with OK/Issue status and per-item comments
- Add save inspection and send-inspection-by-email actions for checkout inventory checklist, storing data in contract or room meta
- Add send_inventory_inspection_email() method in HOSTPN_Notifications that builds an HTML table with item statuses and sends it to the landlord email
- Add frontend financial dashboard loading via AJAX within the management tabs panel (read-only view of the admin financial dashboard)
- Move the standalone contracts block from the bottom of the single accommodation template into the Contracts tab of the new management panel
- Add "Create user" button in the Guest CPT meta box that creates a WordPress user from the guest's data, copies all guest meta to the user, links guest to user, and sends a welcome email via wp_new_user_notification()
- Add "Create user" / "View user" menu item in the guest list dropdown on the plugin dashboard, showing "View user" with a link to the user profile when a WP user is already linked
- Add hostpn_guest_create_user AJAX handler that verifies nonce and permissions, validates guest email, checks for existing linked user, and either creates a new subscriber user or links to an existing WP user with matching email
- Register wp_ajax_hostpn_guest_create_user action in the core plugin class
- Add JS click handler for .hostpn-guest-create-user supporting both meta box buttons and dropdown links with loading state and dynamic UI update on success
- Add i18n strings for guest user creation: creating_user, user_created, user_linked, user_already_exists, create_user, view_user
- Add AJAX cases hostpn_cleaning_load, hostpn_cleaning_save, hostpn_inventory_checklist_load, hostpn_inventory_inspection_save, hostpn_inventory_inspection_email, and hostpn_financial_frontend_load in the AJAX server
- Change hostpn_collect_inventory_items() visibility from private to public in HOSTPN_Contract_Templates to allow access from the management tabs inventory checklist
- Remove all debug console.log and console.error statements from hostpn-rooms-block.js
- Add management tabs CSS (hostpn-management-tabs.css) with styles for tab buttons, cleaning areas grid, inventory checklist, room selectors, action buttons, success/error messages, and responsive layout
- Add management tabs JS (hostpn-management-tabs.js) with tab switching, cleaning CRUD, inventory inspection checklist generation, and financial dashboard AJAX loading
- Add management tabs PHP template (hostpn-management-tabs.php) with role-based tab visibility: admins see all tabs, guests see only Contracts and Inventory (if assigned to a room)
- Update Spanish translation files (.po, .mo, .l10n.php) and translation template (.pot) with all new translatable strings

= 1.0.2 =

Remove README.md file and update README.txt to reflect version 1.0.2. 
Enhance address field maxlength for guest and user input forms from 20 to 40 characters for better data handling. 
Improve AJAX handling and nonce verification for enhanced security and consistency across the codebase.
Restore .gitignore file
Add accommodation, guest, and part search functionality with unified JavaScript handling. Enhance CSS for search input styling and transitions. Update PHP files to include search components in post type templates.
Update version to 1.0.2, enhance AJAX response handling in JavaScript for improved error management and HTML content processing. Refactor form input structure in PHP for better consistency and readability, including the addition of a new input display wrapper method.
Remove hostpn.zip file and update AJAX handling in JavaScript and PHP files for improved nonce usage and sanitization. Refactor form input structure for accommodations, guests, and parts, ensuring consistency and enhanced security across the codebase.
Revert version number to 1.0.0 in hostpn.php and class-hostpn.php. Update AJAX handling in class-hostpn-ajax and class-hostpn-ajax-nopriv to sanitize input keys for improved security and consistency.
Refactor README.txt for clarity on listing customization and update terminology from 'host' to 'Custom Post Type'. Enhance nonce verification in AJAX handling across multiple classes to improve security. Update JavaScript and PHP files for consistency in nonce usage and improve code readability.
Update README.txt to reflect plugin requirements and terminology changes, including version requirement update to 3.5, and replace references from 'recipe' to 'host' throughout the document for consistency.
Update version to 1.0.2, enhance form input structure with improved readability, and enforce maxlength attribute for address fields. Refactor script registration methods for accommodations, guests, and parts to ensure proper loading and printing of scripts.
Rename plugin from 'Hospedajes España - WPH' to 'Hospedajes España - HOSTPN', update related references, and remove unused assets. Adjust initialization to use 'init' hook. Update language files and ensure consistency in function naming across the codebase.
Refactor user references from 'wph' to 'pn' across JavaScript and PHP files, updating class names, selectors, and function names for consistency. Adjusted form handling and popup integration.
Update guest and part post types to order by post date in descending order; modify popup template comments for clarity.
WPH to PN

= 1.0.0 =

Hello hostings world!