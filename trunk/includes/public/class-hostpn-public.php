<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/public
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Public {
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name . '-public', HOSTPN_URL . 'assets/css/public/hostpn-public.css', [], $this->version, 'all');

		// Enqueue carousel CSS
		wp_enqueue_style($this->plugin_name . '-carousel', HOSTPN_URL . 'assets/css/hostpn-carousel.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name . '-public', HOSTPN_URL . 'assets/js/public/hostpn-public.js', ['jquery'], $this->version, false);

		// Enqueue carousel JavaScript
		wp_enqueue_script($this->plugin_name . '-carousel', HOSTPN_URL . 'assets/js/hostpn-carousel.js', ['jquery'], $this->version, false);

		// Enqueue accommodation-specific JavaScript if on accommodation pages
		if (is_post_type_archive('hostpn_accommodation') || is_singular('hostpn_accommodation')) {
			wp_enqueue_script($this->plugin_name . '-accommodation-public', HOSTPN_URL . 'assets/js/public/hostpn-accommodation-public.js', ['jquery'], $this->version, false);
		}

		// Enqueue rooms block JS on single accommodation
		if (is_singular('hostpn_accommodation')) {
			wp_enqueue_script($this->plugin_name . '-rooms-block', HOSTPN_URL . 'assets/js/public/hostpn-rooms-block.js', ['jquery'], $this->version, false);
			wp_localize_script($this->plugin_name . '-rooms-block', 'hostpnRoomsBlock', [
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => wp_create_nonce('hostpn-nonce'),
				'i18n'    => [
					'subscribing'       => __('Sending...', 'hostpn'),
					'subscribed'        => __('We will notify you when available.', 'hostpn'),
					'invalidEmail'      => __('Please enter a valid email.', 'hostpn'),
					'alreadySubscribed' => __('You are already on the waiting list.', 'hostpn'),
					'notifyMe'          => __('Notify me', 'hostpn'),
					'waitingList'       => __('Waiting list', 'hostpn'),
				],
			]);
		}

		// Enqueue contracts block assets on single accommodation for logged-in users
		if (is_singular('hostpn_accommodation') && is_user_logged_in()) {
			wp_enqueue_style($this->plugin_name . '-contracts-block', HOSTPN_URL . 'assets/css/public/hostpn-contracts-block.css', [], $this->version, 'all');
			wp_enqueue_script($this->plugin_name . '-contracts-block', HOSTPN_URL . 'assets/js/public/hostpn-contracts-block.js', ['jquery'], $this->version, true);
			wp_localize_script($this->plugin_name . '-contracts-block', 'hostpnContractsBlock', [
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => wp_create_nonce('hostpn-nonce'),
				'i18n'    => [
					'onlyPdf'       => __('Only PDF files are allowed.', 'hostpn'),
					'uploading'     => __('Uploading...', 'hostpn'),
					'uploadSuccess' => __('Signed copy uploaded successfully.', 'hostpn'),
					'signed'        => __('Signed', 'hostpn'),
				],
			]);
			// Management tabs CSS+JS are enqueued from the template itself
			// (templates/public/hostpn-management-tabs.php) to guarantee they load
			// whenever the panel renders.
		}
	}
}