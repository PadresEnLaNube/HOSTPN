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

      $hostpn_ajax_keys = !empty($_POST['hostpn_ajax_keys']) ? array_map(function($key) {
        return array(
          'id' => sanitize_key($key['id']),
          'node' => sanitize_key($key['node']),
          'type' => sanitize_key($key['type']),
          'field_config' => !empty($key['field_config']) ? $key['field_config'] : []
        );
      }, wp_unslash($_POST['hostpn_ajax_keys'])) : [];

      $hostpn_accommodation_id = !empty($_POST['hostpn_accommodation_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_accommodation_id'])) : 0;
      $hostpn_guest_id = !empty($_POST['hostpn_guest_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_guest_id'])) : 0;
      $hostpn_part_id = !empty($_POST['hostpn_part_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_part_id'])) : 0;
      $hostpn_room_id = !empty($_POST['hostpn_room_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_room_id'])) : 0;
      $hostpn_contract_id = !empty($_POST['hostpn_contract_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_POST['hostpn_contract_id'])) : 0;
      
      $hostpn_key_value = [];

      if (!empty($hostpn_ajax_keys)) {
        foreach ($hostpn_ajax_keys as $hostpn_key) {
          $field_config = !empty($hostpn_key['field_config']) ? $hostpn_key['field_config'] : [];
          if (strpos($hostpn_key['id'], '[]') !== false || (isset($hostpn_key['multiple']) && ($hostpn_key['multiple'] == 'true' || $hostpn_key['multiple'] === true))) {
            $hostpn_clear_key = str_replace('[]', '', $hostpn_key['id']);
            $hostpn_key_value[$hostpn_clear_key] = [];

            if (!empty($_POST[$hostpn_clear_key])) {
              $unslashed_array = wp_unslash($_POST[$hostpn_clear_key]);
              if (!is_array($unslashed_array)) {
                $unslashed_array = array($unslashed_array);
              }
              $sanitized_array = array_map(function($value) use ($hostpn_key, $field_config) {
                return HOSTPN_Forms::hostpn_sanitizer(
                  $value,
                  $hostpn_key['node'],
                  $hostpn_key['type'],
                  $field_config
                );
              }, $unslashed_array);
              
              foreach ($sanitized_array as $multi_key => $multi_value) {
                $final_value = !empty($multi_value) ? $multi_value : '';
                $hostpn_key_value[$hostpn_clear_key][$multi_key] = $final_value;
              }
            } else {
              $hostpn_key_value[$hostpn_clear_key] = [];
            }
          } else {
            $sanitized_key = sanitize_key($hostpn_key['id']);
            $hostpn_key_id = !empty($_POST[$sanitized_key]) ? 
              HOSTPN_Forms::hostpn_sanitizer(
                wp_unslash($_POST[$sanitized_key]), 
                $hostpn_key['node'], 
                $hostpn_key['type'],
                $field_config
              ) : '';
            $hostpn_key_value[$hostpn_key['id']] = $hostpn_key_id;
          }
        }
      }

      switch ($hostpn_ajax_type) {
        case 'hostpn_get_financial_data':
          $accommodation_id = !empty($_POST['accommodation_id']) ? intval($_POST['accommodation_id']) : $hostpn_accommodation_id;
          if (!empty($accommodation_id)) {
            $data = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accommodation_id);
            $data['html'] = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accommodation_id);
            echo wp_json_encode([
              'success' => true,
              'data'    => $data,
              'html'    => $data['html'],
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'success' => false,
              'error'   => esc_html(__('Invalid accommodation ID.', 'hostpn')),
            ]);
            exit;
          }
          break;

        case 'hostpn_save_financial_room_status':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['success' => false, 'error' => __('Permission denied.', 'hostpn')]);
            exit;
          }
          $accommodation_id = !empty($_POST['accommodation_id']) ? intval($_POST['accommodation_id']) : $hostpn_accommodation_id;
          $room_id = !empty($_POST['room_id']) ? intval($_POST['room_id']) : $hostpn_room_id;
          $field = !empty($_POST['field']) ? sanitize_key($_POST['field']) : '';
          $value = isset($_POST['value']) ? sanitize_text_field($_POST['value']) : '0';

          if (!empty($accommodation_id) && !empty($room_id) && !empty($field)) {
            if ($field === 'deposit_paid') {
              update_post_meta($room_id, 'hostpn_room_deposit_paid', ($value === '1' || $value === 'true') ? '1' : '0');
            } elseif ($field === 'rent_paid_current') {
              $curr_month_key = date('Y_m');
              update_post_meta($room_id, 'hostpn_room_rent_paid_' . $curr_month_key, ($value === '1' || $value === 'true') ? '1' : '0');
            } elseif ($field === 'rent_amount') {
              update_post_meta($room_id, 'hostpn_room_rent', floatval($value));
            } elseif ($field === 'deposit_amount') {
              update_post_meta($room_id, 'hostpn_room_deposit', floatval($value));
            }

            $data = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accommodation_id);
            $html = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accommodation_id);
            echo wp_json_encode([
              'success' => true,
              'data'    => $data,
              'html'    => $html,
            ]);
            exit;
          } else {
            echo wp_json_encode(['success' => false, 'error' => __('Invalid parameters.', 'hostpn')]);
            exit;
          }
          break;

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
        case 'hostpn_guest_get_user_data':
            $plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
            $plugin_post_type_guest->hostpn_guest_get_user_data();
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
        // ── ROOM AJAX CASES ──────────────────────────────────────────
        case 'hostpn_room_view':
          if (!empty($hostpn_room_id)) {
            $plugin_post_type_room = new HOSTPN_Post_Type_Room();
            echo wp_json_encode([
              'error_key' => '',
              'html' => $plugin_post_type_room->hostpn_room_view($hostpn_room_id),
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_room_view_error',
              'error_content' => esc_html(__('An error occurred while showing the Room.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_room_edit':
          if (!empty($hostpn_room_id)) {
            $plugin_post_type_room = new HOSTPN_Post_Type_Room();
            echo wp_json_encode([
              'error_key' => '',
              'html' => $plugin_post_type_room->hostpn_room_edit($hostpn_room_id),
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_room_edit_error',
              'error_content' => esc_html(__('An error occurred while showing the Room.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_room_new':
          $plugin_post_type_room = new HOSTPN_Post_Type_Room();
          echo wp_json_encode([
            'error_key' => '',
            'html' => $plugin_post_type_room->hostpn_room_new($hostpn_room_id),
          ]);
          exit;
          break;
        case 'hostpn_room_remove':
          if (!empty($hostpn_room_id)) {
            wp_delete_post($hostpn_room_id, true);
            $plugin_post_type_room = new HOSTPN_Post_Type_Room();
            echo wp_json_encode([
              'error_key' => '',
              'html' => $plugin_post_type_room->hostpn_room_list(),
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_room_remove_error',
              'error_content' => esc_html(__('An error occurred while removing the Room.', 'hostpn')),
            ]);
            exit;
          }
          break;

        case 'hostpn_room_get_guest_data':
          if (!empty($hostpn_room_id)) {
            $guest_id    = get_post_meta($hostpn_room_id, 'hostpn_room_guest_id', true);
            $room_number = get_post_meta($hostpn_room_id, 'hostpn_room_number', true);
            $room_label  = !empty($room_number) ? $room_number : get_the_title($hostpn_room_id);

            $guest_data = ['name' => '', 'nif' => '', 'address' => '', 'email' => ''];
            if (!empty($guest_id) && get_post($guest_id)) {
              $guest_data['name']  = trim(
                get_post_meta($guest_id, 'hostpn_name', true) . ' ' .
                get_post_meta($guest_id, 'hostpn_surname', true) . ' ' .
                get_post_meta($guest_id, 'hostpn_surname_alt', true)
              );
              $guest_data['nif']     = get_post_meta($guest_id, 'hostpn_identity_number', true);
              $guest_address         = get_post_meta($guest_id, 'hostpn_address', true);
              $guest_address_alt     = get_post_meta($guest_id, 'hostpn_address_alt', true);
              $guest_data['address'] = trim($guest_address . (!empty($guest_address_alt) ? ', ' . $guest_address_alt : ''));
              $guest_data['email']   = get_post_meta($guest_id, 'hostpn_email', true);
            }

            // Room-level contract fields
            $contract_keys = [
              'duration', 'start_date', 'end_date', 'notice_days',
              'rent_amount', 'rent_words', 'payment_day',
              'supplies_option', 'supplies_limit',
              'deposit_amount', 'deposit_words', 'deposit_months',
            ];
            $contract_data = [];
            foreach ($contract_keys as $ck) {
              $contract_data[$ck] = get_post_meta($hostpn_room_id, 'hostpn_room_contract_' . $ck, true);
            }

            echo wp_json_encode([
              'error_key'      => '',
              'room_label'     => $room_label,
              'guest'          => $guest_data,
              'guest_id'       => intval($guest_id),
              'guest_edit_url' => ($guest_id && get_post($guest_id))
                                   ? admin_url('post.php?post=' . intval($guest_id) . '&action=edit')
                                   : '',
              'room_edit_url'  => admin_url('post.php?post=' . intval($hostpn_room_id) . '&action=edit'),
              'contract'       => $contract_data,
            ]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_room']);
          }
          exit;
          break;

        // ── CONTRACT AJAX CASES ─────────────────────────────────────
        case 'hostpn_contract_view':
          if (!empty($hostpn_contract_id)) {
            $plugin_post_type_contract = new HOSTPN_Post_Type_Contract();
            echo wp_json_encode([
              'error_key' => '',
              'html' => $plugin_post_type_contract->hostpn_contract_view($hostpn_contract_id),
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_contract_view_error',
              'error_content' => esc_html(__('An error occurred while showing the Contract.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_contract_edit':
          if (!empty($hostpn_contract_id)) {
            $plugin_post_type_contract = new HOSTPN_Post_Type_Contract();
            echo wp_json_encode([
              'error_key' => '',
              'html' => $plugin_post_type_contract->hostpn_contract_edit($hostpn_contract_id),
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_contract_edit_error',
              'error_content' => esc_html(__('An error occurred while showing the Contract.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_contract_new':
          $plugin_post_type_contract = new HOSTPN_Post_Type_Contract();
          echo wp_json_encode([
            'error_key' => '',
            'html' => $plugin_post_type_contract->hostpn_contract_new($hostpn_contract_id),
          ]);
          exit;
          break;
        case 'hostpn_contract_remove':
          if (!empty($hostpn_contract_id)) {
            wp_delete_post($hostpn_contract_id, true);
            $plugin_post_type_contract = new HOSTPN_Post_Type_Contract();
            echo wp_json_encode([
              'error_key' => '',
              'html' => $plugin_post_type_contract->hostpn_contract_list(),
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_contract_remove_error',
              'error_content' => esc_html(__('An error occurred while removing the Contract.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_contract_get_guest_data':
          if (!empty($hostpn_guest_id)) {
            $guest_name = get_post_meta($hostpn_guest_id, 'hostpn_name', true) . ' '
              . get_post_meta($hostpn_guest_id, 'hostpn_surname', true) . ' '
              . get_post_meta($hostpn_guest_id, 'hostpn_surname_alt', true);
            $guest_nif = get_post_meta($hostpn_guest_id, 'hostpn_identity_number', true);
            $guest_email = get_post_meta($hostpn_guest_id, 'hostpn_email', true);

            echo wp_json_encode([
              'error_key' => '',
              'guest' => [
                'name'  => trim($guest_name),
                'nif'   => $guest_nif,
                'email' => $guest_email,
              ],
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_contract_get_guest_data_error',
              'error_content' => esc_html(__('Guest not found.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_contract_get_accommodation_data':
          if (!empty($hostpn_accommodation_id)) {
            $landlord_name = get_post_meta($hostpn_accommodation_id, 'hostpn_contract_landlord_name', true);
            $landlord_nif = get_post_meta($hostpn_accommodation_id, 'hostpn_contract_landlord_nif', true);
            $landlord_address = get_post_meta($hostpn_accommodation_id, 'hostpn_contract_landlord_address', true);
            $contract_type = get_post_meta($hostpn_accommodation_id, 'hostpn_accommodation_type', true);
            $mapped_type = HOSTPN_Contract_Templates::hostpn_get_type_for_accommodation($contract_type);

            echo wp_json_encode([
              'error_key' => '',
              'accommodation' => [
                'landlord_name'    => $landlord_name,
                'landlord_nif'     => $landlord_nif,
                'landlord_address' => $landlord_address,
                'contract_type'    => $mapped_type,
              ],
            ]);
            exit;
          } else {
            echo wp_json_encode([
              'error_key' => 'hostpn_contract_get_accommodation_data_error',
              'error_content' => esc_html(__('Accommodation not found.', 'hostpn')),
            ]);
            exit;
          }
          break;
        case 'hostpn_contract_preview':
          $contract_type = !empty($_POST['hostpn_contract_type']) ? sanitize_key(wp_unslash($_POST['hostpn_contract_type'])) : '';
          $valid_types = array_keys(HOSTPN_Contract_Templates::hostpn_get_contract_types());
          if (in_array($contract_type, $valid_types, true) && !empty($hostpn_accommodation_id)) {
            $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
            $html = HOSTPN_Contract_Templates::hostpn_resolve_shortcodes_from_contract(
              HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $hostpn_accommodation_id),
              $hostpn_contract_id,
              $hostpn_accommodation_id
            );
            $contract_room_id = !empty($hostpn_contract_id) ? get_post_meta($hostpn_contract_id, 'hostpn_contract_room_id', true) : 0;
            $html .= HOSTPN_Contract_Templates::hostpn_render_inventory($hostpn_accommodation_id, absint($contract_room_id));
            echo wp_json_encode(['error_key' => '', 'html' => $html]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_preview']);
          }
          exit;
          break;
        case 'hostpn_contract_frontend_preview':
          if (!empty($hostpn_contract_id)) {
            $contract_type = get_post_meta($hostpn_contract_id, 'hostpn_contract_type', true);
            $contract_accommodation_id = get_post_meta($hostpn_contract_id, 'hostpn_contract_accommodation_id', true);

            if (!empty($contract_type) && !empty($contract_accommodation_id)) {
              $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
              $html = HOSTPN_Contract_Templates::hostpn_resolve_shortcodes_from_contract(
                HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $contract_accommodation_id),
                $hostpn_contract_id,
                $contract_accommodation_id
              );
              $contract_room_id = get_post_meta($hostpn_contract_id, 'hostpn_contract_room_id', true);
              $html .= HOSTPN_Contract_Templates::hostpn_render_inventory($contract_accommodation_id, absint($contract_room_id));
              echo wp_json_encode(['error_key' => '', 'html' => $html]);
            } else {
              echo wp_json_encode(['error_key' => 'invalid_contract', 'error_content' => esc_html(__('Contract data is incomplete.', 'hostpn'))]);
            }
          } else {
            echo wp_json_encode(['error_key' => 'hostpn_contract_preview_error', 'error_content' => esc_html(__('Contract not found.', 'hostpn'))]);
          }
          exit;
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
        case 'hostpn_part_csv_export':
          // Popup para configurar la exportación CSV
          $plugin_post_type_part = new HOSTPN_Post_Type_Part();
          echo wp_json_encode([
            'error_key' => '',
            'html'      => $plugin_post_type_part->hostpn_part_csv_export_popup(),
          ]);
          exit;
          break;
        case 'hostpn_part_csv_download':
          // Descarga real del CSV de hospedajes por año
          $hostpn_year = !empty($_POST['hostpn_year']) ? absint($_POST['hostpn_year']) : (int) gmdate('Y', current_time('timestamp'));
          $include_guest_name = !empty($_POST['include_guest_name']) && $_POST['include_guest_name'] === '1';
          $include_doc_type = !empty($_POST['include_doc_type']) && $_POST['include_doc_type'] === '1';
          $include_doc_number = !empty($_POST['include_doc_number']) && $_POST['include_doc_number'] === '1';

          $plugin_xml = new HOSTPN_XML();
          $plugin_xml->hostpn_part_csv_download($hostpn_year, $include_guest_name, $include_doc_type, $include_doc_number);

          echo wp_json_encode(['error_key' => '']); // No debería alcanzarse por el exit() anterior
          exit;
          break;
        case 'hostpn_update_user_role':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'hostpn_role_error', 'error_content' => esc_html__('Unauthorized access.', 'hostpn')]);
            exit;
          }

          $role_action = !empty($_POST['role_action']) ? sanitize_text_field(wp_unslash($_POST['role_action'])) : '';
          $role = !empty($_POST['role']) ? sanitize_text_field(wp_unslash($_POST['role'])) : '';
          $user_ids = !empty($_POST['user_ids']) ? array_map('intval', wp_unslash($_POST['user_ids'])) : [];
          $role_nonce = !empty($_POST['role_nonce']) ? sanitize_text_field(wp_unslash($_POST['role_nonce'])) : '';

          if (!wp_verify_nonce($role_nonce, 'hostpn-role-assignment')) {
            echo wp_json_encode(['error_key' => 'hostpn_role_nonce_error', 'error_content' => esc_html__('Security check failed.', 'hostpn')]);
            exit;
          }

          $plugin_roles = ['hostpn_role_manager', 'hostpn_role_guest'];
          $role_labels = ['hostpn_role_manager' => __('Host - HOSTPN', 'hostpn'), 'hostpn_role_guest' => __('Guest - HOSTPN', 'hostpn')];

          if (!in_array($role, $plugin_roles)) {
            echo wp_json_encode(['error_key' => 'hostpn_role_invalid', 'error_content' => esc_html__('Invalid role specified.', 'hostpn')]);
            exit;
          }

          if (empty($user_ids)) {
            echo wp_json_encode(['error_key' => 'hostpn_role_no_users', 'error_content' => esc_html__('No users selected.', 'hostpn')]);
            exit;
          }

          $updated_count = 0;
          foreach ($user_ids as $user_id) {
            $user = get_user_by('id', $user_id);
            if ($user) {
              if ($role_action === 'assign') {
                $user->add_role($role);
                $updated_count++;
              } elseif ($role_action === 'remove') {
                $user->remove_role($role);
                $updated_count++;
              }
            }
          }

          $role_label_text = isset($role_labels[$role]) ? $role_labels[$role] : $role;
          if ($role_action === 'assign') {
            $message = sprintf(__('%d user(s) have been assigned the %s role.', 'hostpn'), $updated_count, $role_label_text);
          } else {
            $message = sprintf(__('%d user(s) have been removed from the %s role.', 'hostpn'), $updated_count, $role_label_text);
          }

          echo wp_json_encode(['error_key' => '', 'error_content' => $message]);
          exit;
          break;
        case 'hostpn_create_page':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode([
              'error_key' => 'hostpn_create_page_error',
              'error_content' => esc_html(__('You do not have permission to perform this action.', 'hostpn')),
            ]);
            exit;
          }

          $page_type = !empty($_POST['hostpn_page_type']) ? sanitize_key(wp_unslash($_POST['hostpn_page_type'])) : '';

          $page_types = [
            'accommodation' => [
              'title'     => __('Accommodations', 'hostpn'),
              'shortcode' => 'hostpn-accommodation-list',
              'option'    => 'hostpn_pages_accommodation',
            ],
            'guest' => [
              'title'     => __('Guests', 'hostpn'),
              'shortcode' => 'hostpn-guest-list',
              'option'    => 'hostpn_pages_guest',
            ],
            'part' => [
              'title'     => __('Parts of travelers', 'hostpn'),
              'shortcode' => 'hostpn-part-list',
              'option'    => 'hostpn_pages_part',
            ],
          ];

          if (!isset($page_types[$page_type])) {
            echo wp_json_encode([
              'error_key' => 'hostpn_create_page_error',
              'error_content' => esc_html(__('Invalid page type.', 'hostpn')),
            ]);
            exit;
          }

          $config = $page_types[$page_type];

          // Check if page already exists
          $existing_page = HOSTPN_Settings::hostpn_find_page($config['shortcode']);
          if ($existing_page) {
            echo wp_json_encode([
              'error_key'    => '',
              'redirect_url' => get_edit_post_link($existing_page, 'raw'),
            ]);
            exit;
          }

          // Create the page
          $page_id = wp_insert_post([
            'post_title'   => $config['title'],
            'post_content' => '<!-- wp:shortcode -->[' . $config['shortcode'] . ']<!-- /wp:shortcode -->',
            'post_status'  => 'draft',
            'post_type'    => 'page',
          ]);

          if (is_wp_error($page_id)) {
            echo wp_json_encode([
              'error_key'     => 'hostpn_create_page_error',
              'error_content' => esc_html($page_id->get_error_message()),
            ]);
            exit;
          }

          // Store page reference
          update_option($config['option'], $page_id);
          $hostpn_pages = get_option('hostpn_pages', []);
          if (!is_array($hostpn_pages)) {
            $hostpn_pages = [];
          }
          $hostpn_pages[] = $page_id;
          update_option('hostpn_pages', array_unique($hostpn_pages));

          echo wp_json_encode([
            'error_key'    => '',
            'redirect_url' => get_edit_post_link($page_id, 'raw'),
          ]);
          exit;
          break;

        case 'hostpn_install_plugin':
          if (!current_user_can('install_plugins')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';
          $allowed_slugs = ['pn-customers-manager', 'mailpn', 'pn-tasks-manager', 'pn-cookies-manager'];

          if (!in_array($slug, $allowed_slugs, true)) {
            echo wp_json_encode(['error_key' => 'invalid_slug']);
            exit;
          }

          include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
          include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
          include_once ABSPATH . 'wp-admin/includes/plugin.php';

          $api = plugins_api('plugin_information', [
            'slug'   => $slug,
            'fields' => ['sections' => false],
          ]);

          if (is_wp_error($api)) {
            echo wp_json_encode(['error_key' => 'api_error', 'error_content' => $api->get_error_message()]);
            exit;
          }

          $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
          $result   = $upgrader->install($api->download_link);

          if (is_wp_error($result)) {
            echo wp_json_encode(['error_key' => 'install_error', 'error_content' => $result->get_error_message()]);
            exit;
          }

          if ($result === false) {
            echo wp_json_encode(['error_key' => 'install_failed', 'error_content' => 'Installation failed.']);
            exit;
          }

          echo wp_json_encode(['error_key' => '']);
          exit;
          break;

        case 'hostpn_activate_plugin':
          if (!current_user_can('activate_plugins')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';
          $plugin_files = [
            'pn-customers-manager' => 'pn-customers-manager/pn-customers-manager.php',
            'mailpn'               => 'mailpn/mailpn.php',
            'pn-tasks-manager'     => 'pn-tasks-manager/pn-tasks-manager.php',
            'pn-cookies-manager'   => 'pn-cookies-manager/pn-cookies-manager.php',
          ];

          if (!isset($plugin_files[$slug])) {
            echo wp_json_encode(['error_key' => 'invalid_slug']);
            exit;
          }

          $plugin_file = $plugin_files[$slug];
          $result = activate_plugin($plugin_file);

          if (is_wp_error($result)) {
            echo wp_json_encode(['error_key' => 'activate_error', 'error_content' => $result->get_error_message()]);
            exit;
          }

          echo wp_json_encode(['error_key' => '']);
          exit;
          break;

        case 'hostpn_settings_export':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $settings  = new HOSTPN_Settings();
          $options   = $settings->get_options();
          $export    = [];

          foreach ($options as $key => $config) {
            if (!isset($config['input']) || in_array($config['input'], ['html_multi'])) continue;
            if (isset($config['type']) && in_array($config['type'], ['nonce', 'submit'])) continue;
            if (isset($config['section'])) continue;

            $value = get_option($key, '');
            if ($value !== '') {
              $export[$key] = $value;
            }
          }

          echo wp_json_encode(['error_key' => '', 'settings' => $export]);
          exit;
          break;

        case 'hostpn_save_contract_template':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $contract_type = !empty($_POST['contract_type']) ? sanitize_key(wp_unslash($_POST['contract_type'])) : '';
          $sections_raw = !empty($_POST['sections']) ? wp_unslash($_POST['sections']) : [];

          $valid_types = array_keys(HOSTPN_Contract_Templates::hostpn_get_contract_types());
          if (!in_array($contract_type, $valid_types, true)) {
            echo wp_json_encode(['error_key' => 'invalid_type', 'error_content' => esc_html__('Invalid contract type.', 'hostpn')]);
            exit;
          }

          $sections = [];
          if (is_array($sections_raw)) {
            foreach ($sections_raw as $key => $content) {
              $sections[sanitize_key($key)] = wp_kses_post($content);
            }
          }

          HOSTPN_Contract_Templates::hostpn_save_template($contract_type, $sections);
          echo wp_json_encode(['error_key' => '']);
          exit;
          break;

        case 'hostpn_get_contract_template':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $contract_type = !empty($_POST['contract_type']) ? sanitize_key(wp_unslash($_POST['contract_type'])) : '';
          $valid_types = array_keys(HOSTPN_Contract_Templates::hostpn_get_contract_types());
          if (!in_array($contract_type, $valid_types, true)) {
            echo wp_json_encode(['error_key' => 'invalid_type']);
            exit;
          }

          $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
          $sections = HOSTPN_Contract_Templates::hostpn_get_contract_sections($contract_type);
          echo wp_json_encode(['error_key' => '', 'template' => $template, 'sections' => $sections]);
          exit;
          break;

        case 'hostpn_get_contract_preview':
          $contract_type = !empty($_POST['contract_type']) ? sanitize_key(wp_unslash($_POST['contract_type'])) : '';
          $aid = !empty($_POST['hostpn_accommodation_id']) ? absint($_POST['hostpn_accommodation_id']) : 0;

          $valid_types = array_keys(HOSTPN_Contract_Templates::hostpn_get_contract_types());
          if (!in_array($contract_type, $valid_types, true)) {
            echo wp_json_encode(['error_key' => 'invalid_type']);
            exit;
          }

          $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
          $html = HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $aid);
          echo wp_json_encode(['error_key' => '', 'html' => $html, 'template' => $template]);
          exit;
          break;

        case 'hostpn_restore_contract_defaults':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $contract_type = !empty($_POST['contract_type']) ? sanitize_key(wp_unslash($_POST['contract_type'])) : '';
          $valid_types = array_keys(HOSTPN_Contract_Templates::hostpn_get_contract_types());
          if (!in_array($contract_type, $valid_types, true)) {
            echo wp_json_encode(['error_key' => 'invalid_type']);
            exit;
          }

          delete_option('hostpn_contract_template_' . $contract_type);
          $template = HOSTPN_Contract_Templates::hostpn_get_default_template($contract_type);
          $sections = HOSTPN_Contract_Templates::hostpn_get_contract_sections($contract_type);
          echo wp_json_encode(['error_key' => '', 'template' => $template, 'sections' => $sections]);
          exit;
          break;

        case 'hostpn_settings_import':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $raw = isset($_POST['settings']) ? wp_unslash($_POST['settings']) : '';
          $import = json_decode($raw, true);

          if (!is_array($import) || empty($import)) {
            echo wp_json_encode(['error_key' => 'invalid_data', 'error_content' => 'Invalid settings data.']);
            exit;
          }

          $settings  = new HOSTPN_Settings();
          $options   = $settings->get_options();
          $allowed   = array_keys($options);
          $count     = 0;

          foreach ($import as $key => $value) {
            if (in_array($key, $allowed)) {
              update_option($key, sanitize_text_field($value));
              $count++;
            }
          }

          echo wp_json_encode(['error_key' => '', 'count' => $count]);
          exit;
          break;

        // ── MANAGEMENT TABS AJAX CASES ────────────────────────────────

        case 'hostpn_cleaning_load':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_room_id)) {
            $tasks = get_post_meta($hostpn_room_id, 'hostpn_room_cleaning_tasks', true);
            echo wp_json_encode(['error_key' => '', 'tasks' => !empty($tasks) ? $tasks : new stdClass()]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_room']);
          }
          exit;
          break;

        case 'hostpn_cleaning_save':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_room_id)) {
            $raw_tasks = !empty($_POST['cleaning_tasks']) ? wp_unslash($_POST['cleaning_tasks']) : '{}';
            $tasks = json_decode($raw_tasks, true);
            if (is_array($tasks)) {
              // Sanitize each task
              $clean_tasks = [];
              foreach ($tasks as $area_key => $area_data) {
                $safe_key = sanitize_key($area_key);
                $clean_tasks[$safe_key] = [
                  'done'  => !empty($area_data['done']) && $area_data['done'] === '1' ? '1' : '0',
                  'date'  => !empty($area_data['date']) ? sanitize_text_field($area_data['date']) : '',
                  'notes' => !empty($area_data['notes']) ? sanitize_textarea_field($area_data['notes']) : '',
                ];
              }
              update_post_meta($hostpn_room_id, 'hostpn_room_cleaning_tasks', $clean_tasks);
              echo wp_json_encode(['error_key' => '']);
            } else {
              echo wp_json_encode(['error_key' => 'invalid_data']);
            }
          } else {
            echo wp_json_encode(['error_key' => 'invalid_room']);
          }
          exit;
          break;

        case 'hostpn_cleaning_system_save':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $system = !empty($_POST['cleaning_system']) && $_POST['cleaning_system'] === 'shared' ? 'shared' : 'punctual';
            update_post_meta($hostpn_accommodation_id, 'hostpn_cleaning_system', $system);
            echo wp_json_encode(['error_key' => '', 'system' => $system]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
          exit;
          break;

        case 'hostpn_shared_cleaning_load':
          if (!is_user_logged_in()) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $info = HOSTPN_Post_Type_Accommodation::hostpn_get_shared_cleaning_info($hostpn_accommodation_id);
            echo wp_json_encode(['error_key' => '', 'info' => $info]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
          exit;
          break;

        case 'hostpn_shared_cleaning_save_config':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $frequency = !empty($_POST['frequency_days']) ? max(1, intval($_POST['frequency_days'])) : 7;
            $notice = !empty($_POST['notice_days']) ? max(1, intval($_POST['notice_days'])) : 2;
            $stays = !empty($_POST['stays']) ? sanitize_textarea_field(wp_unslash($_POST['stays'])) : '';
            $next_date = !empty($_POST['next_date']) ? sanitize_text_field(wp_unslash($_POST['next_date'])) : date('Y-m-d', strtotime('+' . $frequency . ' days'));
            $instructions = !empty($_POST['instructions']) ? sanitize_textarea_field(wp_unslash($_POST['instructions'])) : '';

            update_post_meta($hostpn_accommodation_id, 'hostpn_shared_cleaning_frequency_days', $frequency);
            update_post_meta($hostpn_accommodation_id, 'hostpn_shared_cleaning_notice_days', $notice);
            update_post_meta($hostpn_accommodation_id, 'hostpn_shared_cleaning_stays', $stays);
            update_post_meta($hostpn_accommodation_id, 'hostpn_shared_cleaning_next_date', $next_date);
            update_post_meta($hostpn_accommodation_id, 'hostpn_shared_cleaning_instructions', $instructions);

            echo wp_json_encode(['error_key' => '']);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
          exit;
          break;

        case 'hostpn_shared_cleaning_complete':
          if (!is_user_logged_in()) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $notes = !empty($_POST['notes']) ? sanitize_textarea_field(wp_unslash($_POST['notes'])) : '';
            $user_id = get_current_user_id();
            $res = HOSTPN_Post_Type_Accommodation::hostpn_complete_shared_cleaning_turn($hostpn_accommodation_id, $notes, $user_id);
            echo wp_json_encode(['error_key' => '', 'result' => $res]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
          exit;
          break;

        case 'hostpn_shared_cleaning_add_comment':
          if (!is_user_logged_in()) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $comment_text = !empty($_POST['comment']) ? sanitize_textarea_field(wp_unslash($_POST['comment'])) : '';
            $user_id = get_current_user_id();
            $comment = HOSTPN_Post_Type_Accommodation::hostpn_add_shared_cleaning_comment($hostpn_accommodation_id, $user_id, $comment_text);
            if ($comment) {
              echo wp_json_encode(['error_key' => '', 'comment' => $comment]);
            } else {
              echo wp_json_encode(['error_key' => 'empty_comment']);
            }
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
          exit;
          break;

        case 'hostpn_shared_cleaning_send_reminder':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $sent = HOSTPN_Post_Type_Accommodation::hostpn_send_shared_cleaning_reminder($hostpn_accommodation_id);
            if ($sent) {
              echo wp_json_encode(['error_key' => '']);
            } else {
              echo wp_json_encode(['error_key' => 'send_failed', 'error_content' => __('Could not send reminder email. Make sure a guest is assigned to the current room.', 'hostpn')]);
            }
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
        case 'hostpn_shared_cleaning_reorder_queue':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_accommodation_id)) {
            $room_order = !empty($_POST['room_order']) ? array_map('intval', wp_unslash($_POST['room_order'])) : [];
            HOSTPN_Post_Type_Accommodation::hostpn_save_shared_cleaning_queue_order($hostpn_accommodation_id, $room_order);
            $info = HOSTPN_Post_Type_Accommodation::hostpn_get_shared_cleaning_info($hostpn_accommodation_id);
            echo wp_json_encode(['error_key' => '', 'info' => $info]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_accommodation']);
          }
          exit;
          break;


        case 'hostpn_inventory_checklist_load':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (!empty($hostpn_room_id) && !empty($hostpn_accommodation_id)) {
            $categories = [
              'mobiliario'               => __('Furniture', 'hostpn'),
              'equipamiento_individual'  => __('Individual equipment', 'hostpn'),
              'menaje_individual'        => __('Individual kitchenware', 'hostpn'),
              'equipamiento_comunitario' => __('Community equipment', 'hostpn'),
              'otros_enseres'            => __('Other items', 'hostpn'),
            ];

            $all_items = [];
            foreach ($categories as $cat_key => $cat_label) {
              $accom_items = HOSTPN_Contract_Templates::hostpn_collect_inventory_items(
                get_post_meta($hostpn_accommodation_id, 'hostpn_contract_inv_' . $cat_key . '_name', true),
                get_post_meta($hostpn_accommodation_id, 'hostpn_contract_inv_' . $cat_key . '_url', true)
              );
              $room_items = HOSTPN_Contract_Templates::hostpn_collect_inventory_items(
                get_post_meta($hostpn_room_id, 'hostpn_room_inv_' . $cat_key . '_name', true),
                get_post_meta($hostpn_room_id, 'hostpn_room_inv_' . $cat_key . '_url', true)
              );
              $merged = array_merge($accom_items, $room_items);
              foreach ($merged as $item) {
                $all_items[] = [
                  'category'       => $cat_key,
                  'category_label' => $cat_label,
                  'name'           => $item['name'],
                ];
              }
            }

            // Find active contract for this room
            $contract_id = 0;
            $existing = null;
            $contracts = HOSTPN_Post_Type_Contract::hostpn_get_contracts(0, $hostpn_accommodation_id);
            foreach ($contracts as $cid) {
              $c_room = get_post_meta($cid, 'hostpn_contract_room_id', true);
              $c_status = get_post_meta($cid, 'hostpn_contract_status', true);
              if (intval($c_room) === intval($hostpn_room_id) && !in_array($c_status, ['cancelled', 'expired'])) {
                $contract_id = $cid;
                $existing = get_post_meta($cid, 'hostpn_contract_checkout_inspection', true);
                break;
              }
            }

            if (!empty($all_items)) {
              echo wp_json_encode([
                'error_key'   => '',
                'items'       => $all_items,
                'contract_id' => $contract_id,
                'existing'    => !empty($existing) ? $existing : null,
              ]);
            } else {
              echo wp_json_encode(['error_key' => 'no_items', 'error_content' => esc_html__('No inventory items found.', 'hostpn')]);
            }
          } else {
            echo wp_json_encode(['error_key' => 'invalid_params']);
          }
          exit;
          break;

        case 'hostpn_inventory_inspection_save':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $raw_inspection = !empty($_POST['inspection_data']) ? wp_unslash($_POST['inspection_data']) : '{}';
          $inspection = json_decode($raw_inspection, true);

          if (!is_array($inspection) || empty($inspection['items'])) {
            echo wp_json_encode(['error_key' => 'invalid_data']);
            exit;
          }

          // Sanitize inspection data
          $clean_items = [];
          foreach ($inspection['items'] as $item) {
            $clean_items[] = [
              'category' => sanitize_key($item['category']),
              'name'     => sanitize_text_field($item['name']),
              'status'   => in_array($item['status'], ['ok', 'issue']) ? $item['status'] : 'ok',
              'comment'  => sanitize_textarea_field($item['comment']),
            ];
          }

          $current_user = wp_get_current_user();
          $inspection_data = [
            'date'          => current_time('Y-m-d'),
            'inspector'     => $current_user->display_name,
            'room_id'       => intval($hostpn_room_id),
            'items'         => $clean_items,
            'overall_notes' => sanitize_textarea_field($inspection['overall_notes']),
          ];

          // Save to contract meta if contract exists, otherwise to room meta
          if (!empty($hostpn_contract_id) && get_post($hostpn_contract_id)) {
            update_post_meta($hostpn_contract_id, 'hostpn_contract_checkout_inspection', $inspection_data);
          } else {
            update_post_meta($hostpn_room_id, 'hostpn_room_checkout_inspection', $inspection_data);
          }

          echo wp_json_encode(['error_key' => '']);
          exit;
          break;

        case 'hostpn_inventory_inspection_email':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          $raw_inspection = !empty($_POST['inspection_data']) ? wp_unslash($_POST['inspection_data']) : '{}';
          $inspection = json_decode($raw_inspection, true);

          if (!is_array($inspection) || empty($inspection['items'])) {
            echo wp_json_encode(['error_key' => 'invalid_data']);
            exit;
          }

          // Get landlord email
          $landlord_email = get_post_meta($hostpn_accommodation_id, 'hostpn_contract_landlord_email', true);
          if (empty($landlord_email) || !is_email($landlord_email)) {
            // Fallback to admin email
            $landlord_email = get_option('admin_email');
          }

          $current_user = wp_get_current_user();
          $inspection_data = [
            'date'          => current_time('Y-m-d'),
            'inspector'     => $current_user->display_name,
            'room_id'       => intval($hostpn_room_id),
            'items'         => $inspection['items'],
            'overall_notes' => !empty($inspection['overall_notes']) ? $inspection['overall_notes'] : '',
          ];

          $result = HOSTPN_Notifications::send_inventory_inspection_email(
            $inspection_data,
            $hostpn_accommodation_id,
            $hostpn_room_id
          );

          echo wp_json_encode(['error_key' => $result ? '' : 'email_failed', 'error_content' => $result ? '' : esc_html__('Failed to send email.', 'hostpn')]);
          exit;
          break;

        case 'hostpn_financial_frontend_load':
          if (!current_user_can('manage_options')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }

          if (!empty($hostpn_accommodation_id)) {
            $html = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($hostpn_accommodation_id);
            echo wp_json_encode(['error_key' => '', 'html' => $html]);
          } else {
            echo wp_json_encode(['error_key' => 'unavailable', 'html' => '']);
          }
          exit;
          break;

        case 'hostpn_add_room_payment':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $room_id      = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
          $amount       = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.0;
          $payment_type = isset($_POST['payment_type']) ? sanitize_key($_POST['payment_type']) : 'rent';
          $month_key    = isset($_POST['month_key']) ? sanitize_key($_POST['month_key']) : '';
          $payment_date = isset($_POST['payment_date']) ? sanitize_text_field($_POST['payment_date']) : '';
          $notes        = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';

          $result = HOSTPN_Post_Type_Accommodation::hostpn_record_room_payment($room_id, $amount, $payment_type, $month_key, $payment_date, 'manual', $notes);
          if ($result['success']) {
            $accom_id = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
            if (!$accom_id) {
              $accom_id = isset($_POST['accommodation_id']) ? intval($_POST['accommodation_id']) : 0;
            }
            $summary = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html    = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode(['error_key' => '', 'summary' => $summary, 'html' => $html, 'record' => $result['record']]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_data', 'error_content' => $result['message']]);
          }
          exit;
          break;

        case 'hostpn_financial_upload_csv':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          if (empty($_FILES['hostpn_financial_csv_file']['tmp_name'])) {
            echo wp_json_encode(['error_key' => 'no_file', 'error_content' => esc_html__('No CSV file uploaded.', 'hostpn')]);
            exit;
          }
          $accom_id = isset($_POST['hostpn_accommodation_id']) ? intval($_POST['hostpn_accommodation_id']) : 0;
          $tmp_path = $_FILES['hostpn_financial_csv_file']['tmp_name'];

          $parsed = HOSTPN_Financial_Importer::parse_file($tmp_path, $accom_id);
          if ($parsed['success']) {
            echo wp_json_encode([
              'error_key'       => '',
              'detected_format' => $parsed['detected_format'],
              'total_records'   => $parsed['total_records'],
              'records'         => $parsed['records'],
            ]);
          } else {
            echo wp_json_encode(['error_key' => 'parse_error', 'error_content' => $parsed['error']]);
          }
          exit;
          break;

        case 'hostpn_financial_process_import':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $accom_id = isset($_POST['accommodation_id']) ? intval($_POST['accommodation_id']) : 0;
          $records_json = isset($_POST['records']) ? wp_unslash($_POST['records']) : '';
          $records = json_decode($records_json, true);

          $res = HOSTPN_Financial_Importer::execute_import($accom_id, $records);
          if ($res['success']) {
            $summary = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html    = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode([
              'error_key'      => '',
              'imported_count' => $res['imported_count'],
              'message'        => $res['message'],
              'summary'        => $summary,
              'html'           => $html,
            ]);
          } else {
            echo wp_json_encode(['error_key' => 'import_failed', 'error_content' => $res['message']]);
          }
          exit;
          break;

        case 'hostpn_update_room_financial_amounts':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $room_id  = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
          $rent     = isset($_POST['rent_amount']) ? floatval($_POST['rent_amount']) : null;
          $deposit  = isset($_POST['deposit_amount']) ? floatval($_POST['deposit_amount']) : null;

          if ($room_id > 0) {
            if (!is_null($rent)) {
              update_post_meta($room_id, 'hostpn_room_contract_rent_amount', $rent);
            }
            if (!is_null($deposit)) {
              update_post_meta($room_id, 'hostpn_room_contract_deposit_amount', $deposit);
            }
            $accom_id = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
            $summary  = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html     = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode(['error_key' => '', 'summary' => $summary, 'html' => $html]);
          } else {
            echo wp_json_encode(['error_key' => 'invalid_room']);
          }
          exit;
          break;

        case 'hostpn_edit_room_payment':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $room_id    = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
          $payment_id = isset($_POST['payment_id']) ? sanitize_text_field($_POST['payment_id']) : '';
          $data_edit  = [];
          if (isset($_POST['amount'])) $data_edit['amount'] = floatval($_POST['amount']);
          if (isset($_POST['payment_type'])) $data_edit['payment_type'] = sanitize_key($_POST['payment_type']);
          if (isset($_POST['payment_date'])) $data_edit['payment_date'] = sanitize_text_field($_POST['payment_date']);
          if (isset($_POST['notes'])) $data_edit['notes'] = sanitize_textarea_field($_POST['notes']);

          $res = HOSTPN_Post_Type_Accommodation::hostpn_edit_room_payment_record($room_id, $payment_id, $data_edit);
          if ($res['success']) {
            $accom_id = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
            if (!$accom_id && isset($_POST['accommodation_id'])) {
              $accom_id = intval($_POST['accommodation_id']);
            }
            $summary = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html    = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode(['error_key' => '', 'summary' => $summary, 'html' => $html]);
          } else {
            echo wp_json_encode(['error_key' => 'edit_failed', 'error_content' => $res['message']]);
          }
          exit;
          break;

        case 'hostpn_delete_room_payment':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $room_id    = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
          $payment_id = isset($_POST['payment_id']) ? sanitize_text_field($_POST['payment_id']) : '';

          $res = HOSTPN_Post_Type_Accommodation::hostpn_delete_room_payment_record($room_id, $payment_id);
          if ($res['success']) {
            $accom_id = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
            if (!$accom_id && isset($_POST['accommodation_id'])) {
              $accom_id = intval($_POST['accommodation_id']);
            }
            $summary = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html    = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode(['error_key' => '', 'summary' => $summary, 'html' => $html]);
          } else {
            echo wp_json_encode(['error_key' => 'delete_failed', 'error_content' => $res['message']]);
          }
          exit;
          break;

        case 'hostpn_save_expense':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $accom_id   = isset($_POST['accommodation_id']) ? intval($_POST['accommodation_id']) : 0;
          $expense_id = isset($_POST['expense_id']) ? sanitize_text_field($_POST['expense_id']) : '';
          $amount     = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.0;
          $date       = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : current_time('Y-m-d');
          $provider   = isset($_POST['provider']) ? sanitize_text_field($_POST['provider']) : '';
          $category   = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
          $notes      = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';

          $expense_data = [
            'id'       => $expense_id,
            'amount'   => $amount,
            'date'     => $date,
            'provider' => $provider,
            'category' => $category,
            'notes'    => $notes,
          ];

          // Handle secure attachment upload if provided
          if (!empty($_FILES['attachment']['tmp_name'])) {
            $upload_res = HOSTPN_Private_Storage::hostpn_store_expense_attachment($accom_id, $_FILES['attachment']);
            if (is_wp_error($upload_res)) {
              echo wp_json_encode(['error_key' => 'upload_error', 'error_content' => $upload_res->get_error_message()]);
              exit;
            }
            $expense_data['attachment_filename'] = $upload_res['filename'];
            $expense_data['attachment_original_name'] = $upload_res['original_name'];
          }

          $res = HOSTPN_Post_Type_Accommodation::hostpn_save_accommodation_expense($accom_id, $expense_data);
          if ($res['success']) {
            $summary = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html    = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode(['error_key' => '', 'summary' => $summary, 'html' => $html, 'expense' => $res['expense']]);
          } else {
            echo wp_json_encode(['error_key' => 'save_expense_failed', 'error_content' => $res['message']]);
          }
          exit;
          break;

        case 'hostpn_delete_expense':
          if (!current_user_can('edit_posts')) {
            echo wp_json_encode(['error_key' => 'permission_denied']);
            exit;
          }
          $accom_id   = isset($_POST['accommodation_id']) ? intval($_POST['accommodation_id']) : 0;
          $expense_id = isset($_POST['expense_id']) ? sanitize_text_field($_POST['expense_id']) : '';

          $res = HOSTPN_Post_Type_Accommodation::hostpn_delete_accommodation_expense($accom_id, $expense_id);
          if ($res['success']) {
            $summary = HOSTPN_Post_Type_Accommodation::hostpn_get_financial_summary($accom_id);
            $html    = HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accom_id);
            $summary['html'] = $html;
            echo wp_json_encode(['error_key' => '', 'summary' => $summary, 'html' => $html]);
          } else {
            echo wp_json_encode(['error_key' => 'delete_expense_failed', 'error_content' => $res['message']]);
          }
          exit;
          break;

      }

      echo wp_json_encode([
        'error_key' => 'hostpn_save_error',
      ]);

      exit;
    }
  }
}