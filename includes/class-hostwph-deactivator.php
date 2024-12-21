<?php

/**
 * Fired during plugin deactivation
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 *
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Deactivator {

	/**
	 * Plugin deactivation functions
	 *
	 * Functions to be loaded on plugin deactivation. This actions remove roles, options and post information attached to the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$plugin_post = new HOSTWPH_Post_Type_Guest();
		
		if (get_option('hostwph_options_remove') == 'on') {
      remove_role('hostwph_role_manager');

      $hostwph_basecpt = get_posts(['fields' => 'ids', 'numberposts' => -1, 'post_type' => 'hostwph_basecpt', 'post_status' => 'any', ]);

      if (!empty($hostwph_basecpt)) {
        foreach ($hostwph_basecpt as $post_id) {
          wp_delete_post($post_id, true);
        }
      }

      foreach ($plugin_post->get_fields() as $hostwph_option) {
        delete_option($hostwph_option['id']);
      }
    }

    update_option('hostwph_options_changed', true);
	}
}