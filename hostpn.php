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
define('HOSTPN_VERSION', '1.0.11');
define('HOSTPN_DIR', plugin_dir_path(__FILE__));
define('HOSTPN_URL', plugin_dir_url(__FILE__));
define('HOSTPN_CPTS', [
	'guest' => 'Guest',
	'accommodation' => 'Accommodation',
	'part' => 'Part of traveller',
]);

/**
 * Accommodation features configuration
 */
define('HOSTPN_ACCOMMODATION_FEATURES', [
	'kitchen' => [
		'title' => 'Kitchen',
		'icon' => 'kitchen',
		'features' => [
			'hostpn_dining_table' => 'Dining Table',
			'hostpn_coffee_maker' => 'Coffee Maker',
			'hostpn_cleaning_products' => 'Cleaning Products',
			'hostpn_toaster' => 'Toaster',
			'hostpn_stovetops' => 'Stovetops',
			'hostpn_oven' => 'Oven',
			'hostpn_kitchen_utensils' => 'Kitchen Utensils',
			'hostpn_electric_kettle' => 'Electric Kettle',
			'hostpn_kitchen' => 'Kitchen',
			'hostpn_washing_machine' => 'Washing Machine',
			'hostpn_dishwasher' => 'Dishwasher',
			'hostpn_microwave' => 'Microwave',
			'hostpn_refrigerator' => 'Refrigerator',
			'hostpn_kitchen_area' => 'Kitchen Area',
			'hostpn_blender' => 'Blender',
			'hostpn_food_processor' => 'Food Processor',
			'hostpn_juicer' => 'Juicer',
			'hostpn_grill' => 'Grill',
			'hostpn_steamer' => 'Steamer',
			'hostpn_slow_cooker' => 'Slow Cooker',
			'hostpn_air_fryer' => 'Air Fryer',
			'hostpn_wine_cooler' => 'Wine Cooler',
			'hostpn_freezer' => 'Freezer',
			'hostpn_kitchen_island' => 'Kitchen Island',
			'hostpn_pantry' => 'Pantry',
			'hostpn_garbage_disposal' => 'Garbage Disposal',
			'hostpn_water_filter' => 'Water Filter',
			'hostpn_ice_maker' => 'Ice Maker',
			'hostpn_breakfast_bar' => 'Breakfast Bar',
			'hostpn_wine_rack' => 'Wine Rack',
			'hostpn_spice_rack' => 'Spice Rack',
			'hostpn_knife_block' => 'Knife Block'
		]
	],
	'room' => [
		'title' => 'Room',
		'icon' => 'bed',
		'features' => [
			'hostpn_bed_linen' => 'Bed Linen',
			'hostpn_wardrobe' => 'Wardrobe',
			'hostpn_nightstand' => 'Nightstand',
			'hostpn_dresser' => 'Dresser',
			'hostpn_mirror' => 'Mirror',
			'hostpn_armchair' => 'Armchair',
			'hostpn_reading_lamp' => 'Reading Lamp',
			'hostpn_bedside_lamp' => 'Bedside Lamp',
			'hostpn_ceiling_lamp' => 'Ceiling Lamp',
			'hostpn_floor_lamp' => 'Floor Lamp',
			'hostpn_table_lamp' => 'Table Lamp',
			'hostpn_wall_lamp' => 'Wall Lamp',
			'hostpn_curtains' => 'Curtains',
			'hostpn_blinds' => 'Blinds',
			'hostpn_shutters' => 'Shutters',
			'hostpn_blackout_curtains' => 'Blackout Curtains',
			'hostpn_sheer_curtains' => 'Sheer Curtains',
			'hostpn_thermal_curtains' => 'Thermal Curtains',
			'hostpn_soundproofing' => 'Soundproofing',
			'hostpn_air_conditioning' => 'Air Conditioning',
			'hostpn_heating' => 'Heating',
			'hostpn_ceiling_fan' => 'Ceiling Fan',
			'hostpn_floor_fan' => 'Floor Fan',
			'hostpn_table_fan' => 'Table Fan',
			'hostpn_wall_fan' => 'Wall Fan',
			'hostpn_humidifier' => 'Humidifier',
			'hostpn_dehumidifier' => 'Dehumidifier',
			'hostpn_air_purifier' => 'Air Purifier',
			'hostpn_ionizer' => 'Ionizer',
			'hostpn_ozone_generator' => 'Ozone Generator',
			'hostpn_uv_sterilizer' => 'UV Sterilizer',
			'hostpn_hepa_filter' => 'HEPA Filter',
			'hostpn_carbon_filter' => 'Carbon Filter',
			'hostpn_electrostatic_filter' => 'Electrostatic Filter',
			'hostpn_photocatalytic_filter' => 'Photocatalytic Filter',
			'hostpn_plasma_filter' => 'Plasma Filter',
			'hostpn_uv_filter' => 'UV Filter',
			'hostpn_ion_filter' => 'Ion Filter',
			'hostpn_ozone_filter' => 'Ozone Filter'
		]
	],
	'bathroom' => [
		'title' => 'Bathroom',
		'icon' => 'bathroom',
		'features' => [
			'hostpn_private_bathroom' => 'Private Bathroom',
			'hostpn_shared_bathroom' => 'Shared Bathroom',
			'hostpn_hair_dryer' => 'Hair Dryer',
			'hostpn_shampoo' => 'Shampoo',
			'hostpn_conditioner' => 'Conditioner',
			'hostpn_body_wash' => 'Body Wash',
			'hostpn_soap' => 'Soap',
			'hostpn_toilet_paper' => 'Toilet Paper',
			'hostpn_towels' => 'Towels',
			'hostpn_bath_mat' => 'Bath Mat',
			'hostpn_shower_curtain' => 'Shower Curtain',
			'hostpn_bath_tub' => 'Bath Tub',
			'hostpn_shower' => 'Shower',
			'hostpn_bidet' => 'Bidet',
			'hostpn_toilet' => 'Toilet',
			'hostpn_sink' => 'Sink',
			'hostpn_mirror' => 'Mirror',
			'hostpn_medicine_cabinet' => 'Medicine Cabinet',
			'hostpn_vanity' => 'Vanity',
			'hostpn_heating' => 'Heating',
			'hostpn_ventilation' => 'Ventilation'
		]
	],
	'living_area' => [
		'title' => 'Living Area',
		'icon' => 'weekend',
		'features' => [
			'hostpn_sofa' => 'Sofa',
			'hostpn_armchair' => 'Armchair',
			'hostpn_coffee_table' => 'Coffee Table',
			'hostpn_side_table' => 'Side Table',
			'hostpn_tv_stand' => 'TV Stand',
			'hostpn_bookcase' => 'Bookcase',
			'hostpn_magazine_rack' => 'Magazine Rack',
			'hostpn_throw_pillows' => 'Throw Pillows',
			'hostpn_blanket' => 'Blanket',
			'hostpn_rug' => 'Rug',
			'hostpn_floor_lamp' => 'Floor Lamp',
			'hostpn_table_lamp' => 'Table Lamp',
			'hostpn_wall_lamp' => 'Wall Lamp',
			'hostpn_ceiling_lamp' => 'Ceiling Lamp',
			'hostpn_chandelier' => 'Chandelier',
			'hostpn_pendant_lamp' => 'Pendant Lamp',
			'hostpn_track_lighting' => 'Track Lighting',
			'hostpn_recessed_lighting' => 'Recessed Lighting',
			'hostpn_sconces' => 'Sconces',
			'hostpn_fireplace' => 'Fireplace',
			'hostpn_wood_stove' => 'Wood Stove',
			'hostpn_pellet_stove' => 'Pellet Stove',
			'hostpn_gas_stove' => 'Gas Stove',
			'hostpn_electric_stove' => 'Electric Stove',
			'hostpn_propane_stove' => 'Propane Stove',
			'hostpn_kerosene_stove' => 'Kerosene Stove',
			'hostpn_ethanol_stove' => 'Ethanol Stove',
			'hostpn_bioethanol_stove' => 'Bioethanol Stove',
			'hostpn_gel_stove' => 'Gel Stove',
			'hostpn_paraffin_stove' => 'Paraffin Stove',
			'hostpn_denatured_alcohol_stove' => 'Denatured Alcohol Stove',
			'hostpn_methylated_spirits_stove' => 'Methylated Spirits Stove',
			'hostpn_isopropyl_alcohol_stove' => 'Isopropyl Alcohol Stove',
			'hostpn_rubbing_alcohol_stove' => 'Rubbing Alcohol Stove'
		]
	],
	'audiovisual' => [
		'title' => 'Audiovisual',
		'icon' => 'tv',
		'features' => [
			'hostpn_tv' => 'TV',
			'hostpn_smart_tv' => 'Smart TV',
			'hostpn_4k_tv' => '4K TV',
			'hostpn_8k_tv' => '8K TV',
			'hostpn_oled_tv' => 'OLED TV',
			'hostpn_qled_tv' => 'QLED TV',
			'hostpn_led_tv' => 'LED TV',
			'hostpn_lcd_tv' => 'LCD TV',
			'hostpn_plasma_tv' => 'Plasma TV',
			'hostpn_crt_tv' => 'CRT TV',
			'hostpn_projector' => 'Projector',
			'hostpn_screen' => 'Screen',
			'hostpn_sound_system' => 'Sound System'
		]
	]
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