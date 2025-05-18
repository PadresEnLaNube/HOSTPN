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
      // Always require nonce verification
      if (!array_key_exists('hostpn_ajax_nonce', $_POST)) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_ajax_error_required',
          'error_content' => esc_html(__('Security check failed: Nonce is required.', 'hostpn')),
        ]);

        exit;
      }

      if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_ajax_error_invalid',
          'error_content' => esc_html(__('Security check failed: Invalid nonce.', 'hostpn')),
        ]);

        exit;
      }

      $hostpn_ajax_type = HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_ajax_type']));
      $hostpn_ajax_keys = !empty($_POST['hostpn_ajax_keys']) ? wp_unslash($_POST['hostpn_ajax_keys']) : [];
      $hostpn_accommodation_id = !empty($_POST['hostpn_accommodation_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_accommodation_id'])) : 0;
      $hostpn_guest_id = !empty($_POST['hostpn_guest_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_guest_id'])) : 0;
      $hostpn_part_id = !empty($_POST['hostpn_part_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_part_id'])) : 0;
      $hostpn_key_value = [];

      if (!empty($hostpn_ajax_keys)) {
        foreach ($hostpn_ajax_keys as $hostpn_key) {
          if (strpos($hostpn_key['id'], '[]') !== false) {
            $hostpn_clear_key = str_replace('[]', '', $hostpn_key['id']);
            ${$hostpn_clear_key} = $hostpn_key_value[$hostpn_clear_key] = [];

            if (!empty($_POST[$hostpn_clear_key])) {
              foreach (wp_unslash($_POST[$hostpn_clear_key]) as $multi_key => $multi_value) {
                $final_value = !empty($_POST[$hostpn_clear_key][$multi_key]) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST[$hostpn_clear_key][$multi_key]), $hostpn_key['node'], $hostpn_key['type']) : '';
                ${$hostpn_clear_key}[$multi_key] = $hostpn_key_value[$hostpn_clear_key][$multi_key] = $final_value;
              }
            }else{
              ${$hostpn_clear_key} = '';
              $hostpn_key_value[$hostpn_clear_key][$multi_key] = '';
            }
          }else{
            $hostpn_key_id = !empty($_POST[$hostpn_key['id']]) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST[$hostpn_key['id']]), $hostpn_key['node'], $hostpn_key['type']) : '';
            ${$hostpn_key['id']} = $hostpn_key_value[$hostpn_key['id']] = $hostpn_key_id;
          }
        }
      }

      switch ($hostpn_ajax_type) {
        case 'hostpn_accommodation_view':
          if (!empty($hostpn_accommodation_id)) {
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_accommodation->hostpn_accommodation_view($hostpn_accommodation_id), 
            ]);

            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_accommodation_view_error', 
              'error_content' => esc_html(__('An error occurred while showing the Accommodation.', 'hostpn')), 
            ]);

            exit;
          }
          break;
        case 'hostpn_accommodation_edit':
          // Check if the Accommodation exists
          $hostpn_accommodation = get_post($hostpn_accommodation_id);
          

          if (!empty($hostpn_accommodation_id)) {
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_accommodation->hostpn_accommodation_edit($hostpn_accommodation_id), 
            ]);

            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_accommodation_edit_error', 
              'error_content' => esc_html(__('An error occurred while showing the Accommodation.', 'hostpn')), 
            ]);

            exit;
          }
          break;
        case 'hostpn_accommodation_new':
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();

            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_accommodation->hostpn_accommodation_new($hostpn_accommodation_id), 
            ]);

            exit;
          break;
        case 'hostpn_accommodation_check':
          if (!empty($hostpn_accommodation_id)) {
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_accommodation->hostpn_accommodation_check($hostpn_accommodation_id), 
            ]);

            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_accommodation_check_error', 
              'error_content' => esc_html(__('An error occurred while checking the Accommodation.', 'hostpn')), 
              ]);

            exit;
          }
          break;
        case 'hostpn_accommodation_duplicate':
          if (!empty($hostpn_accommodation_id)) {
            $plugin_post_type_post = new HOSTPN_Functions_Post();
            $plugin_post_type_post->hostpn_duplicate_post($hostpn_accommodation_id, 'publish');
            
            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_accommodation->hostpn_accommodation_list(), 
            ]);

            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_accommodation_duplicate_error', 
              'error_content' => esc_html(__('An error occurred while duplicating the Accommodation.', 'hostpn')), 
            ]);

            exit;
          }
          break;
        case 'hostpn_accommodation_remove':
          if (!empty($hostpn_accommodation_id)) {
            wp_delete_post($hostpn_accommodation_id, true);

            $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_accommodation->hostpn_accommodation_list(), 
            ]);

            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_accommodation_remove_error', 
              'error_content' => esc_html(__('An error occurred while removing the Accommodation.', 'hostpn')), 
            ]);

            exit;
          }
          break;
        case 'hostpn_accommodation_share':
          $plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
          echo wp_json_encode([
            'error_key' => '', 
            'html' => $plugin_post_type_accommodation->hostpn_accommodation_share(), 
          ]);

          exit;
          break;
      
        case 'hostpn_guest_view':
          if (!empty($hostpn_guest_id)) {
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_guest->hostpn_guest_view($hostpn_guest_id), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_guest_view_error', 
              'error_content' => esc_html(__('An error occurred while showing the Guest.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_guest_edit':
          // Check if the Guest exists
          $hostpn_guest = get_post($hostpn_guest_id);
          
        
          if (!empty($hostpn_guest_id)) {
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_guest->hostpn_guest_edit($hostpn_guest_id), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_guest_edit_error', 
              'error_content' => esc_html(__('An error occurred while showing the Guest.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_guest_new':
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
        
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_guest->hostpn_guest_new($hostpn_guest_id), 
            ]);
        
            exit;
          break;
        case 'hostpn_guest_check':
          if (!empty($hostpn_guest_id)) {
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_guest->hostpn_guest_check($hostpn_guest_id), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_guest_check_error', 
              'error_content' => esc_html(__('An error occurred while checking the Guest.', 'hostpn')), 
              ]);
        
            exit;
          }
          break;
        case 'hostpn_guest_duplicate':
          if (!empty($hostpn_guest_id)) {
            $plugin_post_type_post = new HOSTPN_Functions_Post();
            $plugin_post_type_post->hostpn_duplicate_post($hostpn_guest_id, 'publish');
            
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_guest->hostpn_guest_list(), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_guest_duplicate_error', 
              'error_content' => esc_html(__('An error occurred while duplicating the Guest.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_guest_remove':
          if (!empty($hostpn_guest_id)) {
            wp_delete_post($hostpn_guest_id, true);
        
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_guest->hostpn_guest_list(), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_guest_remove_error', 
              'error_content' => esc_html(__('An error occurred while removing the Guest.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_part_view':
          if (!empty($hostpn_part_id)) {
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_part->hostpn_part_view($hostpn_part_id), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_part_view_error', 
              'error_content' => esc_html(__('An error occurred while showing the Part.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_part_edit':
          // Check if the Part exists
          $hostpn_part = get_post($hostpn_part_id);
          
        
          if (!empty($hostpn_part_id)) {
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_part->hostpn_part_edit($hostpn_part_id), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_part_edit_error', 
              'error_content' => esc_html(__('An error occurred while showing the Part.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_part_new':
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
        
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_part->hostpn_part_new($hostpn_part_id), 
            ]);
        
            exit;
          break;
        case 'hostpn_part_check':
          if (!empty($hostpn_part_id)) {
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_part->hostpn_part_check($hostpn_part_id), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_part_check_error', 
              'error_content' => esc_html(__('An error occurred while checking the Part.', 'hostpn')), 
              ]);
        
            exit;
          }
          break;
        case 'hostpn_part_duplicate':
          if (!empty($hostpn_part_id)) {
            $plugin_post_type_post = new HOSTPN_Functions_Post();
            $plugin_post_type_post->hostpn_duplicate_post($hostpn_part_id, 'publish');
            
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_part->hostpn_part_list(), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_part_duplicate_error', 
              'error_content' => esc_html(__('An error occurred while duplicating the Part.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_part_remove':
          if (!empty($hostpn_part_id)) {
            wp_delete_post($hostpn_part_id, true);
        
            $plugin_post_type_part = new HOSTPN_Post_Type_Part();
            echo wp_json_encode([
              'error_key' => '', 
              'html' => $plugin_post_type_part->hostpn_part_list(), 
            ]);
        
            exit;
          }else{
            echo wp_json_encode([
              'error_key' => 'hostpn_part_remove_error', 
              'error_content' => esc_html(__('An error occurred while removing the Part.', 'hostpn')), 
            ]);
        
            exit;
          }
          break;
        case 'hostpn_part_download':
            if (!empty($hostpn_part_id)) {
              $plugin_post_type_xml = new HOSTPN_XML();
              $plugin_post_type_xml->hostpn_part_download($hostpn_part_id);
  
              echo wp_json_encode(['error_key' => '', ]);exit();
            }else{
              echo wp_json_encode(['error_key' => 'hostpn_part_download_error', 'error_' => esc_html(__('An error occurred while duplicating the part.', 'hostpn')), ]);exit();
            }
            break;
      }

      echo wp_json_encode([
        'error_key' => 'hostpn_save_error', 
      ]);

      exit;
    }
	}
}