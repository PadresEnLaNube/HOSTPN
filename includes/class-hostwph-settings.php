<?php
/**
 * Plugin settings manager.
 *
 * This class defines plugin settings, both in dashboard or in front-end.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Settings {
  public function get_options() {
    $hostwph_options = [];
    $hostwph_options['hostwph'] = [
      'id' => 'hostwph',
      'class' => 'hostwph-input hostwph-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Guest slug', 'hostwph'),
      'placeholder' => __('Guest slug', 'hostwph'),
      'description' => __('This option sets the slug of the main Guest archive page, and the Guest pages. By default they will be:', 'hostwph') . '<br><a href="' . esc_url(home_url('/host')) . '" target="_blank">' . esc_url(home_url('/host')) . '</a><br>' . esc_url(home_url('/host/host-name')),
    ];
    $hostwph_options['hostwph_options_remove'] = [
      'id' => 'hostwph_options_remove',
      'class' => 'hostwph-input hostwph-width-100-percent',
      'input' => 'input',
      'type' => 'checkbox',
      'label' => __('Remove plugin options on deactivation', 'hostwph'),
      'description' => __('If you activate this option the plugin will remove all options on deactivation. Please, be careful. This process cannot be undone.', 'hostwph'),
    ];
    $hostwph_options['hostwph_nonce'] = [
      'id' => 'hostwph_nonce',
      'input' => 'input',
      'type' => 'hidden',
    ];
    $hostwph_options['hostwph_submit'] = [
      'id' => 'hostwph_submit',
      'input' => 'input',
      'type' => 'submit',
      'value' => __('Save options', 'hostwph'),
    ];

    return $hostwph_options;
  }

	/**
	 * Administrator menu.
	 *
	 * @since    1.0.0
	 */
	public function hostwph_admin_menu() {
    // add_menu_page(__('Users manager', 'userswph'), __('Users manager', 'userswph'), 'administrator', 'userswph_options', [$this, 'userswph_options'], esc_url(USERSWPH_URL . 'assets/media/userswph-menu-icon.svg'));
		add_submenu_page('edit.php?post_type=hostwph_guest', esc_html(__('Settings', 'hostwph')), esc_html(__('Settings', 'hostwph')), 'manage_hostwph_options', 'hostwph-options', [$this, 'hostwph_options'], );
	}

	public function hostwph_options() {
	  ?>
	    <div class="hostwph-options hostwph-max-width-1000 hostwph-margin-auto hostwph-mt-50 hostwph-mb-50">
        <h1 class="hostwph-mb-30"><?php esc_html_e('Base - WPH Options', 'hostwph'); ?></h1>
        <div class="hostwph-options-fields hostwph-mb-30">
          <form action="" method="post" id="hostwph_form" class="hostwph-form">
            <?php foreach ($this->get_options() as $hostwph_option): ?>
              <?php HOSTWPH_Forms::input_wrapper_builder($hostwph_option, 'option', 0, 0, 'half'); ?>
            <?php endforeach ?>
          </form> 
        </div>
      </div>
	  <?php
	}

  public function hostwph_display_post_state($post_states, $post) {
    $hostwph_pages = get_option('hostwph_pages') ?? [];

    if (in_array($post->ID, $hostwph_pages)) {
      $post_states['hostwph-post-state'] = __('Hospedajes España', 'hostwph');
    }

    return $post_states;
  }

  public function activated_plugin($plugin) {
    if($plugin == 'hostwph/hostwph.php') {
      if (!empty(get_option('hostwph_url_main'))) {
        wp_redirect(esc_url(get_option('hostwph_url_main')));exit();
      }
    }
  }
}