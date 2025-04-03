<?php
/**
 * Part of traveler creator.
 *
 * This class defines Part of traveler options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Part {
  public function get_fields($part_id = 0) {
    $hostpn_fields = [];
      $hostpn_fields['hostpn_title'] = [
        'id' => 'hostpn_title',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'value' => !empty($part_id) ? get_the_title($part_id) : gmdate('Y-m-d H:i:s', current_time('timestamp')),
        'label' => __('Part title', 'hostpn'),
        'placeholder' => __('Part title', 'hostpn'),
        'description' => __('This title will help you to remind and find this part in the future', 'hostpn'),
      ];
      $hostpn_fields['hostpn_description'] = [
        'id' => 'hostpn_description',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'value' => !empty($part_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($part_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Part description', 'hostpn'),
      ];
    return $hostpn_fields;
  }

  public function get_fields_meta() {
    $accommodation_id = !empty($_GET['hostpn_accommodation_id']) ? HOSTPN_Forms::sanitizer($_GET['hostpn_accommodation_id']) : '';
    $hostpn_fields_meta = [];

    $hostpn_fields_meta = [];
      $posts_atts = [
        'fields' => 'ids',
        'numberposts' => -1,
        'post_type' => 'hostpn_accomm',
        'post_status' => 'any', 
      ];
      
      if (class_exists('Polylang')) {
        $posts_atts['lang'] = pll_current_language('slug');
      }

      $accommodations = get_posts($posts_atts);
      
      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
        $hostpn_accommodation_options = [];
        
        foreach ($accommodations as $accommodation_id) {
          $hostpn_accommodation_options[$accommodation_id] = esc_html(get_the_title($accommodation_id));
        }

        $hostpn_fields_meta['hostpn_accommodation_id'] = [
          'id' => 'hostpn_accommodation_id',
          'class' => 'hostpn-select hostpn-width-100-percent',
          'input' => 'select',
          'options' => $hostpn_accommodation_options,
          'required' => true,
          'label' => __('Accommodation', 'hostpn'),
          'placeholder' => __('Accommodation', 'hostpn'),
        ];

        $hostpn_page_accommodation = !empty(get_option('hostpn_pages_accommodation')) ? get_option('hostpn_pages_accommodation') : url_to_postid(home_url());

        $hostpn_fields_meta['hostpn_accommodation_add'] = [
          'id' => 'hostpn_accommodation_add',
          'input' => 'html',
          'html_content' => '<div class="hostpn-width-100-percent hostpn-mb-20"><a class="hostpn-font-size-small" href="' . esc_url(get_permalink($hostpn_page_accommodation)) . '"><i class="material-icons-outlined hostpn-vertical-align-middle">add</i>' . esc_html(__('Add accommodation', 'hostpn')) . '</a></div>',
        ];
      }else{
        $current_accommodation_id = !empty($accommodation_id) ? $accommodation_id : $accommodations[0];

        $hostpn_fields_meta['hostpn_accommodation_id'] = [
          'id' => 'hostpn_accommodation_id',
          'class' => 'hostpn-input hostpn-width-100-percent',
          'input' => 'input',
          'type' => 'hidden',
          'value' => $current_accommodation_id,
        ];
      }

      $hostpn_people_options = [];
      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
        $posts_atts = [
          'fields' => 'ids',
          'numberposts' => -1,
          'post_type' => 'hostpn_guest',
          'post_status' => 'any', 
        ];
      }else{
        $posts_atts = [
          'fields' => 'ids',
          'numberposts' => -1,
          'post_type' => 'hostpn_guest',
          'post_status' => 'any',
          'post_author' => get_current_user_id(),
        ];
      }

      foreach (get_posts($posts_atts) as $guest_id) {
        $hostpn_people_options[$guest_id] = get_post_meta($guest_id, 'hostpn_name', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname_alt', true);
      }

      $hostpn_fields_meta['hostpn_people'] = [
        'id' => 'hostpn_people',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => $hostpn_people_options,
        'multiple' => true,
        'required' => true,
        'xml' => 'persona',
        'label' => __('People hosted', 'hostpn'),
        'placeholder' => __('People hosted', 'hostpn'),
      ];

      if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
        $hostpn_page_guest = !empty(get_option('hostpn_pages_guest')) ? get_option('hostpn_pages_guest') : url_to_postid(home_url());

        $hostpn_fields_meta['hostpn_people_add'] = [
          'id' => 'hostpn_people_add',
          'input' => 'html',
          'html_content' => '<div class="hostpn-width-100-percent hostpn-mb-20"><a class="hostpn-font-size-small" href="' . esc_url(get_permalink($hostpn_page_guest) . '?hostpn_action=btn&hostpn_btn_id=hostpn-popup-guest-add-btn') . '"><i class="material-icons-outlined hostpn-vertical-align-middle">add</i>' . esc_html(__('Add guest', 'hostpn')) . '</a></div>',
        ];
      }

      $hostpn_fields_meta['hostpn_contract_holder'] = [
        'id' => 'hostpn_contract_holder',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => $hostpn_people_options,
        'required' => true,
        'xml' => 'rol',
        'label' => __('Contract holder', 'hostpn'),
        'placeholder' => __('Contract holder', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_reference'] = [
        'id' => 'hostpn_reference',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'referencia',
        'label' => __('Contract reference', 'hostpn'),
        'placeholder' => __('Contract reference', 'hostpn'),
        'description' => __('A unique contract reference to distinguish it.', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_date'] = [
        'id' => 'hostpn_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaContrato',
        'label' => __('Contract date', 'hostpn'),
        'placeholder' => __('Contract date', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_check_in_date'] = [
        'id' => 'hostpn_check_in_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaEntrada',
        'label' => __('Check-in date', 'hostpn'),
        'placeholder' => __('Check-in date', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_check_in_time'] = [
        'id' => 'hostpn_check_in_time',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'label' => __('Check-in time', 'hostpn'),
        'placeholder' => __('Check-in time', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_check_out_date'] = [
        'id' => 'hostpn_check_out_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'required' => true,
        'xml' => 'fechaSalida',
        'label' => __('Check-out date', 'hostpn'),
        'placeholder' => __('Check-out date', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_check_out_time'] = [
        'id' => 'hostpn_check_out_time',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'label' => __('Check-out time', 'hostpn'),
        'placeholder' => __('Check-out time', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_people_number'] = [
        'id' => 'hostpn_people_number',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'numPersonas',
        'label' => __('Number of people', 'hostpn'),
        'placeholder' => __('Number of people', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_rooms'] = [
        'id' => 'hostpn_rooms',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'numHabitaciones',
        'label' => __('Number of rooms', 'hostpn'),
        'placeholder' => __('Number of rooms', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_internet'] = [
        'id' => 'hostpn_internet',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'checkbox',
        'xml' => 'internet',
        'label' => __('Internet available', 'hostpn'),
        'placeholder' => __('Internet available', 'hostpn'),
      ];

      $payment_type_options = ['efect' => esc_html(__('Cash', 'hostpn')), 'tarjt' => esc_html(__('Credit card', 'hostpn')), 'platf' => esc_html(__('Payment platform', 'hostpn')), 'trans' => esc_html(__('Transfer', 'hostpn')), 'movil' => esc_html(__('Mobile payment', 'hostpn')), 'treg' => esc_html(__('Gift card', 'hostpn')), 'desti' => esc_html(__('Payment at destination', 'hostpn')), 'otro' => esc_html(__('Other payment methods', 'hostpn')), ];

      $hostpn_fields_meta['hostpn_payment_type'] = [
        'id' => 'hostpn_payment_type',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => $payment_type_options,
        'parent' => 'this',
        'required' => true,
        'xml' => 'tipoPago',
        'label' => __('Payment type', 'hostpn'),
        'placeholder' => __('Payment type', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_card_expiration'] = [
        'id' => 'hostpn_card_expiration',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'parent' => 'hostpn_payment_type',
        'parent_option' => 'tarjt',
        'type' => 'month',
        'xml' => 'caducidadTarjeta',
        'label' => __('Card expiration date', 'hostpn'),
        'placeholder' => __('Card expiration date', 'hostpn'),
        'description' => __('Expiration date of the card.', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_payment_date'] = [
        'id' => 'hostpn_payment_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'xml' => 'fechaPago',
        'label' => __('Payment date', 'hostpn'),
        'placeholder' => __('Payment date', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_payment_method'] = [
        'id' => 'hostpn_payment_method',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'medioPago',
        'label' => __('Payment method', 'hostpn'),
        'placeholder' => __('Payment method', 'hostpn'),
        'description' => __('Payment method identification: card type and number, bank account IBAN, mobile number, etc.', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_payment_holder'] = [
        'id' => 'hostpn_payment_holder',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'xml' => 'titular',
        'label' => __('Payment holder', 'hostpn'),
        'placeholder' => __('Payment holder', 'hostpn'),
        'description' => __('Name and surname of the holder of the payment.', 'hostpn'),
      ];

      $hostpn_fields_meta['hostpn_nonce'] = [
        'id' => 'hostpn_nonce',
        'input' => 'input',
        'type' => 'nonce',
        'xml' => '',
      ];
    return $hostpn_fields_meta;
  }

  public function get_fields_check($part_id) {
    $hostpn_fields_check = [];
      $hostpn_fields_check['hostpn_accomplish_date'] = [
        'id' => 'hostpn_accomplish_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'value' => date('Y-m-d', strtotime('now')),
        'required' => 'true',
        'label' => __('Part of traveler accomplish date', 'hostpn'),
        'placeholder' => __('Part of traveler accomplish date', 'hostpn'),
      ];
      $hostpn_fields_check['hostpn_accomplish_time'] = [
        'id' => 'hostpn_accomplish_time',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'value' => date('H:i', strtotime('now')),
        'label' => __('Part of traveler accomplish time', 'hostpn'),
        'placeholder' => __('Part of traveler accomplish time', 'hostpn'),
      ];

      $hostpn_owners_checkbox = get_post_meta($part_id, 'hostpn_owners_checkbox', true);
      $hostpn_timed_expected = get_post_meta($part_id, 'hostpn_timed_expected', true);
      $hostpn_timed_checkbox = get_post_meta($part_id, 'hostpn_timed_checkbox', true);
      $hostpn_comments_checkbox = get_post_meta($part_id, 'hostpn_comments_checkbox', true);

      if ($hostpn_owners_checkbox == 'on') {
        $hostpn_part_responsible_options = [];
        $hostpn_part_owners = get_post_meta($part_id, 'hostpn_owners', true);
        if (!empty($hostpn_part_owners)) {
          foreach ($hostpn_part_owners as $owner_id) {
            $hostpn_part_responsible_options[$owner_id] = HOSTPN_Functions_User::get_user_name($owner_id);
          }
        }

        $hostpn_fields_check['hostpn_responsible'] = [
          'id' => 'hostpn_responsible',
          'class' => 'hostpn-select hostpn-width-100-percent',
          'input' => 'select',
          'options' => $hostpn_part_responsible_options,
          'label' => __('Part of traveler accomplished by', 'hostpn'),
          'placeholder' => __('Select contact', 'hostpn'),
        ];
      }

      if ($hostpn_timed_checkbox == 'on') {
        $hostpn_fields_check['hostpn_time_spent'] = [
          'id' => 'hostpn_time_spent',
          'class' => 'hostpn-input hostpn-width-100-percent',
          'input' => 'input',
          'type' => 'time',
          'label' => __('Time spent (Hours:Minutes)', 'hostpn'),
          'description' => (!empty($hostpn_timed_expected)) ? __('The estimated time was', 'hostpn') . ' ' . $hostpn_timed_expected : esc_html(__('This Part of traveler was not timed.', 'hostpn')),
          'placeholder' => __('Time spent (Hours:Minutes)', 'hostpn'),
        ];
      }

      if ($hostpn_comments_checkbox == 'on') {
        $hostpn_fields_check['hostpn_comments'] = [
          'id' => 'hostpn_comments',
          'class' => 'hostpn-input hostpn-width-100-percent',
          'input' => 'textarea',
          'label' => __('Part of traveler comments', 'hostpn'),
          'placeholder' => __('Part of traveler comments', 'hostpn'),
        ];
      }

      $hostpn_fields_check['hostpn_user_id'] = [
        'id' => 'hostpn_user_id',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'hidden',
        'value' => get_current_user_id(),
      ];

    return $hostpn_fields_check;
  }

  /**
   * Register Part of traveler.
   *
   * @since    1.0.0
   */
  public function register_post_type() {
    $labels = [
      'name'                => _x('Part of traveler', 'Post Type general name', 'hostpn'),
      'singular_name'       => _x('Part of traveler', 'Post Type singular name', 'hostpn'),
      'menu_name'           => esc_html(__('Part of travelers', 'hostpn')),
      'parent_item_colon'   => esc_html(__('Parent Part of traveler', 'hostpn')),
      'all_items'           => esc_html(__('All Part of travelers', 'hostpn')),
      'view_item'           => esc_html(__('View Part of traveler', 'hostpn')),
      'add_new_item'        => esc_html(__('Add new Part of traveler', 'hostpn')),
      'add_new'             => esc_html(__('Add new Part of traveler', 'hostpn')),
      'edit_item'           => esc_html(__('Edit Part of traveler', 'hostpn')),
      'update_item'         => esc_html(__('Update Part of traveler', 'hostpn')),
      'search_items'        => esc_html(__('Search Part of travelers', 'hostpn')),
      'not_found'           => esc_html(__('Not Part of traveler found', 'hostpn')),
      'not_found_in_trash'  => esc_html(__('Not Part of traveler found in Trash', 'hostpn')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostpn')) ? get_option('hostpn') : 'hostpn'), 'with_front' => false],
      'label'               => esc_html(__('Part of traveler', 'hostpn')),
      'description'         => esc_html(__('Part of traveler description', 'hostpn')),
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

    register_post_type('hostpn_part', $args);
    add_theme_support('post-thumbnails', ['page', 'hostpn_part']);
  }

  /**
   * Add Part of traveler dashboard metabox.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    add_meta_box('hostpn_meta_box', esc_html(__('Part of traveler details', 'hostpn')), [$this, 'hostpn_meta_box_function'], 'hostpn_part', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines Part of traveler dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostpn_meta_box_function($post) {
    foreach (self::get_fields() as $hostpn_field) {
      HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $post->ID);
    }
  }

  /**
   * Defines single template for Part of traveler.
   *
   * @since    1.0.0
   */
  public function single_template($single) {
    if (get_post_type() == 'hostpn_part') {
      if (file_exists(HOSTPN_DIR . 'templates/public/single-hostpn_part.php')) {
        return HOSTPN_DIR . 'templates/public/single-hostpn_part.php';
      }
    }

    return $single;
  }

  /**
   * Defines archive template for Part of traveler.
   *
   * @since    1.0.0
   */
  public function archive_template($archive) {
    if (get_post_type() == 'hostpn_part') {
      if (file_exists(HOSTPN_DIR . 'templates/public/archive-hostpn_part.php')) {
        return HOSTPN_DIR . 'templates/public/archive-hostpn_part.php';
      }
    }

    return $archive;
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
    if ($post_type == 'hostpn_part') {
      switch ($hostpn_form_type) {
        case 'post':
          switch ($hostpn_form_subtype) {
            case 'post_new':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($post_id, $key);
                  }
                }
              }

              $post_functions = new HOSTPN_Functions_Post();
              $part_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_description, '', sanitize_title(esc_html($hostpn_title)), $post_type, 'publish', get_current_user_id());

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($part_id, $key, $value);
                }
              }

              break;
            case 'post_edit':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($post_id, $key);
                  }
                }
              }

              $part_id = $element_id;
              wp_update_post(['ID' => $part_id, 'post_title' => $hostpn_title, 'post_content' => $hostpn_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($part_id, $key, $value);
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
      }
    }
  }

  public function list_wrapper() {
    ob_start();
      ?>
        <?php if (HOSTPN_Functions_User::is_user_admin(get_current_user_id())): ?>
          <div class="hostpn-list-wrapper hostpn-mt-50 hostpn-mb-150" data-hostpn-post-type="hostpn_part">
            <div class="hostpn-menu-more-overlay hostpn-z-index-9"></div>

            <div class="hostpn-display-table hostpn-width-100-percent hostpn-max-width-500 hostpn-margin-auto hostpn-position-relative">
              <div class="hostpn-display-inline-table hostpn-width-90-percent">
                <h4 class="hostpn-mb-30"><?php esc_html_e('Parts', 'hostpn'); ?></h4>
              </div>

              <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-right">
                <i class="material-icons-outlined hostpn-list-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                <div class="hostpn-list-more hostpn-z-index-99 hostpn-display-none-soft">
                  <ul class="hostpn-list-style-none">
                    <li>
                      <a href="#" class="hostpn-order-posts hostpn-text-decoration-none">
                        <div class="wph-display-table hostpn-width-100-percent">
                          <div class="hostpn-display-inline-table hostpn-width-70-percent">
                            <p><?php esc_html_e('Order parts', 'hostpn'); ?></p>
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
        <?php else: ?>
          <?php echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="account_circle" hostpn_call_to_action_title="' . __('Account needed', 'hostpn') . '" hostpn_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostpn') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\'>' . __('login', 'hostpn') . '</a>' . ' ' . __('or', 'hostpn') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\' data-userswph-action=\'register\'>' . __('register', 'hostpn') . '</a>' . ' ' . __('to go ahead', 'hostpn') . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . __('Login', 'hostpn') . '" hostpn_call_to_action_button_class="userswph-profile-popup-btn" hostpn_call_to_action_class="hostpn-mb-100"]'); ?>
        <?php endif ?>
      <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function list() {
    $hosts_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostpn_part',
      'post_status' => 'any', 
      'orderby' => 'post_date', 
      'order' => 'DESC', 
    ];
    
    if (class_exists('Polylang')) {
      $hosts_atts['lang'] = pll_current_language('slug');
    }

    $hosts = get_posts($hosts_atts);

    ob_start();
    ?>
      <ul class="hostpn-list hostpn-guestwph_part-list hostpn-list-host hostpn-parts hostpn-list-style-none hostpn-max-width-500 hostpn-margin-auto">
        <?php if (!empty($hosts)): ?>
          <?php foreach ($hosts as $part_id): ?>
            <?php
              $hostpn_period = get_post_meta($part_id, 'hostpn_period', true);
              $hostpn_owners_checkbox = get_post_meta($part_id, 'hostpn_owners_checkbox', true);
              $hostpn_timed_checkbox = get_post_meta($part_id, 'hostpn_timed_checkbox', true);
              $hostpn_comments_checkbox = get_post_meta($part_id, 'hostpn_comments_checkbox', true);
              $hostpn_accomplish_date = get_post_meta($part_id, 'hostpn_accomplish_date', true);
              $hostpn_checkmark = empty($hostpn_accomplish_date) ? 'radio_button_unchecked' : 'host_alt';
            ?>

            <li class="hostpn-list-element hostpn-mb-10" data-hostpn-element-id="<?php echo $part_id; ?>">
              <div class="hostpn-display-table hostpn-width-100-percent">
                <div class="hostpn-display-inline-table hostpn-check-wrapper hostpn-width-20-percent hostpn-text-align-center">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-check" data-hostpn-ajax-type="hostpn_part_check">
                    <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25"><?php echo $hostpn_checkmark; ?></i>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-60-percent">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-view" data-hostpn-ajax-type="hostpn_part_view">
                    <span><?php echo esc_html(get_the_title($part_id)); ?></span>
                      
                    <?php if ($hostpn_timed_checkbox == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This part is timed', 'hostpn'); ?>">access_time</i>
                    <?php endif ?>

                    <?php if ($hostpn_period == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This part is periodic', 'hostpn'); ?>">replay</i>
                    <?php endif ?>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                  <a href="#" class="hostpn-part-download hostpn-text-decoration-none">
                    <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-mr-10 hostpn-tooltip" title="<?php esc_html_e('Download XML file', 'hostpn'); ?>">file_download</i>
                  </a>

                  <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                  <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                    <ul class="hostpn-list-style-none">
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-view" data-hostpn-ajax-type="hostpn_part_view">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('View part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-edit" data-hostpn-ajax-type="hostpn_part_edit">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Edit part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-part-duplicate hostpn-text-decoration-none">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Duplicate part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-remove">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Remove part', 'hostpn'); ?></p>
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

        <li class="hostpn-mt-50 hostpn-part hostpn-list-element" data-hostpn-element-id="0">
          <a href="#" id="hostpn-popup-part-add-btn" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-add" data-hostpn-ajax-type="hostpn_part_new">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25">add</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent">
                <?php esc_html_e('Add part of travelers', 'hostpn'); ?>
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

  public function view($part_id) {  
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="part-view">
        <h4 class="hostpn-text-align-center"><?php echo get_the_title($part_id); ?></h4>
        
        <div class="hostpn-word-wrap-break-word">
          <p><?php echo str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($part_id)->post_content)); ?></p>
        </div>

        <div class="part-view">
          <?php foreach ($this->get_fields_meta() as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $part_id, 1); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right hostpn-part hostpn-list-element" data-hostpn-element-id="<?php echo $part_id; ?>">
            <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax" data-hostpn-popup-id="hostpn-popup-part-edit" data-hostpn-ajax-type="hostpn_part_edit"><?php esc_html_e('Edit part', 'hostpn'); ?></a>
          </div>
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

      <div class="part-new">
        <h4 class="hostpn-mb-30"><?php esc_html_e('Add part', 'hostpn'); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_part">      
          <?php foreach ($this->get_fields() as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post'); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post'); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-post-type="hostpn_part" data-hostpn-subtype="post_new" type="submit" value="<?php _e('Create part', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function edit($part_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="part-edit">
        <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
        <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo get_the_title($part_id); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_part">
          <?php foreach ($this->get_fields($part_id) as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $part_id); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post', $part_id); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_edit" type="submit" data-hostpn-post-type="hostpn_part" data-hostpn-post-id="<?php echo esc_attr($part_id); ?>" value="<?php _e('Save part', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function check($part_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="part-check">
        <?php if (!empty(get_post_meta($part_id, 'hostpn_accomplish_date', true))): ?>
          <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Marking as not completed', 'hostpn'); ?></p>
          <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo get_the_title($part_id); ?></h4>

          <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_part">
            <?php foreach ($this->get_fields_check($part_id) as $hostpn_field_check): ?>
              <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_check, 'post', $part_id, 1); ?>
            <?php endforeach ?>

            <div class="hostpn-text-align-right">
              <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_uncheck" type="submit" data-hostpn-post-id="<?php echo esc_attr($part_id); ?>" value="<?php _e('Not completed', 'hostpn'); ?>"/>
            </div>
          </form>
        <?php else: ?>
          <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Marking as completed', 'hostpn'); ?></p>
          <h4 class="hostpn-text-align-center"><?php echo get_the_title($part_id); ?></h4>

          <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_part">
            <?php foreach ($this->get_fields_check($part_id) as $hostpn_field_check): ?>
              <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_check, 'post', $part_id); ?>
            <?php endforeach ?>

            <div class="hostpn-text-align-right">
              <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_check" type="submit" data-hostpn-post-id="<?php echo esc_attr($part_id); ?>" value="<?php _e('Completed', 'hostpn'); ?>"/>
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

  public function history_add($part_id) {  
    $host_meta = get_post_meta($part_id);
    $host_meta_array = [];

    if (!empty($host_meta)) {
      foreach ($host_meta as $host_meta_key => $host_meta_value) {
        if (strpos($host_meta_key, 'hostpn_') !== false && !empty($host_meta_value[0])) {
          $host_meta_array[$host_meta_key] = $host_meta_value[0];
        }
      }
    }
    
    if(empty(get_post_meta($part_id, 'hostpn_part_history', true))) {
      update_post_meta($part_id, 'hostpn_part_history', [strtotime('now') => $host_meta_array]);
    }else{
      $wph_post_meta_new = get_post_meta($part_id, 'hostpn_part_history', true);
      $wph_post_meta_new[strtotime('now')] = $host_meta_array;
      update_post_meta($part_id, 'hostpn_part_history', $wph_post_meta_new);
    }
  }

  public function next($part_id) {
    $hostpn_periodicity = get_post_meta($part_id, 'hostpn_periodicity', true);
    $hostpn_date = get_post_meta($part_id, 'hostpn_date', true);
    $hostpn_time = get_post_meta($part_id, 'hostpn_time', true);

    $host_timestamp = strtotime($hostpn_date . ' ' . $hostpn_time);

    if (!empty($hostpn_periodicity) && !empty($host_timestamp)) {
      $now = strtotime('now');

      while ($host_timestamp < $now) {
        $host_timestamp = strtotime('+' . str_replace('_', ' ', $hostpn_periodicity), $host_timestamp);
      }

      return $host_timestamp;
    }
  }

  public function owners($part_id) {
    $hostpn_owners = get_post_meta($part_id, 'hostpn_owners', true);
    $hostpn_owners_array = [get_post($part_id)->post_author];

    if (!empty($hostpn_owners)) {
      foreach ($hostpn_owners as $owner_id) {
        $hostpn_owners_array[] = $owner_id;
      }
    }

    return array_unique($hostpn_owners_array);
  }
}