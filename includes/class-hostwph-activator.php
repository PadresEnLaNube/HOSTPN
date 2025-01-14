<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostwph
 * @subpackage hostwph/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Activator {
	/**
   * Plugin activation functions
   *
   * Functions to be loaded on plugin activation. This actions creates roles, options and post information attached to the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    add_role('hostwph_role_manager', esc_html(__('Host - WPH', 'hostwph')));

    $role_admin = get_role('administrator');
    $hostwph_role_manager = get_role('hostwph_role_manager');
    $hostwph_role_manager->add_cap('upload_files'); 
    $hostwph_role_manager->add_cap('read'); 

    foreach (HOSTWPH_ROLE_CAPABILITIES as $cap_key => $cap_value) {
      $role_admin->add_cap($cap_value); 
      $hostwph_role_manager->add_cap($cap_value); 
    }

    add_role('hostwph_role_guest', esc_html(__('Guest - WPH', 'hostwph')));
    $role_subscriber = get_role('subscriber');
    $hostwph_role_guest = get_role('hostwph_role_guest');
    foreach (HOSTWPH_ROLE_CAPABILITIES as $cap_key => $cap_value) {
      $hostwph_role_guest->add_cap($cap_value); 
    }

    $post_functions = new HOSTWPH_Functions_Post();
    if (empty(get_option('hostwph_pages'))) {
      if (empty(get_option('hostwph_pages_accommodation'))) {
        $hostwph_title = __('Accommodations', 'hostwph');
        $hostwph_post_content = '<!-- wp:shortcode -->[hostwph-accommodation-list]<!-- /wp:shortcode -->';
        $hostwph_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_post_content, '', sanitize_title(esc_html($hostwph_title)), 'page', 'publish', 1);

        $hostwph_meta_value = $hostwph_id;
        if(empty(get_option('hostwph_pages'))) {
          update_option('hostwph_pages', [$hostwph_meta_value]);
        }else{
          $hostwph_option_new = get_option('hostwph_pages', true);
          $hostwph_option_new[] = $hostwph_meta_value;
          update_option('hostwph_pages', array_unique($hostwph_option_new));
        }
        
        update_option('hostwph_pages_accommodation', $hostwph_id);
        update_option('hostwph_url_main', get_permalink($hostwph_id));
      }

      if (empty(get_option('hostwph_pages_guest'))) {
        $hostwph_title = __('Guests', 'hostwph');
        $hostwph_post_content = '<!-- wp:shortcode -->[hostwph-guest-list]<!-- /wp:shortcode -->';
        $hostwph_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_post_content, '', sanitize_title(esc_html($hostwph_title)), 'page', 'publish', 1);

        $hostwph_meta_value = $hostwph_id;
        if(empty(get_option('hostwph_pages'))) {
          update_option('hostwph_pages', [$hostwph_meta_value]);
        }else{
          $hostwph_option_new = get_option('hostwph_pages', true);
          $hostwph_option_new[] = $hostwph_meta_value;
          update_option('hostwph_pages', array_unique($hostwph_option_new));
        }

        update_option('hostwph_pages_guest', $hostwph_id);
      }

      if (empty(get_option('hostwph_pages_part'))) {
        $hostwph_title = __('Parts of travelers', 'hostwph');
        $hostwph_post_content = '<!-- wp:shortcode -->[hostwph-part-list]<!-- /wp:shortcode -->';
        $hostwph_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_post_content, '', sanitize_title(esc_html($hostwph_title)), 'page', 'publish', 1);

        $hostwph_meta_value = $hostwph_id;
        if(empty(get_option('hostwph_pages'))) {
          update_option('hostwph_pages', [$hostwph_meta_value]);
        }else{
          $hostwph_option_new = get_option('hostwph_pages', true);
          $hostwph_option_new[] = $hostwph_meta_value;
          update_option('hostwph_pages', array_unique($hostwph_option_new));
        }

        update_option('hostwph_pages_part', $hostwph_id);
      }
    }

    update_option('hostwph_options_changed', true);
  }
}