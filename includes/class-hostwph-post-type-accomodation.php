<?php
/**
 * accomodation creator.
 *
 * This class defines accomodation options, menus and templates.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    hostwph
 * @subpackage hostwph/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Post_Type_Accomodation {
  public function get_fields($accomodation_id = 0) {
    $hostwph_fields = [];
      $hostwph_fields['hostwph_title'] = [
        'id' => 'hostwph_title',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'value' => !empty($accomodation_id) ? get_the_title($accomodation_id) : '',
        'label' => __('Accomodation title', 'hostwph'),
        'placeholder' => __('Accomodation title', 'hostwph'),
      ];
      $hostwph_fields['hostwph_description'] = [
        'id' => 'hostwph_description',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'value' => !empty($accomodation_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accomodation_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Accomodation description', 'hostwph'),
      ];
    return $hostwph_fields;
  }

  public function get_fields_meta() {
    $hostwph_fields_meta = [];
      $hostwph_fields_meta['hostwph_accomodation_code'] = [
        'id' => 'hostwph_accomodation_code',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accomodation code', 'hostwph'),
        'placeholder' => __('Accomodation code', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_accomodation_type'] = [
        'id' => 'hostwph_accomodation_type',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => HOSTWPH_Data::accomodation_types(),
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accomodation type', 'hostwph'),
        'placeholder' => __('Accomodation type', 'hostwph'),
      ];
      $hostwph_fields_meta['hostwph_accomodation_address'] = [
        'id' => 'hostwph_accomodation_address',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostwph')),
        'placeholder' => esc_html(__('Address', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accomodation_address_alt'] = [
        'id' => 'hostwph_accomodation_address_alt',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostwph')),
        'placeholder' => esc_html(__('Address complementary information', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accomodation_country'] = [
        'id' => 'hostwph_accomodation_country',
        'class' => 'hostwph-select hostwph-width-100-percent',
        'input' => 'select',
        'options' => HOSTWPH_Data::countries(),
        'parent' => 'this',
        'xml' => 'pais',
        'label' => esc_html(__('Accomodation country', 'hostwph')),
        'placeholder' => esc_html(__('Accomodation country', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accomodation_postal_code'] = [
        'id' => 'hostwph_accomodation_postal_code',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'parent' => 'hostwph_accomodation_country',
        'parent_option' => 'es',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('Accomodation postal code', 'hostwph')),
        'placeholder' => esc_html(__('Accomodation postal code', 'hostwph')),
      ];
      $hostwph_fields_meta['hostwph_accomodation_city'] = [
        'id' => 'hostwph_accomodation_city',
        'class' => 'hostwph-input hostwph-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('Accomodation city', 'hostwph')),
        'placeholder' => esc_html(__('Accomodation city', 'hostwph')),
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
   * Register accomodation.
   *
   * @since    1.0.0
   */
  public function register_post_type() {
    $labels = [
      'name'                => _x('Accomodation', 'Post Type general name', 'hostwph'),
      'singular_name'       => _x('Accomodation', 'Post Type singular name', 'hostwph'),
      'menu_name'           => esc_html(__('Accomodations', 'hostwph')),
      'parent_item_colon'   => esc_html(__('Parent accomodation', 'hostwph')),
      'all_items'           => esc_html(__('All accomodations', 'hostwph')),
      'view_item'           => esc_html(__('View accomodation', 'hostwph')),
      'add_new_item'        => esc_html(__('Add new accomodation', 'hostwph')),
      'add_new'             => esc_html(__('Add new accomodation', 'hostwph')),
      'edit_item'           => esc_html(__('Edit accomodation', 'hostwph')),
      'update_item'         => esc_html(__('Update accomodation', 'hostwph')),
      'search_items'        => esc_html(__('Search accomodations', 'hostwph')),
      'not_found'           => esc_html(__('Not accomodation found', 'hostwph')),
      'not_found_in_trash'  => esc_html(__('Not accomodation found in Trash', 'hostwph')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostwph')) ? get_option('hostwph') : 'hostwph'), 'with_front' => false],
      'label'               => esc_html(__('Accomodation', 'hostwph')),
      'description'         => esc_html(__('Accomodation description', 'hostwph')),
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

    register_post_type('hostwph_accomodation', $args);
    add_theme_support('post-thumbnails', ['page', 'hostwph_accomodation']);
  }

  /**
   * Add accomodation dashboard metabox.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    add_meta_box('hostwph_meta_box', esc_html(__('Accomodation details', 'hostwph')), [$this, 'hostwph_meta_box_function'], 'hostwph_accomodation', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines accomodation dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostwph_meta_box_function($post) {
    foreach ($this->get_fields_meta() as $hostwph_field) {
      HOSTWPH_FORMS::input_wrapper_builder($hostwph_field, 'post', $post->ID);
    }
  }

  /**
   * Defines single template for accomodation.
   *
   * @since    1.0.0
   */
  public function single_template($single) {
    if (get_post_type() == 'hostwph_accomodation') {
      if (file_exists(hostwph_DIR . 'templates/public/single-hostwph_accomodation.php')) {
        return hostwph_DIR . 'templates/public/single-hostwph_accomodation.php';
      }
    }

    return $single;
  }

  /**
   * Defines archive template for accomodation.
   *
   * @since    1.0.0
   */
  public function archive_template($archive) {
    if (get_post_type() == 'hostwph_accomodation') {
      if (file_exists(hostwph_DIR . 'templates/public/archive-hostwph_accomodation.php')) {
        return hostwph_DIR . 'templates/public/archive-hostwph_accomodation.php';
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
    if ($post_type == 'hostwph_accomodation') {
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
              $accomodation_id = $post_functions->insert_post(esc_html($hostwph_title), $hostwph_description, '', sanitize_title(esc_html($hostwph_title)), $post_type, 'publish', get_current_user_id());

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accomodation_id, $key, $value);
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

              $accomodation_id = $element_id;
              wp_update_post(['ID' => $accomodation_id, 'post_title' => $hostwph_title, 'post_content' => $hostwph_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accomodation_id, $key, $value);
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
    <div class="hostwph-accomodation-list hostwph-mt-50 hostwph-mb-150" data-hostwph-post-type="hostwph_accomodation">
      <div class="hostwph-menu-more-overlay hostwph-z-index-9"></div>

      <?php echo self::list(); ?>
    </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function list() {
    $accomodation_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostwph_accomodation',
      'post_status' => 'any', 
      'orderby' => 'menu_order', 
      'order' => 'ASC', 
    ];
    
    if (class_exists('Polylang')) {
      $accomodation_atts['lang'] = pll_current_language('slug');
    }

    $accomodation = get_posts($accomodation_atts);

    ob_start();
    ?>
      <ul class="hostwph-list hostwph-guestwph_accomodation-list hostwph-accomodations hostwph-list-style-none hostwph-max-width-500 hostwph-margin-auto">
        <?php if (!empty($accomodation)): ?>
          <?php foreach ($accomodation as $accomodation_id): ?>
            <?php
              $hostwph_period = get_post_meta($accomodation_id, 'hostwph_period', true);
              $hostwph_owners_checkbox = get_post_meta($accomodation_id, 'hostwph_owners_checkbox', true);
              $hostwph_timed_checkbox = get_post_meta($accomodation_id, 'hostwph_timed_checkbox', true);
              $hostwph_comments_checkbox = get_post_meta($accomodation_id, 'hostwph_comments_checkbox', true);
              $hostwph_accomplish_date = get_post_meta($accomodation_id, 'hostwph_accomplish_date', true);
              $hostwph_checkmark = empty($hostwph_accomplish_date) ? 'radio_button_unchecked' : 'hostwph_alt';
            ?>

            <li class="hostwph-accomodation hostwph-mb-10" data-hostwph-accomodation-id="<?php echo $accomodation_id; ?>">
              <div class="hostwph-display-table hostwph-width-100-percent">
                <div class="hostwph-display-inline-table hostwph-width-80-percent">
                  <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accomodation-view" data-hostwph-ajax-type="hostwph_accomodation_view">
                    <span><?php echo esc_html(get_the_title($accomodation_id)); ?></span>
                      
                    <?php if ($hostwph_timed_checkbox == 'on'): ?>
                      <i class="material-icons-outlined hostwph-timed hostwph-cursor-pointer hostwph-vertical-align-super hostwph-p-5 hostwph-font-size-15 hostwph-tooltip" title="<?php esc_html_e('This accomodation is timed', 'hostwph'); ?>">access_time</i>
                    <?php endif ?>

                    <?php if ($hostwph_period == 'on'): ?>
                      <i class="material-icons-outlined hostwph-timed hostwph-cursor-pointer hostwph-vertical-align-super hostwph-p-5 hostwph-font-size-15 hostwph-tooltip" title="<?php esc_html_e('This accomodation is periodic', 'hostwph'); ?>">replay</i>
                    <?php endif ?>
                  </a>
                </div>

                <div class="hostwph-display-inline-table hostwph-width-20-percent hostwph-text-align-right hostwph-position-relative">
                  <i class="material-icons-outlined hostwph-menu-more-btn hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30">more_vert</i>

                  <div class="hostwph-menu-more hostwph-z-index-99 hostwph-display-none-soft">
                    <ul class="hostwph-list-style-none">
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accomodation-view" data-hostwph-ajax-type="hostwph_accomodation_view">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('View accomodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accomodation-edit" data-hostwph-ajax-type="hostwph_accomodation_edit"> 
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Edit accomodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <!-- <li>
                        <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-subaccomodation-new" data-hostwph-ajax-type="hostwph_subhostwph_new"> 
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Add sub accomodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">subdirectory_arrow_right</i>
                            </div>
                          </div>
                        </a>
                      </li> -->
                      <li>
                        <a href="#" class="hostwph-popup-open hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accomodation-move">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Move accomodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">drag_indicator</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-accomodation-duplicate hostwph-text-decoration-none">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Duplicate accomodation', 'hostwph'); ?></p>
                            </div>
                            <div class="hostwph-display-inline-table hostwph-width-20-percent  hostwph-text-align-right">
                              <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-30 hostwph-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostwph-popup-open hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accomodation-remove">
                          <div class="wph-display-table hostwph-width-100-percent">
                            <div class="hostwph-display-inline-table hostwph-width-70-percent">
                              <p><?php esc_html_e('Remove accomodation', 'hostwph'); ?></p>
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

        <li class="hostwph-mt-50 hostwph-accomodation" data-hostwph-accomodation-id="0">
          <a href="#" class="hostwph-popup-open-ajax hostwph-text-decoration-none" data-hostwph-popup-id="hostwph-popup-accomodation-add" data-hostwph-ajax-type="hostwph_accomodation_new">
            <div class="hostwph-display-table hostwph-width-100-percent">
              <div class="hostwph-display-inline-table hostwph-width-20-percent hostwph-tablet-display-block hostwph-tablet-width-100-percent hostwph-text-align-center">
                <i class="material-icons-outlined hostwph-cursor-pointer hostwph-vertical-align-middle hostwph-font-size-30 hostwph-width-25">add_accomodation</i>
              </div>
              <div class="hostwph-display-inline-table hostwph-width-80-percent hostwph-tablet-display-block hostwph-tablet-width-100-percent">
                <?php esc_html_e('Add accomodation', 'hostwph'); ?>
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

  public function view($accomodation_id) {  
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="accomodation-view hostwph-list-element" data-hostwph-accomodation-id="<?php echo $accomodation_id; ?>">
        <h4 class="hostwph-text-align-center"><?php echo get_the_title($accomodation_id); ?></h4>
        
        <div class="hostwph-word-wrap-break-word">
          <p><?php echo str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accomodation_id)->post_content)); ?></p>
        </div>

        <div class="accomodation-view">
          <?php foreach ($this->get_fields_meta() as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $accomodation_id, 1); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right hostwph-accomodation" data-hostwph-accomodation-id="<?php echo $accomodation_id; ?>">
            <a href="#" class="hostwph-btn hostwph-btn-mini hostwph-popup-open-ajax" data-hostwph-popup-id="hostwph-popup-accomodation-edit" data-hostwph-ajax-type="hostwph_accomodation_edit"><?php esc_html_e('Edit accomodation', 'hostwph'); ?></a>
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

      <div class="accomodation-new">
        <h4 class="hostwph-mb-30"><?php esc_html_e('Add new accomodation', 'hostwph'); ?></h4>

        <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_accomodation">      
          <?php foreach ($this->get_fields() as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post'); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostwph_field_meta): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_meta, 'post'); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right">
            <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-post-type="hostwph_accomodation" data-hostwph-subtype="post_new" type="submit" value="<?php _e('Create accomodation', 'hostwph'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function edit($accomodation_id) {
    ob_start();
    ?>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-aux.js'); ?>"></script>
      <script src="<?php echo esc_url(HOSTWPH_URL . 'assets/js/hostwph-forms.js'); ?>"></script>

      <div class="accomodation-edit">
        <p class="hostwph-text-align-center hostwph-mb-0"><?php esc_html_e('Editing', 'hostwph'); ?></p>
        <h4 class="hostwph-text-align-center hostwph-mb-30"><?php echo get_the_title($accomodation_id); ?></h4>

        <form action="" method="post" id="hostwph-form" class="hostwph-form" data-hostwph-post-type="hostwph_accomodation">      
          <?php foreach ($this->get_fields($accomodation_id) as $hostwph_field): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field, 'post', $accomodation_id); ?>
          <?php endforeach ?>

          <?php foreach ($this->get_fields_meta() as $hostwph_field_meta): ?>
            <?php echo HOSTWPH_Forms::input_wrapper_builder($hostwph_field_meta, 'post', $accomodation_id); ?>
          <?php endforeach ?>

          <div class="hostwph-text-align-right">
            <input class="hostwph-btn" data-hostwph-type="post" data-hostwph-subtype="post_edit" type="submit" data-hostwph-post-id="<?php echo $accomodation_id; ?>" value="<?php _e('Save accomodation', 'hostwph'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }
}