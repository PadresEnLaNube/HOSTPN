<?php
/**
 * Part of traveler creator.
 *
 * This class defines Part of traveler options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package   HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Part {
  public function hostpn_part_get_fields($part_id = 0) {
    $hostpn_fields = [];
      $hostpn_fields['hostpn_part_title'] = [
        'id' => 'hostpn_part_title',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'required' => true,
        'value' => !empty($part_id) ? get_the_title($part_id) : gmdate('Y-m-d H:i:s', current_time('timestamp')),
        'label' => __('Part title', 'hostpn'),
        'placeholder' => __('Part title', 'hostpn'),
        'description' => __('This title will help you to remind and find this Part in the future', 'hostpn'),
      ];
      $hostpn_fields['hostpn_part_description'] = [
        'id' => 'hostpn_part_description',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'value' => !empty($part_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($part_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Part description', 'hostpn'),
      ];
    return $hostpn_fields;
  }

  public function hostpn_part_get_fields_meta() {
    $hostpn_fields_meta = [];
    
    $posts_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostpn_accommodation',
      'post_status' => 'any', 
      'orderby' => 'date', 
      'order' => 'DESC', 
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

      $guest_url = add_query_arg([
        'hostpn_action' => 'btn',
        'hostpn_btn_id' => 'hostpn-popup-guest-add-btn',
        'hostpn_get_nonce' => wp_create_nonce('hostpn-get-nonce')
      ], get_permalink($hostpn_page_guest));

      $hostpn_fields_meta['hostpn_people_add'] = [
        'id' => 'hostpn_people_add',
        'input' => 'html',
        'html_content' => '<div class="hostpn-width-100-percent hostpn-mb-20"><a class="hostpn-font-size-small" href="' . esc_url($guest_url) . '"><i class="material-icons-outlined hostpn-vertical-align-middle">add</i>' . esc_html(__('Add guest', 'hostpn')) . '</a></div>',
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

    $hostpn_fields_meta['hostpn_part_form'] = [
      'id' => 'hostpn_part_form',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'hidden',
      'value' => 'hostpn_part_form',
    ];
    $hostpn_fields_meta['hostpn_ajax_nonce'] = [
      'id' => 'hostpn_ajax_nonce',
      'input' => 'input',
      'type' => 'nonce',
    ];
    return $hostpn_fields_meta;
  }

  public function hostpn_part_get_fields_check($part_id) {
    $hostpn_fields_check = [];
      $hostpn_fields_check['hostpn_accomplish_date'] = [
        'id' => 'hostpn_accomplish_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'value' => gmdate('Y-m-d', strtotime('now')),
        'required' => 'true',
        'label' => __('Part of traveler accomplish date', 'hostpn'),
        'placeholder' => __('Part of traveler accomplish date', 'hostpn'),
      ];
      $hostpn_fields_check['hostpn_accomplish_time'] = [
        'id' => 'hostpn_accomplish_time',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'value' => gmdate('H:i', strtotime('now')),
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
            $hostpn_part_responsible_options[$owner_id] = HOSTPN_Functions_User::hostpn_part_get_user_name($owner_id);
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
  public function hostpn_part_register_post_type() {
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
      'show_in_menu'        => false,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
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
  public function hostpn_part_add_meta_box() {
    add_meta_box('hostpn_meta_box', esc_html(__('Part of traveler details', 'hostpn')), [$this, 'hostpn_part_meta_box_function'], 'hostpn_part', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines Part of traveler dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostpn_part_meta_box_function($post) {
    foreach (self::hostpn_part_get_fields() as $hostpn_field) {
     HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID);
    }
  }

  /**
   * Defines single template for Part of traveler.
   *
   * @since    1.0.0
   */
  public function hostpn_part_single_template($single) {
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
  public function hostpn_part_archive_template($archive) {
    if (get_post_type() == 'hostpn_part') {
      if (file_exists(HOSTPN_DIR . 'templates/public/archive-hostpn_part.php')) {
        return HOSTPN_DIR . 'templates/public/archive-hostpn_part.php';
      }
    }

    return $archive;
  }

  public function hostpn_part_save_post($post_id, $cpt, $update) {
    if($cpt->post_type == 'hostpn_part' && array_key_exists('hostpn_part_form', $_POST)){
      // Always require nonce verification
      if (!array_key_exists('hostpn_ajax_nonce', $_POST)) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_error_required',
          'error_content' => esc_html(__('Security check failed: Nonce is required.', 'hostpn')),
        ]);

        exit;
      }

      if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_error_invalid',
          'error_content' => esc_html(__('Security check failed: Invalid nonce.', 'hostpn')),
        ]);

        exit;
      }

      if (!array_key_exists('hostpn_duplicate', $_POST)) {
        foreach (array_merge(self::hostpn_part_get_fields(), self::hostpn_part_get_fields_meta()) as $hostpn_field) {
          $hostpn_input = array_key_exists('input', $hostpn_field) ? $hostpn_field['input'] : '';

          if (array_key_exists($hostpn_field['id'], $_POST) || $hostpn_input == 'html_multi') {
            $hostpn_value = array_key_exists($hostpn_field['id'], $_POST) ? 
                HOSTPN_Forms::hostpn_sanitizer(
                    wp_unslash($_POST[$hostpn_field['id']]), 
                    $hostpn_field['input'], 
                    !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                    $hostpn_field // Pass the entire field config
                ) : '';

            if (!empty($hostpn_input)) {
              switch ($hostpn_input) {
                case 'input':
                  if (array_key_exists('type', $hostpn_field) && $hostpn_field['type'] == 'checkbox') {
                    if (isset($_POST[$hostpn_field['id']])) {
                      update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                    } else {
                      update_post_meta($post_id, $hostpn_field['id'], '');
                    }
                  } else {
                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  }
                  break;

                case 'select':
                  if (array_key_exists('multiple', $hostpn_field) && $hostpn_field['multiple']) {
                    $multi_array = [];
                    $empty = true;

                    foreach (wp_unslash($_POST[$hostpn_field['id']]) as $multi_value) {
                      $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                        $multi_value, 
                        $hostpn_field['input'], 
                        !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                        $hostpn_field // Pass the entire field config
                      );
                    }

                    update_post_meta($post_id, $hostpn_field['id'], $multi_array);
                  } else {
                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  }
                  break;

                case 'html_multi':
                  foreach ($hostpn_field['html_multi_fields'] as $hostpn_multi_field) {
                    if (array_key_exists($hostpn_multi_field['id'], $_POST)) {
                      $multi_array = [];
                      $empty = true;

                      // Sanitize the POST data before using it
                      $sanitized_post_data = isset($_POST[$hostpn_multi_field['id']]) ? 
                          array_map(function($value) {
                              return sanitize_text_field(wp_unslash($value));
                          }, (array)$_POST[$hostpn_multi_field['id']]) : [];

                      foreach ($sanitized_post_data as $multi_value) {
                        if (!empty($multi_value)) {
                          $empty = false;
                        }

                        $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                            $multi_value, 
                            $hostpn_multi_field['input'], 
                            !empty($hostpn_multi_field['type']) ? $hostpn_multi_field['type'] : '',
                            $hostpn_multi_field // Pass the entire field config
                        );
                      }

                      if (!$empty) {
                        update_post_meta($post_id, $hostpn_multi_field['id'], $multi_array);
                      } else {
                        update_post_meta($post_id, $hostpn_multi_field['id'], '');
                      }
                    }
                  }
                  break;

                default:
                  update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  break;
              }
            }
          } else {
            update_post_meta($post_id, $hostpn_field['id'], '');
          }
        }
      }
    }
  }

  public function hostpn_part_form_save($element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type) {
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
              $part_id = $post_functions->hostpn_insert_post(esc_html($hostpn_title), $hostpn_description, '', sanitize_title(esc_html($hostpn_title)), $post_type, 'publish', get_current_user_id());

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
              update_post_meta($element_id, 'hostpn_part_accomplish_date', strtotime('now'));
              self::hostpn_part_history_add($element_id);

              break;
            case 'post_uncheck':
              delete_post_meta($element_id, 'hostpn_part_accomplish_date');

              break;
          }
      }
    }
  }

  public function hostpn_part_register_scripts() {
    if (!wp_script_is('hostpn-aux', 'registered')) {
      wp_register_script('hostpn-aux', HOSTPN_URL . 'assets/js/hostpn-aux.js', [], HOSTPN_VERSION, true);
    }

    if (!wp_script_is('hostpn-forms', 'registered')) {
      wp_register_script('hostpn-forms', HOSTPN_URL . 'assets/js/hostpn-forms.js', [], HOSTPN_VERSION, true);
    }
    
    if (!wp_script_is('hostpn-selector', 'registered')) {
      wp_register_script('hostpn-selector', HOSTPN_URL . 'assets/js/hostpn-selector.js', [], HOSTPN_VERSION, true);
    }
  }

  public function hostpn_part_print_scripts() {
    wp_print_scripts(['hostpn-aux', 'hostpn-forms', 'hostpn-selector']);
  }

  public function hostpn_part_list_wrapper() {
    ob_start();

    if(HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
      ?>
        <div class="hostpn-hostpn_part-list hostpn-mb-50">
          <div class="hostpn-part-search-container hostpn-mb-20 hostpn-text-align-right">
            <div class="hostpn-part-search-wrapper">
              <input type="text" class="hostpn-part-search-input hostpn-input hostpn-display-none" placeholder="<?php esc_attr_e('Filter...', 'hostpn'); ?>" />
              <i class="material-icons-outlined hostpn-part-search-toggle hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Search Parts', 'hostpn'); ?>">search</i>

              <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-add" data-hostpn-ajax-type="hostpn_part_new">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Add new Part', 'hostpn'); ?>">add</i>
              </a>
            </div>
          </div>

          <div class="hostpn-hostpn_part-list-wrapper">
            <?php echo wp_kses(self::hostpn_part_list(), HOSTPN_KSES); ?>
          </div>
        </div>
      <?php
    }else{
      echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="account_circle" hostpn_call_to_action_title="' . __('Account needed', 'hostpn') . '" hostpn_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\'>' . __('login', 'hostpn') . '</a>' . ' ' . __('or', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\' data-userspn-action=\'register\'>' . __('register', 'hostpn') . '</a>' . ' ' . __('to go ahead', 'hostpn') . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . __('Login', 'hostpn') . '" hostpn_call_to_action_button_class="userspn-profile-popup-btn" hostpn_call_to_action_class="hostpn-mb-100"]');
    }
    
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_part_list() {
    $part_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostpn_part',
      'post_status' => 'any', 
      'orderby' => 'date', 
      'order' => 'DESC', 
    ];
    
    if (class_exists('Polylang')) {
      $part_atts['lang'] = pll_current_language('slug');
    }

    $part = get_posts($part_atts);

    ob_start();
    ?>
      <ul class="hostpn-parts hostpn-list-style-none hostpn-p-0 hostpn-margin-auto">
        <?php if (!empty($part)): ?>
          <?php foreach ($part as $part_id): ?>
            <?php
              $hostpn_part_period = get_post_meta($part_id, 'hostpn_part_period', true);
              $hostpn_owners_checkbox = get_post_meta($part_id, 'hostpn_owners_checkbox', true);
              $hostpn_part_timed_checkbox = get_post_meta($part_id, 'hostpn_part_timed_checkbox', true);
              $hostpn_comments_checkbox = get_post_meta($part_id, 'hostpn_comments_checkbox', true);
              $hostpn_part_accomplish_date = get_post_meta($part_id, 'hostpn_part_accomplish_date', true);
              $hostpn_part_checkmark = empty($hostpn_part_accomplish_date) ? 'radio_button_unchecked' : 'task_alt';
            ?>

            <li class="hostpn-part hostpn-hostpn_part-list-item hostpn-mb-10" data-hostpn_part-id="<?php echo esc_attr($part_id); ?>">
              <div class="hostpn-display-table hostpn-width-100-percent">
                <div class="hostpn-display-inline-table hostpn-check-wrapper hostpn-width-20-percent hostpn-text-align-center">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-check" data-hostpn-ajax-type="hostpn_part_check">
                    <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25"><?php echo esc_html($hostpn_part_checkmark); ?></i>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-60-percent">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-view" data-hostpn-ajax-type="hostpn_part_view">
                    <span><?php echo esc_html(get_the_title($part_id)); ?></span>
                      
                    <?php if ($hostpn_part_timed_checkbox == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This Part is timed', 'hostpn'); ?>">access_time</i>
                    <?php endif ?>

                    <?php if ($hostpn_part_period == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This Part is periodic', 'hostpn'); ?>">replay</i>
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
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('View Part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-edit" data-hostpn-ajax-type="hostpn_part_edit"> 
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Edit Part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-part-download hostpn-text-decoration-none">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Download XML file', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">file_download</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-part-duplicate-post">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Duplicate Part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open" data-hostpn-popup-id="hostpn-popup-part-remove">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Remove Part', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right">
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

        <li class="hostpn-mt-50 hostpn-part" data-hostpn_part-id="0">
          <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-part-add" data-hostpn-ajax-type="hostpn_part_new">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25">add</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent">
                <?php esc_html_e('Add new Part', 'hostpn'); ?>
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

  public function hostpn_part_view($part_id) {  
    ob_start();
    self::hostpn_part_register_scripts();
    self::hostpn_part_print_scripts();
    ?>
      <div class="part-view hostpn-p-30" data-hostpn_part-id="<?php echo esc_attr($part_id); ?>">
        <h4 class="hostpn-text-align-center"><?php echo esc_html(get_the_title($part_id)); ?></h4>
        
        <div class="hostpn-word-wrap-break-word">
          <p><?php echo wp_kses(str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($part_id)->post_content)), HOSTPN_KSES); ?></p>
        </div>

        <div class="part-view">
          <?php foreach (self::hostpn_part_get_fields_meta() as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $part_id, 1), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right hostpn-part" data-hostpn_part-id="<?php echo esc_attr($part_id); ?>">
            <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax" data-hostpn-popup-id="hostpn-popup-part-edit" data-hostpn-ajax-type="hostpn_part_edit"><?php esc_html_e('Edit Part', 'hostpn'); ?></a>
          </div>
        </div>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_part_new() {
    ob_start();
    self::hostpn_part_register_scripts();
    self::hostpn_part_print_scripts();
    ?>
      <div class="part-new hostpn-p-30">
        <h4 class="hostpn-mb-30"><?php esc_html_e('Add new Part', 'hostpn'); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form">      
          <?php foreach (self::hostpn_part_get_fields() as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post'), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <?php foreach (self::hostpn_part_get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post'), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" type="submit" data-hostpn-type="post" data-hostpn-post-type="hostpn_part" data-hostpn-subtype="post_new" value="<?php esc_attr_e('Create Part', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_part_edit($part_id) {
    ob_start();
    self::hostpn_part_register_scripts();
    self::hostpn_part_print_scripts();
    ?>
      <div class="part-edit hostpn-p-30">
        <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
        <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($part_id)); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form">      
          <?php foreach (self::hostpn_part_get_fields($part_id) as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $part_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <?php foreach (self::hostpn_part_get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $part_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_edit" data-hostpn-post-type="hostpn_part" type="submit" data-hostpn-post-id="<?php echo esc_attr($part_id); ?>" value="<?php esc_attr_e('Save Part', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_part_check($part_id) {
    ob_start();
    self::hostpn_part_register_scripts();
    self::hostpn_part_print_scripts();
    ?>
      <div class="part-check hostpn-p-30">
        <?php if (!empty(get_post_meta($part_id, 'hostpn_part_accomplish_date', true))): ?>
          <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Marking as not completed', 'hostpn'); ?></p>
          <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($part_id)); ?></h4>

          <form action="" method="post" id="hostpn-form" class="hostpn-form">
            <?php foreach (self::hostpn_part_get_fields_check($part_id) as $hostpn_field_check): ?>
              <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_check, 'post', $part_id, 1), HOSTPN_KSES); ?>
            <?php endforeach ?>

            <div class="hostpn-text-align-right">
              <input class="hostpn-btn" type="submit" data-hostpn-type="post" data-hostpn-subtype="post_uncheck" data-hostpn-post-type="hostpn_part" data-hostpn-post-id="<?php echo esc_attr($part_id); ?>" value="<?php esc_attr_e('Not completed', 'hostpn'); ?>"/>
            </div>
          </form>
        <?php else: ?>
          <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Marking as completed', 'hostpn'); ?></p>
          <h4 class="hostpn-text-align-center"><?php echo esc_html(get_the_title($part_id)); ?></h4>

          <form action="" method="post" id="hostpn-form" class="hostpn-form">
            <?php foreach (self::hostpn_part_get_fields_check($part_id) as $hostpn_field_check): ?>
              <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_check, 'post', $part_id), HOSTPN_KSES); ?>
            <?php endforeach ?>

            <div class="hostpn-text-align-right">
              <input class="hostpn-btn" type="submit" data-hostpn-type="post" data-hostpn-subtype="post_check" data-hostpn-post-type="hostpn_part" data-hostpn-post-id="<?php echo esc_attr($part_id); ?>" value="<?php esc_attr_e('Completed', 'hostpn'); ?>"/>
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

  public function hostpn_part_history_add($part_id) {  
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
      $hostpn_post_meta_new = get_post_meta($part_id, 'hostpn_part_history', true);
      $hostpn_post_meta_new[strtotime('now')] = $host_meta_array;
      update_post_meta($part_id, 'hostpn_part_history', $hostpn_post_meta_new);
    }
  }
  public function hostpn_part_next($part_id) {
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

  public function hostpn_part_owners($part_id) {
    $hostpn_owners = get_post_meta($part_id, 'hostpn_owners', true);
    $hostpn_owners_array = [get_post($part_id)->post_author];

    if (!empty($hostpn_owners)) {
      foreach ($hostpn_owners as $owner_id) {
        $hostpn_owners_array[] = $owner_id;
      }
    }

    return array_unique($hostpn_owners_array);
  }

  /**
   * Customize admin columns for Part post type.
   *
   * @param array $columns The default columns.
   * @return array Modified columns.
   */
  public function hostpn_part_custom_columns($columns) {
    $new_columns = [];
    $new_columns['cb'] = $columns['cb'];
    $new_columns['part_info'] = esc_html(__('Part Information', 'hostpn'));
    $new_columns['author_info'] = esc_html(__('Author', 'hostpn'));
    $new_columns['creation_date'] = esc_html(__('Creation Date', 'hostpn'));
    return $new_columns;
  }

  /**
   * Customize admin column content for Part post type.
   *
   * @param string $column Column name.
   * @param int $post_id Post ID.
   */
  public function hostpn_part_custom_column_content($column, $post_id) {
    ob_start();

    switch ($column) {
      case 'part_info':
        $part_title = get_the_title($post_id);
        $part_description = get_post($post_id)->post_content;
        $edit_link = get_edit_post_link($post_id);

        ?>
        <p>
          <a href="<?php echo esc_url($edit_link); ?>" class="mailpn-color-main-0 mailpn-font-weight-bold mailpn-mr-10"
              target="_blank">
              <i class="material-icons-outlined mailpn-vertical-align-middle mailpn-font-size-20 mailpn-color-main-0">description</i>
              #<?php echo esc_html($post_id); ?>                 <?php echo esc_html($part_title); ?>
          </a>
        </p>
        <?php if (!empty($part_description)): ?>
          <p class="description"><?php echo esc_html(wp_trim_words($part_description, 10)); ?></p>
        <?php endif; ?>
        <?php
        break;

      case 'author_info':
        $post = get_post($post_id);
        $author_id = $post->post_author;
        $author = get_userdata($author_id);
        $author_name = get_user_meta($author_id, 'first_name', true) . ' ' . get_user_meta($author_id, 'last_name', true);
        $author_email = $author->user_email;
        $edit_user_link = admin_url('user-edit.php?user_id=' . $author_id);
        ?>
        <p>
          <a href="<?php echo esc_url($edit_user_link); ?>" class="mailpn-color-main-0 mailpn-font-weight-bold mailpn-mr-10"
              target="_blank">
              <i class="material-icons-outlined mailpn-vertical-align-middle mailpn-font-size-20 mailpn-color-main-0">person</i>
              #<?php echo esc_html($author_id); ?>                 <?php echo esc_html($author_name); ?>
          </a>

          <?php if ($author_email): ?>
              (<a href="mailto:<?php echo esc_attr($author_email); ?>" target="_blank"><?php echo esc_html($author_email); ?></a>)
          <?php endif; ?>
        </p>
        <?php
        break;

      case 'creation_date':
        $post = get_post($post_id);
        $creation_date = $post->post_date;
        $formatted_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($creation_date));

        ?>
        <p>
          <i class="material-icons-outlined mailpn-vertical-align-middle mailpn-font-size-20 mailpn-color-main-0 mailpn-mr-10">calendar_today</i>
          <?php echo esc_html($formatted_date); ?>
        </p>
        <?php
        break;
    }

    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
  }
}