<?php
/**
 * Load the plugin templates.
 *
 * Loads the plugin template files getting them from the templates folders inside common, public or admin, depending on access requirements.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Templates {
	/**
	 * Load the plugin templates.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_templates() {
		require_once HOSTPN_DIR . 'templates/hostpn-footer.php';
		require_once HOSTPN_DIR . 'templates/hostpn-popups.php';
	}
}