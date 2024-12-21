<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin admin area. This file also includes all of the dependencies used by the plugin, registers the activation and deactivation functions, and defines a function that starts the plugin.
 *
 * @link              wordpress-heroes.com/
 * @since             1.0.0
 * @package           HOSTWPH
 *
 * @wordpress-plugin
 * Plugin Name:       Hospedajes España - WPH
 * Plugin URI:        https://wordpress-heroes.com/plugins/hostwph/
 * Description:       Allow you to require, save and send the information required by spanish Royal Decree 933/2021, of October 26.
 * Version:           1.0.0
 * Author:            Wordpress-Heroes
 * Author URI:        https://wordpress-heroes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hostwph
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('HOSTWPH_VERSION', '1.0.56');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hostwph-activator.php
 */
function hostwph_activate() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-hostwph-activator.php';
	HOSTWPH_Activator::activate();
}
register_activation_hook(__FILE__, 'hostwph_activate');

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hostwph-deactivator.php
 */
function hostwph_deactivate() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-hostwph-deactivator.php';
	HOSTWPH_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'hostwph_deactivate');

/**
 * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-hostwph.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks, then kicking off the plugin from this point in the file does not affect the page life cycle.
 *
 * @since    1.0.0
 */
function hostwph_run() {
	$plugin = new HOSTWPH();
	$plugin->run();

	require_once plugin_dir_path(__FILE__) . 'includes/class-hostwph-activator.php';
	HOSTWPH_Activator::activate();
}

hostwph_run();