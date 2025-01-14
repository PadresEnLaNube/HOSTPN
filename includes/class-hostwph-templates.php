<?php
/**
 * Load the plugin templates.
 *
 * Loads the plugin template files getting them from the templates folders inside common, public or admin, depending on access requirements.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Templates {
	/**
	 * Load the plugin templates.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_templates() {
		require_once HOSTWPH_DIR . 'templates/hostwph-footer.php';
		require_once HOSTWPH_DIR . 'templates/hostwph-popups.php';
	}
}