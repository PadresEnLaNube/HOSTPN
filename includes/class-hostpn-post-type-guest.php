<?php
/**
 * Guest creator.
 *
 * This class defines Guest options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Guest {
  public function get_fields($guest_id = 0) {    
    $hostpn_fields = [];
      $hostpn_fields['hostpn_name'] = [
        'id' => 'hostpn_name',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'label' => __('Guest name', 'hostpn'),
        'placeholder' => __('Guest name', 'hostpn'),
      ];
      $hostpn_fields['hostpn_surname'] = [
        'id' => 'hostpn_surname',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'label' => __('Guest surname', 'hostpn'),
        'placeholder' => __('Guest surname', 'hostpn'),
      ];
      $hostpn_fields['hostpn_surname_alt'] = [
        'id' => 'hostpn_surname_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'apellido2',
        'label' => esc_html(__('Guest second surname', 'hostpn')),
        'placeholder' => esc_html(__('Guest second surname', 'hostpn')),
      ];
      $hostpn_fields['hostpn_title'] = [
        'id' => 'hostpn_title',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'hidden',
        'value' => gmdate('Y-m-d H:i:s', current_time('timestamp')) . ' - ' . bin2hex(openssl_random_pseudo_bytes(4)),
      ];
      $hostpn_fields['hostpn_description'] = [
        'id' => 'hostpn_description',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'hidden',
        'value' => __('Special needs, allergies, important situations to highlight...', 'hostpn'),
      ];
    return $hostpn_fields;
  }

  public function get_fields_meta($guest_id = 0) {
    $relationships = HOSTPN_Data::relationships();
    
    $hostpn_fields_meta = [];
      $hostpn_fields_meta['hostpn_identity'] = [
        'id' => 'hostpn_identity',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'parent' => 'this',
        'required' => true,
        'options' => ['nif' => esc_html(__('NIF', 'hostpn')), 'nie' => esc_html(__('NIE', 'hostpn')), 'pas' => esc_html(__('Passport', 'hostpn')), 'cif' => esc_html(__('CIF', 'hostpn')), 'otro' => esc_html(__('Other', 'hostpn'))],
        'xml' => 'tipoDocumento',
        'label' => esc_html(__('Document type', 'hostpn')),
        'placeholder' => esc_html(__('Document type', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_identity_number'] = [
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
      $hostpn_fields_meta['hostpn_identity_support_number'] = [
        'id' => 'hostpn_identity_support_number',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'soporteDocumento',
        'label' => esc_html(__('Document support number', 'hostpn')),
        'placeholder' => esc_html(__('Document support number', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_birthdate'] = [
        'id' => 'hostpn_birthdate',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaNacimiento',
        'label' => esc_html(__('Birthdate', 'hostpn')),
        'placeholder' => esc_html(__('Birthdate', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_nationality'] = [
        'id' => 'hostpn_nationality',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::countries(),
        'required' => true,
        'xml' => 'fechaNacimiento',
        'label' => esc_html(__('Nationality', 'hostpn')),
        'placeholder' => esc_html(__('Nationality', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_gender'] = [
        'id' => 'hostpn_gender',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => ['h' => esc_html(__('Male', 'hostpn')), 'm' => esc_html(__('Female', 'hostpn')), 'o' => esc_html(__('Other', 'hostpn'))],
        'required' => true,
        'xml' => 'sexo',
        'label' => esc_html(__('Gender', 'hostpn')),
        'placeholder' => esc_html(__('Gender', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_address'] = [
        'id' => 'hostpn_address',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostpn')),
        'placeholder' => esc_html(__('Address', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_address_alt'] = [
        'id' => 'hostpn_address_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostpn')),
        'placeholder' => esc_html(__('Address complementary information', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_country'] = [
        'id' => 'hostpn_country',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::countries(),
        'required' => true,
        'xml' => 'pais',
        'label' => esc_html(__('Country', 'hostpn')),
        'placeholder' => esc_html(__('Country', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_postal_code'] = [
        'id' => 'hostpn_postal_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'codigoPostal',
        'label' => esc_html(__('Postal code', 'hostpn')),
        'placeholder' => esc_html(__('Postal code', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_city_code'] = [
        'id' => 'hostpn_city_code',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::spanish_cities(),
        'required' => true,
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('City', 'hostpn')),
        'placeholder' => esc_html(__('City', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_city'] = [
        'id' => 'hostpn_city',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('City', 'hostpn')),
        'placeholder' => esc_html(__('City', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_phone'] = [
        'id' => 'hostpn_phone',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'telefono',
        'label' => esc_html(__('Phone', 'hostpn')),
        'placeholder' => esc_html(__('Phone', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_phone_alt'] = [
        'id' => 'hostpn_phone_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'telefono2',
        'label' => esc_html(__('Alternative phone', 'hostpn')),
        'placeholder' => esc_html(__('Alternative phone', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_email'] = [
        'id' => 'hostpn_email',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'email',
        'required' => true,
        'xml' => 'correo',
        'label' => esc_html(__('Email', 'hostpn')),
        'placeholder' => esc_html(__('Email', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_contract_holder_check'] = [
        'id' => 'hostpn_contract_holder_check',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'checkbox',
        'parent' => 'this',
        'label' => esc_html(__('I´m not the holder of the contract', 'hostpn')),
        'description' => esc_html(__('Check this box if you are not the one who has booked the accomodation. Then you will need to set your relationship with this person.', 'hostpn')),
      ];
        $hostpn_fields_meta['hostpn_relationship'] = [
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

    return $hostpn_fields_meta;
  }

  /**
   * Register Guest.
   *
   * @since    1.0.0
   */
  public function register_post_type() {
    $labels = [
      'name'                => _x('Guest', 'Post Type general name', 'hostpn'),
      'singular_name'       => _x('Guest', 'Post Type singular name', 'hostpn'),
      'menu_name'           => esc_html(__('Guests', 'hostpn')),
      'parent_item_colon'   => esc_html(__('Parent Guest', 'hostpn')),
      'all_items'           => esc_html(__('All Guests', 'hostpn')),
      'view_item'           => esc_html(__('View Guest', 'hostpn')),
      'add_new_item'        => esc_html(__('Add new Guest', 'hostpn')),
      'add_new'             => esc_html(__('Add new Guest', 'hostpn')),
      'edit_item'           => esc_html(__('Edit Guest', 'hostpn')),
      'update_item'         => esc_html(__('Update Guest', 'hostpn')),
      'search_items'        => esc_html(__('Search Guests', 'hostpn')),
      'not_found'           => esc_html(__('Not Guest found', 'hostpn')),
      'not_found_in_trash'  => esc_html(__('Not Guest found in Trash', 'hostpn')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostpn')) ? get_option('hostpn') : 'hostpn'), 'with_front' => false],
      'label'               => esc_html(__('Guest', 'hostpn')),
      'description'         => esc_html(__('Guest description', 'hostpn')),
      'supports'            => ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ],
      'hierarchical'        => true,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'menu_icon'           => esc_url(HOSTPN_URL . 'assets/media/hostpn-menu-icon.svg'),
      'can_export'          => false,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'capability_type'     => 'page',
      'taxonomies'          => HOSTPN_ROLE_CAPABILITIES,
      'show_in_rest'        => false, /* REST API */
    ];

    register_post_type('hostpn_guest', $args);
    add_theme_support('post-thumbnails', ['page', 'hostpn_guest']);
  }

  /**
   * Add Guest dashboard metabox.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    add_meta_box('hostpn_meta_box', esc_html(__('Guest details', 'hostpn')), [$this, 'hostpn_meta_box_function'], 'hostpn_guest', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines Guest dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostpn_meta_box_function($post) {
    foreach (self::get_fields() as $hostpn_field) {
      HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $post->ID);
    }

    foreach (self::get_fields_meta() as $hostpn_field_meta) {
      HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post', $post->ID);
    }
  }

  public function save_post($post_id, $cpt, $update) {
    if (array_key_exists('hostpn_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_nonce'])), 'hostpn-nonce')) {
      echo wp_json_encode(['error_key' => 'hostpn_nonce_error', ]);exit();
    }

    if (!array_key_exists('hostpn_duplicate', $_POST)) {
      foreach (self::get_fields() as $wph_field) {
        $wph_input = array_key_exists('input', $wph_field) ? $wph_field['input'] : '';

        if (array_key_exists($wph_field['id'], $_POST) || $wph_input == 'html_multi') {
          $wph_value = array_key_exists($wph_field['id'], $_POST) ? HOSTPN_Forms::sanitizer($_POST[$wph_field['id']], $wph_field['input'], !empty($wph_field['type']) ? $wph_field['type'] : '') : '';

          if (!empty($wph_input)) {
            switch ($wph_input) {
              case 'input':
                if (array_key_exists('type', $wph_field)) {
                  switch ($wph_field['type']) {
                    case 'checkbox':
                      if (isset($_POST[$wph_field['id']])) {
                        update_post_meta($post_id, $wph_field['id'], $wph_value);
                      }else{
                        update_post_meta($post_id, $wph_field['id'], '');
                      }

                      break;
                    case 'file':
                    update_user_meta(1, 'wph_dev_file_' . $wph_field['id'], $wph_field['type']);
                    update_user_meta(1, 'wph_dev_wph_value', $wph_value);
                      $plugin_attachment = new HOSTPN_Functions_Attachment();
                      $attachment_id = $plugin_attachment->insert_attachment_from_input($wph_value);
                    update_user_meta(1, 'wph_dev_attachment_id', $attachment_id);
                      
                      update_post_meta($post_id, $wph_field['id'], $attachment_id);
                    default:
                      update_post_meta($post_id, $wph_field['id'], $wph_value);
                      break;
                  }
                }

                break;
              case 'select':
                if (array_key_exists('multiple', $wph_field) && $wph_field['multiple']) {
                  $multi_array = [];
                  $empty = true;

                  foreach ($_POST[$wph_field['id']] as $multi_value) {
                    $multi_array[] = HOSTPN_Forms::sanitizer($multi_value, $wph_field['input'], !empty($wph_field['type']) ? $wph_field['type'] : '');
                  }

                  update_post_meta($post_id, $wph_field['id'], $multi_array);
                }else{
                  update_post_meta($post_id, $wph_field['id'], $wph_value);
                }
                
                break;
              case 'html_multi':
                foreach ($wph_field['html_multi_fields'] as $wph_multi_field) {
                  if (array_key_exists($wph_multi_field['id'], $_POST)) {
                    $multi_array = [];
                    $empty = true;

                    foreach ($_POST[$wph_multi_field['id']] as $multi_value) {
                      if (!empty($multi_value)) {
                        $empty = false;
                      }

                      $multi_array[] = HOSTPN_Forms::sanitizer($multi_value, $wph_multi_field['input'], !empty($wph_multi_field['type']) ? $wph_multi_field['type'] : '');
                    }

                    if (!$empty) {
                      update_post_meta($post_id, $wph_multi_field['id'], $multi_array);
                    }else{
                      update_post_meta($post_id, $wph_multi_field['id'], '');
                    }
                  }
                }

                break;
              default:
                update_post_meta($post_id, $wph_field['id'], $wph_value);
                break;
            }
          }
        }else{
          update_post_meta($post_id, $wph_field['id'], '');
        }
      }
    }
  }

  public function hostpn_form_save($element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type) {
    if ($post_type == 'hostpn_guest') {
      switch ($hostpn_form_type) {
        case 'post':
          switch ($hostpn_form_subtype) {
            case 'post_new':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              $post_functions = new HOSTPN_Functions_Post();
              $guest_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_description, '', sanitize_title(esc_html($hostpn_title)), $post_type, 'publish', get_current_user_id());

              if ($guest_id) {
                $email_contents = [];
                if (!empty($key_value)) {
                  foreach ($key_value as $key => $value) {
                    update_post_meta($guest_id, $key, $value);
                    $email_contents[$key] = $value;
                  }
                }

                if (class_exists('MAILWPH')) {
                  ob_start();
                  ?>
                    <h2><?php esc_html_e('Dear guest', 'mailwph'); ?></h2>
                    <p><?php esc_html_e('We have received your application successfully.', 'hostpn'); ?></p>
                    <p><?php esc_html_e('Please check the information fulfilled in the form:', 'hostpn'); ?></p>

                    <ul>
                      <?php foreach (self::get_fields_meta() as $hostpn_field_meta): ?>
                        <?php if (!empty($email_contents[$hostpn_field_meta['id']])): ?>
                          <li><?php echo $hostpn_field_meta['label']; ?>: <?php echo $email_contents[$hostpn_field_meta['id']]; ?></li>
                        <?php endif ?>
                      <?php endforeach ?>
                    </ul>

                    <p><?php esc_html_e('You can now add more guests or share the link to other companions to allow them fulfill their own form.', 'hostpn'); ?></p>
                    <div class="mailwph-text-align-center mailwph-mt-30 mailwph-mb-50">
                      <a href="<?php echo esc_url(home_url('guests')); ?>" class="mailwph-btn"><?php esc_html_e('Guests page', 'mailwph'); ?></a>
                    </div>
                  <?php
                  $mail_content = ob_get_contents(); 
                  ob_end_clean(); 

                  do_shortcode('[mailwph-sender mailwph_user_to="' . get_current_user_id() . '" mailwph_subject="' . esc_html(__('New guest application received', 'mailwph')) . '"]' . $mail_content . '[/mailwph-sender]');
                }
              }

              break;
            case 'post_edit':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              $guest_id = $element_id;
              wp_update_post(['ID' => $guest_id, 'post_title' => $hostpn_title, 'post_content' => $hostpn_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($guest_id, $key, $value);
                }
              }

              break;
            case 'post_check':
              self::hostpn_history_add($element_id);
              break;
            case 'post_uncheck':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              break;
          }
        case 'user':
          switch ($hostpn_form_subtype) {
            case 'user_alt_new':
              $user_password = bin2hex(openssl_random_pseudo_bytes(16));
              $user_email = !empty($_POST['user_email']) ? HOSTPN_Forms::sanitizer(wp_unslash($_POST['user_email'])) : '';

              $user_alt_id = HOSTPN_Functions_User::insert_user($user_email, $user_password, $user_email);

              if (!empty($user_alt_id) && !empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_user_meta($user_alt_id, $key, $value);
                }
              }

              update_user_meta($user_alt_id, 'hostpn_host_parent', get_current_user_id());

              break;
          }
          break;
      }
    }
  }

  public function list_wrapper() {
    ob_start();
    
    if (is_user_logged_in()) {
      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id()) || current_user_can('hostpn_role_guest')) {
        ?>
          <div class="hostpn-list-wrapper hostpn-mt-50 hostpn-mb-150" data-hostpn-post-type="hostpn_guest">
            <div class="hostpn-menu-more-overlay hostpn-z-index-9"></div>

            <div class="hostpn-display-table hostpn-width-100-percent hostpn-max-width-500 hostpn-margin-auto hostpn-position-relative">
              <div class="hostpn-display-inline-table hostpn-width-90-percent">
                <h4 class="hostpn-mb-30"><?php esc_html_e('Guests', 'hostpn'); ?></h4>
              </div>

              <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-right">
                <i class="material-icons-outlined hostpn-list-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                <div class="hostpn-list-more hostpn-z-index-99 hostpn-display-none-soft">
                  <ul class="hostpn-list-style-none">
                    <li>
                      <a href="#" class="hostpn-order-posts hostpn-text-decoration-none">
                        <div class="wph-display-table hostpn-width-100-percent">
                          <div class="hostpn-display-inline-table hostpn-width-70-percent">
                            <p><?php esc_html_e('Order guests', 'hostpn'); ?></p>
                          </div>
                          <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                            <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">low_priority</i>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <?php echo self::list(); ?>
          </div>
        <?php
      }else{
        echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="workspace_premium" hostpn_call_to_action_title="' . __('Capabilities', 'hostpn') . '" hostpn_call_to_action_content="' . __('Capabilities needed', 'hostpn') . '"]');
      }
    }else{
      echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="account_circle" hostpn_call_to_action_title="' . __('Account needed', 'hostpn') . '" hostpn_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostpn') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\'>' . __('login', 'hostpn') . '</a>' . ' ' . __('or', 'hostpn') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\' data-userswph-action=\'register\'>' . __('register', 'hostpn') . '</a>' . ' ' . __('to go ahead', 'hostpn') . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . __('Login', 'hostpn') . '" hostpn_call_to_action_button_class="userswph-profile-popup-btn" hostpn_call_to_action_class="hostpn-mb-100"]');
    }

    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function list() {
    if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
      $guests_atts = [
        'fields' => 'ids',
        'numberposts' => -1,
        'post_type' => 'hostpn_guest',
        'post_status' => 'any', 
        'orderby' => 'menu_order', 
        'order' => 'ASC', 
      ];
    }else{
      $guests_atts = [
        'fields' => 'ids',
        'numberposts' => -1,
        'post_type' => 'hostpn_guest',
        'post_status' => 'any', 
        'author' => get_current_user_id(), 
        'orderby' => 'menu_order', 
        'order' => 'ASC', 
      ];
    }

    $guests = get_posts($guests_atts);

    ob_start();
    ?>
      <ul class="hostpn-list hostpn-hostpn_guest-list hostpn-list-host hostpn-guests hostpn-list-style-none hostpn-max-width-500 hostpn-margin-auto">
        <?php if (!empty($guests)): ?>
          <?php foreach ($guests as $guest_id): ?>
            <li class="hostpn-list-element hostpn-mb-10" data-hostpn-element-id="<?php echo $guest_id; ?>">
              <div class="hostpn-display-table hostpn-width-100-percent">
                <div class="hostpn-display-inline-table hostpn-width-80-percent">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-guest-view" data-hostpn-ajax-type="hostpn_guest_view">
                    <span><?php echo esc_html(self::guest_name($guest_id)); ?></span>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                  <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                  <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                    <ul class="hostpn-list-style-none">
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-guest-view" data-hostpn-ajax-type="hostpn_guest_view">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('View guest', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-guest-edit" data-hostpn-ajax-type="hostpn_guest_edit">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Edit guest', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-guest-duplicate hostpn-text-decoration-none">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Duplicate guest', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-guest-remove">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Remove guest', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">delete</i>
                            </div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach ?>
        <?php endif ?>

        <li class="hostpn-mt-50 hostpn-guest hostpn-list-element" data-hostpn-element-id="0">
          <a href="#" id="hostpn-popup-guest-add-btn" class="hostpn-popup-open-ajax hostpn-text-decoration-none hostpn-mb-10" data-hostpn-popup-id="hostpn-popup-guest-add" data-hostpn-ajax-type="hostpn_guest_new">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25">add</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent">
                <?php if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())): ?>
                  <?php esc_html_e('Add guest', 'hostpn'); ?>
                <?php else: ?>
                  <?php esc_html_e('Add companion', 'hostpn'); ?>
                <?php endif ?>
              </div>
            </div>
          </a>
          
          <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-width-25 hostpn-font-size-20">share</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent">
                <?php esc_html_e('Share link to other guests', 'hostpn'); ?>
              </div>
            </div>
          </a>
        </li>
      </ul>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function view($guest_id) {  
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="guest-view">
        <h4 class="hostpn-text-align-center"><?php echo self::guest_name($guest_id); ?></h4>
        
        <div class="hostpn-word-wrap-break-word">
          <p><?php echo str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($guest_id)->post_content)); ?></p>
        </div>

        <div class="guest-view">
          <?php foreach (self::get_fields_meta() as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $guest_id, 1); ?>
          <?php endforeach ?>
        </div>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function new() {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="guest-new">
        <h4 class="hostpn-mb-30"><?php esc_html_e('Add guest', 'hostpn'); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_guest">      
          <?php foreach (self::get_fields() as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post'); ?>
          <?php endforeach ?>

          <?php foreach (self::get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post', $guest_id); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-post-type="hostpn_guest" data-hostpn-subtype="post_new" type="submit" value="<?php _e('Create guest', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function edit($guest_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="guest-edit">
        <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
        <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo self::guest_name($guest_id); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_guest">
          <?php foreach (self::get_fields($guest_id) as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $guest_id); ?>
          <?php endforeach ?>

          <?php foreach (self::get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post', $guest_id); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_edit" type="submit" data-hostpn-post-type="hostpn_guest" data-hostpn-post-id="<?php echo esc_attr($guest_id); ?>" value="<?php _e('Save guest', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function check($guest_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="guest-check">
        <?php if (!empty(get_post_meta($guest_id, 'hostpn_accomplish_date', true))): ?>
          <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Marking as not completed', 'hostpn'); ?></p>
          <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo self::guest_name($guest_id); ?></h4>

          <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_guest">
            <?php foreach (self::get_fields_check($guest_id) as $hostpn_field_check): ?>
              <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_check, 'post', $guest_id, 1); ?>
            <?php endforeach ?>

            <div class="hostpn-text-align-right">
              <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_uncheck" type="submit" data-hostpn-post-id="<?php echo esc_attr($guest_id); ?>" value="<?php _e('Not completed', 'hostpn'); ?>"/>
            </div>
          </form>
        <?php else: ?>
          <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Marking as completed', 'hostpn'); ?></p>
          <h4 class="hostpn-text-align-center"><?php echo self::guest_name($guest_id); ?></h4>

          <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_guest">
            <?php foreach (self::get_fields_check($guest_id) as $hostpn_field_check): ?>
              <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_check, 'post', $guest_id); ?>
            <?php endforeach ?>

            <div class="hostpn-text-align-right">
              <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_check" type="submit" data-hostpn-post-id="<?php echo esc_attr($guest_id); ?>" value="<?php _e('Completed', 'hostpn'); ?>"/>
            </div>
          </form>
        <?php endif ?>
      </form>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function history_add($guest_id) {  
    $host_meta = get_post_meta($guest_id);
    $host_meta_array = [];

    if (!empty($host_meta)) {
      foreach ($host_meta as $host_meta_key => $host_meta_value) {
        if (strpos($host_meta_key, 'hostpn_') !== false && !empty($host_meta_value[0])) {
          $host_meta_array[$host_meta_key] = $host_meta_value[0];
        }
      }
    }
    
    if(empty(get_post_meta($guest_id, 'hostpn_guest_history', true))) {
      update_post_meta($guest_id, 'hostpn_guest_history', [strtotime('now') => $host_meta_array]);
    }else{
      $wph_post_meta_new = get_post_meta($guest_id, 'hostpn_guest_history', true);
      $wph_post_meta_new[strtotime('now')] = $host_meta_array;
      update_post_meta($guest_id, 'hostpn_guest_history', $wph_post_meta_new);
    }
  }

  public function next($guest_id) {
    $hostpn_periodicity = get_post_meta($guest_id, 'hostpn_periodicity', true);
    $hostpn_date = get_post_meta($guest_id, 'hostpn_date', true);
    $hostpn_time = get_post_meta($guest_id, 'hostpn_time', true);

    $host_timestamp = strtotime($hostpn_date . ' ' . $hostpn_time);

    if (!empty($hostpn_periodicity) && !empty($host_timestamp)) {
      $now = strtotime('now');

      while ($host_timestamp < $now) {
        $host_timestamp = strtotime('+' . str_replace('_', ' ', $hostpn_periodicity), $host_timestamp);
      }

      return $host_timestamp;
    }
  }

  public function owners($guest_id) {
    $hostpn_owners = get_post_meta($guest_id, 'hostpn_owners', true);
    $hostpn_owners_array = [get_post($guest_id)->post_author];

    if (!empty($hostpn_owners)) {
      foreach ($hostpn_owners as $owner_id) {
        $hostpn_owners_array[] = $owner_id;
      }
    }

    return array_unique($hostpn_owners_array);
  }

    public function guest_name($guest_id) {
      return get_post_meta($guest_id, 'hostpn_name', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname_alt', true);
    }
}