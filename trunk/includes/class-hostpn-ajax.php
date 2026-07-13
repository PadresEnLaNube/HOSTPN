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
      
      $hostpn_key_value = [];

      if (!empty($hostpn_ajax_keys)) {
        foreach ($hostpn_ajax_keys as $hostpn_key) {
          if (strpos($hostpn_key['id'], '[]') !== false) {
            $hostpn_clear_key = str_replace('[]', '', $hostpn_key['id']);
            ${$hostpn_clear_key} = $hostpn_key_value[$hostpn_clear_key] = [];

            if (!empty($_POST[$hostpn_clear_key])) {
              $unslashed_array = wp_unslash($_POST[$hostpn_clear_key]);
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
            $hostpn_key_id = !empty($_POST[$sanitized_key]) ? 
              HOSTPN_Forms::hostpn_sanitizer(
                wp_unslash($_POST[$sanitized_key]), 
                $hostpn_key['node'], 
                $hostpn_key['type'],
                $hostpn_key['field_config']
              ) : '';
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

      }

      echo wp_json_encode([
        'error_key' => 'hostpn_save_error',
      ]);

      exit;
    }
  }
}