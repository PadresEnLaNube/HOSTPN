<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin so that it is ready for translation.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'hostwph',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}

	public function hostwph_pll_get_post_types($post_types, $is_settings) {
    if ($is_settings){
      unset($post_types['hostwph_recipe']);
    }else{
      $post_types['hostwph_recipe'] = 'hostwph_recipe';
    }

    return $post_types;
  }

  public function hostwph_timestamp_server_gap() {
    $time = new DateTime(date('Y-m-d H:i:s', time()));
    $current_time = new DateTime(date('Y-m-d H:i:s', current_time('timestamp')));

    $interval = $current_time->diff($time);
    return ((($interval->invert) ? '-' : '+') . $interval->d . ' days ') . ((($interval->invert) ? '-' : '+') . $interval->h . ' hours ') . ((($interval->invert) ? '-' : '+') . $interval->i . ' minutes ') . ((($interval->invert) ? '-' : '+') . $interval->s . ' seconds');
  }

  public static function get_post($post_id) {
  	// BASEWPH_i18n::get_post($post_id);
  	if (class_exists('Polylang') && function_exists('pll_get_post') && function_exists('pll_current_language')) {
  		return pll_get_post($post_id, pll_current_language('slug'));
  	}else{
  		return $post_id;
  	}
  }

  public static function get_term($term_id) {
  	// BASEWPH_i18n::get_term($term_id);
  	if (class_exists('Polylang') && function_exists('pll_get_term') && function_exists('pll_current_language')) {
  		return pll_get_term($term_id, pll_current_language('slug'));
  	}else{
  		return $term_id;
  	}
  }
}