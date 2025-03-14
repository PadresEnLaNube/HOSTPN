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
    // add_menu_page(__('Users manager', 'userswph'), __('Users manager', 'userswph'), 'administrator', 'userswph_options', [$this, 'userswph_options'], esc_url(USERSWPH_URL . 'assets/media/userswph-menu-icon.svg'));
		add_submenu_page('edit.php?post_type=hostpn_guest', esc_html(__('Settings', 'hostpn')), esc_html(__('Settings', 'hostpn')), 'manage_hostpn_options', 'hostpn-options', [$this, 'hostpn_options'], );
	}

	public function hostpn_options() {
	  ?>
	    <div class="hostpn-options hostpn-max-width-1000 hostpn-margin-auto hostpn-mt-50 hostpn-mb-50">
        <h1 class="hostpn-mb-30"><?php esc_html_e('Base - WPH Options', 'hostpn'); ?></h1>
        <div class="hostpn-options-fields hostpn-mb-30">
          <form action="" method="post" id="hostpn_form" class="hostpn-form">
            <?php foreach ($this->get_options() as $hostpn_option): ?>
              <?php HOSTPN_Forms::input_wrapper_builder($hostpn_option, 'option', 0, 0, 'half'); ?>
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

  public function activated_plugin($plugin) {
    if($plugin == 'hostpn/hostpn.php') {
      if (!empty(get_option('hostpn_url_main'))) {
        wp_redirect(esc_url(get_option('hostpn_url_main')));exit();
      }
    }
  }
}