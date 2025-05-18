<?php

/**
 * Fired during plugin deactivation
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 *
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Deactivator {

	/**
	 * Plugin deactivation functions
	 *
	 * Functions to be loaded on plugin deactivation. This actions remove roles, options and post information attached to the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function hostpn_deactivate() {
		$plugin_post = new HOSTPN_Post_Type_Guest();
		
		if (get_option('hostpn_options_remove') == 'on') {
      remove_role('hostpn_role_manager');
      remove_role('hostpn_role_guest');

      $hostpn_basecpt = get_posts(['fields' => 'ids', 'numberposts' => -1, 'post_type' => 'hostpn_basecpt', 'post_status' => 'any', ]);

      if (!empty($hostpn_basecpt)) {
        foreach ($hostpn_basecpt as $post_id) {
          wp_delete_post($post_id, true);
        }
      }

      foreach ($plugin_post->get_fields() as $hostpn_option) {
        delete_option($hostpn_option['id']);
      }
    }

    update_option('hostpn_options_changed', true);
	}
}