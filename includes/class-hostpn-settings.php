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
class HOSTPN_Settings {

  /**
   * Get users for the notification recipients select field.
   *
   * @return array Associative array of user_id => display_name.
   */
  private static function get_users_for_select() {
    $users = get_users(['orderby' => 'display_name', 'order' => 'ASC']);
    $options = [];
    foreach ($users as $user) {
      $options[$user->ID] = $user->display_name . ' (' . $user->user_email . ')';
    }
    return $options;
  }

  public function get_options() {
    $hostpn_options = [];

    // ── DESIGN SECTION ───────────────────────────────────────────────
    $hostpn_options['hostpn_design_section_start'] = [
      'section' => 'start',
      'label' => __('Design', 'hostpn'),
      'description' => __('Configure the design of the plugin.', 'hostpn'),
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
    $hostpn_options['hostpn_design_section_end'] = [
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

    // ── SYSTEM SECTION ───────────────────────────────────────────────
    $hostpn_options['hostpn_system_section_start'] = [
      'section' => 'start',
      'label' => __('System', 'hostpn'),
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
    $hostpn_options['hostpn_system_section_end'] = [
      'section' => 'end',
    ];

    // ── ROLES SECTION ────────────────────────────────────────────────
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
	public function hostpn_admin_menu() {
    // This method is no longer needed as the menu is now centralized in the main class
    // The settings submenu is now added in the centralized admin menu
	}

	/**
	 * Add the centralized admin menu.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_centralized_admin_menu() {
		// Add main menu page
		add_menu_page(
			__('HostPN', 'hostpn'),
			__('HostPN', 'hostpn'),
			'manage_options',
			'hostpn',
			array($this, 'hostpn_admin_page_callback'),
			esc_url(HOSTPN_URL . 'assets/media/hostpn-part-menu-icon.svg')
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

		// Add submenu for Settings
		add_submenu_page(
			'hostpn',
			__('Settings', 'hostpn'),
			__('Settings', 'hostpn'),
			'manage_hostpn_options',
			'hostpn-options',
			array($this, 'hostpn_options')
		);
	}

	/**
	 * Callback for the centralized admin menu.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_admin_page_callback() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__('HostPN Dashboard', 'hostpn') . '</h1>';
		echo '<p>' . esc_html__('Welcome to the HostPN dashboard. Use the submenu to manage your content.', 'hostpn') . '</p>';
		echo '</div>';
	}

	public function hostpn_options() {
	  ?>
	    <div class="hostpn-options hostpn-max-width-1000 hostpn-margin-auto hostpn-mt-50 hostpn-mb-50">
        <h1 class="hostpn-mb-30"><?php esc_html_e('Base - HOSTPN Options', 'hostpn'); ?></h1>
        <div class="hostpn-options-fields hostpn-mb-30 pn-cm-settings-pb-80">
          <form action="" method="post" id="hostpn_form" class="hostpn-form">
            <?php foreach ($this->get_options() as $hostpn_option): ?>
              <?php HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_option, 'option', 0, 0, 'half'); ?>
            <?php endforeach ?>
          <input type="submit" name="hostpn_submit" id="hostpn_submit" class="pn-cm-settings-hidden-submit" data-hostpn-type="option" value="<?php esc_attr_e('Save options', 'hostpn'); ?>">
          </form>
        </div>
      </div>

      <!-- Sticky settings footer bar -->
      <div id="pn-cm-settings-footer" class="pn-cm-settings-footer">
        <div class="pn-cm-settings-footer-inner">
          <div class="pn-cm-settings-footer-left">
            <span class="pn-cm-settings-footer-plugin-name">Host Manager - PN</span>
            <span class="pn-cm-settings-footer-version">v<?php echo esc_html(HOSTPN_VERSION); ?></span>
          </div>
          <div class="pn-cm-settings-footer-right">
            <input type="file" id="pn-cm-settings-import-file" class="pn-cm-settings-hidden-input" accept=".json">
            <button type="button" id="pn-cm-settings-import" class="pn-cm-settings-footer-icon-btn" title="<?php esc_attr_e('Import settings', 'hostpn'); ?>">
              <span class="material-icons-outlined">file_upload</span>
            </button>
            <button type="button" id="pn-cm-settings-export" class="pn-cm-settings-footer-icon-btn" title="<?php esc_attr_e('Export settings', 'hostpn'); ?>">
              <span class="material-icons-outlined">file_download</span>
            </button>
            <button type="button" id="pn-cm-settings-save" class="hostpn-btn hostpn-btn-mini">
              <?php esc_html_e('Save options', 'hostpn'); ?>
            </button>
          </div>
        </div>
      </div>

      <?php
      wp_enqueue_script(
        'hostpn-settings-footer',
        HOSTPN_URL . 'assets/js/admin/hostpn-settings-footer.js',
        [],
        HOSTPN_VERSION,
        true
      );

      wp_localize_script('hostpn-settings-footer', 'pnCmSettingsFooter', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('hostpn-nonce'),
        'i18n'    => [
          'confirmImport'  => __('This will overwrite your current settings. Continue?', 'hostpn'),
          'importSuccess'  => __('Settings imported successfully. Reloading...', 'hostpn'),
          'importError'    => __('Error importing settings.', 'hostpn'),
          'invalidFile'    => __('Invalid JSON file.', 'hostpn'),
          'exportError'    => __('Error exporting settings.', 'hostpn'),
        ],
      ]);
      ?>
	  <?php
	}

  public function hostpn_display_post_state($post_states, $post) {
    $hostpn_pages = get_option('hostpn_pages') ?? [];

    if (in_array($post->ID, $hostpn_pages)) {
      $post_states['hostpn-post-state'] = __('Hospedajes España', 'hostpn');
    }

    return $post_states;
  }

  public function hostpn_activated_plugin($plugin) {
    if($plugin == 'hostpn/hostpn.php') {
      if (get_option('hostpn_pages_accommodation') && get_option('hostpn_url_main')) {
        if (!get_transient('hostpn_just_activated') && !defined('DOING_AJAX')) {
          set_transient('hostpn_just_activated', true, 30);
        }
      }
    }
  }

  public function hostpn_check_activation() {
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