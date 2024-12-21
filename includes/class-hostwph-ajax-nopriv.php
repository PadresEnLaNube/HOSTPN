<?php
/**
 * Load the plugin no private Ajax functions.
 *
 * Load the plugin no private Ajax functions to be executed in background.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Ajax_Nopriv {
	/**
	 * Load the plugin templates.
	 *
	 * @since    1.0.0
	 */
	public function hostwph_ajax_nopriv_server() {
    if (array_key_exists('hostwph_ajax_nopriv_type', $_POST)) {
      if (array_key_exists('ajax_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ajax_nonce'])), 'hostwph-nonce')) {
        echo wp_json_encode(['error_key' => 'hostwph_nonce_error', ]);exit();
      }

  		$hostwph_ajax_nopriv_type = HOSTWPH_Forms::sanitizer($_POST['hostwph_ajax_nopriv_type']);
      $ajax_keys = !empty($_POST['ajax_keys']) ? wp_unslash($_POST['ajax_keys']) : [];
      $key_value = [];

      if (!empty($ajax_keys)) {
        foreach ($ajax_keys as $key) {
          if (strpos($key['id'], '[]') !== false) {
            $clear_key = str_replace('[]', '', $key['id']);
            ${$clear_key} = $key_value[$clear_key] = [];

            if (!empty($_POST[$clear_key])) {
              foreach (wp_unslash($_POST[$clear_key]) as $multi_key => $multi_value) {
                $final_value = !empty($_POST[$clear_key][$multi_key]) ? HOSTWPH_Forms::sanitizer(wp_unslash($_POST[$clear_key][$multi_key]), $key['node'], $key['type']) : '';
                ${$clear_key}[$multi_key] = $key_value[$clear_key][$multi_key] = $final_value;
              }
            }else{
              ${$clear_key} = '';
              $key_value[$clear_key][$multi_key] = '';
            }
          }else{
            $key_id = !empty($_POST[$key['id']]) ? HOSTWPH_Forms::sanitizer(wp_unslash($_POST[$key['id']]), $key['node'], $key['type']) : '';
            ${$key['id']} = $key_value[$key['id']] = $key_id;
          }
        }
      }

      switch ($hostwph_ajax_nopriv_type) {
        case 'hostwph_form_save':
          $hostwph_form_type = $_POST['hostwph_form_type'];

          if (!empty($key_value) && !empty($hostwph_form_type)) {
            $hostwph_form_id = $_POST['hostwph_form_id'];
            $hostwph_form_subtype = $_POST['hostwph_form_subtype'];
            $user_id = $_POST['hostwph_form_user_id'];
            $post_id = $_POST['hostwph_form_post_id'];
            $post_type = $_POST['hostwph_form_post_type'];

            if (($hostwph_form_type == 'user' && empty($user_id)) || ($hostwph_form_type == 'post' && (empty($post_id) && !(!empty($hostwph_form_subtype) && in_array($hostwph_form_subtype, ['post_new', 'post_edit'])))) || ($hostwph_form_type == 'option' && !is_user_logged_in())) {
              session_start();

              $_SESSION['wph_form'] = [];
              $_SESSION['wph_form'][$hostwph_form_id] = [];
              $_SESSION['wph_form'][$hostwph_form_id]['form_type'] = $hostwph_form_type;
              $_SESSION['wph_form'][$hostwph_form_id]['values'] = $key_value;

              if (!empty($post_id)) {
                $_SESSION['wph_form'][$hostwph_form_id]['post_id'] = $post_id;
              }

              echo wp_json_encode(['error_key' => 'hostwph_form_save_error_unlogged', ]);exit();
            }else{
              switch ($hostwph_form_type) {
                case 'user':
                  if (empty($user_id)) {
                    if (hostwph_Functions_User::is_user_admin(get_current_user_id())) {
                      $user_login = $_POST['user_login'];
                      $user_password = $_POST['user_password'];
                      $user_email = $_POST['user_email'];

                      $user_id = hostwph_Functions_Post::insert_user($user_login, $user_password, $user_email);
                    }
                  }

                  foreach ($key_value as $key => $value) {
                    update_user_meta($user_id, $key, $value);
                  }

                  break;
                case 'post':
                  if (empty($hostwph_form_subtype) || !in_array($hostwph_form_subtype, ['post_new', 'post_edit'])) {
                    if (empty($post_id)) {
                      if (hostwph_Functions_User::is_user_admin(get_current_user_id())) {
                        $post_id = hostwph_Functions_Post::insert_post($title, '', '', sanitize_title($title), $post_type, 'publish', get_current_user_id());
                      }
                    }

                    foreach ($key_value as $key => $value) {
                      update_post_meta($post_id, $key, $value);
                    }
                  }

                  break;
                case 'option':
                  if (hostwph_Functions_User::is_user_admin(get_current_user_id())) {
                    foreach ($key_value as $key => $value) {
                      update_option($key, $value);
                    }
                  }

                  break;
              }

              if ($hostwph_form_type == 'option') {
                update_option('hostwph_form_changed', true);
              }

              switch ($hostwph_form_type) {
                case 'user':
                  do_action('hostwph_form_save', $user_id, $key_value, $hostwph_form_type, $hostwph_form_subtype, $post_type);
                  break;
                case 'post':
                  do_action('hostwph_form_save', $post_id, $key_value, $hostwph_form_type, $hostwph_form_subtype, $post_type);
                  break;
                case 'option':
                  do_action('hostwph_form_save', 0, $key_value, $hostwph_form_type, $hostwph_form_subtype, $post_type);
                  break;
              }

              $popup_close = in_array($hostwph_form_subtype, ['post_new', 'post_edit']) ? true : '';
              $update_list = in_array($hostwph_form_subtype, ['post_new', 'post_edit']) ? true : '';

              if ($update_list) {
                switch ($post_type) {
                  case 'hostwph_accomodation':
                    $plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
                    $update_html = $plugin_post_type_accomodation->list();
                    break;
                  case 'hostwph_part':
                    $plugin_post_type_part = new HOSTWPH_Post_Type_Part();
                    $update_html = $plugin_post_type_part->list();
                    break;
                  case 'hostwph_guest':
                    $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
                    $update_html = $plugin_post_type_guest->list();
                    break;
                }
              }else{
                $update_html = '';
              }

              echo wp_json_encode(['error_key' => '', 'popup_close' => $popup_close, 'update_list' => $update_list, 'update_html' => $update_html]);exit();
            }
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_form_save_error', ]);exit();
          }
          break;
      }

      echo wp_json_encode(['error_key' => '', ]);exit();
  	}
  }
}