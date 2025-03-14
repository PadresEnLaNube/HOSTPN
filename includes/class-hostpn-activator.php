<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Activator {
	/**
   * Plugin activation functions
   *
   * Functions to be loaded on plugin activation. This actions creates roles, options and post information attached to the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    add_role('hostpn_role_manager', esc_html(__('Host - WPH', 'hostpn')));

    $role_admin = get_role('administrator');
    $hostpn_role_manager = get_role('hostpn_role_manager');
    $hostpn_role_manager->add_cap('upload_files'); 
    $hostpn_role_manager->add_cap('read'); 

    foreach (HOSTPN_ROLE_CAPABILITIES as $cap_key => $cap_value) {
      $role_admin->add_cap($cap_value); 
      $hostpn_role_manager->add_cap($cap_value); 
    }

    add_role('hostpn_role_guest', esc_html(__('Guest - WPH', 'hostpn')));
    $role_subscriber = get_role('subscriber');
    $hostpn_role_guest = get_role('hostpn_role_guest');
    foreach (HOSTPN_ROLE_CAPABILITIES as $cap_key => $cap_value) {
      $hostpn_role_guest->add_cap($cap_value); 
    }

    $post_functions = new HOSTPN_Functions_Post();
    if (empty(get_option('hostpn_pages'))) {
      if (empty(get_option('hostpn_pages_accommodation'))) {
        $hostpn_title = __('Accommodations', 'hostpn');
        $hostpn_post_content = '<!-- wp:shortcode -->[hostpn-accommodation-list]<!-- /wp:shortcode -->';
        $hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'page', 'publish', 1);

        $hostpn_meta_value = $hostpn_id;
        if(empty(get_option('hostpn_pages'))) {
          update_option('hostpn_pages', [$hostpn_meta_value]);
        }else{
          $hostpn_option_new = get_option('hostpn_pages', true);
          $hostpn_option_new[] = $hostpn_meta_value;
          update_option('hostpn_pages', array_unique($hostpn_option_new));
        }
        
        update_option('hostpn_pages_accommodation', $hostpn_id);
        update_option('hostpn_url_main', get_permalink($hostpn_id));

        if (class_exists('Polylang') && function_exists('pll_default_language')) {
          $language = pll_default_language();
          pll_set_post_language($hostpn_id, $language);
          $locales = pll_languages_list(['hide_empty' => false]);

          if (!empty($locales)) {
            foreach ($locales as $locale) {
              if ($locale != $language) {
                $hostpn_title = __('Accommodations', 'hostpn') . ' ' . $locale;
                $hostpn_post_content = '<!-- wp:shortcode -->[hostpn-accommodation-list]<!-- /wp:shortcode -->';
                $translated_hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'page', 'publish', 1);

                pll_set_post_language($translated_hostpn_id, $locale);

                pll_save_post_translations([
                  $language => $hostpn_id,
                  $locale => $translated_hostpn_id,
                ]);

                $hostpn_meta_value = $translated_hostpn_id;
                if(empty(get_option('hostpn_pages'))) {
                  update_option('hostpn_pages', [$hostpn_meta_value]);
                }else{
                  $hostpn_option_new = get_option('hostpn_pages', true);
                  $hostpn_option_new[] = $hostpn_meta_value;
                  update_option('hostpn_pages', array_unique($hostpn_option_new));
                }
              }
            }
          }
        }
      }

      if (empty(get_option('hostpn_pages_guest'))) {
        $hostpn_title = __('Guests', 'hostpn');
        $hostpn_post_content = '<!-- wp:shortcode -->[hostpn-guest-list]<!-- /wp:shortcode -->';
        $hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'page', 'publish', 1);

        $hostpn_meta_value = $hostpn_id;
        if(empty(get_option('hostpn_pages'))) {
          update_option('hostpn_pages', [$hostpn_meta_value]);
        }else{
          $hostpn_option_new = get_option('hostpn_pages', true);
          $hostpn_option_new[] = $hostpn_meta_value;
          update_option('hostpn_pages', array_unique($hostpn_option_new));
        }

        update_option('hostpn_pages_guest', $hostpn_id);

        if (class_exists('Polylang') && function_exists('pll_default_language')) {
          $language = pll_default_language();
          pll_set_post_language($hostpn_id, $language);
          $locales = pll_languages_list(['hide_empty' => false]);

          if (!empty($locales)) {
            foreach ($locales as $locale) {
              if ($locale != $language) {
                $hostpn_title = __('Guests', 'hostpn') . ' ' . $locale;
                $hostpn_post_content = '<!-- wp:shortcode -->[hostpn-guest-list]<!-- /wp:shortcode -->';
                $translated_hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'page', 'publish', 1);

                pll_set_post_language($translated_hostpn_id, $locale);

                pll_save_post_translations([
                  $language => $hostpn_id,
                  $locale => $translated_hostpn_id,
                ]);

                $hostpn_meta_value = $translated_hostpn_id;
                if(empty(get_option('hostpn_pages'))) {
                  update_option('hostpn_pages', [$hostpn_meta_value]);
                }else{
                  $hostpn_option_new = get_option('hostpn_pages', true);
                  $hostpn_option_new[] = $hostpn_meta_value;
                  update_option('hostpn_pages', array_unique($hostpn_option_new));
                }
              }
            }
          }
        }
      }

      if (empty(get_option('hostpn_pages_part'))) {
        $hostpn_title = __('Parts of travelers', 'hostpn');
        $hostpn_post_content = '<!-- wp:shortcode -->[hostpn-part-list]<!-- /wp:shortcode -->';
        $hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'page', 'publish', 1);

        $hostpn_meta_value = $hostpn_id;
        if(empty(get_option('hostpn_pages'))) {
          update_option('hostpn_pages', [$hostpn_meta_value]);
        }else{
          $hostpn_option_new = get_option('hostpn_pages', true);
          $hostpn_option_new[] = $hostpn_meta_value;
          update_option('hostpn_pages', array_unique($hostpn_option_new));
        }

        update_option('hostpn_pages_part', $hostpn_id);

        if (class_exists('Polylang') && function_exists('pll_default_language')) {
          $language = pll_default_language();
          pll_set_post_language($hostpn_id, $language);
          $locales = pll_languages_list(['hide_empty' => false]);

          if (!empty($locales)) {
            foreach ($locales as $locale) {
              if ($locale != $language) {
                $hostpn_title = __('Parts of travelers', 'hostpn') . ' ' . $locale;
                $hostpn_post_content = '<!-- wp:shortcode -->[hostpn-part-list]<!-- /wp:shortcode -->';
                $translated_hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'page', 'publish', 1);

                pll_set_post_language($translated_hostpn_id, $locale);

                pll_save_post_translations([
                  $language => $hostpn_id,
                  $locale => $translated_hostpn_id,
                ]);

                $hostpn_meta_value = $translated_hostpn_id;
                if(empty(get_option('hostpn_pages'))) {
                  update_option('hostpn_pages', [$hostpn_meta_value]);
                }else{
                  $hostpn_option_new = get_option('hostpn_pages', true);
                  $hostpn_option_new[] = $hostpn_meta_value;
                  update_option('hostpn_pages', array_unique($hostpn_option_new));
                }
              }
            }
          }
        }
      }
    }

    update_option('hostpn_options_changed', true);
  }
}