<?php
/**
 * Guest creator.
 *
 * This class defines Guest options, menus and templates.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Post_Type_Guest {
  public function get_fields($guest_id = 0) {
    $hostwph_fields = [];
      $hostwph_fields['hostwph_title'] = [
        'id' => 'hostwph_title',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'value' => !empty($guest_id) ? get_the_title($guest_id) : gmdate('Y-m-d H:i:s', current_time('timestamp')),
        'label' => __('Part title', 'hostwph'),
        'placeholder' => __('Part title', 'hostwph'),
        'description' => __('This title will help you to remind and find this host in the future', 'hostwph'),
      ];
      $hostwph_fields['hostwph_description'] = [
        'id' => 'hostwph_description',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'value' => !empty($guest_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($guest_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Part description', 'hostwph'),
      ];
    return $hostwph_fields;
  }

  public function get_fields_meta_OLD() {
    $accomodation_id = !empty($_GET['hostwph_accomodation_id']) ? HOSTWPH_Forms::sanitizer($_GET['hostwph_accomodation_id']) : '';
    $hostwph_fields_meta = [];

    $hostwph_fields_meta = [];
      $posts_atts = [
        'fields' => 'ids',
        'numberposts' => -1,
        'post_type' => 'hostwph_accomodation',
        'post_status' => 'any', 
      ];
      
      if (class_exists('Polylang')) {
        $posts_atts['lang'] = pll_current_language('slug');
      }

      $accomodations = get_posts($posts_atts);
      
      if (HOSTWPH_Functions_User::is_user_admin(get_current_user_id())) {
        $hostwph_accomodation_options = [];
        
        foreach ($accomodations as $accomodation_id) {
          $hostwph_accomodation_options[$accomodation_id] = esc_html(get_the_title($accomodation_id));
        }

        $hostwph_fields_meta['hostwph_accomodation_id'] = [
          'id' => 'hostwph_accomodation_id',
          'class' => 'hostwph-select hostwph-width-100-percent',
          'input' => 'select',
          'options' => $hostwph_accomodation_options,
          'required' => true,
          'label' => __('Accomodation', 'hostwph'),
          'placeholder' => __('Accomodation', 'hostwph'),
        ];

        $hostwph_page_accomodation = !empty(get_option('hostwph_pages_accomodation')) ? get_option('hostwph_pages_accomodation')[0] : url_to_postid(home_url());

        $hostwph_fields_meta['hostwph_accomodation_add'] = [
          'id' => 'hostwph_accomodation_add',
          'input' => 'html',
          'html_content' => '<div class="hostwph-width-100-percent hostwph-text-align-right hostwph-mb-20"><a class="hostwph-font-size-small" href="' . esc_url(get_permalink($hostwph_page_accomodation)) . '"><i class="material-icons-outlined hostwph-vertical-align-middle">add</i>' . esc_html(__('Add accomodation', 'hostwph')) . '</a></div>',
        ];
      }else{
        $current_accomodation_id = !empty($accomodation_id) ? $accomodation_id : $accomodations[0];

        $hostwph_fields_meta['hostwph_accomodation_id'] = [
          'id' => 'hostwph_accomodation_id',
          'class' => 'hostwph-input hostwph-width-100-percent',
          'input' => 'input',
          'type' => 'hidden',
          'value' => $current_accomodation_id,
        ];
      }


      $hostwph_people_options = [];
      if (HOSTWPH_Functions_User::is_user_admin(get_current_user_id())) {
        $users_atts = [
          'fields' => 'ids',
          'number' => -1,
          'orderby' => 'display_name', 
          'order' => 'ASC',
        ];
      }else{
        $users_atts = [
          'fields' => 'ids',
          'number' => -1,
          'meta_key' => 'hostwph_contract_holder', 
          'meta_value' => get_current_user_id(),
          'meta_compare' => '=',
          'orderby' => 'display_name', 
          'order' => 'ASC',
        ];
      }

      foreach (get_users($users_atts) as $user_id) {
        $hostwph_people_options[$user_id] = HOSTWPH_Functions_User::get_user_name($user_id);
      }

      $hostwph_fields_meta['hostwph_people'] = [
        'id' => 'hostwph_people',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => $hostwph_people_options,
        'multiple' => true,
        'required' => true,
        'xml' => 'persona',
        'label' => __('People hosted', 'hostwph'),
        'placeholder' => __('People hosted', 'hostwph'),
      ];

      $hostwph_fields_meta['hostwph_people_add'] = [
        'id' => 'hostwph_people_add',
        'input' => 'html',
        'html_content' => '<div class="hostwph-width-100-percent hostwph-text-align-right hostwph-mb-20"><a class="hostwph-font-size-small" href="' . esc_url(get_permalink($hostwph_page_accomodation)) . '"><i class="material-icons-outlined hostwph-vertical-align-middle">add</i>' . esc_html(__('Add guest', 'hostwph')) . '</a></div>',
      ];

      $hostwph_fields_meta['hostwph_contract_holder'] = [
        'id' => 'hostwph_contract_holder',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => $hostwph_people_options,
        'required' => true,
        'xml' => 'rol',
        'label' => __('Contract holder', 'hostwph'),
        'placeholder' => __('Contract holder', 'hostwph'),
      ];

      $hostwph_fields_meta['hostwph_date'] = [
        'id' => 'hostwph_date',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaContrato',
        'label' => __('Contract date', 'hostwph'),
        'placeholder' => __('Contract date', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_time'] = [
        'id' => 'hostwph_time',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'required' => true,
        'label' => __('Contract time', 'hostwph'),
        'placeholder' => __('Contract time', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_check_in_date'] = [
        'id' => 'hostwph_check_in_date',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaEntrada',
        'label' => __('Check-in date', 'hostwph'),
        'placeholder' => __('Check-in date', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_check_in_time'] = [
        'id' => 'hostwph_check_in_time',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'required' => true,
        'label' => __('Check-in time', 'hostwph'),
        'placeholder' => __('Check-in time', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_check_out_date'] = [
        'id' => 'hostwph_check_out_date',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaSalida',
        'label' => __('Check-out date', 'hostwph'),
        'placeholder' => __('Check-out date', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_check_out_time'] = [
        'id' => 'hostwph_check_out_time',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'required' => true,
        'label' => __('Check-out time', 'hostwph'),
        'placeholder' => __('Check-out time', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_people_number'] = [
        'id' => 'hostwph_people_number',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'numPersonas',
        'label' => __('People', 'hostwph'),
        'placeholder' => __('People', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_rooms'] = [
        'id' => 'hostwph_rooms',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'numHabitaciones',
        'label' => __('Number of rooms', 'hostwph'),
        'placeholder' => __('Number of rooms', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_internet'] = [
        'id' => 'hostwph_internet',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'checkbox',
        'xml' => 'internet',
        'label' => __('Internet available', 'hostwph'),
        'placeholder' => __('Internet available', 'hostwph'),
      ];

      $payment_type_options = ['EFECT' => esc_html(__('Cash', 'hostwph')), 'TARJT' => esc_html(__('Credit card', 'hostwph')), 'PLATF' => esc_html(__('Payment platform', 'hostwph')), 'TRANS' => esc_html(__('Transfer', 'hostwph')), 'MOVIL' => esc_html(__('Mobile payment', 'hostwph')), 'TREG' => esc_html(__('Gift card', 'hostwph')), 'DESTI' => esc_html(__('Payment at destination', 'hostwph')), 'OTRO' => esc_html(__('Other payment methods', 'hostwph')), ];

      $hostwph_fields_meta['hostwph_payment_type'] = [
        'id' => 'hostwph_payment_type',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => $payment_type_options,
        'parent' => 'this',
        'xml' => 'tipoPago',
        'label' => __('Payment type', 'hostwph'),
        'placeholder' => __('Payment type', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_payment_expiration'] = [
        'id' => 'hostwph_payment_expiration',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'parent' => 'hostwph_payment_type',
        'parent_option' => 'TARJT',
        'type' => 'month',
        'xml' => 'caducidadTarjeta',
        'label' => __('Card expiration date', 'hostwph'),
        'placeholder' => __('Card expiration date', 'hostwph'),
        'description' => __('Expiration date of the card.', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_payment_date'] = [
        'id' => 'hostwph_payment_date',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'xml' => 'fechaPago',
        'label' => __('Payment date', 'hostwph'),
        'placeholder' => __('Payment date', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_payment_method'] = [
        'id' => 'hostwph_payment_method',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'medioPago',
        'label' => __('Payment method', 'hostwph'),
        'placeholder' => __('Payment method', 'hostwph'),
        'description' => __('Payment method identification: card type and number, bank account IBAN, mobile number, etc.', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_payment_holder'] = [
        'id' => 'hostwph_payment_holder',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'titular',
        'label' => __('Payment holder', 'hostwph'),
        'placeholder' => __('Payment holder', 'hostwph'),
        'description' => __('Name and surname of the holder of the payment.', 'hostwph'),
      ];

      $hostwph_fields_meta['hostwph_nonce'] = [
        'id' => 'hostwph_nonce',
        'input' => 'input',
        'type' => 'nonce',
        'xml' => '',
      ];
    return $hostwph_fields_meta;
  }

  public function get_fields_meta() {
    $hostwph_fields_meta = [];

    $hostwph_fields_meta = [];
      $hostwph_fields_meta['hostwph_test'] = [
        'id' => 'hostwph_test',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => __('test hosted', 'hostwph'),
        'placeholder' => __('test hosted', 'hostwph'),
      ];


      $hostwph_fields_meta['hostwph_nonce'] = [
        'id' => 'hostwph_nonce',
        'input' => 'input',
        'type' => 'nonce',
        'xml' => '',
      ];
    return $hostwph_fields_meta;
  }

  public function get_fields_check($guest_id) {
    $hostwph_fields_check = [];
      $hostwph_fields_check['hostwph_accomplish_date'] = [
        'id' => 'hostwph_accomplish_date',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'value' => date('Y-m-d', strtotime('now')),
        'required' => 'true',
        'label' => __('Host accomplish date', 'hostwph'),
        'placeholder' => __('Host accomplish date', 'hostwph'),
      ];
      $hostwph_fields_check['hostwph_accomplish_time'] = [
        'id' => 'hostwph_accomplish_time',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'value' => date('H:i', strtotime('now')),
        'label' => __('Host accomplish time', 'hostwph'),
        'placeholder' => __('Host accomplish time', 'hostwph'),
      ];

      $hostwph_owners_checkbox = get_post_meta($guest_id, 'hostwph_owners_checkbox', true);
      $hostwph_timed_expected = get_post_meta($guest_id, 'hostwph_timed_expected', true);
      $hostwph_timed_checkbox = get_post_meta($guest_id, 'hostwph_timed_checkbox', true);
      $hostwph_comments_checkbox = get_post_meta($guest_id, 'hostwph_comments_checkbox', true);

      if ($hostwph_owners_checkbox == 'on') {
        $hostwph_guest_responsible_options = [];
        $hostwph_guest_owners = get_post_meta($guest_id, 'hostwph_owners', true);
        if (!empty($hostwph_guest_owners)) {
          foreach ($hostwph_guest_owners as $owner_id) {
            $hostwph_guest_responsible_options[$owner_id] = HOSTWPH_Functions_User::get_user_name($owner_id);
          }
        }

        $hostwph_fields_check['hostwph_responsible'] = [
          'id' => 'hostwph_responsible',
          'class' => 'hostwph-select hostwph-width-100-percent',
          'input' => 'select',
          'options' => $hostwph_guest_responsible_options,
          'label' => __('Host accomplished by', 'hostwph'),
          'placeholder' => __('Select contact', 'hostwph'),
        ];
      }

      if ($hostwph_timed_checkbox == 'on') {
        $hostwph_fields_check['hostwph_time_spent'] = [
          'id' => 'hostwph_time_spent',
          'class' => 'hostwph-input hostwph-width-100-percent',
          'input' => 'input',
          'type' => 'time',
          'label' => __('Time spent (Hours:Minutes)', 'hostwph'),
          'description' => (!empty($hostwph_timed_expected)) ? __('The estimated time was', 'hostwph') . ' ' . $hostwph_timed_expected : esc_html(__('This Guest was not timed.', 'hostwph')),
          'placeholder' => __('Time spent (Hours:Minutes)', 'hostwph'),
        ];
      }

      if ($hostwph_comments_checkbox == 'on') {
        $hostwph_fields_check['hostwph_comments'] = [
          'id' => 'hostwph_comments',
          'class' => 'hostwph-input hostwph-width-100-percent',
          'input' => 'textarea',
          'label' => __('Host comments', 'hostwph'),
          'placeholder' => __('Host comments', 'hostwph'),
        ];
      }

      $hostwph_fields_check['hostwph_user_id'] = [
        'id' => 'hostwph_user_id',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'hidden',
        'value' => get_current_user_id(),
      ];

    return $hostwph_fields_check;
  }

  /**
   * Register Guest.
   *
   * @since    1.0.0
   */
  public function register_post_type() {
    $labels = [
      'name'                => _x('Host', 'Post Type general name', 'hostwph'),
      'singular_name'       => _x('Host', 'Post Type singular name', 'hostwph'),
      'menu_name'           => esc_html(__('Guests', 'hostwph')),
      'parent_item_colon'   => esc_html(__('Parent Guest', 'hostwph')),
      'all_items'           => esc_html(__('All Guests', 'hostwph')),
      'view_item'           => esc_html(__('View Guest', 'hostwph')),
      'add_new_item'        => esc_html(__('Add new Guest', 'hostwph')),
      'add_new'             => esc_html(__('Add new Guest', 'hostwph')),
      'edit_item'           => esc_html(__('Edit Guest', 'hostwph')),
      'update_item'         => esc_html(__('Update Guest', 'hostwph')),
      'search_items'        => esc_html(__('Search Guests', 'hostwph')),
      'not_found'           => esc_html(__('Not Guest found', 'hostwph')),
      'not_found_in_trash'  => esc_html(__('Not Guest found in Trash', 'hostwph')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostwph')) ? get_option('hostwph') : 'hostwph'), 'with_front' => false],
      'label'               => esc_html(__('Host', 'hostwph')),
      'description'         => esc_html(__('Host description', 'hostwph')),
      'supports'            => ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ],
      'hierarchical'        => true,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'menu_icon'           => esc_url(HOSTWPH_URL . 'assets/media/hostwph-menu-icon.svg'),
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'capability_type'     => 'page',
      'taxonomies'          => HOSTWPH_ROLE_CAPABILITIES,
      'show_in_rest'        => true, /* REST API */
    ];

    register_post_type('hostwph_guest', $args);
    add_theme_support('post-thumbnails', ['page', 'hostwph_guest']);
  }

  /**
   * Add Guest dashboard metabox.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    add_meta_box('hostwph_meta_box', esc_html(__('Host details', 'hostwph')), [$this, 'hostwph_meta_box_function'], 'hostwph_guest', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines Guest dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostwph_meta_box_function($post) {
    foreach (self::get_fields() as $hostwph_field) {
      HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $post->ID);
    }
  }

  /**
   * Defines single template for Guest.
   *
   * @since    1.0.0
   */
  public function single_template($single) {
    if (get_post_type() == 'hostwph_guest') {
      if (file_exists(HOSTWPH_DIR . 'templates/public/single-hostwph_guest.php')) {
        return HOSTWPH_DIR . 'templates/public/single-hostwph_guest.php';
      }
    }

    return $single;
  }

  /**
   * Defines archive template for Guest.
   *
   * @since    1.0.0
   */
  public function archive_template($archive) {
    if (get_post_type() == 'hostwph_guest') {
      if (file_exists(HOSTWPH_DIR . 'templates/public/archive-hostwph_guest.php')) {
        return HOSTWPH_DIR . 'templates/public/archive-hostwph_guest.php';
      }
    }

    return $archive;
  }

  public function save_post($post_id, $cpt, $update) {
    if (array_key_exists('hostwph_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostwph_nonce'])), 'hostwph-nonce')) {
      echo wp_json_encode(['error_key' => 'hostwph_nonce_error', ]);exit();
    }

    foreach (self::get_fields() as $wph_field) {
      $wph_input = array_key_exists('input', $wph_field) ? $wph_field['input'] : '';

      if (array_key_exists($wph_field['id'], $_POST) || $wph_input == 'html_multi') {
        $wph_value = array_key_exists($wph_field['id'], $_POST) ? HOSTWPH_Forms::sanitizer($_POST[$wph_field['id']], $wph_field['input'], !empty($wph_field['type']) ? $wph_field['type'] : '') : '';

        if (!empty($wph_input)) {
          switch ($wph_input) {
            case 'input':
              if (array_key_exists('type', $wph_field) && $wph_field['type'] == 'checkbox') {
                if (isset($_POST[$wph_field['id']])) {
                  update_post_meta($post_id, $wph_field['id'], $wph_value);
                }else{
                  update_post_meta($post_id, $wph_field['id'], '');
                }
              }else{
                update_post_meta($post_id, $wph_field['id'], $wph_value);
              }

              break;
            case 'select':
              if (array_key_exists('multiple', $wph_field) && $wph_field['multiple']) {
                $multi_array = [];
                $empty = true;

                foreach ($_POST[$wph_field['id']] as $multi_value) {
                  $multi_array[] = HOSTWPH_Forms::sanitizer($multi_value, $wph_field['input'], !empty($wph_field['type']) ? $wph_field['type'] : '');
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

                    $multi_array[] = HOSTWPH_Forms::sanitizer($multi_value, $wph_multi_field['input'], !empty($wph_multi_field['type']) ? $wph_multi_field['type'] : '');
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

  public function hostwph_form_save($element_id, $key_value, $hostwph_form_type, $hostwph_form_subtype, $post_type) {
    if ($post_type == 'hostwph_guest') {
      switch ($hostwph_form_type) {
        case 'post':
          switch ($hostwph_form_subtype) {
            case 'post_new':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostwph_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($post_id, $key);
                  }
                }
              }

              $post_functions = new HOSTWPH_Functions_Post();
              $guest_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_description, '', sanitize_title(esc_html($hostwph_title)), $post_type, 'publish', get_current_user_id());

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($guest_id, $key, $value);
                }
              }

              break;
            case 'post_edit':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostwph_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($post_id, $key);
                  }
                }
              }

              $guest_id = $element_id;
              wp_update_post(['ID' => $guest_id, 'post_title' => $hostwph_title, 'post_content' => $hostwph_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($guest_id, $key, $value);
                }
              }

              break;
            case 'post_check':
              self::hostwph_history_add($element_id);
              break;
            case 'post_uncheck':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostwph_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              break;
          }
      }
    }
  }

  public function list_wrapper() {
    ob_start();
    ?>
    <div class="hostwph-list-wrapper hostwph-mt-50 hostwph-mb-150" data-hostwph-post-type="hostwph_guest">
      <div class="hostwph-menu-more-overlay hostwph-z-index-9"></div>

      <div class="hostwph-display-table hostwph-width-100-percent hostwph-max-width-500 hostwph-margin-auto hostwph-position-relative">
        <div class="hostwph-display-inline-table hostwph-width-90-percent">
          <h4 class="hostwph-mb-30"><?php esc_html_e('Guests', 'hostwph'); ?></h4>
        </div>

        <div class="hostwph-display-inline-table hostwph-width-10-percent hostwph-text-align-right">
          <i class="material-icons-outlined hostwph-list-more-btn hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30">more_vert</i>

          <div class="hostwph-list-more hostwph-z-index-99 hostwph-display-none-soft">
            <ul class="hostwph-list-style-none">
              <li>
                <a href="#" class="hostwph-order-posts hostwph-text-decoration-none">
                  <div class="wph-display-table hostwph-width-100-percent">
                    <div class="hostwph-display-inline-table hostwph-width-70-percent">
                      <p><?php esc_html_e('Order hosts', 'hostwph'); ?></p>
                    </div>
                    <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                      <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">low_priority</i>
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
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function list() {
    $hosts_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostwph_guest',
      'post_status' => 'any', 
      'orderby' => 'menu_order', 
      'order' => 'ASC', 
    ];
    
    if (class_exists('Polylang')) {
      $hosts_atts['lang'] = pll_current_language('slug');
    }

    $hosts = get_posts($hosts_atts);

    ob_start();
    ?>
      <ul class="hostwph-list hostwph-guestwph_guest-list hostwph-list-host hostwph-guests hostwph-list-style-none hostwph-max-width-500 hostwph-margin-auto">
        <?php if (!empty($hosts)): ?>
          <?php foreach ($hosts as $guest_id): ?>
            <?php
              $hostwph_period = get_post_meta($guest_id, 'hostwph_period', true);
              $hostwph_owners_checkbox = get_post_meta($guest_id, 'hostwph_owners_checkbox', true);
              $hostwph_timed_checkbox = get_post_meta($guest_id, 'hostwph_timed_checkbox', true);
              $hostwph_comments_checkbox = get_post_meta($guest_id, 'hostwph_comments_checkbox', true);
              $hostwph_accomplish_date = get_post_meta($guest_id, 'hostwph_accomplish_date', true);
              $hostwph_checkmark = empty($hostwph_accomplish_date) ? 'radio_button_unchecked' : 'host_alt';
            ?>

            <li class="hostwph-list-element hostwph-mb-10" data-hostwph-element-id="<?php echo $guest_id; ?>">
              <div class="hostwph-display-table hostwph-width-100-percent">
                <div class="hostwph-display-inline-table hostwph-check-wrapper hostwph-width-20-percent hostwph-text-align-center">
                  <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-host-check" data-hostwph-ajax-type="hostwph_guest_check">
                    <i class="material-icons-outlined hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30 hostwph-width-25"><?php echo $hostwph_checkmark; ?></i>
                  </a>
                </div>

                <div class="hostwph-display-inline-table hostwph-width-60-percent">
                  <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-host-view" data-hostwph-ajax-type="hostwph_guest_view">
                    <span><?php echo esc_html(get_the_title($guest_id)); ?></span>
                      
                    <?php if ($hostwph_timed_checkbox == 'on'): ?>
                      <i class="material-icons-outlined hostwph-timed hostwph-cursor-pointer hostwph-vertical-align-super hostwph-p-5 hostwph-font-size-15 hostwph-tooltip" title="<?php esc_html_e('This host is timed', 'hostwph'); ?>">access_time</i>
                    <?php endif ?>

                    <?php if ($hostwph_period == 'on'): ?>
                      <i class="material-icons-outlined hostwph-timed hostwph-cursor-pointer hostwph-vertical-align-super hostwph-p-5 hostwph-font-size-15 hostwph-tooltip" title="<?php esc_html_e('This host is periodic', 'hostwph'); ?>">replay</i>
                    <?php endif ?>
                  </a>
                </div>

                <div class="hostwph-display-inline-table hostwph-width-20-percent hostwph-text-align-right hostwph-position-relative">
                  <i class="material-icons-outlined hostwph-menu-more-btn hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30">more_vert</i>

                  <div class="hostwph-menu-more hostwph-z-index-99 hostwph-display-none-soft">
                    <ul class="hostwph-list-style-none">
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-host-view" data-hostwph-ajax-type="hostwph_guest_view">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('View guest', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-host-edit" data-hostwph-ajax-type="hostwph_guest_edit">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Edit guest', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-guest-duplicate hostwph-text-decoration-none">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Duplicate guest', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-host-remove">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Remove guest', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">delete</i>
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

        <li class="hostwph-mt-50 hostwph-guest hostwph-list-element" data-hostwph-element-id="0">
          <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-host-add" data-hostwph-ajax-type="hostwph_guest_new">
            <div class="hostwph-display-table hostwph-width-100-percent">
              <div class="hostwph-display-inline-table hostwph-width-20-percent hostwph-text-align-center">
                <i class="material-icons-outlined hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30 hostwph-width-25">add_host</i>
              </div>
              <div class="hostwph-display-inline-table hostwph-width-80-percent">
                <?php esc_html_e('Add guest', 'hostwph'); ?>
              </div>
            </div>
          </a>
        </li>
      </ul>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function view($guest_id) {  
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="host-view">
        <h4 class="hostwph-text-align-center"><?php echo get_the_title($guest_id); ?></h4>
        
        <div class="hostwph-word-wrap-break-word">
          <p><?php echo str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($guest_id)->post_content)); ?></p>
        </div>

        <div class="host-view">
          <?php foreach ($this->get_fields_meta() as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $guest_id, 1); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right hostwph-guest hostwph-list-element" data-hostwph-element-id="<?php echo $guest_id; ?>">
            <a href="#" class="hostwph-btn hostwph-btn-mini hostwph-popup-open-ajax" data-hostwph-popup-id="hostwph-popup-host-edit" data-hostwph-ajax-type="hostwph_guest_edit"><?php esc_html_e('Edit guest', 'hostwph'); ?></a>
          </div>
        </div>
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function new() {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="host-new">
        <h4 class="hostwph-mb-30"><?php esc_html_e('Add guest', 'hostwph'); ?></h4>

        <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_guest">      
          <?php foreach ($this->get_fields() as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post'); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostwph_field_meta): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_meta, 'post'); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right">
            <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-post-type="hostwph_guest" data-hostwph-subtype="post_new" type="submit" value="<?php _e('Create guest', 'hostwph'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function edit($guest_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="host-edit">
        <p class="hostwph-text-align-center hostwph-mb-0"><?php esc_html_e('Editing', 'hostwph'); ?></p>
        <h4 class="hostwph-text-align-center hostwph-mb-30"><?php echo get_the_title($guest_id); ?></h4>

        <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_guest">
          <?php foreach ($this->get_fields($guest_id) as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $guest_id); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostwph_field_meta): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_meta, 'post', $guest_id); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right">
            <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-subtype="post_edit" type="submit" data-hostwph-post-type="hostwph_guest" data-hostwph-post-id="<?php echo esc_attr($guest_id); ?>" value="<?php _e('Save guest', 'hostwph'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function check($guest_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="host-check">
        <?php if (!empty(get_post_meta($guest_id, 'hostwph_accomplish_date', true))): ?>
          <p class="hostwph-text-align-center hostwph-mb-0"><?php esc_html_e('Marking as not completed', 'hostwph'); ?></p>
          <h4 class="hostwph-text-align-center hostwph-mb-30"><?php echo get_the_title($guest_id); ?></h4>

          <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_guest">
            <?php foreach ($this->get_fields_check($guest_id) as $hostwph_field_check): ?>
              <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_check, 'post', $guest_id, 1); ?>
            <?php endforeach ?>

            <div class="hostwph-text-align-right">
              <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-subtype="post_uncheck" type="submit" data-hostwph-post-id="<?php echo esc_attr($guest_id); ?>" value="<?php _e('Not completed', 'hostwph'); ?>"/>
            </div>
          </form>
        <?php else: ?>
          <p class="hostwph-text-align-center hostwph-mb-0"><?php esc_html_e('Marking as completed', 'hostwph'); ?></p>
          <h4 class="hostwph-text-align-center"><?php echo get_the_title($guest_id); ?></h4>

          <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_guest">
            <?php foreach ($this->get_fields_check($guest_id) as $hostwph_field_check): ?>
              <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_check, 'post', $guest_id); ?>
            <?php endforeach ?>

            <div class="hostwph-text-align-right">
              <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-subtype="post_check" type="submit" data-hostwph-post-id="<?php echo esc_attr($guest_id); ?>" value="<?php _e('Completed', 'hostwph'); ?>"/>
            </div>
          </form>
        <?php endif ?>
      </form>
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function history_add($guest_id) {  
    $host_meta = get_post_meta($guest_id);
    $host_meta_array = [];

    if (!empty($host_meta)) {
      foreach ($host_meta as $host_meta_key => $host_meta_value) {
        if (strpos($host_meta_key, 'hostwph_') !== false && !empty($host_meta_value[0])) {
          $host_meta_array[$host_meta_key] = $host_meta_value[0];
        }
      }
    }
    
    if(empty(get_post_meta($guest_id, 'hostwph_guest_history', true))) {
      update_post_meta($guest_id, 'hostwph_guest_history', [strtotime('now') => $host_meta_array]);
    }else{
      $wph_post_meta_new = get_post_meta($guest_id, 'hostwph_guest_history', true);
      $wph_post_meta_new[strtotime('now')] = $host_meta_array;
      update_post_meta($guest_id, 'hostwph_guest_history', $wph_post_meta_new);
    }
  }

  public function next($guest_id) {
    $hostwph_periodicity = get_post_meta($guest_id, 'hostwph_periodicity', true);
    $hostwph_date = get_post_meta($guest_id, 'hostwph_date', true);
    $hostwph_time = get_post_meta($guest_id, 'hostwph_time', true);

    $host_timestamp = strtotime($hostwph_date . ' ' . $hostwph_time);

    if (!empty($hostwph_periodicity) && !empty($host_timestamp)) {
      $now = strtotime('now');

      while ($host_timestamp < $now) {
        $host_timestamp = strtotime('+' . str_replace('_', ' ', $hostwph_periodicity), $host_timestamp);
      }

      return $host_timestamp;
    }
  }

  public function owners($guest_id) {
    $hostwph_owners = get_post_meta($guest_id, 'hostwph_owners', true);
    $hostwph_owners_array = [get_post($guest_id)->post_author];

    if (!empty($hostwph_owners)) {
      foreach ($hostwph_owners as $owner_id) {
        $hostwph_owners_array[] = $owner_id;
      }
    }

    return array_unique($hostwph_owners_array);
  }
}