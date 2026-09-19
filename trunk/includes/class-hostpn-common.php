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
			wp_enqueue_style($this->plugin_name . '-mgmt-tabs-css', HOSTPN_URL . 'assets/css/public/hostpn-management-tabs.css', [$this->plugin_name], $this->version, 'all');
			wp_enqueue_style($this->plugin_name . '-contract', HOSTPN_URL . 'assets/css/admin/hostpn-contract.css', [$this->plugin_name], $this->version, 'all');
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

		// Enqueue Chart.js for financial charts
		if (!wp_script_is($this->plugin_name . '-chartjs', 'enqueued')) {
			wp_enqueue_script($this->plugin_name . '-chartjs', HOSTPN_URL . 'assets/js/vendor/chart.min.js', [], '4.4.1', false, ['in_footer' => true, 'strategy' => 'defer']);
		}

		// Enqueue financial management script & management tabs JS
		wp_enqueue_script($this->plugin_name . '-financial', HOSTPN_URL . 'assets/js/hostpn-financial.js', ['jquery', $this->plugin_name . '-ajax', $this->plugin_name . '-chartjs'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_enqueue_script($this->plugin_name . '-mgmt-tabs-js', HOSTPN_URL . 'assets/js/public/hostpn-management-tabs.js', ['jquery', $this->plugin_name . '-ajax', $this->plugin_name . '-chartjs'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
		wp_localize_script($this->plugin_name . '-mgmt-tabs-js', 'hostpnMgmtTabs', [
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce'   => wp_create_nonce('hostpn-nonce'),
			'isAdmin' => current_user_can('manage_options') ? 1 : 0,
			'i18n'    => [
				'occupancy' => esc_html__('Tasa de Ocupación', 'hostpn'),
				'roomLabel' => esc_html__('Habitación', 'hostpn'),
				'guestName' => esc_html__('Huésped Actual', 'hostpn'),
				'monthlyRent' => esc_html__('Renta Mensual', 'hostpn'),
			]
		]);

		// Enqueue contract generation scripts (admin only)
		if (is_admin()) {
			wp_enqueue_script($this->plugin_name . '-html2pdf', HOSTPN_URL . 'assets/js/vendor/html2pdf.bundle.min.js', [], '0.10.1', false, ['in_footer' => true, 'strategy' => 'defer']);
			wp_enqueue_script($this->plugin_name . '-contract', HOSTPN_URL . 'assets/js/admin/hostpn-contract.js', ['jquery', $this->plugin_name . '-html2pdf'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);

			wp_localize_script($this->plugin_name . '-contract', 'hostpn_contract_data', [
				'shortcode_field_map' => HOSTPN_Contract_Templates::hostpn_get_shortcode_field_map(),
				'type_map' => [
					'habitacion' => 'habitacion',
					'vut'        => 'turistico',
					'vft'        => 'turistico',
				],
				'i18n' => [
					'generating' => esc_html__('Generating PDF...', 'hostpn'),
					'generate_pdf' => esc_html__('Generate PDF', 'hostpn'),
					'inventory_heading' => esc_html__('ANNEX: LEASED ITEMS INVENTORY', 'hostpn'),
					'inventory_item' => esc_html__('Item', 'hostpn'),
					'inventory_url' => esc_html__('URL', 'hostpn'),
					'inv_mobiliario' => esc_html__('Furniture', 'hostpn'),
					'inv_equipamiento_individual' => esc_html__('Individual equipment', 'hostpn'),
					'inv_menaje_individual' => esc_html__('Individual kitchenware', 'hostpn'),
					'inv_equipamiento_comunitario' => esc_html__('Community equipment', 'hostpn'),
					'inv_otros_enseres' => esc_html__('Other items', 'hostpn'),
					'summary_tenant' => esc_html__('Inquilino', 'hostpn'),
					'summary_contract' => esc_html__('Detalles del contrato', 'hostpn'),
					'summary_edit' => esc_html__('Editar', 'hostpn'),
					'summary_name' => esc_html__('Nombre', 'hostpn'),
					'summary_address' => esc_html__('Dirección', 'hostpn'),
					'summary_duration' => esc_html__('Duración', 'hostpn'),
					'summary_start' => esc_html__('Inicio', 'hostpn'),
					'summary_end' => esc_html__('Fin', 'hostpn'),
					'summary_rent' => esc_html__('Alquiler', 'hostpn'),
					'summary_payment_day' => esc_html__('Día de pago', 'hostpn'),
					'summary_notice' => esc_html__('Preaviso', 'hostpn'),
					'summary_days' => esc_html__('días', 'hostpn'),
					'summary_deposit' => esc_html__('Depósito', 'hostpn'),
				],
			]);

			// Enqueue contract settings JS on the settings page
			$screen = function_exists('get_current_screen') ? get_current_screen() : null;
			if ($screen && strpos($screen->id, 'hostpn') !== false) {
				wp_enqueue_script($this->plugin_name . '-contract-settings', HOSTPN_URL . 'assets/js/admin/hostpn-contract-settings.js', ['jquery'], $this->version, false, ['in_footer' => true, 'strategy' => 'defer']);
				wp_localize_script($this->plugin_name . '-contract-settings', 'hostpn_contract_settings_i18n', [
					'save_template' => esc_html__('Save template', 'hostpn'),
					'saved' => esc_html__('Saved successfully', 'hostpn'),
					'confirm_restore' => esc_html__('Restore default texts? Unsaved changes will be lost.', 'hostpn'),
				]);
			}
		}

		// Note: Public contract scripts (signature pad, html2pdf, contract-public.js)
		// are enqueued conditionally via hostpn_contract_template_redirect() when the
		// contract shared link is accessed.

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
			'creating_user' => esc_html(__('Creating user...', 'hostpn')),
			'user_created' => esc_html(__('User created successfully.', 'hostpn')),
			'user_linked' => esc_html(__('Guest linked to existing user.', 'hostpn')),
			'user_already_exists' => esc_html(__('This guest already has a linked user.', 'hostpn')),
			'create_user' => esc_html(__('Create user', 'hostpn')),
			'view_user' => esc_html(__('View user', 'hostpn')),
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
