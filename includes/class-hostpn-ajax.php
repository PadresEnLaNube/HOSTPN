<?php
/**
 * Load the plugin Ajax functions.
 *
 * Load the plugin Ajax functions to be executed in background.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Ajax {
	/**
	 * Load ajax functions.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_ajax_server() {
    if (array_key_exists('hostpn_ajax_type', $_POST)) {
      if (array_key_exists('ajax_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ajax_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode(['error_key' => 'hostpn_nonce_error', ]);exit();
      }

  		$hostpn_ajax_type = HOSTPN_Forms::sanitizer($_POST['hostpn_ajax_type']);
      $ajax_keys = !empty($_POST['ajax_keys']) ? wp_unslash($_POST['ajax_keys']) : [];
      $guest_id = !empty($_POST['guest_id']) ? USERSPN_Forms::sanitizer(wp_unslash($_POST['guest_id'])) : 0;
      $accommodation_id = !empty($_POST['accommodation_id']) ? USERSPN_Forms::sanitizer(wp_unslash($_POST['accommodation_id'])) : 0;
      $part_id = !empty($_POST['part_id']) ? USERSPN_Forms::sanitizer(wp_unslash($_POST['part_id'])) : 0;
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

      switch ($hostpn_ajax_type) {
        case 'hostpn_options_save':
          if (!empty($key_value)) {
            foreach ($key_value as $key => $value) {
              if (!in_array($key, ['action', 'hostpn_ajax_type'])) {
                update_option($key, $value);
              }
            }

            update_option('hostpn_options_changed', true);
            echo wp_json_encode(['error_key' => '', ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_options_save_error', ]);exit();
          }
          break;
        case 'hostpn_guest_new':
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->new(), ]);exit();
          break;
        case 'hostpn_guest_view':
          if (!empty($guest_id)) {
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->view($guest_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_guest_view_error', 'error_' => esc_html(__('An error occurred while showing the guest.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_guest_edit':
          if (!empty($guest_id)) {
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->edit($guest_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_guest_edit_error', 'error_' => esc_html(__('An error occurred while showing the guest.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_guest_check':
          if (!empty($guest_id)) {
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->check($guest_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_guest_check_error', 'error_' => esc_html(__('An error occurred while checking the guest.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_guest_duplicate':
          if (!empty($guest_id)) {
            $plugin_post_type_post = new HOSTPN_Functions_Post();
            $plugin_post_type_post->duplicate_post($guest_id, 'publish');
            
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_guest_duplicate_error', 'error_' => esc_html(__('An error occurred while duplicating the guest.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_guest_remove':
          if (!empty($guest_id)) {
            wp_delete_post($guest_id, true);
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_guest->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_guest_remove_error', 'error_' => esc_html(__('An error occurred while removing the guest.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_accommodation_new':
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accommodation->new(), ]);exit();
          break;
        case 'hostpn_accommodation_view':
          if (!empty($accommodation_id)) {
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accommodation->view($accommodation_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_accommodation_view_error', 'error_' => esc_html(__('An error occurred while showing the accommodation.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_accommodation_edit':
          if (!empty($accommodation_id)) {
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accommodation->edit($accommodation_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_accommodation_edit_error', 'error_' => esc_html(__('An error occurred while showing the accommodation.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_accommodation_share':
          $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
          echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accommodation->share(), ]);exit();
          break;
        case 'hostpn_accommodation_duplicate':
          if (!empty($accommodation_id)) {
            $plugin_post_type_post = new HOSTPN_Functions_Post();
            $plugin_post_type_post->duplicate_post($accommodation_id, 'publish');
            
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accommodation->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_accommodation_duplicate_error', 'error_' => esc_html(__('An error occurred while duplicating the accommodation.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_accommodation_remove':
          if (!empty($accommodation_id)) {
            wp_delete_post($accommodation_id, true);
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_accommodation->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_accommodation_remove_error', 'error_' => esc_html(__('An error occurred while removing the accommodation.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_part_new':
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->new(), ]);exit();
          break;
        case 'hostpn_part_view':
          if (!empty($part_id)) {
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->view($part_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_part_view_error', 'error_' => esc_html(__('An error occurred while showing the part.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_part_edit':
          if (!empty($part_id)) {
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->edit($part_id), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_part_edit_error', 'error_' => esc_html(__('An error occurred while showing the part.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_part_download':
          if (!empty($part_id)) {
            $plugin_post_type_xml = new HOSTPN_XML();
            $plugin_post_type_xml->part_download($part_id);

            echo wp_json_encode(['error_key' => '', ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_part_download_error', 'error_' => esc_html(__('An error occurred while duplicating the part.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_part_duplicate':
          if (!empty($part_id)) {
            $plugin_post_type_post = new HOSTPN_Functions_Post();
            $plugin_post_type_post->duplicate_post($part_id, 'publish');
            
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_part_duplicate_error', 'error_' => esc_html(__('An error occurred while duplicating the part.', 'hostpn')), ]);exit();
          }
          break;
        case 'hostpn_part_remove':
          if (!empty($part_id)) {
            wp_delete_post($part_id, true);
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode(['error_key' => '', 'html' => $plugin_post_type_part->list(), ]);exit();
          }else{
            echo wp_json_encode(['error_key' => 'hostpn_part_remove_error', 'error_' => esc_html(__('An error occurred while removing the part.', 'hostpn')), ]);exit();
          }
          break;
      }

      echo wp_json_encode(['error_key' => 'hostpn_save_error', ]);exit();
    }
	}
}