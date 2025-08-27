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
  public function get_options() {
    $hostpn_options = [];
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
    $hostpn_options['hostpn_nonce'] = [
      'id' => 'hostpn_nonce',
      'input' => 'input',
      'type' => 'hidden',
    ];
    $hostpn_options['hostpn_submit'] = [
      'id' => 'hostpn_submit',
      'input' => 'input',
      'type' => 'submit',
      'value' => __('Save options', 'hostpn'),
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
			esc_url(HOSTPN_URL . 'assets/media/hostpn-part-menu-icon.svg'),
			6
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
        <div class="hostpn-options-fields hostpn-mb-30">
          <form action="" method="post" id="hostpn_form" class="hostpn-form">
            <?php foreach ($this->get_options() as $hostpn_option): ?>
              <?php HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_option, 'option', 0, 0, 'half'); ?>
            <?php endforeach ?>
          </form> 
        </div>
      </div>
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