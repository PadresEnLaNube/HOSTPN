<?php
/**
 * Provide a common footer area view for the plugin
 *
 * This file is used to markup the common footer facing aspects of the plugin.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 *
 * @package    HOSTWPH
 * @subpackage HOSTWPH/common/templates
 */

  if (!defined('ABSPATH')) exit; // Exit if accessed directly

  $hostwph_data = $GLOBALS['hostwph_data'];

  echo do_shortcode('[hostwph-navigation]');
?>

<div id="hostwph-main-message" class="hostwph-main-message hostwph-display-none-soft hostwph-z-index-top" style="display:none;" data-user-id="<?php echo esc_attr($hostwph_data['user_id']); ?>" data-post-id="<?php echo esc_attr($hostwph_data['post_id']); ?>">
  <span id="hostwph-main-message-span"></span><i class="material-icons-outlined hostwph-vertical-align-bottom hostwph-ml-20 hostwph-cursor-pointer hostwph-color-white hostwph-close-icon">close</i>

  <div id="hostwph-bar-wrapper">
  	<div id="hostwph-bar"></div>
  </div>
</div>
