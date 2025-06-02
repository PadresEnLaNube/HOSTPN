<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current version of the plugin.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */

class HOSTPN {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      HOSTPN_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin. Load the dependencies, define the locale, and set the hooks for the admin area and the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if (defined('HOSTPN_VERSION')) {
			$this->version = HOSTPN_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'hostpn';

		$this->hostpn_load_dependencies();
		$this->hostpn_load_i18n();
		$this->hostpn_define_common_hooks();
		$this->hostpn_define_admin_hooks();
		$this->hostpn_define_public_hooks();
		$this->hostpn_define_post_types();
		$this->hostpn_define_taxonomies();
		$this->hostpn_load_ajax();
		$this->hostpn_load_ajax_nopriv();
		$this->hostpn_load_data();
		$this->hostpn_load_templates();
		$this->hostpn_load_settings();
		$this->hostpn_load_shortcodes();
	}
			
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 * - HOSTPN_Loader. Orchestrates the hooks of the plugin.
	 * - HOSTPN_i18n. Defines internationalization functionality.
	 * - HOSTPN_Common. Defines hooks used accross both, admin and public side.
	 * - HOSTPN_Admin. Defines all hooks for the admin area.
	 * - HOSTPN_Public. Defines all hooks for the public side of the site.
	 * - HOSTPN_Post_Type_Guest. Defines Guest custom post type.
	 * - HOSTPN_Post_Type_Accommodation. Defines Accommodation custom post type.
	 * - HOSTPN_Post_Type_Part. Defines Part custom post type.
	 * - HOSTPN_Taxonomies_Host. Defines Guest taxonomies.
	 * - HOSTPN_Templates. Load plugin templates.
	 * - HOSTPN_Data. Load main usefull data.
	 * - HOSTPN_Functions_Post. Posts management functions.
	 * - HOSTPN_Functions_User. Users management functions.
	 * - HOSTPN_Functions_Attachment. Attachments management functions.
	 * - HOSTPN_Functions_Settings. Define settings.
	 * - HOSTPN_Functions_Forms. Forms management functions.
	 * - HOSTPN_Functions_Ajax. Ajax functions.
	 * - HOSTPN_Functions_Ajax_Nopriv. Ajax No Private functions.
	 * - HOSTPN_Functions_Shortcodes. Define all shortcodes for the platform.
	 * - HOSTPN_Functions_Validation. Define validation and sanitization.
	 *
	 * Create an instance of the loader which will be used to register the hooks with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-i18n.php';

		/**
		 * The class responsible for defining all actions that occur both in the admin area and in the public-facing side of the site.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-common.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once HOSTPN_DIR . 'includes/admin/class-hostpn-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 */
		require_once HOSTPN_DIR . 'includes/public/class-hostpn-public.php';

		/**
		 * The class responsible for create the Accommodation custom post type.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-post-type-accommodation.php';

		/**
		 * The class responsible for create the Accommodation custom taxonomies.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-taxonomies-accommodation.php';

		/**
		 * The class responsible for create the Part of traveler custom post type.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-post-type-part.php';

		/**
		 * The class responsible for create the Part of traveler custom taxonomies.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-taxonomies-part.php';

		/**
		 * The class responsible for create the Guest custom post type.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-post-type-guest.php';

		/**
		 * The class responsible for create the Guest custom taxonomies.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-taxonomies-guest.php';

		/**
		 * The class responsible for plugin templates.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-templates.php';

		/**
		 * The class getting key data of the platform.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-data.php';

		/**
		 * The class defining posts management functions.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-functions-post.php';

		/**
		 * The class defining users management functions.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-functions-user.php';

		/**
		 * The class defining attahcments management functions.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-functions-attachment.php';

		/**
		 * The class defining settings.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-settings.php';

		/**
		 * The class defining form management.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-forms.php';

		/**
		 * The class defining validation and sanitization.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-validation.php';

		/**
		 * The class defining XML management.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-xml.php';

		/**
		 * The class defining ajax functions.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-ajax.php';

		/**
		 * The class defining no private ajax functions.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-ajax-nopriv.php';

		/**
		 * The class defining shortcodes.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-shortcodes.php';

		/**
		 * The class defining popups.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-popups.php';

		/**
		 * The class defining selectors.
		 */
		require_once HOSTPN_DIR . 'includes/class-hostpn-selector.php';

		$this->loader = new HOSTPN_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the HOSTPN_i18n class in order to set the domain and to register the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_i18n() {
		$plugin_i18n = new HOSTPN_i18n();
		$this->loader->hostpn_add_action('after_setup_theme', $plugin_i18n, 'hostpn_load_plugin_textdomain');

		if (class_exists('Polylang')) {
			$this->loader->hostpn_add_filter('pll_get_post_types', $plugin_i18n, 'hostpn_pll_get_post_types', 10, 2);
		}
	}

	/**
	 * Register all of the hooks related to the main functionalities of the plugin, common to public and admin faces.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_define_common_hooks() {
		$plugin_common = new HOSTPN_Common($this->hostpn_get_plugin_name(), $this->hostpn_get_version());
		$this->loader->hostpn_add_action('wp_enqueue_scripts', $plugin_common, 'hostpn_enqueue_styles');
		$this->loader->hostpn_add_action('wp_enqueue_scripts', $plugin_common, 'hostpn_enqueue_scripts');
		$this->loader->hostpn_add_action('admin_enqueue_scripts', $plugin_common, 'hostpn_enqueue_styles');
		$this->loader->hostpn_add_action('admin_enqueue_scripts', $plugin_common, 'hostpn_enqueue_scripts');
		$this->loader->hostpn_add_filter('body_class', $plugin_common, 'hostpn_body_classes');

		$plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
		$this->loader->hostpn_add_action('hostpn_form_save', $plugin_post_type_accommodation, 'hostpn_accommodation_form_save',  999, 5);

		$plugin_post_type_part = new HOSTPN_Post_Type_Part();
		$this->loader->hostpn_add_action('hostpn_form_save', $plugin_post_type_part, 'hostpn_part_form_save',  999, 5);

		$plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
		$this->loader->hostpn_add_action('hostpn_form_save', $plugin_post_type_guest, 'hostpn_guest_form_save',  999, 5);

		$plugin_user = new HOSTPN_Functions_User();
		$this->loader->hostpn_add_filter('userspn_register_fields', $plugin_user, 'hostpn_user_register_fields', 10, 2);
		$this->loader->hostpn_add_action('user_register', $plugin_user, 'hostpn_user_register', 11, 1);
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_define_admin_hooks() {
		$plugin_admin = new HOSTPN_Admin($this->hostpn_get_plugin_name(), $this->hostpn_get_version());
		$this->loader->hostpn_add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->hostpn_add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_define_public_hooks() {
		$plugin_public = new HOSTPN_Public($this->hostpn_get_plugin_name(), $this->hostpn_get_version());
		$this->loader->hostpn_add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->hostpn_add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$plugin_user = new HOSTPN_Functions_User();
		$this->loader->hostpn_add_action('wp_login', $plugin_user, 'hostpn_wp_login');
	}

	/**
	 * Register all Post Types with meta boxes and templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_define_post_types() {
		$plugin_post_type_part = new HOSTPN_Post_Type_Part();
		$this->loader->hostpn_add_action('init', $plugin_post_type_part, 'hostpn_part_register_post_type');
		$this->loader->hostpn_add_action('admin_init', $plugin_post_type_part, 'hostpn_part_add_meta_box');
		$this->loader->hostpn_add_action('save_post_hostpn_part', $plugin_post_type_part, 'hostpn_part_save_post', 10, 3);
		$this->loader->hostpn_add_shortcode('hostpn-part-list', $plugin_post_type_part, 'hostpn_part_list_wrapper');
	
		$plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
		$this->loader->hostpn_add_action('init', $plugin_post_type_accommodation, 'hostpn_accommodation_register_post_type');
		$this->loader->hostpn_add_action('admin_init', $plugin_post_type_accommodation, 'hostpn_accommodation_add_meta_box');
		$this->loader->hostpn_add_action('save_post_hostpn_accommodation', $plugin_post_type_accommodation, 'hostpn_accommodation_save_post', 10, 3);
		$this->loader->hostpn_add_shortcode('hostpn-accommodation-list', $plugin_post_type_accommodation, 'hostpn_accommodation_list_wrapper');

		$plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
		$this->loader->hostpn_add_action('init', $plugin_post_type_guest, 'hostpn_guest_register_post_type');
		$this->loader->hostpn_add_action('admin_init', $plugin_post_type_guest, 'hostpn_guest_add_meta_box');
		$this->loader->hostpn_add_action('save_post_hostpn_guest', $plugin_post_type_guest, 'hostpn_guest_save_post', 10, 3);
		$this->loader->hostpn_add_shortcode('hostpn-guest-list', $plugin_post_type_guest, 'hostpn_guest_list_wrapper');
	}

	/**
	 * Register all of the hooks related to Taxonomies.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_define_taxonomies() {
		$plugin_taxonomies_host = new HOSTPN_Taxonomies_Host();
		$this->loader->hostpn_add_action('init', $plugin_taxonomies_host, 'register_taxonomies');
	}

	/**
	 * Load most common data used on the platform.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_data() {
		$plugin_data = new HOSTPN_Data();

		if (is_admin()) {
			$this->loader->hostpn_add_action('init', $plugin_data, 'hostpn_load_plugin_data');
		}else{
			$this->loader->hostpn_add_action('wp_footer', $plugin_data, 'hostpn_load_plugin_data');
		}

		$this->loader->hostpn_add_action('wp_footer', $plugin_data, 'hostpn_flush_rewrite_rules');
		$this->loader->hostpn_add_action('admin_footer', $plugin_data, 'hostpn_flush_rewrite_rules');
		
		$plugin_user = new HOSTPN_Functions_User();
		$this->loader->hostpn_add_action('wp_footer', $plugin_user, 'hostpn_user_to_guest');
		$this->loader->hostpn_add_action('admin_footer', $plugin_user, 'hostpn_user_to_guest');
	}

	/**
	 * Register templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_templates() {
		if (!defined('DOING_AJAX')) {
			$plugin_templates = new HOSTPN_Templates();
			$this->loader->hostpn_add_action('wp_footer', $plugin_templates, 'load_plugin_templates');
			$this->loader->hostpn_add_action('admin_footer', $plugin_templates, 'load_plugin_templates');
		}
	}

	/**
	 * Register settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_settings() {
		$plugin_settings = new HOSTPN_Settings();
		$this->loader->hostpn_add_action('admin_menu', $plugin_settings, 'hostpn_admin_menu');
		$this->loader->hostpn_add_filter('display_post_states', $plugin_settings, 'hostpn_display_post_state', 10, 2);
		$this->loader->hostpn_add_action('activated_plugin', $plugin_settings, 'hostpn_activated_plugin');
		$this->loader->hostpn_add_action('admin_init', $plugin_settings, 'hostpn_check_activation');
	}

	/**
	 * Load ajax functions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_ajax() {
		$plugin_ajax = new HOSTPN_Ajax();
		$this->loader->hostpn_add_action('wp_ajax_hostpn_ajax', $plugin_ajax, 'hostpn_ajax_server');
	}

	/**
	 * Load no private ajax functions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_ajax_nopriv() {
		$plugin_ajax_nopriv = new HOSTPN_Ajax_Nopriv();
		$this->loader->hostpn_add_action('wp_ajax_hostpn_ajax_nopriv', $plugin_ajax_nopriv, 'hostpn_ajax_nopriv_server');
		$this->loader->hostpn_add_action('wp_ajax_nopriv_hostpn_ajax_nopriv', $plugin_ajax_nopriv, 'hostpn_ajax_nopriv_server');
	}

	/**
	 * Register shortcodes of the platform.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hostpn_load_shortcodes() {
		$plugin_shortcodes = new HOSTPN_Shortcodes();
		$this->loader->hostpn_add_shortcode('hostpn-navigation', $plugin_shortcodes, 'hostpn_navigation');
		$this->loader->hostpn_add_shortcode('hostpn-call-to-action', $plugin_shortcodes, 'hostpn_call_to_action');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress. Then it flushes the rewrite rules if needed.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_run() {
		$this->loader->hostpn_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function hostpn_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    HOSTPN_Loader    Orchestrates the hooks of the plugin.
	 */
	public function hostpn_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function hostpn_get_version() {
		return $this->version;
	}
}