<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin admin area. This file also includes all of the dependencies used by the plugin, registers the activation and deactivation functions, and defines a function that starts the plugin.
 *
 * @link              padresenlanube.com/
 * @since             1.0.0
 * @package           HOSTPN
 *
 * @wordpress-plugin
 * Plugin Name:       Hospedajes España - HOSTPN
 * Plugin URI:        https://padresenlanube.com/plugins/hostpn/
 * Description:       Allow you to ask for, save and send the information required by spanish Royal Decree 933/2021, of October 26.
 * Version:           1.0.0
 * Author:            Padres en la Nube
 * Author URI:        https://padresenlanube.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hostpn
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
define('HOSTPN_VERSION', '1.0.4');
define('HOSTPN_DIR', plugin_dir_path(__FILE__));
define('HOSTPN_URL', plugin_dir_url(__FILE__));
define('HOSTPN_CPTS', [
	'guest' => 'Guest',
	'accommodation' => 'Accommodation',
	'part' => 'Part of traveller',
]);

/**
 * Plugin role capabilities
 */
define('HOSTPN_ROLE_CAPABILITIES', [
	// Post editing capabilities
	'edit_post' => 'edit_hostpn_basecpt',
	'edit_posts' => 'edit_hostpn_basecpt',
	'edit_private_posts' => 'edit_private_hostpn_basecpt',
	'edit_published_posts' => 'edit_published_hostpn_basecpt',
	'edit_others_posts' => 'edit_others_hostpn_basecpt',
	'publish_posts' => 'publish_hostpn_basecpt',
	
	// Post reading capabilities
	'read_post' => 'read_hostpn_basecpt',
	'read_private_posts' => 'read_private_hostpn_basecpt',
	
	// Post deletion capabilities
	'delete_post' => 'delete_hostpn_basecpt',
	'delete_posts' => 'delete_hostpn_basecpt',
	'delete_private_posts' => 'delete_private_hostpn_basecpt',
	'delete_published_posts' => 'delete_published_hostpn_basecpt',
	'delete_others_posts' => 'delete_others_hostpn_basecpt',
	
	// Media capabilities
	'upload_files' => 'upload_files',
	
	// Taxonomy capabilities
	'manage_terms' => 'manage_hostpn_category',
	'edit_terms' => 'edit_hostpn_category',
	'delete_terms' => 'delete_hostpn_category',
	'assign_terms' => 'assign_hostpn_category',
	
	// Options capabilities
	'manage_options' => 'manage_hostpn_options'
]);

// Build the KSES array first
$hostpn_kses = [
	// Basic text elements
	'div' => ['id' => [], 'class' => []],
	'section' => ['id' => [], 'class' => []],
	'article' => ['id' => [], 'class' => []],
	'aside' => ['id' => [], 'class' => []],
	'footer' => ['id' => [], 'class' => []],
	'header' => ['id' => [], 'class' => []],
	'main' => ['id' => [], 'class' => []],
	'nav' => ['id' => [], 'class' => []],
	'p' => ['id' => [], 'class' => []],
	'span' => ['id' => [], 'class' => []],
	'small' => ['id' => [], 'class' => []],
	'em' => [],
	'strong' => [],
	'br' => [],

	// Headings
	'h1' => ['id' => [], 'class' => []],
	'h2' => ['id' => [], 'class' => []],
	'h3' => ['id' => [], 'class' => []],
	'h4' => ['id' => [], 'class' => []],
	'h5' => ['id' => [], 'class' => []],
	'h6' => ['id' => [], 'class' => []],

	// Lists
	'ul' => ['id' => [], 'class' => []],
	'ol' => ['id' => [], 'class' => []],
	'li' => [
		'id' => [],
		'class' => [],
	],

	// Links and media
	'a' => [
		'id' => [],
		'class' => [],
		'href' => [],
		'title' => [],
		'target' => [],
		'data-hostpn-ajax-type' => [],
		'data-hostpn-popup-id' => [],
	],
	'img' => [
		'id' => [],
		'class' => [],
		'src' => [],
		'alt' => [],
		'title' => [],
	],
	'i' => [
		'id' => [], 
		'class' => [], 
		'title' => []
	],

	// Forms and inputs
	'form' => [
		'id' => [],
		'class' => [],
		'action' => [],
		'method' => [],
	],
	'input' => [
		'name' => [],
		'id' => [],
		'class' => [],
		'type' => [],
		'checked' => [],
		'multiple' => [],
		'disabled' => [],
		'value' => [],
		'placeholder' => [],
		'data-hostpn-parent' => [],
		'data-hostpn-parent-option' => [],
		'data-hostpn-type' => [],
		'data-hostpn-subtype' => [],
		'data-hostpn-user-id' => [],
		'data-hostpn-post-id' => [],
	],
	'select' => [
		'name' => [],
		'id' => [],
		'class' => [],
		'type' => [],
		'checked' => [],
		'multiple' => [],
		'disabled' => [],
		'value' => [],
		'placeholder' => [],
		'data-placeholder' => [],
		'data-hostpn-parent' => [],
		'data-hostpn-parent-option' => [],
	],
	'option' => [
		'name' => [],
		'id' => [],
		'class' => [],
		'disabled' => [],
		'selected' => [],
		'value' => [],
		'placeholder' => [],
	],
	'textarea' => [
		'name' => [],
		'id' => [],
		'class' => [],
		'type' => [],
		'multiple' => [],
		'disabled' => [],
		'value' => [],
		'placeholder' => [],
		'data-hostpn-parent' => [],
		'data-hostpn-parent-option' => [],
	],
	'label' => [
		'id' => [],
		'class' => [],
		'for' => [],
	],
];

// Add custom data attributes for each CPT
foreach (HOSTPN_CPTS as $cpt_key => $cpt_value) {
	$hostpn_kses['li']['data-hostpn_' . $cpt_key . '-id'] = [];
}

// Now define the constant with the complete array
define('HOSTPN_KSES', $hostpn_kses);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hostpn-activator.php
 */
function hostpn_activation_hook() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-hostpn-activator.php';
	HOSTPN_Activator::hostpn_activate();
	
	// Clear any previous state
	delete_option('hostpn_redirecting');
	
	// Set transient only if it doesn't exist
	if (!get_transient('hostpn_just_activated')) {
		set_transient('hostpn_just_activated', true, 30);
	}
}

// Register activation hook
register_activation_hook(__FILE__, 'hostpn_activation_hook');

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hostpn-deactivator.php
 */
function hostpn_deactivation_cleanup() {
	delete_option('hostpn_redirecting');
}
register_deactivation_hook(__FILE__, 'hostpn_deactivation_cleanup');

/**
 * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-hostpn.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks, then kicking off the plugin from this point in the file does not affect the page life cycle.
 *
 * @since    1.0.0
 */
function hostpn_run() {
	$plugin = new HOSTPN();
	$plugin->hostpn_run();
}

// Initialize the plugin on init hook instead of plugins_loaded
add_action('init', 'hostpn_run', 0);