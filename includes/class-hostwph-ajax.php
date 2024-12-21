<?php
/**
 * Load the plugin Ajax functions.
 *
 * Load the plugin Ajax functions to be executed in background.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Ajax {
	/**
	 * Load ajax functions.
	 *
	 * @since    1.0.0
	 */
	public function hostwph_ajax_server() {
    if (array_key_exists('hostwph_ajax_type', $_POST)) {
      if (array_key_exists('ajax_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ajax_nonce'])), 'hostwph-nonce')) {
        echo wp_json_encode(['error_key' => 'hostwph_nonce_error', ]);exit();
      }

  		$hostwph_ajax_type = HOSTWPH_Forms::sanitizer($_POST['hostwph_ajax_type']);
      $ajax_keys = !empty($_POST['ajax_keys']) ? wp_unslash($_POST['ajax_keys']) : [];
      $guest_id = !empty($_POST['guest_id']) ? USERSWPH_Forms::sanitizer(wp_unslash($_POST['guest_id'])) : 0;
      $accomodation_id = !empty($_POST['accomodation_id']) ? USERSWPH_Forms::sanitizer(wp_unslash($_POST['accomodation_id'])) : 0;
      $part_id = !empty($_POST['part_id']) ? USERSWPH_Forms::sanitizer(wp_unslash($_POST['part_id'])) : 0;
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

      switch ($hostwph_ajax_type) {
        case 'hostwph_options_save':
          if (!empty($key_value)) {
            foreach ($key_value as $key => $value) {
              if (!in_array($key, ['action', 'hostwph_ajax_type'])) {
                update_option($key, $value);
              }
            }

            update_option('hostwph_options_changed', true);
            echo wp_json_encode(['error_key' => '', ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_options_save_error', ]);exit();
          }
          break;
        case 'hostwph_guest_new':
            $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->new(), ]);exit();
          break;
        case 'hostwph_guest_view':
          if (!empty($guest_id)) {
            $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->view($guest_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_guest_view_error', 'error_' => esc_html(__('An error occurred while showing the guest.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_guest_edit':
          if (!empty($guest_id)) {
            $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->edit($guest_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_guest_edit_error', 'error_' => esc_html(__('An error occurred while showing the guest.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_guest_check':
          if (!empty($guest_id)) {
            $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->check($guest_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_guest_check_error', 'error_' => esc_html(__('An error occurred while checking the guest.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_guest_duplicate':
          if (!empty($guest_id)) {
            $plugin_post_type_post = new HOSTWPH_Functions_Post();
            $plugin_post_type_post->duplicate_post($guest_id, 'publish');
            
            $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_guest_duplicate_error', 'error_' => esc_html(__('An error occurred while duplicating the guest.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_guest_remove':
          if (!empty($guest_id)) {
            wp_delete_post($guest_id, true);
            $plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_guest_remove_error', 'error_' => esc_html(__('An error occurred while removing the guest.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_accomodation_new':
            $plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accomodation->new(), ]);exit();
          break;
        case 'hostwph_accomodation_view':
          if (!empty($accomodation_id)) {
            $plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accomodation->view($accomodation_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_accomodation_view_error', 'error_' => esc_html(__('An error occurred while showing the accomodation.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_accomodation_edit':
          if (!empty($accomodation_id)) {
            $plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accomodation->edit($accomodation_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_accomodation_edit_error', 'error_' => esc_html(__('An error occurred while showing the accomodation.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_accomodation_duplicate':
          if (!empty($accomodation_id)) {
            $plugin_post_type_post = new HOSTWPH_Functions_Post();
            $plugin_post_type_post->duplicate_post($accomodation_id, 'publish');
            
            $plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accomodation->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_accomodation_duplicate_error', 'error_' => esc_html(__('An error occurred while duplicating the accomodation.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_accomodation_remove':
          if (!empty($accomodation_id)) {
            wp_delete_post($accomodation_id, true);
            $plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accomodation->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_accomodation_remove_error', 'error_' => esc_html(__('An error occurred while removing the accomodation.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_part_new':
            $plugin_post_type_part = new HOSTWPH_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->new(), ]);exit();
          break;
        case 'hostwph_part_view':
          if (!empty($part_id)) {
            $plugin_post_type_part = new HOSTWPH_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->view($part_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_part_view_error', 'error_' => esc_html(__('An error occurred while showing the part.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_part_edit':
          if (!empty($part_id)) {
            $plugin_post_type_part = new HOSTWPH_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->edit($part_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_part_edit_error', 'error_' => esc_html(__('An error occurred while showing the part.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_part_duplicate':
          if (!empty($part_id)) {
            $plugin_post_type_post = new HOSTWPH_Functions_Post();
            $plugin_post_type_post->duplicate_post($part_id, 'publish');
            
            $plugin_post_type_part = new HOSTWPH_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_part_duplicate_error', 'error_' => esc_html(__('An error occurred while duplicating the part.', 'hostwph')), ]);exit();
          }
          break;
        case 'hostwph_part_remove':
          if (!empty($part_id)) {
            wp_delete_post($part_id, true);
            $plugin_post_type_part = new HOSTWPH_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostwph_part_remove_error', 'error_' => esc_html(__('An error occurred while removing the part.', 'hostwph')), ]);exit();
          }
          break;
      }

      echo wp_json_encode(['error_key' => 'hostwph_save_error', ]);exit();
    }
	}
}