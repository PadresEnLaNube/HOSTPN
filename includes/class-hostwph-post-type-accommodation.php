<?php
/**
 * accommodation creator.
 *
 * This class defines accommodation options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostwph
 * @subpackage hostwph/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Post_Type_Accommodation {
  public function get_fields($accommodation_id = 0) {
    $hostwph_fields = [];
      $hostwph_fields['hostwph_title'] = [
        'id' => 'hostwph_title',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'value' => !empty($accommodation_id) ? get_the_title($accommodation_id) : '',
        'label' => __('Accommodation title', 'hostwph'),
        'placeholder' => __('Accommodation title', 'hostwph'),
      ];
      $hostwph_fields['hostwph_description'] = [
        'id' => 'hostwph_description',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'value' => !empty($accommodation_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Accommodation description', 'hostwph'),
      ];
    return $hostwph_fields;
  }

  public function get_fields_meta() {
    $hostwph_fields_meta = [];
      $hostwph_fields_meta['hostwph_accommodation_code'] = [
        'id' => 'hostwph_accommodation_code',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation code', 'hostwph'),
        'placeholder' => __('Accommodation code', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_accommodation_type'] = [
        'id' => 'hostwph_accommodation_type',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => HOSTWPH_Data::accommodation_types(),
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation type', 'hostwph'),
        'placeholder' => __('Accommodation type', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_accommodation_address'] = [
        'id' => 'hostwph_accommodation_address',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostwph')),
        'placeholder' => esc_html(__('Address', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accommodation_address_alt'] = [
        'id' => 'hostwph_accommodation_address_alt',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostwph')),
        'placeholder' => esc_html(__('Address complementary information', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accommodation_country'] = [
        'id' => 'hostwph_accommodation_country',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => HOSTWPH_Data::countries(),
        'parent' => 'this',
        'xml' => 'pais',
        'label' => esc_html(__('Accommodation country', 'hostwph')),
        'placeholder' => esc_html(__('Accommodation country', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accommodation_postal_code'] = [
        'id' => 'hostwph_accommodation_postal_code',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'parent' => 'hostwph_accommodation_country',
        'parent_option' => 'es',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('Accommodation postal code', 'hostwph')),
        'placeholder' => esc_html(__('Accommodation postal code', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accommodation_city'] = [
        'id' => 'hostwph_accommodation_city',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('Accommodation city', 'hostwph')),
        'placeholder' => esc_html(__('Accommodation city', 'hostwph')),
      ];

      $hostwph_fields_meta['hostwph_nonce'] = [
        'id' => 'hostwph_nonce',
        'input' => 'input',
        'type' => 'nonce',
        'xml' => '',
      ];
    return $hostwph_fields_meta;
  }

  /**
   * Register accommodation.
   *
   * @since    1.0.0
   */
  public function register_post_type() {
    $labels = [
      'name'                => _x('Accommodation', 'Post Type general name', 'hostwph'),
      'singular_name'       => _x('Accommodation', 'Post Type singular name', 'hostwph'),
      'menu_name'           => esc_html(__('Accommodations', 'hostwph')),
      'parent_item_colon'   => esc_html(__('Parent accommodation', 'hostwph')),
      'all_items'           => esc_html(__('All accommodations', 'hostwph')),
      'view_item'           => esc_html(__('View accommodation', 'hostwph')),
      'add_new_item'        => esc_html(__('Add new accommodation', 'hostwph')),
      'add_new'             => esc_html(__('Add new accommodation', 'hostwph')),
      'edit_item'           => esc_html(__('Edit accommodation', 'hostwph')),
      'update_item'         => esc_html(__('Update accommodation', 'hostwph')),
      'search_items'        => esc_html(__('Search accommodations', 'hostwph')),
      'not_found'           => esc_html(__('Not accommodation found', 'hostwph')),
      'not_found_in_trash'  => esc_html(__('Not accommodation found in Trash', 'hostwph')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostwph')) ? get_option('hostwph') : 'hostwph'), 'with_front' => false],
      'label'               => esc_html(__('Accommodation', 'hostwph')),
      'description'         => esc_html(__('Accommodation description', 'hostwph')),
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

    register_post_type('hostwph_accomm', $args);
    add_theme_support('post-thumbnails', ['page', 'hostwph_accomm']);
  }

  /**
   * Add accommodation dashboard metabox.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    add_meta_box('hostwph_meta_box', esc_html(__('Accommodation details', 'hostwph')), [$this, 'hostwph_meta_box_function'], 'hostwph_accomm', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines accommodation dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostwph_meta_box_function($post) {
    foreach ($this->get_fields_meta() as $hostwph_field) {
      HOSTWPH_FORMS::input_wrapper_builder($hostwph_field, 'post', $post->ID);
    }
  }

  /**
   * Defines single template for accommodation.
   *
   * @since    1.0.0
   */
  public function single_template($single) {
    if (get_post_type() == 'hostwph_accomm') {
      if (file_exists(hostwph_DIR . 'templates/public/single-hostwph_accommodation.php')) {
        return hostwph_DIR . 'templates/public/single-hostwph_accommodation.php';
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
    if (get_post_type() == 'hostwph_accomm') {
      if (file_exists(hostwph_DIR . 'templates/public/archive-hostwph_accommodation.php')) {
        return hostwph_DIR . 'templates/public/archive-hostwph_accommodation.php';
      }
    }

    return $archive;
  }

  public function save_post($post_id, $cpt, $update) {
    if (array_key_exists('hostwph_nonce', $_POST) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostwph_nonce'])), 'hostwph-nonce')) {
      echo wp_json_encode(['error_key' => 'hostwph_nonce_error', ]);exit();
    }

    if (!array_key_exists('hostwph_duplicate', $_POST)) {
      foreach ($this->get_fields_meta() as $wph_field) {
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
  }

  public function hostwph_form_save($element_id, $key_value, $hostwph_form_type, $hostwph_form_subtype, $post_type) {
    if ($post_type == 'hostwph_accomm') {
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
              $accommodation_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_description, '', sanitize_title(esc_html($hostwph_title)), $post_type, 'publish', get_current_user_id());

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
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

              $accommodation_id = $element_id;
              wp_update_post(['ID' => $accommodation_id, 'post_title' => $hostwph_title, 'post_content' => $hostwph_description,]);

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
    <div class="hostwph-accommodation-list hostwph-mt-50 hostwph-mb-150" data-hostwph-post-type="hostwph_accomm">
      <div class="hostwph-menu-more-overlay hostwph-z-index-9"></div>

      <?php echo self::list(); ?>
    </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function list() {
    $accommodation_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostwph_accomm',
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
      <ul class="hostwph-list hostwph-guestwph_accommodation-list hostwph-accommodations hostwph-list-style-none hostwph-max-width-500 hostwph-margin-auto">
        <?php if (!empty($accommodation)): ?>
          <?php foreach ($accommodation as $accommodation_id): ?>
            <li class="hostwph-list-element hostwph-mb-10" data-hostwph-element-id="<?php echo $accommodation_id; ?>">
              <div class="hostwph-display-table hostwph-width-100-percent">
                <div class="hostwph-display-inline-table hostwph-width-80-percent">
                  <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-view" data-hostwph-ajax-type="hostwph_accommodation_view">
                    <span><?php echo esc_html(get_the_title($accommodation_id)); ?></span>
                  </a>
                </div>

                <div class="hostwph-display-inline-table hostwph-width-20-percent hostwph-text-align-right hostwph-position-relative">
                  <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-share" data-hostwph-ajax-type="hostwph_accommodation_share">
                    <i class="material-icons-outlined hostwph-share-btn hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30">share</i>
                  </a>

                  <i class="material-icons-outlined hostwph-menu-more-btn hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30">more_vert</i>

                  <div class="hostwph-menu-more hostwph-z-index-99 hostwph-display-none-soft">
                    <ul class="hostwph-list-style-none">
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-view" data-hostwph-ajax-type="hostwph_accommodation_view">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('View accommodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-edit" data-hostwph-ajax-type="hostwph_accommodation_edit"> 
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Edit accommodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-share" data-hostwph-ajax-type="hostwph_accommodation_share">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Share accommodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">share</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-accommodation-duplicate hostwph-text-decoration-none">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Duplicate accommodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-remove">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Remove accommodation', 'hostwph'); ?></p>
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

        <li class="hostwph-mt-50 hostwph-accommodation hostwph-list-element" data-hostwph-element-id="0">
          <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accommodation-add" data-hostwph-ajax-type="hostwph_accommodation_new">
            <div class="hostwph-display-table hostwph-width-100-percent">
              <div class="hostwph-display-inline-table hostwph-width-20-percent hostwph-tablet-display-block hostwph-tablet-width-100-percent hostwph-text-align-center">
                <i class="material-icons-outlined hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30 hostwph-width-25">add_accommodation</i>
              </div>
              <div class="hostwph-display-inline-table hostwph-width-80-percent hostwph-tablet-display-block hostwph-tablet-width-100-percent">
                <?php esc_html_e('Add accommodation', 'hostwph'); ?>
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

  public function view($accommodation_id) {  
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="accommodation-view">
        <h4 class="hostwph-text-align-center"><?php echo get_the_title($accommodation_id); ?></h4>
        
        <div class="hostwph-word-wrap-break-word">
          <p><?php echo str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content)); ?></p>
        </div>

        <div class="accommodation-view">
          <?php foreach ($this->get_fields_meta() as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $accommodation_id, 1); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right hostwph-accommodation hostwph-list-element" data-hostwph-accommodation-id="<?php echo $accommodation_id; ?>">
            <a href="#" class="hostwph-btn hostwph-btn-mini hostwph-popup-open-ajax" data-hostwph-popup-id="hostwph-popup-accommodation-edit" data-hostwph-ajax-type="hostwph_accommodation_edit"><?php esc_html_e('Edit accommodation', 'hostwph'); ?></a>
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

      <div class="accommodation-new">
        <h4 class="hostwph-mb-30"><?php esc_html_e('Add new accommodation', 'hostwph'); ?></h4>

        <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_accomm">      
          <?php foreach ($this->get_fields() as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post'); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostwph_field_meta): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_meta, 'post'); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right">
            <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-post-type="hostwph_accomm" data-hostwph-subtype="post_new" type="submit" value="<?php _e('Create accommodation', 'hostwph'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function edit($accommodation_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="accommodation-edit">
        <p class="hostwph-text-align-center hostwph-mb-0"><?php esc_html_e('Editing', 'hostwph'); ?></p>
        <h4 class="hostwph-text-align-center hostwph-mb-30"><?php echo get_the_title($accommodation_id); ?></h4>

        <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_accomm">      
          <?php foreach ($this->get_fields($accommodation_id) as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $accommodation_id); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostwph_field_meta): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_meta, 'post', $accommodation_id); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right">
            <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-subtype="post_edit" type="submit" data-hostwph-post-id="<?php echo $accommodation_id; ?>" value="<?php _e('Save accommodation', 'hostwph'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function share($accommodation_id) {
    ob_start();
    ?>
      <h3 class="hostwph-text-align-center"><?php esc_html_e('Accommodation share link', 'hostwph'); ?></h3>
      <p class="hostwph-text-align-center"><?php esc_html_e('You can share accommodation link to allow guests create their accounts directly in your platform.', 'hostwph'); ?></p>

      <?php if (class_exists('userswph')): ?>
        <div class="hostwph-display-table hostwph-width-100-percent">
          <div class="hostwph-display-inline-table hostwph-width-90-percent">
            <code id="hostwph-share-url"><?php echo esc_url(home_url('guests') . '?hostwph_action=popup_open&hostwph_popup=userswph-profile-popup&hostwph_tab=register'); ?></code>
          </div>
          <div class="hostwph-display-inline-table hostwph-width-10-percent hostwph-text-align-center hostwph-copy-disabled">
            <i class="material-icons-outlined hostwph-btn-copy hostwph-vertical-align-middle hostwph-cursor-pointer hostwph-tooltip" title="<?php esc_html_e('Copy url', 'hostwph'); ?>" data-hostwph-copy-content="#hostwph-share-url">content_copy</i>
          </div>
        </div>
      <?php else: ?>
        <p class="hostwph-text-align-center"><?php esc_html_e('Please install Users Manager - WPH plugin to allow user creation and management.', 'hostwph'); ?></p>

        <div class="hostwph-text-align-center">
          <a href="<?php echo esc_url(self::share_link($accommodation_id)); ?>" class="hostwph-btn hostwph-btn-mini"><?php esc_html_e('Install plugin', 'hostwph'); ?></a>
        </div>
      <?php endif ?>
    <?php
    $wph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $wph_return_string;
  }
      
  public function share_link($accommodation_id) {
    return esc_url(admin_url('/plugin-install.php?s=userswph&tab=search&type=term'));
  }
}