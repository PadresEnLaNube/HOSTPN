<?php
/**
 * Define the users management functionality.
 *
 * Loads and defines the users management files for this plugin so that it is ready for user creation, edition or removal.
 *  
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostwph
 * @subpackage hostwph/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Functions_User {
  public static function is_user_admin($user_id) {
    // HOSTWPH_Functions_User::is_user_admin($user_id)
    return user_can($user_id, 'administrator');
  }

  public static function get_user_name($user_id) {
    if (!empty($user_id)) {
      $user_info = get_userdata($user_id);

      if (!empty($user_info->first_name) && !empty($user_info->last_name)) {
        return $user_info->first_name . ' ' . $user_info->last_name;
      }elseif (!empty($user_info->first_name)) {
        return $user_info->first_name;
      }else if (!empty($user_info->last_name)) {
        return $user_info->last_name;
      }else if (!empty($user_info->user_nicename)){
        return $user_info->user_nicename;
      }else if (!empty($user_info->user_login)){
        return $user_info->user_login;
      }else{
        return $user_info->user_email;
      }
    }
  }

  public static function get_user_age($user_id) {
    $timestamp = get_user_meta($user_id, 'hostwph_child_birthdate', true);

    if (!empty($timestamp) && is_string($timestamp)) {
      $timestamp = strtotime($timestamp);

      $year = date('Y', $timestamp);
      $age = date('Y') - $year;

      if(strtotime('+' . $age . ' years', $timestamp) > time()) {
        $age--;
      }

      return $age;
    }

    return false;
  }

  public static function insert_user($hostwph_user_login, $hostwph_user_password, $hostwph_user_email = '', $hostwph_first_name = '', $hostwph_last_name = '', $hostwph_display_name = '', $hostwph_user_nicename = '', $hostwph_user_nickname = '', $hostwph_user_description = '', $hostwph_user_role = [], $hostwph_array_usermeta = [/*['hostwph_key' => 'hostwph_value'], */]) {
    /* $this->insert_user($hostwph_user_login, $hostwph_user_password, $hostwph_user_email = '', $hostwph_first_name = '', $hostwph_last_name = '', $hostwph_display_name = '', $hostwph_user_nicename = '', $hostwph_user_nickname = '', $hostwph_user_description = '', $hostwph_user_role = [], $hostwph_array_usermeta = [['hostwph_key' => 'hostwph_value'], ],); */

    $hostwph_user_array = [
      'first_name' => $hostwph_first_name,
      'last_name' => $hostwph_last_name,
      'display_name' => $hostwph_display_name,
      'user_nicename' => $hostwph_user_nicename,
      'nickname' => $hostwph_user_nickname,
      'description' => $hostwph_user_description,
    ];

    if (!empty($hostwph_user_email)) {
      if (!email_exists($hostwph_user_email)) {
        if (username_exists($hostwph_user_login)) {
          $user_id = wp_create_user($hostwph_user_email, $hostwph_user_password, $hostwph_user_email);
        }else{
          $user_id = wp_create_user($hostwph_user_login, $hostwph_user_password, $hostwph_user_email);
        }
      }else{
        $user_id = get_user_by('email', $hostwph_user_email)->ID;
      }
    }else{
      if (!username_exists($hostwph_user_login)) {
        $user_id = wp_create_user($hostwph_user_login, $hostwph_user_password);
      }else{
        $user_id = get_user_by('login', $hostwph_user_login)->ID;
      }
    }

    if ($user_id && !is_wp_error($user_id)) {
      wp_update_user(array_merge(['ID' => $user_id], $hostwph_user_array));
    }else{
      return false;
    }

    $user = new WP_User($user_id);
    if (!empty($hostwph_user_role)) {
      foreach ($hostwph_user_role as $new_role) {
        $user->add_role($new_role);
      }
    }

    if (!empty($hostwph_array_usermeta)) {
      foreach ($hostwph_array_usermeta as $hostwph_usermeta) {
        foreach ($hostwph_usermeta as $meta_key => $meta_value) {
          if ((!empty($meta_value) || !empty(get_user_meta($user_id, $meta_key, true))) && !is_null($meta_value)) {
            update_user_meta($user_id, $meta_key, $meta_value);
          }
        }
      }
    }

    return $user_id;
  }

  public function hostwph_wp_login($login) {
    $user = get_user_by('login', $login);
    $user_id = $user->ID;
    $current_login_time = get_user_meta($user_id, 'hostwph_current_login', true);

    if(!empty($current_login_time)){
      update_user_meta($user_id, 'hostwph_last_login', $current_login_time);
      update_user_meta($user_id, 'hostwph_current_login', current_time('timestamp'));
    }else {
      update_user_meta($user_id, 'hostwph_current_login', current_time('timestamp'));
      update_user_meta($user_id, 'hostwph_last_login', current_time('timestamp'));
    }

    update_user_meta($user_id, 'users_wph_newsletter_active', true);
  }

  public function userswph_wph_register_fields($register_fields) {
    global $post;
    $post_id = $post->ID ?? 0;
    $hostwph_pages = get_option('hostwph_pages') ?? [];

    if ((in_array($post_id, $hostwph_pages) || is_admin()) && !(current_user_can('administrator') || current_user_can('hostwph_role_manager'))) {
      $relationships = HOSTWPH_Data::relationships();

      $register_fields['hostwph_surname_alt'] = [
        'id' => 'hostwph_surname_alt',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'apellido2',
        'label' => esc_html(__('Second surname', 'hostwph')),
        'placeholder' => esc_html(__('Second surname', 'hostwph')),
      ];
      $register_fields['hostwph_identity'] = [
        'id' => 'hostwph_identity',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'parent' => 'this',
        'options' => ['nif' => esc_html(__('NIF', 'hostwph')), 'nie' => esc_html(__('NIE', 'hostwph')), 'pas' => esc_html(__('PAS', 'hostwph')), 'cif' => esc_html(__('CIF', 'hostwph')), 'otro' => esc_html(__('OTRO', 'hostwph'))],
        'required' => true,
        'xml' => 'tipoDocumento',
        'label' => esc_html(__('Document type', 'hostwph')),
        'placeholder' => esc_html(__('Document type', 'hostwph')),
      ];
      $register_fields['hostwph_identity_number'] = [
        'id' => 'hostwph_identity_number',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'numeroDocumento',
        'label' => esc_html(__('Document number', 'hostwph')),
        'placeholder' => esc_html(__('Document number', 'hostwph')),
      ];
      $register_fields['hostwph_identity_support_number'] = [
        'id' => 'hostwph_identity_support_number',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'soporteDocumento',
        'label' => esc_html(__('Document support number', 'hostwph')),
        'placeholder' => esc_html(__('Document support number', 'hostwph')),
      ];
      $register_fields['hostwph_birthdate'] = [
        'id' => 'hostwph_birthdate',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaNacimiento',
        'label' => esc_html(__('Birthdate', 'hostwph')),
        'placeholder' => esc_html(__('Birthdate', 'hostwph')),
      ];
      $register_fields['hostwph_nationality'] = [
        'id' => 'hostwph_nationality',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => HOSTWPH_Data::countries(),
        'required' => true,
        'xml' => 'nacionalidad',
        'label' => esc_html(__('Nationality', 'hostwph')),
        'placeholder' => esc_html(__('Nationality', 'hostwph')),
      ];
      $register_fields['hostwph_gender'] = [
        'id' => 'hostwph_gender',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => ['h' => esc_html(__('Male', 'hostwph')), 'm' => esc_html(__('Female', 'hostwph')), 'o' => esc_html(__('Other', 'hostwph'))],
        'required' => true,
        'xml' => 'sexo',
        'label' => esc_html(__('Gender', 'hostwph')),
        'placeholder' => esc_html(__('Gender', 'hostwph')),
      ];
      $register_fields['hostwph_address'] = [
        'id' => 'hostwph_address',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostwph')),
        'placeholder' => esc_html(__('Address', 'hostwph')),
      ];
      $register_fields['hostwph_address_alt'] = [
        'id' => 'hostwph_address_alt',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostwph')),
        'placeholder' => esc_html(__('Address complementary information', 'hostwph')),
      ];
      $register_fields['hostwph_country'] = [
        'id' => 'hostwph_country',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => HOSTWPH_Data::countries(),
        'parent' => 'this',
        'required' => true,
        'xml' => 'pais',
        'label' => esc_html(__('Country', 'hostwph')),
        'placeholder' => esc_html(__('Country', 'hostwph')),
      ];
      $register_fields['hostwph_postal_code'] = [
        'id' => 'hostwph_postal_code',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'parent' => 'hostwph_country',
        'parent_option' => 'es',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('Postal code', 'hostwph')),
        'placeholder' => esc_html(__('Postal code', 'hostwph')),
      ];
      $register_fields['hostwph_city'] = [
        'id' => 'hostwph_city',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('City', 'hostwph')),
        'placeholder' => esc_html(__('City', 'hostwph')),
      ];
      $register_fields['hostwph_phone'] = [
        'id' => 'hostwph_phone',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'telefono',
        'label' => esc_html(__('Phone', 'hostwph')),
        'placeholder' => esc_html(__('Phone', 'hostwph')),
      ];
      $register_fields['hostwph_phone_alt'] = [
        'id' => 'hostwph_phone_alt',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'telefono2',
        'label' => esc_html(__('Alternative phone', 'hostwph')),
        'placeholder' => esc_html(__('Alternative phone', 'hostwph')),
      ];
      $register_fields['hostwph_email'] = [
        'id' => 'hostwph_email',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'email',
        'required' => true,
        'xml' => 'correo',
        'label' => esc_html(__('Email', 'hostwph')),
        'placeholder' => esc_html(__('Email', 'hostwph')),
      ];
      $register_fields['hostwph_contract_holder_check'] = [
        'id' => 'hostwph_contract_holder_check',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'checkbox',
        'parent' => 'this',
        'label' => esc_html(__('I´m not the holder of the contract', 'hostwph')),
        'placeholder' => esc_html(__('I´m not the holder of the contract', 'hostwph')),
      ];
        $register_fields['hostwph_relationship'] = [
          'id' => 'hostwph_relationship',
          'class' => 'hostwph-select hostwph-width-100-percent',
          'input' => 'select',
          'options' => $relationships,
          'parent' => 'hostwph_contract_holder_check',
          'parent_option' => 'on',
          'xml' => 'parentesco',
          'label' => esc_html(__('Relationship', 'hostwph')),
          'placeholder' => esc_html(__('Relationship', 'hostwph')),
        ];
    }
    
    return $register_fields;
  }

  public function hostwph_user_register($user_id) {
    $user = new WP_User($user_id);
    $user->add_role('hostwph_role_guest');

    $post_functions = new HOSTWPH_Functions_Post();
    $hostwph_title = gmdate('Y-m-d H:i:s', current_time('timestamp')) . ' - ' . bin2hex(openssl_random_pseudo_bytes(4));
    $hostwph_post_content = __('Special needs, allergies, important situations to highlight...', 'hostwph');
    update_user_meta($user_id, 'hostwph_user_to_guest', current_time('timestamp'));

    $hostwph_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_post_content, '', sanitize_title(esc_html($hostwph_title)), 'hostwph_guest', 'publish', $user_id);
  }

  public function hostwph_user_to_guest() {
    $user = new WP_User(get_current_user_id());
    $user->add_role('hostwph_role_guest');

    $users_atts = [
      'fields' => 'ids',
      'number' => -1,
      'role' => 'subscriber',
      'meta_key' => 'hostwph_user_to_guest', 
      'meta_compare' => 'exist',
    ];
    
    $users = get_users($users_atts);

    if (!empty($users)) {
      foreach ($users as $user_id) {
        $posts_atts = [
          'fields' => 'ids',
          'numberposts' => -1,
          'post_type' => 'hostwph_guest',
          'post_status' => 'publish', 
          'author' => $user_id, 
        ];
        
        $posts = get_posts($posts_atts);

        if (!empty($posts)) {
          $hostwph_id = $posts[0];

          $user_meta = get_user_meta($user_id);
          if (!empty($user_meta)) {
            foreach ($user_meta as $user_meta_key => $user_meta_value) {
              if (strpos($user_meta_key, 'hostwph_') !== false && !empty($user_meta_value[0])) {
                update_post_meta($hostwph_id, $user_meta_key, $user_meta_value[0]);
              }
            }
          }

          $first_name = get_user_meta($user_id, 'first_name', true);
          if (!empty($first_name)) {
            update_post_meta($hostwph_id, 'hostwph_name', $first_name);
          }

          $last_name = get_user_meta($user_id, 'last_name', true);
          if (!empty($last_name)) {
            update_post_meta($hostwph_id, 'hostwph_surname', $last_name);
          }

          $hostwph_surname_alt = get_user_meta($user_id, 'hostwph_surname_alt', true);

          update_post_meta($hostwph_id, 'hostwph_title', $first_name . ' ' . $last_name . ' ' . $hostwph_surname_alt);
          update_post_meta($hostwph_id, 'hostwph_description', __('Special needs, allergies, important situations to highlight...', 'hostwph'));

          delete_user_meta($user_id, 'hostwph_user_to_guest');
        }
      }
    }
  }
}