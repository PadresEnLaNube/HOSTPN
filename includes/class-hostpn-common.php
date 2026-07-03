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

    if (!wp_style_is($this->plugin_name . '-tooltips', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-tooltips', HOSTPN_URL . 'assets/css/hostpn-tooltips.css', [], $this->version, 'all');
    }

    if (!wp_style_is($this->plugin_name . '-carousel', 'enqueued')) {
			wp_enqueue_style($this->plugin_name . '-carousel', HOSTPN_URL . 'assets/css/hostpn-carousel.css', [], $this->version, 'all');
    }

		wp_enqueue_style($this->plugin_name, HOSTPN_URL . 'assets/css/hostpn.css', [], $this->version, 'all');

		// Enqueue financial management styles (admin only)
		if (is_admin()) {
			wp_enqueue_style($this->plugin_name . '-financial', HOSTPN_URL . 'assets/css/admin/hostpn-financial.css', [$this->plugin_name], $this->version, 'all');
		}
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

    if(!wp_script_is($this->plugin_name . '-tooltips', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-tooltips', HOSTPN_URL . 'assets/js/hostpn-tooltips.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

    if(!wp_script_is($this->plugin_name . '-carousel', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-carousel', HOSTPN_URL . 'assets/js/hostpn-carousel.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
    }

		wp_enqueue_script($this->plugin_name, HOSTPN_URL . 'assets/js/hostpn.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-aux', HOSTPN_URL . 'assets/js/hostpn-aux.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-forms', HOSTPN_URL . 'assets/js/hostpn-forms.js', ['jquery', 'jquery-ui-sortable'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-ajax', HOSTPN_URL . 'assets/js/hostpn-ajax.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);

		// Enqueue financial management script
		wp_enqueue_script($this->plugin_name . '-financial', HOSTPN_URL . 'assets/js/hostpn-financial.js', ['jquery', $this->plugin_name . '-ajax'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);

		// Get loader HTML from HOSTPN_Data
		ob_start();
		HOSTPN_Data::hostpn_popup_loader();
		$popup_loader = ob_get_clean();

		ob_start();
		HOSTPN_Data::hostpn_loader();
		$mini_loader = ob_get_clean();

		wp_localize_script($this->plugin_name . '-ajax', 'hostpn_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'hostpn_ajax_nonce' => wp_create_nonce('hostpn-nonce'),
			'plugin_url' => HOSTPN_URL,
			'popup_loader' => $popup_loader,
			'mini_loader' => $mini_loader,
			'translations' => [
				'loading' => esc_html__('Loading...', 'hostpn'),
				'confirm_delete' => esc_html__('Are you sure you want to delete this record?', 'hostpn'),
				'confirm_batch_delete' => esc_html__('Are you sure you want to delete all records from this import batch?', 'hostpn'),
			],
		]);

		if (class_exists('USERSPN')) {
			wp_localize_script($this->plugin_name . '-ajax', 'userspn_ajax', [
				'userspn_ajax_nonce' => wp_create_nonce('userspn-nonce'),
			]);
		}

		// Verify nonce for GET parameters
		$nonce_verified = false;
		if (!empty($_GET['hostpn_get_nonce'])) {
			$nonce_verified = wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['hostpn_get_nonce'])), 'hostpn-get-nonce');
		}

				// Only process GET parameters if nonce is verified
		$hostpn_action = '';
		$hostpn_btn_id = '';
		$hostpn_popup = '';
		$hostpn_tab = '';

		if ($nonce_verified || !empty($_GET['hostpn_action'])) {
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
			'sending' => esc_html(__('Sending...', 'hostpn')),
			'notification_sent' => esc_html(__('Notification sent successfully', 'hostpn')),
			'edit_image' => esc_html(__('Edit image', 'hostpn')),
			'edit_images' => esc_html(__('Edit images', 'hostpn')),
			'select_image' => esc_html(__('Select image', 'hostpn')),
			'select_images' => esc_html(__('Select images', 'hostpn')),
			'use_image' => esc_html(__('Use image', 'hostpn')),
			'use_images' => esc_html(__('Use images', 'hostpn')),
			'remove' => esc_html(__('Remove', 'hostpn')),
			'edit_video' => esc_html(__('Edit video', 'hostpn')),
			'edit_videos' => esc_html(__('Edit videos', 'hostpn')),
			'select_video' => esc_html(__('Select video', 'hostpn')),
			'select_videos' => esc_html(__('Select videos', 'hostpn')),
			'use_video' => esc_html(__('Use video', 'hostpn')),
			'use_videos' => esc_html(__('Use videos', 'hostpn')),
			'edit_audio' => esc_html(__('Edit audio', 'hostpn')),
			'edit_audios' => esc_html(__('Edit audios', 'hostpn')),
			'select_audio' => esc_html(__('Select audio', 'hostpn')),
			'select_audios' => esc_html(__('Select audios', 'hostpn')),
			'use_audio' => esc_html(__('Use audio', 'hostpn')),
			'use_audios' => esc_html(__('Use audios', 'hostpn')),
			'edit_file' => esc_html(__('Edit file', 'hostpn')),
			'edit_files' => esc_html(__('Edit files', 'hostpn')),
			'select_file' => esc_html(__('Select file', 'hostpn')),
			'select_files' => esc_html(__('Select files', 'hostpn')),
			'use_file' => esc_html(__('Use file', 'hostpn')),
			'use_files' => esc_html(__('Use files', 'hostpn')),
			'ordered_element' => esc_html(__('Ordered element', 'hostpn')),
			'select_option' => esc_html(__('Select option', 'hostpn')),
			'select_options' => esc_html(__('Select options', 'hostpn')),
			'copied' => esc_html(__('Copied', 'hostpn')),
			'sort_newest' => esc_html(__('Sort: Newest first', 'hostpn')),
			'sort_oldest' => esc_html(__('Sort: Oldest first', 'hostpn')),
			'sort_name_az' => esc_html(__('Sort: A-Z', 'hostpn')),
			'sort_name_za' => esc_html(__('Sort: Z-A', 'hostpn')),
		]);

		// Pass CPTs to JavaScript
		wp_localize_script($this->plugin_name, 'hostpn_cpts', HOSTPN_CPTS);

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
