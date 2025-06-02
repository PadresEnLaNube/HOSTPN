<?php
/**
 * Load the plugin no private Ajax functions.
 *
 * Load the plugin no private Ajax functions to be executed in background.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Ajax_Nopriv {
	/**
	 * Load the plugin templates.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_ajax_nopriv_server() {
    if (array_key_exists('hostpn_ajax_nopriv_type', $_POST)) {
      if (!array_key_exists('hostpn_ajax_nopriv_nonce', $_POST)) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_ajax_nopriv_error_required',
          'error_content' => esc_html(__('Security check failed: Nonce is required.', 'hostpn')),
        ]);

        exit;
      }

      if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nopriv_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_ajax_nopriv_error_invalid',
          'error_content' => esc_html(__('Security check failed: Invalid nonce.', 'hostpn')),
        ]);

        exit;
      }

  		$hostpn_ajax_nopriv_type = HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_ajax_nopriv_type']));
      
      $hostpn_ajax_keys = !empty($_POST['hostpn_ajax_keys']) ? array_map(function($key) {
        $sanitized_key = wp_unslash($key);
        return array(
          'id' => sanitize_key($sanitized_key['id']),
          'node' => sanitize_key($sanitized_key['node']),
          'type' => sanitize_key($sanitized_key['type'])
        );
      }, wp_unslash($_POST['hostpn_ajax_keys'])) : [];

      $hostpn_key_value = [];

      if (!empty($hostpn_ajax_keys)) {
        foreach ($hostpn_ajax_keys as $hostpn_key) {
          if (strpos($hostpn_key['id'], '[]') !== false) {
            $hostpn_clear_key = str_replace('[]', '', $hostpn_key['id']);
            ${$hostpn_clear_key} = $hostpn_key_value[$hostpn_clear_key] = [];

            if (!empty($_POST[$hostpn_clear_key])) {
              $unslashed_array = wp_unslash($_POST[$hostpn_clear_key]);
              
              if (!is_array($unslashed_array)) {
                $unslashed_array = array($unslashed_array);
              }

              $sanitized_array = array_map(function($value) use ($hostpn_key) {
                return HOSTPN_Forms::hostpn_sanitizer(
                  $value,
                  $hostpn_key['node'],
                  $hostpn_key['type'],
                  $hostpn_key['field_config']
                );
              }, $unslashed_array);
              
              foreach ($sanitized_array as $multi_key => $multi_value) {
                $final_value = !empty($multi_value) ? $multi_value : '';
                ${$hostpn_clear_key}[$multi_key] = $hostpn_key_value[$hostpn_clear_key][$multi_key] = $final_value;
              }
            } else {
              ${$hostpn_clear_key} = '';
              $hostpn_key_value[$hostpn_clear_key][$multi_key] = '';
            }
          } else {
            $sanitized_key = sanitize_key($hostpn_key['id']);
            $unslashed_value = !empty($_POST[$sanitized_key]) ? wp_unslash($_POST[$sanitized_key]) : '';
            
            $hostpn_key_id = !empty($unslashed_value) ? 
              HOSTPN_Forms::hostpn_sanitizer(
                $unslashed_value, 
                $hostpn_key['node'], 
                $hostpn_key['type'],
                $hostpn_key['field_config']
              ) : '';
            
              ${$hostpn_key['id']} = $hostpn_key_value[$hostpn_key['id']] = $hostpn_key_id;
          }
        }
      }

      switch ($hostpn_ajax_nopriv_type) {
        case 'hostpn_form_save':
          $hostpn_form_type = !empty($_POST['hostpn_form_type']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_form_type'])) : '';

          if (!empty($hostpn_key_value) && !empty($hostpn_form_type)) {
            $hostpn_form_id = !empty($_POST['hostpn_form_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_form_id'])) : 0;
            $hostpn_form_subtype = !empty($_POST['hostpn_form_subtype']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_form_subtype'])) : '';
            $user_id = !empty($_POST['hostpn_form_user_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_form_user_id'])) : 0;
            $post_id = !empty($_POST['hostpn_form_post_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_form_post_id'])) : 0;
            $post_type = !empty($_POST['hostpn_form_post_type']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_form_post_type'])) : '';

            if (($hostpn_form_type == 'user' && empty($user_id) && !in_array($hostpn_form_subtype, ['user_alt_new'])) || ($hostpn_form_type == 'post' && (empty($post_id) && !(!empty($hostpn_form_subtype) && in_array($hostpn_form_subtype, ['post_new', 'post_edit'])))) || ($hostpn_form_type == 'option' && !is_user_logged_in())) {
              session_start();

              $_SESSION['hostpn_form'] = [];
              $_SESSION['hostpn_form'][$hostpn_form_id] = [];
              $_SESSION['hostpn_form'][$hostpn_form_id]['form_type'] = $hostpn_form_type;
              $_SESSION['hostpn_form'][$hostpn_form_id]['values'] = $hostpn_key_value;

              if (!empty($post_id)) {
                $_SESSION['hostpn_form'][$hostpn_form_id]['post_id'] = $post_id;
              }

              echo wp_json_encode(['error_key' => 'hostpn_form_save_error_unlogged', ]);exit;
            }else{
              switch ($hostpn_form_type) {
                case 'user':
                  if (!in_array($hostpn_form_subtype, ['user_alt_new'])) {
                    if (empty($user_id)) {
                      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
                        $user_login = !empty($_POST['user_login']) ? hostpn_Forms::hostpn_sanitizer(wp_unslash($_POST['user_login'])) : 0;
                        $user_password = !empty($_POST['user_password']) ? hostpn_Forms::hostpn_sanitizer(wp_unslash($_POST['user_password'])) : 0;
                        $user_email = !empty($_POST['user_email']) ? hostpn_Forms::hostpn_sanitizer(wp_unslash($_POST['user_email'])) : 0;

                        $user_id = HOSTPN_Functions_User::insert_user($user_login, $user_password, $user_email);
                      }
                    }

                    if (!empty($user_id)) {
                      foreach ($hostpn_key_value as $hostpn_key => $hostpn_value) {
                        // Skip action and ajax type keys
                        if (in_array($hostpn_key, ['action', 'hostpn_ajax_nopriv_type'])) {
                          continue;
                        }

                        // Ensure option name is prefixed with hostpn_
                        if (strpos($hostpn_key, 'hostpn_') !== 0) {
                          $hostpn_key = 'hostpn_' . $hostpn_key;
                        }

                        update_user_meta($user_id, $hostpn_key, $hostpn_value);
                      }
                    }
                  }

                  do_action('hostpn_form_save', $user_id, $hostpn_key_value, $hostpn_form_type, $hostpn_form_subtype);
                  break;
                case 'post':
                  if (empty($hostpn_form_subtype) || in_array($hostpn_form_subtype, ['post_new', 'post_edit'])) {
                    if (empty($post_id)) {
                      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
                        $post_functions = new HOSTPN_Functions_Post();
                        $title = !empty($_POST[$post_type . '_title']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST[$post_type . '_title'])) : '';
                        $description = !empty($_POST[$post_type . '_description']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST[$post_type . '_description'])) : '';
                        
                        $post_id = $post_functions->hostpn_insert_post($title, $description, '', sanitize_title($title), $post_type, 'publish', get_current_user_id());
                      }
                    }

                    if (!empty($post_id)) {
                      foreach ($hostpn_key_value as $hostpn_key => $hostpn_value) {
                        if ($hostpn_key == $post_type . '_title') {
                          wp_update_post([
                            'ID' => $post_id,
                            'post_title' => esc_html($hostpn_value),
                          ]);
                        }

                        if ($hostpn_key == $post_type . '_description') {
                          wp_update_post([
                            'ID' => $post_id,
                            'post_content' => esc_html($hostpn_value),
                          ]);
                        }

                        // Skip action and ajax type keys
                        if (in_array($hostpn_key, ['action', 'hostpn_ajax_nopriv_type'])) {
                          continue;
                        }

                        // Ensure option name is prefixed with hostpn_
                        if (strpos($hostpn_key, 'hostpn_') !== 0) {
                          $hostpn_key = 'hostpn_' . $hostpn_key;
                        }

                        update_post_meta($post_id, $hostpn_key, $hostpn_value);
                      }
                    }
                  }

                  do_action('hostpn_form_save', $post_id, $hostpn_key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type);
                  break;
                case 'option':
                  if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
                    $hostpn_settings = new hostpn_Settings();
                    $hostpn_options = $hostpn_settings->hostpn_get_options();
                    $hostpn_allowed_options = array_keys($hostpn_options);

                    foreach ($hostpn_key_value as $hostpn_key => $hostpn_value) {
                      // Skip action and ajax type keys
                      if (in_array($hostpn_key, ['action', 'hostpn_ajax_nopriv_type'])) {
                        continue;
                      }

                      // Ensure option name is prefixed with hostpn_
                      if (strpos($hostpn_key, 'hostpn_') !== 0) {
                        $hostpn_key = 'hostpn_' . $hostpn_key;
                      }

                      // Only update if option is in allowed options list
                      if (in_array($hostpn_key, $hostpn_allowed_options)) {
                        update_option($hostpn_key, $hostpn_value);
                      }
                        
                      update_option($hostpn_key, $hostpn_value);
                    }
                  }

                  do_action('hostpn_form_save', 0, $hostpn_key_value, $hostpn_form_type, $hostpn_form_subtype);
                  break;
              }

              $popup_close = in_array($hostpn_form_subtype, ['post_new', 'post_edit', 'user_alt_new']) ? true : '';
              $update_list = in_array($hostpn_form_subtype, ['post_new', 'post_edit', 'user_alt_new']) ? true : '';
              $check = in_array($hostpn_form_subtype, ['post_check', 'post_uncheck']) ? $hostpn_form_subtype : '';
              
              if ($update_list && !empty($post_type)) {
                switch ($post_type) {
                  case 'hostpn_accommodation':
                    $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
                    $update_html = $plugin_post_type_accommodation->hostpn_accommodation_list();
                    break;
                  case 'hostpn_guest':
                    $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
                    $update_html = $plugin_post_type_guest->hostpn_guest_list();
                    break;
                  case 'hostpn_part':
                    $plugin_post_type_part = new HOSTPN_Post_Type_Part();
                    $update_html = $plugin_post_type_part->hostpn_part_list();
                    break;
                }
              }else{
                $update_html = '';
              }

              echo wp_json_encode(['error_key' => '', 'popup_close' => $popup_close, 'update_list' => $update_list, 'update_html' => $update_html, 'check' => $check]);exit;
            }
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_form_save_error', ]);exit;
          }
          break;
      }

      echo wp_json_encode(['error_key' => '', ]);exit;
  	}
  }
}