<?php
/**
 * Provide a common footer area view for the plugin
 *
 * This file is used to markup the common footer facing aspects of the plugin.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 *
 * @package    HOSTPN
 * @subpackage HOSTPN/common/templates
 */

  if (!defined('ABSPATH')) exit; // Exit if accessed directly

  $hostpn_data = $GLOBALS['hostpn_data'];

  echo do_shortcode('[hostpn-navigation]');
?>

<div id="hostpn-main-message" class="hostpn-main-message hostpn-display-none-soft hostpn-z-index-top" style="display:none;" data-user-id="<?php echo esc_attr($hostpn_data['user_id']); ?>" data-post-id="<?php echo esc_attr($hostpn_data['post_id']); ?>">
  <span id="hostpn-main-message-span"></span><i class="material-icons-outlined hostpn-vertical-align-bottom hostpn-ml-20 hostpn-cursor-pointer hostpn-color-white hostpn-close-icon">close</i>

  <div id="hostpn-bar-wrapper">
  	<div id="hostpn-bar"></div>
  </div>
</div>
