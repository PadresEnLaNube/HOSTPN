<?php
/**
 * Define the users management functionality.
 *
 * Loads and defines the users management files for this plugin so that it is ready for user creation, edition or removal.
 *  
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Functions_User {
  public static function is_user_admin($user_id) {
    // HOSTPN_Functions_User::is_user_admin($user_id)
    return user_can($user_id, 'administrator') || user_can($user_id, 'hostpn_role_manager');
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
    $timestamp = get_user_meta($user_id, 'hostpn_child_birthdate', true);

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

  public static function insert_user($hostpn_user_login, $hostpn_user_password, $hostpn_user_email = '', $hostpn_first_name = '', $hostpn_last_name = '', $hostpn_display_name = '', $hostpn_user_nicename = '', $hostpn_user_nickname = '', $hostpn_user_description = '', $hostpn_user_role = [], $hostpn_array_usermeta = [/*['hostpn_key' => 'hostpn_value'], */]) {
    /* $this->insert_user($hostpn_user_login, $hostpn_user_password, $hostpn_user_email = '', $hostpn_first_name = '', $hostpn_last_name = '', $hostpn_display_name = '', $hostpn_user_nicename = '', $hostpn_user_nickname = '', $hostpn_user_description = '', $hostpn_user_role = [], $hostpn_array_usermeta = [['hostpn_key' => 'hostpn_value'], ],); */

    $hostpn_user_array = [
      'first_name' => $hostpn_first_name,
      'last_name' => $hostpn_last_name,
      'display_name' => $hostpn_display_name,
      'user_nicename' => $hostpn_user_nicename,
      'nickname' => $hostpn_user_nickname,
      'description' => $hostpn_user_description,
    ];

    if (!empty($hostpn_user_email)) {
      if (!email_exists($hostpn_user_email)) {
        if (username_exists($hostpn_user_login)) {
          $user_id = wp_create_user($hostpn_user_email, $hostpn_user_password, $hostpn_user_email);
        }else{
          $user_id = wp_create_user($hostpn_user_login, $hostpn_user_password, $hostpn_user_email);
        }
      }else{
        $user_id = get_user_by('email', $hostpn_user_email)->ID;
      }
    }else{
      if (!username_exists($hostpn_user_login)) {
        $user_id = wp_create_user($hostpn_user_login, $hostpn_user_password);
      }else{
        $user_id = get_user_by('login', $hostpn_user_login)->ID;
      }
    }

    if ($user_id && !is_wp_error($user_id)) {
      wp_update_user(array_merge(['ID' => $user_id], $hostpn_user_array));
    }else{
      return false;
    }

    $user = new WP_User($user_id);
    if (!empty($hostpn_user_role)) {
      foreach ($hostpn_user_role as $new_role) {
        $user->add_role($new_role);
      }
    }

    if (!empty($hostpn_array_usermeta)) {
      foreach ($hostpn_array_usermeta as $hostpn_usermeta) {
        foreach ($hostpn_usermeta as $meta_key => $meta_value) {
          if ((!empty($meta_value) || !empty(get_user_meta($user_id, $meta_key, true))) && !is_null($meta_value)) {
            update_user_meta($user_id, $meta_key, $meta_value);
          }
        }
      }
    }

    return $user_id;
  }

  public function hostpn_wp_login($login) {
    $user = get_user_by('login', $login);
    $user_id = $user->ID;
    $current_login_time = get_user_meta($user_id, 'hostpn_current_login', true);

    if(!empty($current_login_time)){
      update_user_meta($user_id, 'hostpn_last_login', $current_login_time);
      update_user_meta($user_id, 'hostpn_current_login', current_time('timestamp'));
    }else {
      update_user_meta($user_id, 'hostpn_current_login', current_time('timestamp'));
      update_user_meta($user_id, 'hostpn_last_login', current_time('timestamp'));
    }

    update_user_meta($user_id, 'users_wph_newsletter_active', true);
  }

  public function userswph_wph_register_fields($register_fields) {
    global $post;
    $post_id = $post->ID ?? 0;
    $hostpn_pages = get_option('hostpn_pages') ?? [];

    if ((in_array($post_id, $hostpn_pages) || is_admin()) && !(current_user_can('administrator') || current_user_can('hostpn_role_manager'))) {
      $relationships = HOSTPN_Data::relationships();

      $register_fields['hostpn_surname_alt'] = [
        'id' => 'hostpn_surname_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'apellido2',
        'label' => esc_html(__('Second surname', 'hostpn')),
        'placeholder' => esc_html(__('Second surname', 'hostpn')),
      ];
      $register_fields['hostpn_introduction'] = [
        'id' => 'hostpn_introduction',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'html',
        'html_content' => '<h3>' . __('Dear guest', 'hostpn') . '</h3>' . '<p>' . __('Spanish State regulations require us to collect the data of travelers who stay in the country (spanish Royal Decree 933/2021, of October 26), so I need you to fill out this form with your data and, if applicable, those of the people who will be with you during the stay. Once you create your guest, you will be able to create your companions, even send them a link to fulfill the form by themselves.', 'hostpn') . '</p>',
      ];
      $register_fields['hostpn_identity'] = [
        'id' => 'hostpn_identity',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'parent' => 'this',
        'options' => ['pas' => esc_html(__('Passport', 'hostpn')), 'nif' => esc_html(__('NIF', 'hostpn')), 'nie' => esc_html(__('NIE', 'hostpn')), 'cif' => esc_html(__('CIF', 'hostpn')), 'otro' => esc_html(__('Other', 'hostpn'))],
        'required' => true,
        'xml' => 'tipoDocumento',
        'label' => esc_html(__('Document type', 'hostpn')),
        'placeholder' => esc_html(__('Document type', 'hostpn')),
      ];
      $register_fields['hostpn_identity_number'] = [
        'id' => 'hostpn_identity_number',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'numeroDocumento',
        'label' => esc_html(__('Document number', 'hostpn')),
        'placeholder' => esc_html(__('Document number', 'hostpn')),
        'description' => esc_html(__('The number/ID of the document that you set on "Document type" field just above.', 'hostpn')),
      ];
      $register_fields['hostpn_identity_support_number'] = [
        'id' => 'hostpn_identity_support_number',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'soporteDocumento',
        'label' => esc_html(__('Document support number', 'hostpn')),
        'placeholder' => esc_html(__('Document support number', 'hostpn')),
      ];
      $register_fields['hostpn_birthdate'] = [
        'id' => 'hostpn_birthdate',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaNacimiento',
        'label' => esc_html(__('Birthdate', 'hostpn')),
        'placeholder' => esc_html(__('Birthdate', 'hostpn')),
      ];
      $register_fields['hostpn_nationality'] = [
        'id' => 'hostpn_nationality',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::countries(),
        'required' => true,
        'xml' => 'nacionalidad',
        'label' => esc_html(__('Nationality', 'hostpn')),
        'placeholder' => esc_html(__('Nationality', 'hostpn')),
      ];
      $register_fields['hostpn_gender'] = [
        'id' => 'hostpn_gender',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => ['h' => esc_html(__('Male', 'hostpn')), 'm' => esc_html(__('Female', 'hostpn')), 'o' => esc_html(__('Other', 'hostpn'))],
        'required' => true,
        'xml' => 'sexo',
        'label' => esc_html(__('Gender', 'hostpn')),
        'placeholder' => esc_html(__('Gender', 'hostpn')),
      ];
      $register_fields['hostpn_address'] = [
        'id' => 'hostpn_address',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostpn')),
        'placeholder' => esc_html(__('Address', 'hostpn')),
      ];
      $register_fields['hostpn_address_alt'] = [
        'id' => 'hostpn_address_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostpn')),
        'placeholder' => esc_html(__('Address complementary information', 'hostpn')),
      ];
      $register_fields['hostpn_country'] = [
        'id' => 'hostpn_country',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::countries(),
        'parent' => 'this',
        'required' => true,
        'xml' => 'pais',
        'label' => esc_html(__('Country', 'hostpn')),
        'placeholder' => esc_html(__('Country', 'hostpn')),
      ];
      $register_fields['hostpn_city_code'] = [
        'id' => 'hostpn_city_code',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::spanish_cities(),
        'parent' => 'hostpn_country',
        'parent_option' => 'esp',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('City', 'hostpn')),
        'placeholder' => esc_html(__('City', 'hostpn')),
      ];
      $register_fields['hostpn_postal_code'] = [
        'id' => 'hostpn_postal_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'codigoPostal',
        'label' => esc_html(__('Postal code', 'hostpn')),
        'placeholder' => esc_html(__('Postal code', 'hostpn')),
      ];
      $register_fields['hostpn_city'] = [
        'id' => 'hostpn_city',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('City', 'hostpn')),
        'placeholder' => esc_html(__('City', 'hostpn')),
      ];
      $register_fields['hostpn_phone'] = [
        'id' => 'hostpn_phone',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'telefono',
        'label' => esc_html(__('Phone', 'hostpn')),
        'placeholder' => esc_html(__('Phone', 'hostpn')),
      ];
      $register_fields['hostpn_phone_alt'] = [
        'id' => 'hostpn_phone_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'telefono2',
        'label' => esc_html(__('Alternative phone', 'hostpn')),
        'placeholder' => esc_html(__('Alternative phone', 'hostpn')),
      ];
      $register_fields['hostpn_email'] = [
        'id' => 'hostpn_email',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'email',
        'required' => true,
        'xml' => 'correo',
        'label' => esc_html(__('Email', 'hostpn')),
        'placeholder' => esc_html(__('Email', 'hostpn')),
      ];
      $register_fields['hostpn_contract_holder_check'] = [
        'id' => 'hostpn_contract_holder_check',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'checkbox',
        'parent' => 'this',
        'label' => esc_html(__('Not the holder of the contract', 'hostpn')),
        'description' => esc_html(__('Check this box if you are not the one who has booked the accomodation. Then you will need to set your relationship with this person.', 'hostpn')),
      ];
        $register_fields['hostpn_relationship'] = [
          'id' => 'hostpn_relationship',
          'class' => 'hostpn-select hostpn-width-100-percent',
          'input' => 'select',
          'options' => $relationships,
          'parent' => 'hostpn_contract_holder_check',
          'parent_option' => 'on',
          'xml' => 'parentesco',
          'label' => esc_html(__('Relationship with contract holder', 'hostpn')),
          'placeholder' => esc_html(__('Relationship', 'hostpn')),
        ];
    }
    
    return $register_fields;
  }

  public function hostpn_user_register($user_id) {
    $user = new WP_User($user_id);
    $user->add_role('hostpn_role_guest');

    $post_functions = new HOSTPN_Functions_Post();
    $guest_functions = new HOSTPN_Post_Type_Guest();
    $hostpn_title = gmdate('Y-m-d H:i:s', current_time('timestamp')) . ' - ' . bin2hex(openssl_random_pseudo_bytes(4));
    $hostpn_post_content = __('Special needs, allergies, important situations to highlight...', 'hostpn');
    update_user_meta($user_id, 'hostpn_user_to_guest', current_time('timestamp'));

    $hostpn_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_post_content, '', sanitize_title(esc_html($hostpn_title)), 'hostpn_guest', 'publish', $user_id);

    if ($hostpn_id && class_exists('MAILWPH')) {
      $email_contents = [];

      ob_start();
      ?>
        <h2><?php esc_html_e('Dear guest', 'hostpn'); ?></h2>
        <p><?php esc_html_e('We have successfully received your registration and application.', 'hostpn'); ?></p>
        <p><?php esc_html_e('You can now add more guests by yourself or share the link to other companions to allow them fulfill their own form.', 'hostpn'); ?></p>
        
        <div class="mailwph-text-align-center mailwph-mt-30 mailwph-mb-50">
          <a href="<?php echo esc_url(home_url('guests')); ?>" class="mailwph-btn"><?php esc_html_e('Guests page', 'hostpn'); ?></a>
        </div>
      <?php
      $mail_content = ob_get_contents(); 
      ob_end_clean(); 

      do_shortcode('[mailwph-sender mailwph_user_to="' . $user_id . '" mailwph_subject="' . esc_html(__('Application received', 'hostpn')) . '"]' . $mail_content . '[/mailwph-sender]');
    }
  }

  public function hostpn_user_to_guest() {
    $user = new WP_User(get_current_user_id());
    $user->add_role('hostpn_role_guest');

    $users_atts = [
      'fields' => 'ids',
      'number' => -1,
      'role' => 'subscriber',
      'meta_key' => 'hostpn_user_to_guest', 
      'meta_compare' => 'exist',
    ];
    
    $users = get_users($users_atts);

    if (!empty($users)) {
      foreach ($users as $user_id) {
        $posts_atts = [
          'fields' => 'ids',
          'numberposts' => -1,
          'post_type' => 'hostpn_guest',
          'post_status' => 'publish', 
          'author' => $user_id, 
        ];
        
        $posts = get_posts($posts_atts);

        if (!empty($posts)) {
          $hostpn_id = $posts[0];

          $user_meta = get_user_meta($user_id);
          if (!empty($user_meta)) {
            foreach ($user_meta as $user_meta_key => $user_meta_value) {
              if (strpos($user_meta_key, 'hostpn_') !== false && !empty($user_meta_value[0])) {
                update_post_meta($hostpn_id, $user_meta_key, $user_meta_value[0]);
              }
            }
          }

          $first_name = get_user_meta($user_id, 'first_name', true);
          if (!empty($first_name)) {
            update_post_meta($hostpn_id, 'hostpn_name', $first_name);
          }

          $last_name = get_user_meta($user_id, 'last_name', true);
          if (!empty($last_name)) {
            update_post_meta($hostpn_id, 'hostpn_surname', $last_name);
          }

          $hostpn_surname_alt = get_user_meta($user_id, 'hostpn_surname_alt', true);

          update_post_meta($hostpn_id, 'hostpn_title', $first_name . ' ' . $last_name . ' ' . $hostpn_surname_alt);
          update_post_meta($hostpn_id, 'hostpn_description', __('Special needs, allergies, important situations to highlight...', 'hostpn'));

          delete_user_meta($user_id, 'hostpn_user_to_guest');
        }
      }
    }
  }
}