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

		$this->define_constants();
		$this->load_dependencies();
		$this->set_i18n();
		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_post_types();
		$this->define_taxonomies();
		$this->load_ajax();
		$this->load_ajax_nopriv();
		$this->load_data();
		$this->load_templates();
		$this->load_settings();
		$this->load_shortcodes();
	}

	/**
	 * Define the plugin main constants.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_constants() {
		define('HOSTPN_DIR', plugin_dir_path(dirname(__FILE__)));
		define('HOSTPN_URL', plugin_dir_url(dirname(__FILE__)));
		
		define('HOSTPN_ROLE_CAPABILITIES', ['edit_post' => 'edit_wph_host', 'edit_posts' => 'edit_hostpn', 'edit_private_posts' => 'edit_private_hostpn', 'edit_published_posts' => 'edit_published_hostpn', 'edit_others_posts' => 'edit_other_hostpn', 'publish_posts' => 'publish_hostpn', 'read_post' => 'read_wph_host', 'read_private_posts' => 'read_private_hostpn', 'delete_post' => 'delete_wph_host', 'delete_posts' => 'delete_hostpn', 'delete_private_posts' => 'delete_private_hostpn', 'delete_published_posts' => 'delete_published_hostpn', 'delete_others_posts' => 'delete_others_hostpn', 'upload_files' => 'upload_files', 'manage_terms' => 'manage_hostpn_category', 'edit_terms' => 'edit_hostpn_category', 'delete_terms' => 'delete_hostpn_category', 'assign_terms' => 'assign_hostpn_category', 'manage_options' => 'manage_hostpn_options', ]);

		define('HOSTPN_KSES', ['div' => ['id' => [], 'class' => [], 'data-hostpn-section-id' => [], ], 'span' => ['id' => [], 'class' => [], ], 'p' => ['id' => [], 'class' => [], ], 'ul' => ['id' => [], 'class' => [], ], 'ol' => ['id' => [], 'class' => [], ], 'li' => ['id' => [], 'class' => [], ], 'small' => ['id' => [], 'class' => [], ], 'a' => ['id' => [], 'class' => [], 'href' => [], 'title' => [], 'target' => [], ], 'form' => ['id' => [], 'class' => [], 'action' => [], 'method' => [], ], 'input' => ['name' => [], 'id' => [], 'class' => [], 'type' => [], 'checked' => [], 'multiple' => [], 'disabled' => [], 'value' => [], 'placeholder' => [], 'data-hostpn-parent' => [], 'data-hostpn-parent-option' => [], 'data-hostpn-parent-option' => [], 'data-hostpn-type' => [], 'data-hostpn-subtype' => [], 'data-hostpn-user-id' => [], 'data-hostpn-post-id' => [],], 'select' => ['name' => [], 'id' => [], 'class' => [], 'type' => [], 'checked' => [], 'multiple' => [], 'disabled' => [], 'value' => [], 'placeholder' => [], 'data-placeholder' => [], 'data-hostpn-parent' => [], 'data-hostpn-parent-option' => [], ], 'option' => ['name' => [], 'id' => [], 'class' => [], 'disabled' => [], 'selected' => [], 'value' => [], 'placeholder' => [], ], 'textarea' => ['name' => [], 'id' => [], 'class' => [], 'type' => [], 'multiple' => [], 'disabled' => [], 'value' => [], 'placeholder' => [], 'data-hostpn-parent' => [], 'data-hostpn-parent-option' => [], ], 'label' => ['id' => [], 'class' => [], 'for' => [], ], 'i' => ['id' => [], 'class' => [], 'title' => [], ], 'br' => [], 'em' => [], 'strong' => [], 'h1' => ['id' => [], 'class' => [], ], 'h2' => ['id' => [], 'class' => [], ], 'h3' => ['id' => [], 'class' => [], ], 'h4' => ['id' => [], 'class' => [], ], 'h5' => ['id' => [], 'class' => [], ], 'h6' => ['id' => [], 'class' => [], ], 'img' => ['id' => [], 'class' => [], 'src' => [], 'alt' => [], 'title' => [], ], ]);
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
	 *
	 * Create an instance of the loader which will be used to register the hooks with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
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
	private function set_i18n() {
		$plugin_i18n = new HOSTPN_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the main functionalities of the plugin, common to public and admin faces.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {
		$plugin_common = new HOSTPN_Common($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_common, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_common, 'enqueue_scripts');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_common, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_common, 'enqueue_scripts');
		$this->loader->add_filter('body_class', $plugin_common, 'hostpn_body_classes');

		$plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
		$this->loader->add_action('hostpn_form_save', $plugin_post_type_accommodation, 'hostpn_form_save', 5, 999);

		$plugin_post_type_part = new HOSTPN_Post_Type_Part();
		$this->loader->add_action('hostpn_form_save', $plugin_post_type_part, 'hostpn_form_save', 5, 999);

		$plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
		$this->loader->add_action('hostpn_form_save', $plugin_post_type_guest, 'hostpn_form_save', 5, 999);

		$plugin_user = new HOSTPN_Functions_User();
		$this->loader->add_filter('userswph_register_fields', $plugin_user, 'userswph_wph_register_fields', 10, 2);
		$this->loader->add_action('user_register', $plugin_user, 'hostpn_user_register', 11, 1);
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new HOSTPN_Admin($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new HOSTPN_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$plugin_user = new HOSTPN_Functions_User();
		$this->loader->add_action('wp_login', $plugin_user, 'hostpn_wp_login');
	}

	/**
	 * Register all Post Types with meta boxes and templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_post_types() {
		$plugin_post_type_part = new HOSTPN_Post_Type_Part();
		$this->loader->add_action('init', $plugin_post_type_part, 'register_post_type');
		$this->loader->add_action('admin_init', $plugin_post_type_part, 'add_meta_box');
		$this->loader->add_action('save_post_hostpn_part', $plugin_post_type_part, 'save_post', 10, 3);
		$this->loader->add_shortcode('hostpn-part-list', $plugin_post_type_part, 'list_wrapper');
	
		$plugin_post_type_accommodation = new HOSTPN_Post_Type_Accommodation();
		$this->loader->add_action('init', $plugin_post_type_accommodation, 'register_post_type');
		$this->loader->add_action('admin_init', $plugin_post_type_accommodation, 'add_meta_box');
		$this->loader->add_action('save_post_hostpn_accommodation', $plugin_post_type_accommodation, 'save_post', 10, 3);
		$this->loader->add_shortcode('hostpn-accommodation-list', $plugin_post_type_accommodation, 'list_wrapper');

		$plugin_post_type_guest = new HOSTPN_Post_Type_Guest();
		$this->loader->add_action('init', $plugin_post_type_guest, 'register_post_type');
		$this->loader->add_action('admin_init', $plugin_post_type_guest, 'add_meta_box');
		$this->loader->add_action('save_post_hostpn_guest', $plugin_post_type_guest, 'save_post', 10, 3);
		$this->loader->add_shortcode('hostpn-guest-list', $plugin_post_type_guest, 'list_wrapper');
	}

	/**
	 * Register all of the hooks related to Taxonomies.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_taxonomies() {
		$plugin_taxonomies_host = new HOSTPN_Taxonomies_Host();
		$this->loader->add_action('init', $plugin_taxonomies_host, 'register_taxonomies');
	}

	/**
	 * Load most common data used on the platform.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_data() {
		$plugin_data = new HOSTPN_Data();

		if (is_admin()) {
			$this->loader->add_action('init', $plugin_data, 'load_plugin_data');
		}else{
			$this->loader->add_action('wp_footer', $plugin_data, 'load_plugin_data');
		}

		$this->loader->add_action('wp_footer', $plugin_data, 'flush_rewrite_rules');
		$this->loader->add_action('admin_footer', $plugin_data, 'flush_rewrite_rules');
		
		$plugin_user = new HOSTPN_Functions_User();
		$this->loader->add_action('wp_footer', $plugin_user, 'hostpn_user_to_guest');
		$this->loader->add_action('admin_footer', $plugin_user, 'hostpn_user_to_guest');
	}

	/**
	 * Register templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_templates() {
		if (!defined('DOING_AJAX')) {
			$plugin_templates = new HOSTPN_Templates();
			$this->loader->add_action('wp_footer', $plugin_templates, 'load_plugin_templates');
			$this->loader->add_action('admin_footer', $plugin_templates, 'load_plugin_templates');
		}
	}

	/**
	 * Register settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_settings() {
		$plugin_settings = new HOSTPN_Settings();
		$this->loader->add_action('admin_menu', $plugin_settings, 'hostpn_admin_menu');
		$this->loader->add_filter('display_post_states', $plugin_settings, 'hostpn_display_post_state', 10, 2);
		$this->loader->add_action('activated_plugin', $plugin_settings, 'activated_plugin');
	}

	/**
	 * Load ajax functions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_ajax() {
		$plugin_ajax = new HOSTPN_Ajax();
		$this->loader->add_action('wp_ajax_hostpn_ajax', $plugin_ajax, 'hostpn_ajax_server');
	}

	/**
	 * Load no private ajax functions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_ajax_nopriv() {
		$plugin_ajax_nopriv = new HOSTPN_Ajax_Nopriv();
		$this->loader->add_action('wp_ajax_hostpn_ajax_nopriv', $plugin_ajax_nopriv, 'hostpn_ajax_nopriv_server');
		$this->loader->add_action('wp_ajax_nopriv_hostpn_ajax_nopriv', $plugin_ajax_nopriv, 'hostpn_ajax_nopriv_server');
	}

	/**
	 * Register shortcodes of the platform.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_shortcodes() {
		$plugin_shortcodes = new HOSTPN_Shortcodes();
		$this->loader->add_shortcode('hostpn-test', $plugin_shortcodes, 'hostpn_test');
		$this->loader->add_shortcode('hostpn-navigation', $plugin_shortcodes, 'hostpn_navigation');
		$this->loader->add_shortcode('hostpn-call-to-action', $plugin_shortcodes, 'hostpn_call_to_action');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress. Then it flushes the rewrite rules if needed.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    HOSTPN_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}