<?php
/**
 * accommodation creator.
 *
 * This class defines accommodation options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Accommodation {
  public function get_fields($accommodation_id = 0) {
    $hostpn_fields = [];
      $hostpn_fields['hostpn_title'] = [
        'id' => 'hostpn_title',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'value' => !empty($accommodation_id) ? get_the_title($accommodation_id) : '',
        'label' => __('Accommodation title', 'hostpn'),
        'placeholder' => __('Accommodation title', 'hostpn'),
      ];
      $hostpn_fields['hostpn_description'] = [
        'id' => 'hostpn_description',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'value' => !empty($accommodation_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Accommodation description', 'hostpn'),
      ];
    return $hostpn_fields;
  }

  public function get_fields_meta() {
    $hostpn_fields_meta = [];
      $hostpn_fields_meta['hostpn_accommodation_code'] = [
        'id' => 'hostpn_accommodation_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation code', 'hostpn'),
        'placeholder' => __('Accommodation code', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_accommodation_type'] = [
        'id' => 'hostpn_accommodation_type',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::accommodation_types(),
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation type', 'hostpn'),
        'placeholder' => __('Accommodation type', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_accommodation_address'] = [
        'id' => 'hostpn_accommodation_address',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostpn')),
        'placeholder' => esc_html(__('Address', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_address_alt'] = [
        'id' => 'hostpn_accommodation_address_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostpn')),
        'placeholder' => esc_html(__('Address complementary information', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_country'] = [
        'id' => 'hostpn_accommodation_country',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::countries(),
        'parent' => 'this',
        'xml' => 'pais',
        'label' => esc_html(__('Accommodation country', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation country', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_postal_code'] = [
        'id' => 'hostpn_accommodation_postal_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'parent' => 'hostpn_accommodation_country',
        'parent_option' => 'es',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('Accommodation postal code', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation postal code', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_city'] = [
        'id' => 'hostpn_accommodation_city',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('Accommodation city', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation city', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_nonce'] = [
        'id' => 'hostpn_nonce',
        'input' => 'input',
        'type' => 'nonce',
        'xml' => '',
      ];
    return $hostpn_fields_meta;
  }

  /**
   * Register accommodation.
   *
   * @since    1.0.0
   */
  public function register_post_type() {
    $labels = [
      'name'                => _x('Accommodation', 'Post Type general name', 'hostpn'),
      'singular_name'       => _x('Accommodation', 'Post Type singular name', 'hostpn'),
      'menu_name'           => esc_html(__('Accommodations', 'hostpn')),
      'parent_item_colon'   => esc_html(__('Parent accommodation', 'hostpn')),
      'all_items'           => esc_html(__('All accommodations', 'hostpn')),
      'view_item'           => esc_html(__('View accommodation', 'hostpn')),
      'add_new_item'        => esc_html(__('Add new accommodation', 'hostpn')),
      'add_new'             => esc_html(__('Add new accommodation', 'hostpn')),
      'edit_item'           => esc_html(__('Edit accommodation', 'hostpn')),
      'update_item'         => esc_html(__('Update accommodation', 'hostpn')),
      'search_items'        => esc_html(__('Search accommodations', 'hostpn')),
      'not_found'           => esc_html(__('Not accommodation found', 'hostpn')),
      'not_found_in_trash'  => esc_html(__('Not accommodation found in Trash', 'hostpn')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostpn')) ? get_option('hostpn') : 'hostpn'), 'with_front' => false],
      'label'               => esc_html(__('Accommodation', 'hostpn')),
      'description'         => esc_html(__('Accommodation description', 'hostpn')),
      'supports'            => ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ],
      'hierarchical'        => true,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'menu_icon'           => esc_url(HOSTPN_URL . 'assets/media/hostpn-menu-icon.svg'),
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'capability_type'     => 'page',
      'taxonomies'          => HOSTPN_ROLE_CAPABILITIES,
      'show_in_rest'        => true, /* REST API */
    ];

    register_post_type('hostpn_accomm', $args);
    add_theme_support('post-thumbnails', ['page', 'hostpn_accomm']);
  }

  /**
   * Add accommodation dashboard metabox.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    add_meta_box('hostpn_meta_box', esc_html(__('Accommodation details', 'hostpn')), [$this, 'hostpn_meta_box_function'], 'hostpn_accomm', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines accommodation dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostpn_meta_box_function($post) {
    foreach ($this->get_fields_meta() as $hostpn_field) {
      HOSTPN_FORMS::input_wrapper_builder($hostpn_field, 'post', $post->ID);
    }
  }

  /**
   * Defines single template for accommodation.
   *
   * @since    1.0.0
   */
  public function single_template($single) {
    if (get_post_type() == 'hostpn_accomm') {
      if (file_exists(hostpn_DIR . 'templates/public/single-hostpn_accommodation.php')) {
        return hostpn_DIR . 'templates/public/single-hostpn_accommodation.php';
      }
    }

    return $single;
  }

  /**
   * Defines archive template for accommodation.
   *
   * @since    1.0.0
   */
  public function archive_template($archive) {
    if (get_post_type() == 'hostpn_accomm') {
      if (file_exists(hostpn_DIR . 'templates/public/archive-hostpn_accommodation.php')) {
        return hostpn_DIR . 'templates/public/archive-hostpn_accommodation.php';
      }
    }

    return $archive;
  }

  public function save_post($post_id, $cpt, $update) {
    if (array_key_exists('hostpn_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_nonce'])), 'hostpn-nonce')) {
      echo wp_json_encode(['error_key' => 'hostpn_nonce_error', ]);exit();
    }

    if (!array_key_exists('hostpn_duplicate', $_POST)) {
      foreach ($this->get_fields_meta() as $wph_field) {
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
    if ($post_type == 'hostpn_accomm') {
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
              $accommodation_id = $post_functions->insert_post(esc_html($hostpn_title), $hostpn_description, '', sanitize_title(esc_html($hostpn_title)), $post_type, 'publish', get_current_user_id());

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
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

              $accommodation_id = $element_id;
              wp_update_post(['ID' => $accommodation_id, 'post_title' => $hostpn_title, 'post_content' => $hostpn_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
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
        <div class="hostpn-accommodation-list hostpn-mt-50 hostpn-mb-150" data-hostpn-post-type="hostpn_accomm">
          <div class="hostpn-menu-more-overlay hostpn-z-index-9"></div>

          <?php echo self::list(); ?>
        </div>
      <?php else: ?>
        <?php echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="account_circle" hostpn_call_to_action_title="' . __('Account needed', 'hostpn') . '" hostpn_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\'>' . __('login', 'hostpn') . '</a>' . ' ' . __('or', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\' data-userspn-action=\'register\'>' . __('register', 'hostpn') . '</a>' . ' ' . __('to go ahead', 'hostpn') . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . __('Login', 'hostpn') . '" hostpn_call_to_action_button_class="userspn-profile-popup-btn" hostpn_call_to_action_class="hostpn-mb-100"]'); ?>
      <?php endif ?>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function list() {
    $accommodation_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostpn_accomm',
      'post_status' => 'any', 
      'orderby' => 'menu_order', 
      'order' => 'ASC', 
    ];
    
    if (class_exists('Polylang')) {
      $accommodation_atts['lang'] = pll_current_language('slug');
    }

    $accommodation = get_posts($accommodation_atts);

    ob_start();
    ?>
      <ul class="hostpn-list hostpn-guestwph_accommodation-list hostpn-accommodations hostpn-list-style-none hostpn-max-width-500 hostpn-margin-auto">
        <?php if (!empty($accommodation)): ?>
          <?php foreach ($accommodation as $accommodation_id): ?>
            <li class="hostpn-list-element hostpn-mb-10" data-hostpn-element-id="<?php echo $accommodation_id; ?>">
              <div class="hostpn-display-table hostpn-width-100-percent">
                <div class="hostpn-display-inline-table hostpn-width-80-percent">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-view" data-hostpn-ajax-type="hostpn_accommodation_view">
                    <span><?php echo esc_html(get_the_title($accommodation_id)); ?></span>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
                    <i class="material-icons-outlined hostpn-share-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-20">share</i>
                  </a>

                  <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                  <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                    <ul class="hostpn-list-style-none">
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-view" data-hostpn-ajax-type="hostpn_accommodation_view">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('View accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-edit" data-hostpn-ajax-type="hostpn_accommodation_edit"> 
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Edit accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Share accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">share</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-accommodation-duplicate hostpn-text-decoration-none">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Duplicate accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-remove">
                          <div class="wph-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Remove accommodation', 'hostpn'); ?></p>
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

        <li class="hostpn-mt-50 hostpn-accommodation hostpn-list-element" data-hostpn-element-id="0">
          <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-add" data-hostpn-ajax-type="hostpn_accommodation_new">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25">add</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent">
                <?php esc_html_e('Add accommodation', 'hostpn'); ?>
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

  public function view($accommodation_id) {  
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="accommodation-view">
        <h4 class="hostpn-text-align-center"><?php echo get_the_title($accommodation_id); ?></h4>
        
        <div class="hostpn-word-wrap-break-word">
          <p><?php echo str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content)); ?></p>
        </div>

        <div class="accommodation-view">
          <?php foreach ($this->get_fields_meta() as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $accommodation_id, 1); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right hostpn-accommodation hostpn-list-element" data-hostpn-accommodation-id="<?php echo $accommodation_id; ?>">
            <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax" data-hostpn-popup-id="hostpn-popup-accommodation-edit" data-hostpn-ajax-type="hostpn_accommodation_edit"><?php esc_html_e('Edit accommodation', 'hostpn'); ?></a>
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

      <div class="accommodation-new">
        <h4 class="hostpn-mb-30"><?php esc_html_e('Add new accommodation', 'hostpn'); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_accomm">      
          <?php foreach ($this->get_fields() as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post'); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post'); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-post-type="hostpn_accomm" data-hostpn-subtype="post_new" type="submit" value="<?php _e('Create accommodation', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function edit($accommodation_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTPN_URL . 'assets/js/hostpn-forms.js'); ?>"></script>

      <div class="accommodation-edit">
        <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
        <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo get_the_title($accommodation_id); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form" data-hostpn-post-type="hostpn_accomm">      
          <?php foreach ($this->get_fields($accommodation_id) as $hostpn_field): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field, 'post', $accommodation_id); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo HOSTPN_Forms::input_wrapper_builder($hostpn_field_meta, 'post', $accommodation_id); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_edit" type="submit" data-hostpn-post-id="<?php echo $accommodation_id; ?>" value="<?php _e('Save accommodation', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function share() {
    ob_start();
    ?>
      <h3 class="hostpn-text-align-center"><?php esc_html_e('Accommodation share link', 'hostpn'); ?></h3>
      <p class="hostpn-text-align-center"><?php esc_html_e('You can share accommodation link to allow companions to fill out their guests forms directly in the platform.', 'hostpn'); ?></p>

      <?php if (class_exists('USERSPN')): ?>
        <div class="hostpn-display-table hostpn-width-100-percent">
          <div class="hostpn-display-inline-table hostpn-width-90-percent">
            <code id="hostpn-share-url"><?php echo esc_url(home_url('guests') . '?hostpn_action=popup_open&hostpn_popup=userspn-profile-popup&hostpn_tab=register'); ?></code>
          </div>
          <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-center hostpn-copy-disabled">
            <i class="material-icons-outlined hostpn-btn-copy hostpn-vertical-align-middle hostpn-cursor-pointer hostpn-tooltip" title="<?php esc_html_e('Copy url', 'hostpn'); ?>" data-hostpn-copy-content="#hostpn-share-url">content_copy</i>
          </div>
        </div>
      <?php else: ?>
        <p class="hostpn-text-align-center"><?php esc_html_e('Please install Users Manager - WPH plugin to allow user creation and management.', 'hostpn'); ?></p>

        <div class="hostpn-text-align-center">
          <a href="<?php echo esc_url(self::share_link()); ?>" class="hostpn-btn hostpn-btn-mini"><?php esc_html_e('Install plugin', 'hostpn'); ?></a>
        </div>
      <?php endif ?>
    <?php
    $wph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $wph_return_string;
  }
      
  public function share_link() {
    return esc_url(admin_url('/plugin-install.php?s=userspn&tab=search&type=term'));
  }
}