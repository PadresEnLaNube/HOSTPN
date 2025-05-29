<?php
/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to enqueue the common stylesheet and JavaScript.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Common {

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
	public function hostpn_enqueue_styles() {
		if (!wp_style_is($this->plugin_name . '-material-icons-outlined', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-material-icons-outlined', HOSTPN_URL . 'assets/css/material-icons-outlined.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-popups', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-popups', HOSTPN_URL . 'assets/css/hostpn-popups.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-selector', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-selector', HOSTPN_URL . 'assets/css/hostpn-selector.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-trumbowyg', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-trumbowyg', HOSTPN_URL . 'assets/css/trumbowyg.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-tooltipster', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-tooltipster', HOSTPN_URL . 'assets/css/tooltipster.min.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-owl', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-owl', HOSTPN_URL . 'assets/css/owl.min.css', [], $this->version, 'all');
    }

		wp_enqueue_style($this->plugin_name, HOSTPN_URL . 'assets/css/hostpn.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_enqueue_scripts() {
    if(!wp_script_is('jquery-ui-sortable', 'enqueued')) {
			wp_enqueue_script('jquery-ui-sortable');
    }

    if(!wp_script_is($this->plugin_name . '-trumbowyg', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-trumbowyg', HOSTPN_URL . 'assets/js/trumbowyg.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

		wp_localize_script($this->plugin_name . '-trumbowyg', 'hostpn_trumbowyg', [
			'path' => HOSTPN_URL . 'assets/media/trumbowyg-icons.svg',
		]);

    if(!wp_script_is($this->plugin_name . '-popups', 'enqueued')) {
      wp_enqueue_script($this->plugin_name . '-popups', HOSTPN_URL . 'assets/js/hostpn-popups.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-selector', 'enqueued')) {
      wp_enqueue_script($this->plugin_name . '-selector', HOSTPN_URL . 'assets/js/hostpn-selector.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-tooltipster', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-tooltipster', HOSTPN_URL . 'assets/js/tooltipster.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-owl', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-owl', HOSTPN_URL . 'assets/js/owl.min.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

		wp_enqueue_script($this->plugin_name, HOSTPN_URL . 'assets/js/hostpn.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-aux', HOSTPN_URL . 'assets/js/hostpn-aux.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-forms', HOSTPN_URL . 'assets/js/hostpn-forms.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-ajax', HOSTPN_URL . 'assets/js/hostpn-ajax.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);

		wp_localize_script($this->plugin_name . '-ajax', 'hostpn_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'hostpn_ajax_nonce' => wp_create_nonce('hostpn-nonce'),
		]);

		// Verify nonce for GET parameters
		$nonce_verified = false;
		if (!empty($_GET['hostpn_nonce'])) {
			$nonce_verified = wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['hostpn_nonce'])), 'hostpn-get-nonce');
		}

		// Only process GET parameters if nonce is verified
		$hostpn_action = '';
		$hostpn_btn_id = '';
		$hostpn_popup = '';
		$hostpn_tab = '';

		if ($nonce_verified) {
			$hostpn_action = !empty($_GET['hostpn_action']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_GET['hostpn_action'])) : '';
			$hostpn_btn_id = !empty($_GET['hostpn_btn_id']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_GET['hostpn_btn_id'])) : '';
			$hostpn_popup = !empty($_GET['hostpn_popup']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_GET['hostpn_popup'])) : '';
			$hostpn_tab = !empty($_GET['hostpn_tab']) ? HOSTPN_Forms::hostpn_sanitizer(wp_unslash($_GET['hostpn_tab'])) : '';
		}

		wp_localize_script($this->plugin_name, 'hostpn_path', [
			'main' => HOSTPN_URL,
			'assets' => HOSTPN_URL . 'assets/',
			'css' => HOSTPN_URL . 'assets/css/',
			'js' => HOSTPN_URL . 'assets/js/',
			'media' => HOSTPN_URL . 'assets/media/',
		]);
		
		wp_localize_script($this->plugin_name, 'hostpn_action', [
			'action' => $hostpn_action,
			'btn_id' => $hostpn_btn_id,
			'popup' => $hostpn_popup,
			'tab' => $hostpn_tab,
			'hostpn_get_nonce' => wp_create_nonce('hostpn-get-nonce'),
		]);

		wp_localize_script($this->plugin_name, 'hostpn_i18n', [
			'an_error_has_occurred' => esc_html(__('An error has occurred. Please try again in a few minutes.', 'hostpn')),
			'user_unlogged' => esc_html(__('Please create a new user or login to save the information.', 'hostpn')),
			'saved_successfully' => esc_html(__('Saved successfully', 'hostpn')),
			'removed_successfully' => esc_html(__('Removed successfully', 'hostpn')),
			'edit_image' => esc_html(__('Edit image', 'hostpn')),
			'edit_images' => esc_html(__('Edit images', 'hostpn')),
			'select_image' => esc_html(__('Select image', 'hostpn')),
			'select_images' => esc_html(__('Select images', 'hostpn')),
			'edit_video' => esc_html(__('Edit video', 'hostpn')),
			'edit_videos' => esc_html(__('Edit videos', 'hostpn')),
			'select_video' => esc_html(__('Select video', 'hostpn')),
			'select_videos' => esc_html(__('Select videos', 'hostpn')),
			'edit_audio' => esc_html(__('Edit audio', 'hostpn')),
			'edit_audios' => esc_html(__('Edit audios', 'hostpn')),
			'select_audio' => esc_html(__('Select audio', 'hostpn')),
			'select_audios' => esc_html(__('Select audios', 'hostpn')),
			'edit_file' => esc_html(__('Edit file', 'hostpn')),
			'edit_files' => esc_html(__('Edit files', 'hostpn')),
			'select_file' => esc_html(__('Select file', 'hostpn')),
			'select_files' => esc_html(__('Select files', 'hostpn')),
			'ordered_element' => esc_html(__('Ordered element', 'hostpn')),
			'select_option' => esc_html(__('Select option', 'hostpn')),
			'select_options' => esc_html(__('Select options', 'hostpn')),
			'copied' => esc_html(__('Copied', 'hostpn')),
		]);

		// Initialize popups
		HOSTPN_Popups::instance();

		// Initialize selectors
		HOSTPN_Selector::instance();
	}

  public function hostpn_body_classes($classes) {
	  $classes[] = 'hostpn-body';

	  if (!is_user_logged_in()) {
      $classes[] = 'hostpn-body-unlogged';
    }else{
      $classes[] = 'hostpn-body-logged-in';

      $user = new WP_User(get_current_user_id());
      foreach ($user->roles as $role) {
        $classes[] = 'hostpn-body-' . $role;
      }
    }

	  return $classes;
  }
}
