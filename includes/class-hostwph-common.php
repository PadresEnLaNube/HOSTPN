<?php
/**
 * The-global functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to enqueue the-global stylesheet and JavaScript.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Common {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if (!wp_style_is($this->plugin_name . '-material-icons-outlined', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-material-icons-outlined', HOSTWPH_URL . 'assets/css/material-icons-outlined.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-select2', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-select2', HOSTWPH_URL . 'assets/css/select2.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-trumbowyg', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-trumbowyg', HOSTWPH_URL . 'assets/css/trumbowyg.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-fancybox', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-fancybox', HOSTWPH_URL . 'assets/css/fancybox.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-tooltipster', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-tooltipster', HOSTWPH_URL . 'assets/css/tooltipster.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-owl', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-owl', HOSTWPH_URL . 'assets/css/owl.min.css', [], $this->version, 'all');
    }

		wp_enqueue_style($this->plugin_name, HOSTWPH_URL . 'assets/css/hostwph.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
    if(!wp_script_is('jquery-ui-sortable', 'enqueued')) {
			wp_enqueue_script('jquery-ui-sortable');
    }

    if(!wp_script_is($this->plugin_name . '-select2', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-select2', HOSTWPH_URL . 'assets/js/select2.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-trumbowyg', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-trumbowyg', HOSTWPH_URL . 'assets/js/trumbowyg.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-fancybox', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-fancybox', HOSTWPH_URL . 'assets/js/fancybox.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-tooltipster', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-tooltipster', HOSTWPH_URL . 'assets/js/tooltipster.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-owl', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-owl', HOSTWPH_URL . 'assets/js/owl.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

		wp_enqueue_script($this->plugin_name, HOSTWPH_URL . 'assets/js/hostwph.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-ajax', HOSTWPH_URL . 'assets/js/hostwph-ajax.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-aux', HOSTWPH_URL . 'assets/js/hostwph-aux.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-forms', HOSTWPH_URL . 'assets/js/hostwph-forms.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);

		wp_localize_script($this->plugin_name, 'hostwph_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce('hostwph-nonce'),
		]);

		wp_localize_script($this->plugin_name, 'hostwph_path', [
			'main' => HOSTWPH_URL,
			'assets' => HOSTWPH_URL . 'assets/',
			'css' => HOSTWPH_URL . 'assets/css/',
			'js' => HOSTWPH_URL . 'assets/js/',
			'media' => HOSTWPH_URL . 'assets/media/',
		]);

		// https://seasuite.es/?hostwph_action=popup_open&hostwph_popup=userswph-profile-popup&hostwph_tab=userswph-tab-register
		$hostwph_action = !empty($_GET['hostwph_action']) ? HOSTWPH_Forms::sanitizer(wp_unslash($_GET['hostwph_action'])) : '';
		$hostwph_btn_id = !empty($_GET['hostwph_btn_id']) ? HOSTWPH_Forms::sanitizer(wp_unslash($_GET['hostwph_btn_id'])) : '';
		$hostwph_popup = !empty($_GET['hostwph_popup']) ? HOSTWPH_Forms::sanitizer(wp_unslash($_GET['hostwph_popup'])) : '';
		$hostwph_tab = !empty($_GET['hostwph_tab']) ? HOSTWPH_Forms::sanitizer(wp_unslash($_GET['hostwph_tab'])) : '';
		
		wp_localize_script($this->plugin_name, 'hostwph_action', [
			'action' => $hostwph_action,
			'btn_id' => $hostwph_btn_id,
			'popup' => $hostwph_popup,
			'tab' => $hostwph_tab,
		]);

		wp_localize_script($this->plugin_name, 'hostwph_trumbowyg', [
			'path' => HOSTWPH_URL . 'assets/media/trumbowyg-icons.svg',
		]);

		wp_localize_script($this->plugin_name, 'hostwph_i18n', [
			'an_error_has_occurred' => esc_html(__('An error has occurred. Please try again in a few minutes.', 'hostwph')),
			'user_unlogged' => esc_html(__('Please create a new user or login to save the information.', 'hostwph')),
			'saved_successfully' => esc_html(__('Saved successfully', 'hostwph')),
			'edit_image' => esc_html(__('Edit image', 'hostwph')),
			'edit_images' => esc_html(__('Edit images', 'hostwph')),
			'select_image' => esc_html(__('Select image', 'hostwph')),
			'select_images' => esc_html(__('Select images', 'hostwph')),
			'edit_video' => esc_html(__('Edit video', 'hostwph')),
			'edit_videos' => esc_html(__('Edit videos', 'hostwph')),
			'select_video' => esc_html(__('Select video', 'hostwph')),
			'select_videos' => esc_html(__('Select videos', 'hostwph')),
			'edit_audio' => esc_html(__('Edit audio', 'hostwph')),
			'edit_audios' => esc_html(__('Edit audios', 'hostwph')),
			'select_audio' => esc_html(__('Select audio', 'hostwph')),
			'select_audios' => esc_html(__('Select audios', 'hostwph')),
			'edit_file' => esc_html(__('Edit file', 'hostwph')),
			'edit_files' => esc_html(__('Edit files', 'hostwph')),
			'select_file' => esc_html(__('Select file', 'hostwph')),
			'select_files' => esc_html(__('Select files', 'hostwph')),
			'ordered_element' => esc_html(__('Ordered element', 'hostwph')),
			'copied' => esc_html(__('Copied to clipboard', 'hostwph')),
		]);
	}

  public function hostwph_body_classes($classes) {
	  $classes[] = 'hostwph-body';

	  if (!is_user_logged_in()) {
      $classes[] = 'hostwph-body-unlogged';
    }else{
      $user = new WP_User(get_current_user_id());
      foreach ($user->roles as $role) {
        $classes[] = 'hostwph-body-' . $role;
      }
    }

	  return $classes;
  }
}
