<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current version of the plugin.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */

class HOSTWPH {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      HOSTWPH_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if (defined('HOSTWPH_VERSION')) {
			$this->version = HOSTWPH_VERSION;
		} else {
			$this->version = '1.0.56';
		}

		$this->plugin_name = 'hostwph';

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
		define('HOSTWPH_DIR', plugin_dir_path(dirname(__FILE__)));
		define('HOSTWPH_URL', plugin_dir_url(dirname(__FILE__)));
		
		define('HOSTWPH_ROLE_CAPABILITIES', ['edit_post' => 'edit_wph_host', 'edit_posts' => 'edit_hostwph', 'edit_private_posts' => 'edit_private_hostwph', 'edit_published_posts' => 'edit_published_hostwph', 'edit_others_posts' => 'edit_other_hostwph', 'publish_posts' => 'publish_hostwph', 'read_post' => 'read_wph_host', 'read_private_posts' => 'read_private_hostwph', 'delete_post' => 'delete_wph_host', 'delete_posts' => 'delete_hostwph', 'delete_private_posts' => 'delete_private_hostwph', 'delete_published_posts' => 'delete_published_hostwph', 'delete_others_posts' => 'delete_others_hostwph', 'upload_files' => 'upload_files', 'manage_terms' => 'manage_hostwph_category', 'edit_terms' => 'edit_hostwph_category', 'delete_terms' => 'delete_hostwph_category', 'assign_terms' => 'assign_hostwph_category', 'manage_options' => 'manage_hostwph_options', ]);

		define('HOSTWPH_KSES', ['div' => ['id' => [], 'class' => [], 'data-hostwph-section-id' => [], ], 'span' => ['id' => [], 'class' => [], ], 'p' => ['id' => [], 'class' => [], ], 'ul' => ['id' => [], 'class' => [], ], 'ol' => ['id' => [], 'class' => [], ], 'li' => ['id' => [], 'class' => [], ], 'small' => ['id' => [], 'class' => [], ], 'a' => ['id' => [], 'class' => [], 'href' => [], 'title' => [], 'target' => [], ], 'form' => ['id' => [], 'class' => [], 'action' => [], 'method' => [], ], 'input' => ['name' => [], 'id' => [], 'class' => [], 'type' => [], 'checked' => [], 'multiple' => [], 'disabled' => [], 'value' => [], 'placeholder' => [], 'data-hostwph-parent' => [], 'data-hostwph-parent-option' => [], 'data-hostwph-parent-option' => [], 'data-hostwph-type' => [], 'data-hostwph-subtype' => [], 'data-hostwph-user-id' => [], 'data-hostwph-post-id' => [],], 'select' => ['name' => [], 'id' => [], 'class' => [], 'type' => [], 'checked' => [], 'multiple' => [], 'disabled' => [], 'value' => [], 'placeholder' => [], 'data-placeholder' => [], 'data-hostwph-parent' => [], 'data-hostwph-parent-option' => [], ], 'option' => ['name' => [], 'id' => [], 'class' => [], 'disabled' => [], 'selected' => [], 'value' => [], 'placeholder' => [], ], 'textarea' => ['name' => [], 'id' => [], 'class' => [], 'type' => [], 'multiple' => [], 'disabled' => [], 'value' => [], 'placeholder' => [], 'data-hostwph-parent' => [], 'data-hostwph-parent-option' => [], ], 'label' => ['id' => [], 'class' => [], 'for' => [], ], 'i' => ['id' => [], 'class' => [], 'title' => [], ], 'br' => [], 'em' => [], 'strong' => [], 'h1' => ['id' => [], 'class' => [], ], 'h2' => ['id' => [], 'class' => [], ], 'h3' => ['id' => [], 'class' => [], ], 'h4' => ['id' => [], 'class' => [], ], 'h5' => ['id' => [], 'class' => [], ], 'h6' => ['id' => [], 'class' => [], ], 'img' => ['id' => [], 'class' => [], 'src' => [], 'alt' => [], 'title' => [], ], ]);
	}
			
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 * - HOSTWPH_Loader. Orchestrates the hooks of the plugin.
	 * - HOSTWPH_i18n. Defines internationalization functionality.
	 * - HOSTWPH_Common. Defines hooks used accross both, admin and public side.
	 * - HOSTWPH_Admin. Defines all hooks for the admin area.
	 * - HOSTWPH_Public. Defines all hooks for the public side of the site.
	 * - HOSTWPH_Post_Type_Guest. Defines Guest custom post type.
	 * - HOSTWPH_Post_Type_Accomodation. Defines Accomodation custom post type.
	 * - HOSTWPH_Post_Type_Part. Defines Part custom post type.
	 * - HOSTWPH_Taxonomies_Host. Defines Guest taxonomies.
	 * - HOSTWPH_Templates. Load plugin templates.
	 * - HOSTWPH_Data. Load main usefull data.
	 * - HOSTWPH_Functions_Post. Posts management functions.
	 * - HOSTWPH_Functions_User. Users management functions.
	 * - HOSTWPH_Functions_Attachment. Attachments management functions.
	 * - HOSTWPH_Functions_Settings. Define settings.
	 * - HOSTWPH_Functions_Forms. Forms management functions.
	 * - HOSTWPH_Functions_Ajax. Ajax functions.
	 * - HOSTWPH_Functions_Ajax_Nopriv. Ajax No Private functions.
	 * - HOSTWPH_Functions_Shortcodes. Define all shortcodes for the platform.
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
		require_once HOSTWPH_DIR . 'includes/class-hostwph-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-i18n.php';

		/**
		 * The class responsible for defining all actions that occur both in the admin area and in the public-facing side of the site.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-common.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once HOSTWPH_DIR . 'includes/admin/class-hostwph-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 */
		require_once HOSTWPH_DIR . 'includes/public/class-hostwph-public.php';

		/**
		 * The class responsible for create the Accomodation custom post type.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-post-type-accomodation.php';

		/**
		 * The class responsible for create the Accomodation custom taxonomies.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-taxonomies-accomodation.php';

		/**
		 * The class responsible for create the Part of traveler custom post type.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-post-type-part.php';

		/**
		 * The class responsible for create the Part of traveler custom taxonomies.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-taxonomies-part.php';

		/**
		 * The class responsible for create the Guest custom post type.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-post-type-guest.php';

		/**
		 * The class responsible for create the Guest custom taxonomies.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-taxonomies-guest.php';

		/**
		 * The class responsible for plugin templates.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-templates.php';

		/**
		 * The class getting key data of the platform.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-data.php';

		/**
		 * The class defining posts management functions.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-functions-post.php';

		/**
		 * The class defining users management functions.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-functions-user.php';

		/**
		 * The class defining attahcments management functions.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-functions-attachment.php';

		/**
		 * The class defining settings.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-settings.php';

		/**
		 * The class defining form management.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-forms.php';

		/**
		 * The class defining ajax functions.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-ajax.php';

		/**
		 * The class defining no private ajax functions.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-ajax-nopriv.php';

		/**
		 * The class defining shortcodes.
		 */
		require_once HOSTWPH_DIR . 'includes/class-hostwph-shortcodes.php';

		$this->loader = new HOSTWPH_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the HOSTWPH_i18n class in order to set the domain and to register the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_i18n() {
		$plugin_i18n = new HOSTWPH_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

		if (class_exists('Polylang')) {
			$this->loader->add_filter('pll_get_post_types', $plugin_i18n, 'hostwph_pll_get_post_types', 10, 2);
    }
	}

	/**
	 * Register all of the hooks related to the main functionalities of the plugin, common to public and admin faces.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {
		$plugin_common = new HOSTWPH_Common($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_common, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_common, 'enqueue_scripts');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_common, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_common, 'enqueue_scripts');
		$this->loader->add_filter('body_class', $plugin_common, 'hostwph_body_classes');

		$plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
		$this->loader->add_action('hostwph_form_save', $plugin_post_type_accomodation, 'hostwph_form_save', 5, 999);

		$plugin_post_type_part = new HOSTWPH_Post_Type_Part();
		$this->loader->add_action('hostwph_form_save', $plugin_post_type_part, 'hostwph_form_save', 5, 999);

		$plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
		$this->loader->add_action('hostwph_form_save', $plugin_post_type_guest, 'hostwph_form_save', 5, 999);

		$plugin_user = new HOSTWPH_Functions_User();
		$this->loader->add_filter('userswph_register_fields', $plugin_user, 'userswph_wph_register_fields', 10, 2);
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new HOSTWPH_Admin($this->get_plugin_name(), $this->get_version());
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
		$plugin_public = new HOSTWPH_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$plugin_user = new HOSTWPH_Functions_User();
		$this->loader->add_action('wp_login', $plugin_user, 'hostwph_wp_login');
	}

	/**
	 * Register all Post Types with meta boxes and templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_post_types() {
		$plugin_post_type_part = new HOSTWPH_Post_Type_Part();
		$this->loader->add_action('init', $plugin_post_type_part, 'register_post_type');
		$this->loader->add_action('admin_init', $plugin_post_type_part, 'add_meta_box');
		$this->loader->add_action('save_post_hostwph_part', $plugin_post_type_part, 'save_post', 10, 3);
		$this->loader->add_shortcode('hostwph-part-list', $plugin_post_type_part, 'list_wrapper');
	
		$plugin_post_type_accomodation = new HOSTWPH_Post_Type_Accomodation();
		$this->loader->add_action('init', $plugin_post_type_accomodation, 'register_post_type');
		$this->loader->add_action('admin_init', $plugin_post_type_accomodation, 'add_meta_box');
		$this->loader->add_action('save_post_hostwph_accomodation', $plugin_post_type_accomodation, 'save_post', 10, 3);
		$this->loader->add_shortcode('hostwph-accomodation-list', $plugin_post_type_accomodation, 'list_wrapper');

		$plugin_post_type_guest = new HOSTWPH_Post_Type_Guest();
		$this->loader->add_action('init', $plugin_post_type_guest, 'register_post_type');
		$this->loader->add_action('admin_init', $plugin_post_type_guest, 'add_meta_box');
		$this->loader->add_action('save_post_hostwph_guest', $plugin_post_type_guest, 'save_post', 10, 3);
		$this->loader->add_shortcode('hostwph-guest-list', $plugin_post_type_guest, 'list_wrapper');
	}

	/**
	 * Register all of the hooks related to Taxonomies.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_taxonomies() {
		$plugin_taxonomies_host = new HOSTWPH_Taxonomies_Host();
		$this->loader->add_action('init', $plugin_taxonomies_host, 'register_taxonomies');
	}

	/**
	 * Load most common data used on the platform.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_data() {
		$plugin_data = new HOSTWPH_Data();

		if (is_admin()) {
			$this->loader->add_action('init', $plugin_data, 'load_plugin_data');
		}else{
			$this->loader->add_action('wp_footer', $plugin_data, 'load_plugin_data');
		}

		$this->loader->add_action('wp_footer', $plugin_data, 'flush_rewrite_rules');
		$this->loader->add_action('admin_footer', $plugin_data, 'flush_rewrite_rules');
	}

	/**
	 * Register templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_templates() {
		if (!defined('DOING_AJAX')) {
			$plugin_templates = new HOSTWPH_Templates();
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
		$plugin_settings = new HOSTWPH_Settings();
		$this->loader->add_action('admin_menu', $plugin_settings, 'hostwph_admin_menu');
		$this->loader->add_filter('display_post_states', $plugin_settings, 'hostwph_display_post_state', 10, 2);
		$this->loader->add_action('activated_plugin', $plugin_settings, 'activated_plugin');
	}

	/**
	 * Load ajax functions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_ajax() {
		$plugin_ajax = new HOSTWPH_Ajax();
		$this->loader->add_action('wp_ajax_hostwph_ajax', $plugin_ajax, 'hostwph_ajax_server');
	}

	/**
	 * Load no private ajax functions.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_ajax_nopriv() {
		$plugin_ajax_nopriv = new HOSTWPH_Ajax_Nopriv();
		$this->loader->add_action('wp_ajax_hostwph_ajax_nopriv', $plugin_ajax_nopriv, 'hostwph_ajax_nopriv_server');
		$this->loader->add_action('wp_ajax_nopriv_hostwph_ajax_nopriv', $plugin_ajax_nopriv, 'hostwph_ajax_nopriv_server');
	}

	/**
	 * Register shortcodes of the platform.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_shortcodes() {
		$plugin_shortcodes = new HOSTWPH_Shortcodes();
		$this->loader->add_shortcode('hostwph-accomodation', $plugin_shortcodes, 'hostwph_accomodation');
		$this->loader->add_shortcode('hostwph-part', $plugin_shortcodes, 'hostwph_part');
		$this->loader->add_shortcode('hostwph-guest', $plugin_shortcodes, 'hostwph_guest');
		$this->loader->add_shortcode('hostwph-navigation', $plugin_shortcodes, 'hostwph_navigation');
		$this->loader->add_shortcode('hostwph-call-to-action', $plugin_shortcodes, 'hostwph_call_to_action');
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
	 * @return    HOSTWPH_Loader    Orchestrates the hooks of the plugin.
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