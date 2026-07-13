<?php
/**
 * Plugin settings manager.
 *
 * This class defines plugin settings, both in dashboard or in front-end.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Settings
{

  /**
   * Get users for the notification recipients select field.
   *
   * @return array Associative array of user_id => display_name.
   */
  private static function get_users_for_select()
  {
    $users = get_users(['orderby' => 'display_name', 'order' => 'ASC']);
    $options = [];
    foreach ($users as $user) {
      $options[$user->ID] = $user->display_name . ' (' . $user->user_email . ')';
    }
    return $options;
  }

  public function get_options()
  {
    $hostpn_options = [];

    // ── PAGES SECTION ─────────────────────────────────────────────────
    $hostpn_options['hostpn_pages_section_start'] = [
      'section' => 'start',
      'label' => __('Pages', 'hostpn'),
      'description' => __('Manage the pages used by the plugin. Each page contains the shortcode that renders the corresponding list.', 'hostpn'),
    ];
    $hostpn_options['hostpn_pages_table'] = [
      'id' => 'hostpn_pages_table',
      'input' => 'pages_table',
    ];
    $hostpn_options['hostpn_pages_section_end'] = [
      'section' => 'end',
    ];

    // ── NOTIFICATIONS SECTION ────────────────────────────────────────
    $hostpn_options['hostpn_notifications_section_start'] = [
      'section' => 'start',
      'label' => __('Notifications', 'hostpn'),
      'description' => __('Configure email notifications when new guests are registered.', 'hostpn'),
    ];
    $hostpn_options['hostpn_notifications_enabled'] = [
      'id' => 'hostpn_notifications_enabled',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'checkbox',
      'parent' => 'this',
      'label' => __('Enable guest registration notifications', 'hostpn'),
      'description' => __('Send email notifications when a new guest is registered.', 'hostpn'),
    ];
    $hostpn_options['hostpn_notifications_users'] = [
      'id' => 'hostpn_notifications_users',
      'class' => 'hostpn-select hostpn-width-100-percent',
      'input' => 'select',
      'multiple' => true,
      'parent' => 'hostpn_notifications_enabled',
      'parent_option' => 'on',
      'options' => self::get_users_for_select(),
      'label' => __('Notify platform users', 'hostpn'),
      'description' => __('Select registered users who will receive notification emails when a new guest is registered.', 'hostpn'),
      'placeholder' => __('Select users...', 'hostpn'),
    ];
    $hostpn_options['hostpn_notifications_external_emails'] = [
      'id' => 'hostpn_notifications_external_emails',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'html_multi',
      'parent' => 'hostpn_notifications_enabled',
      'parent_option' => 'on',
      'label' => __('External email recipients', 'hostpn'),
      'description' => __('Add email addresses for people who are not registered on the platform but should receive guest registration notifications.', 'hostpn'),
      'html_multi_fields' => [
        [
          'id' => 'hostpn_notifications_external_email',
          'class' => 'hostpn-input hostpn-width-100-percent',
          'input' => 'input',
          'type' => 'email',
          'label' => __('Email', 'hostpn'),
          'placeholder' => __('email@example.com', 'hostpn'),
        ],
      ],
    ];
    // Internal entry so the html_multi sub-field value is in the allowed options list for saving
    $hostpn_options['hostpn_notifications_external_email'] = [
      'id' => 'hostpn_notifications_external_email',
      'input' => 'input',
      'type' => 'hidden',
      'multiple' => true,
    ];
    $hostpn_options['hostpn_notifications_section_end'] = [
      'section' => 'end',
    ];

    // ── SYSTEM PARENT SECTION ────────────────────────────────────────
    $hostpn_options['hostpn_system_parent_section_start'] = [
      'section' => 'start',
      'label' => __('System', 'hostpn'),
      'description' => __('Configure system settings, design, and user roles.', 'hostpn'),
    ];

    // Subsection: Design
    $hostpn_options['hostpn_design_subsection_start'] = [
      'section' => 'start',
      'label' => __('Design', 'hostpn'),
      'description' => __('Configure the design and colors of the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_color_main'] = [
      'id' => 'hostpn_color_main',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#d45500',
      'label' => __('Main color', 'hostpn'),
      'description' => __('Main color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_bg_color_main'] = [
      'id' => 'hostpn_bg_color_main',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#d45500',
      'label' => __('Main background color', 'hostpn'),
      'description' => __('Main background color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_border_color_main'] = [
      'id' => 'hostpn_border_color_main',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#d45500',
      'label' => __('Main border color', 'hostpn'),
      'description' => __('Main border color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_color_main_alt'] = [
      'id' => 'hostpn_color_main_alt',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#232323',
      'label' => __('Alternative color', 'hostpn'),
      'description' => __('Alternative color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_bg_color_main_alt'] = [
      'id' => 'hostpn_bg_color_main_alt',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#232323',
      'label' => __('Alternative background color', 'hostpn'),
      'description' => __('Alternative background color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_border_color_main_alt'] = [
      'id' => 'hostpn_border_color_main_alt',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#232323',
      'label' => __('Alternative border color', 'hostpn'),
      'description' => __('Alternative border color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_color_main_blue'] = [
      'id' => 'hostpn_color_main_blue',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#6e6eff',
      'label' => __('Blue accent color', 'hostpn'),
      'description' => __('Blue accent color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_color_main_grey'] = [
      'id' => 'hostpn_color_main_grey',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'color',
      'value' => '#f5f5f5',
      'label' => __('Grey accent color', 'hostpn'),
      'description' => __('Grey accent color used across the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn_design_subsection_end'] = [
      'section' => 'end',
    ];

    // Subsection: System Options
    $hostpn_options['hostpn_system_subsection_start'] = [
      'section' => 'start',
      'label' => __('System Options', 'hostpn'),
      'description' => __('Configure system options for the plugin.', 'hostpn'),
    ];
    $hostpn_options['hostpn'] = [
      'id' => 'hostpn',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Guest slug', 'hostpn'),
      'placeholder' => __('Guest slug', 'hostpn'),
      'description' => __('This option sets the slug of the main Guest archive page, and the Guest pages. By default they will be:', 'hostpn') . '<br><a href="' . esc_url(home_url('/host')) . '" target="_blank">' . esc_url(home_url('/host')) . '</a><br>' . esc_url(home_url('/host/host-name')),
    ];
    $hostpn_options['hostpn_options_remove'] = [
      'id' => 'hostpn_options_remove',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'checkbox',
      'label' => __('Remove plugin options on deactivation', 'hostpn'),
      'description' => __('If you activate this option the plugin will remove all options on deactivation. Please, be careful. This process cannot be undone.', 'hostpn'),
    ];
    $hostpn_options['hostpn_system_subsection_end'] = [
      'section' => 'end',
    ];

    // Subsection: User Role Management
    $hostpn_options['hostpn_section_roles_start'] = [
      'section' => 'start',
      'label' => __('User Role Management', 'hostpn'),
      'description' => __('Manage which users have the Host and Guest roles. Users with these roles can access the corresponding features.', 'hostpn'),
    ];
    $hostpn_options['hostpn_role_manager_selector'] = [
      'id' => 'hostpn_role_manager_selector',
      'input' => 'user_role_selector',
      'label' => __('Host - HOSTPN Role', 'hostpn'),
      'role' => 'hostpn_role_manager',
      'role_label' => __('Host - HOSTPN', 'hostpn'),
    ];
    $hostpn_options['hostpn_role_guest_selector'] = [
      'id' => 'hostpn_role_guest_selector',
      'input' => 'user_role_selector',
      'label' => __('Guest - HOSTPN Role', 'hostpn'),
      'role' => 'hostpn_role_guest',
      'role_label' => __('Guest - HOSTPN', 'hostpn'),
    ];
    $hostpn_options['hostpn_section_roles_end'] = [
      'section' => 'end',
    ];

    // End of System parent section
    $hostpn_options['hostpn_system_parent_section_end'] = [
      'section' => 'end',
    ];

    $hostpn_options['hostpn_nonce'] = [
      'id' => 'hostpn_nonce',
      'input' => 'input',
      'type' => 'hidden',
    ];
    return $hostpn_options;
  }

  /**
   * Administrator menu.
   *
   * @since    1.0.0
   */
  public function hostpn_admin_menu()
  {
    // This method is no longer needed as the menu is now centralized in the main class
    // The settings submenu is now added in the centralized admin menu
  }

  /**
   * Add the centralized admin menu.
   *
   * @since    1.0.0
   */
  public function hostpn_centralized_admin_menu()
  {
    // Add main menu page — points directly to Settings
    add_menu_page(
      __('HostPN', 'hostpn'),
      __('HostPN', 'hostpn'),
      'manage_options',
      'hostpn',
      array($this, 'hostpn_options'),
      esc_url(HOSTPN_URL . 'assets/media/hostpn-part-menu-icon.svg')
    );

    // Rename auto-generated first submenu item from "HostPN" to "Settings"
    add_submenu_page(
      'hostpn',
      __('Settings', 'hostpn'),
      __('Settings', 'hostpn'),
      'manage_options',
      'hostpn',
      array($this, 'hostpn_options')
    );

    // Add submenu for Parts
    add_submenu_page(
      'hostpn',
      __('Part of travelers', 'hostpn'),
      __('Part of travelers', 'hostpn'),
      'manage_options',
      'edit.php?post_type=hostpn_part'
    );

    // Add submenu for Accommodations
    add_submenu_page(
      'hostpn',
      __('Accommodations', 'hostpn'),
      __('Accommodations', 'hostpn'),
      'manage_options',
      'edit.php?post_type=hostpn_accommodation'
    );

    // Add submenu for Guests
    add_submenu_page(
      'hostpn',
      __('Guests', 'hostpn'),
      __('Guests', 'hostpn'),
      'manage_options',
      'edit.php?post_type=hostpn_guest'
    );

    // Add submenu for Contract Generator
    add_submenu_page(
      'hostpn',
      __('Contract Generator', 'hostpn'),
      __('Contract Generator', 'hostpn'),
      'manage_options',
      'edit.php?post_type=hostpn_accommodation'
    );
  }

  /**
   * Find a page containing a specific shortcode.
   *
   * @param string $shortcode Shortcode name without brackets.
   * @return int|false Page ID if found, false otherwise.
   */
  public static function hostpn_find_page($shortcode)
  {
    $pages = get_posts([
      'post_type' => 'page',
      'post_status' => ['publish', 'draft', 'private'],
      'numberposts' => -1,
      'fields' => 'ids',
    ]);

    foreach ($pages as $page_id) {
      $content = get_post_field('post_content', $page_id);

      if (has_shortcode($content, $shortcode)) {
        return $page_id;
      }
    }

    return false;
  }

  private function render_pages_table($pages_config)
  {
    ?>
    <table class="hostpn-pages-table">
      <thead>
        <tr>
          <th><?php esc_html_e('Page', 'hostpn'); ?></th>
          <th><?php esc_html_e('Shortcode', 'hostpn'); ?></th>
          <th><?php esc_html_e('Status', 'hostpn'); ?></th>
          <th><?php esc_html_e('Actions', 'hostpn'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pages_config as $page_config):
          $page_id = self::hostpn_find_page($page_config['shortcode']);
          $page_exists = !empty($page_id) && get_post($page_id);
          ?>
          <tr>
            <td><strong><?php echo esc_html($page_config['label']); ?></strong></td>
            <td><code>[<?php echo esc_html($page_config['shortcode']); ?>]</code></td>
            <td>
              <?php if ($page_exists): ?>
                <span class="hostpn-page-status hostpn-page-status-active">
                  <span class="material-icons-outlined">check_circle</span>
                  <?php echo esc_html(get_post_status($page_id) === 'publish' ? __('Published', 'hostpn') : __('Draft', 'hostpn')); ?>
                </span>
              <?php else: ?>
                <span class="hostpn-page-status hostpn-page-status-missing">
                  <span class="material-icons-outlined">error_outline</span>
                  <?php esc_html_e('Not found', 'hostpn'); ?>
                </span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($page_exists): ?>
                <a href="<?php echo esc_url(get_edit_post_link($page_id)); ?>" class="hostpn-page-action-btn"
                  title="<?php esc_attr_e('Edit', 'hostpn'); ?>">
                  <span class="material-icons-outlined">edit</span>
                </a>
                <a href="<?php echo esc_url(get_permalink($page_id)); ?>" class="hostpn-page-action-btn" target="_blank"
                  title="<?php esc_attr_e('View', 'hostpn'); ?>">
                  <span class="material-icons-outlined">visibility</span>
                </a>
              <?php else: ?>
                <button type="button" class="hostpn-btn hostpn-btn-mini hostpn-create-page-btn"
                  data-hostpn-page-type="<?php echo esc_attr($page_config['key']); ?>">
                  <?php esc_html_e('Create page', 'hostpn'); ?>
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php
  }

  public function hostpn_options()
  {
    $hostpn_pages_config = [
      [
        'key' => 'accommodation',
        'label' => __('Accommodations', 'hostpn'),
        'shortcode' => 'hostpn-accommodation-list',
        'option' => 'hostpn_pages_accommodation',
      ],
      [
        'key' => 'guest',
        'label' => __('Guests', 'hostpn'),
        'shortcode' => 'hostpn-guest-list',
        'option' => 'hostpn_pages_guest',
      ],
      [
        'key' => 'part',
        'label' => __('Parts of travelers', 'hostpn'),
        'shortcode' => 'hostpn-part-list',
        'option' => 'hostpn_pages_part',
      ],
    ];
    ?>
    <div class="hostpn-options hostpn-max-width-1000 hostpn-margin-auto hostpn-mt-50 hostpn-mb-50">
      <h1 class="hostpn-mb-30"><?php esc_html_e('HOSTPN Options', 'hostpn'); ?></h1>

      <div class="hostpn-options-fields hostpn-mb-30 hostpn-settings-pb-80">
        <form action="" method="post" id="hostpn_form" class="hostpn-form">
          <?php foreach ($this->get_options() as $hostpn_option): ?>
            <?php if (isset($hostpn_option['input']) && $hostpn_option['input'] === 'pages_table'): ?>
              <?php $this->render_pages_table($hostpn_pages_config); ?>
            <?php else: ?>
              <?php HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_option, 'option', 0, 0, 'half'); ?>
            <?php endif; ?>
          <?php endforeach ?>
          <input type="submit" name="hostpn_submit" id="hostpn_submit" class="hostpn-settings-hidden-submit"
            data-hostpn-type="option" value="<?php esc_attr_e('Save options', 'hostpn'); ?>">
        </form>
      </div>
    </div>

    <?php
    // --- Recommended plugins ---
    $pn_family = [
      'pn-customers-manager' => [
        'name' => 'PN Customers Manager',
        'file' => 'pn-customers-manager/pn-customers-manager.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" fill="#667eea"/></svg>',
        'settings_page' => 'pn_customers_manager_options',
        'desc' => __('CRM with financial management and business tools.', 'hostpn'),
      ],
      'mailpn' => [
        'name' => 'MailPN',
        'file' => 'mailpn/mailpn.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 840 720"><path d="m558-240-170-170 56-56 114 114 226-226 56 56zM400-680 720-880H80zm0 80-320-200v400h206l80 80H80Q47-320 23.5-343.5 0-367 0-400V-880Q0-913 23.5-936.5 47-960 80-960h640q33 0 56.5 23.5 23.5 23.5 23.5 56.5v174l-80 80v-174zm0 0zm0-80zm0 80z" fill="#ffcc00"/></svg>',
        'settings_page' => 'mailpn_options',
        'desc' => __('Email marketing and newsletter campaigns.', 'hostpn'),
      ],
      'pn-tasks-manager' => [
        'name' => 'PN Tasks Manager',
        'file' => 'pn-tasks-manager/pn-tasks-manager.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" fill="#4caf50"/></svg>',
        'settings_page' => 'pn_tasks_manager_options',
        'desc' => __('Task and project management for teams.', 'hostpn'),
      ],
      'pn-cookies-manager' => [
        'name' => 'PN Cookies Manager',
        'file' => 'pn-cookies-manager/pn-cookies-manager.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-75 29-147t81-128.5q52-56.5 125-91T475-881q21 0 43 2t45 7q-9 45 6 85t45 66.5q30 26.5 71.5 36.5t85.5-5q-26 59 7.5 113t99.5 56q1 11 1.5 20.5t.5 20.5q0 82-31.5 154.5t-85.5 127q-54 54.5-127 86T480-80Zm-60-480q25 0 42.5-17.5T480-620q0-25-17.5-42.5T420-680q-25 0-42.5 17.5T360-620q0 25 17.5 42.5T420-560Zm-80 200q25 0 42.5-17.5T400-420q0-25-17.5-42.5T340-480q-25 0-42.5 17.5T280-420q0 25 17.5 42.5T340-360Zm260 40q17 0 28.5-11.5T640-360q0-17-11.5-28.5T600-400q-17 0-28.5 11.5T560-360q0 17 11.5 28.5T600-320ZM480-160q122 0 216-81t94-199q-90-8-163.5-50.5T504-600q-38-20-77.5-27.5T485-640Q276-625 178-494.5T80-182q0 45 35.5 83.5T205-60q54 0 107-21t98-61q45-40 98-61t107-21q-12 43-31 81.5T537-175q-28 29-60.5 52T480-160Z" fill="#ff6b6b"/></svg>',
        'settings_page' => 'pn_cookies_manager_options',
        'desc' => __('Cookie consent and GDPR compliance.', 'hostpn'),
      ],
    ];
    $pn_recommended = ['pn-customers-manager'];
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $pn_installed = get_plugins();
    $pn_rp_badge = 0;
    foreach ($pn_recommended as $pn_s) {
      if (isset($pn_family[$pn_s]) && !isset($pn_installed[$pn_family[$pn_s]['file']])) {
        $pn_rp_badge++;
      }
    }
    ?>

    <!-- Sticky settings footer bar -->
    <div id="hostpn-settings-footer" class="hostpn-settings-footer">
      <div class="hostpn-settings-footer-inner">
        <div class="hostpn-settings-footer-left">
          <span class="hostpn-settings-footer-plugin-name">Host Manager - PN</span>
          <span class="hostpn-settings-footer-version">v<?php echo esc_html(HOSTPN_VERSION); ?></span>
        </div>
        <div class="hostpn-settings-footer-right">
          <button type="button" id="hostpn-settings-recommended"
            class="hostpn-settings-footer-icon-btn pn-cm-rp-btn hostpn-tooltip"
            title="<?php esc_attr_e('Recommended plugins', 'hostpn'); ?>">
            <span class="material-icons-outlined">add</span>
            <?php if ($pn_rp_badge > 0): ?>
              <span class="pn-cm-rp-badge"><?php echo (int) $pn_rp_badge; ?></span>
            <?php endif; ?>
          </button>
          <input type="file" id="hostpn-settings-import-file" class="hostpn-settings-hidden-input" accept=".json">
          <button type="button" id="hostpn-settings-import" class="hostpn-settings-footer-icon-btn hostpn-tooltip"
            title="<?php esc_attr_e('Import settings', 'hostpn'); ?>">
            <span class="material-icons-outlined">file_upload</span>
          </button>
          <button type="button" id="hostpn-settings-export" class="hostpn-settings-footer-icon-btn hostpn-tooltip"
            title="<?php esc_attr_e('Export settings', 'hostpn'); ?>">
            <span class="material-icons-outlined">file_download</span>
          </button>
          <button type="button" id="hostpn-settings-save" class="hostpn-btn hostpn-btn-mini">
            <?php esc_html_e('Save options', 'hostpn'); ?>
          </button>
        </div>
      </div>
    </div>

    <!-- Recommended plugins popup -->
    <div class="hostpn-popup-overlay hostpn-display-none-soft"></div>
    <div id="hostpn-recommended-plugins" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
      <div class="hostpn-popup-content">
        <div class="hostpn-p-30">
          <h3 style="margin:0 0 8px;"><?php esc_html_e('Recommended Plugins', 'hostpn'); ?></h3>
          <p style="color:#787c82;margin:0 0 20px;">
            <?php esc_html_e('Enhance your workflow with these companion plugins.', 'hostpn'); ?>
          </p>
          <div class="pn-cm-rp-list">
            <?php foreach ($pn_family as $pn_slug => $pn_pl):
              $pn_is_installed = isset($pn_installed[$pn_pl['file']]);
              $pn_is_active = $pn_is_installed && is_plugin_active($pn_pl['file']);
              $pn_is_rec = in_array($pn_slug, $pn_recommended, true);
              ?>
              <div class="pn-cm-rp-card" data-slug="<?php echo esc_attr($pn_slug); ?>">
                <div class="pn-cm-rp-icon"><?php echo $pn_pl['icon']; ?></div>
                <div class="pn-cm-rp-info">
                  <div class="pn-cm-rp-name">
                    <?php echo esc_html($pn_pl['name']); ?>
                    <?php if ($pn_is_rec): ?>
                      <span class="pn-cm-rp-recommended"><?php esc_html_e('Recommended', 'hostpn'); ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="pn-cm-rp-desc"><?php echo esc_html($pn_pl['desc']); ?></div>
                </div>
                <div class="pn-cm-rp-action">
                  <?php if ($pn_is_active): ?>
                    <span class="pn-cm-rp-active-badge"><?php esc_html_e('Active', 'hostpn'); ?></span>
                  <?php elseif ($pn_is_installed): ?>
                    <button type="button" class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent pn-cm-rp-activate"
                      data-slug="<?php echo esc_attr($pn_slug); ?>"><?php esc_html_e('Activate', 'hostpn'); ?></button>
                  <?php else: ?>
                    <button type="button" class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent pn-cm-rp-install"
                      data-slug="<?php echo esc_attr($pn_slug); ?>"><?php esc_html_e('Install', 'hostpn'); ?></button>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <?php
    wp_enqueue_style('hostpn-tooltips', HOSTPN_URL . 'assets/css/hostpn-tooltips.css', [], HOSTPN_VERSION);
    wp_enqueue_script('hostpn-popups', HOSTPN_URL . 'assets/js/hostpn-popups.js', ['jquery'], HOSTPN_VERSION, true);
    wp_enqueue_script('hostpn-tooltips', HOSTPN_URL . 'assets/js/hostpn-tooltips.js', ['jquery'], HOSTPN_VERSION, true);
    wp_enqueue_script(
      'hostpn-settings-footer',
      HOSTPN_URL . 'assets/js/admin/hostpn-settings-footer.js',
      ['hostpn-popups'],
      HOSTPN_VERSION,
      true
    );

    $pn_rp_settings = [];
    foreach ($pn_family as $pn_slug => $pn_pl) {
      $pn_rp_settings[$pn_slug] = admin_url('admin.php?page=' . $pn_pl['settings_page']);
    }

    wp_localize_script('hostpn-settings-footer', 'hostpnSettingsFooter', [
      'ajaxUrl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('hostpn-nonce'),
      'settingsPages' => $pn_rp_settings,
      'i18n' => [
        'confirmImport' => __('This will overwrite your current settings. Continue?', 'hostpn'),
        'importSuccess' => __('Settings imported successfully. Reloading...', 'hostpn'),
        'importError' => __('Error importing settings.', 'hostpn'),
        'invalidFile' => __('Invalid JSON file.', 'hostpn'),
        'exportError' => __('Error exporting settings.', 'hostpn'),
        'creatingPage' => __('Creating page...', 'hostpn'),
        'createPage' => __('Create page', 'hostpn'),
        'errorCreatingPage' => __('An error occurred while creating the page.', 'hostpn'),
        'installing' => __('Installing...', 'hostpn'),
        'activating' => __('Activating...', 'hostpn'),
        'activate' => __('Activate', 'hostpn'),
        'active' => __('Active', 'hostpn'),
        'installError' => __('Error installing plugin.', 'hostpn'),
        'activateError' => __('Error activating plugin.', 'hostpn'),
      ],
    ]);
    ?>

    <?php
  }

  public function hostpn_display_post_state($post_states, $post)
  {
    $hostpn_pages = get_option('hostpn_pages') ?? [];

    if (in_array($post->ID, $hostpn_pages)) {
      $post_states['hostpn-post-state'] = __('Hospedajes España', 'hostpn');
    }

    return $post_states;
  }

  public function hostpn_activated_plugin($plugin)
  {
    if ($plugin == 'hostpn/hostpn.php') {
      if (get_option('hostpn_pages_accommodation') && get_option('hostpn_url_main')) {
        if (!get_transient('hostpn_just_activated') && !defined('DOING_AJAX')) {
          set_transient('hostpn_just_activated', true, 30);
        }
      }
    }
  }

  public function hostpn_check_activation()
  {
    // Only run in admin and not during AJAX requests
    if (!is_admin() || defined('DOING_AJAX')) {
      return;
    }

    // Check if we're already in the redirection process
    if (get_option('hostpn_redirecting')) {
      delete_option('hostpn_redirecting');
      return;
    }

    if (get_transient('hostpn_just_activated')) {
      $target_url = get_option('hostpn_url_main');

      if ($target_url) {
        // Mark that we're in the redirection process
        update_option('hostpn_redirecting', true);

        // Remove the transient
        delete_transient('hostpn_just_activated');

        // Redirect and exit
        wp_safe_redirect(esc_url($target_url));
        exit;
      }
    }
  }
}
