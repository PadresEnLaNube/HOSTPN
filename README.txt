=== Hospedajes España - HOSTPN ===
Contributors: felixmartinez, hamlet237
Donate link: https://padresenlanube.com/
Tags: hospedajes España, Spain, hosting, check-in, host register
Requires at least: 3.5
Tested up to: 6.8
Stable tag: 1.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow you to ask for, save and send the information required by spanish Royal Decree 933/2021, of October 26.

== Description ==

The Hospedajes España - HOSTPN application has been created to allow the sending of the information required by Royal Decree 933/2021, of October 26, which establishes the documentary and information registration obligations of natural or legal persons who carry out lodging and motor vehicle rental activities.


== Credits ==
This plugin stands on the shoulders of giants

Tooltipster v4.2.8 - A rockin' custom tooltip jQuery plugin
Developed by Caleb Jacob and Louis Ameline
MIT license
https://calebjacob.github.io/tooltipster/
https://github.com/calebjacob/tooltipster/blob/master/dist/js/tooltipster.main.js
https://github.com/calebjacob/tooltipster/blob/master/dist/css/tooltipster.main.css

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