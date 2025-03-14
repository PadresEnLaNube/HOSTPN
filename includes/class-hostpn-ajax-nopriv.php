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
      if (array_key_exists('ajax_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ajax_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode(['error_key' => 'hostpn_nonce_error', ]);exit();
      }

  		$hostpn_ajax_nopriv_type = HOSTPN_Forms::sanitizer($_POST['hostpn_ajax_nopriv_type']);
      $ajax_keys = !empty($_POST['ajax_keys']) ? wp_unslash($_POST['ajax_keys']) : [];
      $key_value = [];

      if (!empty($ajax_keys)) {
        foreach ($ajax_keys as $key) {
          if (strpos($key['id'], '[]') !== false) {
            $clear_key = str_replace('[]', '', $key['id']);
            ${$clear_key} = $key_value[$clear_key] = [];

            if (!empty($_POST[$clear_key])) {
              foreach (wp_unslash($_POST[$clear_key]) as $multi_key => $multi_value) {
                $final_value = !empty($_POST[$clear_key][$multi_key]) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST[$clear_key][$multi_key]), $key['node'], $key['type']) : '';
                ${$clear_key}[$multi_key] = $key_value[$clear_key][$multi_key] = $final_value;
              }
            }else{
              ${$clear_key} = '';
              $key_value[$clear_key][$multi_key] = '';
            }
          }else{
            $key_id = !empty($_POST[$key['id']]) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST[$key['id']]), $key['node'], $key['type']) : '';
            ${$key['id']} = $key_value[$key['id']] = $key_id;
          }
        }
      }

      switch ($hostpn_ajax_nopriv_type) {
        case 'hostpn_form_save':
          $hostpn_form_type = !empty($_POST['hostpn_form_type']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['hostpn_form_type'])) : '';

          if (!empty($key_value) && !empty($hostpn_form_type)) {
            $hostpn_form_id = !empty($_POST['hostpn_form_id']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['hostpn_form_id'])) : 0;
            $hostpn_form_subtype = !empty($_POST['hostpn_form_subtype']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['hostpn_form_subtype'])) : '';
            $user_id = !empty($_POST['hostpn_form_user_id']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['hostpn_form_user_id'])) : 0;
            $post_id = !empty($_POST['hostpn_form_post_id']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['hostpn_form_post_id'])) : 0;
            $post_type = !empty($_POST['hostpn_form_post_type']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['hostpn_form_post_type'])) : '';

            if (($hostpn_form_type == 'user' && empty($user_id) && !in_array($hostpn_form_subtype, ['user_alt_new'])) || ($hostpn_form_type == 'post' && (empty($post_id) && !(!empty($hostpn_form_subtype) && in_array($hostpn_form_subtype, ['post_new', 'post_edit'])))) || ($hostpn_form_type == 'option' && !is_user_logged_in())) {
              session_start();

              $_SESSION['wph_form'] = [];
              $_SESSION['wph_form'][$hostpn_form_id] = [];
              $_SESSION['wph_form'][$hostpn_form_id]['form_type'] = $hostpn_form_type;
              $_SESSION['wph_form'][$hostpn_form_id]['values'] = $key_value;

              if (!empty($post_id)) {
                $_SESSION['wph_form'][$hostpn_form_id]['post_id'] = $post_id;
              }

              echo wp_json_encode(['error_key' => 'hostpn_form_save_error_unlogged', ]);exit();
            }else{
              switch ($hostpn_form_type) {
                case 'user':
                  if (!in_array($hostpn_form_subtype, ['user_alt_new'])) {
                    if (empty($user_id)) {
                      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
                        $user_login = $_POST['user_login'];
                        $user_password = $_POST['user_password'];
                        $user_email = $_POST['user_email'];

                        $user_id = HOSTPN_Functions_User::insert_user($user_login, $user_password, $user_email);
                      }
                    }

                    if (!empty($user_id)) {
                      foreach ($key_value as $key => $value) {
                        update_user_meta($user_id, $key, $value);
                      }
                    }
                  }

                  break;
                case 'post':
                  if (empty($hostpn_form_subtype) || !in_array($hostpn_form_subtype, ['post_new', 'post_edit'])) {
                    if (empty($post_id)) {
                      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
                        $post_id = HOSTPN_Functions_Post::insert_post($title, '', '', sanitize_title($title), $post_type, 'publish', get_current_user_id());
                      }
                    }

                    if (!empty($post_id)) {
                      foreach ($key_value as $key => $value) {
                        update_post_meta($post_id, $key, $value);
                      }
                    }
                  }

                  break;
                case 'option':
                  if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
                    foreach ($key_value as $key => $value) {
                      update_option($key, $value);
                    }
                  }

                  break;
              }

              if ($hostpn_form_type == 'option') {
                update_option('hostpn_form_changed', true);
              }

              switch ($hostpn_form_type) {
                case 'user':
                  do_action('hostpn_form_save', $user_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type);
                  break;
                case 'post':
                  do_action('hostpn_form_save', $post_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type);
                  break;
                case 'option':
                  do_action('hostpn_form_save', 0, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type);
                  break;
              }

              $popup_close = in_array($hostpn_form_subtype, ['post_new', 'post_edit', 'user_alt_new']) ? true : '';
              $update_list = in_array($hostpn_form_subtype, ['post_new', 'post_edit', 'user_alt_new']) ? true : '';

              if ($update_list && !empty($post_type)) {
                switch ($post_type) {
                  case 'hostpn_accomm':
                    $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
                    $update_html = $plugin_post_type_accommodation->list();
                    break;
                  case 'hostpn_part':
                    $plugin_post_type_part = new HOSTPN_Post_Type_Part();
                    $update_html = $plugin_post_type_part->list();
                    break;
                  case 'hostpn_guest':
                    $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
                    $update_html = $plugin_post_type_guest->list();
                    break;
                }
              }else{
                $update_html = '';
              }

              echo wp_json_encode(['error_key' => '', 'popup_close' => $popup_close, 'update_list' => $update_list, 'update_html' => $update_html]);exit();
            }
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_form_save_error', ]);exit();
          }
          break;
      }

      echo wp_json_encode(['error_key' => '', ]);exit();
  	}
  }
}